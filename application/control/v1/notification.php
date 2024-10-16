<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class notification {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_notifications');
	}

	function templates($param,$get)
	{
		$res = $this->app->model_notifications->get_template_list($get);
        $this->alldata['total'] = $res->total;

		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2pt($value['createAt']);
                $res->result[$key]['exp']     = decode_html_tag($value['exp'],true);
                $res->result[$key]['title']     = decode_html_tag($value['title'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
	}
	function add_template($param,$get)
    {
		$res = $this->app->model_notifications->add_template($get);

        if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
	function message($param,$get)
    {
        $get['mids'] = 'all';
        if($get['members'] != ''){
            $mid = json_decode($get['members'],true);
            if(count($mid)){
                $get['mids'] = implode(',',array_keys($mid));
            }
        }
        $get['form']['message'] =$get['text'];
        $this->app->load->model('model_member');
        if($get['mids'] !== ''){ 
            $res = $this->app->model_member->get_mobiles($get['mids'],$get['form']['for']);
        }else{
            $res = $this->app->model_member->get_mobiles([],$get['form']['for']);
        }
        $addressList = [];
        if($res->count> 0){
            $field = ($get['form']['type'] === 'email')?'email':'mobile';
            foreach ($res->result as $key => $value) {
                if(strlen($value[$field])>6){
                    $addressList[] = $value[$field];
                }
            }
        } 
        $addressList =decode_html_tag($addressList,true); 
        if(count($addressList)){ 
            if(strlen($get['form']['date']<=8)){
                $get['form']['date']  = date("Y-m-d H:i:s");
            }
            $date = new DateTime($get['form']['date'].' +1 hours');  
            require_once(DIR_LIBRARY."Message.php");
            $Message   =   new Message('custom',$addressList); ///sms
            $Message->customTemplate = $get['form']['text']; 
            $Message->send_time = $date->getTimestamp();
            $Message->send($get['form']); 
            $this->alldata['status'] = 1;
        } 
        $this->app->_response(200,$this->alldata);
    }
    function update_template($param,$get)
    {
        // //pr($get,true);

        $res = $this->app->model_notifications->update_template($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function del_template($param,$get)
    {
        $res = $this->app->model_notifications->del_template($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}