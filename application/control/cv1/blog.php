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
    
    function list($param,$get)
    {   
        $this->alldata['total'] = $this->app->model_blog->get_count($get);   

        if ($this->alldata['total'] > 0)
        {
            $res = $this->app->model_blog->get_list($get); 
            foreach ($res->result as &$value) { 
				$value['short_desc'] = decode_html_tag($value['short_desc'],true);  
				$value['desc'] 		 = decode_html_tag($value['desc'],true);  
				$value['title'] 	 = decode_html_tag($value['title'],true);
				$value['createAt'] 	 = g2p($value['createAt']); 
			} 
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;  
        }  
        $this->app->_response(200,$this->alldata);
    }  
}  