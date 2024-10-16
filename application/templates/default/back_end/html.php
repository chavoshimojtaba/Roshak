<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: html -->
<!DOCTYPE HTML>
<html dir="rtl" lang="fa">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="keywords" content="Tarhpich" />
  <meta name="description" content="Tarhpich admin panel" />
  <meta name="robots" content="noindex,nofollow" />
  <title>{title}</title>
  <link rel="canonical" href="https://www.Tarhpich.com" />
  <!-- Favicon icon -->
  <base href=".">
  <!-- <link rel="icon" type="image/png" sizes="16x16" href="{HOST}file/admin/images/fav/favicon-16x16.png" /> -->
  <!-- Vector CSS -->
  <!-- <link href="{HOST}file/admin/libs/jvectormap/jquery-jvectormap.css" rel="stylesheet" /> -->
  <!-- Custom CSS -->
  <link href="{HOST}file/admin/css/fonts.css" rel="stylesheet" />
  <link href="{HOST}file/admin/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" />

  <link href="{HOST}file/admin/libs/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet" />
  <link href="{HOST}file/admin/extra-libs/toastr/dist/build/toastr.min.css" rel="stylesheet" />
  <link
      rel="stylesheet"
      type="text/css"
      href="{HOST}file/admin/libs/select2/dist/css/select2.min.css"
    />

  <link href="{HOST}file/admin/css/style.css" rel="stylesheet" />
  <link href="{HOST}file/admin/css/custom.css" rel="stylesheet" />
  <link href="{HOST}file/admin/css/SearchMultiSelectBox.css" rel="stylesheet" />
  <link href="{HOST}node_modules/dropzone/dist/dropzone.css" rel="stylesheet" type="text/css" />
  <link href="{HOST}file/global/persianDatePicker/mds.bs.datetimepicker.style.css" rel="stylesheet" />
  <link href="{HOST}file/admin/libs/calendar/dist/fullcalendar-admin.css" rel="stylesheet" /> 
  <link rel="stylesheet" href="{HOST}file/global/map/css/mapp.min.css">
<link rel="stylesheet" href="{HOST}file/global/map/css/fa/style.css">
    <script type="text/javascript">
      var page   = '{R}';
      var $_url  = '{HOST}';
      var $_Curl = '{CURRENT_URL}';
      var $_Burl = '{BASE_URL}';
      var langs = JSON.parse('{langs}');

      var HOST = '{HOST}';
        var CURL = '{PATH_URL}';
    </script>
</head>

<body>

  <div id="main-wrapper">
    {header}
    {menu_right}
    {body}
  </div>
  
  <div id="partials">
    <input type="hidden" class="form-control" id="pub_id" value="0"/>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">ویرایش</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">انصراف</button>
            <button type="button" class="btn btn-primary">ثبت</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="chat-windows"></div>
  <script src="{HOST}file/admin/libs/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap tether Core JavaScript -->
  <script src="{HOST}file/admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <!-- apps -->
  <script src="{HOST}file/admin/js/app.min.js"></script>
  <script src="{HOST}file/admin/js/app.init.js"></script>
  <script src="{HOST}file/admin/js/app-style-switcher.js"></script>
  <!-- slimscrollbar scrollbar JavaScript -->
  <script src="{HOST}file/admin/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
  <script src="{HOST}file/admin/extra-libs/sparkline/sparkline.js"></script>
  <!--Wave Effects -->
  <script src="{HOST}file/admin/js/waves.js"></script>
  <!--Menu sidebar -->
  <script src="{HOST}file/admin/js/sidebarmenu.js"></script>
  <!--Custom JavaScript -->
  <script src="{HOST}file/admin/js/feather.min.js"></script>
  <script src="{HOST}file/admin/js/custom.min.js"></script>
  <!--This extra -->
  <script src="{HOST}file/admin/libs/select2/dist/js/select2.min.js"></script>

  <!--This page JavaScript -->
  <script src="{HOST}file/admin/extra-libs/toastr/dist/build/toastr.min.js"></script>
  <script src="{HOST}file/admin/libs/sweetalert2/dist/sweetalert2.min.js"></script>
  <script src="{HOST}file/global/js/pristine.js"></script>
  <script src="{HOST}file/global/tinymce/tinymce.min.js"></script>
  <script src="{HOST}file/global/tinymce/tinymce_config.js"></script>
  <script src="{HOST}file/global/persianDatePicker/mds.bs.datetimepicker.js"></script>

  <script src="{HOST}file/admin/libs/nestable/jquery.nestable.js"></script>
  <script src="{HOST}file/admin/libs/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>

  <script src="{HOST}file/admin/libs/calendar/jquery-ui.min.js"></script>  

  <script src="{HOST}file/global/js/util.js"></script>
  <script src="{HOST}file/global/dropzone/dropzone.js"></script>  
  <script type="text/javascript" src="{HOST}file/global/map/js/mapp.env.js"></script>
  <script type="text/javascript" src="{HOST}file/global/map/js/mapp.min.js"  async defer></script> 


  <script  src="{HOST}file/admin/js/admin_app.js?ver={JSVERSION}"></script>
</body>

</html>
<!-- END: html -->



<!-- 
<aside class="customizer d-none">
    <a href="javascript:void(0)" class="service-panel-toggle"><i class="fa fa-spin fa-cog"></i></a>
    <div class="customizer-body">
      <ul class="nav customizer-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true"><i class="mdi mdi-wrench fs-6"></i></a>
        </li>
      </ul>
      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
          <div class="p-3 border-bottom"> 
            <h5 class="font-weight-medium mb-2 mt-2">Layout Settings</h5>
            <div class="form-check mt-3">
              <input type="checkbox" name="theme-view" class="form-check-input" id="theme-view" />
              <label class="form-check-label" for="theme-view">
                <span>Dark Theme</span>
              </label>
            </div>
            <div class="form-check mt-2">
              <input type="checkbox" class="sidebartoggler form-check-input" name="collapssidebar" id="collapssidebar" />
              <label class="form-check-label" for="collapssidebar">
                <span>Collapse Sidebar</span>
              </label>
            </div>
            <div class="form-check mt-2">
              <input type="checkbox" name="sidebar-position" class="form-check-input" id="sidebar-position" />
              <label class="form-check-label" for="sidebar-position">
                <span>Fixed Sidebar</span>
              </label>
            </div>
            <div class="form-check mt-2">
              <input type="checkbox" name="header-position" class="form-check-input" id="header-position" />
              <label class="form-check-label" for="header-position">
                <span>Fixed Header</span>
              </label>
            </div>
            <div class="form-check mt-2">
              <input type="checkbox" name="boxed-layout" class="form-check-input" id="boxed-layout" />
              <label class="form-check-label" for="boxed-layout">
                <span>Boxed Layout</span>
              </label>
            </div>
          </div>
          <div class="p-3 border-bottom"> 
            <h5 class="font-weight-medium mb-2 mt-2">Logo Backgrounds</h5>
            <ul class="theme-color logo-theme m-0 p-0">
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-logobg="skin1"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-logobg="skin2"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-logobg="skin3"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-logobg="skin4"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-logobg="skin5"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-logobg="skin6"></a>
              </li>
            </ul> 
          </div>
          <div class="p-3 border-bottom"> 
            <h5 class="font-weight-medium mb-2 mt-2">Navbar Backgrounds</h5>
            <ul class="theme-color  navbar-theme m-0 p-0">
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-navbarbg="skin1"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-navbarbg="skin2"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-navbarbg="skin3"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-navbarbg="skin4"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-navbarbg="skin5"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-navbarbg="skin6"></a>
              </li>
            </ul> 
          </div>
          <div class="p-3 border-bottom"> 
            <h5 class="font-weight-medium mb-2 mt-2">Sidebar Backgrounds</h5>
            <ul class="theme-color sidebar-theme m-0 p-0">
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-sidebarbg="skin1"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-sidebarbg="skin2"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-sidebarbg="skin3"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-sidebarbg="skin4"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-sidebarbg="skin5"></a>
              </li>
              <li class="theme-item list-inline-item me-1">
                <a href="javascript:void(0)" class="theme-link rounded-circle d-block" data-sidebarbg="skin6"></a>
              </li>
            </ul> 
          </div>

        </div>
      </div>
    </div>
  </aside> -->