<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once DIR_HELPER.'helper_html.php';

class html {
	
    private $form_item = array ();

    private $items = [];
    
    var $vars = array();
    
    var $form = '';
    
    public $not_item_form = array('form','comment','!','insert','update','conf_file','add_field','_conf');
    
    public $not_item_input = array('form','comment','!','inf','submit', 'insert', 'update','empty_field');
    
    var $set_name = FALSE;
    
    var $change_nes = [];
    
    public $grid_option = [
        'auto_order'
    ];
    
    function analayz_form ($item=array())
    {
        $this->items = $item;
        $this->change_nes = array();
        foreach ( $item as $name=>$attr) 
        {
            if ( !in_array($name,$this->not_item_form) )
            {
                if ( isset($attr['item']) && !in_array($attr['item'],$this->not_item_form) )
                {
                    if ( isset($attr['change_nes']))
                    {
                        $check = $attr['change_nes'][0];
                        $check_value = $attr['change_nes'][1];

                        if ( isset($item[$check]['value']))
                        {
                            $val = $item[$check]['value'];
                        }
                        else 
                        {
                            $val = isset($item[$check]['checked'])?$item[$check]['checked']:$item[$check]['selected'];
                        }
                        if ( (isset($_SESSION['post'][$check]) && in_array($_SESSION['post'][$check], $check_value) ) ||( $val == $check_value)  )
                        { 
                            $attr['add'] = 'show';
                        }
                    }
                    
                    if ( isset($attr['loop']) && is_array($attr['loop']))
                    {
                        if ( count($attr['loop']) > 0 )
                        {
                            foreach ( $attr['loop'] as $index_loop=>$value_loop )
                            {
                                $attr['name']  = $attr['name']."[{$index_loop}]";
                                $attr['value'] = $value_loop;
                                $this->form_item[$name.$index_loop] = $this->$attr['item']($attr);
                            }
                        }
                        else
                        {
                            $attr['name']  = $attr['name']."[]";
                            $this->form_item[$name.$index_loop] = $this->$attr['item']($attr);
                        }
                    }
                    else
                    {
                        $this->form_item[$name] = $this->{$attr['item']}($attr);
                    }
                }
            }
        }
        if ( isset( $item['form'] ) )
        {
            $this->form ($item['form']);
        }
        else
        {
            if ( $this->set_name === TRUE )
            {
                return $this->set_name_field();
            }
            return $this->form_item;
        }
    }
    
    function set_name_field ()
    {
        $arr = array();
        foreach ( $this->form_item as $name=>$input )
        {
            if ( isset($this->items[$name]['alias']) AND !empty($this->items[$name]['alias']))
            {
                $caption = $this->items[$name]['alias'];
            }
            else
            {
                $caption = $name;
            }
            if ( isset( $this->items[$name]['ness'] )  AND $this->items[$name]['ness'] == TRUE )
            {
                $nes = PATTERN_RED_STAR;
            }
            else 
            {
                $nes = '';
            }
            
            $arr[$name] = array(
                'field' => $input,
                'title' => '<span class="_i _setting" aria-name="'.$name.'"></span>'.$nes.'<span class="label_'.$name.'">'.@constant('LANG_'.strtoupper($caption)).'</span>'
            );
        }
        return $arr;
    }
    
    function inf ($attr)
    {
       $pattern = isset($attr['pattern'])?$attr['pattern']:PATTERN_INF;
       return $this->assign_attr($pattern,$attr); 
    }
    
    
    function form ($form)
    {
        $tr = ''; $td = '';$j=$i=1;$table='';
        $this->form = '';
        $content = '';
        
        $len = isset($this->items['form']['len'])?$this->items['form']['len']:2;
        
        foreach ( $this->form_item as $name=>$input )
        {
            
            if ( isset($this->items[$name]['alias']) AND !empty($this->items[$name]['alias']))
            {
                $caption = $this->items[$name]['alias'];
            }
            else
            {
                $caption = $name;
            }
            
            if ($this->items[$name]['type'] != 'hidden')
            {
                switch ($this->items[$name]['item'])
                {
                    case 'inf':
                         if ( isset($form['res']) AND  $form['res'] == TRUE ) $colapse = 3;else $colapse = 2;
                          $tr .= '<tr class="tr_'.$i.' rf_'.$name.' ri_'.$this->items[$name]['item'].'"><td class="Inf_'.$name.' caption_'.$name.'" colspan="'.$colapse.'">'.$input.'</td></tr>';
                    break;
                    case 'submit':
                         if ( isset($form['res']) AND  $form['res'] == TRUE ) $colapse = 3;else $colapse = 2;
                          $tr .= '<tr class="tr_'.$i.' rf_'.$name.' ri_'.$this->items[$name]['item'].'"><td class="Inf_'.$name.' caption_'.$name.'" colspan="'.$colapse.'"><div class="pos_btn">'.$input.'</div></td></tr>';
                    break;
                    case 'btn':
                         if ( isset($form['res']) AND  $form['res'] == TRUE ) $colapse = 3;else $colapse = 2;
                          $tr .= '<tr class="tr_'.$i.' rf_'.$name.' ri_'.$this->items[$name]['item'].'"><td class="Inf_'.$name.' caption_'.$name.'" colspan="'.$colapse.'"><div class="pos_btn">'.$input.'</div></td></tr>';
                    break;
                    default : 
                        if ( isset($form['res']) AND  $form['res'] == TRUE ) $td = '<td class="res_'.$name.' res_filed">&nbsp;</td>';
                        if ( isset( $this->items[$name]['ness'] )  AND $this->items[$name]['ness'] == TRUE )
                            $nes = PATTERN_RED_STAR;
                        else 
                            $nes = '';
                        
                        if ( isset( $this->items[$name]['prefix'] )  AND $this->items[$name]['prefix'] != '' )
                        {
                            $perfix = '<span class="prefix_caption prefix_label">'.$this->items[$name]['prefix'].'</span>';
                        }
                        else
                        {
                            $perfix = '';
                        }
                        
                        $tr .= '<tr class="tr_'.$i.' rf_'.$name.'"><td class="pos_caption caption_'.$name.'"><span class="_i _setting" aria-name="'.$name.'"></span>'
                        .$perfix.$nes.'<span class="label_'.$name.'">'.@constant('LANG_'.strtoupper($caption)).'</span></td><td class="pos_inp">'.$input.'</td>'.$td.'</tr>';
                    break;
                }
                $i++;
            }
            else
            {
                $table .=$input;
            }
        }
       $cls = isset($form['table'])?$form['table']:'table table-striped def_t_style';
        $style = isset($form['style'])?$form['style']:'';
        $table .= '<table class="'.$cls.' '.$style.'">'.$tr.'</table>';
        $form['method'] = isset($form['method'])?$form['method']:'POST';
        $form['content'] = $table;
        $this->form = $this->assign_attr(PATTERN_FORM,$form);
        $this->form_item = array();$this->items=array();
        unset ($form);
    }

    
    function generate_form ($item=array())
    {
        $this->items = $item;
        $form = array();
        if ( isset($this->items['form']) )
        {
            $form = $this->items['form'];
        }
        foreach ( $item as $name=>$attr) 
        {
            if ( !in_array($name,  $this->not_item_form) )
            {
                if ( isset($attr['change_nes']))
                {
                    $check = $attr['change_nes'][0];
                    $check_value = $attr['change_nes'][1];
                    if ( isset($item[$check]['value'])) $val = $item[$check]['value'];
                    else $val = $item[$check]['checked'];
                    if ( (isset($_SESSION['post'][$check]) && $_SESSION['post'][$check] == $check_value) ||
                          ( $val == $check_value)  ) $attr['add'] = 'show';
                }
                $this->form_item[$name] = $this->$attr['item']($attr);
            }
        }
        
        $tr = ''; $td = '';$j=$i=1;$table='';
        $this->form = '';
        $content = '';
        $_td = array();
        $len = isset($this->items['form']['len'])?$this->items['form']['len']:2;
        
        foreach ( $this->form_item as $name=>$input )
        {
            
            if ( isset($this->items[$name]['alias']) AND !empty($this->items[$name]['alias']))
            {
                $caption = $this->items[$name]['alias'];
            }
            else
            {
                $caption = $name;
            }
            
            if ($this->items[$name]['type'] != 'hidden')
            {
                switch ($this->items[$name]['item'])
                {
                    case 'inf':
                        $tr .= '<tr class="tr_'.$i.' rf_'.$name.'"><td class="Inf_'.$name.' caption_'.$name.'" colspan="'.$len.'">'.$input.'</td></tr>';
                    break;
                    default : 
                        $_td [] ='<td class="pos_lable lable_'.$name.'"><span class="label_'.$name.'">'.@constant('LANG_'.strtoupper($caption)).'</span></td>';
                        $_td [] ='<td class="pos_inp_srch">'.$input.'</td>';
                    break;
                }
                $i++;
            }
            else
            {
                $table .=$input;
            }
        }
        foreach ( array_chunk($_td, $len) as $array_td)
        {
            $tr .='<tr>'. implode('', $array_td).'</tr>';
        }
        
        $cls = isset($form['table'])?$form['table']:'table table-striped def_t_style';
        $table .= '<table class="'.$cls.'">'.$tr.'</table>';
        $form['content'] = $table;
        $this->form = $this->assign_attr(PATTERN_FORM,$form);
        unset ($this->form_item,$this->items,$form);
    }
    
    

    function assign_attr ($pattern,$attr)
    {
        preg_match_all('/\{(.*?)\}/',$pattern,$match);
        foreach ( $match[1] as $index=>$key )
        {
            if ( is_array($attr) )
            {
                $replace = isset( $attr[$key]) ? $attr[$key] : "";
            }
            else
            {
                $replace = $attr;
            }
            if (is_array($replace))
            {
                unset($replace);$replace = '';
            }
            $pattern = str_replace('{'.$key.'}',$replace,$pattern);
        }
        return $pattern;		
    }



    function select ($attr=array ()) 
    {
        $data   = isset($attr['caption'])?$attr['caption']:'';
        $option = '';
        $zero = FALSE;
        if ( $data != '' AND ! is_array ($data) )
        {
            $option = '<option value="0">'.LANG_SELECT.'</option>';
            if (strpos($data,'-') !== FALSE)
            {
                list($start,$end) = explode ('-',$data);
                unset($data);
                $data = array();
                for ($i=(int)$start;$i<=(int)$end;$i++)
                {
                    $data[$i] = (isset($attr['lang']) && $attr['lang'] == 'fa')? fa_int($i):$i;
                }
            }
            elseif (strpos($data,'<') !== FALSE) {
                list($start,$end) = explode ('<',$data);
                unset($data);
                $data = array();
                for ($i=(int)$end;$i>=(int)$start;$i--)
                {
                    $data[$i] = (isset($attr['lang']) && $attr['lang'] == 'fa')? fa_int($i):$i;
                }
            }else
            {
                $data = explode (',',$data);
                $zero = TRUE;
            }
        }

        if ( ! isset ($attr['selected']) ) $attr['selected'] = -1;
        if (isset ($attr['attr']) AND $attr['attr'] == -1 ) $option = '';
        $per = isset ($attr['per'])? $attr['per']:'';
        if ( is_array ( $data ) )
        {
            foreach ( $data as $value=>$text )
            {
                if ( $zero === TRUE ) $value++;
                if ( $value == $attr['selected'] ) $index = SELECTED; else $index = '';
                $option .= '<option value=\''.$value.'\' '.$index.' >'.$text.$per.'</option>';
            }
        }

        $attr['option'] = $option;
        /*
        if ( count($caption) > 1 )
        {
           $attr['name'] = "{$name}[{$value}]";
        }
        else
        {
           $attr['name'] = $name;
        }
         * 
         */

        return $this->assign_attr(PATTERN_SELECT,$attr);
    }
    
    
    function input ($attr)
    {
        return $this->assign_attr(PATTERN_INPUT,$attr);
    }
    
    function tel ($attr)
    {
        return $this->assign_attr(PATTERN_TEL,$attr);
    }
    
    
    function file ($attr)
    {
        return $this->assign_attr(PATTERN_INPUT_FILE,$attr);
    }
    
    
    function submit ($attr)
    {
        return $this->assign_attr(PATTERN_SUBMIT,$attr);
    }    
    
    function empty_field ($attr)
    {
        return '&nbsp;';
    }
    
    function radio ($attr)
    {
        $li = '';
        $zero = FALSE;
        if ( ! is_array ( $attr['caption'] ) ) { $caption = explode ( ',',$attr['caption'] ); $zero = TRUE;}
        else $caption = $attr['caption'];

        $i = 1;
        foreach ( $caption as $value=>$title )
        {
            if ( $zero === TRUE ) $value++;
            $attr['i'] = $i;
            $attr['value'] = $value;
            $attr['title'] = $title;
            if ( $value == $attr['checked'] ) $attr['_checked'] = CHECKED; else $attr['_checked'] = '';
            $attr['input'] = $this->assign_attr(PATTERN_CHECKBOX,$attr);
            $li .= $this->assign_attr(PATTERN_RADIO_POS,$attr);
            unset($attr['input'],$attr['title']);
            $i++;
        }
        $conf['li'] = $li;
        return $this->assign_attr(PATTERN_LIST_INPUT,$conf);
    }
    
    
    function checkbox ($attr)
    {
        $li = '';
        $zero = FALSE;
        if ( ! is_array ( $attr['caption'] ) ) { $caption = explode ( ',',$attr['caption'] ); $zero = TRUE;}
        else $caption = $attr['caption'];
        $name = $attr['name'];
        unset($attr['name']);
        
        $i = 1;
        foreach ( $caption as $value=>$title )
        {
            if ( $zero === TRUE ) $value++;

            if ( count($caption) > 1 )
            {
               $attr['name'] = "{$name}[{$value}]";
            }
            else
            {
               $attr['name'] = $name;
            }

            $attr['i'] = $i;
            $attr['value'] = $value;
            $attr['title'] = $title;
            if (is_array($attr['checked']) && in_array($value,$attr['checked']))
            {
                $attr['_checked'] = CHECKED;
            }
            else if ( $value == $attr['checked'] ) 
            {
                $attr['_checked'] = CHECKED;
            }
            else 
            {
                $attr['_checked'] = '';
            }
            $attr['input'] = $this->assign_attr(PATTERN_CHECKBOX,$attr);
            $li .= $this->assign_attr(PATTERN_RADIO_POS,$attr);
            unset($attr['input'],$attr['title']);
            $i++;
        }
        $conf['li'] = $li;
        $conf['class'] = (isset($attr['class']))?$attr['class']:'';
        return $this->assign_attr(PATTERN_LIST_INPUT,$conf);
    }
    
    
    
    function textarea ($attr)
    {
        return $this->assign_attr(PATTERN_TEXTAREA,$attr);
    }
    
    
    function captcha ($attr)
    {
        return $this->assign_attr(PATTERN_CAPTCHA,$attr);
    }
    
    function pdate ($attr)
    {
        return $this->assign_attr(PATTERN_PDATE,$attr);
    }
    
    
    //sort
    function grid ($config)
    {
        $style = 'default_grid';

        if ( $config->count == 0 )
        {
            $msg = _MSG_EMPTY_DATA; 
            if ( isset($config->search) && count($config->search['field']) > 0)
            { 
               $msg .= '<a href="'.go_back_search().'" title="'.LANG_BACK.'" class="btn btn-success back_srch"><i class="fa fa-arrow-left"></i> '.LANG_BACK.'</a>';
            }
            $len_field =  $config->count;
            $thead = '<thead><tr class="th_caption"><th colspan="'.$len_field.'"><div>'.$config->name.'</div></th></tr></thead>';
            $tbody = '<tbody><tr><td><div class="alert alert-warning">'.$msg.'</div></td></tr></tbody>';
        }
        else 
        {
            if ( isset ($config->field) )
            {
                $field = $config->field;
            }
            else
            {
                foreach ( $config->result[0] as $f=>$v)
                {
                    $field[] = $f;
                }
            }
            
            if (!isset($config->table))
            {
                $config->table = '';
            }
            
            $len_field = count ($field)+1;
            
            if ( isset ($config->style) )
            {
                $style = $config->style;
            }
            
            $th    = '<th class="th_sharp">'.LANG_SHARP.'</th>';
           
            
            foreach ( $field as $k=>$name )
            {
                $th .= '<th class="th_'.$name.'">'.@constant('LANG_'.strtoupper($name)).'</th>';
            }
            
            
            if ( isset($config->other_field))
            {
                foreach ($config->other_field as $name=>$value)
                {
                    $th .= '<th class="th_'.$name.' center">'.$value['caption'].'</th>';
                    $len_field++;  
                }
            }
            
            
            $thead = '<thead><tr class="th_caption"><th colspan="'.$len_field.'"><div>'.$config->name.'</div></th></tr></thead>';
            
            if ( isset($config->search) && count($config->search['field']) > 0)
            {
                $len_field_search = 0;
                
                $lang = isset($config->search['lang'])?$config->search['lang']:LANG_SEARCH;
                
                foreach ( $config->search['field'] as $name=>$attr) 
                {
                    if ( !in_array($name,  $this->not_item_form) )
                    {
                        $len_field_search++;
                    }
                }
                
                
                if ( $len_field_search <  $config->search['count_row'] )
                {
                    $diff = ((int)$config->search['count_row'] - $len_field_search);
                    $k = 0;
                    while ($k < $diff)
                    {
                        $config->search['field']['field_'.$k] = array(
                          'item'  => 'empty_field',
                          'type'  => 'empty_field',
                          'name'  => 'empty_field'
                        );$k++;
                    }
                    $len_field_search = $config->search['count_row'];
                }
                
                $diff = $config->search['count_row']-(floor($len_field_search % $config->search['count_row']));
                
                if ($diff != $config->search['count_row'])
                {
                    $k = 0;
                    while ($k < $diff)
                    {
                        $config->search['field']['field_'.$k] = array(
                          'item'  => 'empty_field',
                          'type'  => 'empty_field',
                          'name'  => 'empty_field'
                        );$k++;
                    }
                }       
                $this->generate_form($config->search['field']);
                //set_name_field
                $thead .= '<thead><tr class="th_search"><td colspan="'.$len_field.'"><div class="header_filter_grid"><i class="fa fa-filter"></i><span class="lang_filter" onclick="Toggle(\'form_filter_grid\')">'.$lang.'</span></div><div class="form_filter_grid">'.$this->form.'</div></td></tr></thead>';
            }
                        
            $thead .= '<thead><tr class="th_row">'.$th.'</tr></thead>';
            $tr ='';
            $url = '';

            foreach ( $config->result as $index=>$array_fetch )
            {
                $td = '';
                $td .= '<td class="">'.fa_int($config->result[$index]['sharp']).'</td>';
                foreach ( $field as $k=>$name )
                {
                    $class = '';
                    if ( isset($config->func[$name]) )
                    {
                        $param = array();
                        $param['value'] = $array_fetch[$name];
                        
                        if( isset($config->param_func[$name]) )
                        {
                            $param_func =  $config->param_func[$name];
                            if ( in_array('this_record',$param_func,true))
                            {
                                $param['value'] = $array_fetch;
                                $key_record = array_search('this_record',$param_func);
                                unset($param_func[$key_record]);
                            }
                            $param = array_merge($param,$param_func);
                        }
                        $value = call_user_func_array($config->func[$name],$param);
                    }
                    elseif (in_array($name,$this->grid_option))
                    {
                        $value = call_user_func_array('grid_'.$name,$array_fetch);
                        $class = 'center';
                    }
                    else
                    {
                        $value = $array_fetch[$name];
                    }

                    $td .= '<td class="'.$class.'">'.$value.'</td>';
                }
                
                if ( isset ($config->url['id']) )
                {
                    foreach ($config->url['id'] as $value)
                    {
                      $i[] = lock_string($array_fetch[$value],TRUE);
                    }
                    $url = SLASH.implode('/',$i);
                    unset($i);
                }
                
                if (isset($config->url['mode'])) 
                {
                    $mode_row = 'mode_row'.$array_fetch[$config->url['mode']];
                }
                else
                {
                    $mode_row = "";
                }
                if ( isset($config->other_field))
                {
                    foreach ($config->other_field as $name=>$value)
                    {
                        if (isset($value['attr'])) $attr_other = $value['attr'];else $attr_other = '';
                        if (isset($value['class'])) $class = $value['class'];else $class = '';
                        if (isset($value['query_string'])) $query_string = $value['query_string'];else $query_string = '';
                        if (isset($value['json'])) $json = 'json=\''.json_encode($config->result[$index]).'\'';else $json = '';
                        $td .= '<td class="center"><a href="'.$value['url'].$url.$query_string.'" class="link_'.$name.' '.$class.'" '.$attr_other.' '.$json.'>'.$value['text'].'</a></th>';
                    }
                }

           
                $tr .= '<tr aria-order="'.lock_string(@$config->result[$index]['id'],true).'_'.lock_string(@$config->table,true).'" aria-sharp="'.$config->result[$index]['sharp'].'" class="row_grid tr_'.$config->result[$index]['sharp'].' '.$mode_row.'">'.$td.'</tr>';
            }

            if( isset( $config->pager ) )
            {
                $tr .= '<tr class="tfooter"><td class="td_pager" colspan="'.$len_field.'">'.$config->pager.'</td></tr>';
            }
            $tbody = '<tbody>'.$tr.'</tbody>';
        }

        $grid = '<div class="table-responsive" id="table-main">
                    <table class="table">
                    '.$thead.$tbody.'
                    </table>
                </div>
                ';
        unset($config);
        return $grid;
    }
    
    
    function only_view_vertical ($conf)
    {
        $td = '';
        $i = 1;
        if (isset($conf->name_view)){
            $td = '<thead><tr class="th_caption"><th colspan="2"><div>'.$conf->name_view.'</div></th></tr></thead>';
        }
        foreach ($conf->field AS $name)
        {
            if (isset($conf->result[0][$name]))
            {
                $value = '';
                if ( isset($conf->func[$name]) )
                {
                    $param = array();
                    $param['value'] = $conf->result[0][$name];
                    if ( isset($conf->param_func[$name]) )
                    {
                        $param = array_merge($param,$conf->param_func[$name]);
                    }
                    $value = call_user_func_array($conf->func[$name],$param);
                }
                else
                {
                    $value = $conf->result[0][$name];
                }
                $i++;
                $td .= '<tr class="V_tr_'.$i.' tr_'.$name.'"><td class="td_title td_'.$name.'">'.@constant('LANG_'.strtoupper($name)).'</td><td class="td_value">'.$value.'</td></tr>';  
            }
        }
        
        $style = 'table table-striped';

        if ( isset ($conf->style_view) )
        {
            $style = $conf->style_view;
        }
        
        $grid = '<table class="'.$style.'">'.$td.'</table>';
        unset($config);
        return $grid;
    }
    
    
    function step ($name=array(),$info=array(),$title=array(),$current=1,$step_style='def_step')
    {
        $len_step = count($name);
        
        if ( count($title) == 0 )
        {
            $title = $name;
        }
        $counter = 1;
        $li = '';
        foreach ( $name as $index=>$vname )
        {
            if ( $counter < $current ) $class = 'done';
            if ( $counter == ($current-1) ) $class = 'done_last';
            if ( $counter == $current ) $class = 'step_current';
            if ( $counter > $counter ) $class = '';
            if ( $counter == $len_step ) $class = 'last';
            if ( $current == $len_step AND $counter == $current) $class = 'step_current_last';
            
            $li .= '<li class="step_'.$counter.' '.$class.'" title="'.$title[$index].'">
                        <div class="step_name">'.$vname.'</div>
                        <div class="step_info">'.$a = isset($info[$index])?$info[$index]:''.'</div>
                    </li>';
            $counter++;
            $class = '';
        }
        return '<ul class="step '.$step_style.'">'.$li.'</ul>';
    }
    
    
    function tabs ($name=array(),$info=array(),$current=1,$style='tabs')
    {
        $code = random_code(3);
        $i = 1; $li = '';
        foreach ($name as $item=>$tname)
        {
            $li .= '<li class=""><span class="" aria-tab="'.$i.'" title="'.$tname.'">'.$tname.'</span></li>';
            $i++;
        }
        $ul = '<ul class="'.$style.' tab_'.$code.'">'.$li.'</ul>';
        $div = '';
        $i = 1;
        foreach ($info as $key=>$inf )
        {
            $div .='<div id="ict_+'.$i.'">'.$inf.'</div>';
            $i++;
        }
        $div = '<div class="content_tab"><div class="tab_padding">'.show_msg().$div.'</div></div>';
        $js = '<script>
                (function(){
                    $(".tab_'.$code.'").tab({
                        active:'.$current.'
                    });
                })();
            </script>';
        return $ul.$div.$js;
    }
    
    function tab_links ($name=array(),$content,$active='',$style='')
    {
        $li = '';
        $counter = 0;
        foreach ( $name as $url=>$tab_name )
        {
            $counter++;
            $link  = explode('/',$url);
            $class = (in_array($active, $link)) ? 'active show':'';
            $href  = ($url[0] == '#')?'#':trim(CURRENT_URL.$url);
            $li   .= ' 
                    <li class="nav-item hide">
                        <a class="nav-link '.$class.'" id="base-tab'.$counter.'"  aria-controls="tab'.$counter.'" href="'.$href.'" aria-expanded="false"> '.$tab_name.'</a>
                    </li>
                    ';
        }
        $style_tab = ($style != '')? "nav nav-tabs $style":"nav nav-tabs";
        $tab  = '<ul class="'.$style_tab.'">'.$li.'</ul>';
        $tab .= $content;
        return $tab;
    }
    
    
    function form_upload ($attr)
    {
        if (!isset($attr['pattern']))  $attr['pattern'] = PATTERN_FORM_UPLOAD;
            if ( !isset($attr['class'])) $attr['class'] = 'filehidden';
        return $this->assign_attr($attr['pattern'],$attr);
    }
    
    function __set($name , $value)
    {
        $this->vars[$name] = $value ;
    }
    function set_data($data)
    {
        foreach ($data as $key => $value) {
            $this->vars[$key] = $value ; 
        }
    }
    
    function __get($name)
    {
        if ( isset($this->vars[$name]) )
            return $this->vars[$name];
    }
    
    function get_string ($type='string',$reset=TRUE,$flag=' ')
    {
        $vars = $this->vars;
        if ( $reset == TRUE ) $this->vars = array();
        switch ($type)
        {
            case 'string':return implode($flag,$vars);break;
            case 'array':return $vars;break;
        }
    }
    
    function in_tag ($attr=array(),$tag='div')
    {
        $is_prop = '';
        
        if ( count($attr) > 0 )
        {
            $arr = array();
            foreach ($attr as $prop=>$value)
            {
                $arr[] = "$prop=".'"'.$value.'"';
            }
            $is_prop = implode(' ', $arr);
        }
        
        return "<$tag $is_prop>".$this->get_string()."</$tag>";
    }
    
    
    function ravand ($ravand,$completed=0)
    {
        $li = '';
        foreach ( $ravand as $number=>$name )
        {
            $class = '';
            if ( $number <= $completed ) $class = 'completed';
            if ($number == ($completed+1) ) $class = 'warning';
            $li .= '<li class="'.$class.'"><span class="bubble"></span><span class="progress-text">'.$name.'</span></li>';
        }
        return '<ul class="progress-indicator">'.$li.'</ul>';
    }
}
