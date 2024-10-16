<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class auth extends Controller
{ 
    function index()
    { 
        if(isset($_SESSION['member']) && isset($_SESSION['mid'])){
            $this->session->log_out();   
        } 
        unset($_SESSION['site']);
        $this->part_html['langs'] = json_encode(get_lang_constants());  
        $this->part_html['dir'] = 'rtl';
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
		$this->template->assign($this->part_html); 
        $this->template->parse();
        $this->template->out();
    }
}
