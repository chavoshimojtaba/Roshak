<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . 'lang/lang.php';

require_once BASEPATH . 'core/functions.php';

if ((isset($_COOKIE['def_lang']) && $_COOKIE['def_lang'] == 'en')) {
    require_once APPPATH . 'lang/lang.php';
} else {
    if (!isset($_COOKIE['def_lang'])) {
        setDefLang();
    }
    require_once APPPATH . 'lang/lang_fa.php';
}


require_once BASEPATH . 'core/Controller.php';

$C = new Controller();
require_once APPPATH . 'libraries/helper.php';

$session = &load_class('session', 'core');

$Security = &load_class('security', 'core');

$DB = &load_class('db', 'core');

$R  = &load_class('router', 'core');

$Security->set_key();

$TEM = &load_class('Template');

$load = &load_class('load', 'core');

$html = &load_class('html', 'core');


function &get_instance()
{
    return Controller::get_instance();
}

if (isset($R->host) && !defined('HOST')){
    define('HOST', $R->host);
}

if (!defined('CURRENT_URL')){
    define('CURRENT_URL', $R->current_url());
}

if (!defined('BASE_URL')){
    define('BASE_URL', $R->base_url());
}


$log = &load_class('log', 'core');

$log->set_up($R, $Security);
$Page = &load_class('Page', 'core');


if ($R->path == "dashboard" || $R->path == "dashboard/" && !isset($_SESSION['admin'])) {
    $free_pages = [
        'auth'
    ];
    if (!in_array($R->class, $free_pages)) {
        if ($session->check_login_member() === false) {
            header("location:" . HOST . 'auth');
            exit;
        }
    }
}

if ($R->path == "back_end" || $R->path == "back_end/"  || $R->path == "admin/") {
    $free_pages = [
        'login', 'logout', 'api'
    ];
    if (!in_array($R->class, $free_pages) && $session->check_login() === false) {
        header("location:" . HOST . 'admin/login');
        exit;
    }
    if (!in_array($R->class, $free_pages) && isset($_SESSION['resources'][$R->class]) &&  $_SESSION['is_admin'] != 1 && !check_perm($R->class, 2)) {
        /* header ("location:".HOST.'admin/err');
        exit; */
    }
}

/* if ( isset( $R->url_array['not_check'] ) AND !in_array($R->class, $R->url_array['not_check']))
{
    if ( $session->check_login() === false AND  $R->is_check_user === true )
    {
        //header ("location:".$R->url_array['def_url']);
        //exit;
    }
}
*/

$class_name  = $R->class;
$method_name = $R->method; 
require_once $R->dir_file;
$debug = true;
$path = str_replace('/', '', $R->path);
if (class_exists($class_name)) {
    if (is_file(APPPATH . "lang/lang_{$class_name}" . EXT)) {
        require_once APPPATH . "lang/lang_{$class_name}" . EXT;
    }

    if (is_file(APPPATH . "helper/helper_{$class_name}" . EXT)) {
        require_once APPPATH . "helper/helper_{$class_name}" . EXT;
    }

    $engin = new $class_name;

    if (method_exists($engin, $R->method)) {
        define('PATH_URL', $class_name . '/' . $method_name);
        call_user_func_array(array(&$engin, $method_name), array_slice($R->request_array, $R->count_external_url));
    }
    elseif ($R->path === 'front_end/' &&  method_exists($engin, '_index')) {
        if(count($R->request_array)>=2){
            define('PATH_URL', $class_name . '/slug/' . $method_name);
        }else{
            define('PATH_URL', $class_name . '/' . $method_name);
        }
        call_user_func_array(array(&$engin, '_index'), array_slice($R->request_array, 1));
    }
    elseif ($R->path === 'front_end/') {
        define('PATH_URL', $class_name . '/index/' . $method_name);
        call_user_func_array(array(&$engin, 'index'), array_slice($R->request_array, 1));
    }
    elseif ($R->path === 'dashboard' &&  method_exists($engin, '_index')) {
        // pr($R->request_array,true);
        if(count($R->request_array)>2){
            define('PATH_URL', $class_name . '/slug/' . $method_name);
        }else{
            define('PATH_URL', $class_name . '/' . $method_name);
        }
        call_user_func_array(array(&$engin, '_index'), array_slice($R->request_array, 2));
    }
    else {
        $debug = false;
    }
}else{ 
    $debug = false;
    $class_name  = 'errors';
    $method_name = 'index';
    require_once 'application/control/errors.php'; 
}

if ($debug === false) { 
    if ($path == "back_end" || $path == "admin") {
        header("location:" . HOST . 'admin/err');
        exit;
    } else {
        $engin = new $class_name; 
        if (method_exists($engin, $R->method)) {
            define('PATH_URL', $class_name . '/' . $method_name); 
            error_404(get_instance(),true);
        }else{
            error_404(get_instance(),true); 
        } 
    }
}
