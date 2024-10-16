<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
if (!isset($_SERVER['HTTP_REFERER']) or empty($_SERVER['HTTP_REFERER'])) {
    header('location:' . HOST . '/e_404');
    exit();
}
require_once(DIR_LIBRARY."JwtMiddleWare.php");

class api extends Controller{

    public $requestInfo = [] ;
    public $curentVersion = 'v1';
    public $method = '';
    public $input = '';

    private function parseUrl()
    {
        $request = trim($_SERVER['REQUEST_URI'], '/');
        $request = filter_var($request, FILTER_SANITIZE_URL);
        $request = explode('?', $request)[0];
        $request = array_slice(explode('/', $request),(($_SERVER['HTTP_HOST'] == 'localhost')?2:1));
        return $request;
    }
    public function __construct() {
        parent::__construct();
        $this->method = strtolower($_SERVER["REQUEST_METHOD"]);
        $this->input = (array) json_decode(file_get_contents('php://input'), true);
        if(!$this->input){
            $headers = getallheaders();
            if(isset($headers['Content-Type']) && strpos($headers['Content-Type'],'multipart/form-data') >= 0){
                $this->input = $_POST;
            }
        }

        $this->_request();
	}

    public function _route() {
        $path = str_replace('/','',$this->router->method); 
        if($path == $this->curentVersion){
            if($this->requestInfo['requestArray'][1] != 'auth'){
                $jwt    = new JwtMiddleWare();
                $isAuth = $jwt->getAndDecodeToken();
                if (!$isAuth) { 
                    $this->_response(403, [
                        'status' => 'Unauthorized' ,
                        'msg'    => 'شما اجاز دسترسی به درخواست مورد نظر را ندارید.' ,
                        'error'  => 2
                    ]);
                }else if ($isAuth == 'expired') { 
                    $this->session->log_out();
                    $this->_response(401, [
                        'status' => 'Unauthorized' ,
                        'msg'    => 'شما اجاز دسترسی به درخواست مورد نظر را ندارید.ابتدا وارد حساب کاربری خود شوید.' ,
                        'error'  => 3//'token expired'
                    ]);
                }
            }
            $routes = [
                'get' => [
                    [
                        'path'  => 'v1/tag/list' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/list_loc' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/list_vacancies' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/util/slug_validation' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/util/multi_select' ,
                        'param' => []
                    ],
                   
                    [
                        'path'  => 'v1/setting/footer_links' ,
                        'param' => []
                    ],
                
                    [
                        'path'  => 'v1/setting/header_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/public_links' ,
                        'param' => []
                    ],
                    
                    [
                        'path'  => 'v1/member/search' ,
                        'param' => []
                    ],
                     
                      
                    [
                        'path'  => 'v1/product/list' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/category/edit' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/member_messages' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/pages/edit_faq' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/edit' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/list' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/notification/templates' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/list' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/ticket/list' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/ticket/view' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/user/list' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/file/category' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/file/detail' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/file/list' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/role/list' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/category/list' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/role/permission_list' ,
                        'param' => []
                    ]
                ],
                'post' => [
                    [
                        'path'  => 'v1/tag/add' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/add_vacancies' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/content/add_loc' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/setting/add_footer_links' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/setting/add_header_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/add_public_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/add_user_stories' ,
                        'param' => []
                    ],
                    
                    [
                        'path'  => 'v1/product/add' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/auth/verify' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/auth/login' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/auth/change_password' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/auth/forgot_pass' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/upload_translations' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/add_faq' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/slider/add' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/add_agent' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/add_event' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/add' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/notification/add_template' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/notification/message' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/file/add_cat' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/ticket/reply' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/user/add' ,
                        'param' => []
                    ],  
                    [
                        'path'  => 'v1/role/add' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/role/permissions' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/file/add' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/category/add' ,
                        'param' => []
                    ]

                ],
                'put' => [
                    [
                        'path'  => 'v1/member/update_expertise' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/order_vacancies' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/order_loc' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/order_vacancies' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/content/update_loc' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/category/order' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/member_messages_read' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/pages/update_team_members' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/update_footer_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/update_event' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/update_footer_columns' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/update_header_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/update_public_links' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/plan/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/product/status' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/product/update_attribute' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/product/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/update_profile_designer' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/update_profile' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/confirm_change_type' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/reject_change_type' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/change_password' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/member/update_prfile' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/confirm_agency_request' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/update_event' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/update_about_us' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/update_agent' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/brand/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/social_update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/update_faq' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/seo_update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/slider/order' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/slider/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/notification/update_template' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/user/update_prfile' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/user/change_password' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/file/up_cat' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/ticket/refer' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/ticket/close' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/comment/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/comment/edit' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/user/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/blog/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/role/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/category/update' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/tag/update' ,
                        'param' => []
                    ]
                ],
                'delete' => [
                    [
                        'path'  => 'v1/content/delete_vacancies' ,
                        'param' => []
                    ],  
                    [
                        'path'  => 'v1/content/delete_loc' ,
                        'param' => []
                    ],  
                    [
                        'path'  => 'v1/setting/delete_footer_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/delete_public_links' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/setting/delete_header_links' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/product/delete' ,
                        'param' => []
                    ],   
                    [
                        'path'  => 'v1/pages/del_faq' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/notification/del_template' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/file/del_cat' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/slider/delete' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/pages/delete' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/ticket/delete' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/user/delete' ,
                        'param' => []
                    ], 
                    [
                        'path'  => 'v1/file/delete' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/role/delete' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/category/delete' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'v1/tag/delete' ,
                        'param' => []
                    ] 
                ]
            ];
        }else{ 
            $routes = [
                'get' => [
                    [
                        'path'  => 'cv1/util/slug_validation' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/financial/designer_sell' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/ticket/list' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/plan/list' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/util/get_events' ,
                        'validation'=>false,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/util/multi_select' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/util/search' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/list' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/favorite' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/ticket' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/notification' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/designers' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/comment' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/favorites' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/general_detail' ,
                        'validation'=>0,
                        'param' => []
                    ], 
                    [
                        'path'  => 'cv1/comment/member_comments' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/favorites' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/financial/settlement_requests' ,
                        'validation'=>true,
                        'param' => []
                    ], 
                    [
                        'path'  => 'cv1/order/download' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/order/discount_code' ,
                        'validation'=>true,
                        'param' => []
                    ],
                ],
                'post' => [
                    [
                        'path'  => 'cv1/auth/password' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/file/add' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/file/add_ftp' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/follow' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/add' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/update' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/upgrade_profile' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/update_profile' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/change_password' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/financial/add_settlement_requests' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/util/member_message' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/request_plan' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/update_profile' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/auth/resend_code' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/auth/verify' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/auth/login' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/add_to_compare' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/comment/like' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/add_to_cart' ,
                        'validation'=>0,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/comment/add' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/rating' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/add_to_favorites' ,
                        'validation'=>true,
                        'param' => []
                    ], 
                    [
                        'path'  => 'cv1/pages/member_messages' ,
                        'validation'=>true,
                        'param' => []
                    ], 
                    [
                        'path'  => 'cv1/comment/add' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/ticket/add' ,
                        'validation'=>true,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/ticket/reply' ,
                        'validation'=>true,
                        'param' => []
                    ],
                ],
                'put' => [
                    [
                        'path'  => 'cv1/ticket/reply' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/avatar' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/cover' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/member/edit_address' ,
                        'param' => []
                    ],
                    [
                        'path'  => 'cv1/product/update' ,
                        'param' => []
                    ],
                ],
                'delete' => [
                    [
                        'path'  => 'cv1/comment/delete' ,
                        'param' => []
                    ] ,
                    [
                        'path'  => 'cv1/member/remove_favorite' ,
                        'param' => []
                    ] ,
                    [
                        'path'  => 'cv1/member/remove_address' ,
                        'param' => []
                    ]
                ]
            ]; 

            if(in_array($this->method , ['delete','put'])){  
                $this->checkToken();
            } 
        } 
        if ( isset($routes[$this->method])) {
            $find = false;
            foreach ($routes[$this->method] as $route) {
                if ( $route['path'] == $this->requestInfo['path']) { 
                   /*  if($route['validation']){
                        $this->checkToken();
                    } */
                    list($folder,$class,$method) = $this->requestInfo['requestArray'];
                    require_once $folder.'/'.$class.'.php';
                    $service = new $class;
                    $service->init($this,$this->getParam());
                    $service->$method($this->requestInfo['param'],$this->getParam());
                    $find = true;
                    break;
                }
            } 
            if ( $find === false ) {
                $this->_response(404, [
                    'status' => 'fail' ,
                    'msg'    => 'not found' ,
                    'error'  => 2
                ]);
            }
        }else{
            $this->_response(404, [
                'status' => 'fail' ,
                'msg'    => 'not found' ,
                'error'  => 1
            ]);
        }

    }

    public function _request() {

        $requestArray = $this->parseUrl();

        $temp = [];
        $i = 0;
        $param = [];

        foreach ($requestArray as $item) {
            if (!empty($item)) {
                if ( $i < 3) {
                    if(is_numeric($item)){
                        if($this->method === 'delete'){
                            $temp[] = 'delete';
                        }else if($this->method === 'post'){
                            $temp[] = 'add';
                        }else if($this->method === 'put'){
                            $temp[] = 'update';
                        }
                        $param[] = $item;
                    }else{
                        $temp[] = $item;
                    }
                }else{
                    $param[] = $item;
                }
                $i++;
            }
        }
        if(count($temp) <= 2){
            if($this->method === 'delete'){
                $temp[] = 'delete';
            }else if($this->method === 'post'){
                $temp[] = 'add';
            }else if($this->method === 'put'){
                $temp[] = 'update';
            }
        }
        $this->requestInfo = [
            'requestArray'  => $temp ,
            'path'   => implode('/', $temp) ,
            'param'  => $param
        ]; 
        $this->_route();
    }

    public function _response($code , $ouput){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
        header("Access-Control-Allow-Headers: Authorization");
        $http = [
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Time-out',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Large',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Time-out',
            505 => 'HTTP Version Not Supported',
        ];
        http_response_code($code);
        $ouput['message'] = $http[$code];
        echo json_encode($ouput);
        exit();
    }

    public function getParam(){
        switch ($this->method) {
            case 'get':
                return $this->router->request_get();
                break;
            case 'post':
                return  encode_html_form($this->input);
                break;
            case 'put':
                return encode_html_form($this->input);
                break;
        }
    }

    public function checkToken(){ 
        $jwt    = new JwtMiddleWare();
        $isAuth = $jwt->getAndDecodeToken();
        if (!$isAuth || $isAuth == 'expired') {
            $this->session->log_out(); 
            $this->_response(401, [
                'status' => 'Unauthorized' ,
                'msg'    => 'شما اجاز دسترسی به درخواست مورد نظر را ندارید.ابتدا وارد حساب کاربری خود شوید.' ,
                'error'  => 3//'token expired'
            ]);
        }
    }
    
}




