<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: calendar -->
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="">
				<div class="row">
					<div class="col-lg-12">
						<div class="card-body b-l calender-sidebar">
							<div id="calendar"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal none-border" id="my-event">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><strong>ایجاد | ویرایش آیتم</strong></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">بستن</button>
				<button type="button" class="btn btn-success save-event waves-effect waves-light">ثبت آیتم جدید</button>
				<button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-bs-dismiss="modal">حذف آیتم</button>
			</div>
		</div>
	</div>
</div>
<!-- END: calendar -->
<!-- BEGIN: add_header_links -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">افزودن لینک</h4>
			</div>
			<div class="card-body">
				<form id="form-header_links-submit" novalidate method="post">
					<div class="row">
						<div class="col-md-6">
							<div class="mb-3 form-group">
								<label for="inp_title" class="control-label">عنوان<span class="text-danger">*</span></label>
								<input type="number" class="form-control d-none" id="inp_id" name="id" value="{id}" />
								<input type="text" class="form-control" id="inp_title" name="title" value="{title}" required data-pristine-required-message="لطفا  Sub Title را وارد کنید" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="mb-3 form-group">
								<label for="inp_url" class="control-label"> لینک<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_url" name="url" value="{url}" required data-pristine-required-message="لطفا  لینک را وارد کنید" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_header_links" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: add_header_links -->
<!-- BEGIN: site -->
<div class="row">
	<div class="col-12 col-lg-12">
		<div class="card">
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">تنظیمات عمومی</h4>
			</div>
			<div class="card-body">
				<form id="form-seo-submit" novalidate method="post">
					<div class="row">
						<div class="col-md-6 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_system_name" class="control-label">عنوان <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_system_name" name="system_name" value="{system_name}" required data-pristine-required-message="لطفا عنوان  را وارد کنید" />
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_owner" class="control-label">صاحب امتیاز <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_owner" name="owner" value="{owner}" required data-pristine-required-message="لطفا صاحب امتیاز  را وارد کنید" />
							</div>
						</div>
						<div class="col-md-6 col-lg-4 d-none">
							<div class="mb-3 form-group ">
								<label for="inp_copy_right" class="control-label">کپی رایت<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_copy_right" name="copy_right" value="{copy_right}" required data-pristine-required-message="لطفا کپی رایت را وارد کنید" />
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_email" class="control-label">ایمیل <span class="text-danger">*</span></label>
								<input type="email" class="form-control" id="inp_email" name="email" value="{email}" required data-pristine-required-message="لطفا ایمیل را وارد کنید" />
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_tel" class="control-label">تلفن <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_tel" name="tel" value="{tel}" required data-pristine-required-message="لطفا تلفن را وارد کنید" />
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_mobile" class="control-label">همراه پشتیبانی <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_mobile" name="mobile" value="{mobile}" required data-pristine-required-message="لطفا همراه را وارد کنید" />
							</div>
						</div>
						<div class="col-12 col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_keywords" class="control-label">کلمات کلیدی <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_keywords" name="keywords" value="{keywords}" required data-pristine-required-message="لطفا کلمات کلیدی را وارد کنید" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 form-group">
								<label for="inp_footer_desc" class="control-label">متن فوتر<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_footer_desc" name="footer_desc" value="{footer_desc}" required data-pristine-required-message="لطفا متن فوتر را وارد کنید" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 form-group">
								<label for="inp_address" class="control-label">آدرس <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_address" name="address" value="{address}" required data-pristine-required-message="لطفا آدرس را وارد کنید" />
							</div>
						</div>
						<div class="col-12 d-none">
							<div class=" form-group ">
								<label for="inp_exp" class="control-label">توضیحات<span class="text-danger">*</span></label>
								<textarea id="inp_exp" class="form-control" name="exp" required data-pristine-required-message="لطفا توضیحات را وارد کنید">{exp}</textarea>
							</div>
						</div>
						<div class="col-12">
							<h3 class="border-bottom my-3 pb-1">محتوای صفحه نخست</h3>
						</div>
						<div class="col-12 mt-3 col-lg-6">
							<div class=" form-group ">
								<label for="inp_h1_home" class="control-label">عنوان صفحه نخست<span class="text-danger">*</span></label>
								<textarea id="inp_h1_home" class="form-control" name="h1_home" required data-pristine-required-message="لطفا عنوان صفحه نخست  را وارد کنید">
									{h1_home}
								</textarea>
							</div>
						</div>
						<div class="col-12 mt-3 col-lg-6">
							<div class=" form-group ">
								<label for="inp_sub_title_home" class="control-label"> زیر عنوان صفحه نخست<span class="text-danger">*</span></label>
								<textarea id="inp_sub_title_home" class="form-control" name="sub_title_home" required data-pristine-required-message="لطفا  زیر عنوان را وارد کنید">{sub_title_home}</textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mt-3 form-group " id="form-group_desc">
								<label for="inp_home_desc" class="control-label">متن فوتر صفحه نخست <span class="text-danger">*</span></label>
								<textarea id="inp_home_desc" class="field_home_desc form-control editor">{home_desc}</textarea>
								<div class="invalid-feedback">
									لطفا توضیحات را وارد کنید
								</div>
							</div>
						</div>
						<div class="col-12">
							<h3 class="border-bottom mt-4 mb-3 pb-1">تنظیمات سئو صفحه نخست</h3>
						</div>
						<div class="col-12 col-md-4 mt-2">
							<div class=" form-group ">
								<label for="inp_seo_title_home" class="control-label">Home Title<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_seo_title_home" name="seo_title_home" value="{seo_title_home}" required data-pristine-required-message="لطفا title صفحه نخست را وارد کنید" />
							</div>
						</div>
						<div class="col-12 mt-3">
							<div class=" form-group ">
								<label for="inp_seo_meta_home" class="control-label">Home Meta<span class="text-danger">*</span></label>
								<textarea id="inp_seo_meta_home" class="form-control" name="seo_meta_home" required data-pristine-required-message="لطفا Meta را وارد کنید">{seo_meta_home}</textarea>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_seo" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت
				</button>
			</div>
		</div>
	</div>
	<div class="col-12 col-lg-12">
		<div class="card">
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">شبکه های اجتماعی</h4>
			</div>
			<div class="card-body">
				<form id="form-social-submit" novalidate method="post">
					<style>
						#form-social-submit label {
							float: left;
						}
					</style>
					<div class="row ltr"> 
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_instagram" class="control-label">Instagram </label>
								<input type="text" class="form-control" id="inp_social_instagram" name="social_instagram" value="{social_instagram}" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_youtube" class="control-label">Youtube </label>
								<input type="text" class="form-control" id="inp_social_youtube" name="social_youtube" value="{social_youtube}" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_telegram" class="control-label">Telegram </label>
								<input type="text" class="form-control" id="inp_social_telegram" name="social_telegram" value="{social_telegram}" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_whatsapp" class="control-label">Whatsapp </label>
								<input type="text" class="form-control" id="inp_social_whatsapp" name="social_whatsapp" value="{social_whatsapp}" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_linkedin" class="control-label">Linkedin </label>
								<input type="text" class="form-control" id="inp_social_linkedin" name="social_linkedin" value="{social_linkedin}" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_pinterest" class="control-label">Pinterest </label>
								<input type="text" class="form-control" id="inp_social_pinterest" name="social_pinterest" value="{social_pinterest}" />
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3 w-100 form-group">
								<label for="inp_social_aparat" class="control-label">Aparat </label>
								<input type="text" class="form-control" id="inp_social_aparat" name="social_aparat" value="{social_aparat}" />
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_social" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>

<!-- END: site -->
<!-- BEGIN: user_stories -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="حکایت ها">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " href="{HOST}admin/setting/add_user_stories">
				<i class="ti-plus"></i>&nbsp;
				حکایت جدید
			</a>
		</div>
	</div>
</div>
<!-- END: user_stories -->
<!-- BEGIN: add_user_stories -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">افزودن حکایت</h4>
			</div>
			<div class="card-body">
				<form id="form-user_stories-submit" novalidate method="post">
					<div class="row">
						<div class="col-6">
							<div class="mb-3 form-group">
								<label for="inp_fullname" class="control-label">نام <span class="text-danger">*</span></label>
								<input type="number" class="form-control d-none" id="inp_id" name="id" value="{id}" />
								<input type="text" class="form-control" id="inp_fullname" name="fullname" value="{fullname}" required data-pristine-required-message="لطفا نام  را وارد کنید" />
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3 form-group">
								<label for="inp_sub_title" class="control-label"> Sub Title<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_sub_title" name="sub_title" value="{sub_title}" required data-pristine-required-message="لطفا  Sub Title را وارد کنید" />
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3 form-group">
								<label for="inp_url" class="control-label"> لینک<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_url" name="url" value="{url}" required data-pristine-required-message="لطفا  لینک را وارد کنید" />
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3 form-group">
								<label for="inp_text" class="control-label"> متن<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_text" name="text" value="{text}" required data-pristine-required-message="لطفا  متن را وارد کنید" />
							</div>
						</div>
						<div class="col-3">
							<div class="upload-file pic-file" data-formats="{formats}">
								<!-- BEGIN: image -->
								<input type="hidden" value="{pic}" id="1">
								<!-- END: image -->
							</div>
						</div>
					</div>

				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_user_stories" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: add_user_stories -->

<!-- BEGIN: header_links -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_BACK_LINK} ها">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " href="{HOST}admin/setting/add_header_links">
				<i class="ti-plus"></i>&nbsp;
				لینک جدید
			</a>
		</div>
	</div>
</div>
<!-- END: header_links -->

<!-- BEGIN: public_links -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_BACK_LINK} ها">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " href="{HOST}admin/setting/add_public_links">
				<i class="ti-plus"></i>&nbsp;
				{LANG_BACK_LINK} جدید
			</a>
		</div>
	</div>
</div>
<!-- END: public_links -->
<!-- BEGIN: add_public_links -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">افزودن لینک</h4>
			</div>
			<div class="card-body">
				<form id="form-public_links-submit" novalidate method="post">
					<div class="row">
						<div class="col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_type">نوع<span class="text-danger">*</span></label>
								<select class="form-control" id="inp_type" name="type" required data-pristine-required-message="لطفا نوع    را انتخاب کنید">
									<option value="">انتخاب کنید</option>
									<!-- BEGIN: options -->
									<option value="{id}" {selected}>{title}</option>
									<!-- END: options -->
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_title" class="control-label">عنوان<span class="text-danger">*</span></label>
								<input type="number" class="form-control d-none" id="inp_id" name="id" value="{id}" />
								<input type="text" class="form-control" id="inp_title" name="title" value="{title}" required data-pristine-required-message="لطفا  Sub Title را وارد کنید" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_url" class="control-label"> لینک<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_url" name="url" value="{url}" required data-pristine-required-message="لطفا  لینک را وارد کنید" />
							</div>
						</div>
					</div>

				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_public_links" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: add_public_links -->
<!-- BEGIN: footer_links -->
<div class="card  p-3">
	<form id="form-column-submit" novalidate method="post">
		<div class="row align-items-center">
			<!-- BEGIN: column_form -->
			<div class="col-lg-2">
				<div class="mb-3 form-group">
					<label for="inp_column_title" class="control-label">عنوان<span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="inp_column_title" name="{id}" value="{title}" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
				</div>
			</div>
			<!-- END: column_form -->
			<div class="col-2">
				<button type="button" class="m-0 btn btn-success" id="submit_column">ثبت </button>
			</div>
		</div>
	</form>
</div>
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="لینک ها">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " data-bs-toggle="modal" data-bs-target="#editModal">
				<i class="ti-plus"></i>&nbsp;
				لینک جدید
			</a>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="editModalLabel">
					افزودن/ویرایش {LANG_BC_GENERAL_LINK}
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-footer_link-submit" novalidate method="post">
					<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
					<div class="row">
						<div class="col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_pid">ستون<span class="text-danger">*</span></label>
								<select class="form-control" id="inp_pid" name="pid" required data-pristine-required-message="لطفا ستون    را انتخاب کنید">
									<option value="">انتخاب کنید</option>
									<!-- BEGIN: columns -->
									<option value="{id}">{title}</option>
									<!-- END: columns -->
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_title" class="control-label">عنوان<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
							</div>
						</div>
						<div class="col-md-4">
							<div class="mb-3 form-group">
								<label for="inp_url" class="control-label">لینک<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_url" name="url" required data-pristine-required-message="لطفا لینک را وارد کنید" />
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
				<button type="button" class="btn btn-success" id="submit_footer_links">ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: footer_links -->