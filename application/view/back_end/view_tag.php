<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->

<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_TAG} ">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center "href="{HOST}admin/tag/add">
				<i class="ti-plus"></i>&nbsp;
				{LANG_TAG} جدید
			</a>
		</div>
	</div>
</div>
<!-- END: index -->

<!-- BEGIN: add -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="border-bottom title-part-padding">
				<h4 class="card-title mb-0">افزودن / ویرایش {LANG_TAG}</h4>
			</div>
			<div class="card-body">
				<form id="form-tag-submit" novalidate method="post">
					<div class="row">
						<div class="col-12 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_title" name="title" value="{title}" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
								<input type="number" class="form-control d-none" id="inp_id" value="{id}" name="id" />
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3 form-group">
								<label for="inp_short_desc" class="control-label">خلاصه توضیحات<span class="text-danger">*</span></label>
								<textarea class="form-control" id="inp_short_desc"  maxlength="300"  name="short_desc" required>{short_desc}</textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3 form-group mt-2" id="form-group_desc">
								<label for="inp_desc" class="control-label"> {LANG_DESCRIPTION} <span class="text-danger">*</span></label>
								<textarea id="inp_desc" class="field_desc form-control editor">{desc}</textarea>
								<div class="invalid-feedback">
									لطفا  {LANG_DESCRIPTION} را وارد کنید
								</div>
							</div>
						</div>

                        <div class="col-12"><h3 class="border-bottom my-3 pb-1">تنظیمات سئو</h3></div>
						<div class="col-12 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_seo_title" class="control-label">Title<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_seo_title" value="{seo_title}" name="seo_title" required data-pristine-required-message="لطفا Title را   وارد کنید"   />
							</div>
						</div>

						<div class="col-12 col-lg-4">
							<div class="mb-3 form-group">
								<label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_slug" value="{slug}" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/"/>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3 form-group">
								<label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
								<textarea class="form-control" id="inp_meta"  maxlength="300"  name="meta" required>{meta}</textarea>
							</div>
						</div>
						
					</div>

				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_tag" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: add -->