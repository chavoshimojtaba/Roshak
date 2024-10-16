<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class util {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_util');
	}
    function slug_validation($param,$get)
    {  
        if (slug_is_valid($get['slug'], $get['table'], $get['id']) == 1) {
			$this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function get_events($param,$get)
    {
        $columns = [];
        $this->app->load->model('model_settings');
        $res = $this->app->model_settings->get_events($get);
         
        $this->alldata['total'] = 1000;
        if (1000  > 0)
        {
            foreach ($res->result as $key => $value) { 
                $res->result[$key]['title'] = decode_html_tag($value['title'],true); 
                $res->result[$key]['value'] = decode_html_tag($value['value'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function member_message($param,$get)
    {
        $this->app->load->model('model_pages');
		$res = $this->app->model_pages->add_member_message($get);
        if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function multi_select($param,$get)
    {
		$res = $this->app->model_util->multi_select($get['q'],'product_tags');
		if ($res->count > 0)
        {
            $data = [];
            foreach ($res->result as $key => $value) {
                $data[$key]['title'] = decode_html_tag($value['title'],true);
                $data[$key]['id']       = $value['id'];
            }
            $this->alldata['data']   = $data;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function search($param,$get)
    {
		$res = $this->app->model_util->seach_in_tags_and_designers_products($get['q']);
		if (count($res)> 0)
        {
            $data = [];
            foreach ($res as $key => $value) {
                foreach ($value as $k => $v) {
                    $v['type'] = $key;
                    $data[] = decode_html_tag($v,true);
                }
            }
            $this->alldata['data'] = $data;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
}