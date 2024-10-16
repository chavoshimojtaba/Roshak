<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$router['http']          = 'http://';
$router['default_class'] = 'home';
$router['default_mthod'] = 'index';
$router['folder']        = 'roshak/';
$router['vip']           = array();
$router['Burl']          = '';
$router['dir_view']      = '';
$router['Curl']          = '';
$router['path']          = 'front_end/';
$router['dir_control']        = '';
$router['count_external_url'] = 2;
$router['NotCheck']     = ['login','register'];


$router['extra_url'] =  array(
    'admin'=>array(
        'index'  => 'home',
        'method' => 'index',
        'folder' => 'back_end/',
        'login'  => TRUE,
        'NotCheck' =>array('login')
    ),
    'dashboard' => [
        'index'    => 'home',
        'method'   => 'index',
        'folder'   => 'dashboard/',
        'login'    => TRUE,
        'NotCheck'   => ['login','register']
    ]
);
