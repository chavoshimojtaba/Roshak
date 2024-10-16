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
    function multi_select($param,$get)
    {
		$res = $this->app->model_util->multi_select($get['q'],$get['type']);
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

}