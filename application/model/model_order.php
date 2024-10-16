<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_order extends DB
{
    public function designer_orders_statistics($mid)
    {

        $data = [];
        $data['total_count'] = $this->select(
            "SELECT
                COUNT(`ordr`.`tp_id`)             AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products      ON(order_products.tp_oid = ordr.tp_id )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_mid` = $mid AND ordr.tp_status='done'
        "
        )->result[0]['cnt']; 
        $data['last_mounth'] = $this->select(
            "SELECT
                COUNT(`ordr`.`tp_id`)             AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products      ON(order_products.tp_oid = ordr.tp_id )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_mid` = $mid AND `ordr`.`tp_date`  > DATE_SUB(NOW(), INTERVAL  30 DAY);
        "
        )->result[0]['cnt'];
        $data['today'] = $this->select(
            "SELECT
                COUNT(`ordr`.`tp_id`)             AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products      ON(order_products.tp_oid = ordr.tp_id )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_mid` = $mid AND DATE(`ordr`.`tp_date`)  > DATE(NOW());
        "
        )->result[0]['cnt'];
        return $data;
    }
    public function member_orders_statistics($mid)
    {

        $data = [];

        $data['total_downloads'] = $this->select(
            "SELECT
                COUNT(`ordr`.`tp_id`)             AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_status` = 'done'  AND `ordr`.`tp_mid` = $mid
        "
        )->result[0]['cnt'];

        $data['total_sell'] = $this->select(
            "SELECT
                COUNT(`ordr`.`tp_id`) AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid AND `products`.`tp_mid` = $mid )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_status` = 'done'
        "
        )->result[0]['cnt'];

        $data['today_downloads'] = $this->select(
            "SELECT
            COUNT(`ordr`.`tp_id`) AS `cnt`
        FROM
            `tp_order`    AS `ordr`    INNER JOIN
            `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )
        WHERE
            `ordr`.`tp_delete` = 0 AND `ordr`.`tp_mid` = $mid AND DATE(`ordr`.`tp_date`) = DATE(now()) AND  `ordr`.`tp_type` = 'subscription'  AND  `ordr`.`tp_status` = 'done'
        "
        )->result[0]['cnt'];
        $data['today_downloads_all'] = $this->select(
            "SELECT
            COUNT(`ordr`.`tp_id`) AS `cnt` 
        FROM
            `tp_order`    AS `ordr`    INNER JOIN
            `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )
        WHERE
            `ordr`.`tp_delete` = 0 AND `ordr`.`tp_mid` = $mid AND DATE(`ordr`.`tp_date`) = DATE(now())   AND  `ordr`.`tp_status` = 'done'
        "
        )->result[0]['cnt'];
        return $data;
    } 

    public function get_list($params = [])
    {
        list($q, $order) = create_query_filters($params, [
            'ordr' => ['total_price', 'full_name', 'status', 'serial'],
            'order_products' => ['product_price'],
            'products' => ['product_title'],
            'designer' => ['designer'],
            'member' => ['full_name'],
        ]); 
         
        if( isset($params['mid']) && $params['mid']>0 ) $q .= " AND  `ordr`.`tp_mid` = '{$params['mid']}'"; 
        return $this->pager(
            "SELECT
                `ordr`.`tp_id`             AS `id`,
                `ordr`.`tp_mid`            AS `mid`,
				`ordr`.`tp_type`           AS `type`,
				`ordr`.`tp_serial`         AS `serial`,
				`ordr`.`tp_transaction_id` AS `transaction_id`,
				`ordr`.`tp_total_price`    AS `total_price`,
				`ordr`.`tp_desc`           AS `desc`,
				`ordr`.`tp_discount`       AS `discount`,
				`ordr`.`tp_discount_code`  AS `discount_code`,
				`ordr`.`tp_status`         AS `status`,
                products.`tp_id`            AS `pid`,
                products.`tp_title`         AS `product_title`,
                order_products.`tp_amount`  AS `product_price`,
				`products`.`tp_slug`           AS `products_slug`,
				`designer`.`tp_id`           AS `designer_id`,
				`member`.`tp_pic`           AS `pic`,
                CONCAT(member.tp_name,' ',
				member.tp_family )  		AS `full_name`,
                CONCAT(
                    designer.tp_name,
                        ' ',
                    designer.tp_family
                )                   AS `designer`,
                `ordr`.`tp_date`            AS `createAt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products      ON(order_products.tp_oid = ordr.tp_id ) INNER JOIN
                `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid ) INNER JOIN
                `tp_members`  AS designer  ON(designer.tp_id =products.tp_mid)  INNER JOIN
                `tp_members`  AS member  ON(`member`.tp_id = `ordr`.tp_mid)
            WHERE
                `ordr`.`tp_delete` = 0 $q $order
        ",
            isset($params['limit']) ? $params['limit'] : 10,
            isset($params['page']) ? $params['page'] : 1,
            true
        );
    }

    public function today_downloads($mid)
    {
        return $this->select(
            " SELECT
                COUNT(`ordr`.`tp_id`) AS `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )
            WHERE
                `ordr`.`tp_delete` = 0 AND `ordr`.`tp_mid` = $mid AND DATE(`ordr`.`tp_date`) = DATE(now()) AND  `ordr`.`tp_type` = 'subscription'  AND  `ordr`.`tp_status` = 'done'
        "
        )->result['0']['cnt'];
    }

    public function add($post)
    {
        
        $res= $this->insert(
            " INSERT INTO
                `tp_order`
            SET
                tp_type           = '{$post['payment_type']}',
                tp_transaction_id = '{$post['transaction_id']}',
                tp_total_price    = '{$post['total_price']}',
                tp_desc           = '{$post['desc']}',
                tp_serial         = '{$post['serial']}',
                tp_discount       = '{$post['discount']}',
                tp_designer_share = '{$post['total_designer_share']}',
                tp_discount_code  = '{$post['discount_code']}',
                tp_status         = '{$post['status']}',
                tp_mid            = '{$post['mid']}',
                tp_date           = now()
        "
        );
        if($res->insert_id>0){
            $oid = $res->insert_id;
            $rows = [];
            foreach ($post['products'] as $key => $value) {
                $rows[] = "({$key},{$value['designer_share']},{$value['price']},{$oid} ,now())";
            }
            $values = implode(',', $rows);
            $this->insert(
                "INSERT INTO
                        `tp_order_products`
                        (tp_pid,tp_designer_share,tp_amount,tp_oid,tp_date)
                    VALUES
                        {$values};
                "
            );
        }
        return $res;

    }


    public function has_downlaoded($pid,$mid)
    {
        return $this->select(
            "SELECT
                COUNT(`ordr`.`tp_id`)             AS `cnt`

            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products      ON(order_products.tp_oid = ordr.tp_id AND `ordr`.tp_mid = $mid ) INNER JOIN
                `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid AND order_products.tp_pid = $pid ) INNER JOIN
                `tp_members`  AS member  ON(`member`.tp_id = `ordr`.tp_mid)
            WHERE
                `ordr`.`tp_delete` = 0  AND `ordr`.tp_status = 'done'
        ")->result[0]['cnt'];
    }

    public function update_order($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_order`
            SET
                `tp_status` = '{$data['status']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function update_transaction($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_transaction`
            SET
                `tp_tracking_code` = '{$data['tracking_code']}',
                `tp_bank_message` = '{$data['bank_message']}',
                `tp_bank_code` = '{$data['bank_code']}',
                `tp_data` = '{$data['data']}',
                `tp_status` = '{$data['transation_status']}',
                `tp_status` = '{$data['transation_status']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function add_transaction($post)
    {

        return $this->insert(
            " INSERT INTO
                `tp_transaction`
            SET
                tp_type     = '{$post['type']}',
                tp_bank   = '{$post['bank']}',
                tp_amount   = '{$post['total_price']}',
                tp_mid      = '{$post['mid']}',
                tp_status   = 'pend',
                tp_date     = now()
        "
        );
    }


































    public function get_order_detail($id)
    {
        return $this->select(
            "SELECT
                ordr.tp_id       AS `id`,
                products.`tp_id`   AS `pid`,
                products.`tp_pic`   AS `pic`,
                products.`tp_file`   AS `file`,
                products.`tp_cid`    AS `cid`,
                products.`tp_title`    AS `product_title`,
                order_products.`tp_amount`  AS `product_price`,
                ordr.`tp_total_price`  AS `total_price`,
                ordr.`tp_mid`    AS `mid`,
                ordr.`tp_serial` AS `serial`,
                ordr.`tp_status` AS `status`,
                ordr.`tp_transaction_id`   AS `transaction_id`,
                ordr.`tp_type`   AS `type`,
                ordr.`tp_discount`   AS `discount`,
                products.`tp_desc`   AS `desc`,
                designer.`tp_id`    AS `designer_id`,
                members.`tp_pic`    AS `member_pic`,
                members.`tp_mobile`    AS `member_mobile`,
                cat.tp_title        AS `category`,
                CONCAT(
                    designer.tp_name,
                        ' ',
                    designer.tp_family
                )                   AS `user_name`,
                CONCAT(
                    members.tp_name,
                        ' ',
                    members.tp_family
                )                   AS `member_name`,
                ordr.tp_date     AS createAt
            FROM
                `tp_order`  AS ordr  INNER JOIN
                `tp_order_products`  AS order_products      ON(order_products.tp_oid = ordr.tp_id ) INNER JOIN
                `tp_product`  AS products      ON(products.tp_id = order_products.tp_pid ) INNER JOIN
                `tp_members`  AS designer  ON(designer.tp_id =products.tp_mid)  INNER JOIN
                `tp_category` AS cat      ON(cat.tp_id = products.tp_cid ) INNER JOIN
                `tp_members`  AS members  ON(members.tp_id =ordr.tp_mid)
            WHERE
                ordr.`tp_delete` = 0 AND ordr.tp_id = $id
            ORDER BY ordr.tp_id DESC
        "
        );
    }


    public function changeStatus($status, $id)
    {
        return $this->update("
            UPDATE
                `tp_order`
            SET
                `tp_status` = '{$status}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
}
