<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class comment extends Controller{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function blog ()
    {
        $this->load->model('model_comment');
        $this->model_comment->_table = "`tp_blog_comments`";
        $this->model_comment->_tableType =  'blog' ;
        $data = $this->model_comment->get_statistics()->result[0];
        $data['total']    = $data['approved'] + $data['reject'] + $data['pend'];
        $this->html->set_data($data);

        $this->display();
    }
    public function view ($type,$id)
    {
        $this->display();
    }

    public function product ()
    {

        $this->load->model('model_comment');
        $this->model_comment->_table = "`tp_product_comments`";
        $this->model_comment->_tableType =  'product' ;
        $data = $this->model_comment->get_statistics()->result[0];
        $data['total']    = $data['approved'] + $data['reject'] + $data['pend'];
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
