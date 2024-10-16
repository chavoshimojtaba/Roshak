<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class notification extends Controller
{

	public function index()
    {
        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);

        require_once DIR_LIBRARY."ssr_grid.php";
        $this->load->model('model_notifications');
         $this->model_notifications->set_read_all($_SESSION['mid']);
        $GridData = new SSR_Grid([
            'limit'  => '10',
            'type'  => 'notification',
        ]);
        $part_html = $GridData->getData();
        $part_html['ssr_grid'] = $GridData->html();
		$this->template->assign($part_html);
        $this->page->set_data([
            'title'=>LANG_NOTIFICATIONS,
			'follow_index'=>'follow, noindex',
            'desc'=>LANG_NOTIFICATIONS,
            'breadcrump'=>[LANG_NOTIFICATIONS],
            'files'=>[
				['url'=>'file/client/css/profile.css','type'=>'css'],
                /* ['url'=>'assets/css/splide.min.css','type'=>'css'],
                ['url'=>'file/site/js/splide.min.js','load'=>'defer','type'=>'js'], */
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

