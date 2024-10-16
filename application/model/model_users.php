
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_users extends DB{

    public function login_list_count ()
    {
        $this->total_count("sp_user_login","`sp_delete` = '0' ");
    }
    public function login_list ($params)
    {

        return $this->pager(
            "SELECT
                _login.`sp_agent`            AS `agent`,
                _login.`sp_ip`            AS `ip`,
                CONCAT (
                    user.`sp_name`,'
                    ',user.`sp_family`
                    )                     AS `full_name`,
                user.`sp_pic`             AS `img`,
                user.`sp_date`            AS `createAt`
            FROM
                `user_login` as _login INNER JOIN
                `sp_users` AS `user` ON(user.sp_id = _login.sp_uid)
            WHERE
                user.`sp_delete` = 0
            ORDER BY
                user.sp_id DESC
        " ,$params['limit'],$params['page']);
    }

    public function forgot_pass_token ($params)
    {
        return $this->insert("
            INSERT INTO
                `tp_change_password`
            SET
                tp_token = '{$params['token']}',
                tp_uid   = {$params['uid']},
                tp_date  = now()
        ");
    }
    public function delete_tokens  ($uid)
    {

        $this->update("
            UPDATE
                `tp_change_password`
            SET
                tp_delete     = 1,
                tp_update     = now()
            WHERE
                tp_id  = {$uid}
        ");
    }
    public function check_token ($token)
    {
        return $this->select("
            SELECT
                `tp_uid` as uid
            FROM
                `tp_change_password`
            WHERE
                `tp_delete`    = 0
            AND `tp_token`    = '$token'
            AND `tp_date`  >= (NOW() - INTERVAL 30 MINUTE)
            AND `tp_date`  <= NOW()
        ")->result;
    }

    public function add_user ($params)
    {
        return $this->insert("
            INSERT INTO
                `tp_users`
            SET
                tp_rid             =  {$params['rid']} ,
                tp_pic             = '{$params['pic']}',
                tp_password        = '{$params['password']}',
                tp_expertise       = '{$params['expertise']}',
                tp_mobile          = '{$params['mobile']}',
                tp_name            = '{$params['name']}',
                tp_family          = '{$params['family']}',
                tp_email            = '{$params['email']}',
                tp_status          = '{$params['status']}' ,
                tp_address         = '{$params['address']}',
                tp_activation_code = '{$params['activation_code']}',
                tp_date            = now()
        ");
    }

    public function delete_user ($id)
    {
        return $this->update("
            UPDATE
                `tp_users`
            SET
                `tp_delete` = 1,
                `tp_update` = now()
            WHERE
                `tp_id` = '{$id}'
        ");
    }

 

    public function get_list ($params)
    {
        list($q,$order) = create_query_filters($params,[
            'users'=>['full_name','email','expertise']
        ]); 
        if(isset($params['team'])){
            $q .= " AND users.`tp_as_team_member` = 'yes'";
        }
        if(isset($_SESSION['uid'])){
            if(!$_SESSION['is_admin']){
                $q .= " AND users.tp_is_admin = 0 ";
            }
        }
        if(isset($_SESSION['uid'])){
            $q .= " AND users.tp_id <> {$_SESSION['uid']} ";
        }
        return $this->pager(
            "SELECT
                `role`.`tp_name`          AS `role`,
                users.`tp_name`            AS `name`,
                users.`tp_family`          AS `family`,
                CONCAT (
                    users.`tp_name`,' ', users.`tp_family`
                    )                     AS `full_name`,
                users.`tp_as_team_member`  AS `as_team_member`,
                users.`tp_expertise`       AS `expertise`,
                users.`tp_mobile`          AS `mobile`,
                users.`tp_address`         AS `address`,
                users.`tp_is_active`       AS `is_active`,
                users.`tp_id`              AS `id`,
                users.`tp_rid`             AS `rid`,
                users.`tp_pic`             AS `img`,
                users.`tp_email`           AS `email`,
                users.`tp_password`        AS `password`,
                users.`tp_status`          AS `status`
            FROM
                `tp_users` as users INNER JOIN
                `tp_role` AS `role` ON(role.tp_id = users.tp_rid)
            WHERE
                users.`tp_delete` = 0 AND users.`tp_is_admin` = 0  $q 
                $order
        " ,$params['limit'],$params['page'],true);
    }

    public function check_login ($id,$ip,$uid,$token,$path)
    {
        $res = $this->select("
            SELECT
                `tp_id`
            FROM
                `user_login`
            WHERE
                `tp_id`    = '$id'
            AND `tp_ip`    = '$ip'
            AND `tp_uid`   = '$uid'
            AND `tp_token` = '$token'
            AND `tp_path`  = '$path'
        ");
        if ( $res != false AND isset ($res->count) AND $res->count > 0 ){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function update_profile  ($post,$uid)
    {
        $feilds = [];
        foreach ($post as $key => $value) {
            if ($key != 'type' &&  $key != 'mid' && $value != '') {
                $feilds[] = "tp_".$key."= '".$value."'";
            }
        }
        $q = implode(',',$feilds);
        return $this->update("
            UPDATE
                `tp_users`
            SET
                tp_update       = now(),
                $q
            WHERE
                tp_id  = '{$uid}'
        ");
        return false;
    }

    public function change_pass  ($data)
    {
        $new_password = $data['new_password'];
        $cur_password = $data['cur_password'];
        $cnt = $this->select("
            SELECT

                `tp_password`        AS `password`
            FROM
                `tp_users`
            WHERE
                tp_id  = {$_SESSION['uid']} AND
                tp_password  = '{$cur_password}'
        ")->count;
        if ($cnt > 0) {
            $this->update("
                UPDATE
                    `tp_users`
                SET
                    tp_password     = '{$new_password}',
                    tp_update       = now()
                WHERE
                    tp_id  = {$_SESSION['uid']}
            ");
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }

        return $data;
    }

    public function force_new_password  ($password,$uid)
    {

        return $this->update("
            UPDATE
                `tp_users`
            SET
                tp_status     = 'active',
                tp_first_login     = 'no',
                tp_password     = '{$password}',
                tp_update       = now()
            WHERE
                tp_id  = {$uid}
        ");
    }


    public function fetch_user_by_username ($username,$id=0)
    {
        if ($id > 0) {
            $whr = " `tp_id`='$id'";
        }else{
            $whr = " `tp_email` LIKE '$username'";
        }
        return $this->select("
            SELECT
                CONCAT (
                    `tp_name`,'
                    ',`tp_family`
                    )                AS `full_name`,
                `tp_id`              AS `uid`,
                `tp_first_login`             AS `first_login`,
                `tp_rid`             AS `rid`,
                `tp_is_admin`        AS `is_admin`,
                `tp_pic`             AS `pic`,
                `tp_address`         AS `address`,
                `tp_expertise`       AS `expertise`,
                `tp_mobile`          AS `mobile`,
                `tp_family`          AS `family`,
                `tp_name`            AS `name`,
                `tp_email`            AS `username`,
                `tp_password`        AS `password`,
                `tp_status`         AS `active_status`
            FROM
                `tp_users`
            WHERE
                $whr
            AND `tp_delete`='0'
            LIMIT 1
        ");
    }
}
