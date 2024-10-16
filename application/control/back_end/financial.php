<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class financial extends Controller{
 
    protected $block        = '';
    public $loop            = []; 
    
    public function __construct()
    {
        parent::__construct(); 
    }

    public function index ()
    {      
        $this->display(); 
    } 
    
    public function discount_codes ()
    {      
        $this->display(); 
    }  
    public function designer_share ()
    {      
        $this->load->model('model_financial');
        $designer_share = $this->model_financial->designer_share(); 
        $this->html->set_data($designer_share);
        $this->display(); 
    }  
    private function display ()
    { 
        out([
            'content' => $this->html->tab_links(
                [], 
                min_template(
                    $this->html->get_string('array'),
                    $this->loop,
                    $this->router->method
                    ),
                $this->router->method
                )
        ],'admin'); 
    }
}
