<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class profile extends Controller
{
    public function index()
    {
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $this->load->model('model_member');
        $res = $this->model_member->all_info($_SESSION['mid']); 
        $expertise =  $this->model_member->get_expertise_list(['limit'=>1000,'page'=>1])->result; 
        $cities =city_list();
        $categories = GLOBALS('category',false);
        
        if ($res->count > 0) {
            $member = decode_html_tag($res->result[0],true);
            if($member['change_type_request'] == 'none'){
                $this->template->parse('index.upgrade_btn');
            }
            if($member['change_type_request'] == 'pend'){
                $this->template->parse('index.pend');
            }

            if($member['type'] == 'designer'){

                foreach ($expertise as $value) { 
                    $value['selected'] =str_contains($member['expertise'],'_'. $value['id'].'_')?'selected':'';  
                    $this->template->assign($value);
                    $this->template->parse('index.designer_1.expertise');
                }
                $province = province_list();
                $member['cover']=strlen($member['cover'])>5?$member['cover']:'file/client/images/profile-bg.png';
                $this->template->assign($member);
                $this->template->parse('index.cover_change_perm');
                $this->template->assign($member);
                $this->template->parse('index.designer_1');
                $this->template->assign($member);
                $this->template->parse('index.designer_2');
                $this->template->assign($member);
                $this->template->parse('index.designer_3');
                $this->template->assign($member);
                if($member['password'] != 0){
                $this->template->parse('index.has_pass');

                }
                foreach ($province as $value) {
                    $value['selected'] = $value['pid'] == $member['province_id']?'selected':'';
                    $this->template->assign($value);
                    $this->template->parse('index.designer_4.province');
                }

                if($member['city_id']>0){
                    foreach ($cities as $key => $value) {
                        if($value['pid'] == $member['province_id']){
                            $value['selected'] = $value['id'] == $member['city_id']?'selected':'';
                            $this->template->assign($value);
                            $this->template->parse('index.designer_4.city');
                        }
                    }
                }

                $this->template->parse('index.designer_4');
                $this->template->assign($member);
                $this->template->parse('index.designer_5');
            }else{
                $member['cover']='file/client/images/profile-bg.png'; 
            }

            $level_2_categories = [];
            foreach ($categories as $key => $value) {
                if($value['pid'] == 0){
                    $level_2_categories[$value['id']] = $value['id'];
                }
            }
            foreach ($categories as $key => $value) {
                if(in_array($value['pid'],$level_2_categories)){
                    $value['selected'] =str_contains($member['favorite_categories'],'_'. $value['id'].'_')?'selected':'';
                    $this->template->assign($value);
                    $this->template->parse('index.categories');
                }
            }
            $member['cities'] = json_encode($cities);
            $this->template->assign($member);
        }

        $this->page->set_data([
            'title' => '  حساب کاربری',
            'desc' => '',
			'follow_index'=>'follow, noindex', 
            'breadcrump' => [LANG_PROFILE],
            'files' => [
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url' => 'file/global/dropzone/dropzone.css', 'type' => 'css'],
                ['url' => 'file/global/dropzone/dropzone.js', 'load' => '', 'type' => 'js'], 
                ['url' => 'file/global/cropper/cropper.css', 'type' => 'css'],
                ['url' => 'file/global/cropper/cropper.js', 'load' => '', 'type' => 'js'], 
            ]
        ]);

        $this->display();
    }
    public function upgrade_profile()
    {
        // pr($data,true);
        if(is_designer() || $_SESSION['change_type_request'] != 'none'){
            redirect(HOST.'dashboard/profile');
        }
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $this->load->model('model_member');
        $res       = $this->model_member->all_info($_SESSION['mid']); 
        $expertise =  $this->model_member->get_expertise_list(['limit'=>1000,'page'=>1])->result; 

        if ($res->count > 0) {
            $member = decode_html_tag($res->result[0],true);
            // //pr($member,true);
            if($member['type']  != 'designer'){
                foreach ($expertise as $value_exp) {
                    $value_exp['selected'] =str_contains($member['expertise'],'_'. $value_exp['id'].'_')?'selected':'';
                    $this->template->assign($value_exp);
                    $this->template->parse('upgrade_profile.expertise');
                }
                $cities =city_list();
                $province = province_list();
                $this->template->assign($member);
                foreach ($province as $value_pro) {
                    $value_pro['selected'] = $value_pro['pid'] == $member['province_id']?'selected':'';
                    $this->template->assign($value_pro);
                    $this->template->parse('upgrade_profile.province');
                }
                if($member['city_id']>0){
                    foreach ($cities as $value) {
                        if($value['pid'] == $member['province_id']){
                            $value['selected'] = $value['id'] == $member['city_id']?'selected':'';
                            $this->template->assign($value);
                            $this->template->parse('upgrade_profile.city');
                        }
                    }
                }
            }
            $member['cities'] = json_encode($cities);
            $this->template->assign($member);
        }
        $this->page->set_data([
            'title' => 'ارتقا حساب کاربری',
            'desc' => '',
			'follow_index'=>'follow, noindex', 
            'breadcrump' => [LANG_PROFILE],
            'files' => [
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url' => 'file/global/dropzone/dropzone.css', 'type' => 'css'],
                ['url' => 'file/global/dropzone/dropzone.js', 'load' => '', 'type' => 'js'], 
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
