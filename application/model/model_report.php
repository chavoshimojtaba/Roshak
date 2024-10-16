<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_report extends DB
{
    public function reports_list($params = [])
    {
        $order = 'reports.tp_id DESC';


        return $this->pager(
            "SELECT
                reports.tp_id          AS `id`,
                reports.tp_pid       AS `pid`,
                reports.tp_subject   AS `subject`,
                reports.tp_title     AS `title`,
                reports.tp_desc      AS `desc`,
                reports.tp_reply     AS `reply`,
                product.`tp_title`     AS `product`,
                product.`tp_pic`       AS `img`,
                CONCAT(
                    member.tp_name,
                        ' ',
                    member.tp_family
                )                   AS `full_name`,
                reports.`tp_date`      AS `createAt`
            FROM
                `tp_product_reports`   AS reports INNER JOIN
                `tp_product`    AS product  ON(reports.tp_pid = product.tp_id  ) INNER JOIN
                `tp_members`    AS member  ON(reports.tp_pid = member.tp_id  )
            WHERE
                product.`tp_delete` = 0
            ORDER BY $order
        ",
            isset($params['limit']) ? $params['limit'] : 10,
            isset($params['page']) ? $params['page'] : 1,
            true
        );
    }
    public function designer_sell($mid = [], $data = [])
    {
        if(isset($data['pid'])){
            return $this->select(
                "SELECT
                    DATE(`ordr`.`tp_date`) AS `date`,
                    SUM(`ordr`.`tp_total_price`) AS `total_price`
                FROM
                    `tp_order`    AS `ordr`    INNER JOIN
                    `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                    `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid AND `products`.`tp_id` = {$data['pid']} )
                WHERE
                    `ordr`.`tp_delete` = 0 AND `ordr`.`tp_status` = 'done' AND DATE(`ordr`.`tp_date`) > DATE_SUB(NOW(), INTERVAL  {$data['days']} DAY)
                GROUP BY
                    DATE(`ordr`.`tp_date`)
            "
            );
        }
        return $this->select(
            "SELECT
                DATE(`ordr`.`tp_date`) AS `date`,
                SUM(`ordr`.`tp_total_price`) AS `total_price`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid AND `products`.`tp_mid` = $mid )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_status` = 'done' AND DATE(`ordr`.`tp_date`) > DATE_SUB(NOW(), INTERVAL  {$data['days']} DAY)
            GROUP BY
                DATE(`ordr`.`tp_date`)
        "
        );
    }
    
    public function designer_downloads($mid = [], $pid=0)
    {
        $whr = '';
        if($pid>0){
            $whr = ' AND `products`.`tp_id` = '. $pid;
        }
        return $this->select(
            "SELECT
                count(`order_products`.`tp_id`) AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid AND `products`.`tp_mid` = $mid $whr)
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_status` = 'done' AND DATE(`ordr`.`tp_date`) > DATE_SUB(NOW(), INTERVAL  30 DAY)
        "
        )->result[0]['cnt'];
    }
}
