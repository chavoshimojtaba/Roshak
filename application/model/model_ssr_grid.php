<?php


if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_ssr_grid extends DB
{

    private function queryFilters($params, $tables = [], $filters = [])
    {
        $q = [];
        $date = '';
        $order = ' ORDER BY __table__.tp_id DESC';
        $sort = [
            'statistic_product'       => '__table__.tp_statistic_product ',
            'downloads'       => '__table__.tp_statistic_downloads ',
            'views'       => '__table__.tp_statistic_view ',
            'rates'       => '__table__.tp_statistic_rate ',
            'new'         => '__table__.tp_id ',
            'new_product'         => '__table__.tp_change_status_date ',
            'createAt'    => '__table__.tp_date ',
        ];
        if (isset($params['sort']) && isset($sort[$params['sort']])) {
            $params['sort_type'] = (isset($params['sort_type']) && $params['sort_type'] != null) ? $params['sort_type'] : 'DESC';
            $order = ' ORDER BY ' . $sort[$params['sort']] . $params['sort_type'];
        }

        if (isset($params['q'])  && $params['q'] != '') {
            $types = [
                'date'          => $date,
                'full_name'     => " AND (__table__.tp_family   LIKE '%{$params['q']}%' OR __table__.tp_name  LIKE '%{$params['q']}%') ",
                'title'     => " AND (__table__.tp_title   LIKE '%{$params['q']}%' ) ",
                'name'     => " AND (__table__.tp_name   LIKE '%{$params['q']}%' ) ",
            ];
            foreach ($tables as $key => $fields) {
                if (!is_array($fields)) {
                    if (isset($types[$fields])) {
                        $q[$fields] = str_replace('__table__', $key, $types[$fields]);
                    }
                } else {
                    foreach ($fields as $field) {
                        if (isset($types[$field])) {
                            $q[$field] = str_replace('__table__', $key, $types[$field]);
                        }
                    }
                }
            }
        }
        if (count($filters)) {
            foreach ($filters as $key => $value) {
                if (array_key_exists($key, $params)) {
                    if(is_string($params[$key]) &&   !trim($params[$key])){
                        continue;
                    }
                    $item = $filters[$key];

                    if (is_array($item)) {
                        $item['operator'] = isset($item['operator'])?$item['operator']:' AND ';
                        // //pr($item,true);
                        if(is_array($params[$key]) ){
                            $sec = [];
                            foreach ($params[$key] as $k => $v) {
                                $sec[] = $item['field']." ".$item['condition']." '".$v."' ";
                            }
                            $q[] = " " . $item['operator'] . " (" . implode(' OR ',$sec) . ")";
                        }else{
                            $q[] = $item['operator']." (".$item['field']." ".$item['condition']." '" . $params[$key] . "' )";
                        }
                    } else {
                        $q[] = " AND (" .$filters[$key] . "'" . $params[$key] . "')";
                    }
                }
            }
        }
        $order = str_replace('__table__', array_keys($tables)[0], $order);
        return [implode(' ', $q), $order];
    }

    public function designer($params)
    {
        list($q, $order) = $this->queryFilters($params, [
            'designer' => [],
            'member'   => ['full_name']
        ]);
        if (isset($params['expertise']) && $params['expertise']) {
            $q .= " AND designer.`tp_expertise` LIKE  '%_{$params['expertise']}_%'";
        }

        $res = $this->pager(
            "SELECT
                designer.`tp_statistic_follower` AS `statistic_follower`,
                designer.`tp_statistic_product`  AS `statistic_product`,
                designer.`tp_statistic_view`     AS `statistic_view`,
                designer.`tp_statistic_rate`     AS `statistic_rate`,
                designer.`tp_expertise`          AS `expertise`,
                DATE(member.tp_date)             AS `createAt`,
                member.`tp_slug`                 AS `slug`,
                member.tp_id                     AS `id`,
                CONCAT( member.tp_name,' ',
                    member.tp_family )             AS `full_name`,
                member.tp_pic                    AS `avatar`
            FROM
                `tp_members`   AS `member`   INNER JOIN
                `tp_designers` AS `designer` ON(designer.tp_mid = member.tp_id)
            WHERE
                member.tp_delete = 0  AND designer.tp_show = 'yes'    {$q}
                $order
        ",
            ($params['limit']) ? $params['limit'] : 10,
            ($params['page']) ? $params['page'] : 1,
            true
        );

        if ($res->count > 0) {
            // pr($res->result,true);
            $expertise = expertise();
            foreach ($res->result as &$value) {
                $value['full_name'] = decode_html_tag($value['full_name'], true);
                $value['createAt']     = g2p($value['createAt']);
                $ex = explode('-',$value['expertise']);
                $expertise_id = str_replace('_','',$ex[0]);
                if(isset($expertise[$expertise_id])){ 
                    $value['expertise'] = $expertise[$expertise_id]['title'];
                }else{
                    $value['expertise'] = ''; 
                }
               
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function notification($params)
    {

        $res = $this->pager(
            "SELECT
                notifications.tp_id     AS id,
                notifications.tp_title AS `title`,
                notifications.tp_read  AS `read`,
                notifications.tp_type  AS `type`,
                notifications.tp_text  AS `text`,
                notifications.tp_date   AS createAt
            FROM
                tp_notifications AS  notifications
            WHERE
                notifications.tp_delete = 0 AND notifications.tp_mid = {$_SESSION['mid']}
            ORDER BY
                notifications.tp_id DESC
        ",
            ($params['limit']) ? $params['limit'] : 10,
            ($params['page']) ? $params['page'] : 1,
            true
        );

        if ($res->count > 0) {
            foreach ($res->result as &$value) {
                $value['text']     = decode_html_tag($value['text'], true);
                $value['createAt'] = g2p($value['createAt']);
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function ticket($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        // //pr($params,true);
        list($q, $order) = $this->queryFilters(
            $params,
            ['tickets' => ['date']],
            [
                'date' => ' DATE(tickets.tp_date)=',
                'status' => ' tickets.tp_status='
            ]
        );
        // //pr($q,true);

        $res = $this->pager(
            "SELECT
                tickets.tp_id AS id,
                tickets.tp_title AS title,
                tickets.tp_priority AS priority,
                tickets.tp_status AS `status`,
                tickets.tp_referred AS `referred`,
                tickets.tp_number AS `number`,
                comments.tp_read AS `read`,
                comments.tp_date AS last_update,
                comments.tp_user_type AS coomment_user_type ,
                tickets.tp_rid AS rid,
                roles.tp_name as `role`
            FROM
                tp_tickets tickets INNER JOIN
                tp_ticket_comments comments ON ( comments.tp_tid = tickets.tp_id ) INNER JOIN
                tp_role roles ON(roles.tp_id = tickets.tp_rid)
            WHERE
                tickets.tp_delete = 0 AND tickets.tp_mid = {$_SESSION['mid']}
                AND comments.tp_id = (SELECT tp_id from tp_ticket_comments where tp_delete = 0 and tp_tid = tickets.tp_id ORDER BY tp_id DESC limit 1) {$q}

                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        $p = ($params['page'] - 1) * $params['limit'];
        if ($res->count > 0) {
            $status = ['open' => 'در صف بررسی', 'close' => 'بسته شده', 'unread' => 'پیام جدید دارید'];
            $color = ['open' => 'primary', 'close' => 'secondary'];
            foreach ($res->result as $key => &$value) {
                $value['last_update']     = g2pt($value['last_update']);
                $value['key'] = ($key + 1) + $p;
                if ($value['status'] == 'close') {
                    $value['color'] = $color['close'];
                    $value['status'] = $status['close'];
                } else if ($value['read'] == 'no') {
                    $value['status'] = $status['unread'];
                    $value['color'] = $color['open'];
                } else {
                    $value['status'] = $status['open'];
                    $value['color'] = $color['open'];
                }
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function product($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        list($q, $order) = $this->queryFilters(
            $params,
            ['product' => ['downloads','view','title']],
            [
                'filetype'   => ['field' => ' category.tp_filetype ', 'condition' => 'LIKE'],
                'dimensions'  => ['field' => ' product.tp_dimensions ', 'condition' => '='],
                'color_mode'  => ['field' => ' product.tp_color_mode ', 'condition' => '='],
                'resulation'  => ['field' => ' product.tp_resulation ', 'condition' => '='],
                'date'     => '   DATE(product.tp_date)=',
                'format'      => ['field' => ' product.tp_format ', 'condition' => '='],
                'layer'       => ['field' => ' product.tp_layer ', 'condition' => '='], 
                'mid'         => ' product.tp_mid= ',
            ]
        );
        $join = '';
        if((isset($params['cid']) && $params['cid']>0) || (isset($params['cat_id']) && $params['cat_id']>0)){
            $cid = isset($params['cid'])?$params['cid']:$params['cat_id'];
            $q .= " AND product.`tp_cat_path` LIKE '%\_".$cid."\_%'   " ;
        }
        if(!isset($params['favorite'])){
            if (isset($params['is_premium'])) {
                $q .= ($params['is_premium'] == 'free') ? ' AND product.`tp_price` <= 0 ' : ' AND product.`tp_price` > 0 ';
            }
            $join = " `tp_product` AS product INNER JOIN ";
        }else{ 
            $join = " `tp_member_favorites` AS favorites INNER JOIN `tp_product` AS product ON(product.tp_id = favorites.tp_pid AND favorites.tp_delete = 0) INNER JOIN ";
            $q .= " AND favorites.`tp_mid` = ".$_SESSION['mid']." " ;
        } 
        if(isset($params['tid'])){
            $join_after = "   INNER JOIN `tp_product_tags_map` AS product_tags  ON(product.tp_id = product_tags.tp_pid AND product_tags.tp_delete = 0 AND product_tags.tp_tid = {$params['tid']})";
        }else{
            $join_after = "";
        }
        if(!isset($params['status'])){
            $q .= "  AND product.tp_status = 'accept' ";
        } else if($params['status'] !== 'all'){
            $q .= "  AND product.tp_status = '".$params['status']."' ";
        }
        if(isset($params['designer_show_all'])){
            $q .= "";
        }else{
            $q .= "  AND designer.tp_show = 'yes' ";
        }
        if(isset($params['mids'])  && $params['mids'] !== '' ){
            if(is_array($params['mids'])){
                $params['mids'] = implode(',', $params['mids']);
            }
            $q .=" AND product.tp_mid  IN ({$params['mids']})" ;
        }
        if(isset($params['sort'])){
            if($params['sort'] == 'new' || !$params['sort']){
                $sort_type = isset($params['sort_type'])?$params['sort_type']:'DESC';
                $order = 'ORDER BY product.tp_change_status_date '.$sort_type;
            }
        } 
        if(!isset($params['sort']) || (isset($params['sort']) && $params['sort'] === 'date')){
            $sort_type = isset($params['sort_type'])?$params['sort_type']:'DESC';
            $order = 'ORDER BY product.tp_change_status_date '.$sort_type; 
        } 
        $res = $this->pager(
            " SELECT
                product.tp_id       AS `id`,
                product.`tp_mid`    AS `mid`,
                product.`tp_cid`    AS `cid`,
                product.`tp_title`  AS `title`,
                product.`tp_status` AS `status`,
                member.`tp_pic`     AS `avatar`,
                designer.`tp_statistic_product`    AS `statistic_product`,
                member.`tp_slug`    AS `member_slug`,
                product.`tp_slug`   AS `slug`,
                product.`tp_price`  AS `price`,
                product.`tp_serial` AS `serial`,
                product.`tp_pic`    AS `img`,
                CONCAT(
                    member.tp_name,
                    ' ',
                    member.tp_family ) AS `full_name`,
                category.`tp_title`    AS `category`,
                product.`tp_date`      AS `createAt`
            FROM
                $join
                `tp_category`  AS category ON(category.tp_id = product.tp_cid ) INNER JOIN
                `tp_members`   AS member  ON(member.tp_id = product.tp_mid) INNER JOIN
                `tp_designers` AS designer  ON(designer.tp_mid = member.tp_id) $join_after
            WHERE
                product.`tp_delete` = 0  $q
                GROUP BY product.tp_id
                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        if ($res->count > 0) {
            $p = isset($params['product_url'])?'dashboard/product':'p';
            foreach ($res->result as $key => &$value) {
                $value['width']  = 300;
                $value['page']   = $p; 
                $value['height'] = 214;
                $value['img'] =  thumbnail($value['img'],300);
            }
        } 
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function favorite($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        list($q, $order) = $this->queryFilters(
            $params,
            ['product' => ['title']],
            [ 
                'date'     => '   DATE(product.tp_date)=' 
            ]
        ); 
        $join = " `tp_member_favorites` AS favorites INNER JOIN `tp_product` AS product ON(product.tp_id = favorites.tp_pid AND favorites.tp_delete = 0) INNER JOIN ";
        $q .= " AND favorites.`tp_mid` = ".$_SESSION['mid']." " ;
         
        $q .= "  AND product.tp_status = 'accept'  AND designer.tp_show = 'yes' ";
        if((isset($params['cid']) && $params['cid']>0) || (isset($params['cat_id']) && $params['cat_id']>0)){
            $cid = isset($params['cid'])?$params['cid']:$params['cat_id'];
            $q .= " AND product.`tp_cat_path` LIKE '%\_".$cid."\_%'   " ;
        }
        if(isset($params['sort'])){
            if($params['sort'] == 'new' || !$params['sort']){
                $sort_type = isset($params['sort_type'])?$params['sort_type']:'DESC';
                $order = 'ORDER BY product.tp_change_status_date '.$sort_type;
            }
        } 
        if(!isset($params['sort']) || (isset($params['sort']) && $params['sort'] === 'date')){
            $sort_type = isset($params['sort_type'])?$params['sort_type']:'DESC';
            $order = 'ORDER BY favorites.tp_date '.$sort_type; 
        } 
        $res = $this->pager(
            " SELECT
                favorites.tp_id       AS `id`,
                product.`tp_mid`    AS `mid`,
                product.`tp_cid`    AS `cid`,
                product.`tp_title`  AS `title`,
                product.`tp_status` AS `status`,
                member.`tp_pic`     AS `avatar`,
                designer.`tp_statistic_product`    AS `statistic_product`,
                member.`tp_slug`    AS `member_slug`,
                product.`tp_slug`   AS `slug`,
                product.`tp_price`  AS `price`,
                product.`tp_serial` AS `serial`,
                product.`tp_pic`    AS `img`,
                CONCAT(
                    member.tp_name,
                    ' ',
                    member.tp_family ) AS `full_name`,
                category.`tp_title`    AS `category`,
                product.`tp_date`      AS `createAt`
            FROM
                $join
                `tp_category`  AS category ON(category.tp_id = product.tp_cid ) INNER JOIN
                `tp_members`   AS member  ON(member.tp_id = product.tp_mid) INNER JOIN
                `tp_designers` AS designer  ON(designer.tp_mid = member.tp_id) 
            WHERE
                product.`tp_delete` = 0  $q
                GROUP BY product.tp_id
                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        if ($res->count > 0) {
            $p = isset($params['product_url'])?'dashboard/product':'p';
            foreach ($res->result as $key => &$value) {
                $value['width']  = 300;
                $value['page']   = $p; 
                $value['height'] = 214;
                $value['img'] =  thumbnail($value['img'],300);
            }
        } 
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function comment($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        //pr($params,true);
        list($q, $order) = $this->queryFilters(
            $params,
            ['comments' => []],
            [
                'pbid'     => ' comments.tp_pbid=',
                'date'     => '   DATE(comments.tp_date)=',
                'publish'  => ['field' => '   comments.tp_publish ', 'condition' => 'LIKE'],
            ]
        );
        if (isset($params['send_type'])) {
            if($params['send_type'] === 'send'){
                $q .= " AND comments.tp_mid = {$_SESSION['mid']}";
            }else{
                $q .= " AND comments.tp_parent_mid = {$_SESSION['mid']}";
            }
        }else if(!isset($params['pbid'])){

            $q .= " AND (comments.tp_mid = {$_SESSION['mid']} || comments.tp_parent_mid = {$_SESSION['mid']}) ";
        }
        $res = $this->pager(
            "SELECT
                comments.`tp_id` AS id,
                comments.`tp_mid` AS mid,
                comments.`tp_publish` AS publish,
                comments.`tp_pbid` AS pbid,
                comments.`tp_pid` AS pid,
                comments.`tp_rate` AS rate,
                products.`tp_slug` AS product_slug,
                products.`tp_pic` AS product_pic,
                products.`tp_title` AS product_name,
                comments.`tp_rate` AS rate,
                members.tp_pic  AS `img`,
                CONCAT( members.tp_name, ' ', members.tp_family )  AS `full_name`,
                CONCAT( members_parent.tp_name, ' ', members_parent.tp_family )  AS `parent_full_name`,
                comments.`tp_text` AS `text`,
                comments.tp_date AS createAt
            FROM
                tp_product_comments AS comments
                INNER JOIN `tp_product` AS products        ON ( products.tp_id = comments.tp_pbid )
                INNER JOIN `tp_members` AS members        ON ( members.tp_id = comments.tp_mid )
                LEFT JOIN `tp_members`  AS members_parent ON ( members_parent.tp_id = comments.tp_parent_mid )
            WHERE
                comments.`tp_delete` = 0
                $q
                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        if ($res->count > 0) {
            $publish_text = ['pend' => 'در صف بررسی', 'publish' => 'منتشر شده', 'reject' => 'رد شده'];
            $publish_color = ['pend' => 'secondary', 'publish' => 'success', 'reject' => 'danger'];
            foreach ($res->result as &$value) {
                $value['createAt']     = g2pt($value['createAt']);
                if ($value['mid'] != $_SESSION['mid']) {
                    $value['answer_btn'] = '';
                    $value['publish_text'] = 'نظر دریافتی';
                    $value['publish_color'] = 'primary';
                }else{
                    if($value['publish'] == 'reject'){
                        $value['danger_border'] = 'border border-danger';
                    }else{
                        $value['danger_border'] = ' ';
                    }
                    $value['answer_btn'] = 'd-none';
                    $value['publish_text'] =$publish_text[$value['publish']];
                    $value['publish_color'] =$publish_color[$value['publish']];
                }
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function settlement_requests($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        // //pr($params,true);

        list($q, $order) = $this->queryFilters(
            $params,
            ['settlement_requests' => ['date']],
            [
                'date' => ' DATE(settlement_requests.tp_date)=',
                'status' => ' settlement_requests.tp_status='
            ]
        );
        // //pr($q,true);
        $res = $this->pager(
            "SELECT
                settlement_requests.tp_id AS id,
                settlement_requests.tp_desc AS `desc`,
                settlement_requests.tp_date AS createAt,
                settlement_requests.tp_reply AS reply,
                settlement_requests.tp_status AS `status`
            FROM
                tp_member_settlement_requests settlement_requests
            WHERE
                settlement_requests.tp_delete = 0 AND settlement_requests.tp_mid = {$_SESSION['mid']} {$q}
                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        if ($res->count > 0) {
            $status = ['pend' => 'در صف بررسی', 'reject' => 'رد شده', 'accept' => 'واریز شده'];
            $color = ['pend' => 'primary', 'reject' => 'danger','accept'=>'info'];
            foreach ($res->result as $key => &$value) {
                $value['createAt'] = g2pt($value['createAt']);
                $value['color'] = $color[$value['status']];
                $value['status'] = $status[$value['status']];
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function download($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        list($q, $order) = $this->queryFilters(
            $params,
            ['ordrs'=>['type'],'product' => [ 'title']],[ 
                'date'     => '   DATE(ordrs.tp_date)=',
            ]
        );
        if((isset($params['cid']) && $params['cid']>0) || (isset($params['cat_id']) && $params['cat_id']>0)){
            $cid = isset($params['cid'])?$params['cid']:$params['cat_id'];
            $q .= " AND product.`tp_cat_path` LIKE '%\_".$cid."\_%'   " ;
        }
        // pr($q,true);
        $res = $this->pager(
            " SELECT
                product.tp_id       AS `id`,
                product.`tp_mid`    AS `mid`,
                product.`tp_cid`    AS `cid`,
                product.`tp_title`  AS `title`,
                product.`tp_file`   AS `file`,
                ordrs.`tp_type`     AS `buy_type`,
                product.`tp_status` AS `status`,
                member.`tp_pic`     AS `avatar`,
                member.`tp_slug`    AS `member_slug`,
                product.`tp_slug`   AS `slug`,
                product.`tp_price`  AS `price`,
                product.`tp_serial` AS `serial`,
                product.`tp_pic`    AS `img`,
                CONCAT(
                    member.tp_name,
                    ' ',
                    member.tp_family ) AS `full_name`,
                category.`tp_title`    AS `category`,
                category.`tp_slug`     AS `category_slug`,
                DATE(ordrs.`tp_date`)  AS `createAt`
            FROM
                tp_order as ordrs INNER JOIN
                `tp_order_products`  AS order_products ON(order_products.tp_oid = ordrs.tp_id AND ordrs.tp_status = 'done' AND ordrs.tp_mid = {$params['mid']}) INNER JOIN
                `tp_product` AS product  ON(product.tp_id = order_products.tp_pid) INNER JOIN
                `tp_category`  AS category ON(category.tp_id = product.tp_cid ) INNER JOIN
                `tp_members`   AS member  ON(member.tp_id = product.tp_mid)
            WHERE
                product.`tp_delete` = 0  $q
                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        if ($res->count > 0) {
            $buy_type = ['subscription' => 'اشتراکی', 'bank' => 'بانک'];
            $buy_type_class = ['subscription' => 'danger', 'bank' => 'info'];
            foreach ($res->result as $key => &$value) { 

                $value['buy_type_class'] = $buy_type_class[$value['buy_type']]; 
                $value['buy_type'] = $buy_type[$value['buy_type']]; 
                $value['createAt'] = g2pt($value['createAt']); 
                $value['width'] = 300;
                $value['height'] = 214;
                $value['img'] =  thumbnail($value['img'],300);
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

    public function plan($params)
    {
        $params['page'] = ($params['page']) ? $params['page'] : 1;
        $params['limit'] = ($params['limit']) ? $params['limit'] : 1;
        list($q, $order) = $this->queryFilters(
            $params,
            ['member_plan' => [ 'id','date'],'plan' => [ 'title']],
            [
                'date' => ' DATE(member_plan.tp_start_date)='
            ]

        );
        // pr($q,true);
        $res = $this->pager(
            " SELECT
                member_plan.tp_id       AS `id`,
                member_plan.`tp_start_date`    AS `start_date`,
                member_plan.`tp_end_date`    AS `end_date`,
                member_plan.`tp_status` AS `status`,
                plan.`tp_title`   AS `title`,
                plan.`tp_price`  AS `price`,
                plan.`tp_off` AS `off`,
                member_plan.`tp_date`      AS `createAt`
            FROM
                `tp_plan` AS plan   INNER JOIN
                tp_member_plan as member_plan ON(member_plan.tp_plan_id = plan.tp_id)
            WHERE
                member_plan.`tp_mid` =  {$_SESSION['mid']} AND member_plan.`tp_status` <> 'pend' $q
                $order
            ",
            $params['limit'],
            $params['page'],
            true
        );
        if ($res->count > 0) {
            $status = ['pend' => 'عدم پرداخت','active' => 'فعال', 'ended' => ' منقضی شده', 'reserve' => ' رزرو شده'];
            $color = ['active' => 'primary','reserve' => 'warning', 'pend' => 'danger', 'ended' => 'danger' ];
            foreach ($res->result as $key => &$value) {
                $value['end_date'] = g2pt($value['end_date']);
                $value['start_date'] = g2pt($value['start_date']);
                $value['createAt'] = g2pt($value['createAt']);
                $value['color'] = $color[$value['status']];
                $value['status'] = $status[$value['status']];
            }
        }
        return ['total' => $res->total, 'count' => $res->count, 'result' => $res->result];
    }

}
