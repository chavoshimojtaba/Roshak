<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
require_once(DIR_LIBRARY . "JwtMiddleWare.php");
class auth
{

    private $app;
    private $alldata = [];
    public function init($app)
    {
        $this->alldata['status'] = 0;
        $this->alldata['msg'] = 0;
        $this->app = $app;
        $this->app->load->model('model_member');
    }

    function login($param, $get)
    {
        $mobile = validate('mobile', $get['mobile']);
        if ($mobile) {
            $res = $this->app->model_member->get_member($get); 
            if ($res->count == 0) {
                $id = $this->app->model_member->register_by_mobile($mobile)->insert_id;
                $status = 'incomplete';
                $this->alldata['has_pass'] = 0;
            } else if ($res->result[0]['status'] == 'deactive') {
                $status = 'deactive';
                $this->alldata['msg'] = LANG_DEACTIVE_MEMBER_MESSAGE;
                $this->alldata['has_pass'] = 0;
                $id =  0;
            } else if ($res->result[0]['complete'] == 'pend') {
                $status = 'incomplete';
                $this->alldata['has_pass'] = 0;
                $id =  $res->result[0]['id'];
            } else {
                $status = $res->result[0]['status'];
                $this->alldata['has_pass'] = ($res->result[0]['password'] > 0) ? 1 : 0;
                $id =  $res->result[0]['id'];
            }
            $code = generateAuthCode($id, $status);
            send_message($mobile, [
                'CODE' => $code
            ], 'verify_code'); 
            
            $this->alldata['data'] = $status;
            $this->alldata['status'] = 1;
        } else {
            $this->alldata['msg'] = LANG_INVALID_MOBILE;
        }
        $this->app->_response(200, $this->alldata);
    }

    function resend_code($param, $get)
    {
        $mobile = validate('mobile', $get['mobile']);
        if ($mobile) {
            $res = $this->app->model_member->get_member($get);
            if ($res->count > 0) {
                $status = $res->result[0]['status'];
                $id     =  $res->result[0]['id'];
                $code   = generateAuthCode($id);
                send_message($mobile, [
                    'VerificationCode' => $code
                ], 'send_password');
                $this->alldata['data'] = $status;
                $this->alldata['status'] = 1;
            } else {
                $this->alldata['msg'] = LANG_INVALID_MOBILE;
            }
        } else {
            $this->alldata['msg'] = LANG_INVALID_MOBILE;
        }
        $this->app->_response(200, $this->alldata);
    }

    function verify($param, $get)
    {

       /*  $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LeYdG0pAAAAAC94rZJ5F94Ogcg_4DdsVZYaSaSc', 'response' => $get['g-recaptcha-response']); 
        $options = array(
            'http' => array(    
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context); */
        // $responseKeys = json_decode($response, true); 
        if(true){ 
            $this->app->load->model('model_notifications'); 
            $get['designer'] = ($get['designer'] == 'on') ? 'pend' : 'none';
            $res = $this->app->model_notifications->verify_authentication_code($get['code']);
            if ($res->count > 0) {
                $code_res = $res->result[0];
                // //pr($code_res,true);
                $this->app->load->model('model_member');
                if ($code_res['complete'] == 'pend') {
                    $this->app->model_member->update_incomplete_member(array_merge($get, $code_res));
                }
                $member_res = $this->app->model_member->member_detail($code_res['mid']);
                if ($res->count > 0) {
                    $member = $member_res->result[0];
                    $member['mid'] = $member['id'];
                    if ($member['type'] == 'designer') {
                        $mid = $member['id'];
                        // pr($mid,true);
                        $member = array_merge($member, $this->app->model_member->designer_detail($member['id'])->result[0]);
                        $member['mid']=$mid;
                    } 
                    $jwt   =   new JwtMiddleWare();
                    $member['full_name'] = $member['name'] . ' ' . $member['family'];
                    $token = $jwt->createToken($member['id'], $member['full_name'], 2); 
                    unset($member['id']);
                    $member['member'] = true;
                    $member['agent']  = $this->app->router->agent();
                    $member['ip']     = $this->app->router->ip();
                    $this->app->session->_set_login($member);
                    unset($member['uid']);
                    $this->alldata['data'] = ['token' => $token, 'f' => $member['full_name'], 'p' => $member['pic'], 't' => $member['type']];
                    $this->alldata['status'] = 1;
                } else {
                    $this->alldata['msg'] = LANG_INVALID_VERIFICATION_CODE;
                }
            } else {
                $this->alldata['msg'] = LANG_INVALID_VERIFICATION_CODE;
            }
        } else {
            $this->alldata['msg'] = 'ip شما به عنوان ربات تشخیص داده شد.لطفا لحظاتی دیگر مجدد سعی کنید.';
        }
        $this->app->_response(200, $this->alldata);
    }
    function password($param, $get)
    {
        $get['password'] = hash_password($get['password']);

        $this->app->load->model('model_member');
        $res = $this->app->model_member->passValidation($get['password'], $get['mobile']);
        if ($res->count > 0) {
            $member_res = $this->app->model_member->member_detail($res->result[0]['id']);
            if ($member_res->count > 0) {
                $member = $member_res->result[0];
                if ($member['type'] == 'designer') {
                        $mid = $member['id'];
                        $member = array_merge($member, $this->app->model_member->designer_detail($member['id'])->result[0]);
                        $member['id']=$mid;
                }
                $jwt   =   new JwtMiddleWare(); 
                $member['full_name'] = $member['name'] . ' ' . $member['family'];
                $token = $jwt->createToken($member['id'], $member['full_name'], 2);
                $member['mid'] = $member['id']; 
                $member['member'] = true;
                $member['agent']  = $this->app->router->agent();
                $member['ip']     = $this->app->router->ip();
                $this->app->session->_set_login($member);
                unset($member['uid']);
                $this->alldata['data'] = ['token' => $token, 'f' => $member['full_name'], 'p' => $member['pic'], 't' => $member['type']];
                $this->alldata['status'] = 1;
            } else {
                $this->alldata['msg'] = LANG_INVALID_MOBILE_OR_PASS;
            }
        } else {
            $this->alldata['msg'] = LANG_INVALID_MOBILE_OR_PASS;
        }
        $this->app->_response(200, $this->alldata);
    }
}
