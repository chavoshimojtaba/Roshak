<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class session {


    private $login = false;

    var $conf_session = array ();

    var $session = array ();
 
    function __construct ()
    {
        foreach (load_config ('session') as $name=>$value)
            $this->$name = $value;

        if( isset($_POST) && count($_POST) > 0 )
        {
            $_SESSION['post'] = $_POST;
        }
    }


    function check_login_member ()
    { 
        if (!is_array($_SESSION) || !isset($_SESSION['member']) || !isset($_SESSION['mid'])) {  
            $this->unset_session();
            $login = false;
        }else{ 
             $login = TRUE; 
        }
        return  $login ;
    }

    function check_login ()
    { 
        $R = &load_class('router','core'); 
        $login = TRUE;
        // pr($_SESSION,true);
        if (isset($_SESSION['path']) && $_SESSION['path'] != 'api')
        { 
            $path = isset($R->request_array[0]) ?$R->request_array[0]:'null_dir';   
            if( isset($R->extra_url[$path]) AND $_SESSION['path'] != $path){
                 $this->unset_session();
                $login = false;
            }
        } 
        if ( $login === false ) return false;
        else return $this->check_login_db();
    }

    function check_login_db ()
    {
        $M =  &load_class('model_session','model');

        if (isset($_SESSION['login']) && $M->check_login ($_SESSION['login'],$_SESSION['ip'],$_SESSION['uid'],$_SESSION['token'],$_SESSION['path']) === TRUE )
        {
             return TRUE;
        }
        else
        { 
            $this->unset_session();
            return FALSE;
        }
    } 

    function _set_login ($array=array(),$path=TRUE)
    {
        $C =  &get_instance();
        $this->set_token ($array);
        date_default_timezone_set('Asia/Tehran');
        $array['start'] = date ("Y-m-d H:i:s");
        if ( $path == TRUE )
        {
            $array['path']  = $C->router->request_array[0];
        }
        $this->set_session ($array);
        $C->load->model("model_session");
        $res = $C->model_session->insert_login ($C->router->mack_address());
        if ( isset( $res->insert_id)  )
        {
            $this->set_session('login',$res->insert_id );
        }
        else
        {
            return FALSE;
        }
    }

    //ip agent pass uid date

    function set_token ($array=array())
    {
        $str = (isset($array['uid']))?$array['uid']:$array['mid'].$array['agent'].$array['ip'].date("Y-m-d H:i:s");
        $token = md5 ($str);
        $this->set_session('token',$token);
    }

    function set_session($index,$value=false)
    {
        if ( is_array ( $index ) )
        {
            foreach ( $index as $k=>$v)
                $_SESSION[$k] = $v;
        }
        else if (is_array ( $value ))
        {
            foreach ( $value as $k=>$v)
                $_SESSION[$index][$k] = $v;
        }
        else
        {
            $_SESSION[$index] = $value;
        }
    }

    function set_session_by_array ($index,$data)
    {
        foreach ( $index as $k=>$v )
        {
            $_SESSION[$v] = $data[$v];
        }
    }

    function unset_session ()
    {
        session_destroy();
    }

    function is_session ($name)
    {
        if (isset($_SESSION[$name]))
            return $_SESSION[$name];
        return false;
    }


    function remove ($name)
    {
        unset($_SESSION[$name]);
    }

    /*
    * Created  : Tue Aug 09 2022 10:51:41 AM
    * Author   : Chavoshi Mojtaba
    * return   : void
    */

    function log_out ()
    {
        $this->empty_cache();
        session_destroy();
        unset($_SESSION);
        $_SESSION = array();  
        /* foreach ($this->check as $name)
        {
            $this->remove($name);
        } */
    }


    public function empty_cache ()
    {
        unset($_SESSION['post']);
    }

    function check_expire ()
    {
    }
}
