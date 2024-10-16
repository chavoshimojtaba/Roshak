<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$form_text['form'] = [
    'action' => CURRENT_URL.'save',
    'res'    => TRUE,
    'style'  => 'tbl'
];


$form_text['title'] = [
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

$form_text['desc'] = [
    'item'  => 'textarea',
    'type'  => 'text',
    'name'  => 'desc',
    'class' => 'xxl',
    'ness'  => TRUE,
    'inf'   => '',
    'value' => isset($value['desc'])?$value['desc']:'',
    'lang'  => '',
    'attr'  => ''
];


$form_text['submit'] = [
    'item'  => 'submit',
    'type'  => 'submit',
    'name'  => 'submit',
    'class' => 'primary',
    'value' => LANG_SAVE
 ];