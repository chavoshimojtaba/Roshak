<?php
header("Content-Type: text/xml");


if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class sitemap extends Controller
{
	
	function index($type = '')
	{ 
		$limit = 50000;
		$items = [];
		$this->template->restart(_VIEW . $this->router->class . EXT, $this->router->dir_view);
		if($type == ''){
			redirect(HOST.'sitemap/index.xml');
		}
		$linkData = $this->getlLinkData($type); 
		if(in_array($linkData['link'],['font','image','video','stock','vector','mockup'])){
			// pr(GLOBALS('category'),true);
			$this->load->model('model_category'); 
			$categories = $this->model_category->get_list_sitemap(); 
			$cat = explode('.xml',$type)[0];
			$cid = 0;
			$pids = [];
			foreach ($categories as $key => $value) {
				if ($value['pid'] == '0' && $value['filetype'] ==$linkData['link']) {
					$pids[] = " category.tp_cat_path LIKE '%_{$value['id']}_%'";
					$value['url'] = 'search/'.$value['slug'] ; 
					$items[] = $value;
				}
			}
			$_categories = $this->model_category->get_list_childs_sitemap(implode(' OR ',$pids));
			foreach ($_categories as $key => $sub_cat) { 
				$sub_cat['url'] = 'search/'.$sub_cat['slug'] ; 
				$items[] = $sub_cat; 
			}    
		}
		else{ 
			switch ($linkData['link']) {
				case 'index':
					$products = $this->db->total_count('tp_product','tp_status="accept" AND tp_delete=0');
					$tag = $this->db->total_count('tp_product_tags','tp_delete=0');
					$designer = $this->db->total_count('tp_members','tp_delete=0 AND tp_type="designer"');
					$items =array_merge(
						[
							['url' => 'sitemap/categoryproduct.xml', 'update' => ''],
							['url' => 'sitemap/page.xml', 'update' => ''], 
						],
						$this->createLinks($designer,'graphist'),
						$this->createLinks($tag,'tag'),
						$this->createLinks($products,'product'),
					); 
					
					break;
				case 'page':
					$items = [
						['url' => 'about', 'update' => ''],
						['url' => 'contact', 'update' => ''],
						['url' => 'terms', 'update' => ''],
					];
					break;
				case 'categoryproduct':
					$items = [
						['url' => 'sitemap/font.xml', 'update' => ''], 
						['url' => 'sitemap/image.xml', 'update' => ''], 
						['url' => 'sitemap/video.xml', 'update' => ''],  
						['url' => 'sitemap/stock.xml', 'update' => ''],  
						['url' => 'sitemap/vector.xml', 'update' => ''],  
						['url' => 'sitemap/mockup.xml', 'update' => ''],  
					];
					break;
				case 'tag':
					$this->load->model('model_tag');
					$this->model_tag->_table = "`tp_product_tags`";
					$this->model_tag->_tableType =  'product' ;
					$res = $this->model_tag->get_sitemap_list(['limit'=>$limit,'page'=>$linkData ['number']]);
					if($res->count>0){
						foreach ($res->result as $key => $tag) {
							if(!$tag['update']){
								$tag['update'] = $tag['createAt'];
							}
							$tag['url'] = 'search/'.$tag['slug'].'-tag';
							$items[] = $tag;
						}
					}
					break;
				case 'product':
					$this->load->model('model_product'); 
					$res = $this->model_product->get_list_sitemap(['limit'=>$limit,'page'=>$linkData ['number']]);
					if($res->count>0){
						// pr($res,true);
						foreach ($res->result as $key => $product) {
							$tag = [];
							if(!$product['update']){
								$product['update'] = $product['createAt'];
							}
							$ids[] =$product['id']; 
							$tag['url'] = 'p/'.$product['slug'];
							$tag['images'][] = $product['pic']; 
							$items[$product['id']] = $tag;
						}
						$this->load->model('model_file');
						$res_gallery = $this->model_file->get_resource_files(implode(',',$ids),'product'); 
						if($res_gallery->count> 0){ 
							foreach ($res_gallery->result as $key => $image) {  
								$items[$image['rid']]['images'][] = $image['dir'];
							}
						} 
					}
					break;
				case 'graphist':
					$this->load->model('model_member'); 
					$res = $this->model_member->designers_list_sitemap(['limit'=>$limit,'page'=>$linkData ['number']]); 																																						
					if($res->count>0){
						foreach ($res->result as $key => $member) {
							if(!$member['update']){
								$member['update'] = $member['createAt'];
							}
							$member['url'] = 'designers/'.$member['slug']; 
							$items[] = $member;
						}
					}
					break;
				default: 
					$this->load->model('model_category'); 
					$categories = $this->model_category->get_list_sitemap();
					// $categories = GLOBALS('category',false); 
					$cat = explode('.xml',$type)[0];
					$cid = 0;
					if($cat === 'categoryproduct'){ 
						foreach ($categories as $key => $value) {
							if ($value['pid'] == '0') { 
								$value['url'] = 'sitemap/'.$value['slug'].'.xml';
								$items[] = $value;
							}
						}
					}else{
						foreach ($categories as $key => $value) {
							if ($cat == $value['slug']) {
								$cid = $value['id']; 
							}
						} 
						foreach ($categories as $k => $cat) {
							if ($cid == $cat['pid']) {
								if(!$cat['update']){
									$cat['update'] = $cat['createAt'];
								}
								$cat['url'] = 'sitemap/'.$cat['slug'].'.xml';
								
								$items[] = $cat;
							}
						}
						if(count($items) <= 0){
							$this->load->model('model_product'); 
							$products = $this->model_product->get_sitemap_list($cid);
							if($products->count>0){
								foreach ($products->result as $key => $value) { 
									$value['url'] = 'p/'.$value['slug'];
									$items[] = $value; 
								}
							} 
						}
					}
					break;
			}
		} 
		if($linkData['link'] == 'product'){
			foreach ($items as $key => $v) { 
				if(!$v['update']){
					$v['update'] = $v['createAt'];
				} 
				if(!$v['update']){
					$v['update'] = date("Y-m-d");
				}
				foreach ($v['images'] as $key => $image) { 
					$this->template->assign(['image'=>HOST.$image]);
					$this->template->parse('main.item.image');
				}
				$this->template->assign($v);
				$this->template->parse('main.item');
				$this->template->assign(['image'=>'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"']);
			}
		}else{

			foreach ($items as $key => $v) { 
				if(!$v['update']){
					$v['update'] = $v['createAt'];
				} 
				if(!$v['update']){
					$v['update'] = date("Y-m-d");
				} 
				$this->template->assign($v);
				$this->template->parse('main.item');
			}
		}
		// pr($items,true);
		
		$this->template->parse();
		$this->template->out();
	}
	public function createLinks ($total,$link,$limit=50000)
	{ 
		$items =[['url' => 'sitemap/'.$link.'.xml', 'update' => '']];
		if($total>$limit){
			$cnt = ceil($total/$limit); 
			for ($i=1; $i < $cnt; $i++) { 
				$items[] =['url' => 'sitemap/'.$link.'-'.$i.'.xml', 'update' => '']; 
			}
		}
		return $items;
	}
	public function getlLinkData ($link)
	{  
		$i = explode('.xml',$link);
		$link=$i[0];
		if(count($i)>1){
			$num = end(explode('-',$i[0]));
			if((int)$num){
				$num = (int)$num+1;
				return ['link'=>implode('-',explode('-',$i[0],-1)),'number'=>$num]; 
			}
		}
		return ['link'=>$i[0],'number'=>1];
	}
}
