<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class plan extends Controller
{
    public function index()
    {
        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);
        $post = $this->router->request_get();
        require_once DIR_LIBRARY."ssr_grid.php";
        $GridData = new SSR_Grid(array_merge([
            'limit'  => '10',
            'type'  => 'plan',
        ],$post));
        $data = $GridData->getData();
        $data['ssr_grid'] = $GridData->html();
        $plan = decode_html_tag(plan($_SESSION['mid']),true); 
        if($plan['has_plan']){
            $data['plan'] = $plan['title'];
            $data['complete_text'] = $plan['download_perm']?'':'<span class="text-danger">تکمیل</span>';
            $data['alert_danger'] = $plan['left_days']<=10?'text-danger':'';
            $data['left_days'] = $plan['left_days'];
            $data['today_downloads'] = $plan['today_downloads'];
            $this->template->assign($data);
            $this->template->parse('index.has_plan');
        }else{
            $this->template->parse('index.by_plan');
            $this->template->parse('index.no_plan');
            $this->template->assign($data);
        }
        $this->page->set_data([
            'title'=>LANG_PLANS,
            'desc'=>LANG_PLANS,
			'follow_index'=>'follow, noindex', 
            'breadcrump'=>[LANG_PLANS],
            'files'=>[
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.style.css','type'=>'css'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.js','load'=>'defer','type'=>'js'],]
        ]);
        $this->template->assign($data);

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

