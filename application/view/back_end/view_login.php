<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: main -->
<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="keywords" content="Tarhpich" />
    <meta name="description" content="Tarhpich admin panel" />
    <meta name="robots" content="noindex,nofollow" />
    <title>ورود به پنل مدیریت</title>
    <link rel="canonical" href="https://www.Tarhpich.com" />
    <link rel="canonical" href="https://www.wrappixel.com/templates/materialpro/" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{HOST}file/admin/images/favicon.png" />
    <!-- Custom CSS -->
    <link href="{HOST}file/admin/css/style.css" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js?hl=fa' async defer></script>
    <script type="text/javascript">
        var page = '{R}';
        var $_url = '{HOST}';
        var $_Curl = '{CURRENT_URL}';
        var $_Burl = '{BASE_URL}';
        // var langs = JSON.parse('{langs}');
    </script>
</head>

<body>

    <div class="main-wrapper">
        <div class="
          auth-wrapper
          d-flex
          no-block
          justify-content-center
          align-items-center
        " style="background: url('{HOST}file/admin/images/background/megamenubg.webp') no-repeat center center; background-size: cover; ">
            <div class=" auth-box p-4 bg-white rounded">
                <!-- BEGIN: login -->
                <div id="loginform">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="login-logo">
                            <img src="{HOST}file/global/image/logo.svg" alt="">
                        </div>
                    </div>
                    <div class="logo">
                        <h3 class="box-title mb-1 mt-3">ورود به پنل مدیریت</h3>
                    </div>
                    <!-- Form -->
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal mt-3 form-material" id="form-login" novalidate>
                                <div class="form-group mb-3">
                                    <div>
                                        <input class="form-control" type="text" required data-pristine-required-message="لطفا {LANG_USERNAME} را وارد کنید" name="username" id="username" placeholder="{LANG_USERNAME}" />
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <div>
                                        <input class="form-control" type="password" data-pristine-required-message="لطفا {LANG_PASSWORD} را وارد کنید" name="password" id="password" required placeholder="{LANG_PASSWORD}" />
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <div>
                                        <div class="g-recaptcha" data-sitekey="6LeYdG0pAAAAAHk8vHK7RoS5D0aP1HTfliMUtVMu"    ></div>

                                    </div>
                                </div>
                                <div class="form-group d-none">
                                    <div class="d-flex">
                                        <a href="javascript:void(0)" id="to-recover" class="link font-weight-medium"><i class="fa fa-lock me-1"></i> رمز عبور خود را فراموش کرده اید؟</a>
                                    </div>
                                </div>
                                <div class="form-group text-center mt-4 mb-3">
                                    <div class="col-xs-12">
                                        <button id="submit_login" type="button" class="btn btn-info d-block w-100 waves-effect waves-light">
                                            ورود
                                        </button>
                                    </div>
                                </div>
                                <div class="alert mt-2 alert-light-success text-success d-none" role="alert" id="alert-login-success">
                                    <h4 class="alert-heading">موفقیت آمیز</h4>
                                    <p class="text-start">رمز عبور با موفقیت بازیابی شد</p>
                                </div>
                                <div class="alert mt-2 alert-light-danger text-danger d-none" role="alert" id="alert-login-err">
                                    <h4 class="alert-heading">خطا</h4>
                                    <p class="text-start" id="alert-login-err-txt">
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="recoverform">
                    <div class="logo">
                        <h3 class="font-weight-medium mb-3">بازیابی رمز عبور</h3>
                        <span class="text-muted">ایمیل خود را وارد کنید.لینک بازیابی رمز عبور برای شما ارسال خواهد شد</span>
                    </div>
                    <div class="row">
                        <!-- Form -->
                        <form class="form-horizontal" id="form-forgot-pass" novalidate>
                            <div class="form-group mt-2 mb-4">
                                <div>
                                    <input class="form-control" type="text" required data-pristine-required-message="لطفا {LANG_USERNAME} را وارد کنید" name="username" id="inp_forgot_pass" placeholder="{LANG_USERNAME}" />
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="form-group text-center mt-4 mb-3">
                                    <div class="col-xs-12">
                                        <button id="submit_forgot_pass" type="button" class=" btn btn-info d-block w-100 waves-effect waves-light ">
                                            ارسال لینک بازیابی
                                        </button>
                                        <button id="back-login" type="button" class="
                                            btn btn-outline-secondary
                                            mt-2
                                            d-block
                                            w-100
                                            waves-effect waves-light
                                            ">
                                            ورود
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="alert alert-light-info text-info d-none" role="alert" id="alert-success">
                            <h4 class="alert-heading">لینک ارسال شد</h4>
                            <p class="text-start">لینک بازیابی رمز عبور برای شما ارسال شد. از لینک ارسال شده برای بازیابی رمز عبور خود استفاده نمایید.</p>
                            <hr>
                            <div class="mb-0">
                                اعتبار لینک بازیابی رمز عبور 30 دقیقه میباشد.
                                در صورتی که ایمیل برای شما ارسال نشده بود پوشه اسپم خود را بررسی کنید
                            </div>
                        </div>
                        <div class="alert alert-light-danger text-danger d-none" role="alert" id="alert-err">
                            <h4 class="alert-heading">{LANG_CONNECTION_ERROR}</h4>
                            <p class="text-start">
                                {LANG_INVALID_EMAIL_ADDRESS}
                            </p>
                        </div>
                    </div>
                </div>
                <div id="verifyform">
                    <div class="logo">
                        <h3 class="font-weight-medium mb-3">فعالسازی حساب</h3>
                        <span class="text-muted">کد ارسال شده به شماره همراه خود را وارد کنید</span>
                    </div>
                    <div class="row">
                        <!-- Form -->
                        <form class="form-horizontal" id="form-verify" novalidate>
                            <div class="form-group mt-2 mb-4">
                                <div>
                                    <input class="form-control" type="text" required data-pristine-required-message="لطفا {LANG_CODE} را وارد کنید" name="code" id="inp_forgot_pass" placeholder="{LANG_CODE}" />
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="form-group text-center mt-4 mb-3">
                                    <div class="col-xs-12">
                                        <button id="submit_code" type="button" class=" btn btn-info d-block w-100 waves-effect waves-light ">
                                            بررسی کد
                                        </button>
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
                <!-- END: login -->

                <!-- BEGIN: change_pass -->
                <div id="change_pass">
                    <div class="logo">
                        <h3 class="font-weight-medium mb-3">  رمز عبور جدید</h3>
                    </div>
                    <div class="row">
                        <!-- Form -->
                        <form class="form-horizontal" id="form-change-password" novalidate>
                            <div class="form-group mt-2 mb-4">
                                <div>
                                    <input class="form-control" type="text" required data-pristine-pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/" data-pristine-pattern-message="حداقل 2 کاراکتر،شامل حداقل یک حرف و یک عدد" data-pristine-required-message="رمز عبور الزامی میباشد" name="password" id="inp_password" placeholder="{LANG_PASSWORD}" />
                                    <input type="hidden" name="token" value="{token}" />
                                </div>
                            </div>
                            <div class="form-group mt-2 mb-4">
                                <div>
                                    <input data-pristine-required-message="تکرار رمز عبور الزامی میباشد" class="form-control" type="text" data-pristine-pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/" data-pristine-pattern-message="حداقل 2 کاراکتر،شامل حداقل یک حرف و یک عدد" name="re_password" id="inp_re_password" placeholder="تکرار رمز عبور" />
                                </div>
                            </div>
                            <div class="form-group mb-1">
                                <div class="form-group text-center mt-4 ">
                                    <div class="col-xs-12">
                                        <button id="submit_change_password" type="button" class=" btn btn-info d-block w-100  waves-effect waves-light ">
                                            ثبت رمز عبور جدید
                                        </button>
                                        <a href="{HOST}admin/login" type="button" class="
                                            btn btn-outline-secondary
                                            mt-2
                                            d-block
                                            w-100
                                            waves-effect waves-light
                                            ">
                                            ورود
                                        </a>
                                    </div>
                                </div>
                        </form>
                        <div class="alert mt-2 alert-light-success text-success d-none" role="alert" id="alert-success">
                            <h4 class="alert-heading">موفقیت آمیز</h4>
                            <p class="text-start">رمز عبور با موفقیت بازیابی شد</p>
                        </div>
                        <div class="alert mt-2 alert-light-danger text-danger d-none" role="alert" id="alert-err">
                            <h4 class="alert-heading">خطا</h4>
                            <p class="text-start" id="alert-err-txt">

                            </p>
                        </div>
                    </div>
                </div>
                <!-- END: change_pass -->

                <!-- BEGIN: invalid_token -->
                <div id="invalid_token">

                    <div class="alert m-0 alert-light-danger text-danger" role="alert" id="alert-success">
                        <h4 class="alert-heading d-flex flex-wrap">توکن نامعتبر : </h4>
                        <p class="text-start" style="word-break: break-all;">{token}</p>
                        <hr>
                        <div class="mb-0">
                            توکن مورد نظر معتبر نمیباشد.جهت رفع مشکل بوجود آمده با مدیریت تماس برقرار کنید
                        </div>
                    </div>
                    <a href="{HOST}admin/login" type="button" class="
                        btn btn-outline-info
                        mt-2
                        d-block
                        w-100
                        waves-effect waves-light
                        ">
                        ورود
                    </a>
                </div>
                <!-- END: change_pass -->
            </div>
        </div>
        <!-- -------------------------------------------------------------- -->
        <!-- Login box.scss -->
        <!-- -------------------------------------------------------------- -->
    </div>
    <!-- -------------------------------------------------------------- -->
    <!-- All Required js -->
    <!-- -------------------------------------------------------------- -->
    <script src="{HOST}file/admin/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{HOST}file/admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{HOST}file/admin/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="{HOST}file/admin/extra-libs/toastr/dist/build/toastr.min.js"></script>
    <script src="{HOST}file/global/js/pristine.js"></script>
    <script src="{HOST}file/global/js/util.js"></script>
    <script src="{HOST}file/admin/js/auth_app.js"></script>
    <!-- -------------------------------------------------------------- -->
    <!-- This page plugin js -->
    <!-- -------------------------------------------------------------- -->

    <script>

    </script>
</body>

</html>
<!-- END: main -->