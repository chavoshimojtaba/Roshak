<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
} 
class product {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_product');
	}

    function list($param,$get)
    { 
        require_once DIR_LIBRARY."ssr_grid.php";
        if (count($param) === 1 && $param[0] != '') {
            $tag = is_tag($param[0]); 
            $filetype = is_filetype($param[0]);
			if ($tag) {
                $get['tid'] = $tag['id']; 
			}else if ($filetype) {  
                $get['filetype'] = $filetype['slug']; 
			} else { 
                $catgories = GLOBALS('category',false); 
				foreach ($catgories as   $value) {
                    if (strtolower($param[0]) == strtolower($value['slug'])) { 
						$get['cat_id'] = $value['id']; 
                        break;
					}
				}
			} 
		}
        if(isset($get['designer'])){
            $get ['mid'] = $_SESSION['mid'];
        }
        $get ['designer_show_all'] = 'yes';
        $GridData = new SSR_Grid(array_merge(['type'  => 'product'],$get));
        $res = $GridData->json();
		if ($res['count'] > 0)
        {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add($param,$get)
    { 
        require_once DIR_HELPER . "helper_product.php";
        $get['front_side'] = true;
        $res = add_product($get); 
        if ($res > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update($param,$get)
    {
        $get['front_side'] = true;
        require_once DIR_HELPER . "helper_product.php";
        $res = update_product($get,$get['id']);
        if ($res > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res;
        }
        $this->app->_response(200,$this->alldata);
    }
}