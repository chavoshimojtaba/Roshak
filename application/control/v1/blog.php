<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
} 
class blog {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;  
        $this->app = $app;
        $this->app->load->model('model_blog'); 
	} 
    
    function view($param,$get)
    {         
        $res = $this->app->model_blog->get_blog_detail($get['id']);   
        if ($res->count > 0)
        { 
            $this->app->load->model('model_comment'); 
            $res_comment = $this->app->model_comment->get_comment($get['id']);  
            if ($res_comment->count > 0)
            { 
                $this->alldata['status'] = 1;  
                $this->alldata['comments'] = $res_comment->result;  
            }   
            $this->alldata['status'] = 1;  
            $this->alldata['data'] = $res->result[0];  
        }   
        $this->app->_response(200,$this->alldata);
    }  
    
    function edit($param,$get)
    {       
        $this->alldata['count'] = 0;  
        if(isset($get['id']) && $get['id'] > 0){ 
            $res = $this->app->model_blog->get_blog($get['id']);  
            if ($res->count > 0)
            { 
                $this->alldata['count'] = 1;  
                $this->alldata['status'] = 1;  
                $this->alldata['data'] = $res->result[0];  
            }  
        }
        $this->app->load->model('model_category'); 
		$res_cat = $this->app->model_category->get_list(1);  
		if ($res_cat->count > 0)
        {
            $this->alldata['status'] = 1;  
            foreach ($res_cat->result as $key => $value) {   
                $res_cat->result[$key]['title']      = decode_html_tag($value['title'],true);   
            } 
            $this->alldata['category'] = $res_cat->result;  
        }
        $this->app->_response(200,$this->alldata);
    }  

    function list($param,$get)
    {   
        $res = $this->app->model_blog->get_list($get); 
        
        if ($res->count > 0)
        {
            $this->alldata['total'] = $this->app->model_blog->get_count($get);  
            foreach ($res->result as $key => $value) {  
                $res->result[$key]['createAt']   = g2pt($value['createAt']);  
                $res->result[$key]['title']      = decode_html_tag($value['title'],true);   
            } 
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;  
        }  
        $this->app->_response(200,$this->alldata);
    }  

    function add($param,$get)
    {         
        $res = $this->app->model_blog->add_blog($get);  
        if ($res->insert_id > 0) {  
            $this->alldata['status'] = 1;   
            $this->alldata['data'] = $res->insert_id;   
        }
        $this->app->_response(200,$this->alldata);
    }  

    function update($param,$get)
    {   
        $res = $this->app->model_blog->update_blog($param[0],$get);    
        if ($res->affected_rows > 0) { 
            $this->alldata['status'] = 1;   
            $this->alldata['data'] = $res->affected_rows;   
        } 
        $this->app->_response(200,$this->alldata);
    }  

    function delete($param,$get)
    {        
        $res = $this->app->model_blog->delete_blog($param[0]);  
        if ($res->affected_rows > 0) { 
            $this->alldata['status'] = 1;   
            $this->alldata['data'] = $res->affected_rows;   
        }   
        $this->app->_response(200,$this->alldata);
    }  
}  