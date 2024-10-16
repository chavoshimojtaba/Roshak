<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class plan extends Controller{
  
    public function __construct()
    {
        parent::__construct(); 
    }

    public function index ()
    {        
        $this->display(); 
    } 
    public function add ($id=0)
    {        
		if($id>0){
            $this->load->model('model_plan'); 
            $res = $this->model_plan->get_plan_getail($id);
            if($res->count>0){
                $tag = $res->result[0];
                $tag['desc'] = decode_html_tag($tag['desc'],true);  
                $tag['title'] = decode_html_tag($tag['title'],true);   
            }  
            $this->html->set_data($tag); 
        }
        $this->display(); 
    } 
 

    private function display ()
    {
        out([
            'content' => $this->html->tab_links(
                [], 
                min_template(
                    $this->html->get_string('array'),
                    [],
                    $this->router->method
                    ),
                $this->router->method
                )
        ],'admin'); 
    }
}
