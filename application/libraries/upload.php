<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * agheli
 *
 *
 * @package		agheli
 * @author		ali asghar agheli علی اصغر عاقلی
 * @copyright	        Copyright (c).
 * @license		http://irnote.ir
 * @link		        http://irnote.ir
 * @e-mail		ali.a.agheli@gmail.com 
 * @Tell		0935 72 82 401 
 * @since		Version 1.0
 * @filesource
 */


class upload {
	
    /**
     * List of all Extension video
     *
     * @private array
     */

    private $ext_video = array(
        1  => 'mp4',
        2  => 'avi',
        3  => '3gp',
        4  => 'divx',
        5  => 'flv',
        6  => 'mkv',
        7  => 'movie',
        8  => 'tiff',
        9  => 'mov',
        10 => 'mpeg',
        11 => 'video'
    );
	
	
    /**
     * List of all Extension image
     *
     * @private array
     */

    private $ext_image = array(
        1  => 'gif',
        2  => 'jpeg',
        3  => 'png',
        4  => 'swf',
        5  => 'psd',
        6  => 'bmp',
        7  => 'tiff',
        8  => 'tiff',
        9  => 'jpc',
        10 => 'jp2',
        11 => 'jpf',
        12 => 'jb2',
        13 => 'swc',
        14 => 'aiff',
        15 => 'wbmp',
        16 => 'xbm'
    );
		
		
    /**
     * List of all Extension audio
     *
     * @private array
     */		

    private $ext_audio = array(
        1  => 'mp3',
        2  => 'ogg'
    );
	   
	   
    /**
     * List of all Extension text
     *
     * @private array
     */	   
	   
	   
    private $ext_text = array(
        1  => 'tex',
        2  => 'txt'
    );
	   
	   
	/**
	 * List of all Extension application
	 *
	 * @private array
	 */   
	   
	   
    private $ext_application = array(
        1  => 'docx',
        2  => 'pdf',
        3  => 'rar',
        4  => 'xls',
        5  => 'xlsx',
        6  => 'doc',
        7  => 'zip',
        8  => 'ppt',
        9  => 'pptx'
    );
	   
    /**
     * List of all Extension 
     *
     * @private array
     */ 	
	
    private $ext_all_perm = array(
        1  => 'docx',
        2  => 'pdf',
        3  => 'rar',
        4  => 'xls',
        5  => 'xlsx',
        6  => 'doc',
        7  => 'zip',
        8  => 'ppt',
        9  => 'pptx',
        10  => 'gif',
        11  => 'jpeg',
        12  => 'png',
        13  => 'jpg'
    );
		
    /*
     * upload dir
     *
     * @public string
     * @ desc : add systempath
     */

     public $dir_upload = 'upload/';


    /*
     * format size
     *
     * @public : array
     */

     public $format_size = array (
        'kb'=>'1024',
        'mb'=>'1048576'
    );


    /*
     * format size
     *
     * @private : string
     */


     private $type_size = '';

    /*
     * upload dir files
     *
     * @private array
     */

     private $dir_files = array (
        'image'       => 'image/',
        'video'       => 'video/',
        'application' => 'application/',
        'audio'       => 'audio/'
     );


    /*
     * dir title
     *
     * @private string
     */


     private $dir_title = array ();


    /*
     * categorize file
     *
     * @public bool
     */


     public $categorize = true;



    /*
     * mode read and write
     *
     * @public num
     */


     public $mode = 0777;



    /*
     * file name
     *
     * @public string
     */


      public $file_name = 'file';

    /*
     * file type
     *
     * @public string
     */


      public $filetype = '';	 

    /*
     * file size
     *
     * @public array num kb
     * 
     */	 


     public $file_size = array(0=>'5');


    /**
     * List of all Extension perm
     *
     * @private array
     */		

     private $perm = array();


    /**
     * List of result
     *
     * @public array
     */		

    public $result = array();

    /**
     * type of file
     *
     * @public array
     */

    public $sub_dir = array ();




    /*
     * set_config
     *
     * @$config array()
     * 
     * get config
     */



     function set_config ( $conig =array() )
     {
        $this->initialization ($conig);
     }


    /*
     * initialization
     *
     * @$config array()
     * 
     * set config 
     */	 
	 
    private function initialization ($conig=array())
    {
       $this->file_name = isset($conig['name'])?$conig['name']:$this->file_name;
       $this->perm      = isset($conig['perm'])?$conig['perm']:$this->ext_all_perm;
       $this->file_size = isset($conig['size'])?$conig['size']:$this->file_size;
       $this->dir_title = isset($conig['title'])?$conig['title']:$this->dir_title;
       $this->type_size = isset($conig['type_size'],$this->format_size[$conig['type_size']])?$this->format_size[$conig['type_size']]:$this->format_size['mb'];
       if ( isset ( $_SESSION['uid'] ) ) $this->dir_upload .= $_SESSION['uid'].SLASH;
       $this->analyze_file ();
    }


	/*
	 * analyze_file
	 *
	 */	 
	 
	 
    private function analyze_file ()
    {

        if ( isset ( $_FILES[$this->file_name] ) )
        {
            $this->check_size ();
        }
        else
            return $this->result['error'][$this->file_name] = _MSG_UPLOAD_NOT_FOUNF_FILE;

       if ( ! isset ( $this->result['error'] ) ) 
            $this->set_name();
    }
	 
	 
    /*
     * check_size
     *
     */	 

     private function check_size ()
     {
        $size = 0 ;
        if ( count($this->file_size) == 1) $size = $this->file_size[0];

        if ( is_array ( $_FILES[$this->file_name]['size'] ) )
        {
            foreach (  $_FILES[$this->file_name]['size'] as $index=>$file_size )
            {
                if ( $size == 0 ) $size = isset ($this->file_size[$index])?$this->file_size[$index]:0;
                $this->analyze_size ( $file_size , $size , $index);
            }
        }
        else
            $this->analyze_size ( $_FILES[$this->file_name]['size'] , $size );

       if ( ! isset ($this->result['error']) )
            $this->check_type();

     }


     private function analyze_size ($file_size,$size,$index=-1)
     {  
        $this->result['result']['size'] = $file_size;
        if ( ($file_size/$this->type_size)  > $size )  $this->result['error']["size|{$index}|".__LINE__] = _MSG_UPLOAD_NOT_MATCH_SIZE."[5mb]";
     }
	 
	 

    /*
     * check_type
     *
     */

     private function analyze_type ($name,$index=-1)
     {
        $ext_arr = explode(".",basename($name));
            $ext = strtolower(trim ( end ($ext_arr) ));
        if ( ! in_array ( $ext , $this->perm ) )
        {
            $this->result['error']["type|{$index}|{$ext}|".__LINE__] = _MSG_UPLOAD_NOT_MATCH_EXT.'<span class="pur_left">'.implode(' , ',$this->perm).'</span>';
        }
        else
        {
            if ( $index == -1 )  $this->result['result']['ext'] = $ext;
            else $this->result['result']['ext'][$index] = $ext;
        }
     }
	 
	 
	 
    private function check_type ()
    {
        if ( is_array ( $_FILES[$this->file_name]['name'] ) )
        {
            foreach (  $_FILES[$this->file_name]['name'] as $index=>$name )
            {
                $this->analyze_type( $name , $index);
            }
        }
        else
            $this->analyze_type ( $_FILES[$this->file_name]['name'] );
    }
	 
	 
    private function set_name ()
    {
        if ( is_array ( $_FILES[$this->file_name]['name'] ) )
        {
            foreach (  $_FILES[$this->file_name]['name'] as $index=>$name )
            {
                $this->result['result']['old_name'][$index] = str_replace('.'.$this->result['result']['ext'][$index],'',$name);
                $this->result['result']['name'][$index] = $this->now().random_code(3).'_'.$index.'_'.$this->result['result']['ext'][$index].'';
                $this->result['result']['type'][$index] = $_FILES[$this->file_name]['type'][$index];
            }
        }
        else
        {
            $this->result['result']['old_name'] = str_replace('.'.$this->result['result']['ext'],'',$_FILES[$this->file_name]['name']);
            $this->result['result']['name'] = $this->now().random_code(3).'_'.$this->result['result']['ext'].'';
            $this->result['result']['type'] = $_FILES[$this->file_name]['type'];
        }

        $this->setdir();
    }


   /*
    * makedir
    *
    */
	 
	 
    private function  makedir ($path,$index=-1)
    {
        $dir  = explode('/',$path);
        $is_dir = '';

        foreach ( $dir as $key=>$name )
        {
            $is_dir .= trim($name.SLASH);
            if ( !is_dir ( $is_dir ) ) mkdir ( $is_dir , $this->mode , true );
        }

        if ( $index == -1 )  $this->result['result']['dir'] = $is_dir;
        else $this->result['result']['dir'][$index] = $is_dir;
        return TRUE;
    }
	 
	 
	 
	 
    private function setdir ()
    {
        if ( is_array ( $this->result['result']['type'] ) )
        {
            $path = '';
            $path .=  $this->dir_upload;

            foreach ( $this->result['result']['type'] as $index=>$value )
            {
                if ( isset ( $this->dir_title[$index]) ) $path .= $this->dir_title[$index].SLASH;
                $this->dir_upload .=  $value.SLASH;
            }
            $this->makedir($path,$index);
        }
        else
        {
            $path = '';
            $path .=  $this->dir_upload;
            if ( isset ( $this->dir_title[0]) ) $path .= $this->dir_title[0].SLASH;
            $path .=  $this->result['result']['type'];
            $this->makedir($path);
        }

        $this->uploads();
    }

    /*
     * upload
     *
     */

     private function move ($name ,$index=-1)
     {
        if ( $index == -1 )
        {
            $temp = $_FILES[$this->file_name]["tmp_name"];
            $file = $this->result['result']['dir'].$name;
            $old_name = $this->result['result']['old_name'];
            $ext = $this->result['result']['ext'];
        }
        else
        {
            $temp = $_FILES[$this->file_name]["tmp_name"][$index];
            $file = $this->result['result']['dir'][$index].$name;
            $old_name = $this->result['result']['old_name'][$index];
            $ext = $this->result['result']['ext'][$index];
        }

        if ( !move_uploaded_file($temp, $file) )
        {
            $this->result['error']["upload|{$index}|{$file}|".__LINE__] = _MSG_NOTUPLOAD_ANY_WAY_."[{$old_name}.{$ext}]";
        }
     }



     private function uploads ()
     {
        if ( is_array ( $this->result['result']['name'] ) )
        {
            foreach ( $this->result['result']['name'] as $index=>$vlaue )
            {
                $this->move ( $name ,$index );
            }
        }
        else
        {
            $this->move ( $this->result['result']['name'] );
        }
     }



    private function now($type=false)
    {
        if ( $type == false ) return date("YmdHis"); 
        else return explode('-',date("Y-m-d"));
    }	
}