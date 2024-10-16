<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(DIR_HELPER . 'helper_setting.php');
require_once(DIR_HELPER."helper_html.php");
 
class home extends Controller
{
	public $res_categories = [];

    public function index()
    {
		// pr(isMobile(),true);
        $this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
		$this->res_categories = GLOBALS('category',false) ;
		shuffle($this->res_categories); 
		$c = 0;
        foreach ($this->res_categories as $key =>   $value) {
			if($c==12){
				break;
			}
			if($value['pid'] == 0){
				$c++; 
				$value['title'] = decode_html_tag($value['title'],true); 
				$this->template->assign($value); 
				$this->template->parse('index.section_categories');
			}
		}
		
		$this->load->model('model_blog');
		$res = $this->model_blog->get_list(['limit'=>10]);
        if($res->count > 0){
			foreach ($res->result as $key => &$value) {
				$value['blog_date'] 	 = decode_html_tag($value['blog_date'],true);
				$value['reading_duration'] 	 = decode_html_tag($value['reading_duration'],true);
				$value['author'] 	 = decode_html_tag($value['author'],true);
				$value['title'] 	 = decode_html_tag($value['title'],true);
				$this->template->assign($value);
				$this->template->parse('index.blogs');
			}
		}

        //tags
/*         $this->load->model('model_tag');
		$res = $this->model_tag->home_tags();
        if($res->count > 0){
			foreach ($res->result as $key => &$value) {
				$value['title'] 	 = decode_html_tag($value['title'],true);
				$this->template->assign($value);
				$this->template->parse('index.tags');
			}
		}
 */
        //user_stories
        $this->load->model('model_settings');
		$events = $this->model_settings->get_events(['count'=>6]);
		if($events->count > 0){
			$dates = [];
			foreach ($events->result as $key => $value) {
				if(isset($dates[$value['start']])){
					$dates[$value['start']]['event'][] = $value; 
				}else{
					$dates[$value['start']] = $value; 
					$dates[$value['start']]['event'][]= $value; 

				}
			} 
			$pdate = load_class('persian_date');  
			$days=0;
			foreach ($dates as $key => &$value) {
				if($days>6){
					break;
				}
				$days++;
				$value['title'] = decode_html_tag($value['title'],true);
                $value['value'] = decode_html_tag($value['value'],true); 
                $value['day'] = (int)$pdate->to_date($value['start'], 'd'); 
                $value['mounth'] = $pdate->to_date($value['start'], 'M'); 
				for ($i=0; $i < count($value['event']); $i++) { 	
					if($i==2)break;
					$this->template->assign($value['event'][$i]);
					$this->template->parse('index.calendar.events.event');
				} 
				$this->template->assign($value);
				$this->template->parse('index.events');
				$this->template->parse('index.calendar.events');
			}
			$this->template->parse('index.calendar');
			$this->template->parse('index.calendar_modal');
		}
		$res = $this->model_settings->get_user_stories();
		$home_desc = $this->model_settings->home_desc();
        if($res->count > 0){
			foreach ($res->result as $key => &$value) {
				$value['sub_title'] = decode_html_tag($value['sub_title'],true);
                $value['fullname'] = decode_html_tag($value['fullname'],true);
                $value['text'] = decode_html_tag($value['text'],true);
				$this->template->assign($value);
				$this->template->parse('index.user_stories');
			}
		}
		require_once DIR_LIBRARY . "ssr_grid.php";
		
        $this->load->model('model_member');
		$res = $this->model_member->designers_list(['sort'=>'statistic_product','limit'=>'30','page'=>'1']); 
		$GridData_designers = new SSR_Grid([
			'no_template'  => true,
			'limit'  => '30',
			'type'  => 'designer',
			'sort'=>'statistic_product'
		]);
		$designers = $GridData_designers->json();  
        if($designers['count'] > 0){
			foreach ($designers['result'] as $key => &$value) { 
				$value['full_name'] = decode_html_tag($value['full_name'],true);
                $value['slug'] = decode_html_tag($value['slug'],true);
				$this->template->assign($value);

				if(isMobile()){
					$this->template->parse('index.designers_item');

				}else{
					$this->template->parse('index.designers_items.designers_items_row.designers_item'); 
					if((($key+1) % 5) == 0){
						$this->template->parse('index.designers_items.designers_items_row');
					}  
					if((($key+1) % 10) == 0){ 
						$this->template->parse('index.designers_items');
					}  
				}
			}
			$this->template->parse('index.designers_items'); 
		}
// 
        //public_links
        $this->load->model('model_settings');
		$links = $this->model_settings->get_public_links(['limit'=>1000]);
        if($links->count > 0){
			foreach ($links->result as $key => $value) {
				if($value['type'] == 'back_link'){
					$value['title'] = decode_html_tag($value['title'],true);
					$this->template->assign($value);
					$this->template->parse('index.public_links_item');
				}
			} 
			foreach ($links->result as $key => &$value) {
				if($value['type'] == 'tag'){
					$value['title'] = decode_html_tag($value['title'],true);
					$this->template->assign($value);
					$this->template->parse('index.tags');
				}
			}
		}
		
		if(is_login()){
			$this->load->model('model_member');
			$res = $this->model_member->favorite_products(['page'=>1,'limit'=>10,'mid'=>$_SESSION['mid']]); 
			if($res->count > 0){
				foreach ($res->result as $key => &$value) {
					$value['product_name'] 	 = decode_html_tag($value['product_name'],true);
					$value['thumbnail'] 	 = thumbnail($value['thumbnail'],200);
					// pr($value,true);
					$this->template->assign($value);
					$this->template->parse('index.favorites.item');
				}
				$this->template->parse('index.favorites');
			}
		}

		if(!is_login() || !is_designer()) $this->template->parse('index.coorporation');

		if( !plan($_SESSION['mid'])['has_plan']){
			$this->template->parse('index.plan_1');
			$this->template->parse('index.plan');
		}

		$GridData_most_sell = new SSR_Grid([
			'limit'  => '24',
			'no_template'  => true,
			'type'  => 'product',
			'sort'=>'downloads'
		]);

        $GridData = new SSR_Grid([
            'limit'  => '24',
			'no_template'  => true,
            'type'  => 'product',
			'sort'=>'new_product'
        ] );

        $GridData_free = new SSR_Grid([
			'limit'  => '24',
			'no_template'  => true,
            'type'  => 'product',
            'is_premium'  => 'free'
		] );

		
		generateCategoryTemplatesCache();
		// pr($data,true);
		$data['ssr_grid'] = $GridData->html();
        $data['ssr_grid_most_sell'] = $GridData_most_sell->html();
        $data['ssr_grid_free_products'] = $GridData_free->html(); 
        $data['home_desc'] = $home_desc; 
        $this->template->assign(array_merge($data,siteInfo(true)));
        $this->template->parse('index'); 
		$this->page->set_data([
			'title'=>$_SESSION['site']['seo_title_home'],
			'desc'=>$_SESSION['site']['seo_meta_home'],
			'breadcrump'=>[LANG_HOME],
			'more'=>'<link rel="preload" fetchpriority="high" as="image" href="'.HOST.'file/client/images/bg-search-box.png" type="image/png">
			<script type="application/ld+json">
			{
			  "@context": "https://schema.org",
			  "@type": "Organization",
			  "image": "'.HOST.'file/client/images/logo.svg",
			  "url": "'.HOST.'", 
			  "logo": "'.HOST.'file/client/images/logo.svg",
			  "name": "'.$_SESSION['site']['system_name'].'",
			  "description": "'.$_SESSION['site']['seo_meta_home'].'", 
			  "address": {
				"@type": "PostalAddress",
				"streetAddress": "'.$_SESSION['site']['address'].'",
				"addressLocality": "Mashhad",
				"addressCountry": "IR"
			  } ,
			  "sameAs": [
				'.$_SESSION['site']['social_instagram'].',
				'.$_SESSION['site']['social_youtube'].', 
				'.$_SESSION['site']['social_whatsapp'].',
				'.$_SESSION['site']['social_linkedin'].',
				'.$_SESSION['site']['social_aparat'].',
				'.$_SESSION['site']['social_pinterest'].',
			  ],
			  "contactPoint": {
				"@type": "ContactPoint",
				"email": "'.$_SESSION['site']['email'].'",
				"telephone": "'.$_SESSION['site']['tel'].'"
			  },
			  "geo": {
				"@type": "GeoCoordinates",
				"latitude": "36.342980",
				"longitude": "59.552512"
			  }
			}
			</script>
			<script type="application/ld+json">
			{
				"@context": "http://schema.org",
				"@type": "WebSite",
				"name": "'.$_SESSION['site']['system_name'].'",
				"url": "'.HOST.'"
			}
			</script>  
			
			',
			'files'=>[
				is_login()?['url'=>'file/client/css/home.css','type'=>'css']:['url'=>'file/client/css/home.css','type'=>'css'],
				['url'=>'file/client/css/select2.min.css','type'=>'css'],
				['url'=>'file/client/js/select2.min.js','load'=>'','type'=>'js'], 
				['url'=>'file/admin/libs/calendar/dist/moment.min.js','load'=>'','type'=>'js'],
				['url'=>'file/admin/libs/calendar/dist/moment-jalaali.js','load'=>'','type'=>'js'],
				['url'=>'file/admin/libs/calendar/dist/fullcalendar.min.js','load'=>'','type'=>'js'],
				['url'=>'file/admin/libs/calendar/dist/fullcalendar.localall.js','load'=>'','type'=>'js'],
				['url'=>'file/client/js/owl.carousel.min.js','load'=>'','type'=>'js'],
			]
		]);
        out([
            'content' => $this->template->text('index')
        ]);
    }
}

