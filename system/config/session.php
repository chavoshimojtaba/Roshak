<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$session['check']    =  [
    'login',
    'uid',
    'token',
    'path',
    'key_symbol',
    'key_mixed'
];

$session['check_db'] = array (
    'uid'      => 'uid',
    'username' => 'username' ,
    'password' => 'password',
    'login_id' => 'login',
    'token'    => 'token'
);
$session['expire']   = 60;