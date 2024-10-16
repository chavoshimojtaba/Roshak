<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*  error_reporting(E_ALL);
 ini_set('display_errors', '1');  */
class ticket extends Controller{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        $this->load->model('model_tickets');
        $res = $this->model_tickets->get_statistics();
        $total = 0;
        $closed = 0;
        $open = 0;
        if ($res['total'] > 0 ) {  
            $total = $res['total'];
            $open = $res['open'];
            $closed = $total - $open;
        }

        $this->html->statistics_total = $total;
        $this->html->statistics_open = $open;
        $this->html->statistics_closed = $closed;

        $this->display();
    }

    public function view ($id)
    { 
        $this->html->id = $id;
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
