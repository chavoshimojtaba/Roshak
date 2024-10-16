<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: menu_right -->
<aside class="left-sidebar">
  <div class="scroll-sidebar">
    <div class="user-profile position-relative" style="background: url({HOST}file/admin/images/background/user-info.jpg) no-repeat;">
      <div class="profile-img">
        <img src="{HOST}{pic}" alt="user" style=" width: 50px; height: 50px; border-radius: 50%;" />
      </div>
      <div class="profile-text pt-1 dropdown">
        <a href="#" class="
                  dropdown-toggle
                  u-dropdown
                  w-100
                  text-white
                  d-block
                  position-relative
                " id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">{name}</a>
        <div class="dropdown-menu animated flipInY" aria-labelledby="dropdownMenuLink">
          <a class="dropdown-item" href="#"><i data-feather="mail" class="feather-sm text-success me-1 ms-1"></i>
            پیام ها</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{BASE_URL}logout"><i data-feather="log-out" class="feather-sm text-danger me-1 ms-1"></i>
            خروج</a>
          <div class="dropdown-divider"></div>
          <div class="pe-4 p-2">
            <a href="{BASE_URL}user/profile" class="btn d-block w-100 btn-info rounded-pill">پروفایل</a>
          </div>
        </div>
      </div>
    </div>
    <nav class="sidebar-nav">
      <ul id="sidebarnav" class="d-flex flex-column">
        <li class="sidebar-item order_1">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{burl} " aria-expanded="false"><i class="mdi mdi-gauge"></i><span class="hide-menu">داشبورد</span></a>
        </li>
        <!-- BEGIN: single -->
        <li class="sidebar-item order_{order}">
          <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{burl}{url}" aria-expanded="false"><i class="{icon}"></i><span class="hide-menu">{name}</span></a>
        </li>
        <!-- END: single -->

        <!-- BEGIN: dropdown -->
        <li class="sidebar-item order_{order}">
          <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="{icon}"></i><span class="hide-menu">{name}</span></a>
          <ul aria-expanded="false" class="collapse first-level">
            <!-- BEGIN: dropdown_item -->
            <li class="sidebar-item">
              <a href="{burl}{url}" class="sidebar-link"><i class="{icon}"></i>
                <span class="hide-menu"> {name} </span></a>
            </li>
            <!-- END: dropdown_item -->
          </ul>
        </li>
        <!-- END: dropdown -->
      </ul>
    </nav>
  </div>
  <div class="sidebar-footer">
    <a href="#" class="link d-none" data-bs-toggle="tooltip" data-bs-placement="top" title="Settings"><i class="ti-settings"></i></a>
    <a href="{BASE_URL}logout" class="link w-100" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout"><i class="mdi mdi-power"></i></a>
  </div>
</aside>
<!-- END: menu_right -->