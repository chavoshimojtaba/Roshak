<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: add --> 
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="border-bottom title-part-padding">
                <h4 class="card-title mb-0">افزودن | ویرایش {LANG_PRODUCT}</h4>
            </div>
            <div class="card-body">
                <form id="form-product-submit" novalidate method="post">
                    <input type="number" class="form-control d-none" id="inp_id" value="{pid}" name="id" />
                    <input type="number" class="form-control d-none" id="inp_cid" value="{cid}" />
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="form-group mt-3">
                                <label>شهر و محله<span class="text-danger">*</span></label>
                                <div class="select-menu select-menu-city" value="{lid}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 d-flex align-items-end">
                            <div class="mt-3 form-group flex-fill">
                                <label for="inp_slug" class="control-label">{LANG_ADDRESS}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_address" value="{address}" name="address"  />
                                <input type="hidden" id="inp_lat" name="address_lat" value="{lat}" />
                                <input type="hidden" id="inp_lng" name="address_lng" value="{lng}" />
                                <input type="hidden" id="inp_address_json" name="address_json" value="{address_json}" />
                            </div>
                            <a type="button" class="btn m-0 ms-3 btn-light-info text-info d-flex align-items-center " data-bs-toggle="modal" data-bs-target="#mapModal">
                                <i class="ti-map py-1"></i> 
                            </a>
                        </div> 
                        <div class="col-12 col-md-4">
                            <div class="form-group mt-3">
                                <label>دسته بندی <span class="text-danger">*</span></label>
                                <div class="select-menu select-menu-cat" value="{cid}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="form-group mt-3 mt-3 ">
                                <label for="inp_title" class="control-label">عنوان <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_title" name="title" value="{main_title}" required data-pristine-required-message="لطفا عنوان  را وارد کنید" />
                            </div>
                        </div> 
                        <div class="col-12  col-md-8 ">
                            <div class=" mt-3 form-group  multi-select tag-select-box">
                                <!-- BEGIN: tags -->
                                <input type="hidden" value="{title}" id="{id}" data-selected="{selected}">
                                <!-- END: tags -->
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3 form-group " id="form-group_desc">
                                <label for="inp_desc" class="control-label">توضیحات <span class="text-danger">*</span></label>
                                <textarea  id="inp_desc" class="field_short_desc form-control editor">{desc}</textarea>
                                <div class="invalid-feedback">
                                    لطفا توضیحات را وارد کنید
                                </div>
                            </div>
                        </div>  
                        
                        <div class="col-12"><h3 class="border-bottom my-3 pb-1">تنظیمات سئو</h3></div>
                        
                        
                        <div class="col-12 col-md-4 ">
                            <div class="mt-3 form-group">
                                <label for="inp_seo_title" class="control-label">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_seo_title" value="{seo_title}" name="seo_title" required data-pristine-required-message="لطفا Title را   وارد کنید" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mt-3 form-group">
                                <label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_slug" value="{slug}" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید (حروف کوچک)" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mt-3 form-group">
                                <label for="inp_title" class="control-label">Index / Follow <span class="text-danger">*</span></label>
                                <div class="bt-switch ">
                                    <input {index} type="checkbox" id="inp_index" name="index" data-on-color="success" data-on-text="Index" data-off-text="No Index" />
                                    <input {follow} type="checkbox" id="inp_follow" name="follow" data-on-color="success" data-on-text="Follow" data-off-text="No Follow" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3 form-group">
                                <label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="inp_meta" maxlength="300" name="meta" required>{meta}</textarea>
                            </div>
                        </div>
                        <div class="col-12"><h3 class="border-bottom my-3 pb-1">چند رسانه ای</h3></div>
                         
                        <div class="col-12 col-lg-4 col-md-6">
                            <div class=" form-group mt-4">
                                <div class="upload-file pic-file">
                                    <!-- BEGIN: org_pic -->
                                    <input type="hidden" value="{img}" id="{id}">
                                    <!-- END: org_pic -->
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 col-md-6">
                            <div class=" form-group mt-4">
                                <div class="upload-file gallery-file">
                                    <!-- BEGIN: gallery -->
                                    <input type="hidden" value="{gallery_file}" id="{id}">
                                    <!-- END: gallery -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body border-top">
                <button type="button" id="submit_draft" class="  btn btn-warning  d-inline-flex align-items-center justify-content-center me-2">
                    <i class=" ti-save"></i>&nbsp;
                    ثبت پیش نویس
                </button>
                <button type="button" id="submit_product" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
                    <i class=" ti-save"></i>&nbsp;
                    ثبت {LANG_PRODUCT}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="mapModalLabel">
					افزودن/ویرایش {LANG_BC_GENERAL_LINK}
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
            <div class="modal-body">
                <div id="app" style="width: 100%; height: 400px;" ></div> 
                <div class="row">
                    <div class="col-12 ">
                        <div class="mt-3 form-group">
                            <label for="inp_modal_address" class="control-label">{LANG_ADDRESS}<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" disabled id="inp_modal_address"  />
                        </div>
                    </div>
                </div>
			</div>
			<div class="modal-footer"> 
                <button type="button" id="submit_latlng" class=" btn btn-success  d-inline-flex align-items-center justify-content-center me-2"  data-bs-dismiss="modal">
                    <i class=" ti-map"></i>&nbsp;
                    ثبت 
                </button>
			</div>
		</div>
	</div>
</div>
<div id="menuobject" class="d-none">
{menuObject}
</div>
<div id="locationObject" class="d-none">
{locationObject}
</div>
<script type="application/javascript">
    const locationObject = JSON.parse(document.querySelector('#locationObject').innerText);
    const menuObject     = JSON.parse(document.querySelector('#menuobject').innerText);
</script>

<!-- END: add -->



















<!-- BEGIN: index -->
<!-- BEGIN: designer -->
<div class="d-flex align-items-center  justify-content-center ">

    <div class="card p-3 w-50 pb-1 shadow-sm">
        <a class="d-flex align-items-center justify-content-center flex-column" href="{HOST}admin/member/view/{id}">
            <div class="product__member-pic me-2">
                <img src="{HOST}{pic}" alt="">
            </div>
            <div class="product__member-info d-flex align-items-center py-2">
                <h6 class="fw-bold m-0 me-2">{name} {family}</h6>
                <span class="text-info ms-2 fw-bold">
                    {expertise}
                </span>
            </div>
        </a>
        <div class="d-flex align-items-center justify-content-between pt-2">
            <div class="d-flex align-items-center">
                <i class="mdi mdi-account-star fs-30 text-info me-2"></i>
                <div>
                    <div class="fs-2">دنبال کننده ها</div>
                    <div class="fw-bold">{statistic_follower}</div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <i class="mdi mdi-palette fs-30 text-info me-2"></i>
                <div>
                    <div class="fs-2">طرح ها</div>
                    <div class="fw-bold">{statistic_product}</div>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <i class="mdi mdi-update fs-30 text-info me-2"></i>
                <div>
                    <div class="fs-2">شروع فعالیت</div>
                    <div class="fw-bold">{createAt}</div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: designer -->
<div class="row">
    <div class="col-12">
        <div class="card table-card" data-title="{LANG_PRODUCT}">
            <a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " href="{HOST}admin/product/add">
                <i class="ti-plus"></i>&nbsp;
                {LANG_PRODUCT} جدید
            </a>
        </div>
    </div>
</div>
<!-- END: index -->

<!-- BEGIN: reports -->
<div class="row">
    <div class="col-12">
        <div class="card table-card" data-title="گزارش تخلف ها"></div>
    </div>
</div>
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom py-1 px-2 d-flex align-items-center">
                <h4 class="modal-title" id="replyModalLabel">
                    پاسخ
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 form-group">
                    <h6>توضیحات کاربر:</h6>
                    <p id="el_title">

                    </p>
                </div>
                <form id="form-reply-submit" novalidate>
                    <input type="hidden" />
                    <div class="mb-3 form-group">
                        <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                        <label for="inp_reply" class="control-label">پاسخ<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="inp_reply" maxlength="150" rows="3" name="reply" required> </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer p-2 border-top">
                <button type="button" class="
                                btn btn-light-danger
                                text-danger
                                font-weight-medium
                                " data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_reply">ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: reports -->

<!-- BEGIN: print_request -->
<div class="row">
    <div class="col-12">
        <div class="card table-card" data-title="درخواست های چاپ و ویرایش"></div>

    </div>
</div>
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="replyModalLabel">
                    پاسخ
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>توضیحات کاربر:</h6>
                <p id="desc_print">
                </p>
                <form id="form-reply-submit" novalidate>
                    <input type="hidden" />
                    <div class="mb-3 form-group">
                        <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                        <label for="inp_reply" class="control-label">پاسخ<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="inp_reply" maxlength="150" rows="4" name="reply" required> </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="
                                btn btn-light-danger
                                text-danger
                                font-weight-medium
                                " data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_reply">ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: print_request -->

<!-- BEGIN: attributes -->
<div class="row">
    <div class="col-12">
        <div class="card table-card" data-title="{LANG_ATTRIBUTES}">
            <a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " data-bs-toggle="modal" data-bs-target="#editModal">
                <i class="ti-plus"></i>&nbsp;
                {LANG_ATTRIBUTE} جدید
            </a>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="editModalLabel">
                    افزودن/ویرایش نقش
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-attribute-submit" novalidate method="post">
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3 form-group">
                                <label for="inp_grp" class="control-label">{LANG_ATTRIBUTE} <span class="text-danger">*</span></label>
                                <select required class="form-select mr-sm-2 form-control" name="grp" data-pristine-required-message="لطفا ویژگی را انتخاب کنید" id="inp_grp">
                                    <!-- BEGIN: attribute_item -->
                                    <option value="{grp}">{alias}</option>
                                    <!-- END: attribute_item -->
                                </select>
                            </div>
                            <div class="mb-3 form-group">
                                <label for="inp_title" class="control-label">مقدار <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_title" name="title" value="{title}" required data-pristine-required-message="لطفا مقدار را وارد کنید" />
                                <input type="number" class="form-control d-none" id="inp_id" value="{id}" name="id" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="
                                btn btn-light-danger
                                text-danger
                                font-weight-medium
                                " data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_attribute">ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: attributes -->



 
<!-- BEGIN: view --> 
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center">
                    <div id="carouselExampleIndicators" class="carousel slide {is_image}" style="width: 400px" data-bs-ride="carousel">
                     
                        <div class="carousel-inner">
                            <!-- BEGIN: gallery1 -->
                            <div class="carousel-item {active}">
                                <img src="{HOST}{dir}" class="d-block w-100" alt="...">
                            </div>
                            <!-- END: gallery1 -->
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </a>
                    </div>
                </div>
                <h4 class="mt-3 card-title">{title}</h4>
                <div class="my-3 text-muted">
                    {desc}
                </div>
            </div>
        </div>
        <div class="card">
            <div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
                <h4 class="card-title mb-0"><i class="fas fa-caret-down"></i> {LANG_COMMENTS} : <!-- (میانگین امتیاز :
                    <span class="text-primary">
                        {rate}
                    </span>
                    از -->
                    <span class="text-primary">{cnt}
                    </span>
                    دیدگاه
                </h4>
            </div>
            <div class="card-body p-0">
                <input type="hidden" name="inp_total_comment" value="{total_comment}">
                <div id="widget-comments" class="comment-view">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <h5 class="card-title mb-2 border-bottom p-2 d-flex align-items-center justify-content-start lh-1"><i class="fs-3 fas fa-info-circle"></i>&nbsp; سایر اطلاعات</h5>
            <div class="card-body pt-0 bg-extra-light pb-2">
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">وضعیت</small>
                    <h6 class="mt-2 ltr" id="status-row">{status_badge}</h6>
                </div> 
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">بارگزاری فایل</small>
                    <h6 class="mt-2 ltr">{has_file}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">ارسال  فایل از طریق</small>
                    <h6 class="mt-2 ltr">{send_type}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">کد طرح</small>
                    <h6 class="mt-2 ltr">{serial}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">Slug</small>
                    <h6 class="mt-2 ltr">{slug}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">{LANG_PRICE}</small>
                    <h6 class="mt-2 ltr">
                        <strong class="mb-1 badge bg-success rtl" style="font-size: 16px;">{price} تومان</strong>
                    </h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">Follow</small>
                    <h6 class="mt-2 ltr">{follow}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">Index</small>
                    <h6 class="mt-2 ltr">{index}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">تاریخ ثبت</small>
                    <h6 class="mt-2 ltr">{createAt}</h6>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-0">
                    <small class="text-muted">دسته بندی</small>
                    <h6 class="mt-2"><span class="badge bg-warning">{category}</span></h6>
                </div>
                <div class="d-flex align-items-start justify-content-center flex-column mb-0 ">
                    <small class="text-muted">تگ ها</small>
                    <div class="d-flex align-items-start justify-content-center flex-wrap">
                        <!-- BEGIN: tags -->
                        <h6 class="mt-2 me-1"><span class="badge bg-primary">{title}</span></h6>
                        <!-- END: tags -->
                    </div>
                </div>
                <div class="d-flex align-items-center flex-column border-top pt-2 mt-2">
                    <a type="button" class="d-flex ms-0 mb-2 {change_status_btn}  align-items-center justify-content-center btn w-100 mx-0   waves-effect waves-light btn-primary" id="change-status"  >
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve" width="16px" fill="#FFFFFF" style="margin-left: 6px;">
                            <g>
                                <g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)">
                                    <path d="M2745.6,4633.8c-655.5-125.2-1175.1-594.2-1377.6-1241.7c-79.9-258.5-85.3-724.8-10.7-972.6c170.5-567.5,607.5-1025.9,1161.8-1215c183.9-61.3,634.2-98.6,807.4-66.6l90.6,16l16,207.8c58.6,754.1,559.6,1534.8,1169.8,1825.2c181.2,85.3,181.2,85.3,165.2,178.5c-8,50.6-63.9,194.5-122.6,319.8c-146.5,303.8-471.6,634.2-767.4,778.1C3513,4641.8,3105.3,4703.1,2745.6,4633.8z" />
                                    <path d="M5676.6,3272.3c-850-191.9-1502.8-815.4-1721.3-1652c-79.9-301.1-79.9-794,0-1092.5c215.8-815.4,834-1430.9,1641.4-1638.7c426.3-109.2,1031.2-61.3,1430.9,114.6c612.9,271.8,1089.8,818,1276.3,1462.9c79.9,279.8,98.6,743.4,40,1036.5c-215.8,1063.2-1135.1,1814.6-2211.6,1806.6C5969.8,3309.6,5764.6,3290.9,5676.6,3272.3z" />
                                    <path d="M1418.7,1249.8C1061.6,1015.4,749.9,690.3,526,317.2C384.8,82.8,230.3-295.6,161-588.7C62.4-988.4,30.4-951.1,486.1-951.1h394.4l538.2,538.3l540.9,540.9l423.7-423.7l423.7-423.7l223.8,223.8c189.2,191.8,556.9,474.3,660.8,508.9c24,8,10.7,109.2-53.3,378.4l-87.9,365.1l-175.9-21.3c-642.2-79.9-1175.1,85.3-1644,506.3l-135.9,122.6L1418.7,1249.8z" />
                                    <path d="M1056.3-1358.8l-756.7-724.8l421-13.3l423.7-13.3l71.9-282.4c245.1-964.6,887.3-1614.7,1915.8-1937.1c138.6-42.6,295.8-85.3,346.4-95.9l93.3-16l-79.9,58.6c-181.2,133.2-453,421-586.2,620.8c-255.8,383.7-397,839.3-431.7,1385.6l-16,279.8l445,5.3l445,8l-759.4,727.4c-418.3,397-762.1,724.8-767.4,724.8C1815.7-631.3,1472-959.1,1056.3-1358.8z" />
                                    <path d="M4024.6-1025.7c-133.2-85.3-317.1-218.5-410.3-295.8l-167.9-143.9L3902-1921c250.5-250.5,455.6-466.3,455.6-477c0-10.7-322.4-18.7-714.1-18.7h-716.8l24-165.2c29.3-239.8,143.9-591.5,253.1-794c191.8-354.4,381-546.2,897.9-916.6l210.5-149.2h2792.5H9900v221.2c-2.7,1398.9-551.6,2467.4-1630.7,3181.5l-258.5,170.5l-213.2-183.9c-341.1-295.8-692.8-479.6-1111.1-583.6c-285.1-69.3-866-61.3-1156.4,16c-415.7,111.9-852.7,357-1119.1,628.8c-63.9,66.6-125.2,119.9-133.2,119.9S4155.2-940.4,4024.6-1025.7z" />
                                </g>
                            </g>
                        </svg>
                        تعیین وضعیت
                    </a>
                    <a href="{BASE_URL}product/add/{id}" type="button" class="d-flex align-items-center justify-content-center btn mb-2 w-100 mx-0 waves-effect waves-light btn-info">
                        <i class="ti-pencil me-2"></i>
                        ویرایش محصول
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center  p-2">
                <h4 class="card-title">طراح</h4>
                <div class="profile-pic mb-0 mt-3">
                    <img src="{HOST}{user_pic}" width="150" height="150" class="rounded-circle" alt="user" />
                    <h4 class="mt-3 mb-0">{user_name}</h4>
                    <a href="mailto:{user_email}">{user_email}</a>
                </div>
            </div>
            <div class="p-2 border-top  ">
                <div class="row text-center">
                    <div class="col-12">
                        <a href="{BASE_URL}member/view/{mid}" class="
                            link
                            d-flex
                            align-items-center
                            justify-content-center
                            font-weight-medium
                            "><i class="mdi mdi-developer-board fs-6 me-1"></i>مشاهده پروفایل</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="replyModalLabel">
                    ثبت پاسخ
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-comment-submit" novalidate>
                    <input type="hidden" />
                    <div class="sform-group">
                        <label for="inp_desc" class="control-label">پاسخ<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="inp_reply" name="text" required data-pristine-required-message="لطفا پاسخ را وارد کنید"></textarea>
                        <input type="hidden" id="inp_pbid" value="0">
                        <input type="hidden" id="inp_type" value="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_comment">
                    ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center border-bottom">
                <h4 class="modal-title" id="statusModalLabel">
                    تعیین وضعیت
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-status-submit" novalidate>
                    <input type="hidden" />
                    <div class="form-group ">
                        <label for="inp_status">وضعیت</label>
                        <select class="form-control mt-2" id="inp_status" name="status" required data-pristine-required-message="لطفا وضعیت را انتخاب کنید">
                            <option value="">انتخاب کنید</option>
                            <option value="accept">{LANG_CONFIRM}</option>
                            <option value="reject">{LANG_REJECT}</option>
                        </select>
                    </div>
                    <div class="mt-3 form-group d-none" id="reason_inp">
                        <label for="inp_reason" class="control-label">توضیحات </label>
                        <textarea class="form-control" id="inp_reason" maxlength="300" name="reason" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_status">
                    ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editcommentmodal" tabindex="-1" aria-labelledby="editcommentmodalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="editModalLabel">
                    ویرایش
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-edit-submit" novalidate>
                    <input type="hidden" />
                    <div class="sform-group">
                        <label for="inp_text" class="control-label">پاسخ<span class="text-danger">*</span></label>
                        <textarea class="form-control" id="inp_text" name="text" required data-pristine-required-message="لطفا پاسخ را وارد کنید"></textarea>
                        <input type="hidden" id="pub_id" value="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_edit">
                    ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: view -->