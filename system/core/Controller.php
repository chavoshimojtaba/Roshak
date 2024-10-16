<?php


class Controller {
	
	
    private static $instance;
	
	var $post = array ();

    /**
     * Constructor
     */
    public function __construct()
    {
        self::$instance =& $this;
        // Assign all the class objects that were instantiated by the
        // bootstrap file (CodeIgniter.php) to local class variables
        // so that CI can run as one big super object.
        foreach (is_loaded() as $var => $class)
        {
            $this->$var =& load_class($class);
        }

/*		$this->load =& load_class('Loader', 'core');

        $this->load->initialize();

        log_message('debug', "Controller Class Initialized");*/		
    }

    public static function &get_instance()
    {
        return self::$instance;
    }

	
}