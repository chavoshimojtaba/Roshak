<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*  error_reporting(E_ALL);
 ini_set('display_errors', '1');  */
class pages extends Controller{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index ($change_type_request=0)
    {

        $this->html->unread_count = $this->db->total_count('tp_pages_contact_us','tp_read = "no"'); 
        $this->display();
    }
    
    public function add_faq ()
    {
        $this->display();
    }
    public function add_policy ()
    {
        $this->display();
    }
    public function faq ()
    {
        
        $this->load->model('model_pages');
        $res = $this->model_pages->faq_list();

        if ($res->count > 0)
        {
            foreach ($res->result as  &$value) {
                $value['desc'] = decode_html_tag($value['desc'],true);
                $value['title'] = decode_html_tag($value['title'],true);
            }
            $this->html->faqs = $res->result;

            $this->loop[] = 'faqs';
        } 
        $res        = $this->model_pages->about_us_policy(21);
        // //pr($res,true);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function team_members ()
    {
        $this->display();
    }

    public function add_team_members ($id='')
    {
        if($id > 0){
            $this->load->model('model_pages');
            $res = $this->model_pages->get_team_members($id);
            if ($res->count > 0)
            {
                $data = $res->result[0];
                $data['fullname']  = decode_html_tag($data['fullname'],true);
                $data['expert']  = decode_html_tag($data['expert'],true);
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

    public function member_messages ()
    {
        $this->display();
    }

    public function view ($id)
    {
        $this->load->model('model_pages');
        $res = $this->model_pages->get_pages_detail($id);
        if ($res->count > 0)
        {
            $data = $res->result[0];
            $data['short_desc'] = decode_html_tag($data['short_desc'],true);
            $data['user_email'] = decode_html_tag($data['user_email'],true);
            $data['createAt'] = g2pt($data['createAt']);
            $data['desc'] = decode_html_tag($data['desc'],true);
            $this->html->set_data($data);
            $tags_json = json_decode($data['tags']);
            $tags = [];
            foreach ($tags_json as $key => $value)
            {
                $tags[$key]['title']=$value;
            }
            $this->html->tags = $tags;
            $this->loop[] = 'tags';
        }
        $this->html->id = $id;
        $this->display();
    }

    public function about_us ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(1);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function search_page ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(4);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function image ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(6);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function video ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(7);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function stock ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(8);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function vector ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(9);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function font ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(10);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function mockup ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(11);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }
    public function designers_page ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(5);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }

    public function plan ()
    {
        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(3);
        // //pr($res,true);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $this->display();
    }

    public function policy ()
    {

        $this->load->model('model_pages');
        $res        = $this->model_pages->about_us_policy(2);
        if ($res->count > 0)
        {
            $data               = $res->result[0];
            foreach ($data as $key => $value)
            {
				$this->html->$key = decode_html_tag($value,true);
            }
        }
        $res = $this->model_pages->faq_list('policy');

        if ($res->count > 0)
        {
            foreach ($res->result as  &$value) {
                $value['desc'] = decode_html_tag($value['desc'],true);
                $value['title'] = decode_html_tag($value['title'],true);
            }
            $this->html->faqs = $res->result;

            $this->loop[] = 'faqs';
        }
        $this->display();
    }

    public function add ($id=0)
    {
        if($id > 0){
            $this->load->model('model_pages');
            $res = $this->model_pages->get_page(['id'=>$id]);
            if($res->count > 0){
                $data = $res->result[0];
                $data['title']  = decode_html_tag($data['title'],true);
                $data['slug']   = decode_html_tag($data['slug'],true);
                $data['meta']   = decode_html_tag($data['meta'],true);
                $data['desc']   = decode_html_tag($data['desc'],true);
            }
        }
        $data['formats'] = implode(' , ',get_formats('image',2));
        $this->html->set_data($data);
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
