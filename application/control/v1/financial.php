<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class financial {

    private $app;
	private $alldata = [];

    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_financial');
	}

    function report($param,$get)
    {
        $status = [
            'pend' => '<span class="badge bg-warning status-badge">در صف  </span>',
            'cancel' => '<span class="badge bg-danger status-badge">لغو شده</span>',
            'done' => '<span class="badge bg-success status-badge">تکمیل سفارش</span>',
            'failed' => '<span class="badge bg-danger status-badge">  ناموفق</span>'
        ];
        $type = [
            'bank' => '<span class="badge bg-primary status-badge">خرید تکی</span>',
            'subscription' => '<span class="badge bg-danger status-badge">  اشتراکی</span>'
        ];
        $get['members'] = json_decode(decode_html_tag($get['members'],true),true);
        $get['pid'] = json_decode(decode_html_tag($get['pid'],true),true);
		$res = $this->app->model_financial->report($get);
        $this->alldata['data'] = [];
		if ($res->count > 0)
        {
            foreach ($res->result as &$value) { 
                $value['status'] = $status[$value['status']];
                $value['type'] = $type[$value['type']];
                $value['designer'] = '<a href="'.HOST.'designers/'.$value['member_slug'].'" target="_blank">'.decode_html_tag($value['designer'],true).'</a>';
                $value['product_title'] = '<a href="'.HOST.'p/'.$value['product_slug'].'" target="_blank">'.decode_html_tag($value['product_title'],true).'</a>';
                $value['member'] = '<a href="'.HOST.'admin/member/view/'.$value['mid'].'" target="_blank">'.decode_html_tag($value['member'],true).'</a>';
                $value['createAt'] = g2pt($value['createAt']);
            }
            $this->alldata['data'] = $res->result;
        }
        $this->alldata['status'] = 1;
        $this->app->_response(200,$this->alldata);
    }

    function plan_report($param,$get)
    {
        $status = [
            'reserve' => '<span class="badge bg-warning status-badge">رزرو</span>',
            'active' => '<span class="badge bg-primary status-badge">فعال</span>',
            'ended' => '<span class="badge bg-danger status-badge">اتمام اشتراک</span>'
        ];
        $get['members'] = json_decode(decode_html_tag($get['members'],true),true);
		$res = $this->app->model_financial->plan_report($get);
        $this->alldata['data'] = [];
		if ($res->count > 0)
        {
            foreach ($res->result as &$value) {
                $value['status'] = $status[$value['status']];
                $value['member'] = decode_html_tag($value['member'],true);
                $value['total_price'] = toman($value['total_price'], true);
                $value['createAt'] = g2pt($value['createAt']);
            }
            $this->alldata['data'] = $res->result;
        }
        $this->alldata['status'] = 1;
        $this->app->_response(200,$this->alldata);
    }

    function transaction_report($param,$get)
    {
        $status = [
            'pend' => '<span class="badge bg-warning status-badge">در صف  </span>',
            'bank' => '<span class="badge bg-primary status-badge">بانک</span>',
            'done' => '<span class="badge bg-success status-badge">تکمیل</span>',
            'failed' => '<span class="badge bg-danger status-badge">  ناموفق</span>'
        ];
        $type = [
            'product' => '<span class="badge bg-primary status-badge">محصول</span>',
            'subscription' => '<span class="badge bg-danger status-badge">  اشتراک</span>'
        ];
        $get['members'] = json_decode(decode_html_tag($get['members'],true),true);
        // pr($get,true);
		$res = $this->app->model_financial->transaction_report($get);
        $this->alldata['data'] = [];
		if ($res->count > 0)
        {
            foreach ($res->result as &$value) {
                $value['type'] = $type[$value['type']];
                $value['status'] = $status[$value['status']];
                $value['member'] = decode_html_tag($value['member'],true);
                $value['total_price'] = toman($value['total_price'], true);
                $value['createAt'] = g2pt($value['createAt']);
            }
            $this->alldata['data'] = $res->result;
        }
        $this->alldata['status'] = 1;
        $this->app->_response(200,$this->alldata);
    }

    function discount_codes($param,$get)
    {
        $res = $this->app->model_financial->discount_codes_list($get);
        $this->alldata['total'] = $res->total;

		if ($res->count > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['date_start_p']  = g2p($value['date_start']);
                $res->result[$key]['date_end_p']    = g2p($value['date_end']);
                $res->result[$key]['createAt']    = g2pt($value['createAt']);
                $res->result[$key]['title']       = decode_html_tag($value['title'],true);
                $res->result[$key]['desc']        = decode_html_tag($value['desc'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add_discount_code($param,$get)
    {
        $get['use_for'] = 'product';
        $res = $this->app->model_financial->add_discount_code($get);
        // pr($get,true);
        if ($res->insert_id > 0) {
            $this->app->load->model('model_member');
            if($get['type'] == 'member'){
                $mids = $this->app->model_member->get_mobiles( implode(',',array_keys(json_decode($get['members'],true))) , $get['type']);
            }else{
                $mids = $this->app->model_member->get_mobiles([] , $get['type']);
            }
            foreach ($mids->result as $key => $value) {
                $mobiles[$value['id']] = $value['mobile'];
            }
            send_message($mobiles,[
                'message'=>$get['desc'].'کد تخفیف : '.$get['desc'].' اعتبار تا '.g2p($get['end_date']),
                'type'=>$get['type']
            ],'discount_code');
            foreach ($mids->result as $key => $value) { 
                notification(['mid'=>$value['mid'],'text'=>$get['desc'].'کد تخفیف : '.$get['desc'].' اعتبار تا '.g2p($get['end_date']),'type'=>'info']);
            } 

            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function update_discount_code($param,$get)
    {
        $res = $this->app->model_financial->update_discount_code($get,$get['id']);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function delete_discount_code($param,$get)
    {
        $res = $this->app->model_financial->delete_discount_code($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update_designer_share($param,$get)
    {
        $res = $this->app->model_financial->update_designer_share($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
}