<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function email_template ($sw=FALSE)
{
    $instance = &get_instance();
    $instance->load->model('model_settings');
    $res = $instance->model_settings->fetch_email_tamplate();
    $arr = [];
    if ( $sw !== FALSE )
    {
        $arr[0] = LANG_SELECT;
    }
    for ( $i=0;$i<$res->count;$i++)
    {
        $arr[$res->result[$i]['id']] = $res->result[$i]['title'];
    }
    return $arr;
}

function sms_template ($sw=FALSE)
{
    $instance = &get_instance();
    $instance->load->model('model_settings');
    $res = $instance->model_settings->fetch_sms_tamplate();
    $arr = [];
    if ( $sw !== FALSE )
    {
        $arr[0] = LANG_SELECT;
    }
    for ( $i=0;$i<$res->count;$i++)
    {
        $arr[$res->result[$i]['id']] = $res->result[$i]['title'];
    }
    return $arr;
}


/*
* Created     : Wed Aug 17 2022 5:08:48 PM
* Author      : Chavoshi Mojtaba
* Description : get site general info
* return      : array
*/

function siteInfo ($return=false)
{
    if(!isset($_SESSION['site']) || !is_array($_SESSION['site'])){
        $instance = &get_instance();
        $instance->load->model('model_settings');
        $res = $instance->model_settings->get_seo_info(true);
        $_SESSION['site'] = decode_html_tag($res->result[0],true);
    }
    if($return){
        return $_SESSION['site'];
    }
}
