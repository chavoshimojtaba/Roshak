<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class tag extends Controller{
  
    public function __construct()
    {
        parent::__construct(); 
    }
    
    public function index ($type='product')
    {          
        $this->display(); 
    } 

    public function add ($id=0)
    {       
        if($id>0){ 
            $this->load->model('model_tag'); 
            $res = $this->model_tag->get_tag_getail($id);
            if($res->count>0){
                $tag = $res->result[0];
                $tag['short_desc'] = decode_html_tag($tag['short_desc'],true);   
                $tag['desc'] = decode_html_tag($tag['desc'],true);   
                $tag['slug'] = decode_html_tag($tag['slug'],true);  
                $tag['title'] = decode_html_tag($tag['title'],true);   
                $tag['meta'] = decode_html_tag($tag['meta'],true); 
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
