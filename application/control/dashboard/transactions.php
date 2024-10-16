<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class transactions extends Controller
{
    public $block = 'index';

    public function _index($id = 0)
    {
        if(!is_designer()){
            error_404($this);
        }
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $this->load->model('model_financial');

        require_once DIR_LIBRARY . "ssr_grid.php";
        $GridData = new SSR_Grid([
            'limit'  => '10',
            'type'  => 'settlement_requests',
        ]);
        $data = $GridData->getData();
        $data['ssr_grid'] = $GridData->html();
        $res = $this->model_financial->member_wallet($_SESSION['mid']);
        $data['total_income'] = 0;
        $data['unsettled'] = 0;
        $data['mounth_income'] = 0;
        if ($res['cnt'] > 0) {
            $data['total_income'] = toman($res['total_income']);
            $data['unsettled'] = toman($res['unsettled']);
            $data['mounth_income'] = toman($res['unsettled']);
        }
        // pr($data,true);
        $this->load->model('model_financial');
        $res_req = $this->model_financial->has_settlement_request($_SESSION['mid']);
        if(!$res_req && $data['unsettled'] > 50000){
            $this->template->parse('index.new_request');
            $this->template->parse('index.new_request_1');
        }else{
            $this->template->parse('index.limit_request'); 
        }
        $this->template->assign($data);
        $this->page->set_data([
			'follow_index'=>'follow, noindex', 
            'title' => LANG_SETTLEMENT_REQUESTS,
            'desc' => LANG_SETTLEMENT_REQUESTS,
            'breadcrump' => [LANG_SETTLEMENT_REQUESTS],
            'files' => [
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.style.css','type'=>'css'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.js','load'=>'defer','type'=>'js'],
                ['url'=>'file/client/js/owl.carousel.min.js','load'=>'','type'=>'js'],

                ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'], 
                ]
        ]);
        $this->template->assign($data);
        $this->display();
    }

    public function display()
    {
        $this->template->parse($this->block);
        out([
            'content' => $this->template->text($this->block)
        ]);
    }
}
