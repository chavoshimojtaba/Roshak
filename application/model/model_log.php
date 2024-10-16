<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class model_log extends DB{

    function exist_user_online ($uid,$code,$ip)
    {
        return $this->select("
            SELECT
                `tp_id`
            FROM
                `user_online`
            WHERE
                `tp_uid`    = '$uid'
            AND `tp_code`   = '$code'
            AND `tp_delete` = '0'
            AND `tp_ip`     = '$ip'
        ");
    }


    function insert_user ($time,$uid,$code,$ip)
    {
        return $this->insert("
            INSERT INTO
                `user_online`
            SET
                `tp_time`   = '$time'
              , `tp_date`   = now()
              , `tp_uid`    = '$uid'
              , `tp_code`   = '$code'
              , `tp_ip`     = '$ip'
        ");
    }


    function update_user ($time,$uid,$code,$ip)
    {
        return $this->update("
            UPDATE
                `user_online`
            SET
                `tp_time`   = '$time'
              , `tp_update` = now()
            WHERE
                `tp_uid`    = '$uid'
            AND `tp_code`   = '$code'
            AND `tp_delete` = '0'
            AND `tp_ip`     = '$ip'
        ",false);
    }

    function remove_old_record ($time,$uid,$code,$ip)
    {
        return $this->update("
            UPDATE
                `user_online`
            SET
                `tp_delete`   = '1'
               ,`tp_update` = now()
            WHERE
                `tp_delete` = '0'
            AND `tp_time` < $time
        ",false);
    }


    function all_user_online ()
    {
        return $this->select("
            SELECT
                `tp_uid`
            FROM
                `user_online`
            WHERE
                `tp_delete` = '0'
        ");
    }

    function log_url($uid,$dir,$req,$class,$method,$ip,$agent,$data,$ajax,$mac,$ref)
    {
        return $this->insert("
            INSERT INTO
                `log_url`
            SET
              `tp_uid`   = '$uid'
            , `tp_file`  = '$dir'
            , `tp_req`   = '$req'
            , `tp_class` = '$class'
            , `tp_method`= '$method'
            , `tp_ip`    = '$ip'
            , `tp_agent` = '$agent'
            , `tp_ajax`  = '$ajax'
            , `tp_mac`   = '$mac'
            , `tp_ref`   = '$ref'
            , `tp_date`  = now()
        ");
    }


    function log_sms ($values)
    {
        return $this->insert("
            INSERT INTO
                `log_sms`
            (
                `tp_uid`,
                `tp_class`,
                `tp_method`,
                `tp_ip`,
                `tp_agent`,
                `tp_number`,
        	`tp_recid`,
                `tp_msg`,
                `tp_status`,
                `tp_date`
            ) VALUES
            $values
        ");
    }
    function log_export($values)
    {
        return $this->insert("
            INSERT INTO
                `log_export`
            SET
                tp_name         = '".$values['tp_name']."' ,
                tp_uid          = '".$values['tp_uid']."' ,
                tp_email        = '".$values['tp_email']."' ,
                tp_ip           = '".$values['tp_ip']."' ,
                tp_mobile       = '".$values['tp_mobile']."' ,
                tp_company      = '".$values['tp_company']."' ,
                tp_agent        = '".$values['tp_agent']."' ,
                tp_start        = '".$values['tp_start']."' ,
                tp_path         = '".$values['tp_path']."' ,
                tp_admin        = '".$values['tp_admin']."' ,
                tp_http_referer = '".$values['tp_http_referer']."' ,
                tp_query_string = '".$values['tp_query_string']."' ,
                tp_file_name    = '".$values['tp_file_name']."' ,
                tp_file_id      = '".$values['tp_file_id']."' ,
                tp_date         = now()
        ");
    }

    function insert_log_ip ($values)
    {
        return $this->insert("
            INSERT INTO
                `log_ip`
            SET
                $values ,
                tp_date         = now()
        ");
    }


}
