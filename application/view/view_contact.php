<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="about container-md pt-3">
    {breadcrump} 
    <div class="contact  p-xl-3 m-xl-3">
        <div class="row">
            <div class="col-md-8 order-2 order-md-1">
                <div class="about-header pt-4 mb-xl-5 pb-xl-3">
                    <h1 class="text-right">
                        تماس با ما
                        <span class="text-primary">طـــــرح پیـــــچ</span>
                    </h1>
                    <p class="text-right text-muted my-3 fs-6 px-0 mx-0">
                        {desc}
                    </p>
                    <div class="mt-4 pt-4 d-flex">
                        <div class="wrapper-social">
                            <p class="text-secondary fs-6">
                                راه های ارتباطی
                            </p>
                            <div class="d-flex socials-widget">
                                <div class="col-auto p-0">
                                    <a href="mailto:{email}" rel="nofollow" class="youtube rounded-3 text-white d-block text-center" target="_blank" aria-label="youtube">
                                        <i class="icon icon-sms  fs-3 p-2 d-block"></i>
                                    </a>
                                </div>
                                <div class="col-auto mt-0 me-3 p-0">
                                    <a href="tel:{tel}" rel="nofollow" class="instagram rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich instagram">
                                        <i class="icon icon-call-calling4 fs-3 p-2 d-block"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="wrapper-social me-5">
                            <p class="text-secondary fs-6">
                                شبکه های اجتماعی
                            </p>
                            <div class="d-flex socials-widget">
                                <div class="col-auto p-0">
                                    <a href="{social_youtube}" rel="nofollow" class="youtube rounded-3 text-white d-block text-center" target="_blank" aria-label="youtube">
                                        <i class="icon icon-video-square5 fs-3 p-2 d-block"></i>
                                    </a>
                                </div>
                                <div class="col-auto mt-0 me-3 p-0">
                                    <a href="{social_instagram}" rel="nofollow" class="instagram rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich instagram">
                                        <i class="icon icon-instagram-bold fs-3 p-2 d-block"></i>
                                    </a>
                                </div>
                                <div class="col-auto mt-0 me-3 p-0">
                                    <a href="{social_facebook}" rel="nofollow" class="facebook rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich facebook">
                                        <i class="icon icon-facebook fs-3 p-2 d-block"></i>
                                    </a>
                                </div>
                                <div class="col-auto mt-0 me-3 p-0">
                                    <a href="{social_telegram}" rel="nofollow" class="telegram rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich telegram">
                                        <i class="icon icon-send-2 fs-3 p-2 d-block"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 order-1 order-md-2">
                <img class="img-fluid" src="{HOST}file/client/images/contact-img.png" alt="طرح پیچ" />
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <form id="form-contact-submit" novalidate>
                    <div class="d-flex flex-column position-relative">
                        <div class="wrapper-map flex-fill order-2 shadow-sm rounded-4">
                            <div id="map" class="object-map rounded-4"></div>
                        </div>
                        <div class="flex-fill order-1 contact-form rounded-4 col-5 p-3 bg-white shadow-sm">
                            <h4 class="fs-5 mb-3">
                                <i class="icon icon-headphone fs-2 ps-2 float-end text-primary"></i>
                                فرم اطلاعات تماس
                            </h4>
                            <div class="form-group mb-2 ">
                                <select name="subject" required class="form-control form-control-lg" id="">
                                    <option value="">انتخاب موضوع</option>
                                    <!-- BEGIN: subject -->
                                    <option value="{id}">{name}</option>
                                    <!-- END: subject -->
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <input name="full_name" required placeholder="نام و نام خانوادگی خود را وارد کنید" type="text" class="form-control form-control-lg" />
                            </div>
                            <div class="form-group mb-2">
                                <input name="email" required placeholder="ایمیل خود را وارد کنید" type="text" class="form-control form-control-lg" />
                            </div>
                            <div class="form-group mb-2">
                                <textarea name="exp" required placeholder="متن پیام خود را بنویسید" rows="6" type="textarea" class="form-control form-control-lg"></textarea>
                            </div>
                            <div class="mb-2">
                                <button id="submit-contact" class="btn btn-primary rounded-2 py-2 px-4">
                                    ارسال پیام
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var map_address = '{address}';
</script>
<!-- END: index -->