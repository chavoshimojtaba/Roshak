<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class config {
	
    var $config = array();


    var $_config_paths = array(APPPATH);


    var $is_loaded = array();


    function __construct ()
    {
        $this->config =& get_config();
    }
	
	
	
    function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace('.php', '', $file);
        $found = FALSE;
        $loaded = FALSE;

        $check_locations = defined('ENVIRONMENT')? array(ENVIRONMENT.'/'.$file, $file): array($file);

        foreach ($this->_config_paths as $path)
        {
            foreach ($check_locations as $location)
            {
                $file_path = $path.'config/'.$location.'.php';

                if (in_array($file_path, $this->is_loaded, TRUE))
                {
                    $loaded = TRUE;
                    continue 2;
                }

                if (file_exists($file_path))
                {
                    $found = TRUE;
                    break;
                }
            }

            if ($found === FALSE)
            {
                continue;
            }

            include($file_path);

            if ( ! isset($config) OR ! is_array($config))
            {
                if ($fail_gracefully === TRUE)
                {
                    return FALSE;
                }
                exit('Your '.$file_path.' file does not appear to contain a valid configuration array.');
            }

            if ($use_sections === TRUE)
            {
                if (isset($this->config[$file]))
                {
                    $this->config[$file] = array_merge($this->config[$file], $config);
                }
                else
                {
                    $this->config[$file] = $config;
                }
            }
            else
            {
                $this->config = array_merge($this->config, $config);
            }

            $this->is_loaded[] = $file_path;
            unset($config);

            $loaded = TRUE;
            break;
        }

        if ($loaded === FALSE)
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            exit('The configuration file '.$file.'.php does not exist.');
        }
        return TRUE;
    }	
}