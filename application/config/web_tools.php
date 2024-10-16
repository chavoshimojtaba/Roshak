<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * alias url
 */
$web_tools['alias_url'] = [
    'admin' => 'admin'
];




$web_tools['site'] = array(
    'render'=>'site',
    'template'=>''
);

$web_tools['admin'] = array(
    'render'  => 'admin',
    'template'=> '',
    'dir'     => 'back_end/',
    'setting' => array(
        'menu_right' => (function_exists('dynamic_menu'))?dynamic_menu(array(),TRUE):''
    )
);

$web_tools['dashbord'] = [
    'render'   => 'dashbord',
    'template' => 'bracket/',
    'dir'      => 'front_end/'
];


$web_tools['error'] = array(
    'render'=>'site',
    'template'=>'',
    'dir'=>'front_end/',
    'setting' => array(
        'page_title' => FALSE,
        'header'     => FALSE,
        'blog'       => FALSE,
        'links'      => FALSE
    )
);
