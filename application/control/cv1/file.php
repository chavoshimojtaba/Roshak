<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}



require_once(DIR_CONTROL . "upload.php");

class file
{

    private $app;
    private $alldata = [];

    public function init($app)
    {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_file');
    } 

    function add_ftp($param, $get)
    {
        $cid = json_decode($get['more'],true)['cid'];   
        $cat = GLOBALS('category',false)[$cid]; 
        $uploader = new Uploader($_POST['type'], true);
        $uploader->setExtensions(array('zip')); 
        $uploader->setMaxSize(2024);
        $upload = $uploader->uploadProductFile('file',$cat['slug'] ,$cat['filetype'],'main_files');
        if (!$upload) {
            $this->alldata['data'] = $uploader->getMessage();
            $this->app->_response(400, $this->alldata);
        } else { 
            $this->alldata['status'] = 1;
            $this->alldata['data'] = ['id' => $upload, 'dir' => $uploader->getResult()['file']];
            $this->app->_response(200, $this->alldata);
        }

        // $uploader = new Uploader('ftp', true);
        // $uploader->setExtensions(array('zip'));
        // $uploader->alt = '';
        // $uploader->title = '';
        // $uploader->setMaxSize(200000); 
        // $upload = $uploader->uploadProductFile('file');

        // $this->alldata['data'] = $uploader->getMessage();
        // if (!$upload) {
        //     $this->app->_response(400, $this->alldata);
        //     $this->alldata['status'] = 0;
        // } else {
        //     $this->alldata['status'] = 1;
        // }
        // $this->alldata['data'] = ['id' => '100', 'dir' => $uploader->getResult()['file']];
        // $this->app->_response(200, $this->alldata);
    }
    function add($param, $get)
    { 
        if($_POST['type'] == 'product'){
            $cid = json_decode($get['more'],true)['cid'];  
            $cat = GLOBALS('category',false)[$cid]; 
            $uploader = new Uploader($_POST['type'], true);
            $uploader->setExtensions(array('jpeg', 'jpg', 'png' ,'zip' ));
            $uploader->alt = $get['alt'];
            $uploader->title = $get['title'];
            $uploader->setMaxSize(2024);
            $upload = $uploader->uploadProductFile('file',$cat['slug'] ,$cat['filetype']); 
            
            if (!$upload) {
                $this->alldata['data'] = $uploader->getMessage();
                $this->app->_response(400, $this->alldata);
            } else {
                $uploader->createThumb(200, 143);
                $uploader->createThumb(300, 214);
                $this->alldata['status'] = 1;
                $this->alldata['data'] = ['id' => $upload, 'dir' => $uploader->getResult()['file']];
                $this->app->_response(200, $this->alldata);
            }
        }else{ 
            $uploader = new Uploader(1, true);
            $uploader->setExtensions(array('webp', 'jpeg', 'jpg', 'png',  'pdf', 'zip', 'mp4', 'xls', 'xlsx', 'csv', 'txt'));
            $uploader->alt = $get['alt'];
            $uploader->title = $get['title'];
            $uploader->setMaxSize(2024);
            $upload = $uploader->uploadFile('file', '', 'public');
            if (!$upload) {
                $this->alldata['data'] = $uploader->getMessage();
                $this->app->_response(400, $this->alldata);
            } else {
                $uploader->createThumb(200, 143);
                $uploader->createThumb(300, 214);
                $this->alldata['status'] = 1;
                $this->alldata['data'] = ['id' => $upload, 'dir' => $uploader->getResult()['file']];
                $this->app->_response(200, $this->alldata);
            }
        }
    }


    function delete($param, $get)
    {
        $res = $this->app->model_file->delete_file($param[0]);

        if ($res->affected_rows > 0) {
            $name = $this->app->model_file->get_file_name($param[0])->result[0]['dir'];
            unlink($name);
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200, $this->alldata);
    }
}
