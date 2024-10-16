<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
} 
class plan {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_plan');
	}

    function list($param,$get)
    {
        require_once DIR_LIBRARY."ssr_grid.php";
        $GridData = new SSR_Grid(array_merge(['type'  => 'plan'],$get));
        $res = $GridData->json();
		if ($res['count'] > 0)
        {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function subscribtion($param,$get)
    {

		$get['mid'] = $_SESSION['mid'];
		$get['pid'] = $_SESSION['subscribtion']['pid'];
		$res = $this->app->model_plan->subscribtion($get);
		if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
			$this->alldata['data'] 	 = $res->insert_id;
		}

		$this->app->_response(200,$this->alldata);
	}
}