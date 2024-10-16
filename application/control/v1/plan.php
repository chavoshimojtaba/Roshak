<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
} 
class plan {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;  
        $this->app = $app;
        $this->app->load->model('model_plan'); 
	} 


    function list($param,$get)
    {     
        $res = $this->app->model_plan->get_list($get);  
        $this->alldata['total'] = $res->total;  
        
		if ($res->count > 0)
        {
            foreach ($res->result as $key => &$value) {  
                $value['createAt'] = g2pt($value['createAt']);  
                $value['price']    = toman($value['price'],true);  
                $value['title']    = decode_html_tag($value['title'],true);  
                $value['desc']     = decode_html_tag($value['desc'],true); 
            } 
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;  
        }  
        $this->app->_response(200,$this->alldata);
    }  

     
    function add($param,$get)
    {      
        $res = $this->app->model_plan->add_plan($get); 
        if ($res->insert_id > 0) { 
            $this->alldata['status'] = 1;   
            $this->alldata['data'] = $res->insert_id;   
        }
        $this->app->_response(200,$this->alldata);
    } 
	
    function update($param,$get)
    {      
        $res = $this->app->model_plan->update_plan($param[0],$get);    
        if ($res->affected_rows > 0) { 
            $this->alldata['status'] = 1;   
            $this->alldata['data'] = $res->affected_rows;   
        } 
        $this->app->_response(200,$this->alldata);
    }  
	
    function delete($param,$get)
    {        
        $res = $this->app->model_plan->delete_plan($param[0]);  
        if ($res->affected_rows > 0) { 
            $this->alldata['status'] = 1;   
            $this->alldata['data'] = $res->affected_rows;   
        }   
        $this->app->_response(200,$this->alldata);
    }  
	









}  