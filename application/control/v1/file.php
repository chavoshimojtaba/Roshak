<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
require_once(DIR_CONTROL."upload.php");

class file {

    private $app;
	private $alldata = [];

    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_file');
	}

    function category($param,$get)
    {
        $res_category = $this->app->model_file->get_category_list();
		if ($res_category->count > 0)
        {
            foreach ($res_category->result as &$value) {
                $value['title_fa']  =  $value['title'];
            }
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res_category->result;
        }
        $this->app->_response(200,$this->alldata);
    }

    function list($param,$get)
    {
        if(isset($get['filetype']) && $get['filetype'] != 0 && $get['filetype'] != 'all'){
            if( !in_array($get['filetype'],['image','doc','video'])){
                $get['filetype'] = decode_html_tag($get['filetype'],true);
                $allformats = explode(',',$get['filetype']);
                foreach ($allformats as $value) {
                    $formats[]="'".$value."'";
                }
                $get['formats'] = implode(',',$formats);
            }else{
                $formats = get_formats($get['filetype'],1);
                if(count($formats) > 0){
                    $get['formats'] = implode(',',$formats);
                }
            }
        }
        $res = $this->app->model_file->get_list($get);
        $res_category = $this->app->model_file->get_category_list($get['cat']);
        $this->alldata['total'] = 0;
		if ($res['count'] > 0)
        {
            foreach ($res['result'] as $key => $value) {
                $res['result'][$key]['createAt'] = g2pt($value['createAt']);
                $res['result'][$key]['dir']      = $value['dir'].$value['name'];
                $res['result'][$key]['dir']      = thumbnail($value['dir'].$value['name'],200);
                $size                            = round($value['size'] / 1024 / 1024, 1);
                $res['result'][$key]['size']     = $size."mb";
                $res['result'][$key]['name_cat'] = $value['alias']." (".$value['category_title'].")";
            }
            $this->alldata['total'] = $res['total'];
            $this->alldata['data'] = $res['result'];
            $this->alldata['status']           = 1;
        }
		if ($res_category->count > 0)
        {
            $data = [];
            foreach ($res_category->result as $key => $value) {
                $data[$value['id']] = $value;
            }
            $this->alldata['category'] = $data;
            $this->alldata['status']   = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add($param,$get)
    {
        $watermatk = false;
        if(isset($_POST['watermark']) && $_POST['watermark'] == 'true'){
            $watermatk = true;
        } 
        
        $uploader = new Uploader($_POST['type'],$watermatk);
        $uploader->setExtensions(array('webp','jpeg','jpg','png','gif','pdf','zip','mp4','xls','xlsx','csv','txt'));
        $uploader->alt = $get['alt'];
        $uploader->title = $get['title'];
        $uploader->setMaxSize(2024);
        $uploader->setSameFileName();
        $upload = $uploader->uploadFile('file',$_POST['alias'],$_POST['cat']);
        if(!$upload){
            $this->alldata['data'] = $uploader->getMessage();
            $this->app->_response(400,$this->alldata);
        }else{
            if(isset($_POST['thumbnail']) && strlen($_POST['thumbnail'])>1){
                $size_explode = explode(',',$_POST['thumbnail']);
                foreach ($size_explode as  $value) {
                    $size = explode('*',$value);
                    if(count($size) == 2){
                        $uploader->createThumb($size[0],$size[1]);
                    }
                }
            }
            $uploader->createThumb(100,100);
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $upload;
            $this->app->_response(200,$this->alldata);
        }
    }

    function add_cat($param,$get)
    {
        $res = $this->app->model_file->add_category($get);
        if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data']   = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function detail($param,$get)
    {
        $res = $this->app->model_file->detail($param[0]);
		if ($res->count > 0)
        {
            $this->alldata['data']   = decode_html_tag($res->result[0],true);
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_file($param,$get)
    {
        $res = $this->app->model_file->update_file($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function up_cat($param,$get)
    {
        $res = $this->app->model_file->up_category($get['id'],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function del_cat($param,$get)
    {
        $res = $this->app->model_file->del_category($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }


    function delete($param,$get)
    {
        $res = $this->app->model_file->delete_file($param[0]);

        if ($res->affected_rows > 0) {
            $name = $this->app->model_file->get_file_name($param[0])->result[0]['dir'];
            unlink($name);
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}