<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_member extends DB
{

    public function get_members($params)
    {
        list($q,$order) = create_query_filters($params,[
            'member'=>['date','full_name','status','type','mobile']
        ]); 
        if(isset($params['change_type_request'])  ){
            if($params['change_type_request'] === 'change_type_request'){
                $q .= " AND member.tp_change_type_request='pend' ";
            }else if($params['change_type_request'] === 'confirm'){
                $q .= " AND member.tp_change_type_request='confirm' ";
            }
        }

        if(isset($params['type'])  && ($params['type'] === 'common' || $params['type'] === 'designer')  ){
            $q .= " AND member.tp_type='{$params['type']}' ";
        }

        if(isset($params['mids'])  ){
            $q .= " AND member.tp_id IN({$params['mids']}) ";
        }
        // pr($q,true);
        return $this->pager(
            "SELECT
                member.tp_id                 AS `id`,
                member.tp_mobile             AS `mobile`,
                member.tp_slug               AS `slug`,
                member.tp_email              AS `email`,
                DATE(member.tp_date)         AS `createAt`,
                member.tp_type               AS `type`,
                member.tp_status             AS `status`,
                CONCAT(
                    member.tp_family,
                    ' ',
                    member.tp_name )         AS `full_name`,
                member.tp_pic                AS `img`
            FROM
                tp_members member
            WHERE
                member.tp_delete = 0  {$q} 
                $order
        ",
            ($params['limit'])?$params['limit']:10,
            ($params['page'])?$params['page']:1
        ,true);
    }
    public $query_result = [];

    public function add_product_statistics ($id)
    {
        return $this->update("
            UPDATE
                tp_designers
            SET
                `tp_statistic_product`    = `tp_statistic_product` + 1 ,
                `tp_update`     = now()
            WHERE
                `tp_mid` = '{$id}'
        ");
    }

    public function has_follow($did)
    {
        return $this->select(
            "SELECT
                COUNT(followers.tp_id) AS `cnt`
            FROM
                `tp_designer_followers` AS `followers`
            WHERE
            followers.tp_delete = 0  and followers.tp_did = $did and followers.tp_mid = {$_SESSION['mid']}
        "
        )->result[0]['cnt'];
    }

    public function member_following($mid)
    {
        return $this->select(
            "SELECT
                designer.tp_mid                     AS `did`
            FROM
                `tp_designers`   AS `designer`   INNER JOIN
                `tp_designer_followers` AS `followers` ON(designer.tp_mid = followers.tp_did AND followers.tp_delete = 0  )
            WHERE
                designer.tp_delete = 0  and followers.tp_mid = $mid
        "
        );
    }

    public function settlement_requests ($params=[])
    {
        list($q,$order) = create_query_filters($params,[
            'settlement_requests'=>['date','status','createAt'],
            'member'=>['full_name','mobile'],
        ]);
        return $this->pager(
            "SELECT
                settlement_requests.tp_mid      AS `mid`,
                settlement_requests.tp_id       AS `id`,
                settlement_requests.tp_status   AS `status`,
                settlement_requests.tp_desc     AS `desc`,
                settlement_requests.tp_reply    AS `reply`,
                member.tp_mobile                AS `mobile`,
                member.`tp_pic`                AS `img`,
                CONCAT(
                    member.tp_name,
                        ' ',
                    member.tp_family
                )                               AS `full_name`,
                designer.`tp_bank`                  AS `bank`,
                designer.`tp_sheba`                 AS `sheba`,
                settlement_requests.`tp_date`   AS `createAt`
            FROM
                `tp_member_settlement_requests` AS settlement_requests INNER JOIN
                `tp_members`                    AS member  ON(settlement_requests.tp_mid = member.tp_id  ) INNER JOIN
                `tp_designers` AS `designer`  ON(designer.tp_mid = member.tp_id )
            WHERE
                settlement_requests.`tp_delete` = 0  $q
            $order
        " ,isset($params['limit'])?$params['limit']:10,isset($params['page'])?$params['page']:1,true);
    }

    public function settlement_requests_status  ($id,$data)
    {

        $res =  $this->update("
            UPDATE
                `tp_member_settlement_requests`
            SET
                tp_reply   = '{$data['reply']}',
                tp_status = '{$data['status']}',
                tp_update = now()
            WHERE
                tp_id     = '{$id}'
        ");
        if($res->affected_rows && $data['status'] =='accept'){
            $orders =  $this->select(
                "SELECT
                     ordr.tp_id  as `id`
                FROM
                    `tp_order`    AS `ordr`    INNER JOIN
                    `tp_order_products`  AS order_products ON(order_products.tp_oid = ordr.tp_id )  INNER JOIN
                    `tp_product`  AS products ON(products.tp_id = order_products.tp_pid AND `products`.`tp_mid` = {$data['mid']} )
                WHERE
                    `ordr`.`tp_delete` = 0 AND  `ordr`.`tp_status` = 'done' AND ordr.tp_settled = 'no'
            ");
            // //pr($data,true);
            if($orders->count>0){
                foreach ($orders->result as $value) {
                    $this->update("
                        UPDATE
                            `tp_order`
                        SET
                            tp_settled   = 'yes',
                            tp_update = now()
                        WHERE
                            tp_id     = '{$value['id']}'
                    ");
                }
            }
        }
        return $res;
    }

    public function is_favorite($id,$mid)
    {
        return $this->select(
            "SELECT
                favorites.tp_id
            FROM
                `tp_member_favorites`  AS favorites
            WHERE
                favorites.tp_delete = 0  AND favorites.tp_mid = $mid AND favorites.tp_pid = $id
        ")->count;
    }

    public function search ($params)
    {
        $whr ='';
        if(isset($params['q'])){
            $whr =" AND members.tp_family  LIKE '%{$params['q']}%' OR members.tp_name  LIKE '%{$params['q']}%'";
        }
        return $this->select(
            " SELECT
                members.tp_id     AS id,
                CONCAT(members.tp_name,
                ' ',
                members.tp_family) AS title
            FROM
                tp_members as   members
            WHERE
                members.`tp_delete` = '0' $whr
            ORDER BY  members.tp_id DESC
        ");
    }

    public function update_profile ($id,$data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "tp_{$key}  = '{$value}'";
        }
        $query_fields = implode(',',$fields);
        return $this->update("
            UPDATE
                `tp_members`
            SET
                {$query_fields},
                tp_update           = now()
            WHERE
                tp_id  = $id
        ");
    }

    public function update_profile_designer ($id,$data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            if($key == 'slug'){
                $this->update("
                UPDATE
                    `tp_members`
                    SET
                        tp_slug = '{$value}',
                        tp_update           = now()
                    WHERE
                        tp_id  = $id
                ");
            }else{
                $fields[] = "tp_{$key}  = '{$value}'";
            }
        }
        $query_fields = implode(',',$fields);
        return $this->update("
            UPDATE
                `tp_designers`
            SET
                {$query_fields},
                tp_update           = now()
            WHERE
                tp_mid  = $id
        ");
    }

    public function add_profile_designer ($id,$data)
    {
        $this->update("
        UPDATE
            `tp_designers`
            SET
                tp_delete = 1, 
                tp_update           = now()
            WHERE
                tp_mid  = $id
        ");
        $res =  $this->insert("
            INSERT INTO
                `tp_designers`
            SET
                tp_expertise     = '{$data['expertise']}',
                tp_bank          = '{$data['bank']}',
                tp_card_number   = '{$data['card_number']}',
                tp_province      = '{$data['province']}',
                tp_address       = '{$data['address']}',
                tp_meta       = '{$data['slug']}',
                tp_title       = '{$data['slug']}',
                tp_bio           = '{$data['bio']}',
                tp_national_code = '{$data['national_code']}',
                tp_files         = '{$data['files']}',
                tp_sheba         = '{$data['sheba']}',
                tp_mid           = '{$id}',
                tp_city          = '{$data['city']}',
                tp_date        = now()
        ");
        if($res->insert_id>0){
            $data['family_en'] = strtolower($data['family_en']);
            $data['name_en'] = strtolower($data['name_en']);
             $this->update("
                UPDATE
                    `tp_members`
                    SET
                        tp_family_en = '{$data['family_en']}',
                        tp_name_en = '{$data['name_en']}',
                        tp_slug = '{$data['slug']}',
                        tp_update           = now()
                    WHERE
                        tp_id  = $id
                ");
            return $res->insert_id;
        }
        return false;
    }

    public function register_by_mobile ($mobile)
    {
        return $this->insert("
            INSERT INTO
                `tp_members`
            SET
                tp_pic = 'file/client/images/member.png',
                tp_mobile = '{$mobile}',
                tp_date   = now()
        ");
    }

    public function get_coopration_requests_count($params=[])
    {
        list($q,$order) = create_query_filters($params,[
            'coopration'=>['mobile','expertise']
        ]);
        return $this->select(
            "SELECT
                COUNT(coopration.tp_id)  AS cnt
            FROM
                tp_coopration coopration
            WHERE
                coopration.tp_delete = 0  {$q}
        ")->result[0]['cnt'];

    }

    public function recent_members()
    {
        return $this->select(
            "SELECT
                member.tp_id     AS id,
                member.tp_mobile AS `mobile`,
                member.tp_email  AS `email`,
                member.tp_date   AS createAt,
                member.tp_status AS `status`,
                CONCAT( member.tp_name, ' ', member.tp_family )  AS `full_name`,
                member.tp_pic  AS `img`
            FROM
                tp_members member
            WHERE
                member.tp_delete = 0
            ORDER BY
                member.tp_id DESC limit 6
        " );
    }

    public function get_designers($params)
    {
        list($q,$order) = create_query_filters($params,[
            'member'=>['full_name','expertise']
        ]);
        return $this->pager(
            "SELECT
                designer.`tp_statistic_follower` AS `statistic_follower`,
                designer.`tp_statistic_product`  AS `statistic_product`,
                designer.`tp_title`              AS `title`,
                member.`tp_slug`               AS `slug`,
                designer.`tp_meta`               AS `meta`,
                designer.`tp_statistic_rate`     AS `statistic_rate`,
                designer.`tp_expertise`          AS `expertise`,
                member.tp_id                     AS `id`,
                member.tp_mobile                 AS `mobile`,
                member.tp_slug                   AS `slug`,
                member.tp_email                  AS `email`,
                DATE(member.tp_date)             AS `createAt`,
                member.tp_type                   AS `type`,
                member.tp_status                 AS `status`,
                CONCAT(
                    member.tp_family,
                    ' ',
                    member.tp_name )             AS `full_name`,
                member.tp_pic                    AS `img`
            FROM
                `tp_members`   AS `member`       INNER JOIN
                `tp_designers` AS `designer`
            WHERE
                member.tp_delete = 0  {$q}
                $order
        ",
            ($params['limit'])?$params['limit']:10,
            ($params['page'])?$params['page']:1
        );
    }

    public function designers_list($params)
    {
        $order = '';
        if(isset($params['sort'])){
            $order =" designer.`tp_".$params['sort']."` DESC";
        } 
        return $this->pager(
            "SELECT
                member.tp_id                     AS `id`,
                member.`tp_slug`                 AS `slug`,
                designer.`tp_expertise`          AS `expertise`,
                designer.`tp_statistic_follower` AS `statistic_follower`,
                designer.`tp_statistic_product`  AS `statistic_product`,
                designer.`tp_statistic_rate`     AS `statistic_rate`,
                CONCAT(
                    member.tp_name,
                    ' ',
                    member.tp_family )             AS `full_name`,
                member.tp_pic                    AS `pic`
            FROM
                `tp_members`   AS `member`       INNER JOIN
                `tp_designers` AS `designer`   ON (designer.tp_mid = member.tp_id)
            WHERE
                member.tp_delete = 0
            GROUP BY
                designer.tp_mid
            ORDER BY
                $order
        ",
            ($params['limit'])?$params['limit']:10,
            ($params['page'])?$params['page']:1
        );
    }
    public function designers_list_sitemap($params)
    { 

        return $this->pager(
            "SELECT
                DATE(designer.`tp_update`)   AS `update`, 
                member.`tp_slug`   AS `slug`, 
                DATE(designer.`tp_date`)      AS `createAt`
            FROM
                `tp_members`   AS `member`       INNER JOIN
                `tp_designers` AS `designer`   ON (designer.tp_mid = member.tp_id)
            WHERE
                member.tp_delete = 0 and member.tp_type =  'designer'
            ",
            ($params['limit'])?$params['limit']:10,
            ($params['page'])?$params['page']:1
        );
    }

    public function coopration_request_files($params)
    {
        return $this->select(
            "SELECT
                atachment.tp_id         AS `id`,
                atachment.tp_attachment     AS `attachment`
            FROM
                tp_coopration_attachment  atachment
            WHERE
                atachment.tp_delete = 0  AND atachment.tp_cid='{$params['cid']}'
        " );
    }

    public function coopration_requests($params)
    {
        list($q,$order) = create_query_filters($params,[
            'member'=>['mobile','full_name']
        ]);
        if(isset($params['type'])  && ($params['type'] === 'common' || $params['type'] === 'designer')  ){
            $q .= " AND member.tp_type='{$params['type']}' ";
        }
        return $this->pager(
            "SELECT
                member.tp_id         AS `id`,
                member.tp_mobile     AS `mobile`,
                DATE(member.tp_date) AS `createAt`,
                atachment.tp_attachment     AS `atachment`,
                member.tp_read     AS `status`,
                CONCAT(
                    member.tp_family,
                    ' ',
                    member.tp_name ) AS `full_name`
            FROM
                tp_coopration member INNER JOIN
                tp_coopration_attachment atachment ON(atachment.tp_cid = member.tp_id)
            WHERE
                member.tp_delete = 0
                {$q}
                $order
        ",
            $params['limit'],
            $params['page']
        );
    }

    public function favorite_products($params)
    {

        return $this->pager(
            "SELECT
                favorite.tp_id       AS `id`,
                product.tp_id        AS `pid`,
                favorite.tp_mid         AS `mid`,
                product.tp_slug AS `slug`,
                product.tp_title AS `product_name`,
                product.tp_pic AS `thumbnail`,
                favorite.tp_date     AS `createAt`
            FROM
                tp_member_favorites favorite  INNER JOIN
                tp_product product ON(product.tp_id = favorite.tp_pid)
            WHERE
                product.tp_delete = 0  AND favorite.tp_delete = 0  AND favorite.tp_mid={$params['mid']}
            ORDER BY
                favorite.tp_id DESC
        ",
            $params['limit'],
            $params['page'],
            true
        );

    }

    public function remove_favorite($id)
    {
        return $this->update("
            UPDATE
                `tp_member_favorites`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id`     = $id
        ");
    }

    public function remove_address($id)
    {
        return $this->update("
            UPDATE
                `tp_member_address`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id`     = $id
        ");
    }

    public function get_statistics($type=0)
    {
        return $this->select(
            "SELECT
                SUM(IF(tp_status = 'active', 1, 0)) AS `active`,
                SUM(IF(tp_change_type_request = 'pend', 1, 0)) AS `pend`,
                SUM(IF(tp_type = 'common', 1, 0)) AS `common`,
                SUM(IF(tp_type = 'designer', 1, 0)) AS `designer`
            FROM tp_members WHERE `tp_delete` = 0 
        ");
    }
    public function member_detail ($mid)
    {
        return $this->select(
            "SELECT
                member.`tp_id`                     AS `id`,
                member.`tp_status`                 AS `status`,
                member.`tp_name`                   AS `name`,
                member.`tp_family`                 AS `family`,
                member.`tp_cover`                   AS `cover`,
                member.`tp_type`                   AS `type`,
                member.`tp_mobile`                 AS `mobile`,
                member.`tp_birthdate`              AS `birthdate`,
                member.`tp_email`                  AS `email`,
                member.`tp_statistic_downloads`    AS `statistic_downloads`,
                member.`tp_date`                   AS `createAt`,
                member.`tp_pic`                    AS `pic`,
                member_plan.`tp_start_date`        AS `start_date`,
                member_plan.`tp_end_date`          AS `end_date`,
                DATEDIFF(
                    member_plan.`tp_end_date`
                    , member_plan.`tp_start_date`
                )                                  AS remaining_days,
                plan.`tp_title`                    AS `plan`,
                plan.`tp_period`                   AS `period`,
                member.`tp_date`                   AS `createAt`,
                member.`tp_complete`               AS `complete`,
                member.`tp_change_type_request`    AS `change_type_request`
            FROM
                `tp_members`                AS `member` LEFT JOIN
                `tp_member_plan` AS `member_plan`  ON(member_plan.tp_mid = member.tp_id and  member_plan.tp_status ='pend' )   LEFT JOIN
                `tp_plan` AS `plan`  ON(plan.tp_id = member_plan.tp_plan_id )
            WHERE
                member.`tp_id` = $mid
        " );
    }
    public function member_last_login ($mid)
    {
        return $this->select(
            "SELECT
                user_login.`tp_date`                   AS `last_login`
            FROM
                `user_login` AS `user_login`
            WHERE
                `user_login`.`tp_uid` = $mid  and  user_login.tp_type ='member'
            ORDER BY
                `user_login`.tp_id DESC
            LIMIT 1
        " );
    }
    public function designer ($mid)
    {
        return $this->select(
            "SELECT
                designer.`tp_statistic_follower`   AS `statistic_follower`,
                designer.`tp_statistic_product`    AS `statistic_product`,
                designer.`tp_title`       AS `title`,
                member.`tp_slug`       AS `slug`,
                designer.`tp_meta`       AS `meta`,
                designer.`tp_statistic_rate`       AS `statistic_rate`,
                designer.`tp_show`                 AS `show`,
                designer.`tp_expertise`            AS `expertise`,
                member.`tp_id`                     AS `id`,
                member.`tp_name`                   AS `name`,
                member.`tp_family`                 AS `family`,
                member.`tp_date`                   AS `createAt`,
                member.`tp_pic`                    AS `pic`
            FROM
                `tp_members` AS `member` INNER JOIN
                `tp_designers` AS `designer`  ON(designer.tp_mid = member.tp_id AND member.`tp_id` = $mid)

        " );
    }
    public function follow ($mid,$did)
    {
        $res = $this->update("
            UPDATE
                `tp_designer_followers`
            SET
                tp_delete  = 1,
                tp_update  = now()
            WHERE
                tp_did =  '{$did}' AND tp_mid =  '{$mid}' AND tp_delete  = 0
        ");
        if($res->affected_rows <= 0){
            $this->insert("
                INSERT INTO
                    `tp_designer_followers`
                SET
                    tp_mid = {$mid},
                    tp_did = {$did},
                    tp_date   = now()
            ")->insert_id;
            $this->update("
                UPDATE
                    `tp_designers`
                SET
                    tp_statistic_follower  = tp_statistic_follower + 1,
                    tp_update  = now()
                WHERE
                tp_mid =  '{$did}'
            ");
        }else{
            $this->update("
                UPDATE
                    `tp_designers`
                SET
                    tp_statistic_follower  = tp_statistic_follower - 1,
                    tp_update  = now()
                WHERE
                    tp_mid =  '{$did}'
            ");
        }
        return $res;
    }
    public function designer_by_slug ($slug)
    {
        return $this->select(
            "SELECT
                designer.`tp_statistic_follower`   AS `statistic_follower`,
                designer.`tp_statistic_product`    AS `statistic_product`,
                designer.`tp_statistic_rate`       AS `statistic_rate`,
                designer.`tp_title`                AS `title`,
                
                designer.`tp_website`              AS `website`,
                designer.`tp_social_youtube`       AS `social_youtube`,
                designer.`tp_social_telegram`      AS `social_telegram`,
                designer.`tp_social_whatsapp`      AS `social_whatsapp`,
                designer.`tp_social_bale`          AS `social_bale`,
                designer.`tp_social_ita`           AS `social_ita`,
                designer.`tp_social_roubika`       AS `social_roubika`,
                designer.`tp_social_pinterest`     AS `social_pinterest`,
                designer.`tp_social_dribble`       AS `social_dribble`,
                designer.`tp_social_instagram`     AS `social_instagram`,
                designer.`tp_bio`                  AS `bio`,
                designer.`tp_meta`                 AS `meta`,
                designer.`tp_expertise`            AS `expertise`,
                member.`tp_cover`                  AS `cover`,
                member.`tp_date`                  AS `datetime`,
                member.`tp_update`                  AS `updatetime`,
                member.`tp_email`                  AS `email`,
                member.`tp_slug`                   AS `slug`,
                member.`tp_id`                     AS `id`,
                CONCAT( member.tp_name,' ',
                    member.tp_family )             AS `full_name`,
                member.`tp_date`                   AS `createAt`,
                member.`tp_pic`                    AS `pic`
            FROM
                `tp_members` AS `member` INNER JOIN
                `tp_designers` AS `designer`  ON(designer.tp_mid = member.tp_id AND member.`tp_slug` = '$slug')
            WHERE
                designer.`tp_delete` = 0 AND  designer.`tp_show` = 'yes'

        " );
    }

    public function designer_detail ($mid)
    {
        return $this->select(
            "SELECT
                designer.`tp_id`                    AS `id`,
                designer.`tp_statistic_follower`    AS `statistic_follower`,
                designer.`tp_statistic_product`     AS `statistic_product`,
                designer.`tp_files`                 AS `files`,
                designer.`tp_statistic_rate`        AS `statistic_rate`,
                designer.`tp_as_company`            AS `as_company`,
                designer.`tp_title`                 AS `title`,
                designer.`tp_meta`                  AS `meta`,
                designer.`tp_bank`                  AS `bank`,
                designer.`tp_national_code`         AS `national_code`,
                member.`tp_slug`                    AS `slug`,
                designer.`tp_reject_exp`            AS `reject_exp`,
                designer.`tp_card_number`           AS `card_number`,
                designer.`tp_expertise`             AS `expertise`,
                designer.`tp_bio`                   AS `bio`,
                designer.`tp_sheba`                 AS `sheba`,
                designer.`tp_show`                  AS `designer_show_status`,
                designer.`tp_address`               AS `address`,
                designer.`tp_tel`                   AS `tel`
            FROM
                `tp_members` AS `member` INNER JOIN
                `tp_designers` AS `designer`  ON(designer.tp_mid = member.tp_id AND member.`tp_id` =   $mid )
        " );
    }
    public function all_info ($id)
    {
        return $this->select(
            "SELECT
              member.`tp_id`                       AS `id`,
                member.`tp_status`                 AS `status`,
                member.`tp_name`                   AS `name`,
                member.`tp_family`                 AS `family`,
                member.`tp_name_en`                AS `name_en`,
                member.`tp_cover`                   AS `cover`,
                member.`tp_family_en`              AS `family_en`,
                member.`tp_type`                   AS `type`,
                member.`tp_mobile`                 AS `mobile`,
                member.`tp_password`                 AS `password`,
                member.`tp_birthdate`              AS `birthdate`,
                member.`tp_email`                  AS `email`,
                member.`tp_date`                   AS `createAt`,
                member.`tp_pic`                    AS `pic`,
                member.`tp_complete`               AS `complete`,
                member.`tp_favorite_categories`    AS `favorite_categories`,
                member.`tp_change_type_request`    AS `change_type_request`,
                designer.`tp_id`                   AS `id`,
                designer.`tp_statistic_follower`   AS `statistic_follower`,
                designer.`tp_statistic_product`    AS `statistic_product`,
                designer.`tp_city`       AS `city_id`,
                designer.`tp_province`       AS `province_id`,
                designer.`tp_statistic_rate`       AS `statistic_rate`,
                designer.`tp_title`                AS `title`,
                designer.`tp_meta`                 AS `meta`,
                designer.`tp_bank`                 AS `bank`,
                designer.`tp_national_code`        AS `national_code`,
                designer.`tp_website`              AS `website`,
                designer.`tp_social_youtube`       AS `social_youtube`,
                designer.`tp_social_telegram`      AS `social_telegram`,
                designer.`tp_social_whatsapp`      AS `social_whatsapp`,
                designer.`tp_social_bale`          AS `social_bale`,
                designer.`tp_social_ita`           AS `social_ita`,
                designer.`tp_social_roubika`       AS `social_roubika`,
                designer.`tp_social_pinterest`     AS `social_pinterest`,
                designer.`tp_social_dribble`       AS `social_dribble`,
                designer.`tp_social_instagram`     AS `social_instagram`,
                designer.`tp_reject_exp`           AS `reject_exp`,
                designer.`tp_card_number`          AS `card_number`,
                designer.`tp_expertise`            AS `expertise`,
                designer.`tp_bio`                  AS `bio`,
                designer.`tp_sheba`                AS `sheba`,
                designer.`tp_address`              AS `address`,
                designer.`tp_tel`                  AS `tel`
            FROM
                `tp_members`   AS `member` LEFT JOIN
                `tp_designers` AS `designer` ON(designer.tp_mid = member.tp_id)
            WHERE
                member.`tp_id` = $id
        " );
    }


    /*
    * Created     : Fri Aug 26 2022 6:42:52 PM
    * Author      : Chavoshi Mojtaba
    * Description : change_password
    * return      : array
    */

    public function change_password  ($get)
    {
        $new_password = $get['new_password'];

        return $this->update("
            UPDATE
                `tp_members`
            SET
                tp_password = '{$new_password}',
                tp_update   = now()
            WHERE
                tp_id  = {$get['mid']}
        ");
    }


    /*
    * Created     : Fri Aug 26 2022 8:06:37 PM
    * Author      : Chavoshi Mojtaba
    * Description : Description
    * return      : array
    */

    public function confirm_change_type  ($get,$mid)
    {
        return $this->update("
            UPDATE
                `tp_members`
            SET
                tp_change_type_request = 'confirm',
                tp_type = 'designer',
                tp_update   = now()
            WHERE
                tp_id  = {$mid}
        ");
    }
    public function set_pend_type  ($mid)
    {
        return $this->update("
            UPDATE
                `tp_members`
            SET
                tp_change_type_request = 'pend',
                tp_update   = now()
            WHERE
                tp_id  = {$mid}
        ");
    }
    
    /*
        * Created     : Fri Aug 26 2022 8:19:18 PM
        * Author      : Chavoshi Mojtaba
        * Description : Description
        * return      : array
    */

    public function reject_change_type  ($mid,$get)
    {
        $this->update("
            UPDATE
                `tp_members`
            SET 
                tp_change_type_request = 'reject',
                tp_update   = now()
            WHERE
                tp_id  = {$mid}
        ");
        return $this->update("
            UPDATE
                `tp_designers`
            SET
                tp_reject_exp = '{$get['exp']}',
                tp_update   = now()
            WHERE
                tp_mid  = {$mid}
        ");
    } 

    public function set_active  ($activation_code)
    {
        return $this->update("
            UPDATE
                `tp_members`
            SET
                `tp_status`       = 'active'
            WHERE
                `tp_activation_code` = '$activation_code'
        ");
    }

    public function fetch_activation_code ($activation_code)
    {
        return $this->select("
            SELECT
                members.`tp_mobile`   AS `mobile`,
                members.`tp_mobile_code`   AS `mobile_code`,
                members.`tp_status`   AS `active`,
                members.`tp_name`     AS `name`,
                members.`tp_family`   AS `family`,
                members.`tp_email`    AS `email`
            FROM
                `tp_members`   AS `members`
            WHERE
                `members`.`tp_delete` = '0'
                AND  `members`.`tp_activation_code` = '$activation_code'
        ");
    }
    
    public function check_activation_code ($data)
    {
        $ret    = 0;
        $select =   $this->select("
                SELECT
                    CONCAT (
                        `tp_name`,' ',`tp_family`
                    )                    AS `full_name`  ,
                    `tp_activation_code` AS `code`,
                    `tp_email`           AS `email`,
                    `tp_active`          AS `active`
                FROM
                    `tp_members`
                WHERE
                    tp_activation_code = '{$data['code']}'
            ");
        if ($select->count > 0) {
            if ($select->result[0]['active'] != 1) {
                $this->update("
                    UPDATE
                        `tp_members`
                    SET
                        tp_active  = 1,
                        tp_update  = now()
                    WHERE
                        tp_activation_code = '{$data['code']}'
                ");
            }
            $ret = 1;
        }
        return $select;
    }



    /* //////////////////////////////////////////////// */




    public function get_member ($data)
    {
        $where = '';
        if(isset($data['mid'])){
            $where = 'tp_id ='.$data['mid'];
        }else if(isset($data['mobile'])){
            $where = "tp_mobile ='{$data['mobile']}' ";
        }
        return $this->select("
            SELECT
                `tp_id`       AS `id`,
                CONCAT (
                    `tp_name`,' ',`tp_family`
                )                    AS `full_name`  ,
                `tp_password` AS `password`,
                `tp_complete`   AS `complete`,
                `tp_mobile`   AS `mobile`,
                `tp_status`   AS `status`
            FROM
                `tp_members`
            WHERE
                $where
        ");
    }

    public function passValidation ($password,$data=false)
    {
        if($data){
            return $this->select("
                SELECT
                    tp_id AS id
                FROM
                    `tp_members`
                WHERE
                    tp_password = '{$password}' AND tp_mobile = '{$data}' AND tp_delete = 0
            ");
        }
        return $this->select("
            SELECT
                COUNT(tp_id) AS cnt
            FROM
                `tp_members`
            WHERE
            tp_password = '{$password}'
        ")->result[0]['cnt'];
    }

    public function get_mobiles ($mid ,$type = false)
    {
        $where = ' tp_delete = 0';
        if($type == 'designer'){
            $where .= ' AND tp_type = "designer" ';
        }else if($type == 'public'){
            $where .= '';
        }else if($type == 'common'){
            $where .= ' AND tp_type = "common" ';
        }else{
            $ids_ex = explode(',',$mid);
            $ids_em = [];
            for ($i=0; $i < count($ids_ex); $i++) {
                $ids_em[] = "'".$ids_ex[$i]."'";
            }
            $ids_em = implode(',',$ids_em);
            $where .= ' AND tp_id IN ('.$ids_em.')';
        }
        return $this->select("
            SELECT
                `tp_id`           AS `id`   ,
                `tp_email`           AS `email`   ,
                `tp_mobile`           AS `mobile`
            FROM
                `tp_members`
            WHERE
                $where

        ") ;
    }

    public function update_incomplete_member ($post)
    {
        return $this->update("
            UPDATE
                `tp_members`
            SET
                tp_name   = '{$post['name']}',
                tp_family = '{$post['family']}',
                tp_change_type_request = '{$post['designer']}',
                tp_complete = 'done',
                tp_status = 'active',
                tp_update = now()
            WHERE
                tp_id     = '{$post['mid']}'
        ");
    }

    public function update_profile1  ($post)
    {
        $feilds = [];
        foreach ($post as $key => $value) {
            if ($key != 'type' &&  $key != 'mid') {
                $feilds[] = "tp_".$key."= '".$value."'";
            }
        }
        $q = implode(',',$feilds);
        return $this->update("
            UPDATE
                `tp_members`
            SET
                tp_update       = now(),
                $q
            WHERE
                tp_id  = '{$post['mid']}'
        ");
        return false;
    }


    public function home_members ( )
    {
        return $this->select("
            SELECT
                members.`tp_id`     AS `mid`,
                members.`tp_active` AS `active`,
                members.`tp_date`   AS `date_modified`,
                members.`tp_name`   AS `name`,
                members.`tp_family` AS `family`,
                members.`tp_mobile` AS `mobile`
            FROM
                `tp_members`   AS `members`
            ORDER BY
                `members`.tp_id DESC
            LIMIT 10
        " );
    }




    public function active_member($post)
    {
        $val = 1;
        if (isset($post['val'])) {
            $val = $post['val'];
        }

        return $this->update("
            UPDATE
                `tp_members`
            SET
                `tp_active`                         = $val,
                `tp_update`                         = now()
            WHERE
                `tp_id`                    = '{$post['mid']}'
        ");
    }

    public function rating ($pid,$rate)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        $res = $this->select(
            "SELECT
                tp_id  AS id
            FROM
                `tp_product_rates`
            WHERE
                tp_delete = 0 AND tp_ip LIKE '{$ip}' AND tp_pid={$pid}
        ");
        if($res->count){
            $this->update(
                "UPDATE
                    `tp_product_rates`
                SET
                    tp_rate   = {$rate},
                    tp_update = now()
                WHERE
                    tp_ip LIKE '{$ip}'  AND tp_pid={$pid}
            ");
        }else{
            $this->insert("
                INSERT INTO
                    `tp_product_rates`
                SET
                    tp_rate = {$rate},
                    tp_ip = '{$ip}',
                    tp_pid = {$pid},
                    tp_date   = now()
            ");
        }
        return true;
    }

    public function add_to_favorites ($pid)
    {
        $mid = $_SESSION['mid'];
        $res = $this->select(
            "SELECT
                tp_id  AS id
            FROM
                `tp_member_favorites`
            WHERE
                tp_delete = 0 AND tp_mid={$mid} AND   tp_pid={$pid}
        "); 
        if($res->count){
            $id  =$res->result[0]['id'];
            $this->update("
                UPDATE
                    `tp_member_favorites`
                SET
                    `tp_delete` = 1,
                    `tp_update` = now()
                WHERE
                    `tp_id` = '{$id}'
            ");
            return 'del';
        }
        return $this->insert("
            INSERT INTO
                `tp_member_favorites`
            SET
                tp_mid = {$mid},
                tp_pid = {$pid},
                tp_date   = now()
        ")->insert_id;
    } 
    public function get_product_rate ($pid)
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        return $this->select(
            "SELECT
                tp_rate  AS rate
            FROM
                `tp_product_rates`
            WHERE
                tp_delete = 0 AND tp_ip LIKE '{$ip}' AND   tp_pid={$pid}
        ");  
    }
  

    /* -------------------------------------------------------------------------- */
    /*                                expertise                                */
    /* -------------------------------------------------------------------------- */
    
    public function get_expertise_list ($params)
    {
        $order = 'expertise.tp_id DESC';

        return $this->pager(
            "SELECT
                expertise.tp_id AS id,
                expertise.`tp_slug` AS slug,
                expertise.`tp_title` AS title,
                expertise.`tp_desc` AS `desc`,
                expertise.tp_date AS createAt
            FROM
                `tp_expertise` AS expertise
            WHERE
                expertise.`tp_delete` = 0
            ORDER BY $order
        " ,$params['limit'],$params['page'],true);
    }

    public function get_designer_expertise ($ids)
    {
        $order = 'expertise.tp_id DESC';

        return $this->select(
            "SELECT
                expertise.tp_id AS id,
                expertise.`tp_title` AS title,
                expertise.`tp_desc` AS `desc`,
                expertise.tp_date AS createAt
            FROM
                `tp_expertise` AS expertise
            WHERE
                expertise.`tp_delete` = 0 AND expertise.tp_id IN($ids)
            ORDER BY $order
        ");
    }

    public function get_expertise_getail ($slug)
    { 
        return $this->select(
            "SELECT
                expertise.tp_id AS id,
                expertise.`tp_update` AS update_date,
                expertise.`tp_seo_title` AS seo_title,
                expertise.`tp_title` AS title,
                expertise.`tp_short_desc` AS `short_desc`,
                expertise.`tp_meta` AS `meta`,
                expertise.`tp_desc` AS `desc` 
            FROM
                `tp_expertise` AS expertise
            WHERE
                expertise.`tp_delete` = 0 AND expertise.tp_slug = '{$slug}' 
        ");
    }
    
    public function get_expertise_getail_slug ($params)
    {
        return $this->select(
			"SELECT
                expertise.tp_id      AS `id`,
				expertise.`tp_slug`  AS `slug`,
				expertise.`tp_short_desc` AS `short_desc`,
                expertise.`tp_seo_title` AS seo_title,
				expertise.`tp_title` AS `title`,
				expertise.`tp_meta`  AS `meta`,
                expertise.`tp_desc`  AS `desc`,
                expertise.tp_date    AS `createAt`
            FROM
                `tp_expertise` as   expertise
            WHERE
                expertise.`tp_delete` = '0' AND expertise.tp_id = {$params['id']}
            ORDER BY  expertise.tp_id DESC
        ");
    }

    public function add_expertise ($post)
    {
        return $this->insert("
            INSERT INTO
                `tp_expertise`
            SET
                tp_seo_title  = '{$post['seo_title']}',
                tp_meta  = '{$post['meta']}',
                tp_title = '{$post['title']}',
                tp_short_desc  = '{$post['short_desc']}',
                tp_slug  = '{$post['slug']}',
                tp_desc  = '{$post['desc']}',
                tp_uid   = '{$_SESSION['uid']}',
                tp_date  = now()
        ");
    }

    public function del_expertise ($id)
    {
        return $this->update("
            UPDATE
                `tp_expertise`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function update_expertise ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_expertise`
            SET
				tp_short_desc     = '{$data['short_desc']}',
				tp_seo_title     = '{$data['seo_title']}',
				tp_meta     = '{$data['meta']}',
				tp_title    = '{$data['title']}',
				tp_slug     = '{$data['slug']}',
				tp_desc     = '{$data['desc']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function downgrade_to_common  ($mid)
    {
        $res = $this->update("
            UPDATE
                `tp_members`
            SET 
                tp_change_type_request = 'none',
                tp_type = 'common',
                tp_update   = now()
            WHERE
                tp_id  = {$mid}
        ");
        // pr($res,true);
        if ($res->affected_rows>0) { 
            $this->update("
                UPDATE
                    `tp_designers`
                SET
                    tp_delete = '1',
                    tp_update   = now()
                WHERE
                    tp_mid  = {$mid}
            ");
        }
        return $res;
    } 
}
