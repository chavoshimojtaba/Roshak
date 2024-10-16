<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}

class member
{
    private $app;
    private $alldata = [];
    public function init($app)
    {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_member');
    }

    function change_password($param, $get)
    {
        if(isset($get['old_password'])){
            $get['old_password'] = hash_password($get['old_password']);
            if ($get['old_password'] != $get['new_password'] && $this->app->model_member->passValidation($get['old_password'])) {
                if ($get['password'] === $get['re_password']) {
                    $get['new_password'] = hash_password($get['password']);
                    $get['mid'] = $_SESSION['mid'];
                    $res = $this->app->model_member->change_password($get);
                    if ($res->affected_rows > 0) {
                        $this->alldata['status']  = 1;
                        $this->alldata['data']  = 'Done';
                    }
                } else {
                    $this->alldata['data']  = LANG_PASSWORDS_DOSENT_MATCH;
                }
            } else {
                $this->alldata['data']  = 'رمز عبور فعلی صحیح نمیباشد.';
            }
        }else{
            if ($get['password'] === $get['re_password']) {
                $get['new_password'] = hash_password($get['password']);
                $get['mid'] = $_SESSION['mid'];
                $res = $this->app->model_member->change_password($get);
                if ($res->affected_rows > 0) {
                    $this->alldata['status']  = 1;
                    $this->alldata['data']  = 'Done';
                }
            } else {
                $this->alldata['data']  = LANG_PASSWORDS_DOSENT_MATCH;
            }
        }
        $this->app->_response(200, $this->alldata);
    }

    function update_profile($param, $get)
    {
        $member = ['favorite_categories' => $get['favorite_categories'], 'email' => $get['email'], 'name' => $get['name'], 'family' => $get['family']];
        if (isset($get['family_en'])) {
            $member['name_en'] =   $get['name_en'];
            $member['family_en'] =   $get['family_en'];
        }
        $res = $this->app->model_member->update_profile($_SESSION['mid'], $member); 
        if (isset($get['expertise'])) {
            $designer = ['expertise' => $get['expertise'], 'bank' => $get['bank'], 'card_number' => $get['card_number'], 'province' => $get['province'], 'address' => $get['address'], 'bio' => $get['bio'], 'national_code' => $get['national_code'], 'sheba' => $get['sheba'], 'city' => $get['city'], 'meta' => $get['meta'], 'title' => $get['title']
            , 'website' => $get['website']
            , 'social_youtube' => $get['social_youtube']
            , 'social_telegram' => $get['social_telegram']
            , 'social_whatsapp' => $get['social_whatsapp']
            , 'social_bale' => $get['social_bale']
            , 'social_ita' => $get['social_ita']
            , 'social_roubika' => $get['social_roubika']
            , 'social_pinterest' => $get['social_pinterest']
            , 'social_dribble' => $get['social_dribble']
            , 'social_instagram' => $get['social_instagram']
            ];
            $res = $this->app->model_member->update_profile_designer($_SESSION['mid'], $designer);
        }
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1; 
            $_SESSION['name'] = $get['name'];
            $_SESSION['family'] = $get['family'];
            $_SESSION['full_name'] = $get['name'].' '.$get['family']; 
            $this->alldata['data'] = $res->affected_rows;
        }

        $this->app->_response(200, $this->alldata);
    }
    
    function avatar($param, $get)
    {
        $data = $get['avatar'];
        $avatar =  base64ToImage($data,'image/member/'.time().'.jpg'); 
        $res = $this->app->model_member->update_profile($_SESSION['mid'], ['pic'=>$avatar]); 
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $_SESSION['pic'] = $avatar;
            $this->alldata['data'] = $avatar;
        }

        $this->app->_response(200, $this->alldata);
    }
    
    function cover($param, $get)
    {
        $data = $get['cover'];
        $cover =  base64ToImage($data,'image/member/'.time().'.jpg');  
        $res = $this->app->model_member->update_profile($_SESSION['mid'], ['cover'=>$cover]); 
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $_SESSION['pic'] = $cover;
            $this->alldata['data'] = $cover;
        }

        $this->app->_response(200, $this->alldata);
    }

    function upgrade_profile($param, $get)
    { 
        
        $get['slug'] = str_replace(' ', '', $get['name_en']).'-'.str_replace(' ', '', $get['family_en']);
        $get['slug'] = strtolower($get['slug']);
        if($this->app->model_member->designer_by_slug($get['slug'])->count>0){
            $get['slug'] = $get['slug'].'-'.generateString();
        }
        $res = $this->app->model_member->add_profile_designer($_SESSION['mid'], $get);
        if ($res > 0) {
            $res = $this->app->model_member->set_pend_type($_SESSION['mid']);
            $_SESSION['change_type_request'] = 'pend';
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }

        $this->app->_response(200, $this->alldata);
    }

    function designers($param, $get)
    {
        require_once DIR_LIBRARY . "ssr_grid.php";
        $GridData = new SSR_Grid(array_merge(['type'  => 'designer'], $get));
        $res = $GridData->json();
        if ($res['count'] > 0) {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200, $this->alldata);
    }

    function favorites($param, $get)
    {
        $get['mid'] = 1;
        $res = $this->app->model_member->favorite_products($get);
        if ($res->count > 0) {
            foreach ($res->result as $key => &$value) {
                $value['createAt']  = g2p($value['createAt']);
                $value['product_name'] = decode_html_tag($value['product_name'], true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['count']   = $res->count;
            $this->alldata['total']   = $res->total;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200, $this->alldata);
    }

    function notification($param, $get)
    {
        require_once DIR_LIBRARY . "ssr_grid.php";
        $GridData = new SSR_Grid(array_merge(['type'  => 'notification'], $get));
        $res = $GridData->json();
        if ($res['count'] > 0) {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200, $this->alldata);
    }

    function comment($param, $get)
    {
        require_once DIR_LIBRARY . "ssr_grid.php";
        $GridData = new SSR_Grid(array_merge(['type'  => 'comment'], $get));
        $res = $GridData->json();
        if ($res['count'] > 0) {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200, $this->alldata);
    }

    function ticket($param, $get)
    {
        require_once DIR_LIBRARY . "ssr_grid.php";
        $GridData = new SSR_Grid(array_merge(['type'  => 'ticket'], $get));
        $res = $GridData->json();
        if ($res['count'] > 0) {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200, $this->alldata);
    }

    function follow($param, $get)
    {
        $this->app->load->model('model_member');
        if (is_login()) {
            $res = $this->app->model_member->follow($_SESSION['mid'], $get['id']);
            if ($res) {
                $this->alldata['status'] = 1;
                $this->alldata['data'] = $res;
            }
        } else {
            $this->alldata['status'] = '-1';
            $this->alldata['data'] = 'Not Logged In';
        }
        $this->app->_response(200, $this->alldata);
    }

    function add_to_favorites($param, $get)
    { 
        $this->app->load->model('model_member');
        if (is_login()) {
            $res = $this->app->model_member->add_to_favorites($get['id']);
            if ($res) {
                $this->alldata['status'] = 1;
                $this->alldata['data'] = $res;
            }
            $this->app->_response(200, $this->alldata);
        } else { 
            $this->app->_response(401, $this->alldata);
        }
    }
    function remove_favorite($param, $get)
    {
        $this->app->load->model('model_member'); 
        if (is_login()) {
            $res = $this->app->model_member->remove_favorite($param[0]); 
            if ($res) {
                $this->alldata['status'] = 1;
                $this->alldata['data'] = $res;
            }
            $this->app->_response(200, $this->alldata);
        } else { 
            $this->app->_response(401, $this->alldata);
        }
    }
    function favorite($param,$get)
    { 
        require_once DIR_LIBRARY."ssr_grid.php"; 
        $GridData = new SSR_Grid(array_merge(['type'  => 'favorite'],$get));
        $res = $GridData->json();
		if ($res['count'] > 0)
        {
            $this->alldata   = $res;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function rating($param, $get)
    {
        $this->app->load->model('model_member');
        $res = $this->app->model_member->rating($get['id'], $get['rate']);
        if ($res) { 
            $this->app->load->model('model_product');
            $rate = $this->app->model_product->get_rate($get['id']);  
            $rate['text'] = $rate['rate'].'/5 -('.$rate['cnt'].' کاربر)';
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $rate;
        } 
        $this->app->_response(200, $this->alldata);
    }
}
