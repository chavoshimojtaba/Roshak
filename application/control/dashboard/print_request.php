<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class print_request extends Controller
{
    public function index()
    {
        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);
        $this->page->set_data([ 
            'breadcrump'=>[LANG_PLANS],
            'files'=>[
				['url'=>'file/client/css/profile.css','type'=>'css']
            ]
        ]);
        $this->display();
    }

	public function display()
	{
		$this->template->parse($this->router->method);
		out([
            'content' => $this->template->text($this->router->method)
        ]);
	}
}

