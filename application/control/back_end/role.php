<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
class role extends Controller{
 
    protected $block        = '';
    public $loop            = []; 
    
    public function __construct()
    {
        parent::__construct(); 
    }

    public function index ()
    {
        $this->load->model('model_settings');
        $res = $this->model_settings->get_resources();
        if ($res->count > 0 ) {
            $this->html->resource = $res->result;  
        }
        $this->loop[] = 'resource'; 
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
