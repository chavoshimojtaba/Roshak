<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class product extends Controller{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function add ($id=0)
    {

        $data=[];
        $product_attribute_ids=[];

        if($id > 0){
            $product_attribute_ids = [];
            $this->load->model('model_product');
            $res = $this->model_product->get_product_detail($id);
            if($res->count > 0){
                $data = decode_html_tag($res->result[0],true);
                $data['pid']       = $id;
                $data['main_title']     = $data['title'];
                $data['index']     = ($data['index'] === 'yes')?'checked':'';
                $data['follow']    = ($data['follow'] === 'yes')?'checked':'';
                
                $this->load->model('model_tag');
        	    $this->model_tag->_tableType =  'product' ;
                $resTags = $this->model_tag->get_content_tags($id);
                $tags    = [];
                if($resTags->count > 0){
                    foreach ($resTags->result as $key => $value)
                    {
                        $tags[$key]['title']=$value['title'];
                        $tags[$key]['id']=$value['id'];
                        $tags[$key]['selected']='true';
                    }
                    $this->html->tags = $tags;
                    $this->loop[] = 'tags';
                }
                $this->load->model('model_file');
                $res_gallery = $this->model_file->get_resource_files($id,'product');
                if($res_gallery->count> 0){
                    $gallery    = [];
                    foreach ($res_gallery->result as $key => $value) {
                        $gallery[$key]['gallery_file']=$value['dir'];
                        $gallery[$key]['id']=$value['id'];
                    }
                    $this->html->gallery = $gallery;
                    $this->loop[] = 'gallery';
                } 
                $this->html->org_pic = [['img'=>$data['img'],'id'=>$data['id']]];
                $this->loop[] = 'org_pic';
            }
        } 
        $this->load->model('model_category');
        $res = $this->model_category->get_list();
        if($res->count> 0){
            require_once(DIR_HELPER . "helper_html.php");
            $tree = buildMenuTree($res->result);
            $this->html->menuObject = json_encode($tree,true); 
        }

        $this->load->model('model_content');
        $res_loc = $this->model_content->list_loc();
        if($res_loc->count> 0){
            require_once(DIR_HELPER . "helper_html.php");
            $tree = buildMenuTree($res_loc->result);
            $this->html->locationObject = json_encode($tree,true); 
        }
 
        $this->html->set_data($data);
        $this->display();
    }

    public function view ($id)
    {
        $this->load->model('model_product');
        $res = $this->model_product->get_product_detail($id);
        if($res->count > 0){
            require_once(DIR_HELPER."helper_html.php");

            $data             = $res->result[0];
            $data['title']    = decode_html_tag($data['title'],true);
            $data['slug']     = decode_html_tag($data['slug'],true);
            $data['meta']     = decode_html_tag($data['meta'],true);
            $data['price']    = toman($data['price']);
            $data['createAt']    = g2pt($data['createAt']);
            $data['desc']     = decode_html_tag($data['desc'],true);
            $data['is_image'] = ($data['type'] === 'image')?'':'d-none';
            $data['status_badge'] = productStatus($data['status']);
           
            $this->load->model('model_tag');
            $this->model_tag->_tableType =  'product' ;
            $resTags = $this->model_tag->get_content_tags($id);
            $tags    = [];
            if($resTags->count > 0){
                foreach ($resTags->result as $key => $value)
                {
                    $tags[$key]['title']=$value['title'];
                    $tags[$key]['id']=$value['id'];
                }
                $this->html->tags = $tags;
                $this->loop[] = 'tags';
            }
        }else{
            error_404($this);
        }
        $galleries[] = ['dir'=>$data['img'] ];
        $this->load->model('model_file');
        $res_gallery = $this->model_file->get_resource_files($id,'product');
        if($res_gallery->count> 0){
            $galleries = array_merge($galleries, $res_gallery->result);
            
        }

        foreach ($galleries as $key => &$value) {
            $value['active']='';
            if($key==0){
                $value['active']='active';
            }
        }
        $this->html->gallery1 = $galleries;
        $this->html->gallery = $galleries;
        $this->loop[] = 'gallery1';
        $this->loop[] = 'gallery';


        $this->load->model('model_comment');
        $this->model_comment->_table = "`tp_product_comments`";
        $this->model_comment->_tableType =  'product' ;
        $rate = $this->model_comment->get_rate($id);
        $data['rate'] = $rate['rate'];
        $data['cnt'] = $rate['cnt'];
        $this->html->set_data($data);
        $this->display();
    }
    
    public function index ($id=0)
    {
        /* if($id>0){
            $this->load->model('model_member');
            $res = $this->model_member->designer($id);
            if($res->count > 0){
                $member = $res->result[0];
                $member['createAt'] = g2p($member['createAt']);
                $expertise = values('expertise',false,1);
                $ex = [];
                foreach ($expertise as $key => $value) {
					if(strpos($member['expertise'],'_'.$value['id'].'_') !== false){
						$ex[] = $value['name'];
					}
				}
				$member['expertise'] 	 = implode(' - ',$ex);
                $this->html->designer =[0=>$member];
                $this->loop[] = 'designer';
            }else{
                header ("location:".HOST.'admin/err');
                exit;
            }
        } */
        $this->display();
    }
    public function attributes ()
    {
        $attribute_items = [];
        $attributes    = productValues('attribute');
        foreach ($attributes as $key => $value) {
            $attribute_items[] = [
                'alias'=>$value,
                'grp' => $key
            ];

        }

        $this->html->attribute_item = $attribute_items;
        $this->loop[] = 'attribute_item';
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
