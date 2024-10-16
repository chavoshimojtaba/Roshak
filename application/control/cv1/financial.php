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

	function settlement_requests($param,$get)
    {
        require_once DIR_LIBRARY."ssr_grid.php";
        $GridData = new SSR_Grid(array_merge(['type'  => 'settlement_requests'],$get));
        $res = $GridData->json();
		if ($res['count'] > 0)
        {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add_settlement_requests($param,$get)
    {

		// $get = $_POST;
		$get['mid'] = $_SESSION['mid'];
		$res = $this->app->model_financial->add_settlement_requests($get);
		if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
			$this->alldata['data'] 	 = $res->insert_id;
		}

		$this->app->_response(200,$this->alldata);
	}
    function designer_sell($param,$get)
    {
        $this->app->load->model('model_report');
        $last30days = array();
        $sell_statistics = $this->app->model_report->designer_sell(1,$get);
        if($sell_statistics->count>0){
            foreach ($sell_statistics->result as $key => $value) {
                $last30days[] = ['g'=>$value['date'],'p'=>g2p($value['date'] ),'total_price' => $value['total_price']];
            }
            $this->alldata['status'] = 1;
            $this->alldata['data'] 	 = $last30days;
        }

		$this->app->_response(200,$this->alldata);
	}


}