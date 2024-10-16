<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_plan extends DB
{

    public function get_list ($params)
    {
        list($q,$order) = create_query_filters($params,[
            'plan'=>['title', 'date','price']
        ]);
       
        // pr($q,true);
        return $this->pager(
            "SELECT
                plan.tp_id AS id,
                plan.tp_title AS title,
                plan.tp_desc AS `desc`,
                plan.tp_off AS `off`,
                plan.tp_period AS `period`,
                plan.tp_price AS `price`,
                plan.tp_active AS `active`,
                plan.tp_download_limit AS `download_limit`,
                plan.tp_date AS createAt
            FROM
                `tp_plan` as   plan
            WHERE
                plan.`tp_delete` = '0' $q
            $order
        ",
        isset($params['limit']) ? $params['limit'] : 10,
        isset($params['page']) ? $params['page'] : 1,
        true);
    }

    public function current_plan ($id)
    {
        return $this->select(
            "SELECT
                member_plan.tp_id       AS `id`,
                member_plan.`tp_start_date`    AS `start_date`,
                member_plan.`tp_end_date`    AS `end_date`,
                DATEDIFF( member_plan.`tp_end_date`,now()) AS left_days,
                member_plan.`tp_status` AS `status`,
                plan.`tp_download_limit`   AS `download_limit`,
                plan.`tp_period`   AS `period`,
                plan.`tp_title`   AS `title`,
                plan.`tp_price`  AS `price`,
                plan.`tp_off` AS `off`,
                member_plan.`tp_date`      AS `createAt`
            FROM
                `tp_plan` AS plan   INNER JOIN
                tp_member_plan as member_plan ON(member_plan.tp_plan_id = plan.tp_id)
            WHERE
                member_plan.`tp_mid` =  {$id} and  member_plan.`tp_delete` = '0' and ( member_plan.`tp_status` = 'active' || member_plan.`tp_status` = 'reserve')

                ORDER BY  plan.tp_id DESC
        ");
    }

    public function get_plan_getail ($id)
    {
        return $this->select(
            "SELECT
                plan.tp_id AS id,
                plan.tp_title AS title,
                plan.tp_desc AS `desc`,
                plan.tp_off AS `off`,
                plan.tp_period AS `period`,
                plan.tp_price AS `price`,
                plan.tp_active AS `active`,
                plan.tp_download_limit AS `download_limit`,
                plan.tp_date AS createAt
            FROM
                `tp_plan` as   plan
            WHERE
                plan.`tp_delete` = '0'  AND plan.tp_id=$id
            ORDER BY  plan.tp_id DESC
        ");
    }

    public function get_count ()
    {
        return $this->total_count("tp_plan","`tp_delete` = '0'");
    }

    public function subscribtion ($post)
    {

        $plan = $this->select(
            "SELECT
                plan.tp_id AS id,
                plan.tp_period AS `period`,
                plan.tp_date AS createAt
            FROM
                `tp_plan` as   plan
            WHERE plan.tp_id= {$post['pid']}
            ORDER BY  plan.tp_id DESC
        ")->result['0'];
        return $this->insert(
            " INSERT INTO
                `tp_member_plan`
            SET
                tp_transaction_id = '{$post['transaction_id']}',
                tp_mid            = '{$post['mid']}',
                tp_plan_id        = '{$plan['id']}',
                tp_start_date     = now(),
                tp_date           = now()
        ");
    }

    public function update_member_plan ($id,$data)
    {

        $field = "";
        if($data['status'] == 'active'){
            $this->update("
                UPDATE
                    `tp_member_plan`
                SET
                    `tp_status` = 'ended',
                    `tp_update` = now()
                WHERE
                    `tp_mid` = '{$data['mid']}' AND `tp_status` = 'active'
            ");
            $field = "tp_end_date = now() + interval {$data['period']} day ,`tp_start_date` = now(),";
        }
        // pr($data,true);
        return $this->update(
            "UPDATE
                `tp_member_plan`
            SET
                $field
                tp_status          = '{$data['status']}',
                `tp_update`       = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }
    public function delete_plan ($id)
    {
        return $this->update("
            UPDATE
                `tp_plan`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_plan ($data)
    {

        return $this->insert(
            " INSERT INTO
                `tp_plan`
            SET
                tp_title          = '{$data['title']}',
                tp_off         = '{$data['off']}',
                tp_period         = '{$data['period']}',
                tp_download_limit = '{$data['download_limit']}',
                tp_price          = '{$data['price']}',
                tp_desc           = '{$data['desc']}',
                tp_date           = now()
        ");
    }


    public function update_plan ($id,$data)
    {
        return $this->update(
            "UPDATE
                `tp_plan`
            SET
                tp_title          = '{$data['title']}',
                tp_off            = '{$data['off']}',
                tp_period         = '{$data['period']}',
                tp_download_limit = '{$data['download_limit']}',
                tp_price          = '{$data['price']}',
                tp_desc           = '{$data['desc']}',
                `tp_update`       = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }





































}
