<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
require_once(DIR_LIBRARY."JwtMiddleWare.php");
require_once(DIR_LIBRARY."Message.php");
class auth {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_users');
	}


    function login($param,$get)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array('secret' => '6LeYdG0pAAAAAC94rZJ5F94Ogcg_4DdsVZYaSaSc', 'response' => $get['g-recaptcha-response']);

        $options = array(
            'http' => array(    
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $responseKeys = json_decode($response, true); 
        if($responseKeys['success']){ 
            $res = $this->app->model_users->fetch_user_by_username($get['username']);
            if ( $res->count > 0 )
            {
                $user = $res->result[0];
                if($user['first_login'] == 'yes'){
                    $code = generateAuthCode($user['uid'],0,'user');
                    // pr($user,true);
                    send_message($user['mobile'],[
                        'CODE'=>$code
                    ],'verify_code');
                    
                    $this->alldata['data'] = 'need to active';
                    $this->alldata['status'] = '3';
                }else if($user['active_status'] == 'active'){
                    $get['password'] = decode_html_tag($get['password'],true);
                    if ( $res->result[0]['password'] == hash_password($get['password']))
                    {
                        $this->app->load->model('model_role');
                        $permissions=[];
                        $resources=[];
                        $res_permissions = $this->app->model_role->user_permissions($user['rid']);
                        $resource_list = $this->app->model_role->resource_list();
                        if ($res_permissions->count > 0 ) {
                            foreach ($res_permissions->result as  $value)
                            {
                                $permissions[$value['resource_title']][] = $value['perm'];
                                $permissions[$value['reid']][] = $value['perm'];
                            }
                        }
                        if ($resource_list->count > 0 ) {
                            foreach ($resource_list->result as  $value)
                            {
                                $resources[$value['title']] = $value['id'];
                            }
                        }
                        $jwt   =   new JwtMiddleWare();
                        $token = $jwt->createToken($user['uid'],$user['full_name'],1);
                        $user['agent']       = $this->app->router->agent();
                        $user['ip']          = $this->app->router->ip();
                        $user['resources']   = $resources;
                        $user['permissions'] = $permissions;
                        $this->app->session->_set_login($user);
                        $this->alldata['data'] = $token;
                        $this->alldata['status'] = 1;
                    }
                    else
                    {
                        $this->alldata['data']  = LANG_WRONG_USERNAME_OR_PASSWORD;
                    }
                }else{
                    $this->alldata['data']  = LANG_YOUR_ACCOUNT_DEACTIVATED;
                }
            }
            else
            {
                $this->alldata['data']  = LANG_WRONG_USERNAME_OR_PASSWORD;
            }
        }else{
            $this->alldata['data']  = implode(' - ',$responseKeys['error-codes']);
        }
        $this->app->_response(200,$this->alldata);
    }

    function forgot_pass($param,$get)
    {
        $res = $this->app->model_users->fetch_user_by_username($get['username']);
        if ( $res->count > 0 )
        {
            $get['uid']   = $res->result[0]['uid'];
            $get['token'] = generatePublicToken();
            $res_insert   = $this->app->model_users->forgot_pass_token($get);
            if ( $res_insert->insert_id > 0 ){
                // $Message   =   new Message('sendForgotPassword',[$email]);///email
                $Message   =   new Message('changePasswordToken',['09121122408']); ///sms
                $Message->send([
                    'code'=>$get['token']
                ]);
                $this->alldata['status']  = 1;
            }
        }
        $this->app->_response(200,$this->alldata);
    }

    function verify($param,$get)
    {

        $this->app->load->model('model_notifications');
        $res = $this->app->model_notifications->verify_authentication_code($get['code'],'user');
        if ( $res->count > 0 )
        {
            $code_res = $res->result[0];

            $user_res = $this->app->model_users->fetch_user_by_username(0,$code_res['uid']);
            if ( $res->count > 0 )
            {
                $get['uid']   = $user_res->result[0]['uid'];
                $get['token'] = generatePublicToken();
                $res_insert   = $this->app->model_users->forgot_pass_token($get);
                if ( $res_insert->insert_id > 0 ){
                    $this->alldata['data']  = $get['token'];
                    $this->alldata['status']  = 1;
                }

            }else{
                $this->alldata['msg'] = LANG_INVALID_VERIFICATION_CODE;
            }
        }else{
            $this->alldata['msg'] = LANG_INVALID_VERIFICATION_CODE;
        }
        // pr($this->alldata,true);
        $this->app->_response(200,$this->alldata);
    }
    function change_password($param,$get)
    {
        $res_token = $this->app->model_users->check_token($get['token']);
        // pr($res_token,true);
        if (count($res_token) > 0) {
            $uid = $res_token[0]['uid'];
            if($get['password'] === $get['re_password']){
                $password = hash_password($get['password']);
                $res = $this->app->model_users->force_new_password($password,$uid);
                if($res->affected_rows > 0){
                    $this->app->model_users->delete_tokens($uid);
                    $this->alldata['status']  = 1;
                    $this->alldata['data']  = 'Done';
                }else{
                    $this->alldata['data']  = LANG_PASSWORDS_DOSENT_MATCH;
                }
            }else{
                $this->alldata['data']  = LANG_PASSWORDS_DOSENT_MATCH;
            }
        }else{
            $this->alldata['data']  = INVALID_TOKEN;
        }
        $this->app->_response(200,$this->alldata);
    }

}