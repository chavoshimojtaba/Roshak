<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class post extends Controller{
    
    private $data = array ();

    protected $tab = [
        'index' => LANG_POST
    ];
    
    function index ($page=1)
    {
        $this->load->model('model_post');
        $res = $this->model_post->grid_post($page,'index/');
        
        $this->load->config('grid',$this->router->class.SLASH,$res);        
        $this->html->_msg = show_msg();
        $this->html->_grid = $this->html->grid($res);
        $this->display();
    }
    
    function add ($id='')
    {
        $value = [];
        
        if ( $id != '' )
        {
            $_id = $this->security->check_id($this->security->key,$id);
            $this->load->model('model_post');
            $res_post = $this->model_post->fetch_post($_id);
            $value = $res_post->result[0];
            $value['id'] = $id;
        }
        
        $this->html->_msg = show_msg ();
        $this->tab[$this->router->method] = LANG_ADD_POST;
        $value['url'] = CURRENT_URL.'index';
        $this->html->analayz_form($this->load->config('form',$this->router->class.SLASH,$value));
        $this->html->_form = $this->html->form;
        $this->display();
    }
    
    function display ()
    {
        $this->data['content'] = $this->html->tab_links($this->tab, assign_value($this->html->get_string('array')), $this->router->method);
        out($this->data,'admin'); 
    }
    
}