<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class model_tickets extends DB
{

 
    public function get_tickets($params)
    {
        list($q,$order) = create_query_filters($params,[
            'tickets'=>['title','full_name','status','date'] ,
            'member'=>['full_name'] ,
        ]);

        if(!isset($_SESSION['is_admin'])  || $_SESSION['is_admin'] != 1){
            $q .= " AND tickets.tp_rid = ".$_SESSION['rid']." AND tickets.tp_referred =  'yes'";
        }
        return $this->pager(
            "SELECT
                tickets.tp_mid AS mid,
                tickets.tp_id AS tid,
                tickets.tp_title AS title,
                tickets.tp_priority AS priority,
                tickets.tp_status AS `status`,
                tickets.tp_referred AS `referred`,
                tickets.tp_number AS `number`,
                tickets.tp_rid AS rid,
                comments.tp_read AS `read`,
                comments.tp_id AS comment_id,
                comments.tp_date AS createAt,
                comments.tp_text AS `comment`,
                comments.tp_umid AS comment_umid,
                comments.tp_user_type AS user_type ,
                CONCAT( member.tp_name, ' ', member.tp_family )  AS `full_name`,
                member.tp_pic  AS `img`
            FROM
                tp_tickets tickets INNER JOIN
                tp_ticket_comments comments ON ( comments.tp_tid = tickets.tp_id ) INNER JOIN
                tp_members member ON(member.tp_id = tickets.tp_mid)
            WHERE
                comments.tp_delete = 0
                AND comments.tp_id = (SELECT tp_id FROM tp_ticket_comments WHERE tp_delete = 0 and tp_tid = tickets.tp_id ORDER BY tp_id DESC limit 1) {$q}
                $order
        ",
            $params['limit'],
            $params['page']
        ,true);
    }

    public function add_tickets($post)
    {
        $res = $this->insert("INSERT INTO
            `tp_tickets`
            (`tp_rid`, `tp_title`, `tp_priority`, `tp_mid`, `tp_has_file` ,`tp_number` , `tp_date` )
            VALUES( 1, '{$post['title']}', '{$post['priority']}', {$_SESSION['mid']}, 'no',AutoInc(), now() )
        ");
        $id = $res->insert_id;
         $this->insert("INSERT INTO
            `tp_ticket_comments`
            (`tp_tid`,`tp_text`,`tp_files`,tp_umid , tp_user_type,tp_date)
            VALUES($id ,'{$post['text']}' ,'{$post['files']}' ,'{$_SESSION['mid']}',  'member', now() )
        ");
        return $res;
    }

    public function get_statistics()
    {
        return $this->select(
            "SELECT
                SUM(IF(tp_delete = '0', 1, 0)) AS `total`,
                SUM(IF(tp_status = 'open', 1, 0)) AS `open`
            FROM tp_tickets WHERE `tp_delete` = 0
        ")->result[0];
    }

    public function close_ticket($id)
    {
        return $this->update("
            UPDATE
                `tp_tickets`
            SET
                `tp_status` = 'close',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function refer_ticket($rid, $id)
    {
        return $this->update("
            UPDATE
                `tp_tickets`
            SET
                `tp_rid` = {$rid},
                `tp_referred` =  'yes',
                `tp_update` = now()
            WHERE
                `tp_id` = {$id}
        ");
    }

    public function has_file($tid)
    {
        return $this->update("
            UPDATE
                `tp_tickets`
            SET
                `tp_has_file` =  'yes',
                `tp_update` = now()
            WHERE
                `tp_id` = {$tid}
        ");
    }

    public function get_ticket($id)
    {
        if (isset($_SESSION['uid'])) {
            $whr = " AND tp_user_type =  'member'";
        } else {
            $whr = " AND tp_user_type =  'user'";
        }
        $this->update(
            "UPDATE
                `tp_ticket_comments`
            SET
                `tp_read` = 'yes'
            WHERE `tp_tid` = '{$id}' $whr
        "
        );
        return $this->select(
            "SELECT
                tickets.tp_mid AS mid,
                tickets.tp_id AS tid,
                tickets.tp_title AS title,
                tickets.tp_has_file AS has_file,
                tickets.tp_priority AS priority,
                tickets.tp_status AS `status`,
                tickets.tp_referred AS `referred`,
                tickets.tp_number AS `number`,
                tickets.tp_rid AS rid,
                comments.tp_read AS `read`,
                comments.tp_id AS comment_id,
                comments.tp_date AS last_update,
                comments.tp_text AS `comment`,
                comments.tp_umid AS comment_umid,
                comments.tp_user_type AS user_type,
                `role`.tp_name AS `role`,
            CASE
                comments.`tp_user_type`
                WHEN 'user' THEN
                users.tp_pic ELSE members.tp_pic
                END AS `img`,
            CASE
                comments.`tp_user_type`
                WHEN 'user' THEN
                CONCAT( users.tp_name, ' ', users.tp_family ) ELSE CONCAT( members.tp_name, ' ', members.tp_family )
                END AS `full_name`,
            CASE
                comments.`tp_user_type`
                WHEN 'user' THEN
                users.tp_email ELSE members.tp_email
                END AS `email`,
                comments.tp_date AS createAt
            FROM
                tp_tickets tickets
                INNER JOIN tp_ticket_comments comments ON ( comments.tp_tid = tickets.tp_id )
                LEFT JOIN `tp_members` AS members ON ( members.tp_id = comments.tp_umid AND comments.`tp_user_type` ='member')
                LEFT JOIN `tp_users` AS users ON ( users.tp_id = comments.tp_umid AND comments.`tp_user_type` ='user')
                INNER JOIN `tp_role` AS `role` ON ( `role`.tp_id = tickets.tp_rid)
            WHERE
                comments.tp_delete = 0
                AND tickets.tp_id = '{$id}' ORDER BY comments.tp_id"
        );
    }

    public function add_reply($post)
    {
        $type = (isset($post['member_type']))?$post['member_type']:'user';
        $read = (isset($post['read']))?$post['read']:'no';
        return $this->insert("INSERT INTO
            `tp_ticket_comments`
            (`tp_tid`,tp_umid ,`tp_text`,tp_date, tp_user_type, tp_read)
            VALUES( {$post['tid']},'{$post['umid']}','{$post['text']}',now(),'{$type}','{$read}' )
        ");
    }

    public function get_departments()
    {
        return $this->select("
            SELECT
            `tp_id` AS id,
            `tp_name` AS name
            FROM
            `tp_departments`
        ");
    }

    public function get_tickets_admin()
    {
        return $this->select("SELECT
                departments.tp_name AS department_name,
                tickets.tp_id                 AS tid,
                tickets.tp_department_id                 AS department_id,
                tickets.tp_status                 AS ticket_status ,
                tickets.tp_title                 AS title,
                tickets.tp_level                 AS level,
                tickets.tp_umid                 AS umid ,
                CONCAT(member.tp_name , ' ',member.tp_family) AS membername,
                tickets.tp_user_type                 AS user_type,
                tickets.tp_date                AS  date,
                tickets.tp_update             AS    ticket_update
            FROM
                `tp_tickets` AS tickets
                LEFT JOIN  tp_ticket_answers ans
                ON(ans.tp_tid = tickets.tp_id AND  tickets.tp_id
                IN(SELECT answer.tp_id from tp_ticket_answers answer
                WHERE answer.tp_tid = tickets.tp_id
                ORDER BY answer.tp_id DESC)
            )
                INNER JOIN `tp_departments` AS departments ON (tickets.tp_department_id = departments.tp_id)
                left join tp_members         AS member ON (member.tp_id = tickets.tp_umid    AND tickets.tp_user_type = 'user')

            GROUP BY tickets.tp_id
            ORDER BY  tickets.tp_update  DESC
      ");
    }

    public function admin_add_ticket_add_answer($post)
    {
        $id = $this->insert(" INSERT INTO
            `tp_tickets`
            (tp_department_id,tp_umid,tp_status,tp_title,tp_level,tp_user_type,tp_date,tp_update)
            VALUES(1 , '{$post['mid']}' , 0 , '{$post['title']}' , {$post['level']}  , 0 , now(),now() )
           ")->insert_id;

        return $this->insert(" INSERT INTO
           `tp_ticket_answers`( tp_tid, tp_umid, tp_text,  tp_date, tp_user_type, tp_update)
            VALUES($id , '{$post['mid']}' , '{$post['text']}' , now() , 0 , now() )
        ");
    }

    public function update_status_ticket($post)
    {
        return $this->update("
            UPDATE
            `tp_tickets`
            SET
            `tp_status` = '{$post['status_ticket']}'
            WHERE `tp_id` = '{$post['tid']}'
        ");
    }
    public function set_read($id)

    {
        return $this->update("
            UPDATE
            `tp_ticket_comments`
            SET
            `tp_read` = 'yes'
            WHERE `tp_tid` = '{$id}'
        ");
    }

    public function fetch_ticket_admin($post)
    {
        return $this->select("SELECT
                tick.tp_id AS id,
                tick.tp_title AS title,
                tick.tp_status AS `status`,
                tick.tp_date AS `date`,
                tick.tp_user_type AS `user_type`,
                CONCAT(user.tp_name , ' ', user.tp_family) AS username,
                CONCAT(member.tp_name , ' ',member.tp_family) AS membername,
                answer.tp_user_type AS answer_user_type,
                answer.tp_id AS answer_id,
                answer.tp_text AS `text`,
                answer.tp_date AS `answer_date` ,
                answer.tp_umid AS `answer_umid` ,
                answer.tp_update AS `answer_update`
            FROM
                tp_tickets AS tick
                INNER JOIN tp_ticket_answers AS answer ON ( tick.tp_id = answer.tp_tid AND answer.tp_delete = 0)
                left join tp_users           AS user ON (answer.tp_umid = user.tp_id AND answer.tp_user_type = 'user')
                left join tp_members         AS member ON (member.tp_id = answer.tp_umid    AND answer.tp_user_type = 'user')
                where tick.tp_id = {$post['tid']}   ORDER BY answer.tp_id
        ");
    }

    public function add_answer_to_ticket_admin($post)
    {
        $this->update("
            UPDATE
            `tp_tickets`
            SET
            `tp_read` = 0
            WHERE `tp_id` = {$post['tid']}
        ");
        return $this->insert("INSERT INTO
            `tp_ticket_answers`
            (`tp_tid`,`tp_umid`,`tp_text`,tp_date, tp_user_type,tp_update )
            VALUES( {$post['tid']},'{$post['mid']}','{$post['desc']}',now(),'user',now() )
           ");
    }

    public function fetch_member_tickets($post)
    {
        return $this->select("
          SELECT
                departments.tp_name AS department_name,
                tickets.tp_id                 AS tid,
                tickets.tp_department_id                 AS department_id,
                tickets.tp_status                 AS ticket_status ,
                tickets.tp_title                 AS title,
                tickets.tp_level                 AS level,
                tickets.tp_umid                 AS umid ,
                CONCAT(member.tp_name , ' ',member.tp_family) AS membername,
                tickets.tp_user_type                 AS user_type,
                tickets.tp_date                AS  date,
                tickets.tp_update             AS    ticket_update
       FROM
                `tp_tickets` AS tickets
                LEFT JOIN  tp_ticket_answers ans
                ON(ans.tp_tid = tickets.tp_id AND  tickets.tp_id
                  IN(SELECT answer.tp_id from tp_ticket_answers answer
                   WHERE answer.tp_tid = tickets.tp_id
                  ORDER BY answer.tp_id DESC)
                 )
        INNER JOIN `tp_departments` AS departments ON (tickets.tp_department_id = departments.tp_id)
        left join tp_members         AS member ON (member.tp_id = tickets.tp_umid)
        where  tickets.tp_umid = '{$post['mid']}'
      ");
    }
}
