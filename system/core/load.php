<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class load {
	

    public static $loaded = array ();

    public $conf = array();
    
    public $clone = NULL;
    
    public $reset = FALSE;
    
    public $conf_name = '';
    
    function is_loaded ( $name )
    {
        $this->loaded[$name] = $name;
    }

    function model ($name,$object_name=NULL,$params=NULL,$dir=DIR_MODEL)
    {
        $name = trim($name);
        if ( isset ( $this->loaded[$name]) ) return TRUE;
        if ( file_exists ($dir.$name.EXT ) )
        {
            require_once ($dir.$name.EXT);
            $Controller = &get_instance();
            if ( ! is_null ($object_name) ) $object = $object_name;
            else $object = $name;
            $Controller->$object = new $name($params);
            //$this->clone = clone $Controller;
        }
        else
        {
            exit ("not found :[$name] in ".$dir.$name.EXT);
        }
    }

    function library ($name,$params=NULL,$object_name=NULL,$dir=DIR_LIBRARY)
    {
        if ( isset ( $this->loaded[$name]) ) return TRUE;

        if ( file_exists ($dir.$name.EXT ) )
        {
            require_once ($dir.$name.EXT);
            $Controller = &get_instance();
            if ( ! is_null ($object_name) ) $object = $object_name;
            else $object = $name;
            $Controller->$object = new $name($params);			
        }
        else
        {
            exit ("not found :[$name] in ".$dir.$name.EXT);
        }
    }



    function main_model ($name,$params=NULL,$object_name=NULL,$dir=DIR_MODEL)
    {
        $name = trim($name);
        $C = &get_instance();

        $dir = trim($dir);

        if ( isset ( $this->loaded[$name]) ) return TRUE;

        if ( file_exists ($dir.$name.EXT ) )
        {
            require_once ($dir.$name.EXT);
            $Controller = &get_instance();
            if ( ! is_null ($object_name) ) $object = $object_name;
            else $object = $name;
            $Controller->$object = new $name($params);			
        }
        else
        {
            exit ("not found :[$name] in ".$dir.$name.EXT);
        }

    }


    function config ($name,$dir='',$value=array(),$msg=TRUE)
    {
        static $_configs = array();
        
        $this->conf_name = $name;
        
        if ( (isset($_SESSION['post']) && count ($_SESSION['post']) > 0 ) && is_array($value))
        {
            $value = array_merge($value,$_SESSION['post']);
        }

        if (isset($_configs[$name]))
        {
            return $_configs[$name];
        }
        
        $i=0;
        foreach (array(APPPATH.'config/', APPPATH.'config/'.$dir,BASEPATH.'config/',$dir) as $path)
        {
            if (file_exists($path.$name.EXT))
            {
                require_once($path.$name.EXT);
                $_configs[$name] = @$$name;
                $i=1;
                break;
            }
        }
        if ( $i == 1 )
        {
            $this->conf[$name] = $_configs[$name];
            return $_configs[$name];
        }
        else
        {
            if ($msg == TRUE)
            {
                exit ("config not found :".$path.$name.EXT);
            }
            else
            {
                get_error ( array('config'=>_MSG_your_request_could_not_be_processed),'config',"config not found $name");
                redirect(HOST.'errors/e_404');exit;
            }
        }
    }
    
    function get_conf_nes ($name)
    {
        $field = array();
        $C = &get_instance(); 
        if ( isset($this->conf[$name]) )
        {
            foreach ($this->conf[$name] as $index=>$array_conf)
            {
                if ( !in_array($index,  $C->html->not_item_input) ) 
                {
                    if ( isset($array_conf['change_nes']))
                    {
                        $check = $array_conf['change_nes'][0];
                        $check_value = $array_conf['change_nes'][1];
                        
                        if ( isset($_POST[$check]) && !in_array($_POST[$check],$check_value))
                        {
                            $array_conf['ness'] = false;
                        }
                        else 
                        {
                           //$array_conf['ness'] = TRUE;
                        }
                    }
                    if ( isset ($array_conf['ness']) && $array_conf['ness'] == TRUE )
                    {
                        $field[$index] = $array_conf;
                    }                    
                }
            }
        }
        return $field;
    }
    
    function get_conf_fieldname ($name,$not=array())
    {
        $field = array();
        $fields = array();
        $full_field = array();
        $C = &get_instance();
        if ( isset($this->conf[$name]) )
        {
            foreach ($this->conf[$name] as $index=>$array_conf)
            {
                $item = isset($array_conf['item'])?$array_conf['item']:'!';
                
                if ( !in_array($index,$C->html->not_item_input) && !in_array($item,$C->html->not_item_input) && !in_array($array_conf['name'],$not) ) 
                {
                    $array_conf['name'] = trim($array_conf['name']);
                    $field[$array_conf['name']] = $array_conf['name'];
                    if ( isset($array_conf['change_nes']) )
                    {
                        $change = $array_conf['change_nes'];
                        if ( (isset($_POST[$change[0]]) && !in_array($_POST[$change[0]], $change[1])) &&  (isset($array_conf['change_nes'][2]) && $array_conf['change_nes'][2] == 'off'))
                        {
                            continue;
                        }
                    }
                    $fields[$array_conf['name']] = $array_conf;
                }
                
                
                $full_field[$index] = $array_conf;
            }
        }
        return array($field,$full_field,$fields);
    }
    
    function js ($file)
    {
        $file = 'file/default/js/'.$file.'.js';
        if (is_file($file))
        {
            return '<script type="text/javascript" src="'.HOST.$file._NOCASH_.'"></script>';
        }
        return NULL;
    }
}