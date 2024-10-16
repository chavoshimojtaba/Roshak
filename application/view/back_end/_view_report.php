<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
 
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">گزارشگیری </h4>
			</div>
			<div class="card-body">
				<form id="form-report-submit" novalidate>
					<div class="row">
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_date_from" class="control-label">از تاریخ</label>
								<input type="text" class="form-control" id="date-from" data-name="date_from-date" />
								<input type="hidden" name="date_from" id="inp_date_from">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_date_to" class="control-label">تا تاریخ</label>
								<input type="text" class="form-control" id="date-to" data-name="date_to-date" />
								<input type="hidden" name="date_to" id="inp_date_to">
							</div>
						</div>

						<div class="col-lg-3 ">
							<div class="mb-3 form-group">
								<label for="inp_type" class="form-label">نوع</label>
								<select class="form-control" readonly="" id="inp_type" name="type">
									<option value="all">همه</option>
								<!-- 	<option value="designer">
										تمام طراحان
									</option>
									<option value="common">
										تمام مشتریان عادی
									</option> -->
									<option value="special">
										انتخاب مشتری/طراح
									</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3 ">
							<div class="mb-3 form-group">
								<label for="inp_status" class="form-label">وضعیت سفارش</label>
								<select class="form-control" readonly="" id="inp_status" name="status">
									<option value="all">همه</option>
									<option value="done">
										تکمیل سفارش
									</option>
									<option value="pend">
										در صف
									</option>
									<option value="failed">
										ناموفق
									</option>
									<option value="cancel">
										لغو شده
									</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-3 col-lg-3 mb-2 form-group">
							<label for="inp_cid" class="form-label"> دسته بندی </label>
							<div class="select-menu inp_data" id="data_cid">
							</div>
							<input type="hidden" class="form-control inp_data" id="inp_cid" name="cid" value="">
						</div>
						<div class="col-9 d-none" id="special-members">
							<div class=" form-group  multi-select member-select-box">
							</div>
						</div>
						<div class="col-12" >
							<div class=" form-group  multi-select product-select-box">
							</div>
						</div>
					<!-- 	<div class="col-12 mt-2">
							<input type="checkbox" id="inp_show_service" class="material-inputs filled-in chk-col-purple" >
							<label for="inp_show_service">نمایش خدمات</label>
						</div> -->
						<div class="col-12 pt-2">
							<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit-form">
								<i class="ti-save"></i>&nbsp;مشاهده
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card table-orders table-card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">خروجی گزارش</h4>
				<div class="d-flex">
					<button type="button" style="line-height: 1;" class="m-0 me-2 btn  btn-info d-none align-items-center justify-content-center" id="get-pdf">
						<i class="ti-save"></i>&nbsp;  PDF
					</button>
					<button type="button" style="line-height: 1;" class="m-0 btn btn-danger d-flex align-items-center justify-content-center" id="get-excel">
						<i class="ti-save"></i>&nbsp;  EXCEL
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered m-0 " >
						<thead class="table-light">
							<tr>
								<th scope="col">نوع پرداختی</th>
								<th scope="col">شماره سریال</th>
								<th scope="col">طراح</th>
								<th scope="col">مشتری</th>
								<th scope="col">محصول</th>
								<th scope="col">قیمت محصول</th>
								<th scope="col">وضعیت</th>
								<th scope="col">تاریخ</th>
								<th scope="col">قیمت کل</th>
							</tr>
						</thead>
						<tbody  id="table-orders">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="menuobject" class="d-none">
{menuObject}
</div>
<script type="application/javascript">
    const menuObject = JSON.parse(document.querySelector('#menuobject').innerText);
</script>
<!-- END: index -->
<!-- BEGIN: plan -->

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">گزارشگیری اشتراک ها </h4>
			</div>
			<div class="card-body">
				<form id="form-report-submit" novalidate>
					<div class="row">
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_date_from" class="control-label">از تاریخ</label>
								<input type="text" class="form-control" id="date-from" data-name="date_from-date" />
								<input type="hidden" name="date_from" id="inp_date_from">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_date_to" class="control-label">تا تاریخ</label>
								<input type="text" class="form-control" id="date-to" data-name="date_to-date" />
								<input type="hidden" name="date_to" id="inp_date_to">
							</div>
						</div>
						<div class="col-lg-3 ">
							<div class="mb-3 form-group">
								<label for="inp_status" class="form-label">وضعیت</label>
								<select class="form-control" readonly="" id="inp_status" name="status">
									<option value="all">همه</option>
									<option value="ended">
										اتمام اشتراک
									</option>
									<option value="pend">
										فعال
									</option>
								</select>
							</div>
						</div>
						<div class="col-12" id="special-members">
							<div class=" form-group  multi-select member-select-box">
							</div>
						</div>
						<div class="col-12 pt-2">
							<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit-form">
								<i class="ti-save"></i>&nbsp;مشاهده
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card table-orders table-card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">خروجی گزارش</h4>
				<div class="d-flex">
					<button type="button" style="line-height: 1;" class="m-0 me-2 btn  btn-info d-none align-items-center justify-content-center" id="get-pdf">
						<i class="ti-save"></i>&nbsp;  PDF
					</button>
					<button type="button" style="line-height: 1;" class="m-0 btn btn-danger d-flex align-items-center justify-content-center" id="get-excel">
						<i class="ti-save"></i>&nbsp;  EXCEL
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered m-0 " >
						<thead class="table-light">
							<tr>
								<th scope="col">وضعیت</th>
								<th scope="col">مشتری</th>
								<th scope="col">اشتراک</th>
								<th scope="col">تاریخ</th>
								<th scope="col">قیمت کل</th>
							</tr>
						</thead>
						<tbody  id="table-orders">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: plan -->

<!-- BEGIN: transaction -->

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">گزارشگیری اشتراک ها </h4>
			</div>
			<div class="card-body">
				<form id="form-report-submit" novalidate>
					<div class="row">
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_date_from" class="control-label">از تاریخ</label>
								<input type="text" class="form-control" id="date-from" data-name="date_from-date" />
								<input type="hidden" name="date_from" id="inp_date_from">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_date_to" class="control-label">تا تاریخ</label>
								<input type="text" class="form-control" id="date-to" data-name="date_to-date" />
								<input type="hidden" name="date_to" id="inp_date_to">
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_tracking_code" class="control-label">کد رهگیری</label>
								<input type="text" class="form-control" id="date-to" data-name="tracking_code-date" />
							</div>
						</div>
						<div class="col-lg-3 ">
							<div class="mb-3 form-group">
								<label for="inp_status" class="form-label">وضعیت</label>
								<select class="form-control" readonly="" id="inp_status" name="status">
								<option value="all">همه</option>
									<option value="done">
										تکمیل
									</option>
									<option value="pend">
										در صف
									</option>
									<option value="failed">
										ناموفق
									</option>
									<option value="bank">
										بانک
									</option>
								</select>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="mb-3 form-group">
								<label for="inp_type" class="form-label">نوع</label>
								<select class="form-control" readonly="" id="inp_type" name="type">
									<option value="all">همه</option>
									<option value="product">
										محصول
									</option>
									<option value="subscription">
										اشتراک
									</option>
								</select>
							</div>
						</div>
						<div class="col-12" id="special-members">
							<div class=" form-group  multi-select member-select-box">
							</div>
						</div>
						<div class="col-12 pt-2">
							<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit-form">
								<i class="ti-save"></i>&nbsp;مشاهده
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card table-orders table-card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">خروجی گزارش</h4>
				<div class="d-flex">
					<button type="button" style="line-height: 1;" class="m-0 me-2 btn  btn-info d-none align-items-center justify-content-center" id="get-pdf">
						<i class="ti-save"></i>&nbsp;  PDF
					</button>
					<button type="button" style="line-height: 1;" class="m-0 btn btn-danger d-flex align-items-center justify-content-center" id="get-excel">
						<i class="ti-save"></i>&nbsp;  EXCEL
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-bordered m-0 " >
						<thead class="table-light">
							<tr>
							<th scope="col">وضعیت</th>
								<th scope="col">مشتری</th>
								<th scope="col">کد رهگیری</th>
								<th scope="col">کد بانک</th>
								<th scope="col">پیام بانک</th>
								<th scope="col">نوع</th>
								<th scope="col">تاریخ</th>
								<th scope="col">مبلغ</th>
							</tr>
						</thead>
						<tbody  id="table-orders">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: transaction -->

<!-- BEGIN: category --> 
<div class="col-12 col-md-12">
    <div class="card">
        <div class="border-bottom title-part-padding">
            <h4 class="card-title mb-0">داده های آماری دسته بندی {LANG_PRODUCTS} </h4>
        </div>
        <div class="card-body p-2 p-md-4">
            <div class="treeview js-treeview">
                <ul>
                    <li id="treeview">
                        <div class="treeview__level" data-level="*">
                            <span class="level-title">Root (n سطحی)</span>
                            <div class="treeview__level-btns">
                                <div class="btn btn-primary btn-sm level-expand"><span class="ti-"></span></div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="editModalLabel">
                    افزودن/ویرایش {LANG_CATEGORY}
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-treeview-submit" novalidate>
                    <div class="row">
                        <div class=" col-md-4 col-xl-3">
                            <div class="mb-3 form-group">
                                <label for="inp_filetype">نوع فایل</label>
                                <select class="form-control " id="inp_filetype" name="filetype">
                                    <option value="">انتخاب کنید</option>
                                    <!-- BEGIN: filetype -->
                                    <option value="{key}">{value}</option>
                                    <!-- END: filetype -->
                                </select>
                            </div>
                        </div>
                        <div class=" col-md-4 col-xl-3">
                            <div class="mb-3 form-group">
                                <label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
                                <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                                <input type="number" class="form-control d-none" id="inp_pid" value="0" name="pid" />
                                <input type="hidden" class="form-control " id="inp_path" value="" name="path" />
                            </div>
                        </div>
                        <div class=" col-md-4 col-xl-3">
                            <div class="mb-3 form-group">
                                <label for="inp_seo_title" class="control-label">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_seo_title" value="{seo_title}" name="seo_title" required data-pristine-required-message="لطفا Title را   وارد کنید" />
                            </div>
                        </div>
                        <div class=" col-md-4 col-xl-3">
                            <input type="hidden" />
                            <div class="mb-3 form-group">
                                <label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_slug" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
                            </div>
                        </div>
                        <div class="col-12 col-md-8">
                            <div class="mb-3 form-group">
                                <label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="inp_meta" rows="1"  name="meta" required></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 form-group">
                                <label for="inp_short_desc" class="control-label">خلاصه توضیحات<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="inp_short_desc"  name="short_desc" required>{short_desc}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 form-group mt-2" id="form-group_desc">
                                <label for="inp_desc" class="control-label"> {LANG_DESCRIPTION} <span class="text-danger">*</span></label>
                                <textarea id="inp_desc" class="field_desc form-control editor"> </textarea>
                                <div class="invalid-feedback">
                                    لطفا {LANG_DESCRIPTION} را وارد کنید
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 ">
                            <div class="upload-file icon-file">
                                <!-- BEGIN: icon -->
                                <input type="hidden" value="{icon}" id="1">
                                <!-- END: icon -->
                            </div> 
                            <div class="mx-3 form-group d-none">
                                <input type="checkbox" id="inp_publish" name="publish" class="material-inputs filled-in chk-col-red">
                                <label for="inp_publish">انتشار</label>
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
                <button type="button" class="btn btn-success" id="submit_treeview">ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: category -->
