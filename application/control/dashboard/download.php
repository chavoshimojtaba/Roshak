<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class download extends Controller
{
    public function index()
    {

        $this->template->restart(_VIEW.$this->router->class. EXT, $this->router->dir_view);
        $post = $this->router->request_get(); 
        $post['mid'] = $_SESSION['mid'];
        $categories = GLOBALS('category',false); 
        foreach ($categories as $value) {
            $this->template->assign($value);
            $this->template->parse('index.category');
        }
        $this->load->model('model_order');
        $statistics = $this->model_order->member_orders_statistics($_SESSION['mid']);
        // //pr($statistics,true);

        require_once DIR_LIBRARY."ssr_grid.php";
        $GridData = new SSR_Grid(array_merge([
            'limit'  => '10',
            'type'  => 'download',
        ],$post));
        $data = $GridData->getData();
        $data['ssr_grid'] = $GridData->html();  
        if(isset($_SESSION['bank_status'])){
            $data['bank_message'] = $_SESSION['bank_message'];
            $this->template->assign($data);
            if($_SESSION['bank_status'] == 'done'){
                $this->template->parse('index.order_done');
            }else{
                $this->template->parse('index.order_failed');
            }
            // pr($_SESSION,true);
            unset($_SESSION['bank_status']);
            unset($_SESSION['bank_message']);
        }

        $this->page->set_data([
            'title'=>LANG_ORDERS,
            'desc'=>LANG_ORDERS,
            'breadcrump'=>[LANG_ORDERS],
            'files'=>[
				['url'=>'file/client/css/profile.css','type'=>'css'],
                ['url'=>'file/client/css/select2.min.css','type'=>'css'],
                ['url'=>'file/client/js/select2.min.js','load'=>'','type'=>'js'],				
                ['url'=>'file/client/js/owl.carousel.min.js','load'=>'','type'=>'js'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.style.css','type'=>'css'],
                ['url'=>'file/global/persianDatePicker/mds.bs.datetimepicker.js','load'=>'defer','type'=>'js'],
            ]
        ]);
        $this->template->assign(array_merge($data,$statistics));

        $this->display();
    }

	public function display()
	{
		$this->template->parse($this->router->method);
		out([
            'content' => $this->template->text($this->router->method)
        ]);
	}
}

