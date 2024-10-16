<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class router {

    var $url_array = array ();

    var $request_array = array ();

    var $class = '';

    var $method = '';

    var $argus  = FALSE;

    var $dir_file = '';

    var $is_login = FALSE;

    var $is_check_user = FALSE; 

    function __construct ()
    {

        foreach (load_config ('router') as $name=>$value)
            $this->$name = $value;

        if ( !isset( $_SESSION[$this->agent()]) )
        {
            $_SESSION[$this->agent()] = $this->ip();
        }

        if ( !isset($_SESSION['user_token']) )
        {
            $_SESSION['user_token'] = hash_password(str_random(5).$this->ip().$this->agent().date("Y-m-d H:i:s").str_random(5));
        }

        $host = $_SERVER['HTTP_HOST'];

        $this->host = $this->http.$host.SLASH.$this->folder;
        $this->dir_control = DIR_CONTROL;
        parse_str(parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY),$current_url_params);
        if(!str_contains($_SERVER['QUERY_STRING'],'admin')){
            // pr($_SERVER['QUERY_STRING'],true);
            $this->dir_view    = (isMobile())?DIR_VIEW.'_mobile/':DIR_VIEW;
            if(strpos($_SERVER['QUERY_STRING'],'api') < 0 && substr($_SERVER['QUERY_STRING'], -1) == '/'){
                    redirect($this->host.rtrim($_SERVER['QUERY_STRING'],'/'));
                exit;
            }
        }else{
            $this->dir_view    = DIR_VIEW;
        }
        if ( isset ( $_SERVER['QUERY_STRING'] ) AND !empty ($_SERVER['QUERY_STRING']) )
        {
            $this->request_array = explode ('/',$_SERVER['QUERY_STRING']);
        }

        if(!preg_match('/^([a-z])([a-z]|[-]){0,}$/',$this->request_array[0]) && isset ($this->request_array[0])){
            $this->url_array['class']   = 'invalid_url';
        }else{
            $this->url_array['class']   = isset ($this->request_array[0])?$this->clear_name($this->request_array[0]):$this->default_class;
        }
        $REQUEST_URI = explode ('/',$_SERVER['REQUEST_URI']);
        
        $this->url_array['method']  = isset ($this->request_array[1])?$this->clear_name($this->request_array[1]):$this->default_mthod;
        
        if($this->url_array['method'] !== $this->default_mthod && !in_array($this->url_array['class'],$REQUEST_URI)){ 
            $this->url_array['class']   = 'invalid_url';
        }
        $this->url_array['def_url'] = $this->host.$this->default_class.SLASH.$this->default_mthod;
        if ( isset($this->vip[$this->url_array['class']]) && !in_array($this->ip(),$this->vip[$this->url_array['class']]) )
        {
            exit('error : 405');
        }

        if (isset($this->extra_url[$this->url_array['class']]))
        {
            if ( !isset($this->request_array[1]) OR $this->request_array[1] == '')
            {
                $this->request_array[1] = $this->extra_url[$this->url_array['class']]['index'];
            }
            $this->url_array['def_url'] = $this->host.$this->request_array[0].SLASH.$this->extra_url[$this->url_array['class']]['index'].SLASH.$this->default_mthod;
            $this->url_array['not_check'] = $this->extra_url[$this->url_array['class']]['NotCheck'];
            $this->Curl = $this->host.$this->request_array[0].SLASH.$this->request_array[1];
            $this->dir_control   = $this->dir_control.$this->extra_url[$this->url_array['class']]['folder'];
            $this->path          = str_replace('/','',$this->extra_url[$this->url_array['class']]['folder']);
            $this->dir_view      = $this->dir_view.$this->extra_url[$this->url_array['class']]['folder'];
            $this->is_check_user = $this->extra_url[$this->url_array['class']]['login'];
            $this->url_array['class'] = isset ($this->request_array[1])?$this->clear_name($this->request_array[1]):$this->extra_url[$this->url_array['class']]['index'];
            $this->url_array['method'] = isset ($this->request_array[2])?$this->clear_name($this->request_array[2]):$this->default_mthod;
            $this->count_external_url = 3;
        }
        $this->analyz_request();

    }

    function analyz_request ()
    {
        if ( $this->url_array['method'] == '' ) $this->url_array['method'] = $this->default_mthod;
        //if ( $this->is_login === FALSE ) $this->url_array['class'] = $this->default_class;
        $this->set_request();
        $this->Routing();

    }

    private function Routing ()
    {
        $flag = 0;

        if ( is_dir (  $this->dir_control.$this->class ) )
        {
            if ( !is_file ( $this->dir_control.$this->class.SLASH.$this->class.EXT ) )
            {
                $flag = 1;
                $this->dir_file =  DIR_CONTROL.'errors'.EXT;
                $this->argus = $this->class;
            }
            else{
                $this->dir_file =  $this->dir_control.$this->class.SLASH.$this->class.EXT;
            }
        }
        else if ( is_file ( $this->dir_control.$this->class.EXT ) )
        {
            $this->dir_file = $this->dir_control.$this->class.EXT;
        }
        else
        {
            $flag = 1;
            $this->dir_file =  DIR_CONTROL.'errors'.EXT;
            $this->argus = $this->class;
        }

        if ( $flag == 1)
        {
            $this->count_external_url = 2;
            $this->dir_control = DIR_CONTROL;
            $this->dir_view = DIR_VIEW;
            $this->path = 'front_end/';
        }
    }

    private function set_request ()
    {
        $this->class  = $this->url_array['class'];
        $this->method = $this->url_array['method'];
        if (substr($this->method, 0,1) == '_' )
        {
            $this->class = 'errors';
            $this->method = 'index';
            $this->count_external_url = 2;
            $this->dir_control = DIR_CONTROL;
            $this->dir_view = DIR_VIEW;
            $this->path = 'front_end/';
        }
    }

    private function filter_name ($name)
    {
        return preg_replace("/[^a-zA-Z0-9_]+/", "", $name);
    }

    private function clear_name ($name)
    {
        $Security = & load_class('security', 'core');
        $name = $this->filter_name($name);
        $name = $Security->clear_request ($name);
        return $name;
    }

    function ip ()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    function agent ()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    function is_ajax ()
    {
       if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' )
       {
            return TRUE;
       }
       return FALSE;
    }

    function ref()
    {
        if ( !isset($_SERVER['HTTP_REFERER']) OR empty($_SERVER['HTTP_REFERER']) )
        {
            return false;
        }
        return true;
    }

    function query_string ()
    {
        if ( isset ( $_SERVER['QUERY_STRING'] ) AND !empty ($_SERVER['QUERY_STRING']) )
        {
            return $_SERVER['QUERY_STRING'];
        }
        else return $_SERVER['REQUEST_URI'];
    }

    function current_url ()
    {
        if ($this->Curl == '')
            return $this->host.$this->class.SLASH;
        return $this->Curl.SLASH;
    }


    function base_url ()
    {
        if ( $this->count_external_url == 2 )
            return $this->host;

        if (substr($this->host, -1) == '/')
        {
            return $this->host.$this->request_array[0].SLASH;
        }
        return $this->host.SLASH.$this->request_array[0].SLASH;
    }

    function path_url ()
    {
        $params = [
            $this->class,
            $this->method
        ];
        $counter = 0;
        foreach ($this->request_array as $key => $value) {
            if($counter){
                $params[] = $value;
            }
            if($value == $this->method){
                $counter = 1;
            }
        }
        return implode(SLASH,$params);
    }

    function mack_address ()
    {
        $ipAddress = $this->ip();
        $macAddr   = false;

        #run the external command, break output into lines
    /*     $arp   = `arp -a $ipAddress`;
        $lines = explode("\n", $arp);

        #look for the output line describing our IP address
        foreach($lines as $line)
        {
           $cols = preg_split('/\s+/', trim($line));
           if ($cols[0] == $ipAddress)
           {
               $macAddr=$cols[1];
           }
        } */
        return $macAddr;
    }


    function ref_url ()
    {
        $msg = array();

        if ( isset($_SERVER['HTTP_REFERER']) AND $_SERVER['HTTP_REFERER'] != '')
        {
            $ref_query = str_replace($this->host, '', $_SERVER['HTTP_REFERER'],$count);

            if ( $count > 0 )
            {
                return explode('/',$ref_query);
            }
            else
            {
                get_error(array('ref|from other url'=>_MSG_OTHER_URL),'danger','send data from other url');
                return false;
            }
        }
        get_error(array('ref|not set'=>_MSG_NOT_SET_REF),'danger','not set ref url');
        return false;
    }

    function request_get ()
    {
        $q = [];
        if ( isset($_SERVER['REQUEST_URI']) )
        {
            $get = explode('?',$_SERVER['REQUEST_URI']);
            if ( isset( $get[1]) )
            {
                $temp = $get[1];
                $q = queryStringToArray($temp);
                return $q;
            }
        }
        return $q;
    }
}
