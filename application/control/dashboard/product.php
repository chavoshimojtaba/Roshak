<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once DIR_LIBRARY . "ssr_grid.php"; 
class product extends Controller
{
    public $block = 'index';
    public function _index($slug = '')
    {
        // $_SESSION['mid'] = 1;
        if (!is_designer()) {
            error_404($this);
        }
        // pr($_SESSION,true);
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        if ($slug) {
            $this->block = 'view';
            $this->load->model('model_product');
            $res = $this->model_product->get_product_detail($slug, true);
            if ($res->count > 0) {
                require_once(DIR_HELPER . "helper_html.php");
                $attributes = productValues('product_attributes', true);
                $data = decode_html_tag($res->result[0], true);
                $data['favorite_btn'] = is_favorite($data['id']) ? '<i class="icon icon-heart-bold float-start text-danger fs-4"></i>' : '<i class="icon icon-header-2 fs-4"></i>';
                $data['plan_btn']     = '<a href="' . HOST . 'plan" class="btn btn-warning text-white rounded-3 py-3">خرید اشتراک و دانلود رایگان</a>';
                $data['dimensions']   = $attributes['dimensions'][$data['dimensions']];
                $data['color_mode']   = $attributes['color_mode'][$data['color_mode']];
                $data['layer']        = $attributes['layer'][$data['layer']];
                $data['resulation']   = $attributes['resulation'][$data['resulation']];
                $data['code']         =  ($data['id'] + 1000);
                $data['createAt']     = g2p($data['createAt']);
                $data['price']        = toman($data['price'], true);
                $data['is_image']     = ($data['type'] === 'image') ? '' : 'd-none'; 
                $this->load->model('model_tag');
                $this->model_tag->_tableType =  'product';
                $resTags = $this->model_tag->get_content_tags($data['id']);
                $catgories = GLOBALS('category', false);
                $bread_crump = [];
                $ex = explode('-', $data['cat_path']);
                foreach ($ex as $v) {
                    $cat = $catgories[(int) filter_var($v, FILTER_SANITIZE_NUMBER_INT)];
                    $bread_crump[] = ['title' => $cat['title'], 'url' => 'search/' . $cat['slug']];
                }
                $html = '';
                $cnt  = count($bread_crump);
                foreach ($bread_crump as $key => $value) {
                    if ($cnt > $key + 1) {
                        $html .= '<a href="٫" class="text-dark d-flex text-nowrap text-decoration-none ms-2"> ' . $value['title'] . '<i class="text-primary icon-arrow icon-arrow-left-24  fs-5 pe-2"></i> </a>';
                    } else {
                        $html .= '<a href="٫" class="text-dark text-nowrap text-decoration-none ms-2"> ' . $value['title'] . '</a>';
                    }
                } 
                $data['cat_breadcrump'] = $html; 
                if ($resTags->count > 0) {
                    foreach ($resTags->result as $key => $value) {
                        $value['title'] = decode_html_tag($value['title'], true);
                        $this->template->assign($value);
                        $this->template->parse('view.tags');
                    }
                }else{
                    $value['title'] = 'مشخص نشده';
                    $this->template->assign($value);
                    $this->template->parse('view.tags');
                }
                $galleries[0] = ['dir' => $data['img']];
                $this->load->model('model_file');
                $res_gallery = $this->model_file->get_resource_files($data['id'], 'product'); 
                if ($res_gallery->count > 0) {
                    $galleries = array_merge($galleries, $res_gallery->result);
                }
                foreach ($galleries as $key => &$value) {
                    $value['img_200'] = thumbnail($value['dir'], 200);
                    $this->template->assign($value);
                    $this->template->parse('view.gallery');
                    $this->template->parse('view.gallery1');
                    $this->template->parse('view.gallery2');
                } 
                $attributes   = productValues('attribute', 'all', explode('-', $data['format']));
                foreach ($attributes['format']['data'] as $attribute_formats_value) {
                    if ($attribute_formats_value['selected']) {
                        $this->template->assign($attribute_formats_value);
                        $this->template->parse('view.formats');
                    }
                }
                $last30days = array();
                $this->load->model('model_report');
                $data['total_sell_chart'] = 0;
                $sell_statistics = $this->model_report->designer_sell($_SESSION['mid'], ['days' => 30,'pid' => $data['id']]);
                $data['total_download_chart'] = $this->model_report->designer_downloads($_SESSION['mid'],$data['id']);
                if ($sell_statistics->count > 0) {
                    foreach ($sell_statistics->result as $key => $value) {
                        $last30days[] = ['g' => $value['date'], 'p' => g2p($value['date']), 'total_price' => $value['total_price']];
                        $data['total_sell_chart'] += $value['total_price'];
                    }
                }
                $data['days'] = json_encode($last30days); 
                if($data['status'] == 'pend'){
                    $this->template->parse('view.pending');
                }else if($data['status'] == 'reject'){
                    $this->template->assign($data);
                    $this->template->parse('view.rejected');
                }
                $GridData_comments = new SSR_Grid(array_merge([
                    'limit'  => '3',
                    'type'  => 'comment',
                ], ['pbid' => $data['id']]));
                if ($GridData_comments->getData()['count'] > 0) { 
                    $this->template->assign(['ssr_grid_comment'=>$GridData_comments->html()]);
                    $this->template->parse('view.has_comment');
                }
                if ($_SESSION['designer_show_status'] === 'yes') {  
                    $this->template->parse('view.edit_btn');
                }
                $this->page->set_data([
                    'title' => $data['title'],
                    'desc' => $data['meta'],
                    'follow_index'=>'follow, noindex', 
                    'breadcrump' => [LANG_PRODUCTS],
                    'files' => [
                        ['url'=>'file/client/css/profile.css','type'=>'css'],
                        ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                        ['url' => 'file/client/css/slick.css', 'load' => '', 'type' => 'css'], 
                        ['url'=>'file/client/js/owl.carousel.min.js','load'=>'','type'=>'js'],
                        ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'],
                        ['url'=>'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js','load'=>'','type'=>'js'],
                    ]
                ]);
            } else { 
                error_404($this);
            }
        } else {
            $this->post = decode_html_tag($this->router->request_get(), true);
            $categories = GLOBALS('category');
            foreach ($categories as &$value) {
                if (isset($this->post['cid']) && $this->post['cid'] == $value['id']) {
                    $value['selected'] = 'selected';
                }
                $this->template->assign($value);
                $this->template->parse('index.categories');
            }
            $this->load->model('model_financial');
            $statistics = member_credit($_SESSION['mid']); 
            $this->post['mid'] = $_SESSION['mid'];

            $this->load->model('model_product'); 
            $products_statistics = $this->model_product->get_designer_statistics($_SESSION['mid'])->result[0]; 
            // pr($products_statistics,true);

            $GridData = new SSR_Grid(array_merge([
                'limit'  => '15',
                    'template'  => 'search',
                    'product_url'  => 'product',
                    'designer_show_all'  => true,
                    'status'  => 'all',
                    'type'  => 'product',
            ], $this->post));  
            $data = array_merge($GridData->getData(), $statistics,$products_statistics); 
            $data['q'] = $this->post['q']; 
            $data['ssr_grid'] = $GridData->html();
            $data['statistic_product'] = $designer['statistic_product'];  
            
            
            $status = [
                'all' => ' تمام وضعیت ها',
                'pend' => ' در حال بررسی',
                'reject' => ' رد شده',
                'accept' => ' منتشر شده',
            ];
            
            foreach ($status as $key => &$value) {
                $selected = '';
                if (isset($this->post['status']) && $this->post['status'] == $key) {
                    $selected = 'selected';
                }
                $this->template->assign(['title' => $value, 'key' => $key, 'selected' => $selected]);
                $this->template->parse('index.status');
            }
            $data['date'] = $this->post['date'];

            $this->page->set_data([
			'follow_index'=>'follow, noindex', 
            'title' => LANG_PRODUCTS,
                'desc' => LANG_PRODUCTS,
                'breadcrump' => [LANG_PRODUCTS],
                'files' => [
                    ['url'=>'file/client/css/profile.css','type'=>'css'],
                    ['url'=>'file/client/js/owl.carousel.min.js','load'=>'','type'=>'js'],
                    ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                    ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'],
                    ['url' => 'file/global/persianDatePicker/mds.bs.datetimepicker.style.css', 'type' => 'css'],
                    ['url' => 'file/global/persianDatePicker/mds.bs.datetimepicker.js', 'load' => 'defer', 'type' => 'js'],
                ]
            ]);
        }
        $this->template->assign($data);
        // pr($data,true);
        $this->display();
    }
    public function add($id = 0)
    {
        if (!is_designer() || $_SESSION['designer_show_status'] !== 'yes') {
            error_404($this);
        }
        $data = ['title'=>''];
        $product_attribute_ids = [];
        $this->block = 'add';
        $this->post = decode_html_tag($this->router->request_get(), true);
        $post = [];
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $data['btn_display']     = 'none';

        $product_attribute_ids = [];
        if ($id > 0) {
            $this->load->model('model_product');
            $res = $this->model_product->get_product_detail($id);
            if ($res->count > 0) {
                $data = decode_html_tag($res->result[0], true);
                $data['btn_display']     = 'flex';
                // ////pr($res,true);
                $data['pid']       = $id;
                $product_attribute_ids = [
                    $data['color_mode'],
                    $data['dimensions'],
                    $data['resulation'],
                    $data['layer'],
                ];
                $product_attribute_ids = array_merge($product_attribute_ids, explode('-', $data['format']));

                /* -------------------------------------------------------------------------- */
                /*                                    tags                                    */
                /* -------------------------------------------------------------------------- */
                $this->load->model('model_tag');
                $this->model_tag->_tableType =  'product';
                $resTags = $this->model_tag->get_content_tags($id);
                if ($resTags->count > 0) {
                    foreach ($resTags->result as $key => $value) {
                        $value['title'] = decode_html_tag($value['title'], true);
                        $this->template->assign($value);
                        $this->template->parse('add.tags');
                    }
                }




                $galleries = [['dir' => $data['img'], 'id' => $data['id']]];
                $this->load->model('model_file');
                $res_gallery = $this->model_file->get_resource_files($id, 'product');

                if ($res_gallery->count > 0) { 
                    $galleries = array_merge($galleries ,$res_gallery->result);
                }
                foreach ($galleries as $key => $value) { 
                    $this->template->assign($value);
                    $this->template->parse('add.gallery');
                } 
                $this->template->assign(['main_file' => $data['file'], 'id' => $data['id']]);
                $this->template->parse('add.main_file'); 
            }
        }
        $attributes   = productValues('attribute', 'all', $product_attribute_ids); 
        foreach ($attributes['dimensions']['data'] as $key => $value) {
            $this->template->assign($value);
            $this->template->parse('add.dimensions');
        }
        foreach ($attributes['color_mode']['data'] as $key => $value) {
            $this->template->assign($value);
            $this->template->parse('add.color_mode');
        }
        foreach ($attributes['layer']['data'] as $key => $value) {
            $this->template->assign($value);
            $this->template->parse('add.layer');
        }
        foreach ($attributes['resulation']['data'] as $key => $value) {
            $this->template->assign($value);
            $this->template->parse('add.resulation');
        }
        foreach ($attributes['format']['data'] as $key => $value) {
            $this->template->assign($value);
            $this->template->parse('add.formats');
        }
        $this->load->model('model_category');
        $res = $this->model_category->get_list('product');
        if($res->count> 0){
            require_once(DIR_HELPER . "helper_html.php");
            $tree = buildMenuTree($res->result);
            // pr($tree,true);
            $data['menuObject'] = json_encode($tree,true); 
        }
        $this->template->assign($data);

        $this->page->set_data([
            'title' => LANG_PRODUCTS,
            'desc' => LANG_PRODUCTS,
            'breadcrump' => [LANG_PRODUCTS],
            'files' => [ 
                ['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url'=>'file/client/css/uploadbox.css','type'=>'css'],
                ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'],
                ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                ['url' => 'file/global/tinymce/tinymce.min.js', 'load' => '', 'type' => 'js'],
                ['url' => 'file/global/dropzone/dropzone.css',  'type' => 'css'],
                ['url' => 'file/global/dropzone/dropzone.js', 'load' => '', 'type' => 'js'],
                ['url' => 'file/global/tinymce/tinymce_config_mini.js', 'load' => '', 'type' => 'js'],
            ]
        ]); 
        $this->display();
    }

    public function display()
    {
        $this->template->parse($this->block);
        out([
            'content' => $this->template->text($this->block)
        ]);
    }
}
/*  public function addd($id = 0)
 {

     $data = [];
     $product_attribute_ids = [];

     if ($id > 0) {
         $product_attribute_ids = [];
         $this->load->model('model_product');
         $res = $this->model_product->get_product_detail($id);
         if ($res->count > 0) {
             $data = $res->result[0];
             $data['pid']       = $id;
             $data['title']     = decode_html_tag($data['title'], true);
             $data['slug']      = decode_html_tag($data['slug'], true);
             $data['seo_title'] = decode_html_tag($data['seo_title'], true);
             $data['meta']      = decode_html_tag($data['meta'], true);
             $data['desc']      = decode_html_tag($data['desc'], true);
             $data['index']     = ($data['index'] === 'yes') ? 'checked' : '';
             $data['follow']    = ($data['follow'] === 'yes') ? 'checked' : '';
             $product_attribute_ids = [
                 $data['color_mode'],
                 $data['dimensions'],
                 $data['resulation'],
                 $data['layer'],
             ];
             $product_attribute_ids = array_merge($product_attribute_ids, explode('-', $data['format']));
             $this->load->model('model_tag');
             $this->model_tag->_tableType =  'product';
             $resTags = $this->model_tag->get_content_tags($id);
             $tags    = [];
             if ($resTags->count > 0) {
                 foreach ($resTags->result as $key => $value) {
                     $tags[$key]['title'] = $value['title'];
                     $tags[$key]['id'] = $value['id'];
                     $tags[$key]['selected'] = 'true';
                 }
                 $this->html->tags = $tags;
                 $this->loop[] = 'tags';
             }
             $this->load->model('model_file');
             $res_gallery = $this->model_file->get_resource_files($id, 'product');
             if ($res_gallery->count > 0) {
                 $gallery    = [];
                 foreach ($res_gallery->result as $key => $value) {
                     $gallery[$key]['gallery_file'] = $value['dir'];
                     $gallery[$key]['id'] = $value['id'];
                 }
                 $this->html->gallery = $gallery;
                 $this->loop[] = 'gallery';
             }
             $this->html->set_data($data);
             $this->html->org_file = [['file' => $data['file'], 'id' => $data['id']]];
             $this->loop[] = 'org_file';
             $this->html->org_pic = [['img' => $data['img'], 'id' => $data['id']]];
             $this->loop[] = 'org_pic';
         }
     }

     $attributes   = productValues('attribute', 'all', $product_attribute_ids);
     $productTypes = values('filetype');
     if (@$data['format'] != '') {
         $explode = explode('-', $data['format']);
         foreach ($attributes['format']['data'] as &$attribute_formats_value) {
             if (in_array($attribute_formats_value['title'], $explode)) {
                 $attribute_formats_value['selected'] = 'selected';
             }
         }
     }

     $this->html->attribute_formats = $attributes['format']['data'];
     $this->loop[] = 'attribute_formats';
     $this->load->model('model_category');
     $res = $this->model_category->get_list('product');
     if ($res->count > 0) {
         require_once(DIR_HELPER . "helper_html.php");
         $tree = buildMenuTree($res->result);
         $this->html->menuObject = json_encode($tree, true);
     }

     $this->html->formats = implode(' , ', get_formats('image', 2));
     $types = [];


     unset($attributes['format']);
     $attribute_selectbox = [];
     foreach ($attributes as $key => $value) {
         $attribute_selectbox[$key] = [
             'grp' => $value['grp'],
             'alias' => $value['alias'],
             'options' => createAttributeOptions($value['data'])
         ];
     }

     $this->html->attributes_selectbox = $attribute_selectbox;
     $this->loop[] = 'attributes_selectbox';
     $this->display();
 } */
