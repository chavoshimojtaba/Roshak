<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class db {

    var $show_error = true;
    var $conf_json  = [];
    var $result;
    var $connect    = 'default';

    private $check_lang   = FALSE;
    private $initi        = TRUE;
    private $link         = [];
    private $_config      = [];
    private $page         = 1;
    private $lng          = 'fa';
    private $limit        = 30;
    private $query_result = NULL;

    public static $conuter_sql = 0;
    public static $querys;
    private static $table_backup = '`backup_query`';


    public $table = NULL;
    public $filter;

    protected $skip_chars = [
        '`',
        '@',
        '#',
        '$',
        '%',
        '^',
        '&',
        '*',
        '(',
        ')',
        '+',
        '<',
        '>',
        '.',
        '|',
        ']',
        '[',
        '{',
        '}',
        '\\',
        '?',
        "'",
        '"',
        '='
    ];

    protected $_rep = [
        '_bt',
        '_at',
        '_sharp',
        '_daler',
        '_per',
        '_hashtak',
        '_and',
        '_star',
        '_pl',
        '_pr',
        '_sum',
        '_bl',
        '_br',
        '_dot',
        '_or',
        '_brl',
        '_brr',
        '_acl',
        '_acr',
        '_bslash',
        '_qu',
        "_sq",
        '_wq',
        '_isq'
    ];

    protected $is_log = false;

    function __construct ()
    {
        $this->get_config();
    }


    function init ()
    {
        unset ($this->result);
        $this->result = new stdClass();
    }


    function get_config  ()
    {
        $this->_config = load_config ('db');
        $this->set_lng();
        $this->set_connection();
    }

    function set_lng  ()
    {
        $this->lng = isset($_COOKIE['def_lang'])?$_COOKIE['def_lang']:'fa';
    }


    function switch_db ($db_name)
    {
        $this->connect = $db_name;
        if ( isset ( $this->link[$db_name] ) ) return true;
        $this->set_connection();
    }


    function set_connection ()
    {

        if ( isset ( $this->_config[$this->connect] ) )
        {

            if ( isset ($this->_config[$this->connect]['port']) )
            {
                $this->link[$this->connect] = @mysqli_connect($this->_config[$this->connect]['hostname'],
                $this->_config[$this->connect]['username'],
                $this->_config[$this->connect]['password'],
                $this->_config[$this->connect]['database'],
                $this->_config[$this->connect]['port']);
            }
            else
            {
                $this->link[$this->connect] = @mysqli_connect($this->_config[$this->connect]['hostname'],
                $this->_config[$this->connect]['username'],
                $this->_config[$this->connect]['password'],
                $this->_config[$this->connect]['database']);
            }

            if (!$this->link[$this->connect]) $this->set_error('db');
            @mysqli_query ( $this->link[$this->connect],$this->_config[$this->connect]['char_set'] );

            $this->list_table();
            $this->table = new stdClass();
            foreach ( $this->result->result as $i=>$list_table )
            {
                $alias = ($list_table['alias'] != '')?$list_table['alias']:$list_table['table'];
                $this->table->$alias = $list_table['table'];
            }
        }
        else
        {
            exit("not found config connection for :".$this->connect);
        }
    }

    private function query ($sql,$type)
    {
        $this->query_result = NULL;
        if ( ! $sql OR $sql == '' ) exit ("not found sql");
        if ( $this->initi === TRUE ) $this->init();
        $this->set_result ('start_time',microtime(true));

        $this->query_result = @mysqli_query ( $this->link[$this->connect],$sql );
        if ( !$this->query_result ) {
            $this->set_query ($sql,'false');
            return $this->set_error ($type,$sql);
        }
        $this->set_query  ($sql,'true');
        $this->set_result ('end_time',microtime(true));
        $this->set_result ('time',($this->result->end_time)-($this->result->start_time));
        return $this->query_result;
    }

    function fetch_filed ()
    {
        return mysqli_fetch_fields($this->query_result);
    }

    function set_result ($key,$value)
    {
        $this->result->$key = $value;
    }


    function select ($sql,$check_lang=false,$type='array',$json_array = array() )
    {
        if($this->check_lang || $check_lang){
            $sql = str_replace('_lng_', '_'.$this->lng, $sql);
            $this->check_lang = FALSE;
        }

        $res = $this->query($sql,'fetch');
        switch ($type)
        {
            case 'array': $this->set_result( 'result',$this->fetch_array($res,$json_array) );break;
            case 'json' : $this->set_result( 'result',$this->fetch_json($res) );break;
            case 'fix_json' : $this->set_result( 'result',$this->fix_json($res) );break;
            default :exit ("not define : $type");
            break;
        }
        $this->set_result( 'count', count($this->result->result));
        return $this->result;
    }

    function truncate ($table)
    {
        $res = $this->query('TRUNCATE TABLE '.$table,'TRUNCATE');
        $this->set_result( 'done', 1);
        return $this->result;
    }


    function  fetch_array ($res=NULL,$json_array = array())
    {
        // $R = ((($this->page-1)<0?0:($this->page-1))*$this->limit);
        $array = array ();
        if ( isset ( $res ) )
        {
            while ( $row = mysqli_fetch_assoc($res) )
            {
                if ( count ($json_array) > 0 )
                {
                    $_json = array ();
                    foreach ( $json_array as $k=>$v )
                    {
                        if (is_array ( $v) )
                        {
                            $_json[$v[0]] = $row[$v[1]];
                        }
                        else
                        {
                            $_json[$v] = $row[$v];
                        }
                    }
                    $row['json'] = json_encode($_json);
                }
                // $row['sharp'] = ++$R;
                $array[]  = $row;
            }
        }
        else
        {
            return NULL;
        }
        $this->free_result($res);
        @mysqli_next_result($this->link[$this->connect]);
        return $array;
    }

    function fetch_json ($res)
    {
        $R = ((($this->page-1)<0?0:($this->page-1))*$this->limit);
            $fields = $this->fetch_fields($res);
            $data = array();
            $types = array();
            $array = array();
            foreach($fields as $field)
            {
                switch($field->type)
                {
                    case 3:
                        $types[$field->name] = 'int';
                    break;
                    case 4:
                        $types[$field->name] = 'float';
                    break;
                    default:
                        if ( isset($this->conf_json[$field->name]) )
                        {
                            $types[$field->name] = $this->conf_json[$field->name];
                        }
                        else
                        {
                            $types[$field->name] = 'string';
                        }
                    break;
                }
            }

            while($row=mysqli_fetch_assoc($res)) array_push($data,$row);

            for($i=0;$i<count($data);$i++)
            {
                foreach($types as $name => $type)
                {
                    settype($data[$i][$name], $type);
                }
                $array[$i] = json_encode($data[$i]);
            }
        return $array;
    }


    function fix_json ($res)
    {
        $R = ((($this->page-1)<0?0:($this->page-1))*$this->limit);

        while ( $row = mysqli_fetch_assoc($res) )
        {
            $row['sharp'] = ++$R;
            $array[ ++$R] = $row;
        }

        if ( !isset($array) )
        {
            $array['Res'] = 'null';
        }

        $this->free_result($res);
        return json_encode($array);
    }

    function count_rows ($sql)
    {
        $res = $this->query($sql,'fetch');
        $count = mysqli_num_rows($res);
        $this->free_result($res);
        return $count;
    }

    function total_count ($table,$where='')
    {
        $where .= (isset($this->_config['prefix']))?$this->_config['prefix'].'delete = 0  ':'';
        if($where !== ''){
            $where = ' WHERE '.$where;
        }
        $res = $this->select('SELECT
                    count(tp_id) cnt
                FROM
                    '.$table.$where);
        $count = $res->result[0]['cnt'];
        $this->free_result($res);
        return $count;
    }

    function limit ($sql,$type='array',$json=array(),$page=1,$limit=FALSE)
    {
        $from = 0;
        if ( $limit === FALSE ) $limit = $this->limit;
        else $this->limit = $limit;
        $this->page = (int)$page;
        $page = (int)$page;
        $limit_sql = "";
        if ( $page <= 1 ) $limit_sql = " LIMIT $limit";
        else
        {
            $from = ($page-1)*$limit;
            $limit_sql = " LIMIT $from,$limit ";
        }
        $sql = $sql.$limit_sql;
        return $this->select ($sql,$type,$json);
    }


    function insert ($sql,$log=TRUE)
    {

        if ( is_array($sql) )
        {
            $this->initi = FALSE;
            foreach ( $sql as $table=>$query )
            {
                $this->query($query, 'insert');
                $this->set_result('insert_id_'.$table, $this->auto_increment());
                if ( $log === TRUE )
                {
                    $log = $query.";\n####-----".date('Y-m-d h:m:s')."------######\n\n";
                    log_file ($log,'insert_sql',DIR_LOG_DB.'insert/');
                }
            }
        }
        else
        {
            $this->query($sql, 'insert');
            $this->set_result('insert_id', $this->auto_increment());
            if ( $log === TRUE )
            {
                $log = "\n####-----".date('Y-m-d h:m:s')."------######".$sql.';';
                log_file ($log,'insert_sql',DIR_LOG_DB.'insert/');
            }
        }
	    return $this->result;
    }

    function pager ($sql,$limit=10,$page,$total=false)
    {
        // $this->check_lang = $check_lang;
        $page = (int) $page;
        if ( $page == 1 ) {
            $first_no = 0;
        }else{
            $first_no = $limit * ($page-1);
        }
        $sql .= " LIMIT $first_no , $limit";
        $res = $this->select($sql);
        
        if($total){
            if($res->count > 0){
                $explode = preg_split("/LIMIT/",$sql );
                $explode = preg_split("/ GROUP/",$explode[0] ); 
                $explode = preg_split("/FROM/",$explode[0],2);  
                $prefix = find_between($explode[0],"SELECT",".");
                $alias = ($prefix != false)?trim($prefix).'.':'';
                $total_sql = " SELECT COUNT(".$alias."tp_id) AS cnt FROM ".$explode[1];
                // pr($total_sql,true);
                $res->total = $this->select($total_sql)->result[0]['cnt'];
                if(!$res->total){
                    $res->total = 0;
                }
            }else{
                $res->total = 0;
            }
        }
        return $res;
    }



    function update ( $sql , $backup = TRUE ,$log = TRUE)
    {
        if ( $backup === TRUE )
        {
            if ( $this->backup($sql) === TRUE )
            {
                $res = $this->query($sql,'update');
                $this->set_result( 'affected_rows',$this->affected_rows());
            }
            else
            exit ("backup error");
        }
        else
        {
            $res = $this->query($sql,'update');
            $this->set_result( 'affected_rows',$this->affected_rows());
        }
        $this->free_result($res);
        if ( $log === TRUE )
        {
            $log = "\n####-----".date('Y-m-d h:m:s')."------######".$sql.';';
            log_file ($log,'update_sql',DIR_LOG_DB.'update/');
        }

	    return $this->result;
    }

    function select_tbl ($table,$field,$where)
    {
        return $this->select("
            SELECT
                $field
            FROM
                `$table`
            WHERE
                $where
        ");
    }

    function insert_tbl ($table,$uid,$string_value)
    {
        $field = "";
        if ($uid != 'null'){
            $field = " `tp_uid`  = '$uid',";
        }

        return $this->insert("
            INSERT INTO
                `$table`
            SET
                $field
                $string_value,
                 `tp_date` = now()
        ");
    }


    function update_tbl ($table,$string_value,$where)
    {

        return $this->update("
            UPDATE
                `$table`
            SET
                 $string_value,
                 `tp_update` = now()
            WHERE
                $where
        ");
    }


    function backup ($sql)
    {
        ////clear json object in query
        $sql = preg_replace("#\'\{(.*?)\}\'#","'json_object'", $sql);

        $arr_make_where = explode ('WHERE',$sql);
        $WHERE = $arr_make_where[1];

        $fetch_table_field = explode ('SET',$arr_make_where[0]);
        $TABLE = trim(str_replace('UPDATE','',$fetch_table_field[0]));

        $PRE_TABLE = trim(str_replace('`','',$TABLE));

        //---------------------------- fetch FIELD AND VALUE
        $arry_field_value = explode(',',$fetch_table_field[1]);


        $i=0;
        $ad_where = array();
        $not_update = array('tp_update','tp_date');
        foreach ( $arry_field_value as $fv)
        {
            $div = explode ('=',$fv);
            $fied = trim(str_replace('`','',$div[0]));
            if ( !in_array($fied, $not_update))
            {
                $arr_filed[$i] = trim($div[0]);
                $arr_filed_analogy[$i] = $fied;
                //------------------------------//
                $arr_new_value[$i] = trim(str_replace("'",'',$div[1]));
                $arr_new_value_analogy[$i] = trim(str_replace("'",'',$div[1]));
                $i++;
            }
        }
        //---------------------------- MAKE SELECT
        $STR_FIELD  = implode (',',$arr_filed);
        $new_sql    = "SELECT  `tp_id`,$STR_FIELD FROM $TABLE WHERE $WHERE";
        $resi       = $this->select($new_sql);

        //---------------------------- MAKE INSERT_FIELD

        $ip  = $_SERVER['REMOTE_ADDR'];
        $req = $_SERVER['QUERY_STRING'];

        $array = array(
            '`',
            '@',
            '#',
            '$',
            '%',
            '^',
            '&',
            '*',
            '(',
            ')',
            '+',
            '<',
            '>',
            '.',
            '|',
            ']',
            '[',
            '{',
            '}',
            '\\',
            '?',
            "'",
            '"',
            '='
        );

        $_rep = array(
            '_bt',
            '_at',
            '_sharp',
            '_daler',
            '_per',
            '_hashtak',
            '_and',
            '_star',
            '_pl',
            '_pr',
            '_sum',
            '_bl',
            '_br',
            '_dot',
            '_or',
            '_brl',
            '_brr',
            '_acl',
            '_acr',
            '_bslash',
            '_qu',
            "_sq",
            '_wq',
            '_isq'
        );

        $req = str_replace($array, $_rep, $req);

        $uid = isset($_SESSION['uid']) ?$_SESSION['uid']:0;

        $INSERT_FIELD = "( `tp_uid`, `tp_table`, `tp_field`, `tp_record`, `tp_new_value`, `tp_old_value`, `tp_ip`, `tp_req`, `tp_date`)";
        $arr_v = array();
        if ( $resi->count > 0 )
        {
            for ($i=0;$i<$resi->count;$i++)
            {
                foreach ($arr_filed_analogy as $k=>$v)
                {
                    if ( isset($arr_new_value[$k]) && isset($resi->result[$i][$v]) && $arr_new_value_analogy[$k] != $resi->result[$i][$v] )
                    {
                        $arr_v[] = "(
                            '$uid',
                            '$PRE_TABLE',
                            '$v',
                            '".$resi->result[$i]['tp_id']."',
                            LEFT('".$arr_new_value[$k]."',255),
                            LEFT('".$resi->result[$i][$v]."',255),
                            '$ip',
                            '$req',
                            now())";
                    }
                }
            }
        }

        if(isset($arr_v) && count($arr_v) > 0)
        {
            $QUERY = "INSERT INTO ".self::$table_backup." $INSERT_FIELD VALUES ".implode(',',$arr_v);
            //--------------------------------------------------------- default
           if ( $this->insert($QUERY) )
           {
               //------------------------------------------------------- current
                return TRUE;
           }else
           {
                $this->set_error('backup',$QUERY);
                return FALSE;
           }
        }
        else
        {
            return TRUE;
        }
    }


    function fetch_fields ($res)
    {
        return mysqli_fetch_fields($res);
    }



    function auto_increment()
    {
        return mysqli_insert_id ($this->link[$this->connect]);
    }


    function affected_rows ()
    {
        return mysqli_affected_rows ($this->link[$this->connect]);
    }


    function free_result($res)
    {
        // @mysqli_free_result($res);
    }


    function set_query ($sql,$res)
    {
        self::$querys[$_SERVER['REQUEST_URI'].'_'.date('Y-m-d_H:i:s').' _ sql:'.self::$conuter_sql++.' type:'.$res] = $sql.'<br/>';
    }

    function all_query ()
    {
        return self::$querys;
    }

    function fetch_table_info ( $table , $field = '*')
    {
        return $this->select("
            SELECT
                $field
            FROM
                INFORMATION_SCHEMA. TABLES
            WHERE
                table_schema = '".$this->_config[$this->connect]['database']."'
            AND table_name = '$table';
        ");
    }


    function information_schema ($field='',$where='')
    {
        if ( $where != '' )
        {
            $where = "AND $where";
        }
        return $this->select("
           SELECT
                $field
            FROM
                INFORMATION_SCHEMA. TABLES
            WHERE
                TABLE_SCHEMA = '".$this->_config[$this->connect]['database']."'
            $where
        ");
    }

    function list_table ()
    {
        return $this->select("
            SELECT
                table_name.TABLE_NAME AS `table`,
                sys_table_list.tp_alias AS `alias`
            FROM
                (
                    SELECT
                        TABLE_NAME
                    FROM
                        INFORMATION_SCHEMA. TABLES
                    WHERE
                        TABLE_SCHEMA = '".$this->_config[$this->connect]['database']."'
                ) AS `table_name`
            LEFT JOIN `sys_table_list` ON (
                table_name.TABLE_NAME = sys_table_list.tp_table
                AND sys_table_list.tp_delete = '0'
            )
        ");
    }

    function set_error ($type,$sql=false)
    {
        $file = $type;
        $uid = isset ( $_SESSION['uid'] )?$_SESSION['uid']:0;
        $C = &get_instance();
        $log  = '#[system:error] : '.$type.";\n";
        $log .= '#[error:num]:     '.@mysqli_errno($this->link[$this->connect]).";\n";
        $log .= '#[error]:         <font color="#FF0000">'.@mysqli_error($this->link[$this->connect]).";</font>\n";
        $log .= '#[uid]:           '.$uid.";\n";
        $log .= '#[ip]:            '.$_SERVER['REMOTE_ADDR'].";\n";
        $log .= '#[page]:          '.$_SERVER['REQUEST_URI'].";\n";
        $log .= '#[file]:          '.$C->router->dir_file.";\n";
        $log .= '#[class]:         '.$C->router->class.";\n";
        $log .= '#[method]:        '.$C->router->method.";\n";
        $log .= '#[date]:          '.date('Y-m-d H:i:s').";\n";
        $log .= '#[data]:          '.array_to_pipe($_REQUEST).";\n";
        $log .= '#[sql]:'.         "\n {$sql};\n";
        $log .= '#';
        for ( $i=1;$i<=70;$i++)
                $log .= '_';

        $log .= "\n \n \n";
        log_file ($log,$file);

        if ( $this->show_error === TRUE )
        {
            pr(str_replace("\n",'<br/>',$log));
            pr($this->all_query());
        }
        exit;
    }



    function string_request ($array=array())
    {
        $string = '';
        if ( count ( $array ) == 0 ) $_array = $_REQUEST;
        else $_array = $array;

        foreach ( $_array as $k=>$v )
        {
            if ( is_array ($v) ) $string .= "{$k}=".$this->string_request();
            else
                $string .= "{$k}=$v;";
        }

        return $string;
    }



    function __destruct()
    {
        foreach ($this->link as $k=>$v)
            mysqli_close($this->link[$k]);
    }

/* function pager1 ($sql,$limit=10,$page,$ad_url='',$url=CURRENT_URL,$json=array(),$style='def_pager')
    {
        if ($limit)
        {
            $this->limit = $limit;
        }
        $this->page = (int) $page;

        if ( $this->page == 0 ) $this->page = 1;


        $count = $this->count_rows($sql);
        if ($count < $this->limit)
        {
            $this->initi = FALSE;
            $this->init();
            $this->set_result('total',$count);
            $this->set_result('pager', NULL);
            return $this->select($sql, "array", $json);
        }
        $len_row = $count;
        $last = ceil($len_row / $this->limit);
        if ($this->page > $last)
        {
            $this->page = $last;
        }
        $first_no = 0;
        $last_no = ($this->limit > $last) ? $last : $this->limit;
        if ($this->page > 1)
        {
            $last_no = ($last_no * $this->page);
            $first_no = $this->limit * ($this->page - 1);
        }
        if ($last_no > $len_row)
        {
            $last_no = $len_row;
        }
        if ($last_no < $first_no)
        {
            $last_no = $this->limit;
        }
        if ($first_no == 0)
        {
            $first_no = 1;
        }

        $range = range($first_no, $last_no);

        if ($this->page > 1)
        {
            $r1 = ($this->page > ($this->limit - 7)) ? $this->page - 7 : 1;
            if ( $r1 <= 0 ) $r1 = 1;

            if ($this->page >= $last - 7)
            {
                $r2 = $last;
            }
            else
            {
                $r2 = ($this->page > ($this->limit - 7)) ? $this->page + 7 : $this->limit;
            }
            $range = range($r1, $r2);
        }
        else
        {
            $r1 = 1;
            $r2 = ($this->limit < $last) ? $this->limit : $last;
            $range = range($r1, $r2);
        }
        $li = '';
        $url = $url.$ad_url;
        $xurl = '';
        if ( (is_array($this->filter)) && count($this->filter) > 0 )
        {
            $xurl = '/?'.ArrayToueryString($this->filter);
        }


        $url = trim($url);

        foreach ($range as $key => $value)
        {
            if ($value == $this->page)
            {
                $v = "<span class=\"bold\">[$value]</span>";
            }
            else
            {
                $v = $value;
            }
            $li .= '<li class="item_page"><a href="'.$url.$value.$xurl.'" class="link_pager"><span class="num_pager">' . $v . '</span></a></li>';
        }
        $ul = '<ul class="upager ' . $style . '">' . $li . '</ul>';
        if ($first_no == 1)
        {
            $first = 0;
        }
        else
        {
            $first = $first_no;
        }
        $sql .= " LIMIT $first , $this->limit";
        $this->initi = FALSE;
        $this->init();
        $this->set_result('pager', $ul);
        $this->set_result('total',$count);
        return $this->select($sql, "array", $json);
    } */
}