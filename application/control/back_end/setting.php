<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(DIR_LIBRARY."ExportExcel.php");
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class setting extends Controller{

    protected $block           = '';
    public $loop               = [];
    public $exportableTables  = [];

    public function __construct()
    {
        parent::__construct();
        $this->exportableTables = [
			'blog'=>[
                'title'=>LANG_BLOG,
                'fields'=>[
                    1   =>'title',
                    2   =>'desc',
                    3   =>'short_desc'],
                'mapFields'=>[
                    'C' =>'title',
                    'E' =>'desc',
                    'G' =>'short_desc'
                ],
            ],
			'tags'=>[
                'title'=>LANG_TAG,
                'fields'=>[
                    1=>'title'
                ],
                'mapFields'=>[
                    'C'=>'title'
                ]
            ],
			'pages'=>[
                'title'=>LANG_PAGES,
                'fields'=>[
                    1=>'title',
                    2=>'desc'
                ],
                'mapFields'=>[
                    'C'=>'title',
                    'E'=>'desc'
                ]
            ],
			'pages_about_us'=>[
                'title'=>LANG_ABOUT_US,
                'fields'=>[
                    1=>'title',
                    2=>'desc',
                    3=>'mission'
                ],
                'mapFields'=>[
                    'C'=>'title',
                    'E'=>'desc',
                    'G'=>'mission',
                ]
            ],
		];

    }

    public function public_links ()
    {

        $this->display();
    }
    public function calendar ()
    {

        $this->display();
    }
    public function index ()
    {
        $this->display();
    }

    public function user_stories ()
    {
        $this->display();
    }

    public function footer_links ()
    {
        require_once(DIR_HELPER . "helper_html.php");

        generateFooterLinksCache();
        $this->load->model('model_settings');
        $res = $this->model_settings->footer_link_columns();
        if ($res->count > 0)
        {
            $columns = [];
            foreach ($res->result as  &$value) {
                $value = decode_html_tag($value,true);
                if($value['pid'] == 0){
                    $columns[] = $value;
                }
            }
            $this->html->column_form = $columns;
            $this->html->columns = $columns;

            $this->loop[] = 'columns';
            $this->loop[] = 'column_form';
        }
        $this->display();
    }

    public function header_links ()
    {
        $this->display();
    }

    public function add_user_stories ($id='')
    {
        if($id > 0){
            $this->load->model('model_settings');
            $res = $this->model_settings->get_user_stories($id);
            if ($res->count > 0)
            {
                $data = $res->result[0];
                $data['fullname']  = decode_html_tag($data['fullname'],true);
                $data['text']  = decode_html_tag($data['text'],true);
                $data['sub_title']  = decode_html_tag($data['sub_title'],true);
                $this->html->set_data($data);
                $this->html->image = [
                    0=>['pid'=>$id,'pic'=>$data['pic']]
                ];
                $this->loop[] = 'image';
            }
        }
        $this->html->formats = implode(' , ',get_formats('image',2));

        $this->display();
    }

    public function add_public_links ($id='')
    {
        if($id > 0){
            $this->load->model('model_settings');
            $res = $this->model_settings->get_public_link_detail($id);
            if ($res->count > 0)
            {
                $data = $res->result[0];
                $data['text']  = decode_html_tag($data['text'],true);
                $data['url']  = decode_html_tag($data['url'],true);
                $this->html->set_data($data);
            }else{
                error_404($this);
            }
        }
        $options = [
            ['title'=>'HEADER','id'=>'header'],
            ['title'=>'TAG','id'=>'tag'],
            ['title'=>'BACK_LINK','id'=>'back_link'],
        ];
        if($id > 0){
            foreach ($options as $key => &$value) {
                if($data['type'] == $value['id']){
                    $value['selected'] = 'selected';
                }else{
                    $value['selected'] = '';

                }
            }
        }
        // pr($options,true);
        $this->html->options = $options;
        $this->loop[] = 'options';
        $this->display();
    }

    public function add_header_links ($id='')
    {
        if($id > 0){
            $this->load->model('model_settings');
            $res = $this->model_settings->get_header_links($id);
            if ($res->count > 0)
            {
                $data = $res->result[0];
                $data['title']  = decode_html_tag($data['title'],true);
                $data['url']  = decode_html_tag($data['url'],true);
                $this->html->set_data($data);
            }
        }
        $this->display();
    }

    public function site ()
    {
        $this->load->model('model_settings');
        $res = $this->model_settings->get_seo_info();
        if ($res->count > 0)
        {
			$data = decode_html_tag($res->result[0],true);

            $this->html->set_data($data);

        }

        $this->display();
    }

    public function translation ($tablesFilter='',$all='0')
    {
        if($tablesFilter != ''){
            $this->load->model('model_translation');
            $res = $this->model_translation->get_translation_fields(explode(',',$tablesFilter),$this->exportableTables,$all);
            if ($res->count > 0)
            {
                $headers = [
                    'شناسه',
                    'متن 1',
                    'ترجمه انگلیسی 1',
                    'متن 2',
                    'ترجمه انگلیسی 2',
                    'متن 3',
                    'ترجمه انگلیسی 3',
                    'جدول',
                ];
                foreach ($res->result as $key => $value)
                {
                    $data[$key+1] = decode_html_tag($value,true);
                }
            }
            $excel = new ExportExcel('translation');
            $excel->setData($headers,$data);
            $excel->generate('translate');
        }
        $tables = [];
        foreach ($this->exportableTables as $key => $value) {
            $tables[$key]['id'] = $key;
            $tables[$key]['name'] = $value['title'];
        }
        $this->html->tables = $tables;
        $this->loop[] = 'tables';
        $this->display();
    }

    public function import_translations ($id=0)
    {
        $errs = [];
        if($id != '0'){
            $this->load->model('model_file');
            $res = $this->model_file->get_file_by_id($id);
            if ($res->count > 0)
            {
                $file = $res->result[0];
                $reader = new Xlsx();
                $fileDir = $file['dir'].$file['name'];
                $workbook = $reader->load($fileDir);
                $data = $workbook->getSheet(0)->toArray(null,true,true,true);
                $data = array_splice($data,1);
                if(count($data[0]) != 9){
                    $errs[]['msg'] = 'ساختار فایل تغییر یافته است.';
                }else{
                    $queries = [];
                    foreach ($data as $key => $value) {
                        $tableName = $value['H'];
                        $id = $value['A'];
                        $tableFields = $this->exportableTables[$tableName]['mapFields'];
                        $query_fields = [];
                        foreach ($tableFields as $k => $field) {
                            $val = encode_html_tag($value[$k],true);
                            $query_fields[] = "`tp_{$field}_en` = '{$val}'";
                        }
                        $queries[$tableName][$id] = implode(' , ',$query_fields);
                    }
                    if(count($queries) <= 0){
                        $errs[]['msg'] = 'محتوای فایل معتبر نمیباشد';
                    }else{
                        $this->load->model('model_translation');
                        $res = $this->model_translation->update_translation($queries);
                        if(count($res) <= 0){
                            $errs[]['msg'] = 'خطا در ارتباط با دیتابیس';
                        }else{
                            $this->html->success = [0=>['data'=>json_encode($res,true)]];
                            $this->loop[] = 'success';
                        }
                    }
                }

            }else{
                $errs[]['msg'] = 'فایل مورد نظر یافت نشد.';
            }
        }else{
            $errs[]['msg'] = 'لطفا فایل مورد نظر را برای بارگذاری انتخاب نمایید.';
        }
        $this->html->errs = $errs;
        $this->loop[] = 'errs';
        $this->display();
    }

    private function display ()
    {
        // pr($this->router->method,true);
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
