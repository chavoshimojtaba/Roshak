<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="ruls container-md pt-3">
    {breadcrump}
    <div class="p-xl-5 mt-sm-5 m-xl-5">
        <div class="ruls-header mb-5 pb-3">
            <h1 class="text-center">
                قوانین و مقررات
                <span class="text-primary">طــــــــرح پیـــــــــچ</span>
                <div class="bg-right"></div>
            </h1>
            <p class="text-center text-muted my-3 fs-5">
              {sub_title}
            </p>
        </div>
        <div class="row">
            <div class="col-md-4 col-xl-4">
                <div class="sidebar px-3 py-4 rounded-4">
                    <nav class="nav position-relative flex-column px-0">
                        <div class="nav-items fs-6">
                            <!-- BEGIN: policy1 -->
                            <a id="nav-item-tab" data-bs-toggle="tab" data-bs-target="#nav-tab{key}" type="button" role="tab" aria-controls="nav-tab{key}" aria-selected="true" class="nav-link py-3  {active}" aria-current="page" href="#">
                                {title}
                            </a>
                            <!-- END: policy1 --> 
                        </div>
                    </nav>
                </div>
            </div>
            <div class="col-md-8 col-xl-8">
                <!-- BEGIN: policy -->
                <article class="tab-pane box-content p-4 fade {active_show}" id="nav-tab{key}" role="tabpanel" aria-labelledby="nav-tab{key}" tabindex="0">
                    <h2 class="fs-5 text-primary">
                        {title}
                    </h2>
                    <p class="text-secondary">
                        {desc}
                    </p>
                </article>
                <!-- END: policy -->
            </div>
        </div>
    </div>
</div>
<!-- END: index -->