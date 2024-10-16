<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(DIR_HELPER . 'helper_setting.php');
require_once(DIR_HELPER.'helper_html.php');


class site
{

    private $skin = 'default/';

    private $part_html = array();

    private $sys;

    private $conf = array();

    private $setting = array();

    private $section = array(
        'menu_top',
        'menu_right',
        'footer',
        'body'
    );

    public function init($init = array())
    {
        $Page__  = &load_class('router', 'core');

        if(isset($init['dir']) && ($init['dir'] == 'dashboard')){
            $init['dir'] = 'front_end/';
        }

        $this->skin = isset($init['skin']) ? $init['skin'] : $this->skin;
        $this->skin .= (isset($init['dir']) && $init['dir'] != 'dashboard') ? $init['dir']   : 'front_end';
        $this->skin .= (isMobile() && $init['dir'] != 'dashboard') ? 'mobile/'  : '';
        $this->conf['content'] = isset($init['content']) ?  $init['content'] : '';
        // $this->conf['body_class'] = isset($init['body_class']) ?  $init['body_class'] : '';
        // $init['name']   = isset($_SESSION['name']) ? $_SESSION['name'] : ''
        $this->conf     = $init;
        $this->Template =  &load_class('Template');
        $this->sys      =  &get_instance();
        // //pr($this,true);

        $this->sys->load->model('model_settings');
        $this->setting  = array_merge(siteInfo(true), []);
        // pr($this,true);
        $this->part_html['R'] = $Page__->class;
        // //pr($Page__,true);
        if (isset($_SESSION['admin']) && $_SESSION['path'] = 'admin') {
            redirect(BASE_URL . 'out');
            exit();
        }

        if (isset($this->setting['system_name'])) {
            $this->setting['keyword']   = str_replace('-', ',', $this->setting['keywords']);
            $this->setting['desc']      = decode_html_tag($this->setting['exp'], true);
            $this->setting['copyright'] = decode_html_tag($this->setting['copy_right'], true);
        }

        $this->part_html['path'] = $Page__->path == 'front_end/'?'client':$Page__->path;
        $this->part_html['langs'] = json_encode(get_lang_constants());
        $this->part_html['breadcrump'] = $this->sys->page->get_breadCrump();
        foreach ($this->section as $section) {
            if (isset($this->conf[$section]) and $this->conf[$section] === FALSE) {
                continue;
            }
            $this->$section($Page__->path);
        }
        $this->assign_html($Page__->path);
    }

    public function menu_right($path)
    {
        if($path == 'dashboard'){ 
            $this->part_html['menu_right'] = dashboard_sidebar($this->part_html['R'],false,true);
        }
    }

    public function menu_top()
    {
        if (isset($this->conf['menu_top']) && $this->conf['menu_top'] === FALSE) {
            return false;
        }

        if (isset($this->conf['menu_top']) && !empty($this->conf['menu_top'])) {
            return $this->part_html['menu_top'] = $this->conf['menu_top'];
        }

        $this->Template->restart($this->skin . 'menu_top' . EXT);

        if(is_login()){
            // pr($_SESSION,true);
            $this->Template->assign(array_merge($_SESSION,['menu_right'=> dashboard_sidebar($this->part_html['R'],1)]));
            $this->Template->parse('menu_top.dashboard_btn');
            // $this->Template->assign(array_merge($_SESSION,['menu_right'=> dashboard_sidebar($this->part_html['R'],1)]));
            $this->Template->parse('menu_top.dashboard_btn1');
        }else{
            $this->Template->parse('menu_top.login_btn');
        }
        if($this->part_html['R'] == 'home'){
            $this->Template->assign('header_links',header_links_html());
            $this->Template->parse('menu_top.menu');
        }else{
            $this->Template->parse('menu_top.search');
        }
        // pr(plan(@$_SESSION['mid']),true);
        if(!plan(@$_SESSION['mid'])['has_plan']){
            $this->Template->assign(@$_SESSION['full_name']);
            $this->Template->parse('menu_top.plan_btn');
        }
        if (isMobile()){
            $menu = file_get_contents(DIR_CACHE.'menu_mobile.php' );
            $this->Template->assign('header_links',header_links_html());
        }else{
            $menu = file_get_contents(DIR_CACHE.'menu.php' );
        }
        $this->Template->assign('menu',$menu);

        $this->Template->parse('menu_top');
        $this->part_html['menu_top'] = $this->Template->text('menu_top');
    }

    public function footer($path)
    {
        
        if (isset($this->conf['footer']) && $this->conf['footer'] === FALSE) {
            return false;
        }
        if (isset($this->conf['footer']) && !empty($this->conf['footer'])) {
            return $this->part_html['footer'] = $this->conf['footer'];
        }
        $this->Template->restart($this->skin . 'footer' . EXT);
        if($path !== 'dashboard'){   
            // pr($path,true);
            $footer_links = file_get_contents(DIR_CACHE.'footer_links.php' );
            $this->setting['footer_links'] = $footer_links;
            $this->Template->assign($this->setting);
            $this->Template->parse('footer.main_footer');
        }
        $this->Template->assign($this->setting);
        $this->Template->parse('footer');
        $this->part_html['footer'] = $this->Template->text('footer');
    }

    public function body()
    {
        if (isset($this->conf['content']) && !empty($this->conf['content'])) {
            return $this->part_html['body'] = $this->conf['content'];
        }
        $this->part_html['body'] = $this->Template->text('body');
    }

    function assign_html($path)
    {
        
        $this->sys->template->restart($this->skin . SLASH . 'html' . EXT);
        if(is_login()){ 
            $this->Template->parse('html.is_login');
        }
        if($path == 'dashboard'){
            $this->sys->template->assign($this->part_html);
            $this->sys->template->parse('html.body_dashboard');
        }else{
            $this->sys->template->assign($this->part_html);
            $this->sys->template->parse('html.body');
        }
        $this->sys->template->assign($this->part_html);
        // pr($this->sys->page->get_meta(),true);
        $this->sys->template->assign($this->sys->page->get_meta());
        $this->sys->template->assign($this->setting);
        $this->sys->template->parse('html');
        $this->sys->template->out('html');
    }
}
