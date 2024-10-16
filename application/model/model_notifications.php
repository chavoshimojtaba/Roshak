<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_notifications extends DB
{


    /*
    * Created     : Mon Aug 29 2022 7:22:02 PM
    * Author      : Chavoshi Mojtaba
    * Description : Description
    * return      : array
    */

    public function add_authentication_code ($post)
    {
        $field = '';
        if(isset($post['status']) && $post['status'] =='incomplete'){
            $field = "tp_need_complete_profile = 1, ";
        }
        if($post['type'] == 'user'){
            return $this->insert(
                "INSERT INTO
                    `tp_authentication_code_user`
                SET
                    $field
                    tp_uid   = '{$post['mid']}',
                    tp_code  = '{$post['code']}',
                    tp_date  = now()
            ");
        }else{
            return $this->insert(
                "INSERT INTO
                    `tp_authentication_code`
                SET
                    $field
                    tp_mid   = '{$post['mid']}',
                    tp_code  = '{$post['code']}',
                    tp_date  = now()
            ");
        }
    }

    public function verify_authentication_code($code,$type = 'member')
    {
        if($type == 'user'){
            return $this->select(
                "SELECT
                    auth_code.tp_uid  AS `uid`
                FROM
                    tp_authentication_code_user auth_code INNER JOIN
                    tp_users user  ON (user.tp_id = auth_code.tp_uid)
                WHERE
                    auth_code.tp_delete = 0
                    AND auth_code.tp_code = '{$code}'
                    AND auth_code.tp_date  BETWEEN DATE_SUB(NOW() , INTERVAL 10  MINUTE) AND NOW()
                ORDER BY
                    auth_code.tp_id DESC limit 1
            ");
        }

        return $this->select(
            "SELECT
                member.tp_complete  AS `complete`,
                auth_code.tp_mid  AS `mid`
            FROM
                tp_authentication_code auth_code INNER JOIN
                tp_members member  ON (member.tp_id = auth_code.tp_mid)
            WHERE
                auth_code.tp_delete = 0
                AND auth_code.tp_code = '{$code}'
                AND auth_code.tp_date  BETWEEN DATE_SUB(NOW() , INTERVAL 2  MINUTE) AND NOW()
            ORDER BY
                auth_code.tp_id DESC limit 1
        ");
    }


    /*
    * Created     : Mon Aug 22 2022 8:25:57 PM
    * Author      : Chavoshi Mojtaba
    * Description : Description
    * return      : array
    */

    public function list($params)
    {
        return $this->pager(
            "SELECT
                notifications.tp_id     AS id,
                notifications.tp_title AS `title`,
                notifications.tp_read  AS `read`,
                notifications.tp_text  AS `text`,
                notifications.tp_type  AS `type`,
                notifications.tp_date   AS createAt
            FROM
                tp_notifications AS  notifications
            WHERE
                notifications.tp_delete = 0 AND notifications.tp_mid = {$params['mid']}
            ORDER BY
                notifications.tp_id DESC
        ",
            $params['limit'],
            $params['page'],true
        );
    }
 
    public function get_template_list($params)
    {
        $q = '';
        if(isset($params['type'])){
            $q = " AND template.tp_type = '{$params['type']}' ";
        }
        return $this->pager(
            "SELECT
                template.tp_id     AS id,
                template.tp_type AS `type`,
                template.tp_title AS `title`,
                template.tp_exp  AS `exp`,
                template.tp_alias    AS `alias`,
                template.tp_date   AS createAt
            FROM
                tp_message_template AS  template
            WHERE
                template.tp_delete = 0 $q
            ORDER BY
                template.tp_id DESC
        ",
            $params['limit'],
            $params['page'],true
        );
    }

    public function get_template($alias)
    {
        return $this->select(
            "SELECT
                template.tp_templateid  AS `TemplateId`,
                template.tp_sms_type  AS `sms_type`,
                template.tp_type  AS `type`,
                template.tp_title AS `title`,
                template.tp_exp   AS `exp`
            FROM
                tp_message_template AS  template
            WHERE
                template.tp_delete = 0 AND template.tp_alias = '{$alias}'
            ORDER BY
                template.tp_id DESC limit 1
        ");
    }




    public function update_template ($id,$data)
    {
        return $this->update("
            UPDATE
                `tp_message_template`
            SET
                `tp_title` = '{$data['title']}',
                `tp_alias` = '{$data['alias']}',
                `tp_exp` = '{$data['exp']}',
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function del_template ($id)
    {
        return $this->update("
            UPDATE
                `tp_message_template`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

    public function add_template ($post)
    {
        return $this->insert("
            INSERT INTO
                `tp_message_template`
            SET
                tp_alias    = '{$post['alias']}',
                tp_exp      = '{$post['exp']}',
                tp_title    = '{$post['title']}',
                tp_type     = '{$post['type']}',
                tp_uid      = {$_SESSION['uid']},
                tp_date     = now()
        ");
    }

    public function set_read  ($id)
    {
        return $this->update("
            UPDATE
                `tp_notifications`
            SET
                `tp_read`       = '1'
            WHERE
                `tp_id` = {$id}
        ");
    }
    public function set_read_all  ($id)
    {
        return $this->update("
            UPDATE
                `tp_notifications`
            SET
                `tp_read`       = '1'
            WHERE
                `tp_mid` = {$id}
        ");
    }

    public function submit_notifications ($type,$text ,$mids,$title='')
    {
        
        $values      = [];
        if($type == 'members'){
            foreach ($mids as $k => $value) {
                $values [] = "( '".$title."','".$text."','info','public', '".$mids[$k]."',now())";
            }
        }else{
            $values [] = "( '".$title."','".$text."','info','".$type."', '0',now())";
        }
        $vals = implode(',',$values);
        return $this->insert("
            INSERT INTO
                `tp_notifications`
                ( tp_title,tp_text,tp_type,tp_member_type,tp_mid,tp_date)
            VALUES
            $vals
        ");
    }
    public function submit_common_notification ($mid,$text,$type='info',$title='اعلان')
    {  
        return $this->insert("
            INSERT INTO
                `tp_notifications`
                ( tp_title,tp_text,tp_type,tp_member_type,tp_mid,tp_date)
            VALUES
            ( '".$title."','".$text."','".$type."','member', '".$mid."',now())
        ");
    }

    public function notify_count ($mid=0)
    {
        return $this->select("
            SELECT
                 COUNT(tp_id)           AS cnt
            FROM
                `tp_notifications`
            WHERE
                `tp_delete` = '0'  AND `tp_read` = 0  AND `tp_mid` = {$mid}
        ")->result[0]['cnt'];
    }
    /*
    public function get_notifications ($page=1,$url='',$limit=500,$mid=0)
    {
        $whr='';
        if ($mid > 0) {
            $whr = "AND members.`tp_id` = {$mid}";
        }
        return $this->pager("
            SELECT
                notifications.tp_id           AS id,
                notifications.tp_title        AS title,
                notifications.tp_text         AS text,
                notifications.tp_read         AS `read`,
                CONCAT (
                    members.`tp_name`,'
                    ',members.`tp_family`
                    )                         AS `full_name`,
                TIME(notifications.tp_date)   AS `time`,
                notifications.tp_date         AS date,
                notifications.tp_mid          AS mid
            FROM
                `tp_notifications`    notifications INNER JOIN
                `tp_members`   AS `members` ON(members.tp_id = notifications.tp_mid)
            WHERE
                notifications.`tp_delete` = '0'  $whr
            ORDER BY  notifications.tp_id DESC
        ",$limit,$page,$url);
    }  */
}
