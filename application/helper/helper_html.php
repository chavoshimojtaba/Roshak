<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function createTicketRows($data)
{
    
    if ($data['user_type'] == 'user') {
        return '<div class="d-flex flex-row-reverse comment-item mt-4">
                <div class="comment-avatar">
                    <img width="50px" height="50px" class="rounded-pill" src="' . HOST . '' . $data['img'] . '" alt="">
                </div>

                <div class="comment-user ps-2 text-start">
                    <span class="fs-6 fw-bold pe-2">' . $data['full_name'] . '</span>
                    <span class="date fs-7 text-secondary pe-2 me-2 border-end">
                        ' . $data['createAt'] . '
                    </span>
                    <div class="comment-text rounded-end rounded-bottom px-4 py-3 border text-secondary">
                        ' . $data['comment'] . '
                        <div class="d-flex align-items-center text-white">
                            ' . $data['files'] . '
                        </div>
                    </div>
                </div>
            </div>';
    } else {
        return '<div class="d-flex comment-item mt-4">
            <div class="comment-avatar">
                <img width="50px" height="50px" class="rounded-pill" src="' . HOST . '' . $data['img'] . '" alt="">
            </div>
            <div class="comment-user pe-2">
            <span class="fs-6 fw-bold pe-2">' . $data['full_name'] . '</span>
                <span class="date pe-2 me-2 border-end fs-7 text-secondary">
                    ' . $data['createAt'] . '
                </span>
                <div class="comment-text rounded-start rounded-bottom px-4 py-3 bg-light text-secondary bg-opacity-25">
                    ' . $data['comment'] . '
                    <div class="d-flex align-items-center text-white">
                        ' . $data['files'] . '
                    </div>
                </div>
            </div>
        </div> ';
    }
}
function getPriority($priority)
{
    $priorityList = [
        'low' => '<span class="badge bg-secondary status-badge">' . LANG_LOW . '</span>',
        'normal' => '<span class="badge bg-primary status-badge">' . LANG_NORMAL . '</span>',
        'high' => '<span class="badge bg-warning status-badge">' . LANG_HIGH . '</span>',
        'critical' => '<span class="badge bg-danger status-badge">' . LANG_CRITICAL . '</span>'
    ];
    return $priorityList[$priority];
}
function getTicketStatus($status)
{
    $statusList = [
        'open' => '<span class="badge bg-success status-badge">' . LANG_OPENED . '</span>',
        'close' => '<span class="badge bg-danger status-badge">' . LANG_CLOSED . '</span>',
    ];
    return $statusList[$status];
}
function dashboard_sidebar($class = '', $whitout_ul = false,$is_sidebar=false)
{
    $C               = &get_instance();

    $C->load->model('model_notifications');
    $cnt = $C->model_notifications->notify_count($_SESSION['mid']);
    $badge = ($cnt>0)?'<span class="fs-6 p-0 d-flex align-items-center pt-1 justify-content-center rounded-circle bg-danger badge">'.$cnt.'</span>':'';
    if (isset($_SESSION['type']) && $_SESSION['type'] == 'designer') {
        $items_common = [
            'home'         => ['title' => 'داشبورد', 'url' => HOST . 'dashboard/', 'icon' => 'menu','dropdown'=>false],
            'notification' => ['title' => 'اعلانات', 'url' => HOST . 'dashboard/notification', 'icon' => 'notification','badge'=>$badge ,'dropdown'=>false],
            ['title' => 'گزینه های کاربر', 'url' => HOST, 'icon' => '','dropdown'=>true],
            'ticket'       => ['title' => 'تیکت به پشتیبانی', 'url' => HOST . 'dashboard/ticket', 'icon' => 'support'],
            'plan'         => ['title' => 'مدیریت اشتراک', 'url' => HOST . 'dashboard/plan', 'icon' => 'plan'],
            'comment'      => ['title' => 'نظرات', 'url' => HOST . 'dashboard/comment', 'icon' => 'comment' ,'dropdown'=>false],
            'download'     => ['title' => 'دانلود ها', 'url' => HOST . 'dashboard/download', 'icon' => 'download'],
            ['title' => 'گزینه های طراح', 'url' => HOST, 'icon' => '','dropdown'=>true],
            'product'      => ['title' => 'مدیریت محصولات', 'url' => HOST . 'dashboard/product', 'icon' => 'product'],
            'settlement_requests'      => ['title' => 'تسویه و درآمد ها', 'url' => HOST . 'dashboard/settlement_requests', 'icon' => 'wallet' ,'dropdown'=>false],
            ['title' => 'حساب کاربری', 'url' => HOST, 'icon' => '','dropdown'=>true],
            'favorite'     => ['title' => 'علاقه مندی ها', 'url' => HOST . 'dashboard/favorite', 'icon' => 'favorite' ,'dropdown'=>false],
            'profile'      => ['title' => 'پروفایل کاربری', 'url' => HOST . 'dashboard/profile', 'icon' => 'profile','dropdown'=>false],
        ];
    } else { 
        $items_common = [
            'home'         => ['title' => 'داشبورد', 'url' => HOST . 'dashboard/', 'icon' => 'menu'],
            // 'notification' => ['title' => 'اعلانات', 'url' => HOST . 'dashboard/notification', 'icon' => 'notification','dropdown'=>false],
            ['title' => 'گزینه های کاربر', 'url' => HOST, 'icon' => ''],
            'ticket'       => ['title' => 'تیکت به پشتیبانی', 'url' => HOST . 'dashboard/ticket', 'icon' => 'support'],
            'plan'         => ['title' => 'مدیریت اشتراک', 'url' => HOST . 'dashboard/plan', 'icon' => 'plan'],
            // 'comment'      => ['title' => 'نظرات', 'url' => HOST . 'dashboard/comment', 'icon' => 'comment','dropdown'=>false],
            'download'     => ['title' => 'دانلود ها', 'url' => HOST . 'dashboard/download', 'icon' => 'download'],
            ['title' => 'حساب کاربری', 'url' => HOST, 'icon' => ''],
            // 'favorite'     => ['title' => 'علاقه مندی ها', 'url' => HOST . 'dashboard/favorite', 'icon' => 'favorite','dropdown'=>false],
            'profile'      => ['title' => 'پروفایل کاربری', 'url' => HOST . 'dashboard/profile', 'icon' => 'profile'],
        ];
    }
    $designer_menu_items = '';
    foreach ($items_common as $key => $value) {
        if (is_int($key) ) {
            if($value['dropdown']){
                $designer_menu_items .= '<li class="nav-item disabled"><span class="nav-link disabled text-muted">' . $value['title'] . ' </span></li>';
            }
        } else {
            if(!$is_sidebar){
                if(isset($value['dropdown']) &&  !$value['dropdown']){
                    continue;
                }
            }
            // pr($value,true);
            $badge = ($key == 'notification') ? $value['badge'] : '';
            $active = ($key == $class) ? 'active' : '';
            $designer_menu_items .= '<li class="nav-item  ' . $active . '"><a class="nav-link " aria-current="page" href="' . $value['url'] . '"><i class="icon-svg float-end mt-0 ms-2 ' . $value['icon'] . ' ' . $active . '"></i>' . $value['title'] . ' '.$badge.'</a></li>';
        }
    }
    $new_product = '';
    if (isset($_SESSION['type']) && $_SESSION['type'] == 'designer' && $_SESSION['designer_show_status'] === 'yes') { 
        $new_product = '
            <div class="px-4"><a class="btn w-100 btn-primary rounded-2 mb-2 py-2 px-4" href="' . HOST . 'dashboard/product/add">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.833333 10C0.833333 12.0397 0.943714 13.6219 1.21794 14.8559C1.49006 16.0803 1.91232 16.9101 2.50113 17.4989C3.08995 18.0877 3.91971 18.5099 5.14415 18.7821C6.37805 19.0563 7.96027 19.1667 10 19.1667C12.0397 19.1667 13.6219 19.0563 14.8558 18.7821C16.0803 18.5099 16.9101 18.0877 17.4989 17.4989C18.0877 16.9101 18.5099 16.0803 18.7821 14.8558C19.0563 13.6219 19.1667 12.0397 19.1667 10C19.1667 7.96027 19.0563 6.37805 18.7821 5.14415C18.5099 3.91971 18.0877 3.08995 17.4989 2.50113C16.9101 1.91232 16.0803 1.49006 14.8558 1.21794C13.6219 0.943714 12.0397 0.833334 10 0.833334C7.96027 0.833334 6.37805 0.943714 5.14415 1.21794C3.91971 1.49006 3.08995 1.91232 2.50113 2.50113C1.91232 3.08995 1.49006 3.91971 1.21794 5.14415C0.943714 6.37805 0.833333 7.96027 0.833333 10Z" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.0013 6.66602V9.99935M10.0013 13.3327V9.99935M10.0013 9.99935H13.3346H6.66797" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>&nbsp;
                افزودن طرح جدید
            </a></div>';
    }
    
    return $whitout_ul ? $designer_menu_items : '
        ' . $new_product . ' 
        <ul class="nav flex-column px-0">
            ' . $designer_menu_items . '
            <li class="nav-item mb-2"><a  class="nav-link  logout-btn"  ><i class="icon-svg float-end mt-0 ms-2 logout"></i>خروج </a></li>
        </ul>
    ';
}
function generateCategoryTemplatesCache()
{
    $C               = &get_instance();
    $C->load->model('model_category');
    $menu_mobile            = '';
    $menu            = '';
    $res_categories  = $C->model_category->get_list('product', true);
    // //pr($menu,true);
    if ($res_categories->count > 0) {
        $menu = buildProductMenuFromTree($res_categories->result);
        $menu_mobile = buildProductMenuMobileFromTree($res_categories->result);
    }

    if (!file_put_contents(DIR_CACHE . 'menu.php', $menu)) {
        //pr(DIR_CACHE,true);
        die("Error: failed to write data to : menu.php");
    }
    if (!file_put_contents(DIR_CACHE . 'menu_mobile.php', $menu_mobile)) {
        die("Error: failed to write data to : menu_mobile.php");
    }

    /*  if (!file_put_contents(DIR_CACHE . 'categoryOptions.php', $categoryOptions)) {
        die("Error: failed to write data to : categoryOptions.php");
    } */
}
function header_links_html()
{
    $C       = &get_instance();
    $C->load->model('model_settings');
    $res = $C->model_settings->get_public_link_detail(0, 'header');
    if ($links->count > 0) {
        foreach ($links->result as $key => $value) {
            if ($value['type'] == 'back_link') {
                $value['title'] = decode_html_tag($value['title'], true);
                $this->template->assign($value);
                $this->template->parse('index.public_links.public_links_item');
                if ((($key + 1) % 6) == 0) {
                    $this->template->parse('index.public_links');
                }
            }
        }
        $this->template->parse('index.public_links');

        foreach ($links->result as $key => &$value) {
            if ($value['type'] == 'tag') {
                $value['title'] = decode_html_tag($value['title'], true);
                $this->template->assign($value);
                $this->template->parse('index.tags');
            }
        }
    }
    $_columnsHtml = '';
    foreach ($res->result as  $vv) {
        $vv = decode_html_tag($vv, true);
        $_columnsHtml .= '<li class="nav-item py-2 px-2 rounded-3"><a href="' . $vv['url'] . '" class="nav-link text-dark">' . $vv['title'] . ' </a></li>  ';
    }
    return $_columnsHtml;
}
function generateFooterLinksCache()
{
    $C       = &get_instance();
    $C->load->model('model_settings');
    $res = $C->model_settings->footer_link_list([]);
    $columns_res = $C->model_settings->footer_link_columns();
    $all_columns = [];
    foreach ($columns_res->result as $key => $value) {
        $all_columns[$value['id']]['title'] = $value['title'];
    }
    foreach ($res->result as $key => $value) {
        $value['title'] = decode_html_tag($value['title'], true);
        $value['url'] = decode_html_tag($value['url'], true);
        $all_columns[$value['pid']]['childs'][$value['id']] = $value;
    }
    $_columnsHtml = '';
    foreach ($all_columns as  $vv) {
        if (count($vv) >= 2) {
            $_columnRowsHtml = '';
            foreach ($vv['childs'] as  $v) {
                $_columnRowsHtml .= ' <li class="mb-3 list-group-item"> <a href=" ' . $v['url'] . '" class="text-decoration-none text-secondary"> ' . $v['title'] . ' </a> </li> ';
            }
            $_columnsHtml .= '<div class="col-6 col-md-3"><span class="f-title text-dark pb-3 bold fs-6  d-block"> ' . $vv['title'] . ' </span><ul class="text-right p-0"> ' . $_columnRowsHtml . ' </ul> </div>';
        }
    }
    $wrapperDiv = ' <div class="row"> ' . $_columnsHtml . ' </div> ';
    // //pr(DIR_CACHE,true);
    if (!file_put_contents(DIR_CACHE . 'footer_links.php', $wrapperDiv)) {
        die("Error: failed to write data to : footer_links.php");
    }
    if (!file_put_contents(DIR_CACHE . 'footer_links_mobile.php', $wrapperDiv)) {
        die("Error: failed to write data to : footer_links_mobile.php");
    }
}
function buildProductMenuFromTree($menuArray, $hasChild = 0)
{
    $res = [
        'image' => '',
        'video' => '',
        'font' => '',
        'vector' => '',
        'stock' => '',
        'mockup' => '',
    ];
    foreach ($menuArray as $node) {
        $res[$node['filetype']] .= ' <li class="mx-0"><a href="' . HOST . 'search/' . $node['slug'] . '"  class="menu-item text-decoration-none"> ' . $node['title'] . '<span class="fs-6 float-start"> <i class="icon icon-arrow-left-24 mt-1 float-start"></i> </span> </a> </li>
        ';
    }
    return '
    <ul class="navbar-nav p-0">
        <li class="nav-item  rounded-3 flex-fill">
            <a href="'.HOST.'search/image-t" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="nav-link menu-nav rounded-3 py-3 px-3">
                <i class="icon d-none d-xl-block icon-color-swatch fs-2 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    فایل های لایه باز
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <ul class="inline-menu dropdown-menu position-absolute p-2 text-end" data-popper-placement="bottom-end">
                <div class="d-flex flex-row">
                    <ul class="d-flex flex-column flex-fill">
                    ' . $res['image'] . '
                    </ul>
                </div>
            </ul>
        </li>
        <li class="nav-item  rounded-3 flex-fill">
            <a href="'.HOST.'search/video-t" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="nav-link menu-nav rounded-3 py-3 px-3">
                <i class="icon icon-video-play d-none d-xl-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    ویدیو افترافکت
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <ul class="inline-menu dropdown-menu position-absolute p-2 text-end" data-popper-placement="bottom-end">
                <div class="d-flex flex-row">
                    <ul class="d-flex flex-column flex-fill">
                        ' . $res['video'] . '
                    </ul>
                </div>
            </ul>
        </li>
        <li class="nav-item  rounded-3 flex-fill">
            <a href="'.HOST.'search/stock-t" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="nav-link menu-nav rounded-3 py-3 px-3">
                <i class="icon d-none d-xl-block icon-gallery fs-2 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    تصاویر استوک
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <ul class="inline-menu dropdown-menu position-absolute p-2 text-end" data-popper-placement="bottom-end">
                <div class="d-flex flex-row">
                    <ul class="d-flex flex-column flex-fill">
                        ' . $res['stock'] . '
                    </ul>
                </div>
            </ul>
        </li>
        <li class="nav-item  rounded-3 flex-fill">
            <a href="'.HOST.'search/vector-t" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="nav-link menu-nav rounded-3 py-3 px-3">
                <i class="icon d-none d-xl-block icon-vector fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    وکتور
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <ul class="inline-menu dropdown-menu position-absolute p-2 text-end" data-popper-placement="bottom-end">
                <div class="d-flex flex-row">
                    <ul class="d-flex flex-column flex-fill">
                        ' . $res['vector'] . '
                    </ul>
                </div>
            </ul>
        </li>
        <li class="nav-item  rounded-3 flex-fill">
            <a href="'.HOST.'search/font-t" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="nav-link menu-nav rounded-3 py-3 px-3">
                <i class="icon d-none d-xl-block icon-text fs-2 float-end ps-2"></i>
                <span class="text-nowrap pe-0"> فونت </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <ul class="inline-menu dropdown-menu position-absolute p-2 text-end" data-popper-placement="bottom-end">
                <div class="d-flex flex-row">
                    <ul class="d-flex flex-column flex-fill">
                        ' . $res['font'] . '
                    </ul>
                </div>
            </ul>
        </li>
        <li class="nav-item  rounded-3 flex-fill">
            <a href="'.HOST.'search/mockup-t" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="nav-link menu-nav rounded-3 py-3 px-3">
                <i class="icon d-none d-xl-block icon-additem fs-2 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    موکاپ
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <ul class="inline-menu dropdown-menu position-absolute p-2 text-end" data-popper-placement="bottom-end">
                <div class="d-flex flex-row">
                    <ul class="d-flex flex-column flex-fill">
                        ' . $res['mockup'] . '
                    </ul>
                </div>
            </ul>
        </li>
    </ul>
    ';
    return $res;
}
function buildProductMenuMobileFromTree($menuArray, $hasChild = 0)
{
    $res = [
        'image' => '',
        'video' => '',
        'font' => '',
        'vector' => '',
        'stock' => '',
        'mockup' => '',
    ];

    foreach ($menuArray as $node) {
        $res[$node['filetype']] .= '
            <li class="px-0 mx-0">
                <a  href="' . HOST . 'search/' . $node['slug'] . '"  class="text-decoration-none">
                ' . $node['title'] . '
                    <span class="fs-6 float-start"> 
                        <i class="icon icon-arrow-left-24 mt-0 fs-5 float-start"></i>
                    </span>
                </a>
            </li>';
    }
    return '
        <li class="nav-item active py-0 px-0 overflow-hidden rounded-3 flex-fill">
            <a role="button" data-bs-toggle="collapse" href="#imageCollapse" class="nav-link fromRight py-3 px-3 collapsed">
                <i class="icon icon-color-swatch d-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    فایل های لایه باز
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <div id="imageCollapse" class="collapse py-0 px-0" data-popper-placement="bottom-end">
                <div class="px-3">
                    <div class="d-flex bg-white border align-items-stretch rounded-3 mt-2" >
                        <button type="submit" class="btn d-flex align-items-center bg-white rounded-3">
                            <i class="icon text-dark fs-5 icon-search-normal-14"></i>
                        </button>
                        <div class="flex-fill">
                            <div class="d-flex position-relative">
                                <input id="menu-mobile-image" type="text" name="search"
                                class="form-control border-0 bg-white fs-6 ps-0 rounded-3 text-muted py-2" placeholder="جستجو در دسته بندی ها..." />
                            </div>
                        </div>
                    </div>
                    <ul class="py-2 px-0 text-end" data-popper-placement="bottom-end">
                        ' . $res['image'] . '
                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item active py-0 px-0 overflow-hidden rounded-3 flex-fill">
            <a role="button" data-bs-toggle="collapse" href="#videoCollapse"  class="nav-link fromRight py-3 px-3 collapsed">
                <i class="icon icon-color-swatch d-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                    ویدیو افترافکت
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <div id="videoCollapse" class="collapse py-0 px-0" data-popper-placement="bottom-end">
                <div class="px-3">
                    <div class="d-flex bg-white border align-items-stretch rounded-3 mt-2" >
                        <button type="submit" class="btn d-flex align-items-center bg-white rounded-3">
                            <i class="icon text-dark fs-5 icon-search-normal-14"></i>
                        </button>
                        <div class="flex-fill">
                            <div class="d-flex position-relative">
                                <input id="menu-mobile-video" type="text" name="search"
                                class="form-control border-0 bg-white fs-6 ps-0 rounded-3 text-muted py-2" placeholder="جستجو در دسته بندی ها..." />
                            </div>
                        </div>
                    </div>
                    <ul class="py-2 px-0 text-end" data-popper-placement="bottom-end">
                        ' . $res['video'] . '

                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item active py-0 px-0 overflow-hidden rounded-3 flex-fill">
            <a role="button" data-bs-toggle="collapse" href="#stockCollapse"  class="nav-link fromRight py-3 px-3 collapsed">
                <i class="icon icon-color-swatch d-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                تصاویر استوک
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <div id="stockCollapse" class="collapse py-0 px-0" data-popper-placement="bottom-end">
                <div class="px-3">
                    <div class="d-flex bg-white border align-items-stretch rounded-3 mt-2" >
                        <button type="submit" class="btn d-flex align-items-center bg-white rounded-3">
                            <i class="icon text-dark fs-5 icon-search-normal-14"></i>
                        </button>
                        <div class="flex-fill">
                            <div class="d-flex position-relative">
                                <input id="menu-mobile-stock" type="text" name="search"
                                class="form-control border-0 bg-white fs-6 ps-0 rounded-3 text-muted py-2" placeholder="جستجو در دسته بندی ها..." />
                            </div>
                        </div>
                    </div>
                    <ul class="py-2 px-0 text-end" data-popper-placement="bottom-end">
                        ' . $res['stock'] . '

                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item active py-0 px-0 overflow-hidden rounded-3 flex-fill">
            <a role="button" data-bs-toggle="collapse" href="#vectorCollapse"   class="nav-link fromRight py-3 px-3 collapsed">
                <i class="icon icon-color-swatch d-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                وکتور
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <div id="vectorCollapse" class="collapse py-0 px-0" data-popper-placement="bottom-end">
                <div class="px-3">
                    <div class="d-flex bg-white border align-items-stretch rounded-3 mt-2" >
                        <button type="submit" class="btn d-flex align-items-center bg-white rounded-3">
                            <i class="icon text-dark fs-5 icon-search-normal-14"></i>
                        </button>
                        <div class="flex-fill">
                            <div class="d-flex position-relative">
                                <input id="menu-mobile-vector" type="text" name="search"
                                class="form-control border-0 bg-white fs-6 ps-0 rounded-3 text-muted py-2" placeholder="جستجو در دسته بندی ها..." />
                            </div>
                        </div>
                    </div>
                    <ul class="py-2 px-0 text-end" data-popper-placement="bottom-end">
                        ' . $res['vector'] . '

                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item active py-0 px-0 overflow-hidden rounded-3 flex-fill">
            <a role="button" data-bs-toggle="collapse" href="#fontCollapse" class="nav-link fromRight py-3 px-3 collapsed">
                <i class="icon icon-color-swatch d-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                فونت
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <div id="fontCollapse" class="collapse py-0 px-0" data-popper-placement="bottom-end">
                <div class="px-3">
                    <div class="d-flex bg-white border align-items-stretch rounded-3 mt-2" >
                        <button type="submit" class="btn d-flex align-items-center bg-white rounded-3">
                            <i class="icon text-dark fs-5 icon-search-normal-14"></i>
                        </button>
                        <div class="flex-fill">
                            <div class="d-flex position-relative">
                                <input id="menu-mobile-font" type="text" name="search"
                                class="form-control border-0 bg-white fs-6 ps-0 rounded-3 text-muted py-2" placeholder="جستجو در دسته بندی ها..." />
                            </div>
                        </div>
                    </div>
                    <ul class="py-2 px-0 text-end" data-popper-placement="bottom-end">
                        ' . $res['font'] . '

                    </ul>
                </div>
            </div>
        </li>
        <li class="nav-item active py-0 px-0 overflow-hidden rounded-3 flex-fill">
            <a role="button" data-bs-toggle="collapse" href="#mockupCollapse" class="nav-link fromRight py-3 px-3 collapsed">
                <i class="icon icon-color-swatch d-block fs-3 float-end ps-2"></i>
                <span class="text-nowrap pe-0">
                موکاپ
                </span>
                <i class="icon icon-arrow-down-14 px-0 me-2 mt-0 float-start fs-3"></i>
            </a>
            <div id="mockupCollapse" class="collapse py-0 px-0" data-popper-placement="bottom-end">
                <div class="px-3">
                    <div class="d-flex bg-white border align-items-stretch rounded-3 mt-2" >
                        <button type="submit" class="btn d-flex align-items-center bg-white rounded-3">
                            <i class="icon text-dark fs-5 icon-search-normal-14"></i>
                        </button>
                        <div class="flex-fill">
                            <div class="d-flex position-relative">
                                <input id="menu-mobile-mockup" type="text" name="search"
                                class="form-control border-0 bg-white fs-6 ps-0 rounded-3 text-muted py-2" placeholder="جستجو در دسته بندی ها..." />
                            </div>
                        </div>
                    </div>
                    <ul class="py-2 px-0 text-end" data-popper-placement="bottom-end">
                        ' . $res['mockup'] . '
                    </ul>
                </div>
            </div>
        </li>
    ';
}

function no_result($q = '')
{
    return '<div class="no_result d-flex align-items-center justify-content-start"><h4 class="m-0">' . LANG_NO_RESULT . '</h4>' . (($q != '') ? "&nbsp;:&nbsp;" . $q : '') . "</div>";
}

function buildMenuFromTree($menuArray, $subclass = "dorpdown-list_sub")
{
    $res = '';
    foreach ($menuArray as $node) {
        $title = $node['title_' . $_COOKIE['def_lang']];
        if (!empty($node['children'])) {
            $res .= '<li class="' . $subclass . '"><a href="#cat_' . $title . '"/>' . $title . '</a>';
        } else {
            $res .= '<li><a href="{HOST}blog/index/' . $node['id'] . '?cat=' . $title . '"/>' . $title . '</a>';
        }

        if (!empty($node['children'])) {
            $res .= '<ul class="dorpdown-list_categories">';
            $res .= buildMenuFromTree($node['children']);
            $res .= '</ul>';
        }

        $res .= '</li>';
    }
    return $res;
}

function buildTree(array $elements, $options = [
    'pid' => 'pid',
    'children' => 'children',
    'id' => 'id'
], $parentId = 0, $path = '')
{

    $branch = array();
    foreach ($elements as $element) {
        $element['path'] = $path;
        if ($element[$options['pid']] == $parentId) {
            $p = '_' . $element[$options['id']] . '_';
            $children = buildTree($elements, $options, $element[$options['id']], $path . '-' . $p);
            if ($children) {
                $element[$options['children']] = $children;
            } else {
                $element[$options['children']] = [];
            }
            $branch[] = $element;
        }
    }
    return $branch;
}

function buildMenuTree(array $elements, $options = [
    'pid' => 'pid',
    'subItems' => 'subItems',
    'hasSub' => false,
    'id' => 'id'
], $parentId = 0)
{
    $branch = array();
    foreach ($elements as $element) {
        if ($element[$options['pid']] == $parentId) {
            // //pr($elements,true);
            $subItems = buildMenuTree($elements, $options, $element[$options['id']]);
            if ($subItems) {
                $element['hasSub'] = true;
                $element[$options['subItems']] = $subItems;
            } else {
                $element['hasSub'] = false;
            }
            $branch[$element[$options['id']]] = $element;
        }
    }
    if ($parentId === 0) {
        return [
            0 => [
                'hasSub' => 1,
                'id' => 0,
                'subItems' => $branch
            ]
        ];
    } else {
        return $branch;
    }
}

function categoryTree($output = 'html')
{
    $C             = &get_instance();
    $C->load->model('model_category');
    $res_categories = $C->model_category->get_list('product');
    if ($res_categories->count > 0) {
        $categories = buildTree($res_categories->result);
        foreach ($categories as &$category) {
            $category['title'] = decode_html_tag($category['title_' . $_COOKIE['def_lang']], true);
        }
        if ($output == 'html') {
            return buildMenuFromTree($categories);
        } else {
            return $categories;
        }
    }
    return [];
}

function buildCommentListFromTree($comments, $subclass = "")
{
    $res = '';
    foreach ($comments as $node) {
        $title = decode_html_tag($node['text'], true);
        $res .= '
            <div class="comment_list ' . $subclass . '">
                <div class="comment_thumb">
                    <img src="' . HOST . $node['img'] . '" alt="' . $title . '">
                </div>
                <div class="comment_content">
                    <div class="comment_meta">
                        <h5><a href="#">' . $node['full_name'] . '</a></h5>
                        <span>' . $node['createAt'] . '</span>
                    </div>
                    <p>' . $title . '</p>
                    <div class="comment_reply">
                        <a href="#">پاسخ</a>
                    </div>
                </div>
            </div>
        ';
        if (!empty($node['children'])) {
            $res .= buildCommentListFromTree($node['children'], 'list_two');
        }
    }
    return $res;
}
function buildCommentListFromTreeFront($comments, $subclass = "1")
{
    $res = '';
    foreach ($comments as $node) {
        $title = decode_html_tag($node['text'], true);
        $like_class = '';
        $like_class = 'like-comment';
        $res .= '
            <div class="d-flex comment-item mt-4  ">
                <div class="comment-avatar">
                    <img width="50px" height="50px" class="rounded-pill" src="' . HOST . $node['img'] . '" alt="" />
                </div>
                <div class="comment-user  w-100 pe-2">
                    <div class="d-flex">
                        <div class="user-info flex-fill">
                            <span class="fs-5 ps-2">' . $node['full_name'] . '</span>
                            <span class="d-block date fs-7 text-secondary">
                            ' . g2pt($node['createAt']) . '
                            </span>
                        </div>
                        <button class="btn btn-default ' . $like_class . ' btn-custom text-secondary fs-5"  data-id="' . $node['id'] . '">
                            <span class="likes-' . $node['id'] . '">
                                ' . $node['statistic_like'] . '
                            </span>
                            <i class="icon icon-heart-bold float-start text-danger me-2"></i>
                        </button>
                    </div>
                    <div class="comment-text rounded-start rounded-bottom px-0 py-3 text-secondary">
                        ' . $title . '
                    </div>
        ';
        if (isset($_SESSION['mid'])) {
            $res .= '<div class="actions-container" data-id="' . $node['id'] . '">
            <button class="btn btn-link btn-comment-reply text-decoration-none px-0">
                <i class="icon-svg comment active float-end ms-1 fs-5"></i>
                ارسال پاسخ
            </button>
        </div>';
        }
        if (!empty($node['children'])) {
            $res .= buildCommentListFromTreeFront($node['children'], '0');
        }
        $res .= '</div>
        </div>';
        if ($subclass) {
            $res .= '<hr class="border-secondary" />';
        }
    }
    return $res;
}
function changeMemberTypeActions($reject = false)
{
    if ($reject) {

        return '
            <div class="col-12 pt-2 d-flex change_type_request">
                <button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="confirm_change_type">
                    <i class="ti-check"></i>&nbsp;
                    تغییر نوع کاربری به فروشنده
                </button>
            </div>
        ';
    }
    return '
        <div class="col-12 pt-2 d-flex change_type_request">
            <button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="confirm_change_type">
                <i class="ti-check"></i>&nbsp;
                تغییر نوع کاربری به فروشنده
            </button>
            <button type="button" class="  btn btn-danger d-flex align-items-center justify-content-center" id="reject_change_type">
                <i class="ti-close"></i>&nbsp;
                رد درخواست
            </button>
        </div>
    ';
}
/*
function contentComments($id, $type, $output = 'html')
{
    $C             = &get_instance();
    $C->load->model('model_comment');
    $res = $C->model_comment->get_content_comments($id, $type);
    if ($res->count > 0) {
        $comments = buildTree($res->result);
        if ($output == 'html') {
            return [
                'res' => buildCommentListFromTree($comments),
                'count' => $res->count
            ];
        } else {
            return [
                'res' => $comments,
                'count' => $res->count
            ];
        }
    }
    return false;
}

function recentComments($type)
{
    $C             = &get_instance();
    $C->load->model('model_comment');
    $recent_comments = $C->model_comment->recent_comments($type);
    if ($recent_comments->count > 0) {
        foreach ($recent_comments->result as &$comment) {
            $comment['text'] = decode_html_tag($comment['text'], true);
            $C->template->assign($comment);
            $C->template->parse($C->router->method . '.recent_comments');
        }
    }
}
function getPriority($priority)
{
    $priorityList = [
        1 => '<span class="badge bg-secondary status-badge">' . LANG_LOW . '</span>',
        2 => '<span class="badge bg-primary status-badge">' . LANG_NORMAL . '</span>',
        3 => '<span class="badge bg-warning status-badge">' . LANG_HIGH . '</span>',
        4 => '<span class="badge bg-danger status-badge">' . LANG_CRITICAL . '</span>'
    ];
    return $priorityList[$priority];
}
function productStatus($status)
{
    $statusList = [
        'pend' => '<span class="badge bg-warning status-badge">' . LANG_PEND . '</span>',
        'accept' => '<span class="badge bg-primary status-badge">' . LANG_CONFIRMED . '</span>',
        'reject' => '<span class="badge bg-danger status-badge">' . LANG_REJECTED . '</span>'
    ];
    return $statusList[$status];
}

function getTicketStatus($status)
{
    $statusList = [
        1 => '<span class="badge bg-success status-badge">' . LANG_OPENED . '</span>',
        2 => '<span class="badge bg-danger status-badge">' . LANG_CLOSED . '</span>',
    ];
    return $statusList[$status];
}
*/


/*
   * Created     : Sat Aug 27 2022 10:33:37 AM
   * Author      : Chavoshi Mojtaba
   * Description : Description
   * return      : array
*/
/*


 */