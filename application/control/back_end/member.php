<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class member extends Controller
{

    protected $block        = '';
    public $loop            = [];

    public function __construct()
    {
        parent::__construct();
    }

    public function index($change_type_request = 0)
    {
        $this->load->model('model_member');
        if ($change_type_request === 'change_type_request') {
            $data['show'] = 'd-none';
            $data['change_type_request'] = '';
        } else {
            $data = $this->model_member->get_statistics()->result[0];
            $data['show'] = '';
            $data['change_type_request'] = 'd-none';
            $data['total'] = $data['common'] + $data['designer'];
        }
        // pr($data,true);
        $this->html->set_data($data);
        $this->display();
    }

    public function coopration_requests()
    {
        $this->display();
    }

    public function settlement_requests()
    {
        $this->display();
    }

    public function view($id)
    {
        require_once DIR_HELPER . "helper_html.php"; 
        $this->load->model('model_member');
        $res = $this->model_member->member_detail($id); 
        if ($res->count > 0) {

            $member = decode_html_tag($res->result[0],true);
            $member['mid']         = $id;
            $member['id']          = $id;
            $member['reject_show'] = 'd-none';
            $member['show_notify'] = 'd-none';
            $member['createAt']    = g2p($member['createAt'], true);
            $member['birthdate_p'] = g2p($member['birthdate'], true);
            $member['status_inp']  = ($member['status'] == 'active') ? 'checked' : '';
            $member['type_fa']     = @constant('LANG_' . strtoupper($member['type']));
            $member['type_class']  = ($member['type'] === 'designer') ? 'primary' : 'info';
            $member_last_login = $this->model_member->member_last_login($id);
            if ($member_last_login->count > 0) {
                $member['last_login']   = g2pt($member_last_login->result[0]['last_login'], true);
            } else {
                $member['last_login']   = '-/-/- 00:00:00';
            } 
            if ($member['type'] === 'designer' || $member['change_type_request'] !== 'none') { 
                $designer = decode_html_tag($this->model_member->designer_detail($id)->result[0],true); 
                $exp = str_replace(['_','-'],['',','],$designer['expertise']); 
                $expertise =  $this->model_member->get_designer_expertise($exp)->result; 
                $options = ''; 
                foreach ($expertise as $item) { 
                    $options .= '<option selected value="' . $item['id'] . '"  >' . $item['title'] . '</option>';
                } 
                $designer['expertise_list']      = $options; 
                $this->load->model('model_file');
                $files = json_decode($designer['files'],true); 
                if (is_array($files) && count($files) > 0) {
                    $fileHtml = '';
                    foreach ($files as $key => $value) {    
                        $fileHtml .= ' <a type="button" target="_blank" class="btn btn-info d-flex me-2 align-items-center justify-content-center" href="' . HOST . $value . '" >
                            <i class="ti-save"></i>&nbsp;
                            دانلود
                        </a>'; 
                    }
                    $designer['attachment'] = $fileHtml;
                }
                $member = array_merge($member, $designer);
                if ($member['type'] === 'designer') {
                    $member['tab_title'] = 'اطلاعات طراح';
                    $member['statistics_sell'] = 0;
                    $this->load->model('model_financial');
                    $res = $this->model_financial->member_wallet($id);
                    $member['total_income'] = toman($res['total_income'],true);
                    $member['unsettled'] = toman($res['unsettled'],true);
                    $member['mounth_income'] = toman($res['mounth_income'],true);
                    $this->html->designer = [0 => $member];
                    $this->loop[] = 'designer';
                } else {
                    $member['statistics_downloads'] = $this->db->total_count('tp_order', 'tp_mid = ' . $id);
                    $member['tab_title'] = 'درخواست ارتقای کاربری';
                    if ($member['change_type_request'] == 'pend') {
                        $member['actions'] = changeMemberTypeActions();
                        $member['show_notify'] = '';
                    }
                    if ($member['change_type_request'] === 'reject') {
                        $member['actions'] = changeMemberTypeActions(true);
                        $member['reject_show'] = '';
                    }
                    $this->html->member = [0 => $member];
                    $this->loop[] = 'member';
                }
                $member['downgrade'] = '<div class="col-12 d-none">
                    <div class="mt-3 form-group"> 
                        <input type="checkbox" id="inp_downgrade" name="downgrade" class="form-control material-inputs filled-in chk-col-blue">
                        <label for="inp_downgrade">تنزل نوع کاربری</label>
                    </div>
                </div>';
                $member['show']  = ($member['show'] === 'no') ? 'checked' : '';
                $member['as_company']  = ($member['as_company'] === 'yes') ? 'checked' : '';
                $this->html->designer_tab = [0 => $member];
                $this->loop[] = 'designer_tab';
                $this->html->designer_tab_container = [0 => $member];
                $this->loop[] = 'designer_tab_container';
            } else {
                $member['statistics_downloads'] = $this->db->total_count('tp_order', 'tp_mid = ' . $id);
                $this->html->change_type_tab = [0 => $member];
                $this->html->change_type = [0 => $member];
                $this->loop[] = 'change_type';
                $this->loop[] = 'change_type_tab';
                $this->html->member = [0 => $member];
                $this->loop[] = 'member';
            }
            $data = [];
            $plan = plan($id);
            if ($plan['has_plan']) {
                $data = $plan;
                $data['endd_date'] = g2p($data['end_date']);
                $data['plan_name'] =  $data['title'];
                $data['startt_date'] = g2p($data['start_date']);
                $this->html->has_plan = [0 => $data];
                $this->loop[] = 'has_plan';
            } else {
                $this->html->no_plan = [0 => $data];
                $this->loop[] = 'no_plan';
            }
            $data['statistic_tickets'] = $this->db->total_count('tp_tickets', ' tp_mid = ' . $id . ' ');
            $this->html->set_data($data);
            $this->html->set_data($member);
            // pr($member,true);
        } else {
            header("location:" . HOST . 'admin/err');
            exit;
        }
        $this->display();
    }

    public function add_expertise ($id=0)
    {
        if($id > 0){
            $this->load->model('model_member');
            $res = $this->model_member->get_expertise_getail_slug(['id'=>$id]);
            if($res->count > 0){
                $data = decode_html_tag($res->result[0],true); 
            }
        }
        $data['formats'] = implode(' , ',get_formats('image',2));
        $this->html->set_data($data);
        $this->display();
    }
    
    public function expertise ($id=0)
    { 
        // pr($id,true);
        $this->display();
    }

    private function display()
    {
        out([
            'content' => $this->html->tab_links(
                [],
                min_template(
                    $this->html->get_string('array'),
                    $this->loop,
                    $this->router->method
                ),
                $this->router->method
            )
        ], 'admin');
    }
}
