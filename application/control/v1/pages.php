<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class pages {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_pages');
	}



    function list($param,$get)
    {
        $res = $this->app->model_pages->get_list($get);
        $this->alldata['total'] = $res->total;

        if ($this->alldata['total'] > 0)
        {
            foreach ($res->result as $key => $value) {

                $res->result[$key]['createAt']   = g2pt($value['createAt']);
                $res->result[$key]['title']      = decode_html_tag($value['title'],true);
            }
            $this->alldata['data'] = $res->result;
            $this->alldata['status']           = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    function add($param,$get)
    {
        $res = $this->app->model_pages->add_pages($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }


    /*
    * Created  : Thu Aug 04 2022 2:51:45 PM
    * Author   : Chavoshi Mojtaba
    * return   : response
    */
    function add_agent($param,$get)
    {
        $res = $this->app->model_pages->add_agent($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }

    function update($param,$get)
    {
        $res = $this->app->model_pages->update_pages($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function member_messages_read($param,$get)
    {
        $res = $this->app->model_pages->member_messages_read($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }



    /*
    * Created     : Wed Aug 17 2022 4:15:18 PM
    * Author      : Chavoshi Mojtaba
    * Description : list of member messages in contact us page
    * return      : array
    */
    function member_messages($param,$get)
    {
        $res = $this->app->model_pages->member_messages($get);
        $this->alldata['total'] = $res->total;
		$subject = values('contact_us_subject',false,1);
        if ($res->count > 0)
        {
            foreach ($res->result as  &$value) {
                $value['createAt']   = g2pt($value['createAt']);
                $value['exp']        = decode_html_tag($value['exp'],true);
                $value['subject']      = $subject[$value['subject']]['name'];
                $value['email']      = decode_html_tag($value['email'],true);
                $value['mobile']     = decode_html_tag($value['mobile'],true);
            }
            $this->alldata['data']    = $res->result;
            $this->alldata['status']  = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    /*
    * Created  : Fri Aug 05 2022 2:36:24 PM
    * Author   : Chavoshi Mojtaba
    * return   : response
    */
    function update_agent($param,$get)
    {
        $res = $this->app->model_pages->update_agent($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }



    /*
    * Created  : Fri Aug 05 2022 5:18:05 PM
    * Author   : Chavoshi Mojtaba
    * return   : response
    */
    function update_about_us($param,$get)
    {
        $get['meta'] = isset($get['meta'])?$get['meta']:'';
        $get['cover'] = isset($get['cover'])?$get['cover']:'';
        $get['title'] = isset($get['title'])?$get['title']:'';
        $get['slug'] = isset($get['slug'])?$get['slug']:'';
        $get['desc'] = isset($get['desc'])?$get['desc']:'';
        $res = $this->app->model_pages->update_about_us_policy($get,$get['type']);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    /*
    * Created  : Sun Aug 14 2022 1:31:05 PM
    * Author   : Chavoshi Mojtaba
    * return   : res
    */

    function del_event($param,$get)
    {
        $res = $this->app->model_pages->del_event($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function del_agent($param,$get)
    {
        $res = $this->app->model_pages->del_agent($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function delete($param,$get)
    {
        $res = $this->app->model_pages->delete_pages($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }


    /*
    * Created  : Sun Aug 14 2022 3:29:15 PM
    * Author   : Chavoshi Mojtaba
    * return   : add new event
    */

    function add_event($param,$get)
    {
		$res = $this->app->model_pages->add_event($get);
        if ($res->insert_id > 0) {
            $get['files'] = json_decode($get['gallery'],true);
            if(count($get['files']) > 0){
                $get['type'] = 'gallery';
                $get['pbid'] = $res->insert_id;
                $this->app->load->model('model_file');
                $this->app->model_file->add_file_relation($get);
            }
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function edit_faq($param,$get)
    {
        $res = $this->app->model_pages->get_faq($get['id']);
        if ($res->count > 0)
        {
            $faq = $res->result[0];
            $this->alldata['status'] = 1;
            $faq['title'] = decode_html_tag($faq['title'],true);
            $faq['desc']  = decode_html_tag($faq['desc'],true);
            $this->alldata['data'] = $faq;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_faq($param,$get)
    {
        $res = $this->app->model_pages->add_faq($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function del_faq($param,$get)
    {
		$res = $this->app->model_pages->del_faq($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_faq($param,$get)
    {
		$res = $this->app->model_pages->update_faq($get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    function team_members($param,$get)
    {
        $res = $this->app->model_pages->get_team_members();
        $this->alldata['total'] = $res->count;
        if ($res->count  > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['expert'] = decode_html_tag($value['expert'],true);
                $res->result[$key]['fullname'] = decode_html_tag($value['fullname'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_team_members($param,$get)
    {
		$res = $this->app->model_pages->update_team_members($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_team_members($param,$get)
    {
		$res = $this->app->model_pages->add_team_members($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete_team_members($param,$get)
    {
        $res = $this->app->model_pages->delete_team_members($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}