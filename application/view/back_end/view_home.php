<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->

<div class="row statistics-boxes">
     
    <div class="col-md-6 col-lg-3">
        <a href="{HOST}admin/product">
            <div class="card  border-bottom border-danger">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-danger display-6 d-flex">
                                <i class="mdi mdi-cart-outline"></i>
                            </span>
                        </div>
                        <div class="ms-4">
                            <h2>{statistics_total_products}</h2>
                            <h6 class="text-danger mb-0">
                                تعداد محصولات
                                {statistics_total_products_pend}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>  
    <div class="col-md-6 col-lg-3">
        <a href="{BASE_URL}member">
            <div class="card  border-bottom border-danger">
                <div class="card-body">
                    <div class="d-flex no-block align-items-center">
                        <div>
                            <span class="text-danger display-6 d-flex">
                                <i class="mdi mdi-account-multiple-outline"></i>
                            </span>
                        </div>
                        <div class="ms-4">
                            <h2>{statistics_total_members}</h2>
                            <h6 class="text-danger mb-0">کل کاربران</h6>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
 
 
<!-- END: index -->