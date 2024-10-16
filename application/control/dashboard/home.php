<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once DIR_LIBRARY . "ssr_grid.php";
class home extends Controller
{
    public function index()
    {

        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $last30days = array();
        $this->load->model('model_report');
        $data = [];
        if ($_SESSION['change_type_request'] == 'none') {
            $this->template->parse('index.upgrade_btn');
        }
        $plan = plan($_SESSION['mid']);
        $data['today_downloads'] = $plan['today_downloads'];
        if ($plan['has_plan']) {
            $data['plan'] = $plan['title'];
            $data['left_days'] = $plan['left_days'];
            $this->template->assign($data);
            $this->template->parse('index.has_plan');
            $data['avount_type'] = 'اشتراکی';
        } else {
            $data['left_days'] = 0;
            $data['avount_type'] = 'عادی';
        }

        $this->load->model('model_order');
        $statistics = $this->model_order->member_orders_statistics($_SESSION['mid']);
        $this->load->model('model_member');
        $res = $this->model_member->member_following($_SESSION['mid']);
        $mids = [0];
        if ($res->count > 0) {
            $mids = [];
            foreach ($res->result as $key => $value) {
                $mids[] = $value['did'];
            }
        }
        $GridData1 = new SSR_Grid([
            'template'  => 'search',
            'limit'  => '3',
            'designer_show_all'  => true,
            'type'  => 'product',
            'mids' => implode(',', $mids)
        ]);
        $data['ssr_grid_followers'] = $GridData1->html();
        $data['total_downloads'] = $statistics['total_downloads'];
        $data['total_tickets'] = $this->db->total_count('tp_tickets', 'tp_mid =' . $_SESSION['mid'] . ' AND tp_delete = 0');
        if (is_designer()) {
            $sell_statistics = $this->model_report->designer_sell($_SESSION['mid'], ['days' => 30]);
            $data['total_download_chart'] = $this->model_report->designer_downloads($_SESSION['mid']);
            if ($sell_statistics->count > 0) {
                foreach ($sell_statistics->result as $key => $value) {
                    $last30days[] = ['g' => $value['date'], 'p' => g2p($value['date']), 'total_price' => $value['total_price']];
                }
            }
            $designer = $this->model_member->designer($_SESSION['mid'])->result[0]; 
            $member_credit = member_credit($_SESSION['mid']);
            @$data['statistic_follower'] = $designer['statistic_follower'];
            $data['mounth_income'] = toman($member_credit['mounth_income']);
            $data['unsettled'] = toman($member_credit['unsettled']);
            $data['total_sell'] = $statistics['total_sell'];
            @$data['statistic_product'] = $designer['statistic_product'];
            $GridData_comments = new SSR_Grid([
                'limit'  => '3',
                'type'  => 'comment',
            ]);
            $this->template->assign($data);
            if ($designer['show'] !== 'yes') { 
                $this->template->parse('index.hidden_designer');
            }
            if ($GridData_comments->getData()['count'] > 0) {
                $data['ssr_grid_comment'] = $GridData_comments->html();
                $this->template->assign($data);
                $this->template->parse('index.designer_comments');
            }
            $this->template->parse('index.designer');
            $this->template->assign($data);
            $this->template->parse('index.designer_1');
        }

        $GridData = new SSR_Grid([
            'limit'  => '4',
            'designer_show_all'  => true,
            'type'  => 'product',
            'favorite' => true
        ]);
        $data['ssr_grid_favorite'] = $GridData->html();
        $data['days'] = json_encode($last30days);
        $this->template->assign($data);
        $this->page->set_data([
            'title' => LANG_DASHBOARD,
            'desc' => LANG_DASHBOARD,
            'breadcrump' => [LANG_DASHBOARD],
            'files' => [
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'],
                ['url' => 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js', 'load' => '', 'type' => 'js'],
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
