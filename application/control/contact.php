<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(DIR_HELPER . 'helper_setting.php');
class contact extends Controller
{
    public function index()
    {
        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);
		$site = siteInfo(true);
		$this->part_html=$site;


		//// get faqs
        $this->load->model('model_pages');
		$res = $this->model_pages->about_us_policy(2);
		$about_us = [];
		if($res->count > 0){
			$about_us = $res->result[0];
			// pr($about_us,true);
			foreach ($about_us as $key => $value) {
				$this->part_html[$key] 	 = decode_html_tag($value,true);
			}
		}
		foreach ($site as $key => $value) {
			if(strpos($value,'social_') >= 0){
				$this->template->parse('index.subject');
			}
		}
		// if($){}
		$subject = values('contact_us_subject',false,1);

		foreach ($subject as $key => $value) {
			$this->template->assign($value);
			$this->template->parse('index.subject');
		}

		// pr(11,true);
		$this->page->set_data([
			'title'=>$about_us['seo_title'],
			'desc'=>$about_us['meta'],
			'breadcrump'=>[LANG_CONTACT_US],
			'files'=>[
				['url'=>'file/client/css/style.css','type'=>'css'],
				['url'=>'file/client/js/map.min.js','load'=>'','type'=>'js'],
			]
		]);
		$this->part_html['breadcrump'] = $this->page->get_breadCrump();
		$this->template->assign($this->part_html);
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

