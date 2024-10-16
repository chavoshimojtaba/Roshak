<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user extends Controller{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_role');
        $res = $this->model_role->get_list_all();
        $data = [];
        if ($res->count > 0 ) {
            foreach ($res->result as $key => $value)
            {
                $data[$key]['selected'] = ($value['id'] === $_SESSION['rid'])?'selected':'';
                $data[$key]['id'] = $value['id'];
                $data[$key]['name'] = $value['name'];
            }
        }
        $this->html->roles = $data;
        $this->loop[] = 'roles';
    }

    public function index ()
    {

        $this->display();
    }
    public function profile ()
    {
        // //pr($_SESSION,true);
        $user = [
            'pic',
            'mobile',
            'address',
            'name',
            'family',
            'role',
            'full_name'
        ];
        foreach ($user as $key => $value) {
            $this->html->$value = $_SESSION[$value];
        }
        $this->html->email = decode_html_tag($_SESSION['username'],true);
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
