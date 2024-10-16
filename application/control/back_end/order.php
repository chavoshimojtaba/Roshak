<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class order extends Controller{
 
    protected $block        = '';
    public $loop            = []; 
    
    public function __construct()
    {
        parent::__construct(); 
    }

    public function index ($id=0)
    {
        if($id>0){
            $this->load->model('model_member');
            $res = $this->model_member->member_detail($id);  
            if($res->count > 0){  
                $data = decode_html_tag($res->result[0],true);        
                $data['orders_for']       = '('.$data['name'].' '.$data['family'].')'; 
                $this->html->set_data($data);
            }
             
             
        }
        $this->display(); 
    } 
    
    public function view ($id)
    {      
        $this->load->model('model_order'); 
        $res = $this->model_order->get_order_detail($id); 
        if($res->count > 0){  

            $data = $res->result[0];        
            $data['product_title']       = decode_html_tag($data['product_title'],true); 
            $data['total_price'] = toman($data['total_price']);   
            $data['product_price'] = toman($data['product_price']);  
            $data['createAt'] = g2pt($data['createAt']);   
           
        }
         
         
        $this->html->set_data($data);
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
