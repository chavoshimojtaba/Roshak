<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
class comment {

    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_comment');
        if(isset($get['type'])){
            $this->app->model_comment->_table     = "`tp_".$get['type']."_comments`";
            $this->app->model_comment->_tableType =  $get['type'] ;
        }
	}

    function add($param,$get)
    {
        $get['publish'] = 'pend';
        $get['uid']     = 0;
		$get['rate']    = 0;
        $get['mid']     = $_SESSION['mid'];//session member
        if($get['pid']>0){
            $detail  = $this->app->model_comment->get_detail($get['pid']);
            $get['parent_mid']     = $detail['mid'];
            $get['pbid']    = $detail['pbid'];
            $get['path']    = $detail['path'];
        }else{
            $get['parent_mid']     = $get['mid'];
        }
        // //pr($get,true);
		$res = $this->app->model_comment->add_reply($get);
        if ($res->insert_id > 0) {
			$this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function like($param,$get)
    {
        if(is_login()){
            $res  = $this->app->model_comment->like($get['cid']);
            if ($res) {
                $this->alldata['status'] = 1;
            }
            $this->app->_response(200,$this->alldata);
        }else{
            $this->app->_response(401,$this->alldata);
        }
    }

    /*
    * Created     : Fri Aug 19 2022 6:09:18 PM
    * Author      : Chavoshi Mojtaba
    * Description : list of member comments
    * return      : res
    */
    function member_comments($param,$get)
    {
        $get['mid'] = $_SESSION['mid'];
        $this->alldata['total'] = $this->app->db->total_count('tp_comments', "`tp_delete` = '0' AND tp_type=1  AND tp_mid=".$get['mid']);

		if ($this->alldata['total'] > 0)
        {
            $res = $this->app->model_comment->get_content_comments_of_members($get,$get['type']);
            // $this->alldata['total'] = $this->app->model_comment->get_count($get,$get['type']);
            $data = [];
            foreach ($res->result as  &$value) {
                $value['createAt']  = g2pt($value['createAt']);
                $value['content_name']     = decode_html_tag($value['content_name'],true);
                $value['text']     = decode_html_tag($value['text'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }

    /*
    * Created     : Fri Aug 19 2022 8:36:33 PM
    * Author      : Chavoshi Mojtaba
    * Description : delete comment of member
    * return      : array
    */


    function delete($param,$get)
    {
        $res = $this->app->model_comment->delete_comment($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

}