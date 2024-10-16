<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class member {

    private $app;
	private $alldata = [];

    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_member');
	}

    function search($param,$get)
    {

		$res = $this->app->model_member->search($get);
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
    
    function member_credit($param,$get)
    {

        $this->app->load->model('model_financial');
		$res = $this->app->model_financial->member_wallet($get['mid']);
        // //pr($res,true);
		if ($res['cnt'] > 0)
        { 
            $res['unsettled'] = toman($res['unsettled'],true); 
            $this->alldata['data']   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function list($param,$get)
    {
		$res = $this->app->model_member->get_members($get);
		if ($res->count > 0)
        {
            $this->alldata['total'] = $res->total;
            foreach ($res->result as $key => &$value) {
                $value['createAt']  = g2p($value['createAt']);
                $value     = decode_html_tag($value,true); 
                $value['has_page'] =  true;
                if( strlen($value['full_name'])<=0 ){ 
                    $value['has_page'] =  false;
                    $value['full_name'] =  '<span class="text-light-secondary">اطلاعات ناقص</span>'; 
                }
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function expertise($param,$get)
    {
        $res = $this->app->model_member->get_expertise_list($get);
        $this->alldata['total'] = $res->total;

        if ($this->alldata['total'] > 0)
        {
            foreach ($res->result as $key => $value) {

                $res->result[$key]['slug'] .= '-ex';
                $res->result[$key]['createAt']   = g2pt($value['createAt']);
                $res->result[$key]['title']      = decode_html_tag($value['title'],true);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add_expertise($param,$get)
    {
        $res = $this->app->model_member->add_expertise($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update_expertise($param,$get)
    {
        $res = $this->app->model_member->update_expertise($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function del_expertise($param,$get)
    {
        $res = $this->app->model_member->del_expertise($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }


    function settlement_requests($param,$get)
    {
		$res = $this->app->model_member->settlement_requests($get);

        $this->alldata['total'] = $res->total;

		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2p($value['createAt']);
                $res->result[$key]['desc']     = decode_html_tag($value['desc'],true);
                $res->result[$key]['desc']     = decode_html_tag($value['reply'],true);
                $res->result[$key]['full_name'] = decode_html_tag($value['full_name'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function coopration_requests($param,$get)
    {
		$res = $this->app->model_member->coopration_requests($get);

        $this->alldata['total'] = $this->app->model_member->get_coopration_requests_count($get);

		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']  = g2p($value['createAt']);
                $res->result[$key]['full_name'] = decode_html_tag($value['full_name'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function coopration_request_files($param,$get)
    {
		$res = $this->app->model_member->coopration_request_files($get);


		if ($res->count > 0)
        {
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    } 

    function update_profile($param,$get)
    {
        $get['status'] = ($get['status'] == 'on')?'active':'deactive';
		$res = $this->app->model_member->update_profile($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update_profile_designer($param,$get)
    {
		$res = $this->app->model_member->update_profile_designer($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

 
    function change_password($param,$get)
    {
        $new_password =  $get['new_password'] ;
        $get['new_password'] = md5($get['new_password']);
        $res = $this->app->model_member->change_password($get);
        if ($res->affected_rows > 0) { 
            $mobile = $this->app->model_member->get_mobiles($get['mid'])->result[0]['mobile']; 
            send_message($mobile, [
                'code' => $new_password
            ], 'send_password');
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

 

    function confirm_change_type($param,$get)
    {
        $res = $this->app->model_member->confirm_change_type($param[0],$get['mid']);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $member = $this->app->model_member->get_member($get)->result[0]; 
            $mobile = $member['mobile'];
            $full_name = $member['full_name'];
            send_message($mobile, [
                'full_name' => $full_name
            ], 'upgrade_member');
            notification(['mid'=>$get['mid'],'text'=>'درخواست ارتقای کاربری شما تایید شد','type'=>'success']); 
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function downgrade_to_common($param,$get)
    {
        $res = $this->app->model_member->downgrade_to_common($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1; 
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function reject_change_type($param,$get)
    {

        $res = $this->app->model_member->reject_change_type($param[0],$get); 
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $member = $this->app->model_member->get_member($get)->result[0]; 
            $mobile = $member['mobile'];
            $full_name = $member['full_name'];
            send_message($mobile, [ 
                'full_name' => $full_name
            ], 'reject_upgrade_request');
            notification(['mid'=>$param[0],'text'=>'درخواست ارتقای کاربری شما رد شد.برای اطلاعات بیشتر با تیم طرح پیچ در تماس باشید:'.$get['exp'],'type'=>'danger']);
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function settlement_requests_reply($param,$get)
    {

        $res = $this->app->model_member->settlement_requests_status($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1; 
            $member = $this->app->model_member->get_member($get)->result[0]; 
            $mobile = $member['mobile'];
            $full_name = $member['full_name'];
            send_message($mobile, [
                'full_name' => $full_name
            ], 'settlement_request');
            notification(['mid'=>$get['mid'],'text'=>'وضعیت درخواست تسویه حساب شما مشخص شد.برای اطلاعات بیشتر به پنل کاربری خود مراجعه کنید','type'=>'info']);

            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}