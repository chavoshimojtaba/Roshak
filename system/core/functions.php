<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function sort_row_array($array, $index, $value, $column = false)
{
    $sort = [];
    foreach ($array as $k => $row) {
        if (!isset($sort[$row[$index]])) {
            $sort[$row[$index]] = [];
        }
        array_push($sort[$row[$index]], $row[$value]);
    }
    return $sort;
}

function setDefLang()
{
    setcookie("def_lang", "", time() - 3600, '/');
    setcookie('def_lang', 'fa', time() + (8886400 * 30), "/");
    $_SESSION['def_lang']  = $_COOKIE['def_lang'];
}

function pr($Array, $exit = False)
{
    header('Content-Type: text/html; charset=utf-8');
    echo '<pre style="text-align:left !important;float:left !important;direction:ltr !important;font-family:Verdana, Geneva, sans-serif;width:100%">';
    print_r($Array);
    echo '</pre>'; 
    if ($exit == TRUE) exit();
}

function use_tools($tools = [])
{
    if (count($tools) > 0) {
        foreach ($tools as $section => $files) {
            foreach ($files as $name) {
                require_once APPPATH . "{$section}/{$section}_{$name}" . EXT;
            }
        }
    }
}

function find_url_by_ref()
{
    if (isset($_SERVER['HTTP_REFERER'])) {
        $url = trim(str_replace(HOST, '', $_SERVER['HTTP_REFERER']));
        if ($url != '') {
            $explode = explode('/', $url);
            return HOST . $explode[0];
        }
    }
}

function array_column_multi(array $input, array $column_keys)
{
    $result = array();
    $column_keys = array_flip($column_keys);
    foreach ($input as $key => $el) {
        $result[$key] = array_intersect_key($el, $column_keys);
    }
    return $result;
}

function array_column_custom(array $input, array $column_keys, $row = false)
{
    $temp = array();
    $len  = count($column_keys);
    $first = $column_keys[0];
    unset($column_keys[0]);

    foreach ($input as $i => $list) {
        if (isset($list[$first])) {
            if ($len == 1 && $row == true) {
                $temp[$list[$first]] = $list;
            }

            if ($len == 1 && $row == false) {
                $temp[] = $list[$first];
            }

            if ($len == 2) {
                $temp[$list[$first]] = $list[$column_keys[1]];
            }

            if ($len > 2) {
                $a = array();
                foreach ($column_keys as $index) {
                    $a[$index] = $list[$index];
                }
                $temp[$list[$first]] = $a;
            }
        }
    }
    return $temp;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/


function check_empty($filed = array(), $full_field = array(), $mode = 'insert')
{

    $C = &get_instance();
    $error = array();
    $sql_value = '';

    $array = $C->security->input('post', array(), TRUE);

    if (count($filed) > 0) {
        foreach ($filed as $name => $array_field) {
            if (isset($array_field['alias'])) {
                $caption = $array_field['alias'];
            } else {
                $caption = $name;
            }
            if (!isset($array[$name]) || empty($array[$name])) {
                $error['error']["{$name}|empty"] = @constant('LANG_' . strtoupper($caption)) . ' : ' . _MSG_EMPTY;
            }
        }
    }

    if (isset($error) && count($error) > 0) {
        return $error;
    }

    $conf = $C->load->conf[$C->load->conf_name];

    $array_sql = array();

    switch ($mode) {
        case 'insert':
            $sql = "INSERT INTO ";
            break;
        case 'update':
            $sql = "UPDATE ";
            break;
    }

    if (isset($conf[$mode]) && count($conf[$mode]['table']) > 0) {
        foreach ($conf[$mode]['table'] as $table) {
            if (!isset($conf[$mode]['field']) or count($conf[$mode]['field']) == 0) {
                $array_sql[$table] = "$sql `$table` SET " . set_fields($full_field, $array, $mode);
            } else {
                $array_sql[$table] = "$sql `$table` SET " . set_fields($conf[$mode]['field'][$table], $array, $mode);
            }
        }
        return $array_sql;
    }

    return set_fields($full_field, $array, $mode);
}


/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function set_fields($full_field, $array, $mode = '')
{
    pr($full_field, true);

    $arr = array();

    foreach ($full_field as $k => $name) {
        if (isset($array[$name]) and $array[$name] != '' and !is_array($array[$name])) {
            $arr[$name] = " `tp_{$name}`='" . $array[$name] . "'";
        }
    }

    switch ($mode) {
        case 'insert':
            $arr['uid'] = " `tp_uid` = '" . $_SESSION['uid'] . "'";
            $arr['date'] = " `tp_date` = now()";
            break;
        case 'update':
            $arr['update'] = " `tp_update` = now()";
            break;
        default:
            break;
    }
    return implode(",", $arr);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

if (!function_exists('load_class')) {
    function &load_class($class, $directory = 'libraries', $prefix = '', $param = NULL)
    {
        //echo $class." __ ";
        static $_classes = array();

        // Does the class exist?  If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        // Look for the class first in the local application/libraries folder
        // then in the native system/libraries folder
        foreach (array(APPPATH, BASEPATH) as $path) {
            if (file_exists($path . $directory . '/' . $class . '.php')) {
                $name = $prefix . $class;

                if (class_exists($name) === FALSE) {
                    require($path . $directory . '/' . $class . '.php');
                }

                break;
            }
        }


        // Did we find the class?
        if ($name === FALSE) {
            // Note: We use exit() rather then show_error() in order to avoid a
            // self-referencing loop with the Excptions class
            exit('Unable to locate the specified class: ' . $class . '.php');
        }

        // Keep track of what we just loaded
        is_loaded($class);

        $_classes[$class] = new $name($param);
        return $_classes[$class];
    }
}
/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/
if (!function_exists('is_loaded')) {
    function &is_loaded($class = '')
    {
        static $_is_loaded = array();

        if ($class != '') {
            $_is_loaded[strtolower($class)] = $class;
        }

        return $_is_loaded;
    }
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

if (!function_exists('get_config')) {
    function &get_config($replace = array())
    {
        static $_config;

        if (isset($_config)) {
            return $_config[0];
        }

        // Is the config file in the environment folder?
        if (!defined('ENVIRONMENT') or !file_exists($file_path = APPPATH . 'config/' . ENVIRONMENT . '/config.php')) {
            $file_path = APPPATH . 'config/config.php';
        }

        // Fetch the config file
        if (!file_exists($file_path)) {
            exit('The configuration file does not exist.');
        }

        require($file_path);

        // Does the $config array exist in the file?
        if (!isset($config) or !is_array($config)) {
            exit('Your config file does not appear to be formatted correctly.');
        }

        // Are any values being dynamically replaced?
        if (count($replace) > 0) {
            foreach ($replace as $key => $val) {
                if (isset($config[$key])) {
                    $config[$key] = $val;
                }
            }
        }

        return $_config[0] = &$config;
    }
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

if (!function_exists('config_item')) {
    function config_item($item)
    {
        static $_config_item = array();

        if (!isset($_config_item[$item])) {
            $config = &get_config();

            if (!isset($config[$item])) {
                return FALSE;
            }
            $_config_item[$item] = $config[$item];
        }

        return $_config_item[$item];
    }
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function load_config($name)
{
    static $_configs = array();
    $i = 0;
    if (isset($_configs[$name])) return $_configs[$name];

    foreach (array(APPPATH . 'config/', BASEPATH . 'config/') as $path) {
        if (file_exists($path . $name . EXT)) {
            require_once($path . $name . EXT);
            $_configs[$name] = @$$name;
            $i = 1;
            return $_configs[$name];
        }
    }
    if ($i == 0) {
        exit("file not exist : " . $path . $name . EXT);
    }
}


/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/


function log_file($log, $file, $dir = DIR_LOG_DB)
{
    $file = $file . '_' . date('Y_m');
    $filename = strtolower(trim($dir . $file . '.log'));
    ob_start();
    $handle = fopen($filename, 'a+');
    if (!$handle) exit("cannot open file" . $filename);
    if (fwrite($handle, $log) === FALSE) {
        exit("Cannot write to file ($filename)");
    }
    fclose($handle);
    ob_end_clean();
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function error_404($_this,$error_page=false)
{  
    http_response_code(404); 
    if($error_page){ 
        $_this->page->set_data([ 
            'title'=>$_SESSION['site']['h1_home'], 
            'error'=>HOST, 
            'desc'=>$_SESSION['site']['seo_meta_home'], 
            'files'=>[ 
                ['url'=>'file/client/css/home.css','type'=>'css'],
            ]
        ]);
    } else{
        $_this->page->set_data([  
            'files'=>[  
                ['url'=>'file/client/css/profile.css','type'=>'css'],
            ]
        ]);
    }
    // pr(explode('/dashboard',$_this->router->dir_view)[0],true);
    $_this->template->restart(_VIEW . 'errors' . EXT, explode('/dashboard',$_this->router->dir_view)[0]); 
    $_this->template->parse('404');
    out([
        'content' => $_this->template->text('404')
    ]);  
    exit; 
}

function error_405()
{ 
    http_response_code(405);
    $_this->template->restart(_VIEW . 'errors' . EXT, $_this->router->dir_view); 
    $_this->template->parse('405');
		out([
            'content' => $_this->template->text('405')
        ]);  

}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function get_error($error = array(), $is_error = '', $desc = '', $buffer = TRUE)
{
    if (count($error) > 0) {
        $C = &get_instance();
        $li = '';
        $lii = '';
        $js = '';
        foreach ($error as $index => $error) {
            $name = array();
            $name = explode('|', $index);
            $li  .= '<li class="error_list_item" aria-item="' . $name[0] . '">' . $error . '</li>';
            $lii .= '<li class="error_list_item" aria-item="' . $name[0] . '">' . $error . "<br/>[$index]" . '</li>';
        }
        $ul = '<ul class="list_error">' . $lii . '</ul>';

        $C->load->model("model_log_error");
        $uid = isset($_SESSION['uid']) ?  $_SESSION['uid'] : 0;
        $R = $C->model_log_error->insert_log($C->router->class, $C->router->method, $ul, $C->router->ip(), $C->router->query_string(), $C->router->dir_file, $C->router->path, $uid, $is_error, $desc, $C->router->mack_address());

        //msg
        /*
        $str = 'msg_'.str_random();
        if ($Msg == TRUE)
        {
            if ( $parent === TRUE ) $js = '$(".'.$str.'").msg();';
            else $js = '$(".'.$str.'").msg({parent:false});';
            $js = '<script type="application/javascript">'.$js.'</script>';
        }
         *
         */

        $ul = '<ul class="list_error">' . $li . '</ul>';
        if ($buffer === TRUE) {
            $_SESSION['msg'][2] = $ul;
            $_SESSION['post'] = $_POST;
        }
        if ($R == TRUE) return $ul;
        else exit("unabel log error");
    }
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/


function unset_msg()
{
    unset($_SESSION['msg']);
    unset($_SESSION['do_post']);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function set_massage($sw = 1, $msg = FALSE)
{
    $type = array(1 => 'success', 2 => 'warning', 3 => 'error', 4 => 'notice');
    $massage = array(1 => _MSG_SUCCESS_, 2 => _MSG_ERROR_);
    $pattern = '<div class="alert-box ' . $type[$sw] . '"><span>' . _MSG_SYS_ . '</span><br/>' . $msg !== FALSE ? $msg : $massage[$sw] . '</div>';
    return $pattern;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/


function define_lang($name, $per = 'part_')
{
    return @constant('LANG_' . strtoupper($per) . strtoupper($name));
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function assign_value($array = array(), $block = 'main', $loop = '', $name = '', $view = '')
{
    $C = &get_instance();
    $name = ($name == '') ? $C->router->class : $name;
    $view = ($view == '') ? $C->router->dir_view : $view;
    $C->template->restart(_VIEW . $name . EXT, $view);

    if ($loop == '') {
        $C->template->assign($array);
    } else {
        if (isset($array['assign'])) {
            $assign = $array['assign'];
            unset($array['assign']);
            $C->template->array_loop($block . '.' . $loop, $assign, $array);
        } else {
            $C->template->insert_loop($block . '.' . $loop, $array);
        }
    }
    $C->template->parse($block);
    return $C->template->text($block);
}


//mini template

function min_template($array = array(), $loop = [], $block = 'main', $name = '', $view = '')
{
    $C = &get_instance();
    $name = ($name == '') ? $C->router->class : $name;
    $view = ($view == '') ? $C->router->dir_view : $view;
    $C->template->restart(_VIEW . $name . EXT, $view);

    if (count($loop) > 0) {
        foreach ($loop as $loop_block) {
            foreach ($array[$loop_block] as $key => $value) {
                $C->template->assign($value);
                $C->template->parse($block . ".{$loop_block}");
            }
            unset($array[$loop_block]);
        }
    }
    if (count($array) > 0) {
        $C->template->assign($array);
    }
    $C->template->parse($block);
    return $C->template->text($block);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function array_to_pipe($array, $delimeter = '|', $parents = array(), $recursive = false)
{
    $result = '';

    foreach ($array as $key => $value) {
        $group = $parents;
        array_push($group, $key);

        // check if value is an array
        if (is_array($value)) {
            if ($merge = array_to_pipe($value, $delimeter, $group, true)) {
                $result = $result . ',' . $merge;
            }
            continue;
        }

        if (strlen($value) > 20) $value = substr($value, 0, 20);

        // check if parent is defined
        if (!empty($parents)) {
            $result = $result . ',' . PHP_EOL . implode($delimeter, $group) . '=' . $value;
            continue;
        }

        $result = $result . ',' . PHP_EOL . $key . '=' . $value;
    }

    // somehow the function outputs a new line at the beginning, we fix that
    // by removing the first new line character
    if (!$recursive) {
        $result = substr($result, 1);
    }

    return $result;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function out($data, $render = 'site')
{
  /*   pr('++++');
    pr($data,true); */
    $C = &get_instance();
    $conf = $C->load->config('web_tools');
    if (isset($conf['alias_url'][$render])) {
        $render = $conf['alias_url'][$render];
    }

    $C->load->library($conf[$render]['render'], NULL, 'view');
    $data['dir']  = isset($conf[$render]['dir']) ? $conf[$render]['dir'] : $C->router->path;
    $data['skin'] = ($conf[$render]['template'] != '') ? $conf[$render]['template'] : 'default/';
    if (isset($conf[$render]['setting']) && count($conf[$render]['setting']) > 0) {
        foreach ($conf[$render]['setting'] as $section => $value) {
            if (!isset($data[$section])) {
                $data[$section] = $value;
            }
        }
    }

    $C->view->init($data);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function show_msg()
{
    $div = '';
    if (isset($_SESSION['msg'])) {
        $array = array('1' => 'success', '2' => 'danger', '3' => 'warning', '4' => 'info');
        foreach ($_SESSION['msg'] as $type => $error) {
            if (isset($array[$type]))
                $div = '<div class="alert alert-' . $array[$type] . ' clear">' . $error . '</div>'; //<script>gotop();</script>
            else
                $div = $error;
        }
        if ($type != 2) {
            unset($_SESSION['post']);
        }
        unset($_SESSION['msg']);
    }
    return $div;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function success($msg = '', $url = '')
{
    //   pr($url);
    if ($msg == '') {
        $msg = _MSG_SUCCESS_;
    }

    $_SESSION['msg'][1] = $msg;

    if (!isset($_SERVER['HTTP_REFERER']) and $url == "") {
        exit("empty url");
    }

    if ($url == '') {
        $url = $_SERVER['HTTP_REFERER'];
    }
    // pr($url,1);
    redirect($url);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function redirect($url = '', $type = false)
{ 
    if ($url == '') {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url = $_SERVER['HTTP_REFERER'];
        } else {
            $url = HOST . 'errors/e_404';
        }
    }
    if(strpos($url,'auth')>=0){ 
        $url .='?callback='.$_SERVER['QUERY_STRING'];
    } 
    if ($type === false) {
        header("location: " . $url);
    } else {
        header("location: " . CURRENT_URL . $url);
    }
    exit();
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function msg_json($msg = FALSE, $sw = 1)
{
    $type = array(1 => 'success', 2 => 'warning', 3 => 'error', 4 => 'notice', 5 => 'res_json_msg');
    $massage = array(1 => _MSG_SUCCESS_, 2 => _MSG_ERROR_);
    $arr['type'] = $type[$sw];
    if ($msg === FALSE) $msg = $massage[$sw];
    $arr['msg'] = $msg;
    return json_encode($arr);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function sting2value($value, $string, $def = 2)
{
    $a = explode(",", $string);
    $i = 1;
    foreach ($a as $values) {
        $b[$i] = $values;
        $i++;
    }
    if (isset($b[$value]))
        return $b[$value];
    return $b[$def];
}

function sting2array($string)
{
    $a = explode(",", $string);
    $i = 1;
    $b = [];
    foreach ($a as $values) {
        $b[$i] = $values;
        $i++;
    }
    return $b;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function array2value($value, $array, $desc = '')
{
    if (isset($array[$value]))
        return $array[$value];
    return $desc;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function array2string($array, $glue = ' ')
{
    return implode($glue, $array);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function random_code($size = 5)
{
    return substr(md5(uniqid(mt_rand(), true)), 0, $size);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function clear_field_name($array = array(), $convert = FALSE)
{
    $new_array = array();
    foreach ($array as $field => $value) {
        if ($convert == TRUE) $value = iconv("windows-1256", "utf-8", $value);;
        $new_array[trim(str_replace('tp_', '', $field))] = $value;
    }
    return $new_array;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function hash_password($value)
{
    return md5($value);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function format_int($value)
{
    return trim(str_replace(',', '', $value));
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/


function mailuser($data = array(), $pattern = PATTENT_EMAIL_ACTIVE_ACCOUNT)
{
    foreach ($data as $k => $v) {
        $pattern = str_replace('{' . $k . '}', $v, $pattern);
    }

    $headers = 'From: ' . $data['from'] . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    @mail($data['to'], $data['subject'], $pattern, $headers);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function isset_empty($value)
{
    $C = &get_instance();
    $post  = $C->security->input();
    if (isset($post[$value]) && !empty($post[$value])) return true;
    else return false;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function p2g($date, $check = FALSE)
{
    if ($check !== FALSE) {
        if (format_date($date) === FALSE) {
            return FALSE;
        }
    }
    if(!$date){
        return '0000-00-00';
    }
    $C = &get_instance();
    list($y, $m, $d) = explode('/', $date);
    $C->persian_date = load_class('persian_date');
    return $C->persian_date->persian_to_gregorian($y, $m, $d, '-');
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function format_date($value)
{
    if (strpos($value, '/') === FALSE) return FALSE;
    $value = str_replace('/', '-', $value);
    if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $value)) {
        return true;
    } else {
        return false;
    }
}

function g2p($date, $msg = _MSG_NOT_DEFINE_DATE_, $patern = 'Y/m/d')
{
    if ( isset($_COOKIE['def_lang']) && $_COOKIE['def_lang'] == 'en' ) {
        return $date;
    }
    if ($date == '') {
        return '';
    }
    if ($date == '0000-00-00 00:00:00') {
        return $msg;
    }
    $pdate = load_class('persian_date');
    return $pdate->to_date($date, $patern);
}
function g2pt($date, $msg = _MSG_NOT_DEFINE_DATE_, $patern = 'Y/m/d')
{
    if (isset($_COOKIE['def_lang']) && $_COOKIE['def_lang'] == 'en' ) {
        return $date;
    }

    if ($date == '0000-00-00 00:00:00' || !$date || strlen($date) <= 6) {
        return $msg;
    }
    $pdate = load_class('persian_date');
    return $pdate->to_date($date, $patern).' '.explode(' ',$date)[1];
}

function g2fa($date, $msg = _MSG_NOT_DEFINE_DATE_, $patern = 'Y/m/d')
{
    if ($date == '0000-00-00 00:00:00') {
        return $msg;
    }
    $pdate = load_class('persian_date');
    return fa_int($pdate->to_date($date, $patern));
}

function g2time($date, $msg = _MSG_NOT_DEFINE_DATE_, $patern = 'Y/m/d')
{
    if ($date == '0000-00-00 00:00:00') {
        return $msg;
    }
    $_date = explode(' ', $date);
    $pdate = load_class('persian_date');
    $d = fa_int($pdate->to_date($_date[0], $patern));
    return '<span style="direction:ltr;text-align:left">' . fa_int($_date[1]) . ' - ' . $d . '</span>';
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function split_date($date = "", $value = 0)
{
    if ($date == '') $date = date("Y-m-d");
    $ex = explode('/', g2p($date));
    return $ex[$value];
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function post_date_per($post, $type = false)
{
    $date = array();
    if ($post[2] == '') $date[0]  = split_date(0);
    if (strlen($post[2]) == 2) $date[0]  = '13' . $post[2];

    if ($post[1] == '') $date[1]  = '01';
    if (strlen($post[1]) == 1) $date[1]  = '0' . $post[1];
    else $date[1]  = $post[1];

    if ($post[0] == '') $date[2]  = '01';
    if (strlen($post[0]) == 1) $date[2]  = '0' . $post[0];
    else $date[2]  = $post[0];
    if ($type === FALSE)
        return p2g(implode('/', $date));
    return implode('-', $date);
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function str_random($len = 5)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $len; $i++) {
        $randstring .= $characters[rand(0, 25)];
    }
    return $randstring;
}
/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function summarize_str($str, $size, $ext = '...', $break = 20)
{
    if (strlen($str) > $size) {
        $arr =  array(';', ':', '!', '?', '/', '', '.', ' ', '', '_');
        for ($len = $size; $len <= $size + $break; $len++) {
            $point = substr($str, $len, 1);

            if (in_array($point, $arr)) {
                return substr($str, 0, $len) . $ext;
            }
        }
        return substr($str, 0, $size) . $ext;
    } else {
        return  $str;
    }
}
/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function go_url($sw)
{
    $array_url = array(
        'h' => HOST,
        'b' => BASE_URL,
        'c' => CURRENT_URL
    );

    if (!isset($array_url[$sw])) exit("go url error :" . $sw);
    return $array_url[$sw];
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function not_in_array_unset($orginal_array, $pattern_array)
{

    foreach ($orginal_array as $index => $values) {
        if (!in_array($pattern_array, $index)) {
            unset($orginal_array[$index]);
        }
    }
    return $orginal_array;
}

/*------------------------------------------------------------------------------
#
#
#
------------------------------------------------------------------------------*/

function inline_msg($msg, $class = 'success', $type = 'div')
{
    $attr = array(
        'type' =>  $type,
        'class' => 'alert alert-' . $class,
        'text' => $msg
    );
    $C = &get_instance();
    return $C->html->assign_attr(PATTERN_INF, $attr);
}
/* ------------------------------------------------------------------------------
  #
  ------------------------------------------------------------------------------ */

function path_libs($name)
{
    $o =  new stdClass();
    $o->model  = DIR_LIBS . "{$name}/model/";
    $o->view   = DIR_LIBS . "{$name}/view/";
    $o->conf   = DIR_LIBS . "{$name}/config/";
    $o->lang   = DIR_LIBS . "{$name}/lang/";
    $o->helper = DIR_LIBS . "{$name}/helper/";
    return $o;
}

/* ------------------------------------------------------------------------------
 #
 ------------------------------------------------------------------------------ */

function fa_int($string)
{
    return str_replace(['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'], $string);
}

/* ------------------------------------------------------------------------------
 #                               array_column
 ------------------------------------------------------------------------------ */

if (!function_exists("array_column")) {
    function array_column($array, $column_name)
    {
        return array_map(function ($element) use ($column_name) {
            return $element[$column_name];
        }, $array);
    }
}

/* ------------------------------------------------------------------------------
 #
 ------------------------------------------------------------------------------ */

function ArrayToueryString($array)
{
    $temp = array();
    foreach ($array as $index => $value) {
        $temp[$index] = "{$index}={$value}";
    }
    return implode('&', $temp);
}

function queryStringToArray($string)
{
    $temp   = explode('&', $string);
    $output = array();
    $C = &get_instance();
    foreach ($temp as $str) {
        $t = explode('=', $str);
        // pr($t,true);
        $index = $C->security->clear($t[0]);
        $value = urldecode($t[1]);
        $value = $C->security->clear($value);
        $output[$index] = $value;
    }
    return $output;
}
/* ------------------------------------------------------------------------------
 #
 ------------------------------------------------------------------------------ */
function fix_url($url)
{
    return preg_replace('/[ \-]+/', '-', $url);
}

if (!function_exists('load_plugin')) {
    function &load_plugin($class, $param = NULL)
    {
        static $_classes = array();

        // Does the class exist?  If so, we're done...
        if (isset($_classes[$class])) {
            return $_classes[$class];
        }

        $name = FALSE;

        if (file_exists(BASEPATH . "libs/{$class}/{$class}.php")) {
            $name = $class;

            if (class_exists($name) === FALSE) {
                require(BASEPATH . "libs/{$class}/{$class}.php");
            }
        }
        // Did we find the class?
        if ($name === FALSE) {
            // Note: We use exit() rather then show_error() in order to avoid a
            // self-referencing loop with the Excptions class
            exit('Unable to locate the specified class: ' . $class . '.php');
        }
        // Keep track of what we just loaded
        is_loaded($class);

        $_classes[$class] = new $name($param);
        return $_classes[$class];
    }
}

/*------------------------------------------------------------------------------
 *
 */
function pc_array_shuffle($array)
{
    $i = count($array);

    while (--$i) {
        $j = mt_rand(0, $i);

        if ($i != $j) {
            // swap elements
            $tmp = $array[$j];
            $array[$j] = $array[$i];
            $array[$i] = $tmp;
        }
    }
    return $array;
}

function call_helper($helper)
{
    $helpers = explode(',', $helper);

    foreach ($helpers as $filename) {
        require_once DIR_HELPER . 'helper_' . $filename . '.php';
    }
}
function find_between($str, $starting_word, $ending_word)
{
    $subtring_start = strpos($str, $starting_word);
    $subtring_start += strlen($starting_word);
    $size = strpos($str, $ending_word, $subtring_start) ;
    if($size == false){
        return false;
    }
    $size -= $subtring_start;
    return substr($str, $subtring_start, $size);
}
