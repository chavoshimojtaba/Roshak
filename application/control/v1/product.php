<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class product {

    private $app;
	private $alldata = [];
    public function init($app) {
            $this->alldata['total'] = 0;
            $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_product');
	}

    function add($param,$get)
    {
        require_once DIR_HELPER . "helper_product.php";
        $res = add_product($get);
        if ($res > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update($param,$get)
    {
        require_once DIR_HELPER . "helper_product.php";
        $res = update_product($get,$get['id']);
        if ($res > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add_attribute($param,$get)
    {
        $res = $this->app->model_product->add_attribute_value($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update_attribute($param,$get)
    {
        $res = $this->app->model_product->update_attribute($param[0],$get);
        if ($res->affected_rows > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function print_request_reply($param,$get)
    {
        $res = $this->app->model_product->print_request_reply($param[0],$get);
        if ($res->affected_rows > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function delete_attribute($param,$get)
    {
        $res = $this->app->model_product->delete_attribute($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

	function attributes($param,$get)
    {
        $res = $this->app->model_product->get_attributes_list($get);
        if ($res->count > 0)
        {
            $atribute = productValues('attribute');
            foreach ($res->result as $key => &$value) {
                $value['attribute'] = $atribute[$value['attribute_grp']];
                $value['attribute_title'] = decode_html_tag($value['attribute_title'],true);
                $value['attribute_createAt'] = g2pt($value['attribute_createAt']);
            }
            $this->alldata['total']  = $res->total;
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

	function list($param,$get)
    {
        $res = $this->app->model_product->get_list($get);
        if ($res->count > 0)
        {
            foreach ($res->result as $key => &$value) {
               
                $value['createAt']   = g2p($value['createAt']);
                $value['title']      = decode_html_tag($value['title'],true);
            }
            $this->alldata['total'] = $res->total;
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

	function reports($param,$get)
    {
        $this->alldata['total'] = $this->app->model_product->get_reports_count($get);

        if ($this->alldata['total'] > 0)
        {
            $res = $this->app->model_product->reports_list($get);
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']   = g2pt($value['createAt']);
                $res->result[$key]['title']      = decode_html_tag($value['title'],true);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

	function print_request($param,$get)
    {
        $this->alldata['total'] = $this->app->model_product->get_print_request_count($get);

        if ($this->alldata['total'] > 0)
        {
            $res = $this->app->model_product->print_request_list($get);
            foreach ($res->result as $key => $value) {
                $res->result[$key]['createAt']   = g2pt($value['createAt']);
                $res->result[$key]['title']      = decode_html_tag($value['title'],true);
                $res->result[$key]['desc']      = decode_html_tag($value['desc'],true);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function report_reply($param,$get)
    {
        $res = $this->app->model_product->report_reply($param[0],$get);
        if ($res->affected_rows > 0) {

			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function delete($param,$get)
    {
        $res = $this->app->model_product->delete_product($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function status($param,$get)
    {
        $res = $this->app->model_product->changeStatus($get['status'],$param[0]); 
        if ($res->affected_rows > 0) {
            $product   = $this->app->model_product->get_product_detail($param[0])->result[0];
            $cid   = $product['cid']; 
            $mid   = $product['mid'];
            $title = $product['title'];
            if($get['status'] == 'accept'){
                $this->app->load->model('model_category');
                $res = $this->app->model_category->dec_inc_cat_product($cid); 
                notification(['mid'=>$mid,'text'=>'طرح '.$title.'  برای نمایش در وبسایت تایید شد.','type'=>'info']); 
            }else{
                notification(['mid'=>$mid,'text'=>'طرح '.$title.'  برای نمایش در وبسایت تایید نشد.','type'=>'danger']);
            }
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}