<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_menu extends DB
{
    public function fetch_menu($type=0)
    {
        
            $WHERE = " AND `tp_type` = '$type' ";
        

        return $this->select("
            SELECT
                `tbl_menu`.`tp_id`      AS `id`,
                `tbl_menu`.`tp_name`    AS `name`,
                `tbl_menu`.`tp_url`     AS `url`,
                `tbl_menu`.`tp_parent`  AS `parent`,
                `tbl_menu`.`tp_order`   AS `order`,
                `tbl_menu`.`tp_icon`    AS `icon`,
                `tbl_menu`.`tp_type`    AS `type`
            FROM
                `tbl_menu`
            WHERE
                `tp_delete` = '0'
            $WHERE
            AND `tp_parent` = '0'
            ORDER BY
                 tp_order
        ");
    }

    public function fetch_feature_menu($type=1)
    {
        return $this->select("
            SELECT
                `menu`.`tp_id`      AS `id`,
                `menu`.`tp_show`    AS `show`,
                `menu`.`tp_name`    AS `name`,
                `menu`.`tp_url`     AS `url`,
                `menu`.`tp_parent`  AS `parent`,
                `menu`.`tp_order`   AS `order`,
                `menu`.`tp_icon`    AS `icon`,
                `menu`.`tp_type`    AS `type`
            FROM
                `tbl_menu` AS `menu`
                
            WHERE
                    `menu`.`tp_delete` = '0'
                AND `menu`.`tp_feature` = '1'
                AND `menu`.`tp_parent` = '0'
            ORDER BY
                `menu`.tp_order
        ");
    }

    public function fetch_feature_menu_dashboad($type=1)
    {
        return $this->select("
            SELECT
            	`menu`.`tp_id` AS `id`,
            	`menu`.`tp_name` AS `name`,
            	`menu`.`tp_url` AS `url`,
            	`menu`.`tp_parent` AS `parent`,
            	`menu`.`tp_order` AS `order`,
            	`menu`.`tp_icon` AS `icon`,
            	`menu`.`tp_type` AS `type`
            FROM
            	`tbl_menu` AS `menu`
            WHERE
            	`menu`.`tp_delete` = '0'
            AND `menu`.`tp_feature` = '1'
            AND `menu`.`tp_type` = '$type'
            ORDER BY
            	`menu`.tp_order
        ");
    }

    public function fetch_all_menu()
    {
        return $this->select("
                SELECT
                    `tbl_menu`.`tp_id`      AS `id`,
                    `tbl_menu`.`tp_reid`    AS `reid`,
                    `tbl_menu`.`tp_name`    AS `name`,
                    `tbl_menu`.`tp_url`     AS `url`,
                    `tbl_menu`.`tp_parent`  AS `parent`,
                    `tbl_menu`.`tp_order`   AS `order`,
                    `tbl_menu`.`tp_show`    AS `show`,
                    `tbl_menu`.`tp_icon`    AS `icon`,
                    `tbl_menu`.`tp_type`    AS `type`
                FROM
                    `tbl_menu`
                WHERE
                    `tp_delete` = '0' AND 
                    `tp_show` = 2 
                ORDER BY tp_type,tp_order
        ");
    }

    public function fetch_menu_by_parent($parent=0,$type=1)
    {
        return $this->select("
            SELECT
                `tbl_menu`.`tp_id`      AS `id`,
                `tbl_menu`.`tp_name`    AS `name`,
                `tbl_menu`.`tp_url`     AS `url`,
                `tbl_menu`.`tp_parent`  AS `parent`,
                `tbl_menu`.`tp_order`   AS `order`,
                `tbl_menu`.`tp_icon`    AS `icon`,
                `tbl_menu`.`tp_type`    AS `type`
            FROM
                `tbl_menu`
            WHERE
                `tp_delete` = '0'
            AND `tp_parent` = " .$parent."
            AND `tp_type`   = '$type'
            ORDER BY tp_order
        ");
    }


    public function fetch_menu_user ($type=FALSE)
    {
        $WHERE = "";
        if ( $type == TRUE)
        {
            $WHERE  = "AND `tp_special`= '1' OR `tp_type`= '2'";
        }
        else
        {
             $WHERE  = "AND `tp_type`= '1'";
        }
        return $this->select("
            SELECT
                `tp_id`      AS `id`,
                `tp_name`    AS `name`,
                `tp_url`     AS `url`,
                `tp_ico`     AS `ico`,
                `tp_class`   AS `class`,
                `tp_type`    AS `type`
            FROM
                `zemanat_user_menu`
            WHERE
                `tp_delete` = '0'
            $WHERE
            ORDER BY tp_order
        ");
    }

}
