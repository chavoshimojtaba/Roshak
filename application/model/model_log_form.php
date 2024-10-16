<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_log_form extends DB{
     
    function grid_value ($table,$rid,$page=1,$url='',$limit=40,$search='')
    {
        return $this->pager(
            " SELECT
                    bq.tp_field      AS `field`,
                    bq.tp_old_value  AS `old_value`,
                    bq.tp_new_value  AS `new_value`,
                    bq.tp_date       AS `date`
                FROM
                    `$table` AS `form`
                INNER JOIN `backup_query` AS `bq` ON (
                    form.tp_id = bq.tp_record
                    AND bq.tp_field != 'tp_delete'
                    AND bq.tp_table = '$table'
                )
                WHERE
                    form.tp_rid = '$rid'
        ",$limit,$page,$url);
    } 
}