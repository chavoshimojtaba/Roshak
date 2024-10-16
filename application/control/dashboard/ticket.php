<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class ticket extends Controller
{
    public $block = 'index';

    public function _index($id = 0)
    {

        // //pr($this->router,true);
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
        $this->load->model('model_role');
        $res = $this->model_role->get_list_all();
        if ($res->count > 0) {
            foreach ($res->result as &$value) {
                $value['name'] = decode_html_tag($value['name'], true);
                $this->template->assign($value);
                $this->template->parse('index.roles');
                $this->template->parse('index.roles1');
            }
        }

        if ($id > 0) {
            $this->block = 'view';
            $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
            $this->load->model('model_tickets');
            $res = $this->model_tickets->get_ticket($id);
            // pr($id,true);
            if ($res->count > 0) {
                $data                = [];
                $ticket              = $res->result[0];
                // //pr($ticket,true);
                $ticket['full_name']   = decode_html_tag($ticket['full_name'], true);
                $ticket['title']       = decode_html_tag($ticket['title'], true);
                $ticket['last_update'] = g2pt($ticket['last_update']);
                $ticket['priority']    = getPriority($ticket['priority']);
                $ticket['id']      = $id;
                $ticket['status']      = $ticket['status'];
                $ticket['comment']     = decode_html_tag($res->result[0]['comment'],true);
                $ticket['createAt']    = g2pt($ticket['createAt']);
                $ticket['number']      = "TIK_" . $ticket['number'];
                foreach ($res->result as &$value) {
                    $value['createAt']  = g2pt($value['createAt']);
                    $value['comment']   = decode_html_tag($value['comment'], true); 
                    $data[$value['comment_id']] = $value;
                }
                if ($res->result[0]['has_file'] == 'yes') {
                    $this->load->model('model_file');
                    $res_file = $this->model_file->get_resource_files(implode(',', array_keys($data)), 'ticket');
                    foreach ($res_file->result as $v) { 
                        $data[$v['rid']]['files'] .= '
                            <a class="ticket-card__file btn btn-info px-2 mt-2 me-2" href="' . HOST . $v['dir'] . '" target="_blank">دانلود فایل</a>
                        ';
                    }
                } 
                require_once(DIR_HELPER . "helper_html.php");
                foreach ($data as  $ticket_item) {
                    $this->template->assign(['comment'=>createTicketRows($ticket_item)]);
                    $this->template->parse($this->block . '.comments');
                }

                if ($ticket['status'] == 'open') {
                    $this->template->parse($this->block . '.reply');
                    $this->template->assign($ticket_item);
                    $this->template->parse($this->block . '.reply_form');
                } else {
                    $this->template->parse($this->block . '.closed');
                }
                $this->template->assign($ticket);
                $this->page->set_data([
                    'title' => $ticket['title'],
                'follow_index'=>'follow, noindex', 
                'desc' => LANG_TICKETS,
                    'breadcrump' => [LANG_TICKETS],
                    'files' => [ 
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url' => 'file/global/dropzone/dropzone.css', 'type' => 'css'],
                    ['url' => 'file/global/dropzone/dropzone.js', 'load' => '', 'type' => 'js'],  
                        ]
                ]);
            } else {
                _404();
            }

            /*
			$res = $this->model_member->designer_by_slug($slug);
			if($res->count > 0){
				$expertise = values('expertise');
				$designer = $res->result[0];
				$part_html = $designer;
				$this->load->model('model_product');
				$res_products = $this->model_product->get_list([
					'mid'=>$designer['id'],
					'limit'=>20,
					'page'=>1
				]);

				foreach ($res_products->result as &$value) {
					$value['createAt']   = g2pt($value['createAt']);
					$value['title']      = decode_html_tag($value['title'],true);
					$this->template->assign($value);
					$this->template->parse('view.product');
				}
			} else {
				error_404($this);
			} */
          
        } else {

            require_once DIR_LIBRARY . "ssr_grid.php";
            $GridData = new SSR_Grid([
                'limit'  => '10',
                'type'  => 'ticket',
            ]);
            $part_html = $GridData->getData();
            $part_html['ssr_grid'] = $GridData->html();
            $this->template->assign($part_html);
            $this->page->set_data([
                'title' => LANG_TICKETS,
			'follow_index'=>'follow, noindex', 
            'desc' => LANG_TICKETS,
                'breadcrump' => [LANG_TICKETS],
                'files' => [
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.style.css','type'=>'css'],
                    ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.js','load'=>'defer','type'=>'js'],
                    ['url' => 'file/global/dropzone/dropzone.css', 'type' => 'css'],
                ['url' => 'file/global/dropzone/dropzone.js', 'load' => '', 'type' => 'js'], 
                    ['url' => 'file/client/css/select2.min.css', 'type' => 'css'],
                    ['url' => 'file/client/js/select2.min.js', 'load' => '', 'type' => 'js'],
                    ]
            ]);
        }

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
