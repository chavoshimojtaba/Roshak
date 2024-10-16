<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class faq extends Controller
{
    public function index()
    {

        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);
		//// get faqs
        $this->load->model('model_pages');
		$res = $this->model_pages->faq_list('faq');
		$questions = [];
		if($res->count > 0){
			foreach ($res->result as $key => &$value) {
				$value['show'] 		 = 'show';
				if($key!=0){
					$value['collapsed'] 		 = 'collapsed';
					$value['show'] 		 = '';
				}
				$value['desc'] 		 = decode_html_tag($value['desc'],true);
				$value['title'] 	 = decode_html_tag($value['title'],true);
				$questions[] = '
				{
					"@type": "Question",
					"name": "'.$value['title'].'",
					"acceptedAnswer": {
					"@type": "Answer",
					"text": "'.$value['desc'].'"
					}
				}
				';
				$this->template->assign($value);
				$this->template->parse('index.faq');
			}
		}



		$res_policy        = $this->model_pages->about_us_policy(21);
		$data               = [];
        if ($res_policy->count > 0)
        {
            $data               = decode_html_tag($res_policy->result[0],true); 
			$this->template->assign($data);
        }

		$this->page->set_data([
			'title'=>$data['seo_title'],
			'desc'=>$data['meta'],
			'breadcrump'=>[LANG_FAQ],
			'more'=>'
			<script type="application/ld+json">
				{
					"@context": "https://schema.org",
					"@type": "FAQPage",
					"mainEntity": [
						'.implode(',',$questions).'
					]
				}
			</script>
			',
			'files'=>[
				['url'=>'file/client/css/style.css','type'=>'css'],
			]
		]);
  
		$part_html['breadcrump'] = $this->page->get_breadCrump();
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

