<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

use Lazzard\FtpClient\Config\FtpConfig;
use Lazzard\FtpClient\Connection\FtpConnection;
use Lazzard\FtpClient\FtpClient;
use Lazzard\FtpClient\FtpWrapper;

class Uploader extends Controller
{
	private $destinationPath;
	private $errorMessage;
	private $extensions;
	private $allowAll;
	private $maxSize;
	public $uploadName;
	public $insertDbId 	   	   = 0;
	public $size 	   		   = 0;
	public $alias 	   		   = '';
	public $ext 	   		   = '';
	public $name       		   = 'Uploader';
	public $alt       		   = '';
	public $title       	   = '';
	public $type       		   = '';
	public $cid        		   = 0;
	public $width      		   = 0;
	public $height     		   = 0;
	public $oldName    		   = '';
	public $sameName   		   = false;
	public $watermark   	   = false;
	public $useTable   		   = false;
	public $sub_folder 		   = '';
	public $file_relation_fids = []; //file ids
	public $file_relation_type = '';
	public $file_relation_rid  = 0;

	public $dirs 	   = [
		'webp' => 'image',
		'jpeg' => 'image',
		'jpg'  => 'image',
		'png'  => 'image',
		'gif'  => 'image',
		'mp4'  => 'video',
		'pdf'  => 'doc',
		'zip'  => 'doc',
		'xls'  => 'doc',
		'xlsx' => 'doc',
		'csv'  => 'doc',
		'txt'  => 'doc'
	];

	public function __construct($cid, $watermark = false)
	{
		parent::__construct();
		$this->load->model('model_file');
		$this->cid = $cid;
		$this->watermark = $watermark;
		$this->setMaxSize(5);
	}

	function getInfo()
	{
		return [
			'is_thumbnail' => 0,
			'old_name' => $this->oldName,
			'type' => $this->type,
			'alias' => $this->alias,
			'title' => $this->title,
			'alt' => $this->alt,
			'dir' => $this->destinationPath,
			'width' => $this->width,
			'height' => $this->height,
			'cid' => $this->cid,
			'size' => $this->size,
			'name' => $this->uploadName,
			'ext' => $this->ext
		];
	}

	function setDir($path = null,$rootFolder='upload')
	{
		if ($path !== null) {
			$path .= '/';
		}

		$this->destinationPath  =  $rootFolder.'/' . $this->sub_folder . '/' . $path;
		is_dir($rootFolder.'/' . $this->sub_folder) || mkdir($rootFolder.'/' . $this->sub_folder);
		is_dir($this->destinationPath) || mkdir($this->destinationPath);
		$this->allowAll =   false;
	}

	function getResult($path = null)
	{
		return [
			'file' => $this->destinationPath . $this->uploadName,
			'id' => $this->insertDbId
		];
	}

	function setType($type, $rid) //ticket , slider , profile
	{
		$this->file_relation_type = $type;
		$this->file_relation_rid  = $rid;
	}
	
	function setSubFolder($sub='')
	{
		if($sub != ''){
			$this->sub_folder  =  $sub;
		}else
		$this->sub_folder  =  $this->dirs[$this->ext];
	}

	function allowAllFormats()
	{
		$this->allowAll =   true;
	}

	function setMaxSize($sizeMB)
	{
		$this->maxSize  =   $sizeMB * (1024 * 1024);
	}

	function setExtensions($options)
	{
		$this->extensions   =   $options;
	}

	function setSameFileName()
	{
		$this->sameFileName =   true;
		$this->sameName =   true;
	}
	function getExtension($string)
	{
		$this->ext        =   pathinfo($string, PATHINFO_EXTENSION);
	}
	function isWatermark($cat = '')
	{
		if (($this->watermark == true || $cat == 'product') &&  in_array($this->ext, ['jpg', 'jpeg', 'png', 'webp'])) {
			return true;
		}
		return false;
	}

	function setMessage($message)
	{
		$this->errorMessage =   $message;
	}

	function getMessage()
	{
		return $this->errorMessage;
	}

	function getUploadName()
	{
		return $this->uploadName;
	}

	function getRandom($cnt=8)
	{
		return substr(md5(rand(1111, 9999)), 0, $cnt) . strtotime(date('Y-m-d H:i:s'));
	}
	function sameName($true)
	{
		$this->sameName = $true;
	}

	function uploadProductFile($fileBrowse,  $cat = '' ,$file_type,$rootFolder='upload')
	{ 
		$result =   false; 
		$this->size    = $_FILES[$fileBrowse]["size"];
		$this->type    = $_FILES[$fileBrowse]["type"];
		$this->oldName = $_FILES[$fileBrowse]["name"];
		$this->alias   = ($alias != '') ? $alias : $this->oldName; 
		$this->getExtension($this->oldName);
		$this->setSubFolder($file_type);
		$this->setDir($cat,$rootFolder);
		if (!is_writable($this->destinationPath)) {
			$this->setMessage("Destination is not writable !");
		} else if (empty($this->oldName)) {
			$this->setMessage("File not selected ");
		} else if ($this->size > $this->maxSize) {
			$this->setMessage("Too large file !");
		} else if ($this->allowAll || (!$this->allowAll && in_array($this->ext, $this->extensions))) {
			$this->oldName = preg_replace("/[^A-Za-z0-9-]/", "", pathinfo($this->oldName ,PATHINFO_FILENAME)); 
			if ($this->sameName == false) {
				$this->uploadName    =  $cat . "-" .$this->oldName.'-'.  $this->getRandom(6). "." . $this->ext;
			} else {
				$this->uploadName =  $cat . "-" . rand(99, 9999) .'-'.$this->oldName;
			} 
			$temporaryname = $this->uploadName;
			if ($this->isWatermark($cat)) {
				$temporaryname = 'temporary_' . $this->oldName;
			}
			if (move_uploaded_file($_FILES[$fileBrowse]["tmp_name"], $this->destinationPath . $temporaryname)) {
				if ($this->isWatermark($cat)) {
					$this->watermark_image($this->destinationPath . $temporaryname, $this->destinationPath . $this->uploadName);
				}
				if($rootFolder == 'upload'){
					$result = $this->insertDb();
				}else{
					$result=1;
				}
			} else {
				$this->setMessage("Upload failed , try later !");
			}
		} else {
			$this->setMessage("Invalid file format !");
		}  
		return $result;
	}
	function uploadFile($fileBrowse, $alias = '', $cat = '', $insertRelation = true)
	{
		$files = $_FILES[$fileBrowse];
		$result =   false;
		if (is_array($files["name"])) {
			foreach ($files["name"] as $key => $value) {
				$this->size    = $files["size"][$key];
				$this->type    = $files["type"][$key];
				$this->oldName = $value;
				$this->alias   = ($alias != '') ? $alias : $this->oldName;
				$this->getExtension($this->oldName);
				$this->setSubFolder();
				$this->setDir($cat);
				if (!is_writable($this->destinationPath)) {
					$this->setMessage("Destination is not writable !");
				} else if (empty($this->oldName)) {
					$this->setMessage("File not selected ");
				} else if ($this->size > $this->maxSize) {
					$this->setMessage("Too large file !");
				} else if ($this->allowAll || (!$this->allowAll && in_array($this->ext, $this->extensions))) {
					if ($this->sameName == false) {
						$randomValue =  $this->getRandom() . rand(99, 9999);
						$this->uploadName    =  $cat . "-" . $randomValue . "." . $this->ext;
					} else {
						$this->uploadName =  $cat . "-" . rand(99, 9999) .'-'.$this->oldName;
					} 
					$temporaryname = $this->uploadName;
					if ($this->isWatermark($cat)) {
						$temporaryname = 'temporary_' . $this->oldName;
					}
					if (move_uploaded_file($files["tmp_name"][$key], $this->destinationPath . $temporaryname)) {
						if ($this->isWatermark($cat)) {
							$this->watermark_image($this->destinationPath . $temporaryname, $this->destinationPath . $this->uploadName);
						}
						$result = $this->insertDb();
					} else {
						$this->setMessage("Upload failed , try later !");
					}
				} else {
					$this->setMessage("Invalid file format !");
				}
			}
		} else {
			$this->size    = $_FILES[$fileBrowse]["size"];
			$this->type    = $_FILES[$fileBrowse]["type"];
			$this->oldName = $_FILES[$fileBrowse]["name"];
			$this->alias   = ($alias != '') ? $alias : $this->oldName; 
			$this->getExtension($this->oldName);
			$this->setSubFolder();
			$this->setDir($cat);
			if (!is_writable($this->destinationPath)) {
				$this->setMessage("Destination is not writable !");
			} else if (empty($this->oldName)) {
				$this->setMessage("File not selected ");
			} else if ($this->size > $this->maxSize) {
				$this->setMessage("Too large file !");
			} else if ($this->allowAll || (!$this->allowAll && in_array($this->ext, $this->extensions))) {
				if ($this->sameName == false) {
					$this->uploadName    =  $cat . "-" .  $this->getRandom() . rand(99, 9999) . "." . $this->ext;
				} else {
					$this->uploadName =  $cat . "-" . rand(99, 9999) .'-'.$this->oldName;
				} 
				$temporaryname = $this->uploadName;
				if ($this->isWatermark($cat)) {
					$temporaryname = 'temporary_' . $this->oldName;
				}
				if (move_uploaded_file($_FILES[$fileBrowse]["tmp_name"], $this->destinationPath . $temporaryname)) {
					if ($this->isWatermark($cat)) {
						$this->watermark_image($this->destinationPath . $temporaryname, $this->destinationPath . $this->uploadName);
					}
					$result = $this->insertDb();
				} else {
					$this->setMessage("Upload failed , try later !");
				}
			} else {
				$this->setMessage("Invalid file format !");
			}
		}
		if ($insertRelation) {
			$this->insertFileRelations();
		}
		return $result;
	}
	function uploadFileFtp($arraykey='file')
	{
		$file = $_FILES[$arraykey];
		$result =   false;
		$this->size    = $file["size"];
		$this->type    = $file["type"];
		$this->oldName = $file["name"]; 
		$temp_dir = $file["tmp_name"];
		$this->getExtension($this->oldName);  
		if (empty($this->oldName)) {
			$this->setMessage("File not selected ");
		} else if ($this->size > $this->maxSize) {
			$this->setMessage("Too large file !");
		} else if ($this->allowAll || (!$this->allowAll && in_array($this->ext, $this->extensions))) {
			$this->uploadName    =  'tarhpich_'.$this->getRandom() . rand(99, 9999) . "." . $this->ext;  
			try {
				$ftp_server = "79.127.126.105";
				$ftp_username = 'dltarhpi';
				$ftp_userpass =  'EHRtty8iolu9plo4erfr';

				$ftp = ftp_connect($ftp_server );
				ftp_login($ftp, $ftp_username, $ftp_userpass);
				
				$ret = ftp_nb_put($ftp, $this->uploadName, $temp_dir, FTP_BINARY, FTP_AUTORESUME); 

				ftp_close($ftp);   
				$result = true;  
            } catch (Throwable $ex) {
                $this->setMessage($ex->getMessage());
            } 
		} else {
			$this->setMessage("Invalid file format !");
		}
		return $result;
	}

	function deleteUploaded($file = '')
	{
		unlink(($file != '') ? $file : $this->destinationPath . $this->uploadName);
	}

	function insertDb()
	{ 
		$res = $this->model_file->insert_file($this->getInfo());
		if ($res->insert_id > 0) {
			$this->insertDbId = $res->insert_id;
			$this->file_relation_fids[$this->insertDbId] = $this->insertDbId;
			return $this->insertDbId;
		} else {
			return 0;
		}
	}

	function insertThumbnail($thumbnailName)
	{
		$data = $this->getInfo();
		$data['is_thumbnail'] = 1;
		$data['name'] 		  = $thumbnailName;
		$res = $this->model_file->insert_file($data);
		return $res->insert_id;
	}

	function insertFileRelations()
	{
		if ($this->file_relation_type != '') {
			$res = $this->model_file->add_file_relation([
				'files' => $this->file_relation_fids,
				'type' => $this->file_relation_type,
				'pbid' => $this->file_relation_rid,
			]);
			if ($res->insert_id > 0) {
				$this->file_relation_fids[] = $res->insert_id;
				return $res->insert_id;
			} else {
				return 0;
			}
		}
	}

	function createThumb($thumbWidth = 150, $thumbHeight = 150)
	{
		if (!in_array($this->ext, ['jpeg', 'jpg', 'png', 'webp'])) return;
		if ($this->ext == 'webp') {
			$sourceImage = imagecreatefromwebp($this->destinationPath . $this->uploadName);
		} else if ($this->ext == 'png') {
			$sourceImage = imagecreatefrompng($this->destinationPath . $this->uploadName);
		} else {
			$sourceImage = imagecreatefromjpeg($this->destinationPath . $this->uploadName);
		}
		is_dir($this->destinationPath . 'w_' . $thumbWidth . '/') || mkdir($this->destinationPath . 'w_' . $thumbWidth . '/');
		$size=GetImageSize( $this->destinationPath . $this->uploadName ); 
		if($size[0] != 700){
			$ratio = $thumbWidth/$size[0];
			$thumbHeight = ceil($size[1]*$ratio);  
		}  
		$orgWidth = imagesx($sourceImage);
		$orgHeight = imagesy($sourceImage);
		$destImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
		imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $orgWidth, $orgHeight);
		if ($this->ext == 'webp') {
			imagewebp($destImage,  $this->destinationPath . 'w_' . $thumbWidth . '/' . $this->uploadName);
		} else if ($this->ext == 'png') {
			imagepng($destImage,  $this->destinationPath . 'w_' . $thumbWidth . '/' . $this->uploadName);
		} else {
			imagejpeg($destImage,  $this->destinationPath . 'w_' . $thumbWidth . '/' . $this->uploadName);
		}
		imagedestroy($sourceImage);
		imagedestroy($destImage);
		// $this->insertThumbnail($thumbnailName);
	}
	function watermark_image($desImage, $newname, $watermak = 'file/global/image/watermark.png')
	{
		// return true;
		$image = imagecreatefromstring(file_get_contents($desImage));

		$w = imagesx($image);
		$h = imagesy($image);
		$watermark = imagecreatefrompng($watermak);
		$ww = imagesx($watermark);
		$wh = imagesy($watermark);
		$img_paste_x = 0;

		while ($img_paste_x < $w) {
			$img_paste_y = 0;
			while ($img_paste_y < $h) {
				imagecopy($image, $watermark, $img_paste_x, $img_paste_y, 0, 0, $ww, $wh);
				$img_paste_y += $wh;
			}
			$img_paste_x += $ww;
		}

		if ($this->ext == 'webp') {
			imagewebp($image,  $newname, 90);
		} else if ($this->ext == 'png') {
			imagepng($image,  $newname, 9);
		} else {
			imagejpeg($image,  $newname, 90);
		}

		imagedestroy($image);
		imagedestroy($watermark);
		$this->deleteUploaded($desImage);
	}
}
