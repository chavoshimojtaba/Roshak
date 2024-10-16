<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class about extends Controller
{
    public function index()
    {

        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);

		//// get faqs
        $this->load->model('model_pages');
		$res = $this->model_pages->about_us_policy(1);
		if($res->count > 0){
			$about_us = $res->result[0];
			foreach ($about_us as $key => $value) {
				$this->part_html[$key] 	 = decode_html_tag($value,true);
			}
		}
		$this->part_html['social_instagram'] = $_SESSION['site']['social_instagram'];
		$this->part_html['social_youtube'] = $_SESSION['site']['social_youtube'];
		$this->part_html['social_telegram'] = $_SESSION['site']['social_telegram'];
		$this->part_html['social_whatsapp'] = $_SESSION['site']['social_whatsapp'];
		$this->part_html['social_linkedin'] = $_SESSION['site']['social_linkedin'];
		$this->part_html['social_aparat'] = $_SESSION['site']['social_aparat'];
		$this->part_html['social_pinterest'] = $_SESSION['site']['social_pinterest'];

		/* -------------------------------------------------------------------------- */
		/*                                team members                                */
		/* -------------------------------------------------------------------------- */
		$res_team = $this->model_pages->get_team_members();
		if($res_team->count > 0){
			foreach ($res_team->result as $val) {
				$val['full_name'] 	 = decode_html_tag($val['fullname'],true);
				$this->template->assign($val);
				$this->template->parse('index.user');
			}
		}

		$this->page->set_data([
			'title' => $this->part_html['seo_title'],
			'desc' => $this->part_html['meta'],
			'breadcrump' => [LANG_ABOUT_US],
			'files'=>[
				['url'=>'file/client/css/style.css','type'=>'css'],
				['url'=>'file/client/js/owl.carousel.min.js','load'=>'','type'=>'js'],
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

