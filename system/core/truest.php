<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class truest{

    protected  $C  = NULL;

    public $post      = [];
    public $full_conf = [];
    public $id_table  = 0;
    public $fields    = [];
    public $data      = [];


    public function __construct() {
        $this->C = &get_instance();
    }

    public function check ($conf,$folder='',$mode='insert')
    {
        $folder = ($folder == '')?$conf:$folder;

        if (is_file(APPPATH."lang/lang_{$folder}".EXT))
        {
            require_once APPPATH."lang/lang_{$folder}".EXT;
        }

        if (is_file(APPPATH."helper/helper_{$folder}".EXT))
        {
            require_once APPPATH."helper/helper_{$folder}".EXT;
        }
        $this->C->load->config($conf,$folder.SLASH,array(),FALSE);
        
        list($field_name,$full_field,$filds) = $this->C->load->get_conf_fieldname($conf);
        
        $this->full_conf = $full_field;
        
        $field_nes = $this->C->load->get_conf_nes($conf);
        $this->post = $this->C->security->input('post', array());
        
        if ( count($field_nes) > 0 )
        {
            foreach ( $field_nes as $index=>$array_field )
            {
                if ( $index == 'conf_file')
                {
                    foreach ($array_field['ness'] as $value_file)
                    {
                        if (!isset($_SESSION['img'][$value_file]) || count($_SESSION['img'][$value_file]) == 0)
                        {
                            if ( !isset($_SESSION['temp_file'][$value_file]))
                            {
                                $error['error']["{$value_file}|empty"] = @constant('LANG_'.strtoupper($value_file)).' : '._MSG_EMPTY.' '._MSG_FIELD_FILE;
                            }
                        }
                    }
                   // pr($_SESSION);
                   // pr($error,1);
                    continue;
                }

                $name = $array_field['name'];

                if (isset($array_field['alias']))
                {
                    $caption = $array_field['alias'];
                }
                else
                {
                    $caption = $name;
                }

                if (strpos($name,'['))
                {
                    $a = explode('[',$name);

                    if ( !isset($this->post[$a[0]][str_replace(']','',$a[1])]) || empty($this->post[$a[0]][str_replace(']','',$a[1])]) )
                    {
                        if ( (isset($array_field['zero']) && $array_field['zero'] != TRUE) ||  !isset($array_field['zero']))
                        {
                            $error['error']["{$name}|empty"] = @constant('LANG_'.strtoupper($caption)).' : '._MSG_EMPTY;
                        }
                        else
                        {
                            $this->post[$a[0]][str_replace(']','',$a[1])] = 0;
                        }
                    }
                }
                else if ( !isset($this->post[$name]) || empty($this->post[$name]) )
                {
                    if ( (isset($array_field['zero']) && $array_field['zero'] != TRUE) ||  !isset($array_field['zero']))
                    {
                        $error['error']["{$name}|empty"] = @constant('LANG_'.strtoupper($caption)).' : '._MSG_EMPTY;
                    }
                    else
                    {
                        $this->post[$name] = 0;
                    }
                }
            }
        }
        $form_name = isset($this->full_conf['_conf']['name'])?$this->full_conf['_conf']['name']:$conf;
        unset($_SESSION['temp_file']);
        if ( isset($error) && count($error) > 0 )
        {
            get_error($error['error'],$form_name,LANG_EMPTY);
            redirect();
        }

        foreach ($filds as $index=>$array_field )
        {
            if (isset($array_field['func']) && $array_field['func'] != '')
            {
                if ( isset($array_field['func']['param']) )
                {
                    if (strpos($array_field['name'],'['))
                    {
                        $a = explode('[',$array_field['name']);
                        $valu_post = $this->post[$a[0]][str_replace(']','',$a[1])];
                    }
                    else
                    {
                        $valu_post = $this->post[$array_field['name']];
                    }
                    array_unshift($array_field['func']['param'],$valu_post);
                }
                else
                {
                    if (strpos($array_field['name'],'['))
                    {
                        $a = explode('[',$array_field['name']);
                        $valu_post = $this->post[$a[0]][str_replace(']','',$a[1])];
                    }
                    else
                    {
                        $valu_post = $this->post[$array_field['name']];
                    }
                    $array_field['func']['param']['value'] = $valu_post;
                }

                $res = call_user_func_array($array_field['func']['fn'], $array_field['func']['param']);

                if ( $res['res'] == FALSE )
                {
                    $error[$array_field['name']] = $res['msg'];
                    unset($_SESSION['post'][$array_field['name']]);
                }
                else
                {
                    if (strpos($array_field['name'],'['))
                    {
                        $a = explode('[',$array_field['name']);
                        $this->post[$a[0]][str_replace(']','',$a[1])] = $res['msg'];
                    }
                    else
                    {
                        $this->post[$array_field['name']] = $res['msg'];
                    }
                }

            }
        }

        if ( isset($error) && count($error) > 0 )
        {
            get_error($error,$form_name,LANG_FAIL_VALIDATION);
            redirect();
        }

        switch ($mode)
        {
            case 'insert':$sql = "INSERT INTO ";break;
            case 'update':$sql = "UPDATE ";break;
        }

        if (isset($full_field[$mode]) )
        {
            foreach ( $full_field[$mode]['table'] as $table )
            {

                if ( isset($full_field[$mode]['data_type'][$table]) && $full_field[$mode]['data_type'][$table] == 'multi')
                {
                    $array_sql[$table] = $this->multi_query_builder($full_field[$mode],$table,$mode);
                    continue;
                }

                if ( !isset($full_field[$mode]['field']) OR count($full_field[$mode]['field']) == 0)
                {
                    $array_sql[$table] = "$sql `$table` SET ".$this->set_fields($field_name,$mode);
                }
                else
                {
                    $array_sql[$table] = "$sql `$table` SET " . $this->set_fields($full_field[$mode]['field'][$table],$mode);
                }

                if ( $mode == 'update' && !isset($full_field[$mode]['where'][$table]))
                {
                    exit("sql is fail. error 1");
                }

                if ( $mode == 'update' )
                {
                    $where = $full_field[$mode]['where'][$table];
                    $k = 1;
                    foreach ( $where as $tb_field=>$index_post )
                    {
                        if ( $k == 1 )
                        {
                            $this->id_table = isset($this->post[$index_post])?$this->post[$index_post]:$index_post;
                        }

                        $value_field = isset($this->post[$index_post])?$this->post[$index_post]:$index_post;

                        if ( !empty($value_field) )
                        {
                            $w[$index_post] = "`{$tb_field}`="."'{$value_field}'";
                            $k++;
                        }
                        else
                        {
                            $notset_where[$index_post] = _MSG_your_request_could_not_be_processed;
                        }
                    }

                    if (isset($notset_where))
                    {
                        get_error($notset_where,$form_name,_MSG_your_request_could_not_be_processed);
                        unset($_SESSION['post']);
                        redirect();
                    }

                    $array_sql[$table] .= " WHERE ".implode(' AND ',$w);
                    $w = [];
                }

            }
            return $array_sql;
        }

        return $this->set_fields($field_name,$mode);

    }

    public function multi_query_builder ($schema,$table,$mode)
    {

        $real_table_name = (isset($schema['alias'][$table]))?$schema['alias'][$table]:$table;
        $this->fields  = $schema['field'][$table];

        if ( $mode == 'insert' )
        {
            $sql = "INSERT INTO $real_table_name ";
            $i = 0;
            foreach ( $this->fields as $field=>$type )
            {
                $field_name[] = "tp_{$field}";
                $field_list[] = $field;
                $field_list_key[$field] = $field;

                if ( $i==0 && $type[1] != 'array' && $type[0] != 'post')
                {
                    die("truest => [{$real_table_name}][{$field}] : Must be the first array field !");
                }
                else if ($i == 0)
                {
                    $loop = $field;
                }
                $i++;
            }

            $this->data   = array_intersect_key($this->post, $field_list_key);
            $this->data   = array_merge(array_flip($field_list),$this->data);
            $temp   = [];
            $values = [];
            $i      = 1;
            $len    = count($field_list);
            $lens   = count($data[$loop]);

            for ( $k=0; $k<$lens; $i++ )
            {
                foreach ($this->data as $column=>$value)
                {

                    $temp[] = $this->analyze_data($column,$value);

                    if ($i%$len == 0 )
                    {
                        $values[] = "(".implode(',',$temp).")";
                        $temp = [];
                    }
                    $i++;
                }
            }

            $sql .= "(".implode(',',$field_name).") VALUES ".implode(',', $values);
            return $sql;
        }//insert


    }


    public function analyze_data ($column,$value)
    {

        /*
         *  'rid'             => ['post','var'],
            'sood_sarresid'   => ['post','array'],
            'takhir'          => ['session','uid'],
            'number_tashilat' => ['index','takhir'],
            'nerkh_sood'      => ['var','3'],
            'nerkh_sood'      => ['ai','table_name']
         */

        switch($this->fields[$column][0])
        {
            case 'post':
                if ($this->fields[$column][1] == 'var')
                {
                    return $this->data[$column];
                }
                $first_key = key($value);
                $val = $value[$first_key];
                unset($this->data[$column][$first_key]);
                return $val;
            break;

            case 'session':
                return (isset($_SESSION[$this->fields[$column][1]]))?$_SESSION[$this->fields[$column][1]]:0;
            break;

            case 'index':
                return key($this->data[$this->fields[$column][1]]);
            break;

            case 'var':
                return $this->fields[$column][1];
            break;

            case 'ai':
                if ( !isset($_SESSION['insert_id_temp'][$this->fields[$column][1]]) )
                {
                    die("truest:[ai:error]:{}");
                }
                return $_SESSION['insert_id_temp'][$this->fields[$column][1]];
            break;

            default :
                die("truest:[{$column}]:undefined type of {$column}");
            break;
        }
    }



    public function set_fields ($full_field ,$mode='')
    {
        $arr = array();
        foreach ($full_field as $name)
        {
            if (isset($this->post[$name])  AND ! is_array($this->post[$name])) //AND $this->post[$name] != ''
            {
                $arr[$name] = " `tp_{$name}`='" . $this->post[$name] . "'";
            }

            if ( strpos($name,':') !== FALSE )
            {
                $fields = explode(':',$name);/*
                $value  = (isset($this->post[$fields[0]]) && $this->post[$fields[0]] != '')?$this->post[$fields[0]]:@$this->post[$fields[1]];
                $arr[$fields[0]] = " `tp_{$fields[0]}`= '".$value. "'";*/
                $value  = (isset($this->post[$fields[1]]) && $this->post[$fields[1]] != '')?$this->post[$fields[1]]:@$this->post[$fields[2]];
                $arr[$fields[0]] = " `tp_{$fields[0]}`= '".$value. "'";
            }

            if ( strpos($name,'@') !== FALSE )
            {
                $fields = explode('@',$name);
                $arr[$fields[0]] = " `tp_{$fields[0]}`= '".$fields[1]. "'";
            }
        }
        switch ($mode)
        {
            case 'insert':
                if ( !in_array('uid',$full_field))
                {
                    $arr['uid'] = " `tp_uid` = '".$this->C->session->is_session('uid')."'";
                }
                $arr['date'] = " `tp_date` = now()";
            break;
            case 'update':
                $arr['update'] = " `tp_update` = now()";
            break;
            default:break;
        }
        return implode(",", $arr);
    }
}
