<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class home extends Controller{

    protected $block        = '';

    public function __construct()
    {
        parent::__construct();
    }

    public function index ()
    {
        



        $this->display ();
    }
    private function display ()
    {

        out([
            'content' => $this->html->tab_links(
                [],
                min_template(
                    $this->html->get_string('array'),
                    [],
                    $this->router->method
                    ),
                $this->router->method
                )
        ],'admin');
    }
}
