<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class ticket {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_tickets');
	}


    function list($param,$get)
    {
		$res = $this->app->model_tickets->get_tickets($get);
        // pr($res,true);
        $this->alldata['total'] = $res->total;
		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2pt($value['createAt']);
                $res->result[$key]['comment']     = decode_html_tag($value['comment'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function view($param,$get)
    {
        $this->alldata['action_perm']   = false;

        $res = $this->app->model_tickets->get_ticket($param[0]);

		if ($res->count > 0)
        {
            $this->app->model_tickets->set_read($param[0]);
                $this->alldata['false']   = true;
                $ids = [];
            $this->alldata['files']   = 0;
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2pt($value['createAt']);
                $res->result[$key]['email']   = decode_html_tag($value['email'],true);
                $res->result[$key]['comment']   = decode_html_tag($value['comment'],true);
                $ids[] = $value['comment_id'];
            }
            $this->alldata['files'] = [];

            if($res->result[0]['has_file'] == 'yes'){
                $this->app->load->model('model_file');
                $res_file = $this->app->model_file->get_resource_files(implode(',',$ids),'ticket');
                $files = [];
                foreach ($res_file->result as $v) {
                    $files[$v['rid']][] = $v;
                }
                $this->alldata['files'] = $files;
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
            if($_SESSION['is_admin'] == 1  || $_SESSION['rid'] == $res->result[0]['rid']){
                $this->alldata['action_perm']   = true;

            }
            $this->alldata['roles'] = '<option value="">انتخاب کنید</option>';
            $this->app->load->model('model_role');
            $res_r = $this->app->model_role->get_list_all();
            foreach ($res_r->result as $key => $v) {
                if($v['id'] != $res->result[0]['rid']){
                    $v = decode_html_tag($v,true);
                    $this->alldata['roles'] .= '<option value="'.$v['id'].'">'.$v['name'].'</option>';
                }
            }
        }

		$this->app->_response(200,$this->alldata);
    }

   /*  function add($param,$get)
    {
		$res = $this->app->model_tickets->add_tag($get);
        if ($res->insert_id > 0) {
			$get['cid'] = $res->insert_id;
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    } */

    function reply($param,$get)
    {
		$get['umid'] 	= $_SESSION['uid'];
		$res = $this->app->model_tickets->add_reply($get);
        if ($res->insert_id > 0) {
            $get['files'] = json_decode($get['files'],true);
            $this->alldata['files'] = [];

            if(count($get['files']) > 0){
                $get['type'] = 'ticket';
                $get['pbid'] = $res->insert_id;
                $this->app->load->model('model_file');
                $this->app->model_file->add_file_relation($get);
		        $res = $this->app->model_tickets->has_file($get['tid']);
                foreach ($get['files'] as $key => $value) {
                    $this->alldata['files'][$get['pbid']][] = [
                        'dir'=> $value['file'],
                        'rid'=>$get['pbid']
                    ];
                }
            }
			$this->alldata['status'] = 1;
            $this->alldata['data'] = [
				'pbid'=>$get['pbid'],
				'pic'=>$_SESSION['pic'],
				'full_name'=>$_SESSION['full_name'],
			];
        }
        $this->app->_response(200,$this->alldata);
    }

    function refer($param,$get)
    {
		$res = $this->app->model_tickets->refer_ticket($get['role'],$param[0]);
        if ($res->affected_rows > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update($param,$get)
    {
		$res = $this->app->model_tickets->set_publish($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function close($param,$get)
    {
		$res = $this->app->model_tickets->close_ticket($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function delete($param,$get)
    {
        $res = $this->app->model_tickets->delete_ticket($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}