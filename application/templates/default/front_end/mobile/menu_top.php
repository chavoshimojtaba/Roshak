<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: menu_top -->

<header class="header-mobile d-block" id="header">
    <div class="container-fluid border-bottom">
        <div class="pt-3 pb-3 shadow-box">
            <div class="d-flex align-items-center">
                <div class="flex-fill order-3">
                    <div class="d-flex align-content-stretch d-flex flex-wrap justify-content-end">
                        <div class="header-bottom-btn">
                            <!-- <a class="p-1 align-self-stretch btn btn-default btn-basket text-primary rounded-3 ms-2" aria-label="سبد خرید"  href="{HOST}bcart">
                                <i class="icon icon-cart-2 fs-5 "></i>
                            </a> -->
                            <a class="btn btn-warning p-0 d-flex align-items-center justify-content-center text-white rounded-3 ms-2" href="{HOST}plan" aria-label="خرید اشتراک ویژه"  >
                               <i class="icon icon-crown fs-4"></i>
                           </a>
                            <!-- BEGIN: plan_btn -->
                            <!-- END: plan_btn -->
                            <!-- BEGIN: login_btn -->
                            <a class="p-1 btn btn-primary rounded-3 align-items-center justify-content-center d-flex" aria-label="ورود | ثبت نام"  href="{HOST}auth">
                                <i class="icon icon-user fs-4 "></i>
                            </a>
                            <!-- END: login_btn -->
                            <!-- BEGIN: dashboard_btn -->
                            <span id="offCanvas"  class="hamburger btn btn-default btn-dropdown-login-user p-0 align-items-center justify-content-center d-flex rounded-3 px-xl-2" >
                                <i class="icon-svg profile active text-primary "></i>
                            </span>  
                            <!-- END: dashboard_btn -->

                            <!-- BEGIN: search -->
                            <div class="col-md-5 col-xl-6 d-none order-2">
                                <div class="header-search-form rounded rounded-2 p-1">
                                    <form class="d-flex align-items-stretch" action="{HOST}search">
                                        <div class="flex-fill">
                                            <div class="d-flex position-relative main-q-container-fluid">
                                                <input id="main-q" type="text" name="q" class="me-0 form-control border-0 font-14 rounded-3 text-muted py-2" placeholder="هر چه دل تنگت میخواهد سرچ کن  :)" />
                                                <div class="dropdown-menu search-header-result index-up-1 rounded-3 position-absolute mt-1 text-end" data-popper-placement="bottom-end">
                                                    <div class="scrollbar-inner">
                                                        <ul class="px-0 mb-0" id="main-q_result"></ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> 
                                        <button id="main-q-submit-btn" type="submit" class="btn d-flex align-items-center btn-primary rounded-3">
                                            <i class="icon text-white fs-5 icon-search-normal-14 ps-2"></i>
                                            جستجو
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <!-- END: search -->
                        </div>
                    </div>
                </div>
                <div class="order-1">
                    <div class="d-flex">
                        <button class="btn btn-default shadow-sm ms-2 rounded-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                            <i class="float-end mt-0 menu"></i>
                        </button>
                        <a href="{HOST}" class="text-end d-block" aria-label="طرح پیچ"  >
                            <img src="{HOST}file/client/images/logo.svg" alt="" class="img-fluid" width="130px" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-bottom navbar-mobile" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header position-relative">
            <img src="{HOST}file/client/images/logo-white.png" alt="" class="img-fluid" width="150px" />
            <div class="wrapper-close">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
        </div>
        <div class="offcanvas-body">
            <div>
                <ul class="navbar-nav p-0">
                    {menu}
                </ul>
                <hr class="border-secondary" />
                <ul class="top-navbar mt-2 p-0 mb-0">
                    {header_links}
                </ul>
            </div>
        </div>
    </div>
    <!-- Offcanvas Navigation Start -->
    <!-- BEGIN: dashboard_btn1 -->
    <div class="nav-offcanvas">
        <button type="button" class="close" id="offCanvasClose" aria-label="Close">
            <i class="ti-close"></i>
        </button>
        <div class="d-block nav-offcanvas-menu">
            <ul class="inline-menu d-block dropdown-login-user dropdown-menu-lg-end dropdown-menu   py-3 text-end">
                <li class="nav-item d-flex mb-2">
                    <div class="wrapper-profile">
                        <img width="65px" src="{HOST}{pic}" alt="اواتار" />
                        <div class="bg-under"></div>
                    </div>
                    <div class="mt-2 user-info">
                        <span class="d-flex fs-6 py-0 mt-2">
                            {full_name}
                        </span>
                        <a href="{HOST}dashboard" class="d-block py-1 rounded-5 text-decoration-none text-primary">
                            مشاهده پنل کاربری
                            <i class="icon icon-arrow-left4 float-start mt-0 me-2 fs-4"></i>
                        </a>
                    </div>
                </li>
                {menu_right}
                <li class="nav-item mb-2">
                    <a class="nav-link logout-btn">
                        <i class="icon-svg float-end mt-0 ms-2 logout"></i>
                        خروج
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="offcanvas-overlay"></div>
    <!-- END: dashboard_btn1 -->

    <!-- Offcanvas Navigation End -->

</header>

<!-- END: menu_top -->