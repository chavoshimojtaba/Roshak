<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$value->style = 'default_grid';
$value->name  = '';

$value->url  = [
    'id'   => ['id']
];

$folder =  lock_string('isic',TRUE);
$grid   =  lock_string(grid,TRUE);
$table  =  lock_string('model_cms|grid_links|tbl_cms_link',TRUE);

$value->other_field  = [

    'delete' => [
        'caption'   => LANG_DELETE,
        'url'       => HOST."sweep/alert/{$folder}/{$grid}/{$table}", 
        'text'      => ICON_IMG_TRASH
    ]
];     
         
$value->field = array(
    'title',
    'link'
);

$value->func  = [
    'link' =>'decode_html_tag'
];

$value->param_func = [
    'link' => [true]
];
