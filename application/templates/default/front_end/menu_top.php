<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: menu_top -->
<header class="header-desktop" id="header">
    <div class="container-md">
        <div class="pt-3 pb-1 shadow-box">
            <div class="d-flex align-items-center">
                <div class="col-md-5 col-xl-4 order-3">
                    <div class="d-flex align-content-stretch d-flex flex-wrap justify-content-end">
                        <div class="header-bottom-btn position-relative">
                            <a class="align-self-stretch btn btn-default btn-basket text-primary py-1 rounded-3 px-3 ms-2" aria-label="سبد خرید" href="{HOST}bcart">
                                <i class="icon icon-cart-2 fs-4"></i>
                            </a>
                            <a class="btn btn-warning text-white py-2 rounded-3 px-xl-3 ms-2" href="{HOST}plan" aria-label="خرید اشتراک ویژه"  >
                                <i class="icon icon-crown fs-4 d-none d-xl-block float-end ms-2"></i>
                                خرید اشتراک
                            </a>
                            <!-- BEGIN: plan_btn -->
                            <!-- END: plan_btn -->


                            <!-- BEGIN: login_btn -->
                            <a class="btn btn-primary py-2 rounded-3 px-xl-4" href="{HOST}auth" aria-label="ورود | ثبت نام"  >
                                ورود به حساب
                            </a>
                            <!-- END: login_btn -->
                            <!-- BEGIN: dashboard_btn -->
                            <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true" class="btn btn-default btn-dropdown-login-user py-2 rounded-3 px-xl-2" href="/">
                                <i class="icon-svg profile active text-primary float-end mt-0 me-0 ms-2"></i>
                                <span class="username">
                                    {full_name}
                                </span>
                                <i class="icon icon-arrow-down-14 text-primary px-0 me-2 mt-0 float-start fs-4"></i>
                            </a>
                            <ul class="inline-menu dropdown-login-user position-absolute left-0 dropdown-menu-lg-end dropdown-menu py-1 text-end">
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
                                    <a class="nav-link logout-btn" data-bs-toggle="modal" data-bs-target="#modalexit">
                                        <i class="icon-svg float-end mt-0 ms-2 logout"></i>
                                        خروج
                                    </a>
                                </li>
                            </ul>
                            <div class="d-none menu-overlay"></div>
                            <!-- END: dashboard_btn -->
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-xl-2 order-1">
                    <div class="row">
                        <a href="{HOST}" class="text-end d-block" aria-label="طرح پیچ"  >
                            <img src="{HOST}file/client/images/logo.svg" alt="" class="img-fluid" height="35px" width="140px" />
                        </a>
                    </div>
                </div>
                <!-- BEGIN: menu -->
                <div class="col-md-5 col-xl-6  order-2">
                    <ul class="top-navbar mt-2 d-flex p-0 mb-0">
                        {header_links}
                    </ul>
                </div>
                <!-- END: menu -->
                <!-- BEGIN: search -->
                <div class="col-md-5 col-xl-6 order-2">
                    <div class="header-search-form   position-relative">

                        <div class=" header-search-form--inner  d-flex flex-column  rounded rounded-2 p-1">
                            <form class="d-flex align-items-stretch" action="{HOST}search">
                                <div class="flex-fill">
                                    <div class="d-flex position-relative main-q-container">
                                        <input id="main-q" type="text" name="q" class="me-0 form-control border-0 font-14  text-muted " placeholder="هر چه دل تنگت میخواهد سرچ کن  :)" />
                                    </div>
                                </div>
                                <button id="main-q-submit-btn" type="submit" class="btn d-flex align-items-center btn-primary rounded-3">
                                    <i class="icon text-white fs-5 icon-search-normal-14 ps-2"></i>
                                    جستجو
                                </button>
                            </form>
                            <div class="search-header-result  text-end" data-popper-placement="bottom-end">
                                <ul class="px-0 mb-0 d-flex flex-wrap  w-100" id="main-q_result"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END: search -->
            </div>
        </div>
    </div>
</header>
<div class="navbar-desktop d-none   pt-2 pb-0 mega-menu d-md-flex">
    <div class="container-md">
        <div class="row align-items-center">
            <div class="col-md-12 col-xl-12">
                <nav class="inline-menu">
                    {menu}
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- END: menu_top -->