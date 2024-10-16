<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0">پیام </h4>
			</div>
			<div class="card-body">
				<form id="form-message-submit" novalidate >
					<div class="row">
						<div class="col-md-3  ">
							<div class="mb-3 form-group">
								<label for="inp_type" class="form-label">Email | SMS</label>
								<select class="form-control" required id="inp_type" name="type">
									<option value="">انتخاب کنید</option>
									<option value="sms">SMS</option>
									<option value="email">Email</option>
								</select>
							</div>
						</div>
						<div class="col-md-3">
							<div class="mb-3 form-group">
								<label for="inp_for" class="form-label">ارسال برای</label>
								<select class="form-control" required id="inp_for" name="for">
									<option value="">انتخاب کنید</option>
									<option value="public">تمام مشتریان</option>
									<option value="designer">طراحان</option>
									<option value="common">مشتریان عادی</option>
									<option value="custom">منتخب</option>
								</select>
							</div>
						</div>
						<div class="col-md-3 d-none">
							<div class="mb-3 form-group">
								<label for="inp_template" class="form-label">انتخاب قالب</label>
								<select class="form-control" id="inp_template" name="template">
									<option value="0">انتخاب کنید</option>
									<!-- BEGIN: templates -->
									<option value="{alias}" data-type="{type}">{title}</option>
									<!-- END: templates -->
									<option value="custom">بدون قالب</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 ">
							<div class="mb-3 form-group " id="form-group_text">
								<label for="inp_date" class="control-label">زمان ارسال <span class="text-danger fs-1">(بصورت خودکار 1 ساعت به تایم مشخص شده اضافه میگردد)*</span></label>
									<input type="text" autocomplete="off" class="form-control" id="inp_date" required data-name="send-date" />
									<input type="hidden" name="date">
							</div>
						</div>
						<div class="col-12 d-none choose-member">
							<div class=" form-group  multi-select inp_member">
							</div>
						</div>
						<div class="mb-3 d-none form-group mt-2" id="form-group_sms">
							<label for="inp_text" class="control-label">قالب متن   <span class="text-danger">*</span></label>
							<textarea minlength="2" id="inp_text" class="field_short_text form-control  " name="text"></textarea>
							<div class="invalid-feedback">
								لطفا متن   را وارد کنید
							</div>
						</div>
						<div class="mb-3 d-none form-group mt-2" id="form-group_email">
							<label for="inp_text2" class="control-label">قالب متن   <span class="text-danger">*</span></label>
							<textarea id="inp_text2" class="field_short_text form-control  editor" name="text2"></textarea>
							<div class="invalid-feedback">
								لطفا متن   را وارد کنید
							</div>
						</div>

						<div class="col-12 pt-2">
							<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit_message">
								<i class="ti-save"></i>&nbsp;ثبت
							</button>
						</div>
					</div>
			</div>
			</form>
		</div>
	</div>
</div>


<!-- END: index -->
<!-- BEGIN: sms_template -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_SMS_TEMPLATE}"></div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="editModalLabel">
					افزودن/ویرایش {LANG_SMS_TEMPLATE}
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-template-submit" novalidate>
					<input type="hidden" />
					<div class="alert alert-danger p-2 ">
						<p class="text-start d-flex m-0 fw-bold align-items-center">
							<i class="ti ti-na me-1"></i>
							مهم * : برای ویرایش قالب مورد نظر مقادیر ورودی که بین دو کاراکتر {} قرار گرفته شده حفظ شود.
						</p>
					</div>
					<div class="mb-3 form-group">
						<label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
						<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
					</div>
					<div class="mb-3 form-group d-none">
						<label for="inp_alias" class="control-label">شناسه {LANG_EN}<span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_alias" disabled name="alias" data-pristine-pattern="/^([A-za-z])([A-za-z]|[_]){1,150}$/" required data-pristine-required-message="لطفا شناسه را انگلیسی وارد کنید" />
					</div>
					<div class="mb-3 form-group">
						<label for="inp_exp" class="control-label">متن<span class="text-danger">*</span></label>
						<textarea class="form-control" id="inp_exp" name="exp" required data-pristine-required-message="لطفا متن را وارد کنید"></textarea>
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
				<button type="button" class="btn btn-success" id="submit_template">ثبت </button>
			</div>
		</div>
	</div>
</div>
<!-- END: sms_template -->
<!-- BEGIN: email_template -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_EMAIL_TEMPLATE}">

		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="editModalLabel">
					افزودن/ویرایش {LANG_EMAIL_TEMPLATE}
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-template-submit" novalidate>
					<input type="hidden" />
					<div class="alert alert-danger p-2 ">
						<p class="text-start d-flex m-0 fw-bold align-items-center">
							<i class="ti ti-na me-1"></i>
							مهم * : برای ویرایش قالب مورد نظر مقادیر ورودی که بین دو کاراکتر {} قرار گرفته شده حفظ شود.
						</p>
					</div>
					<div class="mb-3 form-group">
						<label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
						<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
					</div>
					<div class="mb-3 form-group">
						<label for="inp_alias" class="control-label">شناسه {LANG_EN}<span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_alias" name="alias" data-pristine-pattern="/^([A-za-z])([A-za-z]|[_]){1,150}$/" required data-pristine-required-message="لطفا شناسه را انگلیسی وارد کنید" />
					</div>
					<div class="mb-3 form-group">
						<label for="inp_exp" class="control-label">متن<span class="text-danger">*</span></label>
						<textarea class="form-control" id="inp_exp" name="exp" required data-pristine-required-message="لطفا متن را وارد کنید"></textarea>
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
				<button type="button" class="btn btn-success" id="submit_template">ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: email_template -->