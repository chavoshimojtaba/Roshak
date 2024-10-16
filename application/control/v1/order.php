<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}

class order {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_order');
	}

	function list($param,$get)
    {
        $res = $this->app->model_order->get_list($get);
        $this->alldata['total'] = $res->total;

        if ($this->alldata['total'] > 0)
        {
            foreach ($res->result as $key => &$value) {
                $value['product_price'] = toman($value['product_price'],true);
                $value['createAt']    = g2pt($value['createAt']);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

	function detail($param,$get)
    {
        $res = $this->app->model_order->get_order_detail($get['oid']);
        if($res->count > 0){
            $data = $res->result[0];
            $this->app->load->model('model_financial');
            $data['transaction']   = $this->app->model_financial->transaction_detail($data['transaction_id'])->result[0];
            $data['product_title']       = decode_html_tag($data['product_title'],true);
            $data['total_price'] = toman($data['total_price']);
            $data['product_price'] = toman($data['product_price']);
            $data['createAt'] = g2pt($data['createAt']);
            $this->alldata['data'] = $data;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

}