<?php if (!defined('BASEPATH')) exit('No direct script access allowed');



require_once(DIR_LIBRARY . "JwtMiddleWare.php");

class login extends Controller
{


    function index($token = '')
    {

        if(isset($_SESSION['login']) && isset($_SESSION['uid'])  && isset($_SESSION['admin'])){
            redirect(BASE_URL.'home');
            exit();
        }else{
            $this->session->empty_cache();
            session_destroy();
            unset($_SESSION);
            $_SESSION = array();
        }
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        if ($token != '') {
            $this->load->model("model_users");
            $res = $this->model_users->check_token($token);
            $this->template->assign(['token'=>$token]);
            if (count($res) > 0) {
                $this->template->parse('main.change_pass');
            }else{
                $this->template->parse('main.invalid_token');
            }
        } else {
            $this->template->parse('main.login');
        }

        $this->template->parse('main');
        $this->template->out();
    }

}
