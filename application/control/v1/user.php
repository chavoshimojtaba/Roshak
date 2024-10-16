<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class user {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_users');
	}

    function log_in_out($param,$get)
    {
        $res = $this->app->model_users->login_list($get);
        $this->alldata['total'] = $this->app->db->total_count('user_login');
        $data = [];
		if ($res->count > 0)
        {
            foreach ($res->result as  $value) {
                $value['createAt']    = g2pt($value['createAt'],true);
				$data[]             = $value;
            }
            $this->alldata['data'] = $data;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function list($param,$get)
    {
        $res = $this->app->model_users->get_list($get);
        $this->alldata['total'] = $res->total;
        $data = [];
		if ($res->count > 0)
        {
            foreach ($res->result as  $value) {
                $value['family']    = decode_html_tag($value['family'],true);
                $value['email']     = decode_html_tag($value['email'],true);
                $value['name']      = decode_html_tag($value['name'],true);
				$data[]             = $value;
            }
            $this->alldata['data'] = $data;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add($param,$get)
    {
		$get['activation_code'] = generate_verify_code();
        $pass = rand(10000000,99999999);
        $params['password'] = hash_password($pass);

		$res = $this->app->model_users->add_user($get);
        if ($res->insert_id > 0) {
            //send sms password
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update($param,$get)
    {
        $res = $this->app->model_users->update_profile($get,$param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update_profile($param,$get)
    {
        $res = $this->app->model_users->update_profile($get,$_SESSION['uid']);
        if ($res->affected_rows > 0) {
            foreach ($get as $key => $value) {
                if ($value != '') {
                    $_SESSION[$key] = $value ;
                }
            }
            $_SESSION['full_name'] = $get['name'].' '.$get['family'] ;
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }


    function change_password($param,$get)
    {
        $get['new_password'] = md5($get['new_password']);
        $get['cur_password'] = md5($get['cur_password']);
        $res = $this->app->model_users->change_pass($get);
        if ($res['status'] > 0) {
            $_SESSION['password'] = $get['new_password'];
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res['status'];
        }
        $this->app->_response(200,$this->alldata);
    }

    function delete($param,$get)
    {
        $res = $this->app->model_users->delete_user($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}