<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class comment extends Controller
{
    public $block = 'index';

    public function _index($id = 0)
    {

        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);

        require_once DIR_LIBRARY . "ssr_grid.php";
        $GridData = new SSR_Grid([
            'limit'  => '10',
            'type'  => 'comment',
        ]);
        $part_html = $GridData->getData();
        // //pr($part_html,true);
        if(isset($part_html['date'])){
            $part_html['date_p'] = g2p($part_html['date']);
        }
        $part_html['ssr_grid'] = $GridData->html();
        $this->template->assign($part_html);
        $this->page->set_data([
            'title' => LANG_COMMENTS,
            'desc' => LANG_COMMENTS,
			'follow_index'=>'follow, noindex', 

            'breadcrump' => [LANG_COMMENTS],
            'files' => [
                
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'],
                ['url' => 'file/global/persianDatePicker/mds.bs.datetimepicker.style.css', 'type' => 'css'],
                ['url' => 'file/global/persianDatePicker/mds.bs.datetimepicker.js', 'load' => 'defer', 'type' => 'js'],
            ]
        ]);
        $this->display();
    }

    public function display()
    {
        $this->template->parse($this->block);
        out([
            'content' => $this->template->text($this->block)
        ]);
    }
}
