<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class model_log_error extends DB{
	
	
    static $table_menu = '`log_error`';

    function insert_log ($control,$index,$ul,$ip,$query_string,$dir_file,$path,$uid,$error,$desc,$mac)
    {
        if(is_array($query_string))
        {
            $query_string = str_replace($this->skip_chars, $this->_rep, implode(",",$query_string));
        }
        else
        {
            $query_string = str_replace($this->skip_chars, $this->_rep,$query_string);  
        }
        
        if ( isset($_REQUEST) )
        {
            foreach ($_REQUEST as $k=>$v)
            {
                if(!is_array($v))
                {
                    $request[] = "{$k}={$v}";
                }
            }
            $data = str_replace($this->skip_chars, $this->_rep, implode(",",$request));
        }
        else
        {
            $data = "";
        }
        
        if ( $this->insert("
            INSERT INTO 
                ".self::$table_menu."
            SET
                `tp_control` = '$control',
                `tp_index`   = '$index',
                `tp_value`   = '$ul',
                `tp_ip`      = '$ip',
                `tp_req`     = '$query_string',
                `tp_file`    = '$dir_file',
                `tp_uid`     = '$uid',
                `tp_path`    = '$path',
                `tp_data`    = '$data',
                `tp_error`   = '$error',
                `tp_desc`    = '$desc',
                `tp_mac`     = '$mac',
                `tp_date`    = now()") ) return TRUE;
        else return FALSE;
    }
    
    function detect_attack ($uid,$ip)
    {
        return $this->insert("
            INSERT INTO
                `log_request_ip`
            SET
                `tp_uid`   = '$uid' ,
                `tp_ip`    = '$ip' ,
                `tp_date`  = now()
        ");
    }
    
    //function 
    
    function update_quarantine ()
    {
        $this->update("
            UPDATE
                `log_request_ip`
            SET
                `tp_delete` = '1' ,
                `tp_update` = now()
            WHERE
                `tp_delete` = '0'
            AND `tp_date` < DATE_SUB(NOW(), INTERVAL 48 HOUR)
        ");
        
        $this->update("
            UPDATE
                `log_ban_ip`
            SET
                `tp_delete` = '1' ,
                `tp_update` = now()
            WHERE
                `tp_delete` = '0'
            AND `tp_date` < DATE_SUB(NOW(), INTERVAL 48 HOUR)
        ");
    }
    
    function add_quarantine ($uid,$ip)
    {
        return $this->insert("
            INSERT INTO
                `log_ban_ip`
            SET
                `tp_uid`   = '$uid' ,
                `tp_ip`    = '$ip' ,
                `tp_date`  = now()
        ");
    }
    
    function check_in_quarantine ($ip)
    {
        return $this->select("
            SELECT 
                COUNT(*) AS `total` 
            FROM 
                `log_ban_ip` 
            WHERE 
                    `tp_ip` ='$ip' 
                AND `tp_date` > DATE_SUB(NOW(), INTERVAL 48 HOUR)
                AND `tp_delete` = '0'
        ");
    }
    
    function check_ip ($ip)
    {
        return $this->select("
            SELECT 
                COUNT(*) AS `total` 
            FROM 
                `log_request_ip` 
            WHERE 
                    `tp_ip` ='$ip' 
                AND `tp_date` > SUBTIME(NOW(), '00:01:00')
                AND `tp_delete` = '0'
        ");
    }
    
}