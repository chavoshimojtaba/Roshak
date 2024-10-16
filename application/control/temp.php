<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class temp extends Controller
{
	public function index($slug='')
	{

		$this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);  
		if (is_login()) { 
			$this->load->model('model_product'); 
			$res = $this->model_product->get_product_file($slug,true);
			if($res->count <= 0){
				pr('404 : شما مجوز دسترسی به این فایل را ندارید', true);
				exit();
			}
			$server_file =  $res->result[0]['file'];
			$ftp_server = "79.127.126.105";
			$ftp_username = 'dltarhpi';
			$ftp_userpass =  'EHRtty8iolu9plo4erfr';
			$filePath = "temp_file/"; 
			$local_file =  $this->uploadName();
			$conn_id = ftp_connect($ftp_server); 
			$login_result = ftp_login($conn_id, $ftp_username, $ftp_userpass);
			ftp_pasv($conn_id, true);  
			if (!ftp_get($conn_id, $filePath.$local_file, $server_file, FTP_BINARY)) { 
				pr('404 : ادرس فایل نامعتبر میباشد.', true);
			} 
			
			ftp_close($conn_id); 

			if (!file_exists($filePath.$local_file)) { // file does not exist
				pr('404 : ادرس فایل نامعتبر میباشد.', true);
			} else {
				header("Cache-Control: public");
				header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
				header("Content-Description: File Transfer");
				header("Content-Disposition: attachment; filename=$local_file");
				header("Content-Type: application/zip");    
				header("Content-Length:".filesize($filePath.$local_file));
				header("Content-Transfer-Encoding: binary"); 
				readfile($filePath.$local_file);
			}
		} else {
			redirect(HOST.'auth');
			exit();
		} 
	}
	function uploadName()
	{
		return  substr(md5(rand(1111, 9999)), 0, 8) . strtotime(date('Y-m-d H:i:s')) . rand(1111, 9999) . rand(11, 99) . rand(111, 999) . ".zip";
	}
	public function ftp_download()
	{
	}
	public function display()
	{
		$this->template->parse($this->router->method);
		out([
			'content' => $this->template->text($this->router->method)
		]);
	}
}
