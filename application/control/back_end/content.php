<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//new
class content extends Controller{

    protected $block        = '';
    public $loop            = []; 

    public function __construct()
    {
        parent::__construct();
    } 

    public function loc ()
    {
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
