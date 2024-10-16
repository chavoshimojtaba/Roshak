<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_extra extends DB{


    function value ($grp)
    {
        return $this->select("
            SELECT
                tp_id,
                tp_name,
                tp_value
            FROM
                `tbl_values`
            WHERE
                `tp_delete` = '0'
            AND `tp_grp`    = '$grp'
            ORDER BY `tp_order` ASC
        ");
    }


    function province ($id=0)
    {
        $WHERE = "";

        if ( !is_array($id) &&  $id > 0 )
        {
            $WHERE = " AND `tp_id` = '$id' ";
        }

        if ( is_array($id) && count($id) > 0 )
        {
            $WHERE = " AND `tp_id` IN (".  implode(',',$id).") ";
        }

        return $this->select("
            SELECT
                `tp_name` AS `name`,
                `tp_id`    AS `pid`
            FROM
                `general_province`
            WHERE
                `tp_delete` = '0'
            $WHERE
        ");
    }


    function city ($pid=0)
    {
        $whr = '';
        if($pid != 0){
            $whr = "AND `tp_pid`    = '$pid'";
        }
        return $this->select("
            SELECT
                `tp_pid` AS `pid`,
                `tp_name` AS `name`,
                `tp_id`    AS `id`
            FROM
                `general_city`
            WHERE
                `tp_delete` = '0'
                $whr
        ");
    }

    function city_province ()
    {
        return $this->select(
            "SELECT
                city.`tp_name`     AS `city`,
                city.`tp_id`       AS `cid`,
                province.`tp_name` AS `province`,
                province.`tp_id`   AS `pid`
            FROM
                `general_city` AS city INNER JOIN
                `general_province` AS province ON(province.tp_id = city.tp_pid)
        ");
    }


    function sms_sign ($sid)
    {
        return $this->select("
            SELECT
                `sd_id`    AS `id`,
                `sd_value` AS `name`
            FROM
                `general_setting`
            WHERE
                `sd_delete` = '0'
            AND `sd_name`   = 'sms_sign'
            AND `sd_index`  = '$sid'
        ");
    }


}
