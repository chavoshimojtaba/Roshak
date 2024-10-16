<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class log{


    private $controll;

    private $data = array();

    private $session_uid = 0;

    private $router;

    private $sec;


    function set_up ($router,$security)
    {
        $this->router = $router;
        $this->sec    = $security;

        $this->check_ip ();
        $this->session_uid = isset($_SESSION['uid'])?$_SESSION['uid']:0;
        $this->usesp_online ();
       $this->log_url();
       $this->log_ip();
    }

    function check_ip ()
    {
        $m =  &load_class('model_log_error','model');

        $res_quarantine = $m->check_in_quarantine($this->router->ip());
        if ( $res_quarantine->count > 0 )
        {
            if ( $res_quarantine->result[0]['total'] > 0 )
            {
                if ( $this->router->class != 'errors' )
                {
                    header("location:".HOST.'errors/ban');
                    exit;
                }
            }
        }

        $res = $m->check_ip($this->router->ip());
        if ( $res->count > 0 )
        {
            if ( $res->result[0]['total'] > 30 )
            {
                if ( $this->router->class != 'errors' )
                {//echo 'ali';
                    $m->add_quarantine($this->session_uid,$this->router->ip());
                    header("location:".HOST.'errors/ban');
                    exit;
                }
            }
        }

    }

    function log_ip ()
    {
        $url = "http://api.db-ip.com/v2";
        $key = "854c965b0bb80d1279db05cc9e77585916547e28";
        $ip  = $this->router->ip();

        if ( $_SESSION[$this->router->agent()] != 'SET' )
        {
            $_SESSION[$this->router->agent()] = 'SET';

            try{
                $response = @file_get_contents("$url/{$key}/{$ip}");
                $o = @json_decode($response);

                if ( !isset($obj->error) )
                {
                    $m =  &load_class('model_log','model');
                  /*   $array_field = array(
                        'continentCode'  ,
                        'continentName'  ,
                        'countryCode'    ,
                        'countryName'    ,
                        'currencyCode'   ,
                        'phonePrefix'    ,
                        'stateProv'      ,
                        'district'       ,
                        'city'           ,
                        'geonameId'      ,
                        'zipCode'        ,
                        'latitude'       ,
                        'longitude'      ,
                        'gmtOffset'      ,
                        'timeZone'
                    ); */
                    $array_field = array(
                        'continentCode'  ,
                        'continentName'  ,
                        'countryCode'    ,
                        'countryName'    ,
                        'currencyCode'   ,
                        'phonePrefix'    ,
                        'district'       ,
                        'geonameId'      ,
                        'zipCode'        ,
                        'latitude'       ,
                        'longitude'      ,
                        'gmtOffset'      ,
                        'timeZone'
                    );
                    if (isset($o))
                    {
                        foreach ( $o as $field=>$value)
                        {
                            if ( in_array($field, $array_field) )
                            {
                                $field = $this->sec->clear(strtolower($field));
                                $value = $this->sec->clear($value);
                                $qu[$field] = " `tp_{$field}`='{$value}'";
                            }
                        }

                        if ( isset($qu) && count($qu) > 0 )
                        {
                            $qu['ipAddress'] = " `tp_ipAddress` = '$ip'";
                            $m->insert_log_ip(implode(',',$qu));
                        }
                    }
                }
            }
            catch (Exception $ex)
            {

            }
        }
    }

    function usesp_online ()
    {
		$session = $_SESSION['user_token'];
		$time = time();
		$time_check = $time-600;

		$m =  &load_class('model_log','model');

        $m->exist_user_online($this->session_uid,$session,$this->router->ip());

		if ( $m->result->count > 0 )
		{
		   $m->update_user($time,$this->session_uid,$session,$this->router->ip());
		}
		else
		{
		    $m->insert_user($time,$this->session_uid,$session,$this->router->ip());
		}
		$m->remove_old_record($time_check,$this->session_uid,$session,$this->router->ip());
    }

    function log_url ()
    {
        if ( $this->router->class == 'src' ) return false;
        $m      =  &load_class('model_log','model');
        $req    = $this->clean(urldecode($_SERVER['REQUEST_URI']));
        $dir    = $this->router->dir_file;
        $class  = $this->router->class;
        $method = $this->router->method;
        $ip     = $this->router->ip();
        $mac    = '';//$this->router->mack_address();
        $agent  = $this->router->agent();

        $data   = $this->clean(array_to_pipe($_POST));
        $ajax   = (int)$this->router->is_ajax();
        $ref    = isset($_SERVER['HTTP_REFERER']) ? $this->clean($_SERVER['HTTP_REFERER']) : '';
        $m->log_url($this->session_uid,$dir,$req,$class,$method,$ip,$agent,$data,$ajax,$mac,$ref);
    }

    function clean ($string)
    {
        $clean = [
            'script',
            '<script>',
            '</script>',
            'javascript',
            'document.cookie',
            'document.write',
            '.parentNode',
            '.innerHTML',
            'window.location',
            '-moz-binding',
            '<!--',
            '-->',
            '<![CDATA[',
            '<comment>',
            'SELECT',
            'select',
            '../',
            './',
            '\\',
            'object',
            'embed',
            'form',
            'php',
            '<?',
            'expression',
            'system',
            'fopen',
            'fsockopen',
            'file',
            'file_get_contents',
            'readfile',
            'unlink',
            'cmd',
            'passthru',
            'eval',
            'exec',
            '..',
            '--'
        ];


        $array  = array(
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
        $string =  str_replace($clean, '', $string);
        $string =  str_replace($array, $_rep, $string);
        return $string;
    }


    function string_request1 ($array=array())
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
}
