<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
require_once(DIR_CONTROL."upload.php");

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
        $this->alldata['total'] = $res->total; 
		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt'] = g2pt($value['createAt']);
                $res->result[$key]['comment']  = decode_html_tag($value['comment'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

	function add($param,$get)
    { 
		$files = json_decode($get['files'],true);
		$get['has_file'] = (count($files) > 0)?true:false; 
		$res = $this->app->model_tickets->add_tickets($get);
		if ($res->insert_id > 0) { 
			$this->alldata['status'] = 1;
			$this->alldata['data'] 	 = $res->insert_id;
		}

		$this->app->_response(200,$this->alldata);
	}

	function reply($param,$get)
    {

		// $get = $_POST;
		$get['umid'] = $_SESSION['mid'];
		$get['read'] = 'yes';
		$get['member_type'] = 'member';
		$get['tid'] = $get['id'];
		$get['files'] =isset($get['files'])?json_decode($get['files'],true):[];
		$res = $this->app->model_tickets->add_reply($get);
		if ($res->insert_id > 0) { 
			if(count($get['files'])>0){
				$files = [];
				foreach ($get['files'] as $key => $value) {
					$files[$value['id']] = $value['id'];
				}
				$get['files'] = $files;
				$get['type'] = 'ticket';
				$get['pbid'] = $res->insert_id;
				$this->app->load->model('model_file');
				$this->app->model_file->add_file_relation($get);
				$this->app->model_tickets->has_file($get['id']);
			} 
			$this->alldata['status'] = 1;
			$this->alldata['data'] 	 = $res->insert_id; 
		} 
	 

		$this->app->_response(200,$this->alldata);
	}

}