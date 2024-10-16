<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class admin {


    private $skin = 'default/';

    private $tpl;

    private $part_html = array ();

    private $controll;

    private $fullscreen ='';
    private $title ='';

    private $conf = array();

    private $section = array(
        'header',
        'footer',
        'body'  ,
        'menu_right',
        'tinymce_script'
    );

    function init ( $init=array() )
    {
        $this->skin = isset ( $init['skin'] ) ? $init['skin'] : $this->skin;
        $this->skin.= isset ( $init['dir'] ) ? $init['dir']  : 'front_end';
        $this->conf['content'] = isset ( $init['content']) ?  $init['content'] : '';
        $init['name']  = isset ( $_SESSION['full_name'] ) ? $_SESSION['full_name']:'مدیریت';
        $init['pic']  = $_SESSION['pic'] ;
        $Page__  = & load_class('router', 'core');
        $this->part_html['R'] = $Page__->class;
        $this->part_html['langs'] = json_encode(get_lang_constants());

        // //pr($init['title'],true);
        $this->conf = $init;
        $this->Template =  &load_class('Template');
        $this->controll =  &get_instance();
        if( !strpos($_SERVER['QUERY_STRING'], 'fullscreen')){
            foreach ($this->section as $section)
            {
                if ( isset($this->conf[$section]) AND $this->conf[$section] === FALSE )
                {
                    continue;
                }
                $this->$section();
            }
        }else{

            $this->fullscreen = 'fullscreen_body';
            $this->body();

        }
        $this->part_html['title'] = ( $this->title != '' )  ?  $this->title : '_SYS_TITLE';
        $this->assign_html();
    }


    function header ()
    {
        $conf['pic']  =  'upload/image/'.$_SESSION['pic'] ;

        if (isset($this->conf['header']) && $this->conf['header'] === FALSE){ return false;}
        if (isset($this->conf['header']) && !empty($this->conf['header'])) {
            $this->part_html['header'] = $this->conf['header'];
            return;
        }
        $this->Template->restart($this->skin.SLASH.'header'.EXT);
        $this->Template->assign($this->conf);

        $this->Template->parse('header');

        $this->part_html['header'] = $this->Template->text('header');
    }

    function menu_right ()
    {
        if (isset($this->conf['menu_right']) && $this->conf['menu_right'] === FALSE){ return false;}
        if (isset($this->conf['menu_right']) && !empty($this->conf['menu_right']))
        {
            return $this->part_html['menu_right'] = $this->conf['menu_right'];
        }
        $this->Template->restart($this->skin.SLASH.'menu_right'.EXT);
        $this->controll->load->model('model_menu');
        $res = $this->controll->model_menu->fetch_all_menu();
        $burl = ($this->controll->router->Burl != '')?$this->controll->router->Burl:BASE_URL;
        $permissions = $_SESSION['permissions'];
        $menus = [];
        $sub_menus = [];
        foreach ($res->result as $value) {
            if($_SESSION['is_admin'] || (array_key_exists($value['reid'],$permissions) || $value['reid'] == 0)){
                if($value['parent'] == 0){
                    $menus[] = $value;
                }else{
                    $sub_menus[$value['parent']][] = $value;
                }
            }
        }
        // //pr($permissions,true);
        foreach ($menus as $val) {
            $has_child = array_key_exists($val['id'],$sub_menus);
            $this->Template->assign('burl',$burl);
            if($has_child && count($sub_menus[$val['id']]) > 0){
                $child_menus = $sub_menus[$val['id']];
                foreach ($child_menus as  $child_value) {
                    $this->Template->assign($child_value);
                    $this->Template->parse('menu_right.dropdown.dropdown_item');
                }
                $this->Template->assign($val);
                $this->Template->parse('menu_right.dropdown');
            }else if($val['url']){
                if ($val['show'] == 2) {
                    $this->Template->assign($val);
                    $this->Template->parse('menu_right.single');
                }
            }
        }
        $this->Template->assign($this->controll->router->class,'active');
        $this->Template->assign($this->conf);
        $this->Template->parse('menu_right');
        $this->part_html['menu_right'] = $this->Template->text('menu_right');

    }


    function footer ()
    {
        if (isset($this->conf['footer']) && $this->conf['footer'] === FALSE){ return false;}
        if (isset($this->conf['footer']) && !empty($this->conf['footer'])) {
            $this->part_html['footer'] = $this->conf['footer'];
            return;
        }
        $this->Template->restart($this->skin.SLASH.'footer'.EXT);
        $this->Template->parse('footer');
        $this->part_html['footer'] = $this->Template->text('footer');
    }



    function body ()
    {
        if (isset($this->conf['body']) && $this->conf['body'] === FALSE){ return false;}
        if (isset($this->conf['body']) && !empty($this->conf['body'])) {
            $this->part_html['body'] = $this->conf['body'];
            return;
        }
        $methods = [
            'home'=>[
                'title'=>LANG_DASHBOARD,
                'index'=>'',
            ],
            'user'=>[
                'title'=>LANG_PROFILE,
                'profile'=>'',
            ]
        ];
        $this->Template->restart($this->skin.SLASH.'body'.EXT);
		$route_array = $this->controll->router->url_array;
        $page_title = @constant('LANG_BC_'.strtoupper($route_array['class']));
        if(isset($methods[$route_array['class']]) && isset($methods[$route_array['class']][$route_array['method']])){
            if(isset($methods[$route_array['class']]['title'])){
                // //pr($route_array,true);
                $page_title = $methods[$route_array['class']]['title'];
            }else if(constant('LANG_BC_'.strtoupper($route_array['method']))){
                $page_title = @constant('LANG_BC_'.strtoupper($route_array['method']));
            }
            $cur_page_title = $methods[$route_array['class']][$route_array['method']];
        }else{
            // $cur_page_title = @constant('LANG_BC_'.strtoupper($route_array['method'])).' '.@constant('LANG_'.strtoupper($route_array['class']));
            $cur_page_title = @constant('LANG_BC_'.strtoupper($route_array['method']));
        }
        $this->Template->assign('current_page_Link',BASE_URL.$route_array['class']);
        $this->Template->assign('page_Link',BASE_URL.$route_array['class']);
        $this->Template->assign('cur_active_title',$cur_page_title);
        $this->Template->assign('fullscreen',$this->fullscreen);
        $this->Template->assign('page_title',$page_title);

        $this->title = $page_title.(($cur_page_title == '')?'':' | '.$cur_page_title);
        $this->Template->assign($this->conf);
        if ( isset ($this->conf['title_content']) AND !empty($this->conf['title_content']) )
        {
            $this->Template->parse('body.title_content');
        }
        // $this->Template->assign('header_date', g2p(date('Y-m-d')));
        $this->Template->parse('body');

        $this->part_html['body'] = $this->Template->text('body');
    }


    function tinymce_script ()
    {
        if (isset($this->conf['tinymce_script']) && $this->conf['tinymce_script'] === FALSE){ return false;}
        if (isset($this->conf['tinymce_script']) && !empty($this->conf['tinymce_script'])) {
            $this->part_html['tinymce_script'] = $this->conf['tinymce_script'];
            return;
        }
        $this->Template->restart($this->skin.SLASH.'tinymce'.EXT);
        $this->Template->parse('script');
        $this->part_html['tinymce_script'] = $this->Template->text('script');
    }

    function assign_html ()
    {
        $this->Template->restart($this->skin.SLASH.'html'.EXT);
        $this->part_html['JSVERSION'] = rand(111,333);
        $this->Template->assign($this->part_html);
        $this->Template->parse('html');
        $this->Template->out('html');
    }
}