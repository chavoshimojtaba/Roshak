<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<main>
    <div class="container-md">
        <section id="search-block" class="text-center mb-5 pb-5">
            <div class="container-sm">
                <div class="position-relative d-flex flex-column align-items-center">
                    <p class="fs-1 m-0 fw-bolder d-inline-block">طـــــــــــــرح پــــــــــــــیچ</p>
                    <h1 class="fs-2 text-dark d-inline-block">
                        {h1_home}
                    </h1>
                    <p class="mb-4 d-inline-block">
                        {sub_title_home}
                    </p>
                    <img class="position-absolute arrow-absolute" src="{HOST}file/client/images/arrow-absolute.png" alt="" />
                </div>

                <div class="header-search-form rounded rounded-4 px-3">
                    <form class="d-flex align-items-stretch" action="{HOST}search">
                        <!-- <div class="position-relative">
                        <div class="position-relative">
                            <select name="cid" id="search-product" class=" select-2 form-control">
                                <option value="">دسته بندی</option>
                                <-- BEGIN: main_cats
                                <option value="{slug}">{title}</option>
                                <-- END: main_cats
                            </select>
                        </div>
                    </div> -->
                        <div class="flex-fill">
                            <div class="d-flex position-relative main-q-container">
                                <input id="main-q" type="text" name="q" class="me-0 form-control border-0 font-14 rounded-3 text-muted py-2" placeholder="هر چه دل تنگت میخواهد سرچ کن  :)" />
                                <div class="dropdown-menu search-header-result index-up-1 rounded-3 position-absolute mt-1 text-end" data-popper-placement="bottom-end">
                                    <div class="scrollbar-inner">
                                        <ul class="px-0 mb-0 d-flex" id="main-q_result"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="main-q-submit-btn" type="submit" aria-label="search" class="position-relative btn d-flex align-items-center btn-primary rounded-3">
                            <i class="icon text-white fs-2 py-1 icon-search-normal-14"></i>
                        </button>
                    </form>
                </div>

                <div class="px-xl-5 mx-xl-5">
                    <!-- prettier-ignore -->
                    <section id="search-tags" class="mt-0  pt-3 pb-2 px-0">
                        <div class="d-flex flex-wrap position-relative">
                            <!-- BEGIN: tags -->
                            <a href="{url}" class="flex-fill btn btn-outline-secondary mb-2 px-3 ms-1 me-1 py-2 text-decoration-none text-center">
                                {title}
                            </a>
                            <!-- END: tags -->
                        </div>
                    </section>
                </div>
                <!-- BEGIN: calendar -->

                <div class="mb-3 px-xl-5 mx-xl-5">
                    <div class="calendar-preview py-3 d-flex flex-fill">
                        <div class="flex-fill d-flex overflow-hidden">
                            <!-- BEGIN: events -->
                            <div class="d-flex flex-column w-100 calendar-item border-start px-2 align-items-center">
                                <div class="d-flex calendar-date px-3 py-1 active fw-bolder">
                                    <span class="calendar-item--day d-flex align-items-center justify-content-center ps-1 fs-5">{day}</span>
                                    <span class="calendar-item--mounth flex-fill">{mounth}</span>
                                </div>
                                <div class="calendar-events text-truncate d-flex flex-column h-100">
                                    <!-- BEGIN: event -->
                                    <a href="#">{title}</a>
                                    <!-- END: event -->
                                </div>
                            </div>
                            <!-- END: events -->
                        </div>
                        <div class="calendar-btn bg-white text-primary text-center px-2 align-items-center justify-content-center ms-3 rounded-1 d-flex flex-column">
                            <i class="icon-svg fw-bolder mt-1 calendar active"></i>
                            <span class="my-2 fw-bolder">
                                نمایش
                                <br />
                                تقویم
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- END: calendar -->
                <!-- BEGIN: plan -->
                <a href="{HOST}plan" class="btn btn-download">
                    <i class="icon icon-star float-end mt-1 ms-2"></i>
                    خرید اشتراک‌ ویژه‌ (دانلود ‌نامحدود‌ و ‌رایگان)
                </a>
                <!-- END: plan -->

            </div>

            <div class="line-bottom">
                <a href="#category-widget">
                    <img src="{HOST}file/client/images/arrow-down.svg" alt="رفتن به پایین صفحه" />
                </a>
            </div>
        </section>
        <section id="category-widget">
            <div class="row pb-0">
                <div class="col-12 pt-0 text-start">
                    <div class="category-slider wrapper-items   d-flex flex-wrap owl-carousel owl-theme">
                        <!-- BEGIN: section_categories -->
                        <a class="position-relative card rounded-4 bg-white item ml-3 text-decoration-none " href="{HOST}search/{slug}">
                            <figure class="m-1 p-1">
                                <img width="160px" height="120px" src="{HOST}{icon}" class="img-fit rounded rounded-4" src="" alt="دسته بندی {title}" title="{title}" />
                            </figure>
                            <div class="card-info text-center p-2">
                                <div class="mt-1 text-trucate">
                                    <span class="fs-6 text-dark bold">{title}</span>
                                </div>
                            </div>
                        </a>
                        <!-- END: section_categories -->
                    </div>
                </div>
            </div>
        </section>
        <!-- BEGIN: favorites -->

        <section id="favorite-widget" class=" mb-4">
            <div class="d-flex justify-content-center align-self-center mt-5 pt-0 mb-3">
                <span class="fav-title position-relative py-4 fw-bolder text-center text-dark fs-4">
                    <div class="bg-right"></div>
                    مورد علاقه های شما
                    <div class="bg-left"></div>
                </span>
            </div>
            <div class="row pb-0">
                <div class="col-12 pt-0 text-start">
                    <div class="favorite-slider custom-owl left owl-carousel owl-theme">
                        <!-- BEGIN: item -->
                        <div class="position-relative card rounded-4 bg-white item ml-3" width="170px">
                            <a class="text-decoration-none" href="{HOST}p/{slug}" target="_blank">
                                <figure class="m-1 p-1">
                                    <img width="150px" height="255px" src="{HOST}{thumbnail}" class="img-fit rounded rounded-4" src="" alt="{product_name}" title="{product_name}" />
                                </figure>
                            </a>
                        </div>
                        <!-- END: item -->
                    </div>
                </div>
            </div>
        </section>
        <!-- END: favorites -->
        <!-- BEGIN: plan_1 -->

        <div class="py-5 my-5">
            <div id="free-download-widget">
                <div class="close-widget">
                    <img src="{HOST}file/client/images/close-icon-download.svg" width="45" height="45" alt="خروج" />
                    <span class="fs-5 px-3  fw-bold  text-secondary">
                        پلن ها اشتراک طرح پیچ رو دیدی؟
                    </span>
                </div>
                <div class="mark-star"></div>
                <div class="wrapper-widget py-4 px-5">
                    <div class="bg-transparent-widget"></div>
                    <span class="fs-1 fw-bolder text-white position-relative px-3">
                        دانلود بیشتر و بصرفه...
                        <div class="title-rounded rounded-pill"></div>
                    </span>
                    <p class="fs-5 text-dark py-2 w-75">
                        با خریدی یکی از طرح‌های اشتراکی وب سایت طرح پیچ می‌تونی سود خوبی داشته باشی! اهل سود بردن هستی؟ </p>
                    <a class="btn btn-primary px-5 py-2 rounded-3 fs-5" href="{HOST}plan">
                        خرید طرح اشتراکی ویژه مدت محدود </a>
                </div>
            </div>
        </div>
        <!-- END: plan_1 -->
        <div id="files-tab-widget" class="bg-body rounded-4">
            <div class="wrapper-files">
                <ul class="d-flex justify-content-start pb-3 nav nav-tabs px-0" id="myTab" role="tablist">
                    <li class="nav-item me-2" role="tab">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="false" tabindex="-1">
                            جدیدترین طرح ها
                        </button>
                    </li>
                    <li class="nav-item me-2" role="tab">
                        <button class="nav-link text-secondary" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">
                            پرفروش ترین طرح ها
                        </button>
                    </li>
                    <li class="nav-item flex-fill" role="tab">
                        <button class="nav-link text-secondary" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="true">
                            طرح های رایگان
                        </button>
                    </li>
                    <div class="text-muted px-0 py-2" role="tab">
                        <a target="_blank" class="text-decoration-none" href="{HOST}search" role="button" aria-label="مشاهده همه محصولات">
                            مشاهده همه
                            <i class="icon fs-4 icon-arrow-left4 float-start pe-2"></i>
                        </a>
                    </div>
                </ul>
            </div>
            <div class="tab-content grid-content-item pt-4 p-0" id="myTabContent">
                <div class="tab-pane fade active show" id="home" role="tabpanel" aria-labelledby="home-tab">
                    {ssr_grid}
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    {ssr_grid_most_sell}
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    {ssr_grid_free_products}
                </div>
            </div>
        </div>
        <!-- BEGIN: coorporation -->

        <div id="contact-widget">
            <div class="close-widget">
                <img src="{HOST}file/client/images/close-icon-contact.svg" width="45px" height="45px" alt=" پلن ها اشتراک طرح پیچ رو دیدی؟" />
                <span class="fs-5 px-3 text-secondary">
                    پلن ها اشتراک طرح پیچ رو دیدی؟
                </span>
            </div>
            <div class="mark-star"></div>
            <div class="wrapper-widget py-4 px-5">
                <div class="bg-transparent-widget"></div>
                <span class="fs-1 text-white fw-bold position-relative px-3">
                    همکاری پر درآمد با طرح پیچ ویژه طراحان گرافیک...
                    <div class="title-rounded rounded-pill"></div>
                </span>
                <p class="fs-5 text-dark py-2 w-75">
                    اگر طراح گرافیک هستید و می‌خواهید کسب درآمد و فروش محصولات گرافیکی خود را آغاز کنین طرح پیچ برنامه ویژه‌ای برای شما دارد! مستقیم به مشتری بفروش و درآمد بیشتر به دست بیار...
                    متن دکمه
                    شروع همکاری پر سود رو رقم بزن! </p>
                <a class="btn btn-warning text-white px-5 py-2 rounded-3 fs-5" href="{HOST}cooperation">
                    شروع همکاری
                </a>
            </div>
        </div>
        <!-- END: coorporation -->
        <div id="comment-widget" class="d-flex justify-content-center align-self-center mt-4 pt-3 mb-3">
            <span class="comment-title position-relative text-center position-relative text-dark fw-bolder py-4 fs-4">
                <div class="bg-right"></div>
                حکایت کاربران از
                <span class="text-primary">طرح پیج</span>
            </span>
        </div>
        <div class="d-flex comment-wrapper justify-content-center mt-2 mb-5 text-center">
            <div class="container">
                <div class="col-sm-12 col-lg-7">
                    <div class="comment-slider custom-owl owl-carousel owl-theme">
                        <!-- BEGIN: user_stories -->
                        <div class="m-3 p-4 position-relative shadow-custom comment-card rounded-4 bg-white item ml-3">
                            <div class="d-flex card-info">
                                <div class="wrapper-profile">
                                    <img width="60px" height="60px" src="{HOST}{pic}" alt="{fullname}" />
                                    <div class="bg-under"></div>
                                </div>
                                <div class="info-user p-2">
                                    <span class="mb-0 pb-0 fw-bold fs-5">{fullname}</span>
                                    <a href="{url}" class="mt-2 d-block text-decoration-none">
                                        <span class="float-end">{sub_title}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-info text-secondary text-justify text-end pt-3">
                                <p class="m-0">{text}</p>
                            </div>
                        </div>
                        <!-- END: user_stories -->
                    </div>
                </div>
            </div>
        </div>
        <div id="blog-widget" class="d-flex justify-content-center align-self-center mt-5 pt-5 mb-3">
            <span class="blog-title position-relative text-center position-relative text-dark py-4 fs-4 fw-bolder">
                <div class="bg-right"></div>
                آخرین بلاگ پست ها
                <div class="bg-left"></div>
            </span>
        </div>
        <div class="d-flex mt-4 mb-5">
            <div class="blog-slider custom-owl owl-carousel owl-theme d-flex flex-wrap">
                <!-- BEGIN: blogs -->
                <div class="position-relative card rounded-4 bg-white item ml-3 d-flex flex-wrap" width="170px">
                    <figure class="m-1 p-1">
                        <a class="text-decoration-none" href="{url}" target="_blank">
                            <img width="250px" height="170px" src="{HOST}{img}" class="rounded rounded-4" src="" alt="{title}" title="{title}" />
                        </a>
                    </figure>
                    <div class="card-info text-end p-3">
                        <div class="text-muted h4 small mt-0">
                            <span>
                                <i class="icon icon-watch fs-5 float-end"></i>
                                زمان مطالعه:{reading_duration}
                            </span>
                            <span class="pe-2">
                                <i class="icon icon-calendar-14 fs-5 float-end"></i>
                                {blog_date}
                            </span>
                        </div>
                        <a class="text-decoration-none mt-1" href="{url}" target="_blank">
                            <span class="fs-6 text-secondary bold">
                                {title}
                            </span>
                        </a>
                        <hr class="border-secondary" />
                        <div class="d-flex justify-content-between mt-1">
                            <div class="avatar">
                                <img class="float-end ms-2 rounded-2" width="35px" height="35px" src="{HOST}{avatar}" alt="{author}" />
                                <div class="name text-nowrap pt-2">{author}</div>
                            </div>
                            <button class="btn btn-like" aria-label="like">
                                <i class="icon icon-heart fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- END: blogs -->
            </div>
        </div>
        <div class="d-lg-block profile-widget text-center mb-2 mb-lg-5 py-5 px-5">
            <span class="d-inline-flex fs-4 fw-bolder justify-content-center profile-title position-relative my-5 text-center">
                <div class="bg-right"></div>
                <span>صفحه پروفایل طراحان</span>
                <span class="text-primary">طرح پیچ</span>
                <div class="bg-left"></div>
            </span>
            <div class="profile-slider wrapper-items owl-carousel owl-theme">
                <!-- BEGIN: designers_items -->
                <div class="item">
                    <!-- BEGIN: designers_items_row -->
                    <div class="d-flex justify-content-between mt-5 px-md-5">
                        <!-- BEGIN: designers_item -->
                        <a class="profile-card text-center" href="{HOST}designers/{slug}">
                            <div class="wrapper-profile">
                                <img src="{HOST}{avatar}" alt="{full_name}" />
                                <div class="bg-under"></div>
                            </div>
                            <span class="title fs-5 text-dark d-block">{full_name}</span>
                            <span class="text-primary py-2 d-block"> {statistic_product} طرح </span>
                            <span class="text-warning pt-1 d-block">
                                <i class="icon icon-star"></i>
                                <span>امتیاز</span>
                                <span>{statistic_rate}</span>
                            </span>
                        </a>
                        <!-- END: designers_item -->
                    </div>
                    <!-- END: designers_items_row -->
                </div>
                <!-- END: designers_items -->
            </div>
            <a target="_blank" class="d-flex justify-content-center mt-5 align-items-center text-center text-decoration-none" href="{HOST}designers">
                مشاهده همه طراحان
                <i class="icon fs-4 icon-arrow-left4 float-start pe-2"></i>
            </a>
        </div>
        <div class="d-block d-lg-none profile-widget text-center mb-2 mb-lg-5 py-5 px-0">
            <span class="d-inline-flex fw-bolder justify-content-center profile-title position-relative my-5 text-center">
                <div class="bg-right"></div>
                <span>صفحه پروفایل طراحان</span>
                <span class="text-primary">طرح پیچ</span>
                <div class="bg-left"></div>
            </span>
            <div class="profile-slider-mobile justify-content-between mt-0 mt-lg-5 px-md-5 wrapper-items owl-carousel owl-theme">
                <!-- BEGIN: designers -->
                <div class="profile-card text-center">
                    <div class="wrapper-profile">
                        <img src="{HOST}{pic}" alt="{full_name}" alt="طراح {full_name}" />
                        <div class="bg-under"></div>
                    </div>
                    <span class="title fs-5 text-dark d-block">{full_name}</span>
                    <span class="text-primary py-2 d-block"> {statistic_product} طرح </span>
                    <span class="text-warning pt-1 d-block">
                        <i class="icon icon-star"></i>
                        <span>امتیاز</span>
                        <span>{statistic_rate}</span>
                    </span>
                </div>
                <!-- END: designers -->
            </div>
            <a target="_blank" class="d-flex justify-content-center mt-5 text-center text-decoration-none" href="{HOST}designers">
                مشاهده همه طراحان
                <i class="icon fs-4 icon-arrow-left4 float-start pe-2"></i>
            </a>
        </div>
        <section class="links-list  rounded-5 mt-2  mt-lg-5 pt-4 pb-2 px-1 px-xl-4">
            <div>
                <ul class="text-right m-0 d-flex flex-wrap ">
                    <!-- BEGIN: public_links_item -->
                    <li class="mb-3 list-group-item px-2">
                        <a href="{url}" class="text-decoration-none text-dark">{title}
                        </a>
                    </li>
                    <!-- END: public_links_item -->
                </ul>
            </div>
        </section>
        <section class="mt-4">
            <div>
                {home_desc}
            </div>
        </section>
    </div>
</main>
<!-- BEGIN: calendar_modal -->
<div class="calendar-modal">
    <span type="button" class="w-auto bg-transparent btn-close bg-none position-absolute start-0 top-0 m-2" >
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 511.88 511.88" width="24" height="24">
            <g data-name="Layer 2">
                <path d="M255.94,511.88A255.94,255.94,0,0,1,75,75L85.57,85.57,75,75a255.94,255.94,0,0,1,362,362A254.26,254.26,0,0,1,255.94,511.88Zm0-481.88A225.94,225.94,0,0,0,96.18,415.71,225.94,225.94,0,0,0,415.71,96.18,224.47,224.47,0,0,0,255.94,30Z" fill="#ffffff" class="color000 svgShape"></path>
                <path d="M170.76,356.13a15,15,0,0,1-10.61-25.61L330.52,160.15a15,15,0,1,1,21.21,21.21L181.36,351.73A15,15,0,0,1,170.76,356.13Z" fill="#ffffff" class="color000 svgShape"></path>
                <path d="M341.13,356.13a15,15,0,0,1-10.61-4.4L160.15,181.36a15,15,0,1,1,21.21-21.21L351.73,330.52a15,15,0,0,1-10.6,25.61Z" fill="#ffffff" class="color000 svgShape"></path>
            </g>
        </svg>
    </span>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl ">
        <div class="modal-content bg-transparent border-0">
            <div class="d-none modal-header">
                <h5 class="modal-title" id="calendarModalLabel">تقویم مناسبتی طرح پیچ</h5>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="card-body b-l calender-sidebar">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: calendar_modal -->
<!-- END: index -->