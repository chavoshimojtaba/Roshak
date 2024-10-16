<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class sms {
    
    public $sys = null;
    
    public $config = array();
    
    public static $client = null;
    
    public $to = array();
    
    public $recId = array();
    
    public $message = '';
    
    public $reciver = 0;
    
    public $msg_status = array(
        'نام کاربری و رمز اشتباه',
        'ارسال موفق',
        'کمبود اعتبار',
        'محدودیت ارسال روزانه',
        'محدودیت در ارسال',
        'شماره مجازی غیر مجاز',
        'در صف ارسال',
        'کلمات فیلتر شده'
    );
    
    
    
    function __construct()
    {
        ini_set('soap.wsdl_cache_enabled', '0');        
    }
    
    
    function init ($conf_name='default')
    {
        $c = &get_instance();
        $c->load->model('model_settings');
        
        $res = $c->model_settings->fetch_sms_setting(0,1);
        if ( $res->count == 0 ) exit('SMS : eror !');
        
        foreach ($res->result[0] as $column=>$value)
        {
            $this->config[$column] = decode_html_tag($value,true);
        }
        
        if ( function_exists('xdebug_disable') )
        { 
            xdebug_disable(); 
        }
        try
        {  
            self::$client = @new SoapClient($this->config['url'],array('encoding'=>'UTF-8','exceptions' => true ));
        }
        catch (SoapFault $E)
        {  
            return array('error'=>TRUE,'text'=>$E->faultstring);
        }
        
        if(function_exists('xdebug_disable'))
        { 
            xdebug_enable(); 
        }
        
        $this->sys = &get_instance();
    }
    
    
    function get_credit()
    {
        $getcredit_parameters = [
            'username' => $this->config['user'],
            'password' => $this->config['pass']
        ];
        return self::$client->GetCredit($getcredit_parameters)->GetCreditResult;
    }
    
    
    function send_sms ()
    {
        try {
            $textMessage = iconv($this->config['unicode'], 'UTF-8//TRANSLIT',trim($this->message));

            $sendsms_parameters = array(
                'username' => $this->config['user'],
                'password' => $this->config['pass'],
                'from'     => $this->config['from'],
                'to'       => $this->to,
                'text'     => $textMessage,
                'isflash'  => false,
                'udh'      => '',
                'recId'    => $this->recId,
                'status'   => 0
            );
            
            $status = self::$client->SendSms($sendsms_parameters)->SendSmsResult;
            
            $uid = isset($_SESSION['uid']) ? $_SESSION['uid']:0;
            
            foreach ( $this->to as $k=>$number )
            {
                $sql[] = " ('$uid','".$this->sys->router->class."','".$this->sys->router->method."','".$this->sys->router->ip()."','".$this->sys->router->agent()."','$number','".$this->recId[$k]."','$textMessage','$status',now()) " ;
            }

            if ( isset($sql) && count($sql) > 0 )
            {
                $this->sys->load->model('model_log');
                $this->sys->model_log->log_sms(implode(',',$sql));
            }

            return array($status,$this->msg_status[$status]);
        } catch (Exception $exc) {
        }
    }
    
    
    function ping($url=NULL)  
    {  
        if($url == NULL) return false;  
        $ch = curl_init($url);  
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
        $data = curl_exec($ch);  
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
        curl_close($ch);  
        if($httpcode>=200 && $httpcode<300)
        {  
            return true;  
        }
        else
        {  
            return false;  
        }  
    }
    
    
    function get_msg ()
    {
        $getnewmessage_parameters = array(
            'username' => $this->config['user'],
            'password' => $this->config['pass'],
            'from'     => $this->config['from']
        );
        $incomingMessagesClient = new SoapClient($this->config['url_msg']);
        return $incomingMessagesClient->GetNewMessagesList($getnewmessage_parameters);
    }
        
}