<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: main -->
<!doctype html>
<html class="no-js" lang="fa" dir="{dir}">

<head>
    <base href="{HOST}" />
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="180x180" href="{CFILES}images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="{CFILES}images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="{CFILES}images/favicons/favicon-16x16.png" />
    <title>ورود | ثبت نام</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="طرح پیچ" />
    <meta itemprop="name" content="طرح پیچ" />
    <meta property="og:title" content="طرح پیچ" />
    <meta property="og:description" content="طرح پیچ" />
    <meta property="og:site_name" content="طرح پیچ" />
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="طرح پیچ" />
    <meta name="robots" content="follow, noindex, max-snippet:-1, max-video-preview:-1, max-image-preview:standard" />  
    <meta name="twitter:description" content="طرح پیچ" />
    <meta name="fontiran.com:license" content="C6FUV" />
    <meta name="theme-color" content="#4690cd" />
    <link rel="stylesheet" href="{HOST}file/global/style.css" />
    <link rel="stylesheet" href="{HOST}file/client/css/style.css" />
    <!-- <script src='https://www.google.com/recaptcha/api.js?hl=fa' async defer></script> -->
    <script type="text/javascript">    
        var page = '{R}'.toLowerCase();
        var HOST = '{HOST}';
        var CURL = '{PATH_URL}';
        var langs = JSON.parse('{langs}');
    </script>
    <style> 
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        } 
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="body-auth">
    <main class="text-center">
        
        <div class="d-inline-flex col-xl-4 wrapper-auth py-2 my-5 rounded rounded-5 justify-content-center">
            <section class="justify-content-center auth my-3 mx-4 py-4 px-5 bg-white rounded rounded-5 auth-page__box" id="box-login">
                <div class="text-center">
                    <div class="mx-auto mt-2 text-center mb-5">
                        <img class=" logo " src="{CFILES}images/logo.svg" alt="">
                    </div>
                    <h4 class="text-dark fw-bolder">
                        سلام رفیق!
                    </h4>
                    <p class="text-muted fs-6 mb-3 px-3 mx-3">
                    برای ورود و ثبت نام در طرح پیچ کافی است شماره موبایل خود را وارد کنید.                    </p>
                </div>
                <form novalidate id="form-login"  onSubmit="return false;">
                    <div class="my-3 form-group">
                        <div class="text-end">
                            <label for="phone" class="fs-5 text-muted">
                                شماره همراه
                            </label>
                            <input type="text" class="form-control p-3" name="mobile" data-pristine-pattern="/^([0-9]){11,11}$/" required data-pristine-required-message="{LANG_PLEASE_ENTER_YOUR_PHONE_NUMBER}" id="inp_mobile" placeholder="شماره موبایل خود را وارد کنید" data-pristine-pattern-message="شماره موبایل صحیح نمیباشد" />
                        </div>
                    </div>
                    <div class="mt-5 mb-3 d-flex flex-column">
                        <button id="submit_mobile" type="button" class="btn btn-lg btn-primary py-3">
                            ارسال کد
                        </button>
                    </div>
                </form>
            </section>
            <section class=" d-none  justify-content-center auth my-3 mx-4 py-4 px-5 bg-white rounded rounded-5 auth-page__box" id="box-verify">
                <div class="text-center">
                    <div class="mx-auto mt-2 text-center mb-5">
                        <img class=" logo " src="{CFILES}images/logo.svg" alt="">
                    </div>
                    <h4 class="text-dark fw-bolder">سلام رفیق!</h4>
                    <p class="text-muted fs-6 mb-3 px-3 mx-3">
                    اعتبار سنجی پیامکی
                    </p>
                </div>
                <form novalidate id="form-verify">
                    <div class="register-inputs d-none">
                        <div class="my-3" id="container_inp_name">
                            <div class="text-end form-group">
                                <label for="inp_name" class="fs-6 text-muted">
                                    نام
                                    (فارسی)
                                </label>
                                <input type="text" class="form-control p-3" required name="name" data-pristine-pattern="/^([\u0600-\u06FF\s]){2,30}$/" required data-pristine-required-message="{LANG_PLEASE_ENTER_YOUR_NAME}" id="inp_name" />
                            </div>
                        </div>
                        <div class="my-3" id="container_inp_family">
                            <div class="text-end form-group">
                                <label for="inp_family" class="fs-6 text-muted">
                                    نام خانوادگی
                                    (فارسی)
                                </label>
                                <input type="text" class="form-control p-3" required name="family" data-pristine-pattern="/^([\u0600-\u06FF\s]){2,30}$/" required data-pristine-required-message="{LANG_PLEASE_ENTER_YOUR_FAMILY}" id="inp_family" />
                            </div>
                        </div>
                        <div class="my-3 d-none" id="container_inp_designer">
                            <div class="form-check text-end pe-3 mb-3">
                                <input checked="checked" class="form-check-input" type="checkbox" value="" name="designer" id="inp_designer" />
                                <label class="form-check-label" for="inp_designer">
                                    من طراح هستم
                                </label>
                            </div>
                        </div>
                    </div> 
                    <div class="my-3">
                        <div class="text-end">
                            <label for="phone" class="fs-6 mb-1 text-muted">
                                کد تایید را وارد کنید
                            </label>
                            <div class="d-flex flex-row-reverse code-inputs">
                                <div class="form-group p-1">
                                    <input required data-pristine-pattern="/^([0-9]){1,1}$/" type="number" maxlength="1" class="form-control text-center  p-3" index="0" name="code[]" data-pristine-required-message=" " data-pristine-pattern-message=" " autocomplete="off" />
                                </div>
                                <div class="form-group p-1">
                                    <input required data-pristine-pattern="/^([0-9]){1,1}$/" type="number" maxlength="1" class="form-control text-center  p-3" index="1" name="code[]" data-pristine-required-message=" " data-pristine-pattern-message=" " autocomplete="off" />
                                </div>

                                <div class="form-group p-1">
                                    <input required data-pristine-pattern="/^([0-9]){1,1}$/" type="number" maxlength="1" class="form-control text-center  p-3" index="2" name="code[]" data-pristine-required-message=" " data-pristine-pattern-message=" " autocomplete="off" />
                                </div>

                                <div class="form-group p-1">

                                    <input required data-pristine-pattern="/^([0-9]){1,1}$/" type="number" maxlength="1" class="form-control text-center  p-3" index="3" name="code[]" data-pristine-required-message=" " data-pristine-pattern-message=" " autocomplete="off" />
                                </div> 

                            </div>
                        </div>
                        <div class="timer_new d-flex align-items-center d-none pt-2 justify-content-end">
                            <span class="m-link timer_new-btn text-primary ms-3 fw-500 ">{LANG_NEW_CODE}</span>
                            <span class="m-link timer_new-edit  fw-500 text-primary">{LANG_EDIT_MOBILE_NUMBER}</span>
                        </div>
                        <div class="d-flex mt-3 text-end timer_timer">
                            <span class="text-muted flex-fill">
                                کد تایید ارسال شده به موبایلتان را وارد کنید
                            </span>
                            <span class="text-primary" id="timer-alert">2:00</span>
                        </div>
                        <!-- <div class="form-group my-4">
                            <div>
                                <div class="g-recaptcha" data-sitekey="6LeYdG0pAAAAAHk8vHK7RoS5D0aP1HTfliMUtVMu"></div> 
                            </div>
                        </div> -->
                        <a class="text-decoration-none d-block text-end text-primary my-3">
                            <i class="icon icon-edit fs-4 float-end"></i>
                            <span class="pe-2 back-btn"> تغییر شماره تلفن </span>
                        </a>
                        <a class="text-decoration-none d-none  text-end text-primary mt-3 d-flex align-items-center justify-contet-end" id="login-by-pass">
                            <i class="icon icon-link-214 fs-4 float-end"></i>
                            <span class="pe-2"> ورود با رمز عبور </span>
                        </a>
                    </div>
                    <div class="mt-4 mb-3 d-flex flex-column">
                        <button type="button" class="btn btn-lg btn-primary py-3" id="submit_code">
                            تایید
                        </button>
                    </div>
                </form>
            </section>
            <section class=" d-none justify-content-center auth my-3 mx-4 py-4 px-5 bg-white rounded rounded-5 auth-page__box" id="box-password">
                <div class="text-center">
                    <div class="mx-auto mt-2 text-center mb-5">
                        <img class=" logo " src="{CFILES}images/logo.svg" alt="">
                    </div>
                    <h4 class="text-dark fw-bolder"> 
                    ورود / ثبت نام با رمز ثابت
                                    </h4>
                    <p class="text-muted fs-6 mb-3 px-3 mx-3">
                        در صورتی که رمز عبور خود را به یاد میاورید وارد کنید و وارد شوید.
                    </p>
                </div>
                <form novalidate id="form-password">
                    <div class="my-3">
                        <div class="text-end">
                            <label for="inp_password" class="fs-6 text-muted">
                                رمز عبور
                            </label>
                            <input type="password" class="form-control p-3" required name="password" required data-pristine-required-message="{LANG_PLEASE_ENTER_YOUR_PASSWORD}" id="inp_password" />
                        </div>
                    </div>
                    <div class="mt-3">
                        <a class="text-decoration-none d-flex align-items-center justify-content-start text-end text-primary mt-3" id="login-by-code">
                            <i class="icon icon-link-214 fs-4 float-end"></i>
                            <span class="pe-2"> ورود با رمز یکبار </span>
                        </a>
                    </div>
                    <div class="mt-4 mb-3 d-flex flex-column">
                        <button type="button" class="btn btn-lg btn-primary py-3" id="submit_password">
                            تایید
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
     
    <script src="{HOST}{CFILES}js/main.min.js"></script>
    <script src="{HOST}file/global/js/pristine.js"></script>
    <script src="{HOST}{CFILES}js/client_app.js"></script>
</body>

</html>
<!-- END: main -->