<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$mode = isset($value['id'])?'reset':'set';

$footer_links['form'] = [
    'action' => HOST.'receiver/get/'.lock_string ('footer_links',TRUE).'/'.lock_string ('skin',TRUE).'/'.$mode,
    'res'    => TRUE,
    'style'  => 'tbl'
];

$id =  isset($value['id'])?$value['id']:0;

$footer_links['id'] = [
    'item'  => 'input',
    'type'  => 'hidden',
    'ness'  => FALSE,
    'name'  => 'id',
    'value' => $id,
    'func' => [
        'fn'    => 'unlock_string',
        'param' => []
    ]
];

$footer_links['section'] = [
    'item'  => 'input',
    'type'  => 'hidden',
    'ness'  => true,
    'name'  => 'section',
    'value' => 'footer'
];

$footer_links['title'] = [
    'item'  => 'input',
    'type'  => 'text',
    'name'  => 'title',
    'class' => '',
    'ness'  => TRUE,
    'inf'   => '',
    'value' => isset($value['title'])?$value['title']:'',
    'lang'  => '',
    'attr'  => ''
];

$footer_links['url'] = [
    'item'  => 'input',
    'type'  => 'text',
    'name'  => 'url',
    'alias' => 'link',
    'class' => '',
    'ness'  => TRUE,
    'inf'   => '',
    'value' => isset($value['url'])?$value['url']:'',
    'lang'  => 'en',
    'attr'  => ''
];


$footer_links['submit'] = [
    'item'  => 'submit',
    'type'  => 'submit',
    'name'  => 'submit',
    'class' => 'primary',
    'value' => LANG_SAVE
 ];


/*------------------------------------------------------------------------------
 * 
 * 
 -----------------------------------------------------------------------------*/

$footer_links['insert'] = [
    'table' => ['tbl_cms_link'],
    'field' => [
        'tbl_cms_link' => [
            'title',
            'url',
            'section'
        ]
    ]
];