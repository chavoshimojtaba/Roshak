<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class report { 
    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_report');
	}

    function product_statistics($param,$get)
    {
        $this->app->load->model('model_category');
		$res = $this->app->model_category->get_list($get['type'],'info'); 
        $this->app->load->model('model_product');
		$downloads_by_category = $this->app->model_product->downloads_by_category($get);  
		if ($res->count > 0)
        {
            foreach ($res->result as &$value) {
                $value['downloads'] = 0; 
                $value  = decode_html_tag($value,true);
                $value['level_number'] = $value['statistic_product']; 
                $cid = '_'.$value['id'].'_-';
                foreach ($res->result as $v) { 
                    $pos = strpos($v['path'],$cid); 
                    if( $pos !== false){ 
                        $value['level_number'] +=  (int)$v['statistic_product'];
                    }
                }
            }
            $a = 0; 
            foreach ($res->result as &$value) { 
                $cid = '_'.$value['id'].'_'; 
                foreach ($downloads_by_category->result as $v) { 
                    $pos = strpos($v['cat_path'],$cid);
                    if( $pos !== false ){ 
                        $value['downloads'] +=  (int)$v['downloads'] ;  
                    }
                }
            }   
            foreach ($res->result as &$value) { 
                $value['title'] .= '<span class="badge text-danger fw-bold">'.$value['level_number'].'</span> - <span class="badge fw-bold text-primary">'.$value['downloads'].'</span>';  
            }   
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->result;
        }
        $this->app->_response(200,$this->alldata);
    }

}