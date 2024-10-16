<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*   error_reporting(E_ALL);
  ini_set('display_errors', '1'); */
class file extends Controller{

    protected $block        = '';
    public $loop            = [];
    public $ext_class = [
		".webp" => 'primary',
		".jpeg" => 'primary',
		".jpg" => 'primary',
		".png" => 'primary',
		".gif" => 'primary',
		".pdf" => 'success',
		".zip" => 'success',
		".mp4" => 'danger',
		".xls" => 'success',
		".xlsx" => 'success',
		".csv" => 'success',
		".txt" => 'success',

		"'webp'" => 'primary',
		"'jpeg'" => 'primary',
		"'jpg'" => 'primary',
		"'png'" => 'primary',
		"'gif'" => 'primary',
		"'zip'" => 'success',
		"'pdf'" => 'success',
		"'mp4'" => 'danger',
		"'xls'" => 'success',
		"'xlsx'" => 'success',
		"'csv'" => 'success',
		"'txt'" => 'success',

		"webp" => 'primary',
		"jpeg" => 'primary',
		"jpg" => 'primary',
		"png" => 'primary',
		"gif" => 'primary',
		"zip" => 'success',
		"pdf" => 'success',
		"mp4" => 'danger',
		"xls" => 'success',
		"xlsx" => 'success',
		"csv" => 'success',
		"txt" => 'success',
	];

    public function __construct()
    {
        parent::__construct();
    }

    public function index ($type=0,$forEditor=0,$cat=0)
    {
        if($type == 'gallery'){
            $type = 'image';
        }
        $this->html->type   = $type;
        $this->html->editor = $forEditor;
        $this->html->cat    = $cat;
        $formats            = '';
        if($type !== 0 && !in_array($type,['image','doc','video']) ){
            $type = decode_html_tag($type,true);
            $allformats = explode(',',$type);
            $arr = [];
            foreach ($allformats as $value) {
                $arr[]=".".$value;
            }
            $formats = implode(',',$arr);
        }else if($type !== 0){
            $formats = implode(' , ',get_formats($type,2));
        }else{
            $formats = get_formats();
        }
        $this->html->formats =$formats;
        $format_array =explode(',',$formats);
        $data = [];
        if (count($format_array) > 0 ) {
            foreach ($format_array as $key => $value)
            {
                $value = trim($value);
                $data[$key]['ext'] = $value;
                $data[$key]['class'] = $this->ext_class[$value];
            }
        }
        $this->html->ext = $data;
        $this->loop[] = 'ext';
        $this->display();
    }

    public function category ()
    {
        $this->display();
    }

    private function display ()
    {
        out([
            'content' => $this->html->tab_links(
                [],
                min_template(
                    $this->html->get_string('array'),
                    $this->loop,
                    $this->router->method
                    ),
                $this->router->method
                )
        ],'admin');
    }
}
