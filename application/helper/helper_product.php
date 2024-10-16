<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
function update_product($data, $id)
{
 
	$C               = &get_instance();
	$C->load->model('model_category');
	$data['cat_path'] = $C->model_category->get_path($data['cid']);
	$C->load->model('model_product'); 
	$res = $C->model_product->update_product($data['id'], $data);
	if ($res->affected_rows > 0) {
		$tagsJson = json_decode($data['tags'], true);
		$C->load->model('model_tag'); 
		$tags = [];
		foreach ($tagsJson as $key => $value) {
			$tags[] = [
				'pid' => $id,
				'tid' => $key,
			];
		}
		$C->model_tag->add_content_tags($tags, $id); 
		$galleryJson = json_decode($data['gallery'], true);
		$gallery = [
			'type' => 'product',
			'rid' => $id,
			'files' => []
		]; 
		foreach ($galleryJson as $key => $value) {
			$gallery['files'][] = isset($data['front_side'])?$value:$key;  
		} 
		$C->load->model('model_file');
		$C->model_file->update_file_relations($gallery);
	}
	return $res->affected_rows;
}
function add_product($data)
{
	 
	$C           = &get_instance();
	$data['mid'] = isset($_SESSION['mid']) ? $_SESSION['mid'] : 1;
	$C->load->model('model_category');
	$data['cat_path'] = $C->model_category->get_path($data['cid']);
	$C->load->model('model_product');
	$data['serial'] = $C->model_product->createProductSerial();
	if($C->model_product->get_product_detail($data['slug'],true)->count == 0){
		$res = $C->model_product->add_product($data);
		if ($res->insert_id > 0) { 
			$C->load->model('model_tag'); 
			$tagsJson = json_decode($data['tags'], true);
			$tags = [];
			foreach ($tagsJson as $key => $value) {
				$tags[] = [
					'pid' => $res->insert_id,
					'tid' => $key,
				];
			}
			if (count($tags) > 0) {
				$C->model_tag->add_content_tags($tags);
			} 
			$galleryJson = json_decode($data['gallery'], true);
			$gallery = [
				'type' => 'product',
				'rid' => $res->insert_id,
				'files' => []
			]; 
			foreach ($galleryJson as $key => $value) {
				$gallery['files'][] = isset($data['front_side'])?$value:$key; 
			};
			$C->load->model('model_file');
			$C->model_file->update_file_relations($gallery);
		}
		return $res->insert_id;
	}
	return 0;
}
