<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="row {show} statistics-boxes">
	<div class="col-md-6 col-lg-3">
		<a class="card  border-bottom border-primary" href="{HOST}admin/member/index">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-primary display-6 d-flex">
							<i class=" fas fa-users"></i>
						</span>
					</div>
					<div class="ms-3">
						<h2>{total}</h2>
						<h6 class="text-primary mb-0">تعداد کل </h6>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-6 col-lg-3">
		<a class="card  border-bottom border-danger" href="{HOST}admin/member/index/designer">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-danger display-6 d-flex">
							<i class="fas fa-paint-brush"></i>
						</span>
					</div>
					<div class="ms-3">
						<h2>{designer}</h2>
						<h6 class="text-danger mb-0">طراحان</h6>
					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-6 col-lg-3">
		<a class="card  border-bottom border-success" href="{HOST}admin/member/index/common">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-success display-6 d-flex">
							<i class=" fas fa-user"></i>
						</span>
					</div>
					<div class="ms-3">
						<h2>{common}</h2>
						<h6 class="text-success mb-0">مشتری عادی</h6>

					</div>
				</div>
			</div>
		</a>
	</div>
	<div class="col-md-6 col-lg-3 d-none">
		<a class="card  border-bottom border-warning" href="{BASE_URL}member/index/change_type_request">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-warning display-6 d-flex">
							<i class=" fas fa-user-plus"></i>
						</span>
					</div>
					<div class="ms-3">
						<h2>{pend}</h2>
						<h6 class="text-warning mb-0">درخواست های همکاری <i class="ms-1 ti-angle-left" style="font-size: 10px;"></i> </h6>
					</div>
				</div>
			</div>
		</a>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_MEMBERS}" data-id="table-members"></div>
	</div>
</div>
<!-- END: index -->
<!-- BEGIN: coopration_requests -->

<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_REQUESTS}" data-id="table-requests"></div>

	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-sm " role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="editModalLabel">
					رزومه
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row" id="file-rows">

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="
								btn btn-light-danger
								text-danger
								font-weight-medium
								" data-bs-dismiss="modal">
					بستن
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: coopration_requests -->
<!-- BEGIN: settlement_requests -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_SETTLEMENT_REQUESTS}" data-id="table-requests"></div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-sm " role="document">
		<div class="modal-content">
			<div class="modal-header d-flex border-bottom py-1 px-2  align-items-center">
				<h4 class="modal-title" id="editModalLabel">
					اطلاعات
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-12">
						<h6>توضیحات کاربر:</h6>
						<p id="el_desc">

						</p>
					</div>
					<div class="col-12">
						<form id="form-reply-submit" novalidate>
							<input type="hidden" />
							<div class="mb-3 form-group">
								<input type="hidden" id="inp_id" value="0" name="id" />
								<input type="hidden" id="inp_mid" value="0" name="mid" />
								<label for="inp_reply" class="control-label">پاسخ<span class="text-danger">*</span></label>
								<textarea class="form-control" id="inp_reply" maxlength="150" rows="3" name="reply" required> </textarea>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="modal-footer py-1">
				<button type="button" class="
								btn btn-light-danger
								text-danger
								font-weight-medium
								" id="btn-status-reject">
					رد درخواست
				</button>
				<button type="button" class="
								btn btn-light-success
								text-success
								font-weight-medium
								" id="btn-status-accept">
					تایید درخواست
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: settlement_requests -->
<!-- BEGIN: view -->
<!-- BEGIN: has_plan -->
<div class="card ">
	<div class="bg-primary rounded card-body p-3">
		<div class="row">
			<div class="text-white col">
				<small class="   db"> اشتراک فعال </small>
				<h6 class="text-white mb-0 mt-2">{plan_name}</h6>
			</div>
			<div class="text-white col">
				<small class=" ">تاریخ شروع</small>
				<h6 class="text-white mb-0 fw-bold mt-2">{startt_date}</h6>
			</div>
			<div class="text-white col">
				<small class=" ">تاریخ پایان</small>
				<h6 class="text-white mb-0 fw-bold mt-2">{endd_date}</h6>
			</div>
			<div class="text-white col">
				<small class=" ">تعداد روز باقیمانده</small>
				<h6 class="text-white mb-0 fw-bold mt-2">{left_days} روز</h6>
			</div>
			<div class="text-white col">
				<small class=" ">تعداد دانلود امروز </small>
				<h6 class="text-white mb-0 fw-bold mt-2">{today_downloads} عدد</h6>
			</div>
		</div>
	</div>
</div>
<!-- END: has_plan -->
<!-- BEGIN: no_plan -->
<div class="alert mt-md-2 alert-danger  p-2" role="alert">
	<h4 class=" m-0 	alert-heading">توجه</h4>
	<p class=" my-1 text-start">در حال حاضر اشتراکی برای کاربر مورد نظر فعال نمیباشد
	</p>
</div>
<!-- END: no_plan -->
<div class="row statistics-boxes">
	<!-- BEGIN: designer -->
	<div class="col-md-4 col-lg-3 col-xlg-3">
		<div class="card  border-bottom border-info">
			<div class="card-body">
				<a class="d-flex no-block align-items-center" href="{HOST}admin/product/index/{mid}">
					<div>
						<span class="text-info display-6 d-flex">
							<i class="fas fa-shopping-cart"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{statistic_product}</h2>
						<h6 class="text-info mb-0 d-flex align-items-center justify-content-start">
							طرح ها -
							مشاهده
							<i class="ms-1 ti-angle-left" style="font-size: 10px;"></i>
						</h6>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-lg-3 col-xlg-3">
		<div class="card  border-bottom border-success">
			<div class="card-body">
				<a class="d-flex no-block align-items-center" href="{HOST}admin/order/index/designer/{mid}">
					<div>
						<span class="text-success display-6 d-flex">
							<i class="mdi mdi-comment-processing"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{total_income}</h2>
						<h6 class="text-success mb-0 d-flex align-items-center justify-content-start">
							فروش -
							مشاهده
							<i class="ms-1 ti-angle-left" style="font-size: 10px;"></i>
						</h6>
					</div>
				</a>
			</div>
		</div>
	</div>
	<!-- END: designer -->
	<div class="col-md-4 col-lg-3 col-xlg-3">
		<div class="card  border-bottom border-danger">
			<div class="card-body">
				<a class="d-flex no-block align-items-center" href="{HOST}admin/order/index/{id}">
					<div>
						<span class="text-danger display-6 d-flex">
							<i class="mdi mdi-file-image"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{statistic_downloads} طرح</h2>
						<h6 class="text-danger mb-0 d-flex align-items-center justify-content-start">
							دانلود ها -
							مشاهده
							<i class="ms-1 ti-angle-left" style="font-size: 10px;"></i>
						</h6>
					</div>
				</a>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-lg-3 col-xlg-3">
		<div class="card  border-bottom border-danger">
			<div class="card-body">
				<a class="d-flex no-block align-items-center" href="{HOST}admin/ticket/index/{id}">
					<div>
						<span class="text-danger display-6 d-flex">
							<i class="mdi mdi-file-image"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{statistic_tickets} عدد</h2>
						<h6 class="text-danger mb-0 d-flex align-items-center justify-content-start">
							تیکت ها -
							مشاهده
							<i class="ms-1 ti-angle-left" style="font-size: 10px;"></i>
						</h6>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-3 col-xlg-3 col-md-5">
		<div class="card">
			<div class="card-body pb-0">
				<center class="mt-4">
					<img src="{HOST}{pic}" class="rounded-circle" width="150" height="150" id="user_pic">
					<h4 class="card-title mt-2" id="user_full_name">{name} {family}</h4>
					<h6 class="card-subtitle">{email}</h6>
					<div class="row text-center justify-content-center">
						<div class="col-12">
							<span class="badge bg-light-{type_class} text-{type_class}">نوع کاربری : {type_fa}</span>
						</div>
					</div>
				</center>
			</div>
			<div>
				<hr>
			</div>
			<div class="card-body pt-0">
				<small class="text-muted   db">شماره همراه</small>
				<h6>{mobile}</h6>
				<small class="text-muted pt-4 db">ایمیل</small>
				<h6 id="user_address">{email}</h6>
				<small class="text-muted pt-4 db">تاریخ تولد</small>
				<h6 id="user_address">{birthdate}</h6>
				<small class="text-muted pt-4 db">تاریخ ثبت نام</small>
				<h6 id="user_address">{createAt}</h6>
				<small class="text-muted pt-4 db">اخرین ورود </small>
				<h6 id="user_address">{last_login}</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-9 col-xlg-9 col-md-7">
		<div class="card">
			<ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active" id="pills-info-tab" data-bs-toggle="pill" href="#tab-info" role="tab" aria-controls="pills-info" aria-selected="false">اطلاعات</a>
				</li>
				<!-- BEGIN: designer_tab -->
				<li class="nav-item  position-relative">
					<a class="nav-link" id="pills-legal-info-tab" data-bs-toggle="pill" href="#tab-legal-info" role="tab" aria-controls="pills-legal-info" aria-selected="true">
						{tab_title}
						<div class="notify badge-alert-dot {show_notify}">
							<span class="heartbit"></span> <span class="point"></span>
						</div>
					</a>
				</li>
				<!-- END: designer_tab -->
				<li class="nav-item">
					<a class="nav-link" id="pills-password-tab" data-bs-toggle="pill" href="#tab-password" role="tab" aria-controls="pills-password" aria-selected="true">تغییر رمز عبور</a>
				</li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
				<div class="tab-pane fade" id="tab-timline" role="tabpanel" aria-labelledby="pills-address-tab">
					<!-- BEGIN: addresses -->
					<div class="card rounded-3 border-bottom m-0">
						<div class="card-body p-3">
							<div class="d-flex align-items-center">
								<span class="flex-shrink-0" style="width: 80px;">
									<img class="card-img-top w-100 img-responsive" src="{HOST}file/global/image/map.png">
								</span>
							</div>
						</div>
					</div>
					<!-- END: addresses -->
				</div>
				<div class="tab-pane  active show" id="tab-info" role="tabpanel" aria-labelledby="pills-info-tab">
					<div class="card-body">
						<form id="form-member-submit" novalidate>
							<div class="row">
								<div class="col-6 col-md-4">
									<div class="mb-3 form-group">
										<label for="inp_name" class="control-label">نام<span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="inp_name" name="name" value="{name}" required data-pristine-required-message="لطفا نام را وارد کنید" />
									</div>
								</div>
								<div class="col-6 col-md-4">
									<div class="mb-3 form-group">
										<label for="inp_family" class="control-label">نام خانوادگی<span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="inp_family" name="family" value="{family}" required data-pristine-required-message="لطفا نام خانوادگی را وارد کنید" />
									</div>
								</div>
								<div class="col-6 col-md-4">
									<div class="mb-3 form-group">
										<label for="inp_mobile" class="control-label">شماره همراه<span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="inp_mobile" name="mobile" value="{mobile}" />
									</div>
								</div>
								<div class="col-6 col-md-4">
									<div class="mb-3 form-group">
										<label for="inp_email" class="control-label">ایمیل </label>
										<input type="email" class="form-control" id="inp_email" name="email" value="{email}" />
									</div>
								</div>
								<div class="col-6 col-md-4">
									<div class="mb-3 form-group">
										<label for="inp_birthdate" class="control-label">تاریخ تولد </label>
										<input type="text" class="form-control" id="inp_birthdate" data-name="dtp1-date" />
										<input type="hidden" name="birthdate" value="{birthdate}">
									</div>
								</div>
								<div class="col-12">
									<div class="mb-3 form-group">
										<label class="control-label">وضعیت(امکان فعالیت در وبسایت)</label>
										<br>
										<input type="checkbox" id="inp_status" name="status" class="form-control material-inputs filled-in chk-col-blue" {status_inp}>
										<label for="inp_status">فعال</label>
									</div>
								</div>
								<div class="col-12 pt-2">
									<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit_member">
										<i class="ti-save"></i>&nbsp;ثبت
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>

				<!-- BEGIN: designer_tab_container -->
				<div class="tab-pane  fade  " id="tab-legal-info" role="tabpanel" aria-labelledby="pills-legal-info-tab">
					<div class="card-body">
						<form id="form-designer-submit" novalidate>
							<div class="row">
								<div class="form-group col-md-4 pt-2">
									<label for="inp_national_code" class="control-label">کد ملی<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_national_code" name="national_code" value="{national_code}" required data-pristine-required-message="لطفا کد ملی   را وارد کنید" />
								</div>
								<div class="d-none form-group col-md-4 pt-2">
									<label for="inp_tel" class="control-label">{LANG_TEL} <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_tel" name="tel" value="1" required data-pristine-required-message="لطفا {LANG_TEL}  را وارد کنید" />
								</div>
								<div class="form-group col-md-4 pt-2">
									<label for="inp_sheba" class="control-label"> شبا<span class="text-danger">*</span></label>
									<input type="number" class="form-control" id="inp_sheba" name="sheba" value="{sheba}" required data-pristine-required-message="لطفا  شبا را وارد کنید" />
								</div>
								<div class="form-group col-md-4 pt-2">
									<label for="inp_card_number" class="control-label"> شماره کارت<span class="text-danger">*</span></label>
									<input type="number" class="form-control" id="inp_card_number" name="card_number" value="{card_number}" required data-pristine-required-message="لطفا  شبا را وارد کنید" />
								</div>
								<div class="form-group col-md-4 pt-2">
									<label for="inp_bank" class="control-label"> نام بانک<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_bank" name="bank" value="{bank}" required data-pristine-required-message="لطفا بانک  را وارد کنید" />
								</div>
								<div class="form-group col-4 pt-2">
									<label for="inp_expertise" class="control-label"> تخصص<span class="text-danger">*</span></label>
									<select name="expertise" class="form-control" id="inp_expertise" disabled>
										{expertise_list}
									</select>
								</div>
								<div class="form-group col-12 pt-2 ">
									<label for="inp_address" class="control-label"> {LANG_ADDRESS}<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_address" name="address" value="{address}" required data-pristine-required-message="لطفا {LANG_ADDRESS}  را وارد کنید" />
								</div>
								<div class="form-group col-6 pt-2">
									<label for="inp_bio" class="control-label">{LANG_ABOUT_ME} <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_bio" name="bio" value="{bio}" required data-pristine-required-message="لطفا {LANG_ABOUT_ME}  را وارد کنید" />
								</div>
								<div class="form-group col-6 pt-2">
									<label for="inp_meta" class="control-label">Meta Desc <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_meta" name="meta" value="{meta}" required data-pristine-required-message="لطفا meta  را وارد کنید" />
								</div>
								<div class="form-group col-6 pt-2">
									<label for="inp_title" class="control-label">Title<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_title" name="title" value="{title}" required data-pristine-required-message="لطفا title  را وارد کنید" />
								</div>
								<div class="form-group col-6 pt-2">
									<label for="inp_slug" class="control-label">slug<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_slug" name="slug" value="{slug}" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
								</div>
								<div class="col-12">
									<div class="d-flex my-4">
										<div class=" me-3 form-group">
											<input type="checkbox" id="inp_as_company" name="as_company" class="form-control material-inputs filled-in chk-col-blue" {as_company}>
											<label for="inp_as_company">فعالیت بعنوان شرکت</label>
										</div>
										<div class=" form-group">
											<input type="checkbox" id="inp_show" name="show" class="form-control material-inputs filled-in chk-col-blue" {show}>
											<label for="inp_show">عدم نمایش در وبسایت</label>
										</div>
									</div>
								</div>
								<div class="col-12">
								</div>
								{downgrade}
								<div class="form-group col-12 pt-2">
									<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit_designer">
										<i class="ti-save"></i>&nbsp;ثبت
									</button>
								</div>
							</div>
						</form>

						<hr>
						<div class="col-12 ">
							<h4 class="font-weight-medium mt-4">فایل های پیوست</h4>
							<div class="d-flex p-2 align-items-center justify-content-start">
								{attachment}
							</div>
						</div>
						<div class="col-12 pt-2 {reject_show}">
							<div class="
												alert alert-danger alert-dismissible
												bg-danger
												text-white
												border-0
												fade
												show

												" role="alert">
								<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
								<strong>رد شد - </strong> {reject_exp}
							</div>
						</div>
						{actions}
					</div>
				</div>
				<!-- END: designer_tab_container -->
				<div class="tab-pane " id="tab-password" role="tabpanel" aria-labelledby="pills-password-tab">
					<div class="card-body">
						<form id="form-password-submit" novalidate method="post">
							<div class="row">
								<div class="col-md-6">
									<div class="mb-3 form-group">
										<label for="inp_new_password" class="control-label">رمز عبور<span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="inp_new_password" data-pristine-pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/" data-pristine-pattern-message="حداقل 2 کاراکتر،شامل حداقل یک حرف و یک عدد" name="new_password" required data-pristine-required-message="لطفا رمز عبور را وارد کنید" autocomplete="off" />
									</div>
								</div>
								<div class="col-md-6">
									<div class="mb-3 form-group">
										<label for="inp_rep-new_password" class="control-label">تکرار رمز عبور<span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="inp_rep-new_password" name="rep_new_password" data-pristine-pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/" data-pristine-pattern-message="حداقل 8 کاراکتر،شامل حداقل یک حرف و یک عدد" required data-pristine-required-message="لطفا تکرار رمز عبور را وارد کنید" autocomplete="off" />
									</div>
								</div>
								<div class="col-12 pt-2">
									<button type="button" class="m-0 btn btn-info d-flex align-items-center justify-content-center" id="submit_password">
										<i class="ti-save"></i>&nbsp;ثبت
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- END: view -->

<!-- BEGIN: add_expertise -->
<div class="row">
	<div class="col-12">
		<div class="card">`
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">افزودن {LANG_BC_EXPERTISE} </h4>
			</div>
			<div class="card-body">
				<form id="form-pages-submit" novalidate method="post">
					<input type="number" class="form-control d-none" id="inp_id" name="id" value="{id}" />
					<div class="row"> 
						<div class="col-6">
							<div class="mb-3 form-group">
								<label for="inp_title" class="control-label">عنوان <span class="text-danger">*</span></label>
								<input type="text" maxlength="100" minlength="3" class="form-control" value="{title}" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان  را وارد کنید" />
							</div>
						</div> 
						<div class="col-12">
							<div class="mb-3 form-group">
								<label for="inp_short_desc" class="control-label">خلاصه توضیحات<span class="text-danger">*</span></label>
								<textarea class="form-control" id="inp_short_desc" maxlength="150" name="short_desc" required>{short_desc}</textarea>
							</div>
						</div> 
						<div class="col-12">
							<div class="mb-3 form-group mt-2" id="form-group_desc">
								<label for="inp_desc" class="control-label">متن کامل <span class="text-danger">*</span></label>
								<textarea id="inp_desc" class="field_short_desc form-control editor">{desc}</textarea>
								<div class="invalid-feedback">
									لطفا متن کامل را وارد کنید
								</div>
							</div>
						</div>
					</div> 
					<h3 class="border-bottom my-3 pb-1">تنظیمات سئو</h3> 
					<div class="row">  
						<div class="col-6">
							<div class=" form-group mb-3 ">
								<label for="inp_seo_title" class="control-label">Seo Title<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_seo_title" name="seo_title" value="{seo_title}" required data-pristine-required-message="لطفا title     را وارد کنید" />
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3 form-group">
								<label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_slug" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" value="{slug}" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3 form-group">
								<label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
								<textarea class="form-control" id="inp_meta" maxlength="150" name="meta" required>{meta}</textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_page" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت {LANG_PAGE}
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: add_expertise -->
<!-- BEGIN: expertise -->
<div class="card table-card" data-title="{LANG_BC_EXPERTISE} ها">
	<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " href="{HOST}admin/member/add_expertise">
		<i class="ti-plus"></i>&nbsp;
		{LANG_BC_EXPERTISE} جدید
	</a>
</div>
<!-- END: expertise -->