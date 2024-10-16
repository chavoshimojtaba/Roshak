<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function _g2fa($date,$patern='Y')
{
  $pdate = load_class('persian_date');
  return fa_int($pdate->to_date($date,$patern));
}

function month_range()
{
    $d = substr(g2p(date('Y-m-d')),0,8);
    $m = substr(g2p(date('Y-m-d')),5,2);
    $r = ['31','31','31','31','31','31','30','30','30','30','29'];
    return [
        'first'=>p2g($d.'01'),
        'last' =>p2g($d.$r[(int)$m])
    ];
}