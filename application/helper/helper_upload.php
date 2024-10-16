<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function form_upload($att=array(),$block='main')
{
    ini_set('max_execution_time', 3000);
    set_time_limit (3000);
    $C = &get_instance();
    $C->template->restart(_VIEW.'upload_form'.EXT,DIR_VIEW);
    $C->template->assign($att);
    $C->template->parse($block);
    return $C->template->text($block);
}

function new_load_file ($record_id,$section_name,$type,$key='',$merge=false)
{
    global $grab_pattern;

    $C = &get_instance();
    $content = '';
    
    if( $record_id > 0 )
    {
        $C->load->model("model_file");
        $res = $C->model_file->fetch_files($record_id,$section_name);
        if ( $key == '' )
        {
            $key = $C->security->key;
        }
        if( $res->count > 0 )
        {

            $content = HOST.$res->result[0]['dir'].''.$res->result[0]['name'];
        }
    }
    return $content;
}
function load_file ($record_id,$section_name,$type,$key='',$merge=false)
{
    global $grab_pattern;

    $block = $grab_pattern[$type]['block'];
    $C = &get_instance();
    $C->template->restart('view_upload.php',DIR_VIEW);
    $att   = array();
    $content = '';
    if($merge == true)
    {
        $type = $section_name;
    }
    if ( isset ( $_SESSION['img'][$type] ) AND count ($_SESSION['img'][$type]) > 0 )
    {

        foreach ( $_SESSION['img'][$type] as $value )
        {
            $value['uptype'] = $type;
            $C->template->assign($value);
            $C->template->parse($block);
        }
        $content .= $C->template->text($block);
    }
    if( $record_id > 0 )
    {
        $block = $block.'_save';
        $C->load->model("model_file");
        $res = $C->model_file->fetch_files($record_id,$section_name);
        if ( $key == '' )
        {
            $key = $C->security->key;
        }
        if( $res->count > 0 )
        {
            for ($i=0;$i<$res->count;$i++)
            {
                $res->result[$i]['file_id'] = $C->security->lock_string($key,$res->result[$i]['file_id']);
                $res->result[$i]['file_relation_id'] = lock_string($res->result[$i]['file_relation_id'],TRUE);
                $C->template->assign( $res->result[$i]);
                $C->template->parse($block);
            }
            $_SESSION['temp_file'][$section_name] = $section_name;
        }
        $content .= $C->template->text($block);
    }
    return $content;
}
