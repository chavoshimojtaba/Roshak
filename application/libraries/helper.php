<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

  
/*------------------------------------------------------------------------------ 
 * Main
 -----------------------------------------------------------------------------*/  
function slug_is_valid($slug, $table = 'product', $id = 0)
{

    $C = &get_instance();
    $C->load->model("model_util");
    $res = $C->model_util->check_slug_exist($table, $slug);
    if ($res->count > 0) {
        if ($id > 0 && $id == $res->result[0]['id']) {
            return true;
        }
        return false;
    }
    return 1;
}
 
function is_login($admin = false)
{
    if ($admin) {
        return (isset($_SESSION['uid'])) ? true : false;
    }
    return (isset($_SESSION['mid'])) ? true : false;
}
  
function thumbnail($dir, $width = '150')
{
    $ex = explode('/', $dir);
    $ex[count($ex) - 1] = 'w_' . $width . '/' . $ex[count($ex) - 1];
    return implode('/', $ex);
}

function isMobile()
{
    $tablet_browser = 0;
    $mobile_browser = 0;

    if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $tablet_browser++;
    }

    if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
    }

    if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
    }

    $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
    $mobile_agents = array(
        'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
        'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
        'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
        'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
        'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
        'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
        'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
        'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
        'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
    );

    if (in_array($mobile_ua, $mobile_agents)) {
        $mobile_browser++;
    }

    if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'opera mini') > 0) {
        $mobile_browser++;
        //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
            $tablet_browser++;
        }
    }
    if ($tablet_browser > 0 || $mobile_browser > 0) {
        return true;
    } else {
        return false; 
    }
}

function GLOBALS(string $key, bool $tree = true,$reset=false)
{
    if($reset){
        unset($GLOBALS[$key]);
    }
    if (!array_key_exists($key, $GLOBALS)) {
        $C = &get_instance();
        switch ($key) {
            case 'category':
                $C->load->model('model_category');
                $res = $C->model_category->get_list('product', false, true);
                if ($res->count) {
                    $data = buildTree($res->result);
                    $dataa = [];
                    foreach ($res->result as   $value) {
                        $dataa[$value['id']] = $value;
                    }
                    $GLOBALS['category_list'] =  $dataa;
                }
                break; 
            default:
                $data = [];
                break;
        }
        $GLOBALS[$key] = $data;
    }
    return !$tree ? $GLOBALS[$key . '_list'] : $GLOBALS[$key];
}

function create_query_filters($params, $tables)
{
    $q = [];
    $order = ' ORDER BY __table__.tp_id DESC';

    /////////////////////////// sort
    $sort = [
        'full_name' => '__table__.tp_family ',
        'createAt'  => '__table__.tp_date ',
        'serial'      => '__table__.tp_serial ',
        'cid'      => '__table__.tp_id ',
        'role'      => 'tp_role.tp_role ',
        'product_title'     => '__table__.tp_title ',
        'title'     => '__table__.tp_title ',
        'name'      => '__table__.tp_name ',
        'code'     => '__table__.tp_code ',
        'period'     => '__table__.tp_period ',
        'price'     => '__table__.tp_price ',
        'email'     => '__table__.tp_email ',
        'statistic_downloads'      => '__table__.tp_statistic_downloads ',
        'type'      => '__table__.tp_type ',
        'status'    => '__table__.tp_status ',
        'mobile'    => '__table__.tp_mobile '
    ];
    if ((isset($params['sort_by'])   && $params['sort_by'] != '') && isset($sort[$params['sort_by']])) {
        
        foreach ($tables as $key => $fields) { 
            if (in_array($params['sort_by'], $fields)) {
                $params['sort_type'] = (isset($params['sort_type']) && $params['sort_type'] != null) ? $params['sort_type'] : 'DESC';
                $order =  str_replace('__table__', $key, ' ORDER BY ' . $sort[$params['sort_by']] . $params['sort_type']);
            }
        }
    } 
    /////////////////////////// filter q
    if (isset($params['q'])  && $params['q'] != '' && $params['q_type'] != '' || $params['q_type'] == 'date') {
        $types = [
            'designer' => " AND  (__table__.tp_family LIKE '%{$params['q']}%' OR __table__.tp_name  LIKE '%{$params['q']}%') ",
            'full_name' => " AND  (__table__.tp_family LIKE '%{$params['q']}%' OR __table__.tp_name  LIKE '%{$params['q']}%') ",
            'name'    => " AND __table__.tp_name LIKE '%{$params['q']}%' ",
            'code'    => " AND __table__.tp_code LIKE '{$params['q']}' ",
            'product_title'    => " AND __table__.tp_title LIKE '%{$params['q']}%' ",
            'title'    => " AND __table__.tp_title LIKE '%{$params['q']}%' ",
            'expertise'    => " AND __table__.tp_expertise LIKE '{$params['q']}' ",
            'mobile'    => " AND __table__.tp_mobile LIKE '%{$params['q']}%' ",
            'email'    => " AND __table__.tp_email LIKE '%{$params['q']}%' "
        ];
        if (isset($params['date_to']) && $params['date_to'] != '' && $params['date_from'] != '') {
            $types['date'] =  " AND (__table__.tp_date BETWEEN   '{$params['date_from']}' AND '{$params['date_to']}')";
        }
        foreach ($tables as $key => $fields) {
            // pr($fields,true);
            if (isset($types[$params['q_type']]) && in_array($params['q_type'], $fields)) {
                $q[$params['q_type']] = str_replace('__table__', $key, $types[$params['q_type']]);
            }
        }
    }
    $order = str_replace('__table__', array_keys($tables)[0], $order);
    return [implode(' ', $q), $order];
} 

function categories()
{
    $C             = &get_instance();
    $C->load->model("model_category");
    $res_categories = $C->model_category->get_list('product');
    if ($res_categories->count > 0) {
        foreach ($res_categories->result as &$category) {
            $category['title'] = decode_html_tag($category['title'], true);
        }
        return $res_categories->result;
    }
    return [];
}

function generateAuthCode($mid, $status = 0, $type = 'member')
{
    $seed          = str_split('1234567890'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $code          = '';
    if ($type == 'user') {
        foreach (array_rand($seed, 8) as $k) $code .= $seed[$k];
    } else {
        foreach (array_rand($seed, 4) as $k) $code .= $seed[$k];
    }
    $C             = &get_instance();
    $C->load->model("model_notifications");
    $C->model_notifications->add_authentication_code([
        'type' => $type,
        'status' => $status,
        'code' => $code,
        'mid' => $mid
    ]);
    return $code;
}

function verifyAuthCode($code, $mid, $type = 'member')
{

    $C             = &get_instance();
    $C->load->model("model_notifications");
    $res = $C->model_notifications->verify_authentication_code($code, $mid, $type);
    // pr($res,true);
    return $code;
}

function send_message($mobiles, $params = [], $bodyId = '')
{
    $mobiles = is_array($mobiles) ? $mobiles : [$mobiles];
    @$params['type'] = $params['type'] ? $params['type'] : 'info';
    require_once(DIR_LIBRARY . 'Message.php');
    $message = new Message($bodyId, is_array($mobiles) ? $mobiles : [$mobiles]);
    $send =  $message->send($params);
    if (isset($params['db']) && $params['db']) {
        $C             = &get_instance();
        $C->load->model("model_notifications");
        $C->model_notifications->submit_notifications($params['type'], $message->text, array_keys($mobiles));
    }
    return $send;
}

function notification($params)
{
    $params = array_merge(
        ['type' => 'info', 'title' => 'اعلان'],
        $params
    );
    $C             = &get_instance();
    $C->load->model("model_notifications");
    $C->model_notifications->submit_common_notification($params['mid'], $params['text'], $params['type'], $params['title']);
    return true;
}

function validate($type, $value = '', $get_regex = false)
{
    switch ($type) {
        case 'mobile':
            $mobile = latinNum($value);
            if (preg_match('/^((09)|(\+\d(\d{1,3}))|9)(\d{9})/', $mobile)) {
                return $mobile;
            }
            break;
        case 'tel':
            if ($get_regex) {
                return "/^\d{8,12}$/";
            }
            break;
        default:
            break;
    }
    return false;
}

function regex($type)
{
    switch ($type) {
        case 'mobile':
            return '/^((09)|(\+\d(\d{1,3}))|9)(\d{9})/';
            break;
        case 'tel':
            return "/^\d{8,12}$/";
            break;
        default:
            break;
    }
    return false;
}
/*
* Created  : Thu Aug 04 2022 12:13:57 PM
* Author   : Chavoshi Mojtaba
* return   : array
*/
function city_list($pid = 0)
{
    $C = &get_instance();
    $C->load->model("model_extra");
    $res = $C->model_extra->city($pid);
    if ($res->count > 0) {
        return $res->result;
    }
    return [];
}

/*
* Created  : Thu Aug 04 2022 12:13:52 PM
* Author   : Chavoshi Mojtaba
* return   : array
*/

function province_list($cid = 0)
{
    $C = &get_instance();
    $C->load->model("model_extra");
    $res = $C->model_extra->province($cid);
    if ($res->count > 0) {
        return $res->result;
    }
    return [];
}

function createCityOption($selectedId = 0)
{
    $C = &get_instance();
    $C->load->model("model_extra");
    $res = $C->model_extra->city_province();
    $selected = '';
    $html = '';
    $list = [];
    foreach ($res->result as $key => $value) {
        $list[$value['pid']]['title']    = $value['province'];
        $list[$value['pid']]['childs'][] = $value;
    }
    foreach ($list as $key => $val) {
        $html .= '<optgroup label="' . $val['title'] . '">';
        foreach ($val['childs'] as   $city) {
            if ($selectedId > 0) {
                if ($selectedId == $city['cid']) {
                    $selected = 'selected';
                }
            }
            $html .= '<option value="' . $city['cid'] . '" ' . $selected . '>' . $city['city'] . '</option>';
        }
        $html .= '</optgroup>';
    }

    return $html;
}

function generatePublicToken()
{
    $seed = str_split('1234567890ABCDEFGHJKLMNOPQRSTUVWXYZabcdefghjklmnopqrstuvwxyz'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, 50) as $k) $rand .= $seed[$k];
    $code = $rand;
    return $code;
}
function generateString()
{
    $seed = str_split('abcdefghjklmnopqrstuvwxyz'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, 2) as $k) $rand .= $seed[$k];
    $code = $rand;
    return $code;
}

function _404()
{
    //pr(4,true);
    header("location:" . HOST . 'errors');
    exit;
}

function get_lang_constants()
{
    $vars = [
        'readMore' => LANG_READ_MORE,
        'readMessage' => LANG_READ_MESSAGE,  
        'selectTheFileToUpload' => LANG_SELECT_THE_FILE_TO_UPLOAD,
        'chooseFile' => LANG_CHOOSE_FILE,
        'theTicketWasSuccessfullySubmitted' => LANG_THE_TICKET_WAS_SUCCESSFULLY_SUBMITTED,
        'closed' => LANG_CLOSED,
        'opened' => LANG_OPENED,
        'allowedFormats' => LANG_ALLOWED_FORMATS,
        'search' => LANG_SEARCH,
        'success' => LANG_SUCCESS,
        'error' => LANG_ERROR,
        'warning' => LANG_WARNING,
        'formValidationErrorRequired' => LANG_FORM_VALIDATION_ERROR_REQUIRED,
        'formValidationErrorEmail' => LANG_FORM_VALIDATION_ERROR_EMAIL,
        'formValidationErrorNumber' => LANG_FORM_VALIDATION_ERROR_NUMBER,
        'formValidationErrorInteger' => LANG_FORM_VALIDATION_ERROR_INTEGER,
        'formValidationErrorUrl' => LANG_FORM_VALIDATION_ERROR_URL,
        'formValidationErrorTel' => LANG_FORM_VALIDATION_ERROR_TEL,
        'formValidationErrorMaxlength' => LANG_FORM_VALIDATION_ERROR_MAXLENGTH,
        'formValidationErrorMinlength' => LANG_FORM_VALIDATION_ERROR_MINLENGTH,
        'formValidationErrorMin' => LANG_FORM_VALIDATION_ERROR_MIN,
        'formValidationErrorMax' => LANG_FORM_VALIDATION_ERROR_MAX,
        'formValidationErrorPattern' => LANG_FORM_VALIDATION_ERROR_PATTERN, 
        'yourRequestHasBeenOurColleaguesWillContactYouSoon' => LANG_YOUR_REQUEST_HAS_BEEN_OUR_COLLEAGUES_WILL_CONTACT_YOU_SOON, 
        'invalidMobile' => LANG_INVALID_MOBILE,
        'addToCart' => LANG_ADD_TO_CART,  
        'confirmed' => LANG_CONFIRMED, 
        'rejected' => LANG_REJECTED, 
        'pend' => LANG_PEND,
        'edit' => LANG_EDIT,
        'delete' => LANG_DELETE
    ];

    return $vars;
}

function insert_Media($file)
{
    $C             = &get_instance();
    $C->load->model("model_file");
    $res = $C->model_file->insert_file($file);
    return $res;
}

function get_formats($type = '*', $additional = 0)
{
    if ($type == '*') {
        return '.webp,.jpeg,.jpg,.png,.gif,.zip,.pdf,.mp4,.xls,.xlsx,.csv,.txt';
    }
    $formats = [
        'image' => [
            'webp',
            'jpeg',
            'jpg',
            'png',
            'gif'
        ],
        'video' => [
            'mp4'
        ],
        'doc' => [
            'zip',
            'pdf',
            'xlsx',
            'csv',
            'xls',
            'txt'
        ]
    ];
    $formats_dot = [
        'image' => [
            '.webp',
            '.jpeg',
            '.jpg',
            '.png',
            '.gif'
        ],
        'video' => [
            '.mp4'
        ],
        'doc' => [
            '.pdf',
            '.zip',
            '.xlsx',
            '.csv',
            '.xls',
            '.txt'
        ]
    ];
    $formats_string = [
        "image" => [
            "'webp'",
            "'jpeg'",
            "'jpg'",
            "'png'",
            "'gif'",
        ],
        "video" => [
            "'mp4'"
        ],
        "doc" => [
            "'zip'",
            "'pdf'",
            "'csv'",
            "'xlsx'",
            "'xls'",
            "'txt'"
        ]
    ];
    if ($additional == 1) {
        return $formats_string[$type];
    }
    if ($additional == 2) {
        return $formats_dot[$type];
    }
    return $formats[$type];
}

function extType($ext)
{
    $dirs = [
        'webp' => 'image',
        'jpeg' => 'image',
        'jpg'  => 'image',
        'png'  => 'image',
        'gif'  => 'image',
        'mp4'  => 'video',
        'xls'  => 'doc',
        'pdf'  => 'doc',
        'xlsx' => 'doc',
        'csv'  => 'doc',
        'txt'  => 'doc'
    ];
    return $dirs[$ext];
}

function check_perm($resource, $perm)
{

    if (
        /*  $_SESSION['is_admin'] == 1 ||
        (
            isset($_SESSION['permissions'][$resource]) &&
            in_array($perm, $_SESSION['permissions'][$resource]))
        ) */
        $_SESSION['is_admin'] == 1 ||
        (
            isset($_SESSION['permissions'][$resource]))
    ) {
        return true;
    }
    return false;
}

function user_msg($data)
{
    $C             = &get_instance();
    $C->load->model("model_settings");
    $r             = $C->model_settings->set_user_message($data);
    return true;
}


function generate_verify_code($lnt = 5)
{
    $seed = str_split('1234567890'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, $lnt) as $k) $rand .= $seed[$k];
    $code = $rand;
    return $code;
}
function generate_order_traking_code($mid = 1)
{
    $seed = str_split('1234567890'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, 9) as $k) $rand .= $seed[$k];
    $code = $mid . $rand;
    return $code;
}

function uniqeId()
{
    $seed = str_split('1234567890'); // and any other characters
    shuffle($seed); // probably optional since array_is randomized; this may be redundant
    $rand = '';
    foreach (array_rand($seed, 5) as $k) $rand .= $seed[$k];
    $code = $rand;
    return $code;
}
 
function insert_files($context, $truest, $id)
{
    if (isset($_SESSION['img'])  && count($_SESSION['img']) > 0 && isset($truest->full_conf['conf_file'])) {
        $context->load->model('model_file');

        $file = $truest->full_conf['conf_file']['file'];

        if (isset($truest->full_conf['conf_file']['type']) && $truest->full_conf['conf_file']['type'] == 'multi') {
            foreach ($file as $section => $type) {
                if (isset($_SESSION['img'][$section])) {
                    foreach ($_SESSION['img'][$section] as $name_file => $detail_file) {
                        $context->model_file->insert_file_relation($detail_file['insert_id'], $id, $section, $type);
                    }
                }
            }
        } else {
            foreach ($file as $type => $section) {
                if (isset($_SESSION['img'][$type])) {
                    foreach ($_SESSION['img'][$type] as $name_file => $detail_file) {
                        $context->model_file->insert_file_relation($detail_file['insert_id'], $id, $section, $type);
                    }
                }
            }
        }
        unset($_SESSION['img']);
    }
}

function percent($price, $percent)
{
    return ($price / 100) * $percent;
}

function poll($value)
{
    return fa_int(number_format($value));
}

function rial_to_toman($amount)
{
    return $amount / 10;
}
function toman($value, $pre = false)
{
    if ($value <= 0) return '0';
    if ($pre === 1) {
        return   rial_to_toman($value);
    } else if ($pre) {
        return number_format(rial_to_toman($value)) . ' تومان';
    } else {
        return number_format(rial_to_toman($value));
    }
}

function poll_en($value)
{
    if ($value == '' || empty($value)) return '0';
    $C = &get_instance();
    $num = $C->security->only_number($value);
    if ($value < 0) {
        return '-' . number_format($num);
    }
    return (number_format($num));
}
function floor_down($price, $div = 1000)
{
    return floor($price / $div) * $div;
}

function check_melicode($melicode, $type = false, $mode = '')
{
    $v = &load_class('validate');
    $res_fn         = array();
    $res_fn['res']  = TRUE;
    $res_fn['msg']  = $melicode;

    if ($v->melicode($melicode) === FALSE) {
        $res_fn['res'] = FALSE;
        $res_fn['msg'] = _MSG_FAIL_MELICODE;
    }
    if ($type == TRUE) {
        $m = &load_class('model_applicant', 'model');
        $res = $m->exist_melicode($melicode);
        if ($mode == 'user') {
            $post = $_SESSION['post'];

            if (isset($post['id'])) {
                $user_id = unlock_string($post['id'], TRUE);
            } else {
                $user_id = $_SESSION['uid'];
            }
            if ($res->result[0]['tp_id'] != $user_id && $res->count > 0) {
                $res_fn['res'] = FALSE;
                $res_fn['msg'] = _MSG_REG_MELICODE;
                return $res_fn;
            }
        } else if ($res->count > 0) {
            $res_fn['res'] = FALSE;
            $res_fn['msg'] = _MSG_REG_MELICODE;
        }
    }
    return $res_fn;
}
  
function date2g($date)
{
    $res_fn         = array();
    $res_fn['res']  = TRUE;
    $res_fn['msg']  = p2g($date, TRUE);

    if (empty($date)) {
        $res_fn['msg']  = '0000-00-00';
        return $res_fn;
    }

    if ($res_fn['msg'] == FALSE) {
        $res_fn['res']  = FALSE;
        $res_fn['msg']  = _MSG_FAIL_DATE;
    }
    return $res_fn;
}

function check_repassword($value, $post)
{
    $res_fn         = array();
    $res_fn['res']  = TRUE;
    $res_fn['msg']  = $value;

    if ($value != $_POST[$post]) {
        $res_fn['res']  = FALSE;
        $res_fn['msg']  = LANG_FAIL_VIN;
    }
    return $res_fn;
}
 
function has_password($pass)
{
    $res_fn         = array();
    $res_fn['res']  = TRUE;
    $res_fn['msg']  = hash_password($pass);
    return $res_fn;
} 
 
function only_number($string, $type = false)
{
    $C = &get_instance();
    $res_fn = array();
    $res_fn['res'] = true;
    $res_fn['msg'] = $C->security->only_number($string);
    if ($type == true) return $res_fn['msg'];
    return $res_fn;
}
 
function only_int($string, $type = false)
{
    $C = &get_instance();
    $res_fn = array();
    $res_fn['res'] = true;
    $string = decode_html_tag($string, true);
    $res_fn['msg'] = trim(str_replace(',', '', $string));
    if ($type == true) return $res_fn['msg'];
    return $res_fn;
}
 
function just_string($string, $type = FALSE, $short = FALSE)
{
    $C              = &get_instance();
    $res_fn         = array();
    $res_fn['res']  = TRUE;
    $res_fn['msg']  = strip_tags($C->security->clear2html($string));
    if ($type === TRUE && $short == FALSE) return $res_fn['msg'];
    if ($type === TRUE && $short != FALSE) return summarize_str($res_fn['msg'], $short);
    return $res_fn;
}

function lock_string($string, $type = FALSE)
{
    $C = &get_instance();
    $res_fn = array();
    $res_fn['res'] = TRUE;
    $res_fn['msg'] = $C->security->lock_string($C->security->key, $string);
    if ($type === TRUE) return $res_fn['msg'];
    return $res_fn;
}

function lock_string_custom($string, $key, $type = FALSE)
{
    $C = &get_instance();
    $res_fn = array();
    $res_fn['res'] = TRUE;
    $res_fn['msg'] = $C->security->lock_string($key, $string);
    if ($type === TRUE) return $res_fn['msg'];
    return $res_fn;
} 

function unlock_string($string, $type = FALSE)
{
    $res_fn = array();
    $res_fn['res'] = TRUE;
    $C = &get_instance();
    $res_fn['msg'] = $C->security->clear($C->security->unlock_string($C->security->key, $string));
    if ($type === TRUE) return $res_fn['msg'];
    return $res_fn;
}

function unlock_string_custom($string, $key, $type = FALSE)
{
    $res_fn = array();
    $res_fn['res'] = TRUE;
    $C = &get_instance();
    $res_fn['msg'] = $C->security->clear($C->security->unlock_string($key, $string));
    if ($type === TRUE) return $res_fn['msg'];
    return $res_fn;
} 

function decode_html_tag($html_code, $type = FALSE, $int = false)
{
    $C = &get_instance();
    $res_fn = array();
    $res_fn['res'] = TRUE;
    $res_fn['msg'] = $C->security->clear2html($html_code);
    if ($type === TRUE && $int == false) return $res_fn['msg'];
    if ($type === TRUE && $int == 1) return fa_int($res_fn['msg']);
    if ($type === TRUE && $int == 2) return poll($res_fn['msg']);
    return $res_fn;
}
 
function encode_html_tag($html_code, $just_result = false)
{
    $C = &get_instance();
    $res_fn = array();
    $res_fn['res'] = TRUE;
    $res_fn['msg'] = $C->security->clearhtml($html_code);
    if ($just_result) {
        return $res_fn['msg'];
    }
    return $res_fn;
}


function encode_html_form($post)
{
    $inputs = [
        'name',
        'fullname',
        'full_name',
        'username',
        'title',
        'desc',
        'text',
        'email',
        'mail',
        'exp',
        'family',
        'address'
    ];
    $number_inputs = [
        'mobile',
        'tel',
        'age',
        'birthdate',
        'national_code',
        'weight',
        'size',
    ];
    $currency_inputs = [
        'price',
        'amount'
    ];
    foreach ($post as $key => $value) {
        if (in_array($key, $inputs)) {
            $post[$key] = encode_html_tag($value, true);
        }
        if (in_array($key, $number_inputs)) {
            $post[$key] = latinNum($value);
        }
        if (in_array($key, $currency_inputs)) {
            $post[$key] = str_replace(',', '', $value);
        }
    }
    return $post;
}


function check_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $res_fn = array();
        $res_fn['res'] = true;
        $res_fn['msg'] = $email;
    } else {
        $res_fn['res'] = false;
        $res_fn['msg'] = _MSG_FAIL_EMAIL_;
    }
    return $res_fn;
} 

function insert_file($control, $type_file, $inset_id, $bakhsh = '', $max = 0)
{
    $array_file = array();
    $control->load->model("model_file");
    if ($max == 0) {
        $max = $control->model_file->max_file_id();
    }

    foreach ($_SESSION['img'][$type_file] as $name => $file_inf) {
        $array_file[$name] = "(
            '" . $_SESSION['uid'] . "',
            '$inset_id',
            '$bakhsh',
            '$max',
            '" . $file_inf['name'] . "',
            '" . $file_inf['dir'] . "',
            '" . $file_inf['ext'] . "',
            '" . $file_inf['size'] . "',
            '" . $file_inf['old_name'] . "'
            ,now() )";
    }

    unset($_SESSION['img'][$type_file]);

    if (isset($array_file) and count($array_file) > 0) {
        $control->model_file->insert_file(implode(",", $array_file));
        unset($array_file);
    }
    return $max;
}


function values($grp, $sw = FALSE, $id = false)
{
    $C = &get_instance();
    $C->load->model("model_extra");
    $r = $C->model_extra->value($grp);
    $arr = array();
    if ($sw !== FALSE) {
        $arr[0] = LANG_SELECT;
    }
    for ($i = 0; $i < $r->count; $i++) {
        if ($id) {
            $arr[$r->result[$i]['tp_value']] = ['id' => $r->result[$i]['tp_id'], 'name' => $r->result[$i]['tp_name']];
        } else {
            $arr[$r->result[$i]['tp_value']] = $r->result[$i]['tp_name'];
        }
    }

    return $arr;
}

function new_pdate($date)
{
    $pdate = &load_class('persian_date', 'libraries');
    $date = $pdate->to_date(substr($date, 0, 10), 'Y/m/d');
    list($year, $month, $day) = explode("/", $date);
    $dayOfWeek = date('N');

    switch ($dayOfWeek) {
        case 1:
            $dayOfWeek = "دوشنبه";
            break;
        case 2:
            $dayOfWeek = "سه شنبه";
            break;
        case 3:
            $dayOfWeek = "چهار شنبه";
            break;
        case 4:
            $dayOfWeek = "پنج شنبه";
            break;
        case 5:
            $dayOfWeek = "جمعه";
            break;
        case 6:
            $dayOfWeek = "شنبه";
            break;
        case 7:
            $dayOfWeek = "یک شنبه";
            break;
    }
    switch ($month) {
        case 1:
            $monthName = "فروردین";
            break;
        case 2:
            $monthName = "اردیبهشت";
            break;
        case 3:
            $monthName = "خرداد";
            break;
        case 4:
            $monthName = "تیر";
            break;
        case 5:
            $monthName = "مرداد";
            break;
        case 6:
            $monthName = "شهریور";
            break;
        case 7:
            $monthName = "مهر";
            break;
        case 8:
            $monthName = "آبان";
            break;
        case 9:
            $monthName = "آذر";
            break;
        case 10:
            $monthName = "دی";
            break;
        case 11:
            $monthName = "بهمن";
            break;
        case 12:
            $monthName = "اسفند";
            break;
    }
    return $dayOfWeek . " " . $day . " " . $monthName . " " . $year . " ";
}

function latinNum($string)
{
    $newNumbers = range(0, 9);
    // 1. Persian HTML decimal
    $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
    // 2. Arabic HTML decimal
    $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
    // 3. Arabic Numeric
    $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
    // 4. Persian Numeric
    $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

    $string =  str_replace($persianDecimal, $newNumbers, $string);
    $string =  str_replace($arabicDecimal, $newNumbers, $string);
    $string =  str_replace($arabic, $newNumbers, $string);
    return str_replace($persian, $newNumbers, $string);
}

function E_164_mobile_structure($string)
{
    return str_replace('+', '00', $string);
}


/* 
////depricated
function query_filters($params, $tables)
{
    $q = [];
    $order = ' ORDER BY __table__.tp_id DESC';
    if (isset($params['date_to']) && $params['date_to'] != '' && $params['date_from'] != '') {
        $date =  " AND (__table__.tp_date BETWEEN   '{$params['date_from']}' AND '{$params['date_to']}')";
    } else {
        $date = '';
    }
    $sort = [
        'full_name' => '__table__.tp_family ',
        'createAt'  => '__table__.tp_date ',
        'role'      => 'tp_role.tp_role ',
        'title'     => '__table__.tp_title ',
        'name'      => '__table__.tp_name ',
        'period'     => '__table__.tp_period ',
        'price'     => '__table__.tp_price ',
        'email'     => '__table__.tp_email ',
        'type'      => '__table__.tp_type ',
        'status'    => '__table__.tp_status ',
        'mobile'    => '__table__.tp_mobile '
    ];
    if (isset($params['sort_by']) && isset($sort[$params['sort_by']])  && $params['sort_type'] != null &&   $params['sort_by']) {
        foreach ($tables as $table_name => $table) {
            if (is_array($table)) {
                if (in_array($params['sort_by'], $table)) {
                    $order = ' ORDER BY ' . str_replace('__table__', $table_name, $sort[$params['sort_by']])  . $params['sort_type'];
                }
            } else {
                if ($params['sort_by'] == $table) {
                    $order = ' ORDER BY ' . str_replace('__table__', $table_name, $sort[$params['sort_by']])  . $params['sort_type'];
                }
            }
        }
    }
    if (isset($params['q'])  && $params['q'] != '' && $params['q_type'] != '') {
        $types = [
            'date'    => $date,
            'designer' => " AND  (__table__.tp_family LIKE '%{$params['q']}%' OR __table__.tp_name  LIKE '%{$params['q']}%') ",
            'full_name' => " AND  (__table__.tp_family LIKE '%{$params['q']}%' OR __table__.tp_name  LIKE '%{$params['q']}%') ",
            'name'    => " AND __table__.tp_name LIKE '{$params['q']}' ",
            'code'    => " AND __table__.tp_code LIKE '{$params['q']}' ",
            'product_title'    => " AND __table__.tp_title LIKE '{$params['q']}' ",
            'title'    => " AND __table__.tp_title LIKE '{$params['q']}' ",
            'expertise'    => " AND __table__.tp_expertise LIKE '{$params['q']}' ",
            'title'    => " AND __table__.tp_title LIKE '%{$params['q']}%' ",
            'mobile'    => " AND __table__.tp_mobile LIKE '%{$params['q']}%' ",
            'email'    => " AND __table__.tp_email LIKE '%{$params['q']}%' "
        ];
        foreach ($tables as $key => $fields) {

            // //pr($fields,true);
            if (!is_array($fields)) {
                if (isset($types[$fields])) {
                    $q[$fields] = str_replace('__table__', $key, $types[$fields]);
                }
            } else {
                $q[$params['q_type']] = str_replace('__table__', $key, $types[$params['q_type']]);
                // //pr($types[$field],true);
            }
        }
    }
    $order = str_replace('__table__', array_keys($tables)[0], $order);
    return [implode(' ', $q), $order];
}
 */