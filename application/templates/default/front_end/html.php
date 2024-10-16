<!-- BEGIN: html -->
<!doctype html>
<html class="no-js" lang="fa-IR" prefix="og: https://ogp.me/ns#" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="apple-touch-icon" sizes="180x180" href="{HOST}{CFILES}images/favicons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="{HOST}{CFILES}images/favicons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="{HOST}{CFILES}images/favicons/favicon-16x16.png" /> 
    <!-- BEGIN: Meta Tags -->
    <title>{meta_title}</title>
    <link rel="canonical" href="{meta_url}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="fontiran.com:license" content="C6FUV" /> 
    <meta name="theme-color" content="#4690cd" />
    <meta name="description" content="{meta_desc}" />
    <meta name="robots" content="{meta_follow_index}, max-snippet:-1, max-video-preview:-1, max-image-preview:standard" />  
    <meta property="og:locale" content="fa_IR" />
    <meta property="og:type" content="{meta_type}" />
    <meta property="og:title" content="{meta_title}" />
    <meta property="og:description" content="{meta_desc}" />
    <meta property="og:url" content="{meta_url}" />
    <meta property="og:site_name" content="{meta_site_name}" />
    <meta property="og:updated_time" content="{meta_update}" />
    <meta property="og:image" content="{HOST}{meta_image}" />
    <meta property="og:image:secure_url" content="{HOST}{meta_image}" />
    <meta property="og:image:width" content="{meta_width}" />
    <meta property="og:image:height" content="{meta_height}" />
    <meta property="og:image:alt" content="{meta_alt}" />
    <meta property="og:image:type" content="image/{meta_image_type}" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{meta_title}" />
    <meta name="twitter:description" content="{meta_desc}" />
    <meta name="twitter:image" content="{HOST}{meta_image}" />
    {meta_more}
    <!-- END: Meta Tags -->  
    {css_scripts} 
    <script type="text/javascript">
        var page = '{R}'.toLowerCase();
        var todaydate = '{today}';
        var HOST = '{HOST}';
        var CURL = '{PATH_URL}';
        var langs = JSON.parse('{langs}');
    </script>
</head> 
<body class="rtl">
    {menu_top}
    {dashboard_menu}
    <!-- BEGIN: body_dashboard -->
    <div class="dashboard container-md">
        <div class="row mt-3">
            <div class="col-md-3">
                <section id="sidebar-dashboard" class="bg-white shadow-custom rounded-4  pt-4 pb-2 ">
                    {menu_right}
                </section>
            </div>
            <div class="col-md-9">
                {body}
            </div>
        </div>
    </div>
    <div class="partials d-none">
        <div class="file-row" id="template">
            
            <label class="position-relative selectable w-100">
                <div class=" d-flex justify-content-between align-items-center rounded-1">
                    <div class="d-flex align-items-center file-info text-truncate pb-2">
                        <img data-dz-thumbnail class="rounded-1" width="60" />
                        <div class="d-flex flex-column  pe-2 file-info--text">
                            <h6 data-dz-name class="text-truncate file-name m-0">
                            </h6>
                            <div class="d-flex align-items-center">
                                <strong class="error text-danger m-0" data-dz-errormessage></strong>
                                <span data-dz-size class="  fs-6   text-dark "></span>
                                <span class="  me-1 dz-type  fs-6 fw-bolder text-dark "></span>
                            </div>
                        </div> 
                        <input type="radio" name="main-file" > 
                        <span class="main-file-image align-items-center justify-content-center text-center text-white d-none">
                            تصویر
                            <br>
                            اصلی
                        </span>
                    </div>
                    <div class="d-flex align-items-center action-buttons">
                        <span class=" text-info start me-1">
                            <i class="ti-upload"></i>
                        </span>
                        <span data-dz-remove class="btn rounded-2 py-1 px-2 text-white bg-danger delete">
                            حذف
                        </span>
                    </div>
                </div>
                <div class="bg-white rounded-2 dropzone-item-progress"> 
                    <div class="dropzone-item-progress-container posistion-relative mb-2">
                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div class="progress-bar progress-bar-striped bg-success" style="width:0%;" data-dz-uploadprogress></div>
                        </div>
                    </div>
                </div> 
            </label>
        </div>
    </div>
    <!-- END: body_dashboard -->
    <!-- BEGIN: body -->
    {body}
    <!-- END: body -->
   
      <!-- BEGIN: is_login -->
      <div class="modal fade show" id="modalexit" tabindex="-1" aria-labelledby="modalExitModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex align-content-between">
                    <h3 class="modal-title fs-5" id="modalExitModalLabel">
                        خروج از حساب کاربری
                    </h3> 
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img class="img-fluid my-4" src="{HOST}file/client/images/icon-exit.png" alt="خروج از حساب کاربری" />
                    <p class="fs-4">آیا از خروج مطمئن هستید؟</p>
                    <button class="modal-logout-btn btn btn-danger px-5 py-2 my-3 rounded-3">
                        خروج
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- END: is_login --> 
    {footer} 
</body>

<script src="{HOST}{CFILES}js/main.min.js"></script>
<script src="{HOST}file/global/js/pristine.js"></script>
{js_scripts}
<script src="{HOST}{CFILES}js/{path}_app.js"></script>
</html>
<!-- END: html -->