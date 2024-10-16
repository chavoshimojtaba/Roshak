<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tree {


    private $controll;

    public $result = array();

    var $Vitem = '';

    var $Titem = '';

    function __construct() {
        $this->controll = &get_instance();
    }


    function publish_tree (){
        $this->tree_item();
        $this->result['v'] = $this->Vitem;
        return $this->set_tree();
    }


    function tree_item ($parent=0,$id=0)
    {
        $this->controll->load->model('model_tree');
        $this->result['v'] = $this->Vitem;
        $res = $this->controll->model_tree->fetch_tree($parent,$id);

        //////pr($this->controll->router->dir_view);
        //////pr(_VIEW.'tree'.EXT,1);


        $this->controll->template->restart(_VIEW.'tree'.EXT,$this->controll->router->dir_view);
        for ( $i=0;$i<$res->count;$i++ )
        {
            $res->result[$i]['t'] = $this->Titem;
            $res->result[$i]['v'] = $this->Vitem;
            if ( $res->result[$i]['child'] > 0 )
            {
                $res->result[$i]['cls']   = 'parent';
                $res->result[$i]['click'] = 'true';
            }
            else
            {
                $res->result[$i]['cls']   = 'node';
                $res->result[$i]['click'] = 'false';
            }
            $this->controll->template->assign($res->result[$i]);
            $this->controll->template->parse("li");
        }
        $this->result['content'] = $this->controll->template->text("li");
    }


    function set_tree()
    {
        $this->controll->template->restart(_VIEW.'tree'.EXT,$this->controll->router->dir_view);
        $this->controll->template->assign($this->result);
        $this->controll->template->parse("tree");
        $this->result['content'] = $this->controll->template->text("tree");
        return $this->out();
    }


    function rename_item ()
    {
        $post =  $this->controll->security->input('post',array(),TRUE);
        $this->controll->load->model('model_tree');
        if ( $this->controll->model_tree->rename_item($post['id'],$post['name']))
        {
            $this->message(1,'ok');
        }
        else
        {
            $this->message(2,_MSG_ERROR_);
        }
    }


    function remove_item ()
    {
        $post =  $this->controll->security->input('post',array(),TRUE);
        $this->controll->load->model('model_tree');
        if ( $this->controll->model_tree->remove_item($post['id']))
        {
            $this->message(1,'ok');
        }
        else
        {
            $this->message(2,_MSG_ERROR_);
        }
    }

    function add_item ()
    {
        $post =  $this->controll->security->input('post',array(),TRUE);

        if ( $post['t'] > 0 ){
            $parent = $post['id'];
        }else{
            $parent = 0;
        }
        $this->controll->load->model('model_tree');
        $res = $this->controll->model_tree->add_item($post['title'],$parent);
        if ( $parent > 0 )
        {
            $this->controll->model_tree->update_count_child($parent);
        }
        if ( $res->insert_id > 0 )
        {
            $this->tree_item($parent,$res->insert_id);
            $this->message(1,$this->result['content']);
        }
        else
        {
            $this->message(2,_MSG_ERROR_);
        }
    }


    function out ()
    {
        $this->controll->template->restart(_VIEW.'tree'.EXT,$this->controll->router->dir_view);
        $this->controll->template->assign($this->result);
        $this->controll->template->parse("main");
        $tree = $this->controll->template->text("main");
        return $tree;
    }

    function message ($type,$msg){
        $array = array(
            1=>'success',
            2=>'error'
        );
        $this->controll->template->out(json_encode(array($array[$type]=>$msg)),TRUE);
    }



}