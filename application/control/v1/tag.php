<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class tag {

    private $app;
	private $alldata = [];

    public function init($app,$get) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_tag'); 
	}

    function list($param,$get)
    {

        $res = $this->app->model_tag->get_list($get);
        $this->alldata['total'] = $res->total;
        if ($this->alldata['total'] > 0)
        {
            foreach ($res->result as &$value) {
                $value             = decode_html_tag($value ,true);
                $value['slug']     =  $value['slug'].'-tag';
                $value['createAt'] = g2pt($value['createAt']);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function search($param,$get)
    {

		$res = $this->app->model_tag->search($get);
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

    function add($param,$get)
    {
		$res = $this->app->model_tag->add_tag($get);
        if ($res->insert_id > 0) {
			$get['cid'] = $res->insert_id;
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update($param,$get)
    {
        $res = $this->app->model_tag->update_tag($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function delete($param,$get)
    {
        $res = $this->app->model_tag->delete_tag($param[0] );
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}