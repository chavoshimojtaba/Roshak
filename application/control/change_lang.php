<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class change_lang extends Controller{  
    function index ()
    {   
        if (isset($_COOKIE['def_lang']) && $_COOKIE['def_lang'] === 'en' ){  
            setcookie("def_lang", "", time() - 3600, '/'); 
            setcookie('def_lang', 'fa', time() + (8886400 * 30), "/");  
        }else if (isset($_COOKIE['def_lang']) && $_COOKIE['def_lang'] === 'fa' ){
            setcookie("def_lang", "", time() - 3600, '/'); 
            setcookie('def_lang', 'en', time() + (8886400 * 30), "/");  
        }else{ 
            setcookie("def_lang", "", time() - 3600, '/');
            setcookie('def_lang', 'en', time() + (8886400 * 30), "/");    
        }  
        $_SESSION['def_lang']  = $_COOKIE['def_lang'];  
        redirect(BASE_URL.'home');
        exit(); 
    }  
}
