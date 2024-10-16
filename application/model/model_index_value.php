<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_index_value extends DB
{

    public function fetch_values ($name,$where=[])
    {
        $w = '';
        if (count($where) > 0)
        {
            foreach ($where as $field=>$value)
            {
                $temp[] = "sd_{$field} = '$value' ";
            }
            $w = " AND ".implode(' AND ',$temp);
        }
        return $this->select("
            SELECT
                `sd_index` AS `index`,
                `sd_value` AS `value`
            FROM
                `general_index_value`
            WHERE
                `sd_delete` = '0'
            AND `sd_name`   = '$name' $w
        ");
    }



}
