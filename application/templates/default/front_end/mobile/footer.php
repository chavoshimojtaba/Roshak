<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: footer --> 
<footer id="footer" class="text-right">
    <!-- BEGIN: main_footer -->
    
    <div class="line-top">
        <a href="#top">
            <img src="{HOST}{CFILES}images/arrow-up.svg" alt="رفتن به بالای صفحه" />
        </a>
    </div>
    <div class="container-fluid position-relative  py-xl-5">
        <div class="row mb-3">
            <a class="col-12 col-lg-6 text-center text-lg-end text-decoration-none" href="{HOST}"  aria-label="طرح پیچ">
                <img src="{HOST}{CFILES}images/logo.svg" alt="" class="img-fluid" width="180px" />
            </a>
            <div class="col-12 col-lg-6 text-center text-lg-start">
                <div class="mt-1 social-links mx-auto">
                    <div class="d-flex justify-content-center mt-4 mt-lg-0 justify-content-lg-end">
                        <div class="col-auto p-0">
                            <a href="{social_youtube}" rel="nofollow" class="youtube  shadow-custom rounded-3 text-white d-block text-center" target="_blank" aria-label="youtube">
                                <i class="icon icon-play4 fs-3 p-2 d-block"></i>
                            </a>
                        </div>
                        <div class="col-auto mt-0 me-2 p-0">
                            <a href="{social_instagram}" rel="nofollow" class="telegram  shadow-custom rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich instagram">
                                <i class="icon icon-instagram fs-3 p-2 d-block"></i>
                            </a>
                        </div>
                        <div class="col-auto mt-0 me-2 p-0">
                            <a href="{social_telegram}" rel="nofollow" class="telegram  shadow-custom rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich telegram">
                                <i class="icon icon-send-21 fs-3 p-2 d-block"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="position-relative">
                    <p class="text-secondary justify-content-center content-hidden text-center">
                       {footer_desc}
                    </p>
                    <div class="more-wrapper text-center pt-2 pb-2">
                        <button class="btn px-4 mt-5 py-2 text-primary rounded-3 toggle-content">
                            <i class="icon icon-arrow-down-14 fs-3 float-start mt-0"></i>
                            مشاهده بیشتر
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="contact-footer rounded-4 py-2 px-3">
            <div class="row">
                <div class="col-12 col-lg-6 py-2 border-start border-white mb-2 text-center">
                    <span class="fs-5 text-white">
                        پشتیبانی سایت (شنبه تا چهارشنبه ۸ الی ۱۵):
                        <span class="d-block  py-2">
                        {tel}</span>
                    </span>
                </div>
                <div class="col-12 col-lg-6 text-center">
                    <span class="fs-5 text-white">
                        پشتیبانی ساعات غیراداری (واتساپ، ایتا، روبیکا، بله):
                    </span>
                    <span class="d-block fs-5 text-white">
                    {mobile}
                    </span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-lg-8 mt-4 mb-2">
                {footer_links}
            </div>
            <div class="col-sm-12 col-lg-4 pt-0">
                <hr class="border-secondary" />
                <div class="mb-3 d-flex text-muted">
                    <div class="flex-fill">
                        <i class="icon icon-call-calling4 text-white bg-primary icon-date fs-5 px-2 py-2 float-end rounded-3"></i>
                        <div class="d-flex mt-2 text-muted text-end pe-2">
                            شماره تماس
                        </div>
                    </div>
                    <div class="d-flex mt-2 text-dark float-start">
                        {tel}
                    </div>
                </div>
                <div class="mb-3 d-flex text-muted">
                    <div class="flex-fill">
                        <i class="icon icon-sms text-white bg-primary icon-date fs-5 px-2 py-2 float-end rounded-3"></i>
                        <div class="d-flex mt-2 text-muted text-end pe-2">
                        پست الکترونیکی
                        </div>
                    </div>
                    <div class="d-flex mt-2 text-dark float-start">
                    {email}
                                    </div>
                </div>
                <div class="mb-3 d-flex text-muted">
                    <div class="flex-fill">
                        <i class="icon icon-location text-white bg-primary icon-date fs-5 px-2 py-2 float-end rounded-3"></i>
                        <div class="d-flex mt-2 text-muted text-end pe-2">
                        {address}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
           
            <div class="col-12 p-sm-0 p-md-4 mt-2 mt-md-0">
                <div class="d-flex text-end justify-content-end py-2 pb-3">
                <a href="{HOST}about" target="_blank" class="bg-white ms-2 rounded-4">
                    <img width="70px" class="img-fluid" height="82px" src="{HOST}{CFILES}images/enamad.png" alt="enamad" />
                </a> 
                </div>
            </div>
        </div>
    </div>
    <!-- END: main_footer -->
    <div id="copy-right" class="bg-light position-relative text-center py-3">

        <div class="container-fluid">
            <p class="m-0 text-dark">
            تمام حقوق برای تیم <a class="text-decoration-none" href="{HOST}">طرح پیچ</a> محفوظ است. برنامه نویسی و سئو سایت شرکت تبلیغاتی <a class="text-decoration-none" href="https://mianborco.ir/">میانبر</a>
            </p>
        </div>
    </div>
</footer>
<!-- END: footer -->
<div class="col-12 ps-sm-0 ps-md-5">
                <div class="mt-0 subscibe-form mx-0">
                    <form action="" method="post" target="_blank">
                        <div class="d-flex position-relative subscibe-form-bottom pt-2">
                            <button class="btn btn-primary border-radius radius-4 btn-sm text-white px-2 ms-2">
                                <span class="text-nowrap">ثبت ایمیل</span>
                            </button>
                            <input name="footer_newsletter_email" class="form-control small fs-7 pt-2 pb-2 pr-3 text-info" placeholder="برای اطلاع از اخبار ایمیل خود را وارد کنید" type="text" />
                        </div>
                    </form>
                </div>
            </div>