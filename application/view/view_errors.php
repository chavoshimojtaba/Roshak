<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: 404 -->
<style>
    .page_404 {
        padding: 40px 0;
        background: #fff;
        font-family: 'Arvo', serif;
    }

    .page_404 img {
        width: 100%;
    }

    .four_zero_four_bg {

        background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
        height: 400px;
        background-position: center;
    }


    .four_zero_four_bg h1 {
        font-size: 80px;
    }

    .four_zero_four_bg h3 {
        font-size: 80px;
    }

    .link_404 {
        color: #fff !important;
        padding: 10px 20px;
        background: #39ac31;
        margin: 20px 0;
        display: inline-block;
    }

    .contant_box_404 {
        margin-top: -50px;
    }
</style>
<section class="page_404">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 ">
                <div class="four_zero_four_bg">
                    <h1 class="text-center ">404</h1>
                </div>
                <div class="contant_box_404 text-center">
                    <h3 class="h2">
                        اوپس! صفحه پیدا نشد
                    </h3>
                    <h6>منبع خواسته شده پيدا نشد</h6>

                    <p class="d-none">شما به دلايل زير نمي توانيد صفحه درخواستی را ملاحظه نماييد</p>

                    <ul class="d-none">
                        <li>
                            <h6>مطلب از انتشار خارج شده باشد</h6>
                        </li>
                        <li>
                            <h6>يک موتور جستجو که يک ليست تاريخ گذشته از اين صفحه دارد</h6>
                        </li>
                        <li>
                            <h6>یک آدرس اشتباه تايپ شده است</h6>
                        </li>
                        <li>
                            <h6>شما مجوز دسترسی به این صفحه را ندارید</h6>
                        </li>
                        <li>
                            <h6>منبع خواسته شده پيدا نشد</h6>
                        </li>
                        <li>
                            <h6>در هنگام پردازش درخواست شما خطايي رخ داده است</h6>
                        </li>
                    </ul>

                    <a href="{HOST}" class="btn btn-primary">{LANG_BACK_TO_HOME_PAGE}</a>
                </div>
                <div class="col-sm-10  text-center">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- END: 404 -->


<!-- BEGIN: ban -->
<div class="container min_height" style="margin-top: 20px">
    <div class="col-lg-12 box">
        <div class="clear"></div>
    </div>
    <div class="row col-md-12">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="alert alert-danger">آدرس ip شما برای 48 ساعت مسدود شده است</h4>
                            <p class="alert alert-warning" style="line-height: 28px;font-size: 14px;">
                                این خطا زمانی رخ میدهد که شما درخواست غیر مجاز به وبگاه ارسال کرده باشید
                                <br />
                                اگر فکر میکنید اشتباهی رخ داده است و آدرس ip شما به اشتباه مسدود شده است لطفا با پشتیبانان وب سایت تماس بگیرید
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div style="text-align: center;">
                                <a style="font-size:120px; text-decoration:none;">403</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: ban -->