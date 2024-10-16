<?php
	if( ! defined('BASEPATH')) {exit('No direct script access allowed');}
class Page{

	private $meta = [];
	private $js   = '';
	private $css  = '';
	private $breadCrump  = '';
	private $data  = [
		"title"=>LANG_WEBSITE_NAME,
		"desc"=>LANG_WEBSITE_DESC,
		"url"=>CURRENT_URL,
		"follow_index"=>'follow, index',
		"type"=>'product',
		"image"=>HOST.'file/global/image/logo.jpg',
		"width"=>'140',
		"height"=>'35px',
		"twitter_card_size"=>'summary_large_image',
		"image_type"=>'image/jpeg',
		"twitter_id"=>'',
	];


    public function __construct()
    {
    }

	public function set_data ($data=[])
	{

		$data = array_merge($this->data,$data);

		
		if(array_key_exists('files',$data) && count($data)>0){
			foreach ($data['files'] as $key => $value) {
				$host = HOST;
				if(isset($value['cdn'])){
					$host = '';
				} 
				if(!str_contains($value['url'],'http') ){
					$value['url'] = $host.$value['url'];
				}
				if($value['type'] === 'css'){
					$this->css .= '<link rel="stylesheet" href="'.$value['url'].'">'; 
				}else if($value['type'] === 'js'){
					$this->js .= '<script src="'.$value['url'].'" '.$value['load'].'></script>';
				}
			}
		}

		if(array_key_exists('breadcrump',$data) ){
			$this->set_breadCrump($data['breadcrump']);
		}
		unset($data['breadcrump']);
		unset($data['files']);
		$data['title'] = isset($data['title'])?$data['title']:LANG_WEBSITE_NAME; 
		$data['follow_index'] = isset($data['follow_index'])?$data['follow_index']:'follow, index';
		$data['desc'] = isset($data['desc'])?$data['desc']:LANG_WEBSITE_DESC;
		$data['more'] = isset($data['more_meta'])?$data['more_meta']:''; 
		if(isset($data['error'])){  
			$data['url'] = $data['error'];  
		} else{
			$data['url'] = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
		} 
		$url_components = parse_url($data['url'],PHP_URL_QUERY);
		parse_str($url_components, $params);
		if(count($params)>0){
			if(isset($params['page']) && $params['page']>1){ 
				$data['title'] .= ' - صفحه '.$params['page'];
				$data['desc'] .= ' - صفحه '.$params['page'];
				$data['url'] = explode('?',$data['url'])[0] .'?page='. $params['page'];
			}else{
				$data['url'] = explode('?',$data['url'])[0]  ;
			}
		} 
		$GLOBALS['page_config'] = ['meta'=>$data,'js_scripts'=>$this->js,'css_scripts'=>$this->css]; 
	}

	public function get_meta ()
	{
		foreach ($GLOBALS['page_config']['meta'] as $key => $value) {
			$GLOBALS['page_config']['meta_'.$key] = $value;
		}
		// pr($GLOBALS['page_config'],true);
		return $GLOBALS['page_config'];
	}

	public function get_js ()
	{
		return $this->js;
	}

	public function get_css ()
	{
		return $this->css;
	}

	function set_breadCrump(array $routes=[]) : void {
		$position = 1;
		$html = '<li class="breadcrumb-item active" itemprop="itemListElement" itemscope
		itemtype="https://schema.org/ListItem">
		<a itemprop="item" href="'.HOST.'" class="text-decoration-none text-secondary">
		<i class="float-end mt-0 fs-5 ms-2 icon icon-home"></i>
		<span itemprop="name"> '.LANG_WEBSITE_NAME.'
		</span>
		</a><meta itemprop="position" content="'.$position.'" />
		</li> ';
		$cnt  = count($routes);
		foreach ($routes as $key => $value) {
			$position++; 
			if($cnt > $key+1){
				$html .= '<li  class="breadcrumb-item active" itemprop="itemListElement" itemscope
				itemtype="https://schema.org/ListItem" ><a itemprop="item"  class="text-decoration-none text-secondary" href="'.HOST.''.$value['url'].'"><span itemprop="name">'.$value['title'].'</span></a><meta itemprop="position" content="'.$position.'" /></li>';
			}else{
				$html .= is_array($value)? '<li class="breadcrumb-item text-primary" itemprop="itemListElement" itemscope
				itemtype="https://schema.org/ListItem" aria-current="page"><span itemprop="name">'.$value['title'].'</span> <meta itemprop="position" content="'.$position.'" /></li>':'<li itemprop="itemListElement" itemscope
				itemtype="https://schema.org/ListItem" class="breadcrumb-item text-primary"  aria-current="page" ><span itemprop="name">'.$value.'</span> <meta itemprop="position" content="'.$position.'" /></li>';
			}
		} 
		$this->breadCrump = '
			<nav aria-label="breadcrumb" class="mt-1 mb-0">
                <ol class="breadcrumb text-end" itemscope itemtype="https://schema.org/BreadcrumbList">
					'.$html.'
                </ol>
            </nav>
		';
	}

	public function get_breadCrump ()
	{
		return $this->breadCrump;
	}

}

?>
