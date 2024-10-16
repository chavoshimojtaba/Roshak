<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_financial extends DB
{
    
    public function transaction_detail($id)
    { 
        return $this->select(
            "SELECT
                `transactions`.`tp_tracking_code`         AS `tracking_code`,
                `transactions`.`tp_bank_message` AS `bank_message`,
                `transactions`.`tp_bank_code` AS `bank_code`,
                `transactions`.`tp_token` AS `token`,
                `transactions`.`tp_amount` AS `total_price`,
                `transactions`.`tp_status`         AS `status`,
                `transactions`.`tp_type`         AS `type`, 
                `transactions`.`tp_date`            AS `createAt`
            FROM
                `tp_transaction`  AS transactions   
            WHERE
                `transactions`.`tp_delete` = 0    AND transactions.tp_id = $id
        "
        );
    }

    public function report($params)
    {
        $q = '';
        if (isset($params['status'])  && $params['status'] != 'all') {
            $q  .=  " AND ordr.tp_status = '{$params['status']}'";
        }
        if (isset($params['date_from'])  && $params['date_from']  !== '') {
            $q  .=  " AND ordr.tp_date > '{$params['date_from']}'";
        }
        if (isset($params['date_to'])  && $params['date_to']  !== '') {
            $q  .=  " AND ordr.tp_date < '{$params['date_to']}'";
        }
        if (isset($params['cid'])  && $params['cid']  > 0) {
            $q  .=  " AND products.tp_cat_path LIKE '%\_".$params['cid']."\_%' ";
        }
        if (count($params['members']) > 0) {
            $keys = array_keys($params['members']);
            $members = implode(',', $keys);
            $q  .=  " AND member.tp_id IN({$members})";
        }
        if (isset($params['type'])  && $params['type']  !== 'special') {
            if ($params['type']  == 'special') {
                if (count($params['members']) > 0) {
                    $keys = array_keys($params['members']);
                    $members = implode(',', $keys);
                    $q  =  " AND ordr.tp_mid IN({$members})";
                }
            } else if ($params['type']  == 'common') {
                $q  .=  " AND member.tp_type = '{$params['type']}'";
            }
        }
        if (isset($params['pid'])  && count($params['pid']) > 0) {
            $keys = array_keys($params['pid']);
            $pid = implode(',', $keys);
            $q  .=  " AND order_products.tp_pid IN({$pid})";
        } 
        // //pr($q,true);
        return $this->select(
            "SELECT
                `ordr`.`tp_type`            AS `type`,
                `ordr`.`tp_serial`          AS `serial`,
                `ordr`.`tp_transaction_id`  AS `transaction_id`,
                `ordr`.`tp_total_price`     AS `total_price`,
                `ordr`.`tp_desc`            AS `desc`,
                `ordr`.`tp_discount`        AS `discount`,
                `ordr`.`tp_discount_code`   AS `discount_code`,
                `ordr`.`tp_status`          AS `status`,
                `member`.`tp_id`            AS `mid`,
                `designer`.`tp_slug`        AS `member_slug`,
                products.`tp_slug`          AS `product_slug`,
                products.`tp_title`         AS `product_title`,
                order_products.`tp_amount`  AS `product_price`,
                CONCAT(
                    member.tp_name
                    ,' ',
                    member.tp_family 
                )  		                    AS `member`,
                CONCAT(
                    designer.tp_name,
                        ' ',
                    designer.tp_family
                )                           AS `designer`,
                `ordr`.`tp_date`            AS `createAt`
            FROM
                `tp_order`          AS `ordr`    INNER JOIN
                `tp_order_products` AS order_products      ON(order_products.tp_oid = ordr.tp_id ) INNER JOIN
                `tp_product`        AS products      ON(products.tp_id = order_products.tp_pid ) INNER JOIN
                `tp_members`        AS designer  ON(designer.tp_id =products.tp_mid)  INNER JOIN
                `tp_members`        AS member  ON(`member`.tp_id = `ordr`.tp_mid)
            WHERE
                `ordr`.`tp_delete` = 0    $q  GROUP BY ordr.tp_id
        "
        );
    }

    public function plan_report($params)
    {
        $q = '';
        if (isset($params['status'])  && $params['status'] != 'all') {
            $q  .=  " AND member_plan.tp_status = '{$params['status']}'";
        }
        if (isset($params['date_from'])  && $params['date_from']  !== '') {
            $q  .=  " AND member_plan.tp_date > '{$params['date_from']}'";
        }
        if (isset($params['date_to'])  && $params['date_to']  !== '') {
            $q  .=  " AND member_plan.tp_date < '{$params['date_to']}'";
        }
        if (count($params['members']) > 0) {
            $keys = array_keys($params['members']);
            $members = implode(',', $keys);
            $q  =  " AND member_plan.tp_mid IN({$members})";
        }
        return $this->select(
            "SELECT
                plan.`tp_title`         AS `plan`,
                `transactions`.`tp_amount` AS `total_price`,
                `member_plan`.`tp_status`         AS `status`,
                CONCAT(member.tp_name,' ',
                member.tp_family )  		AS `member`,
                `member_plan`.`tp_date`            AS `createAt`
            FROM
                `tp_member_plan`    AS `member_plan`    INNER JOIN
                `tp_plan`  AS plan      ON(plan.tp_id = member_plan.tp_plan_id ) INNER JOIN
                `tp_transaction`  AS transactions      ON(transactions.tp_id = member_plan.tp_transaction_id AND  `transactions`.`tp_type` = 'subscription' ) INNER JOIN
                `tp_members`  AS member  ON(`member`.tp_id = `member_plan`.tp_mid)
            WHERE
                `transactions`.`tp_delete` = 0 AND `member_plan`.`tp_status` <> 'pend'  $q  GROUP BY member_plan.tp_id 
        "
        );
    }

    public function transaction_report($params)
    {
        $q = '';
        if (isset($params['status'])  && $params['status'] != 'all') {
            $q  .=  " AND transactions.tp_status = '{$params['status']}'";
        }
        if (isset($params['type'])  && $params['type'] != 'all') {
            $q  .=  " AND transactions.tp_type = '{$params['type']}'";
        }
        if (isset($params['date_from'])  && $params['date_from']  !== '') {
            $q  .=  " AND transactions.tp_date > '{$params['date_from']}'";
        }
        if (isset($params['date_to'])  && $params['date_to']  !== '') {
            $q  .=  " AND transactions.tp_date < '{$params['date_to']}'";
        }
        if (isset($params['tracking_code'])  && $params['tracking_code']  !== '') {
            $q  .=  " AND transactions.tp_tracking_code = '{$params['tracking_code']}'";
        }
        if (count($params['members']) > 0) {
            $keys = array_keys($params['members']);
            $members = implode(',', $keys);
            $q  .=  " AND transactions.tp_mid IN({$members})";
        }
        return $this->select(
            "SELECT
                transactions.`tp_tracking_code`         AS `tracking_code`,
                `transactions`.`tp_bank_message` AS `bank_message`,
                `transactions`.`tp_bank_code` AS `bank_code`,
                `transactions`.`tp_token` AS `token`,
                `transactions`.`tp_amount` AS `total_price`,
                `transactions`.`tp_status`         AS `status`,
                `transactions`.`tp_type`         AS `type`,
                CONCAT(member.tp_name,' ',
                member.tp_family )  		AS `member`,
                `transactions`.`tp_date`            AS `createAt`
            FROM
                `tp_transaction`  AS transactions       INNER JOIN
                `tp_members`  AS member  ON(`member`.tp_id = `transactions`.tp_mid)
            WHERE
                `transactions`.`tp_delete` = 0  $q  GROUP BY transactions.tp_id
        "
        );
    }

    /* -------------------------------------------------------------------------- */
    /*                                    wallet                                 */
    /* -------------------------------------------------------------------------- */

    public function member_wallet($mid)
    {

        $data =  $this->select(
            "SELECT
                SUM(IF(ordr.tp_settled = 'no', ordr.tp_designer_share, 0)) as `unsettled`,
                SUM(ordr.tp_designer_share) as `total_income` ,
                COUNT(ordr.tp_id)  as `cnt`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                `tp_product`  AS products ON(products.tp_id = order_products.tp_pid AND `products`.`tp_mid` = $mid )
            WHERE
                `ordr`.`tp_delete` = 0 AND  `ordr`.`tp_status` = 'done'
        "
        )->result[0];
        $data['mounth_income'] = $this->select(
            "SELECT
                SUM(ordr.tp_designer_share) as `mounth_income`
            FROM
                `tp_order`    AS `ordr`    INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                `tp_product`  AS products ON(products.tp_id = order_products.tp_pid AND `products`.`tp_mid` = $mid )
            WHERE
                `ordr`.`tp_delete` = 0 AND  `ordr`.`tp_status` = 'done' AND `ordr`.`tp_date`  > DATE_SUB(NOW(), INTERVAL  30 DAY);
        "
        )->result[0]['mounth_income'];
        return $data;
    }

    public function designer_share()
    {

        return $this->select(
            "SELECT
                designer.tp_single_product as `single_product`,
                designer.tp_subscription as `subscription`
            FROM
                `tp_designer_share` AS designer
            WHERE
				designer.`tp_delete` = '0'
        "
        )->result[0];
    }

    public function update_designer_share($data)
    {
        $this->update("
            UPDATE
                `tp_designer_share`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_delete` = '0'
        ");
        return $this->insert("
            INSERT INTO
                `tp_designer_share`
            SET
                `tp_single_product` = '{$data['single_product']}',
                `tp_subscription`  = '{$data['subscription']}',
                tp_uid   = '{$_SESSION['uid']}',
                tp_date  = now()
        ");
    }

    public function has_settlement_request($mid)
    {

        return $this->select(
            "SELECT
                settlement_requests.tp_id as id
            FROM
                `tp_member_settlement_requests` AS settlement_requests
            WHERE
				settlement_requests.`tp_delete` = '0' and settlement_requests.`tp_status` = 'pend' and settlement_requests.tp_mid = $mid
        "
        )->count;
    }
    
    public function add_settlement_requests($post)
    {
        return $this->insert("INSERT INTO
                `tp_member_settlement_requests`
            SET
                tp_mid    = '{$post['mid']}',
                tp_desc       = '{$post['desc']}',
                tp_date       = now()
        ");
    } 

    /* -------------------------------------------------------------------------- */
    /*                                discount_code                               */
    /* -------------------------------------------------------------------------- */ 

    public function discount_codes_list($params = [])
    {
        list($q, $order) = create_query_filters($params, [
            'codes' => ['title', 'code', 'status', 'createAt'],
            'member' => ['full_name']
        ]); 
        return $this->pager(
            "SELECT
                codes.tp_id         AS `id`,
				codes.tp_desc       AS `desc`,
                codes.tp_status     AS `status`,
                codes.tp_code       AS `code`,
                codes.tp_type       AS `type`,
                codes.tp_percent    AS `percent`,
                codes.tp_date       AS `createAt`,
                codes.tp_date_start AS `date_start`,
                IF(codes.tp_date_end < DATE(now()), 'yes','pend') as expired,
                codes.tp_date_end   AS `date_end`,
                codes.tp_mid        AS `mid`,
                codes.tp_title      AS `title`,
				CONCAT( member.tp_name,
				' ', member.tp_family )   AS `full_name`
            FROM
				`tp_discount_codes`        AS codes
				LEFT JOIN `tp_members` AS member ON ( member.`tp_id` = codes.`tp_mid` )
            WHERE
				codes.`tp_delete` = '0' $q $order
        ",
            isset($params['limit']) ? $params['limit'] : 10,
            isset($params['page']) ? $params['page'] : 1,
            true
        );
    }

    public function discount_code($code, $mid)
    { 
        return $this->select(
            "SELECT
                codes.tp_code  AS code,
                codes.tp_id  AS id,
                codes.tp_percent  AS percent
            FROM
                `tp_discount_codes` AS codes
            WHERE
				codes.`tp_delete` = 0    AND ((DATE(now()) >= codes.tp_date_start AND DATE(now()) <= codes.tp_date_end)) AND codes.tp_status = 'pend'
                AND (codes.tp_mid = {$mid} OR codes.tp_type = 'public'  )
                AND codes.tp_code = '{$code}'
        "
        );
    }

    public function add_discount_code($post)
    {
        if ($post['type'] == 'public') {
            $res = $this->insert("
                INSERT INTO
                    `tp_discount_codes`
                SET
                    tp_percent    = '{$post['percent']}',
                    tp_use_for       = '{$post['use_for']}',
                    tp_type       = '{$post['type']}',
                    tp_date_start = '{$post['date_start']}',
                    tp_date_end   = '{$post['date_end']}',
                    tp_desc       = '{$post['desc']}',
                    tp_title  	  = '{$post['title']}',
                    tp_code       = '{$post['code']}',
                    tp_date       = now()
            ");
        }else if ($post['type'] == 'member') {
            $members = json_decode($post['members'], true);
            foreach ($members as $key => $value) {
                $res = $this->insert("
					INSERT INTO
						`tp_discount_codes`
					SET
						tp_percent    = '{$post['percent']}',
						tp_type       = '{$post['type']}',
                        tp_use_for    = '{$post['use_for']}',
                        tp_mid        = '{$key}',
						tp_date_start = '{$post['date_start']}',
						tp_date_end   = '{$post['date_end']}',
						tp_desc       = '{$post['desc']}',
						tp_title  	  = '{$post['title']}',
						tp_code       = '{$post['code']}',
						tp_date       = now()
				");
            }
        } else {
            $res = $this->insert("
				INSERT INTO
					`tp_discount_codes`
				SET
					tp_percent    = '{$post['percent']}',
					tp_type       = '{$post['type']}',
					tp_mid        = '{$post['mid']}',
                    tp_use_for    = '{$post['use_for']}',
                    tp_date_start = '{$post['date_start']}',
					tp_date_end   = '{$post['date_end']}',
					tp_desc       = '{$post['desc']}',
					tp_title  	  = '{$post['title']}',
					tp_code       = '{$post['code']}',
					tp_date       = now()
			");
        }
        return $res;
    }

    public function delete_discount_code($id)
    {
        return $this->update("
            UPDATE
                `tp_discount_codes`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function update_discount_code($data ,$id)
    {
        return $this->update("
            UPDATE
                `tp_discount_codes`
            SET
                tp_percent    = '{$data['percent']}', 
                tp_date_start = '{$data['date_start']}',
                tp_date_end   = '{$data['date_end']}',
                tp_desc       = '{$data['desc']}',
                tp_title  	  = '{$data['title']}', 
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function set_used($id)
    {
        return $this->update("
            UPDATE
                `tp_discount_codes`
            SET
                tp_status    = 'used', 
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    } 





    /* public function get_category_detail ($id)
    {
        return $this->select(
            "SELECT
                    category.tp_id      AS id,
                    category.tp_type    AS `type`,
                    category.tp_pid     AS pid,
                    category.tp_publish AS publish,
                    category.tp_meta    AS meta,
                    category.tp_desc    AS `desc`,
                    category.tp_icon    AS icon,
                    category.tp_slug    AS slug,
                    category.tp_title   AS title,
                    category.tp_date    AS createAt
			FROM
                `tp_category`        AS category
			WHERE
                category.`tp_delete` = '0' AND category.tp_id=$id
        ");
    }

    public function update_category ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_category`
            SET
                tp_slug     = '{$data['slug']}',
                tp_icon     = '{$data['icon']}',
                tp_meta     = '{$data['meta']}',
                tp_desc     = '{$data['desc']}',
                `tp_title`  = '{$data['title']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
 */
}
