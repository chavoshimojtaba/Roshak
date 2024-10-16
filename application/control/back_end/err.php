<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class err extends Controller{ 
    
    public function __construct()
    {
        parent::__construct(); 
    }
    public function index ()
    {    
        $this->template->restart(_VIEW.$this->router->class.EXT,$this->router->dir_view);  
        $this->template->parse();
        $this->template->out(); 
    }  
}
