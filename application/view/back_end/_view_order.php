<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
    <div class="row">
        <div class="col-12">
            <div class="card table-card" data-title="{LANG_ORDERS}{orders_for}">
            </div>
        </div>
    </div>
    <div class="modal fade" id="orderInfoModal" tabindex="-1" aria-labelledby="orderInfoModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="orderInfoModalLabel">
                        جزئیات دانلود
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                   <div class="row">
                        <div class="col-12 d-flex ">
                            <div class="d-flex w-100 rounded-1 p-2 align-items-center   border border-1">
                                <div class="product-img">
                                    <img src="" width="50px" height="50px"  alt="" class="_el-product--img">
                                </div>
                                <div class="product-detail w-100 d-flex flex-column ps-2">
                                    <h6 class="m-0 ">
                                         <a class="_el-product--name"></a>&nbsp;<span class="text-danger _el-product--price"></span>
                                    </h6>
                                    <div class="d-flex align-items-center">
                                        <span class="text-muted">
                                        طراح:
                                        </span>&nbsp;
                                        <a  href="#" class="_el-product--full_name"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 ">
                            <div class="d-flex w-100 rounded-1 p-2 align-items-center  border border-top-0  border-bottom-0  ">
                                <div class="member-img" >
                                    <img src="" alt="" width="50px" height="50px" class="_el-member--img">
                                </div>
                                <div class="member-detail w-100 d-flex flex-column ps-2">
                                    <h6 class="m-0">
                                        <a  href="#" class="_el-member--name"></a>
                                    </h6>
                                    <span class="_el-member--date"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="order-detail d-flex flex-column border radius-1 p-2  ">
                                <div class="w-100 d-flex justify-content-between align-items-center py-1">
                                    <span>شماره سریال :</span>
                                    <span class="_el-order--serial"></span>
                                </div>
                                <div class="w-100 d-flex justify-content-between align-items-center py-1">
                                    <span>تاریخ :</span>
                                    <span class="_el-order--date"></span>
                                </div>
                                <div class="w-100 d-flex justify-content-between align-items-center py-1">
                                    <span>نوع پرداخت   :</span>
                                    <span class="_el-order--type"></span>
                                </div>
                                <div class="w-100 d-flex justify-content-between align-items-center py-1">
                                    <span>تخفیف :</span>
                                    <span class="_el-order--discount"></span>
                                </div>
                                <div class="w-100 d-flex justify-content-between align-items-center py-1">
                                    <span>مبلغ پرداختی :</span>
                                    <span class="_el-order--total-price text-danger"></span>
                                </div>
                                <div class="w-100 d-flex justify-content-between align-items-center py-1">
                                    <span>تراکنش:</span>
                                </div>
                                <pre class="  output">

                                </pre> 
                            </div>
                        </div>
                   </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">
                    بستن
                    </button>
                </div>
            </div>
        </div>
    </div>
<!-- END: index -->


