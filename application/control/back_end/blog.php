<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class blog extends Controller{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        $this->display();
    }

    public function category ()
    {
        $this->display();
    }

    public function view ($id)
    {
        $this->load->model('model_blog');
        $res = $this->model_blog->get_blog_detail($id);

        if ($res->count > 0)
        {
            $data = $res->result[0];
            $data['short_desc'] = decode_html_tag($data['short_desc'],true);
            $data['user_email'] = decode_html_tag($data['user_email'],true);
            $data['createAt'] = g2pt($data['createAt']);
            $data['slug'] = decode_html_tag($data['slug'],true);
            $data['meta'] = decode_html_tag($data['meta'],true);
            $data['desc'] = decode_html_tag($data['desc'],true);
            $tags_json = json_decode($data['tags'],true);
            $tags = [];
            foreach ($tags_json as $key => $value)
            {
                $tags[$key]['title']=$value['fa'];
            }
            $this->html->tags = $tags;

            $this->loop[] = 'tags';
            $this->load->model('model_comment');
            $this->model_comment->_table = "`tp_blog_comments`";
            $this->model_comment->_tableType =  'blog' ;
            $rate = $this->model_comment->get_rate($id);
            $data['rate'] = $rate['rate'];
            $data['cnt'] = $rate['cnt'];
            $this->html->set_data($data);

        }

        $this->html->id = $id;
        $this->display();
    }

  /*   public function add ($id=0)
    {
        $this->html->formats = implode(' , ',get_formats('image',2));

        if($id > 0){
            $res = $this->model_blog->get_blog($id);
            if($res->count > 0){
                $data = $res->result[0];
                $data['title']  = decode_html_tag($data['title'],true);
                $data['slug']   = decode_html_tag($data['slug'],true);
                $data['meta']   = decode_html_tag($data['meta'],true);
                $data['short_desc']   = decode_html_tag($data['short_desc'],true);
                $data['desc']   = decode_html_tag($data['desc'],true);

                $this->load->model('model_tag');
        	    $this->model_tag->_tableType =  'blog' ;
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
                $this->html->set_data($data);
            }
        }

        $this->load->model('model_category');
        $res = $this->model_category->get_list('blog');
        if($res->count> 0){
            require_once(DIR_HELPER . "helper_html.php");
            $tree = buildMenuTree($res->result);
            $this->html->menuObject = json_encode($tree,true);
        }

        $this->display();
    } */
    public function add ($id=0)
    {
        $this->html->formats = implode(' , ',get_formats('image',2));

      
    
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
