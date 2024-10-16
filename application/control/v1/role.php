<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class role {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_role');
	}


    function list($param,$get)
    {
        $res = $this->app->model_role->get_list($get);
        $this->alldata['total'] = $res->total;

		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2pt($value['createAt']);
                $res->result[$key]['name']      = decode_html_tag($value['name'],true);
                $res->result[$key]['desc']      = decode_html_tag($value['desc'],true);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function permission_list($param,$get)
    {
        $res = $this->app->model_role->role_permissions($param[0]);
		if ($res->count > 0)
        {
            /* foreach ($res->result as $key => $value) {
                $data[$value['reid']][$value['perm']]= $value;
            }  */
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function permissions($param,$get)
    {
        $res = $this->app->model_role->add_role_permissions($get);
		if ($res->insert_id > 0)
        {
            $this->alldata['data'] =$res->insert_id;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add($param,$get)
    {
        $res = $this->app->model_role->add_role($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update($param,$get)
    {
        $res = $this->app->model_role->update_role($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete($param,$get)
    {
        $res = $this->app->model_role->delete_role($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}