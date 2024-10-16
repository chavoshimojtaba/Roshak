<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class category {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_category');
	}

    function order($param,$get)
    {
        $res = $this->app->model_content->order($param[0],$get['order']);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1; 
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    
    function list($param,$get)
    {
		$res = $this->app->model_category->get_list(  ); 
		if ($res->count > 0)
        {  
            foreach ($res->result as &$value) {  
                $value  = decode_html_tag($value,true);
            }
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->result;
        }
        $this->app->_response(200,$this->alldata);
    } 
    function update($param,$get)
    {

        $res = $this->app->model_category->update_category( $get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            // generateCategoryTemplatesCache();
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }














    /*
    * Created     : Fri Sep 09 2022 12:05:20 PM
    * Author      : Chavoshi Mojtaba
    * Description : get category detail
    * return      : array
    */
    function edit($param,$get)
    {
        $res = $this->app->model_category->get_category_detail($param[0]);
        if ($res->count > 0)
        {
            $cat = $res->result[0];
            $cat['title']  = decode_html_tag($cat['title'],true);
            $cat['desc']  = decode_html_tag($cat['desc'],true);
            $cat['slug']  = decode_html_tag($cat['slug'],true);
            $cat['meta']  = decode_html_tag($cat['meta'],true);
            $this->alldata['status'] = 1;
            $this->alldata['data']   = $cat;

        }
        $this->app->_response(200,$this->alldata);
    }

    function add($param,$get)
    {
        $get['icon'] = (isset($get['icon'])?$get['icon']:'');
		$res = $this->app->model_category->add_category($get);
        if ($res->insert_id > 0) {
            if(isset($get['path'])){
                $this->app->model_category->update_path($res->insert_id,$get);
            }
            // generateCategoryTemplatesCache();
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

   
    function delete($param,$get)
    {
        $check_has_child  = $this->app->db->total_count('tp_category' , "`tp_delete` = '0' AND tp_cat_path LIKE '%\_{$param[0]}\_%' AND tp_id <> $param[0]"); 
        if($check_has_child > 0){
            $this->alldata['status'] = -1;
        }else{

            $res = $this->app->model_category->delete_category($param[0]);
            if ($res->affected_rows > 0) {
                $this->alldata['status'] = 1;
                $this->alldata['data'] = $res->affected_rows;
            }
        }
        $this->app->_response(200,$this->alldata);
    }

    
}