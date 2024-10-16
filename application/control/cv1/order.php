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

    function download($param,$get)
    {
        require_once DIR_LIBRARY."ssr_grid.php";
        $get['mid'] = $_SESSION['mid'];
        
        $GridData = new SSR_Grid(array_merge(['type'  => 'download'],$get));
        $res = $GridData->json(); 
		if ($res['count'] > 0)
        {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function discount_code($param,$get)
    {   
        $_SESSION['order_discount_code'] = 0; 
		$this->app->load->model('model_financial');
        $mid = isset($_SESSION['mid'])?$_SESSION['mid']:0;
		$res = $this->app->model_financial->discount_code($get['code'],$mid); 
        if( $res->count>0){
			$value =$res->result[0];  
			$_SESSION['order_discount_id'] = $get['code'];
			$this->alldata['data'] = $value;
			$this->alldata['status'] = 1; 
        }else{ 
            $this->alldata['data']  = '  کد صحیح نمیباشد.';  
        }
        $this->app->_response(200,$this->alldata);
    }  
 

}