<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class security {

    protected $clean_string = array (
        '<script>',
        '</script>',
        'script',
        'javascript',
        'document.cookie',
        'document.write',
        '.parentNode',
        '.innerHTML',
        'window.location',
        '-moz-binding',
        '<!--',
        '-->',
        '<![CDATA[',
        '<comment>',
        'SELECT',
        'select',
        '../',
        './',
        '\\',
        '/ ',
        'object',
        'applet',
        'embed',
        'form ',
        '</form>',
        '--'
    );

    protected $find_bad_char = array (
        '"',
        "'",
        '&',
        '<',
        '>',
        '@',
        '#',
        '!',
        '?',
        ')',
        '(',
        '{',
        '}',
        '%',
        ';',
        ':',
        '.',
        '*',
        '^',
        ',',
        "/\/"
    );

    protected $replace_real_char = array (
        '[wq]',
        '[sq]',
        '[and]',
        '[sl]',
        '[sr]',
        '[at]',
        '[sharp]',
        '[tajob]',
        '[ques]',
        '[pr]',
        '[pl]',
        '[aql]',
        '[aqr]',
        '[darsad]',
        '[semi]',
        '[colon]',
        '[dot]',
        '[star]',
        '[hat]',
        '[comma]',
        '[backSlAsH]'
    );


    protected $_php_code = array (
        'php',
        '<?',
        'expression',
        'system ',
        'fopen',
        'fsockopen',
        'file ',
        'file_get_contents',
        'readfile',
        'unlink',
        'cmd',
        'passthru',
        'eval',
        'exec',
        '..'
    );

    protected $code = array (
        'Qaz',
        'WG1',
        'JK8',
        'nmq',
        '3b2',
        'Kl9',
        'S85',
        'Gkg',
        'Kxy',
        'w0x'
    );

    var $key = '#a^gH!@';
    var $symbol = ['~','!','@','#','$','%','^','&','*','+'];


    protected $alphabet = ['a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];

    protected $mixed    = [];


    function __construct(){
        //pr($_SERVER,1);
    }

    function set_key ()
    {
        if ( !isset($_SESSION['key_symbol']) )
        {
            shuffle($this->symbol);
            $alfa = $this->alphabet;
            shuffle($alfa);
            $_SESSION['key_symbol'] = $this->symbol;
            $_SESSION['key_mixed']  = $alfa;
        }
        $this->mixed = $_SESSION['key_mixed'];

        $token = isset($_SESSION['token'])?$_SESSION['token']:$_SESSION['user_token'];
        $len   = (strlen($token)/2);
        $str   = substr($token, $len, 5);
        $this->key = $_SESSION['key_symbol'][0].$str.$_SESSION['key_symbol'][9];
    }

    function clear ($String='null')
    {
        //if (is_array($String)) return false;

        $String = str_replace( $this->clean_string,'',$String);
        $String = str_replace( $this->find_bad_char , $this->replace_real_char , $String );
        $String = strip_tags($String);
        $String = trim($String);
        return $this->php_clean ($String);
    }

    function clearhtml($string)
    {
        return str_replace ($this->find_bad_char,$this->replace_real_char,$string );
    }

    function clear2html($string)
    {
        return str_replace ($this->replace_real_char,$this->find_bad_char,$string );
    }


    function php_clean ($String)
    {
        return str_replace ( $this->_php_code,'',$String);
    }


    function clear_request ($String,$stip=FALSE)
    {
        $String = str_replace ( $this->_php_code,'',$String);
        $String = str_replace ( $this->clean_string,'',$String);
        if ( $stip === FALSE) return $String;
        return strip_tags($String);
    }

    function encode_html ($String)
    {
        if (is_array($String)){
            foreach ($str as $key => $value){

                if (is_array($value)){
                    $output[$key]=$this->encode_html($value);
                }
                else {
                    $output[$key]=trim(str_ireplace($this->find_bad_char, $this->replace_real_char, $value));
                }
            }
        }
        else {
                $output=trim(str_ireplace($this->find_bad_char,$this->replace_real_char, $String));
        }
        return $output;
    }


    function decode_html ($String)
    {
        return str_replace ( $this->replace_real_char , $this->find_bad_char , $String );
    }

    function just_string($string)
    {
        return str_replace ( $this->replace_real_char , ' ' , $string );
    }

    function input ( $request = 'post' , $data=array(),$stip=FALSE)
    {
        $input = array ();

        switch ( $request )
        {
            case 'api':$method=$data;break;
            case 'post':$method=$_POST;break;
            case 'get':$method= $_GET ;break;
            case 'request':$method=$_REQUEST;break;
            default: $method = $data;break;
        }

        if ( isset ( $method ) AND is_array($method))
        {
            foreach ( $method as $key=>$value )
            {
                if ( is_array ( $key ) )
                {
                    $input[$this->clear($key)] = $this->input($request,$method[$key]);
                }
                else if(is_array ( $value ))
                {
                    foreach ($value as $k=>$v )
                    {
                        $input[$key][$k] = $this->clear($v,$stip);
                    }
                }
                else
                {
                    $input[$this->clear($key)] = $this->clear($value,$stip);
                }
            }
        }
        return $input;
    }


    function qout_rep ($value)
    {
        return str_replace("'","[qout]", $value);
    }

    function return_qout_rep ($value)
    {
        return str_replace("[qout]","'", $value);
    }

    function is_int ($string)
    {
        $string = substr($string,0,11);
        preg_match_all("/[^0-9]+/",$string,$matches);
        if ( count($matches[0]) > 0 ) return false;
        return true;
    }

    public function base64url_encode($data) {
      return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data) {
      return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    function lock_string ($key,$string='null'){
        $a = str_split($this->base64url_encode($key.$string));
        $b = array_reverse($a);
        $c = [];
        for ($i=0;$i<count($b);$i++)
        {
            if (in_array($b[$i],  $this->alphabet))
            {
                $key   = array_search($b[$i],$this->alphabet);
                $b[$i] = $this->mixed[$key];
            }
            if ( $i%2 == 0 )
            {
                $c[$i+1] = $b[$i];
            }
            else
            {
                $c[$i-1] = $b[$i];
            }
        }
        ksort($c);
        return implode('',$c);
    }

    function unlock_string ($key,$string){
        $d = str_split($string);
        $temp = [];
        $j = 0;
        for ($i=0;$i<count($d);$i++)
        {
            if ( $i%2 == 0 )
            {
                $j = $i+1;
                $temp[$j] = $d[$i];
            }
            else
            {
                $j = $i-1;
                $temp[$j] = $d[$i];
            }

            if (in_array($d[$i],  $this->mixed))
            {
                $k   = array_search($d[$i],$this->mixed);
                $temp[$j] = $this->alphabet[$k];
            }

        }
        ksort($temp);
        $temp = array_reverse($temp);
        $string = implode('',$temp);
        $str = $this->base64url_decode($string);
        if ( substr($str,0,strlen($key)) != $key ) return FALSE;
        return substr($str,strlen($key));
    }

    function check_id ($key,$id)
    {
        $id = $this->unlock_string($key,$id);
        if ( $this->is_int($id) == FALSE OR $id === FALSE)
        {
            get_error (
                array(
                    'id|fail'=>LANG_CHANGE_DATA
                ),
                'Danger',
                'change string',
                false
            );
            redirect(HOST.'errors/e_404');
            exit();
        }
        return $id;
    }

    function only_number($string) {
        return preg_replace('/[^0-9]/', '', $string);
    }

    function static_encode ($str){
        return urlencode( base64_encode( $str ));
    }

    function static_decode ($encoded){
        return base64_decode( urldecode( $encoded ) );
    }
}
