<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class favorite extends Controller
{
    public function index()
    {
		$post = $this->router->request_get(); 
        $post['mid'] = $_SESSION['mid'];
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $categories = GLOBALS('category'); 
        foreach ($categories as $key => $value) {
            $this->template->assign($value);
            $this->template->parse('index.category');
        }
        require_once DIR_LIBRARY."ssr_grid.php";
        $GridData = new SSR_Grid(array_merge([
            'limit'  => '10', 
            'type'  => 'favorite',
        ],$post));
        $part_html = $GridData->getData(); 
        $part_html['ssr_grid'] = $GridData->html();
        $this->page->set_data([
            'title'=>LANG_FAVORITES,
			'follow_index'=>'follow, noindex', 
            'desc'=>LANG_FAVORITES,
            'breadcrump'=>[LANG_FAVORITES],
            'files'=>[
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url'=>'file/client/css/select2.min.css','type'=>'css'],
                ['url'=>'file/client/js/select2.min.js','load'=>'','type'=>'js'],
            ]
        ]);
        $this->template->assign($part_html);

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

