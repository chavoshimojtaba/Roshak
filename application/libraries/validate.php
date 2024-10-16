<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class validate {
    
    public $data = array();
    
    public $sys = null;
    
    
    function __construct() {
        $this->sys = &get_instance();
    }
    
    
    function melicode ($melicode)
    {
        
        $not_in = array(
            '0000000000',
            '1111111111',
            '2222222222',
            '3333333333',
            '4444444444',
            '5555555555',
            '6666666666',
            '777777777',
            '8888888888',
            '9999999999'
        );
        
        if (strlen($melicode) != 10 ) return false;
        
        if (in_array($melicode, $not_in) ) return false;
        $intSum = 0;
        for ($i=0;$i<9;$i++)
        {
            $intSum += (int)substr($melicode, $i, 1) * (10-$i);
        }
        $intD = $intSum%11;
        $intC = 11 - $intD;
        $intP = (int)substr($melicode, 9, 1);
        if ((($intD == 0 OR $intD == 1) AND $intP == $intD) OR ($intD > 1 AND $intP == $intC))
            {
                return true;
        }
            else
            {
                return false;
        }
    }
    
    function check_national_id  ($shenase)
    {
        if (strlen($shenase) !=11 )
        {
            return false;
        }
        $i=(((int)substr( $shenase , 0, 1)+
        (int)substr( $shenase , 9, 1)+2)*29)+
        (((int)  substr( $shenase , 1, 1)+
        (int)  substr( $shenase , 9, 1)+2)*27)+
        (((int)  substr( $shenase , 2, 1)+
        (int)  substr( $shenase , 9, 1)+2)*23)+
        (((int)  substr( $shenase , 3, 1)+
        (int)  substr( $shenase , 9, 1)+2)*19)
        +(((int)  substr( $shenase , 4, 1)
        +(int)  substr( $shenase , 9, 1)+2)*17)
        +(((int)  substr( $shenase , 5, 1)
        +(int)  substr( $shenase , 9, 1)+2)*29)
        +(((int)  substr( $shenase , 6, 1)
        +(int)  substr( $shenase , 9, 1)+2)*27)
        +(((int)  substr( $shenase , 7, 1)
        +(int)  substr( $shenase , 9, 1)+2)*23)
        +(((int)  substr( $shenase , 8, 1)
        +(int)  substr( $shenase , 9, 1)+2)*19)
        +(((int)  substr( $shenase , 9, 1)
        +(int)  substr( $shenase , 9, 1)+2)*17) ;
        $checkDG = $i % 11;
        
        if ($checkDG == 10)
        { 
            $checkDG = 0 ; 
        }
        if ( $checkDG == ((int)substr($shenase,10, 1)) )
        {
            return true;
        }else
        {
          return false;
        }
    }
            
    
    
    function check_captcha ()
    {
        
    }
    
    function is_password ()
    {
        
    }
    
    function has_password ()
    {
        
    }
    
    
    function just_string()
    {
        
    }
    
    
    function lock_string()
    {
        
    } 
    
    
    function unlock_string()
    {
        
    } 
    
    function decode_html_tag()
    {
        
    } 
    
    
    function encode_html_tag()
    {
    } 
    
    function check_email()
    {
    } 
    
}