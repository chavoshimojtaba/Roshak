<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_BLOG} ها">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center " href="{HOST}admin/blog/add">
				<i class="ti-plus"></i>&nbsp;
				{LANG_BLOG} جدید
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
				<h4 class="card-title mb-0">افزودن | ویرایش {LANG_BLOG}</h4>
			</div>
			<div class="card-body">
				<form id="form-blog-submit" novalidate method="post">
					<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
					<div class="row">
						<div class="col-12">
							<div class="mb-3 form-group ">
								<label for="inp_title" class="control-label">عنوان <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان  را وارد کنید" />
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3 form-group ">
								<label for="inp_url" class="control-label">لینک <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_url" name="url" required data-pristine-required-message="لطفا لینک  را وارد کنید" />
							</div>
						</div>
						<div class="col-4">
							<div class="mb-3 form-group">
								<label for="inp_reading_duration" class="control-label">زمان مطالعه <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_reading_duration" name="reading_duration" required data-pristine-required-message="لطفا زمان مطالعه  را وارد کنید" />
							</div>
						</div>
						<div class="col-4">
							<div class="mb-3 form-group">
								<label for="inp_blog_date" class="control-label">تاریخ <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_blog_date" name="blog_date" required data-pristine-required-message="لطفا تاریخ  را وارد کنید" />
							</div>
						</div>
						<div class="col-4">
							<div class="mb-3 form-group">
								<label for="inp_author" class="control-label">نویسنده <span class="text-danger">*</span></label>
								<input type="text" class="form-control" id="inp_author" name="author" required data-pristine-required-message="لطفا نویسنده  را وارد کنید" />
							</div>
						</div>
						<div class="col-6">
							<div class="upload-file pic-file" data-formats="{formats}">
								<!-- BEGIN: image -->
								<input type="hidden" value="{pic}" id="1">
								<!-- END: image -->
							</div>
						</div>
						<div class="col-6">
							<div class="upload-file avatar-file" data-formats="{formats}">
								<!-- BEGIN: image -->
								<input type="hidden" value="{pic}" id="1">
								<!-- END: image -->
							</div>
						</div>
					</div>
					<div class="mb-3 form-group d-none">
						<label>دسته بندی <span class="text-danger">*</span></label>
						<div class="select-menu" value="{cid}" id="category-option">
						</div>
					</div>
					<div class="mb-3 form-group d-none">
						<label for="inp_short_desc" class="control-label">خلاصه مطلب<span class="text-danger">*</span></label>
						<textarea id="inp_short_desc" name="short_desc" rows="5" class="form-control " data-pristine-required-message="لطفا خلاصه مطلب  را وارد کنید"></textarea>

					</div>
					<div class="mb-3 form-group d-none">
						<label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_slug" value="{slug}" name="slug" data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
					</div>
					<div class="mb-3 form-group d-none">
						<label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
						<textarea class="form-control" id="inp_meta" maxlength="150" name="meta"  >{meta}</textarea>
					</div>
					<div class="mb-3 form-group mt-2 d-none" id="form-group_desc">
						<label for="inp_desc" class="control-label">متن کامل <span class="text-danger">*</span></label>
						<textarea id="inp_desc" class="field_short_desc form-control editor"></textarea>
						<div class="invalid-feedback">
							لطفا متن کامل را وارد کنید
						</div>
					</div>
					<div class="mb-3 form-group d-none multi-select">
					</div>

				</form>
			</div>
			<div class="card-body border-top">
				<button type="button" id="submit_blog" class="  btn btn-info  d-inline-flex align-items-center justify-content-center">
					<i class=" ti-save"></i>&nbsp;
					ثبت {LANG_BLOG}
				</button>
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
<!-- END: add -->
<!-- BEGIN: view -->
<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title">{title}</h4>
				<p>
					{short_desc}
				</p>
				<img src="{HOST}{img}" width="100%">
				<div class="my-3 text-muted">
					{desc}
				</div>
			</div>
		</div>
		<div class="card">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
				<h4 class="card-title mb-0"><i class="fas fa-caret-down"></i> {LANG_COMMENTS} (میانگین امتیاز :
					<span class="text-primary">
						{rate}
					</span>
					از
					<span class="text-primary">{cnt}
					</span>
					دیدگاه منتشر شده)
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
					<small class="text-muted">تاریخ ثبت</small>
					<h6 class="mt-2 ltr">{createAt}</h6>
				</div>
				<div class="d-flex align-items-center justify-content-between mb-0">
					<small class="text-muted">دسته بندی</small>
					<h6 class="mt-2"><span class="badge bg-warning">{category}</span></h6>
				</div>
				<div class="d-flex align-items-start justify-content-center flex-column mb-0">
					<small class="text-muted">تگ ها</small>
					<div class="d-flex align-items-start justify-content-center">
						<!-- BEGIN: tags -->
						<h6 class="mt-2 me-1"><span class="badge bg-primary">{title}</span></h6>
						<!-- END: tags -->
					</div>
				</div>
				<div class="d-flex align-items-center flex-column border-top pt-2 mt-2">
					<a href="{BASE_URL}blog/add/{id}" type="button" class="d-flex align-items-center justify-content-center btn mb-2 w-100 mx-0 waves-effect waves-light btn-primary">
						<i class="ti-pencil me-2"></i>
						ویرایش مقاله
					</a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body text-center  p-2">
				<h4 class="card-title">نویسنده</h4>
				<div class="profile-pic mb-0 mt-3">
					<img src="{HOST}{user_pic}" width="150" height="150" class="rounded-circle" alt="user" />
					<h4 class="mt-3 mb-0">{user_name}</h4>
					<a href="mailto:{user_email}">{user_email}</a>
				</div>
			</div>
			<div class="p-2 border-top  ">
				<div class="row text-center">
					<div class="col-12">
						<a href="{BASE_URL}user/view/{uid}" class="
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
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
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
<!-- BEGIN: category -->
<div class="col-12 col-md-12">
	<div class="card">
		<div class="border-bottom title-part-padding">
			<h4 class="card-title mb-0">مدیریت دسته بندی {LANG_BLOGS}</h4>
		</div>
		<div class="card-body">
			<div class="treeview js-treeview">
				<ul>
					<li id="treeview">
						<div class="treeview__level" data-level="*">
							<span class="level-title">Root (n سطحی)</span>
							<div class="treeview__level-btns">
								<div class="btn btn-primary btn-sm level-expand"><span class="ti-"></span></div>

								<div class="btn btn-success btn-sm add-first d-none"><span class="ti-plus"></span></div>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
	<div class="modal-dialog modal-fullscreen" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="editModalLabel">
					افزودن/ویرایش {LANG_CATEGORY}
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-treeview-submit" novalidate>
					<input type="hidden" />
					<div class="mb-3 form-group">
						<label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
						<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
						<input type="number" class="form-control d-none" id="inp_pid" value="0" name="pid" />
					</div>
					<div class="mb-3 form-group">
						<label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_slug" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^(a-z])(a-z]|[-]){1,150}$/" />
					</div>
					<div class="mb-3 form-group">
						<label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
						<textarea class="form-control" id="inp_meta" maxlength="150" name="meta" required> </textarea>
					</div>
					<div class="mb-3 form-group mt-2" id="form-group_desc">
						<label for="inp_desc" class="control-label"> {LANG_DESCRIPTION} <span class="text-danger">*</span></label>
						<textarea id="inp_desc" class="field_desc form-control editor"> </textarea>
						<div class="invalid-feedback">
							لطفا {LANG_DESCRIPTION} را وارد کنید
						</div>
					</div>
					<div class="mb-3 form-group">
						<input type="checkbox" id="inp_publish" name="publish" class="material-inputs filled-in chk-col-red">
						<label for="inp_publish">عدم نمایش</label>
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