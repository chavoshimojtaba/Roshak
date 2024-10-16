<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}

class setting {

    private $model;
    private $app;
	private $alldata = [];
    public function init($app) {
        $this->alldata['status'] = 0;
        $this->app = $app;
        $this->app->load->model('model_settings');
        $this->model = $this->app->model_settings;
	}

    function seo_update($param,$get)
    {
		$res = $this->app->model_settings->update_seo($get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function social_update($param,$get)
    {
		$res = $this->app->model_settings->social_update($get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    /* -------------------------------------------------------------------------- */
    /*                                 user_stories                                 */
    /* -------------------------------------------------------------------------- */
    function user_stories($param,$get)
    {
        $res = $this->app->model_settings->get_user_stories(0,$get);
        $this->alldata['total'] = $res->total;
        if ($res->count  > 0)
        {
            foreach ($res->result as $key => &$value) {
                $res->result[$key]['sub_title'] = decode_html_tag($value['sub_title'],true);
                $res->result[$key]['fullname'] = decode_html_tag($value['fullname'],true);
                $value['createAt']   = g2pt($value['createAt']);
                $res->result[$key]['text'] = decode_html_tag($value['text'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_user_stories($param,$get)
    {
		$res = $this->app->model_settings->update_user_stories($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_user_stories($param,$get)
    {
		$res = $this->app->model_settings->add_user_stories($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete_user_stories($param,$get)
    {
        $res = $this->app->model_settings->delete_user_stories($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    /* -------------------------------------------------------------------------- */
    /*                                 public_links                                 */
    /* -------------------------------------------------------------------------- */

    function public_links($param,$get)
    {
        $res = $this->app->model_settings->get_public_links($get);
        $this->alldata['total'] = $res->total;
        if ($res->count  > 0)
        {
            foreach ($res->result as &$value) {
                $value['createAt']   = g2pt($value['createAt']);
                $value  = decode_html_tag($value,true);
            }

            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_public_links($param,$get)
    {
		$res = $this->app->model_settings->update_public_links($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_public_links($param,$get)
    {
		$res = $this->model->add_public_links($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete_public_links($param,$get)
    {
        $res = $this->model->delete_public_links($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    /* -------------------------------------------------------------------------- */
    /*                                 header_links                                 */
    /* -------------------------------------------------------------------------- */

    function header_links($param,$get)
    {
        $res = $this->app->model_settings->get_header_links($get);
        $this->alldata['total'] = $res->count; 
        if ($res->count  > 0)
        {
            foreach ($res->result as &$value) {
                $value['createAt']   = g2pt($value['createAt']);
                $value  = decode_html_tag($value,true);
            }

            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_header_links($param,$get)
    {
		$res = $this->app->model_settings->update_header_links($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_header_links($param,$get)
    {
		$res = $this->model->add_header_links($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete_header_links($param,$get)
    {
        $res = $this->model->delete_header_links($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }

    

    /* -------------------------------------------------------------------------- */
    /*                                footer_links                                */
    /* -------------------------------------------------------------------------- */
    function footer_links($param,$get)
    {
        $columns = [];
        $res = $this->app->model_settings->footer_link_list($get);
        $columns_res = $this->app->model_settings->footer_link_columns();
        foreach ($columns_res->result as $key => $value) {
            $columns[$value['id']] = $value['title'];
        }
        $this->alldata['total'] = $res->count;
        if ($res->count  > 0)
        {
            foreach ($res->result as $key => $value) {
                $res->result[$key]['column'] = $columns[$value['pid']];
                $res->result[$key]['title'] = decode_html_tag($value['title'],true);
                $res->result[$key]['createAt']   = g2pt($value['createAt']);
                $res->result[$key]['url'] = decode_html_tag($value['url'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_footer_columns($param,$get)
    {
		$res = $this->app->model_settings->update_footer_columns($get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_footer_links($param,$get)
    {
		$res = $this->app->model_settings->update_footer_links($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_footer_links($param,$get)
    {
		$res = $this->model->add_footer_links($get);
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->insert_id;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete_footer_links($param,$get)
    {
        $res = $this->model->delete_footer_links($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }


     /* -------------------------------------------------------------------------- */
    /*                                 event                                 */
    /* -------------------------------------------------------------------------- */

     function get_events($param,$get)
    {
        $columns = [];
        $res = $this->app->model_settings->get_events($get);
         
        $this->alldata['total'] = 1000;
        if (1000  > 0)
        {
            foreach ($res->result as $key => $value) { 
                $res->result[$key]['title'] = decode_html_tag($value['title'],true); 
                $res->result[$key]['value'] = decode_html_tag($value['value'],true);
            }
            $this->alldata['data']   = $res->result;
            $this->alldata['status'] = 1;
        }
        $this->app->_response(200,$this->alldata);
    }
    function update_event($param,$get)
    {
		$res = $this->app->model_settings->update_event($param[0],$get);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
    function add_event($param,$get)
    {
        $get['start']=date_format(date_create($get['start']),'Y/m/d'); 
        $get['end']=date_format(date_create($get['end']),'Y/m/d'); 
        $res = $this->app->model_settings->add_event($get); 
        if ($res->insert_id > 0) {
            $this->alldata['status'] = 1;
            $get['id'] = $res->insert_id;
            $this->alldata['data'] = $get;
        }
        $this->app->_response(200,$this->alldata);
    }
    function delete_event($param,$get)
    {
        $res = $this->app->model_settings->delete_event($param[0]);
        if ($res->affected_rows > 0) {
            $this->alldata['status'] = 1;
            $this->alldata['data'] = $res->affected_rows;
        }
        $this->app->_response(200,$this->alldata);
    }
}