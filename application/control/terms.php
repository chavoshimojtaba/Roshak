<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class terms extends Controller
{
    public function index()
    {

        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);
        $this->load->model('model_pages');
		$res = $this->model_pages->faq_list('policy');
		if($res->count > 0){
			$active 		 = 'active';
			foreach ($res->result as $key => &$value) {
				$value['active_show'] 		 = 'active show';
				$value['active'] 		 = 'active';
				$value['collapsed'] 		 = '';
				$value['show'] 		 = 'show';
				if($key!=0){
					$value['show'] 		 = '';
					$value['collapsed'] 		 = 'collapsed';
					$value['active_show'] 		 = '';
					$value['active'] 		 = '';
				}
				$value['key'] 		 = $key;
				$value['desc'] 		 = decode_html_tag($value['desc'],true);
				$value['title'] 	 = decode_html_tag($value['title'],true);
				$this->template->assign($value);
				$this->template->parse('index.policy1');
				$this->template->parse('index.policy');
			}
		}

		$res_policy        = $this->model_pages->about_us_policy(2);
		$data               = [];
        if ($res_policy->count > 0)
        {
            $data               = $res_policy->result[0];
			$data['desc'] 		 = decode_html_tag($data['desc'],true);
			$data['title'] 	 = decode_html_tag($data['title'],true);
			$data['seo_title'] 	 = decode_html_tag($data['seo_title'],true);
			$this->template->assign($data);
        }

		$this->page->set_data([
			'title'=>$data['seo_title'],
			'desc'=>$data['meta'],
			'breadcrump'=>[LANG_POLICY],
			'files'=>[
				['url'=>'file/client/css/style.css','type'=>'css'],
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

