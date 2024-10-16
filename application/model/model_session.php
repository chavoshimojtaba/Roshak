<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_session extends DB{


    static $table_login = ' `user_login` ';


    public function insert_login ($mac)
    {
        $uid = (isset($_SESSION['uid']))?$_SESSION['uid']:$_SESSION['mid'];
        return $this->insert("
            INSERT INTO
                ".self::$table_login."
            SET
                `tp_ip`    = '".$_SESSION['ip']."',
                `tp_agent` = '".$_SESSION['agent']."',
                `tp_uid`   = '".$uid."',
                `tp_token` = '".$_SESSION['token']."',
                `tp_path`  = '".$_SESSION['path']."',
                `tp_mac`   = '$mac',
                `tp_date`  = now()
        ");
    }

	public function check_login ($id,$ip,$uid,$token,$path)
    {    
        $res = $this->select("
            SELECT
                `tp_id`
            FROM
                `user_login`
            WHERE 
                `tp_uid`   = '$uid'
            AND `tp_token` = '$token'
        "); 
        if ( $res != false AND isset ($res->count) AND $res->count > 0 )
        {
            return true;
        }
        else
            return false;
    }



}
