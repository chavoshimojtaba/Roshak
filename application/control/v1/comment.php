<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class comment {

    private $app;
	private $alldata = [];

    public function init($app,$get) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_comment');
        if(isset($get['type'])){
            $this->app->model_comment->_table     = "`tp_".$get['type']."_comments`";
            $this->app->model_comment->_tableType =  $get['type'] ;
        }
	}

    function list($param,$get)
    {

		$res = $this->app->model_comment->get_list($get);
        $this->alldata['total'] =$res->total;
		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2pt($value['createAt']);
                $res->result[$key]['text']     = decode_html_tag($value['text'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function view($param,$get)
    {

        $res = $this->app->model_comment->get_comment($get,$get['type']);
		if ($res->count > 0)
        {
            $data = [];
            foreach ($res->result as $key => $value) {
                $value['createAt']  = g2pt($value['createAt']);
                $value['text']     = decode_html_tag($value['text'],true);
                if($value['pid'] == 0){
                    $data[$value['id']][]  = $value;
                }else{
                    $data[$value['pid']][]  = $value;
                }
            }
            $res_data = [];
            foreach ($data as $k => $v) {
                $res_data = array_merge($res_data,$v);
            }
            $this->alldata['data']   = $res_data;
            $this->alldata['status'] = 1;
			$this->app->_response(200,$this->alldata);
        }else{
            $this->app->_response(404, [
                'status' => 'fail' ,
				'msg'    => 'not found' ,
				'error'  => 2
			]);
		}

    }

    function content_comments($param,$get)
    {
        $this->alldata['total'] = 0; 
		$res = $this->app->model_comment->get_content_comment($get,$get['type']); 
		if ($res['count'] > 0)
        {
            $this->alldata['total'] = $res['total'];
            $data = [];
            foreach ($res['data'] as $key => $value) {
                $value['createAt']  = g2pt($value['createAt']);
                $value['text']     = decode_html_tag($value['text'],true);
                if($value['pid'] == 0){
                    $data[$value['id']][]  = $value;
                }else{
                    $data[$value['pid']][]  = $value;
                }
            } 
            $res_data = [];
            foreach ($data as $k => $v) {
                $res_data = array_merge($res_data,$v);
            }
            $this->alldata['data']   = $res_data;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function add($param,$get)
    { 
		$res = $this->app->model_comment->add_tag($get);
        if ($res->insert_id > 0) {
			$get['cid'] = $res->insert_id;
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function reply($param,$get)
    {
        $get['mid'] 	= 0;
		$get['uid'] = $_SESSION['uid'];
		$get['rate'] = 0;
		$get['publish'] = 'publish';
        if($get['pid']>0){
            $detail  = $this->app->model_comment->get_detail($get['pid']);
            $get['parent_mid']     = $detail['mid'];
            $get['path']    = $detail['path'];
        }else{
            $get['parent_mid']     = $get['mid'];
        }
        
		$res = $this->app->model_comment->add_reply($get);
        if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update($param,$get)
    { 
		$res = $this->app->model_comment->set_publish($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function edit($param,$get)
    { 
        $this->app->model_comment->_table     = "`tp_product_comments`";
        $this->app->model_comment->_tableType =  'product' ;
		$res = $this->app->model_comment->edit($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function del_comment_product($param,$get)
    {
        $this->app->model_comment->_table     = "`tp_product_comments`";
        $this->app->model_comment->_tableType =  'product' ;
        $res = $this->app->model_comment->delete_comment($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function del_comment_blog($param,$get)
    {
        $this->app->model_comment->_table     = "`tp_blog_comments`";
        $this->app->model_comment->_tableType =  'blog' ;
        $res = $this->app->model_comment->delete_comment($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}
