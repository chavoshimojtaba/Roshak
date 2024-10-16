<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<input type="hidden" id="filetype" value="{type}">
<input type="hidden" id="is_editor" value="{editor}">
<input type="hidden" id="is_cat" value="{cat}">
<div class="card position-relative">
	<div class="border-bottom title-part-padding d-flex align-items-center justify-content-between">
		<h4 class="card-title mb-0">بارگذاری فایل جدید</h4>
		<div class="d-flex align-items-center" id="accept-formats" data-formats="{formats}">
			<!-- BEGIN: ext -->
			<span class="badge bg-light-{class} text-{class} fs-2 me-1 d-flex align-items-center" dir="ltr">
				{ext}
			</span>
			<!-- END: ext -->
		</div>
	</div>
	<div class="card-body">
		<div id="actions" class="row dz-container">
			<div class="col-12 d-flex flex-column ">
				<div class="form-group mb-4 w-50">
					<label class="mr-sm-2" for="category_type">دسته بندی</label>
					<select class="form-select mt-2" id="category_type">
					</select>
				</div>
				<div class="d-flex">
					<span class="btn ms-0 btn-success fileinput-button">
						<i class="mdi mdi-database-plus"></i>
						افزودن فایل...
					</span>
					<button type="submit" class="btn btn-info d-none start">
						<i class="ti-upload"></i>
						<span>شروع بارگذاری</span>
					</button>
					<button type="reset" class="btn btn-danger d-none cancel">
						<i class="ti-close"></i>
						<span>انصراف</span>
					</button>
				</div>
			</div>
		</div>
		<div id="card-preview" class="upload-center mt-3">
			<div class="files" id="previews">
				<div class="card file-row my-2 shadow-sm  posistion-relative" id="template">
					<div class="upload-done  d-none">
						<i class="ti-check-box text-success"></i>
					</div>
					<div class="card-body p-2 d-flex justify-content-between align-items-center ">
						<div class="d-flex align-items-center file-info text-truncate ">
							<img data-dz-thumbnail class="rounded-circle" width="40" />
							<div class="d-flex flex-column  ps-3">
								<h6 data-dz-name class="text-truncate file-name"></h6>
								<div class="d-flex align-items-center">
									<strong class="error text-danger" data-dz-errormessage></strong>
									<span data-dz-size class=" mb-1 badge font-weight-medium bg-light-info text-info
								"></span>
									<span class=" mb-1 ms-1 dz-type badge font-weight-medium bg-light-primary text-primary
								"></span>
								</div>
							</div>
						</div>
						<div class="d-flex flex-column px-4">
							<div class="form-group mb-1">
								<input type="text" class="form-control file_title" placeholder="title">
							</div>
							<div class="form-group ">
								<input
									type="text"
									class="form-control file_thumbnail ltr"
									placeholder="Thumbnail Size (50*50)"
									min="50" max="500" step="10"
									oninput="validity.valid||(value='');">
							</div>
                      	</div>
						<div class="d-flex flex-column px-4">
							<div class="form-group mb-1">
								<input type="text" class="form-control file_name" placeholder="File Name">
							</div>
							<div class="form-group mb-1">
								<input type="text" class="form-control file_alt" placeholder="alt">
							</div>
                      	</div>
						<div class="d-flex flex-column px-4">
							<div class="form-check form-check-inline">
								<input class=" form-check-input success check-outline outline-success watermark-inp " type="checkbox" id="watermark-inp" value="watermark">
								<label class="form-check-label" for="watermark-inp">وارترمارک</label>
							</div>
                      	</div>
						<div class="d-flex align-items-center flex-column action-buttons">
							<button class="btn btn-light-info text-info start mb-1">
								<i class="ti-upload"></i>
							</button>
							<button data-dz-remove class="btn btn-light-danger text-danger cancel">
								<i class="ti-close"></i>
							</button>
							<button data-dz-remove class="btn btn-light-danger text-danger delete">
								<i class="far fa-trash-alt"></i>
							</button>
						</div>
					</div>
					<div class="dropzone-item-progress-container">
						<div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
							<div class="progress-bar progress-bar-striped bg-success" style="width:0%;" data-dz-uploadprogress></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="card table-card">
	<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
		<div class="d-flex align-items-center justify-content-center">
			<form id="q-form" action="#">
				<div class="input-group">
					<div class="position-relative">
						<input type="text" class="form-control" placeholder="جستجو..." id="table_q">
						<span class="q-form__clear-btn">
							<i class="mdi text-danger mdi-close-circle-outline d-flex"></i>
						</span>
					</div>
					<button type="submit" class="btn btn-primary d-flex align-items-center" type="button" id="table_q_btn">
						<i class="ti-search"></i>
					</button>
				</div>
			</form>
		</div>
		<div class="w-25">
			<select class="form-select d-none" id="filter_filetype">
				<option value="all" selected>
					نوع فایل
					(همه)
				</option>
				<option value="image">
				تصویر
				</option>
				<option value="doc">
				مستندات
				</option>
				<option value="video">
				ویدئو
				</option>
			</select>
		</div>
		<div class="w-25">
			<select class="form-select" id="filter_category_type">
			</select>
		</div>
	</div>
	<div class="card-body">
		<!-- Add Contact Popup Model -->
		<div class="table-responsive" id="table-blogs">
		</div>
	</div>
</div>
<section id="card-table" class="card-table">

</section>

<div class="modal fade" id="moreModal" tabindex="-1" aria-labelledby="moreModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="moreModalLabel">
					اطلاعات فایل
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-0">
				<div class="card m-0">
					<div class="file_info" data-id="file">
					</div>
					<ul class="list-group list-group-flush">
						<li class="list-group-item d-flex align-items-center justify-content-between">
							<span>نام</span>
							<h6 class="file_info" data-id="name"></h6>
						</li>
						<li class="list-group-item d-flex align-items-center justify-content-between">
							<span>دسته بندی</span>
							<h6 class="file_info" data-id="category_title"></h6>
						</li>
						<li class="list-group-item d-flex align-items-center justify-content-between">
							<span>فرمت</span>
							<h6 class="file_info" data-id="ext"></h6>
						</li>
						<li class="list-group-item d-flex align-items-center justify-content-between">
							<span>حجم</span>
							<h6 class="file_info" data-id="size"></h6>
						</li>
						<li class="list-group-item d-flex align-items-center justify-content-between">
							<span>تاریخ بارگذاری</span>
							<h6 class="file_info" data-id="createAt"></h6>
						</li>
					</ul>
					<hr class=" my-0"/>
					<div class="p-3 pt-1">
						<form id="form-update-submit" novalidate>
							<input type="hidden" />
							<div class="form-group ">
								<label for="inp_title" class="control-label">Title</label>
								<input type="text" class="form-control inp_title" id="inp_update_title" name="title" placeholder="title">
								<input type="hidden"  id="inp_update_id" name="id" >
							</div>
							<div class="form-group mt-2">
								<label for="inp_update_alt" class="control-label">Alt</label>
								<input type="text" class="form-control file_update_alt" id="inp_update_alt" placeholder="update_alt" name="alt">
							</div>
							<div class="form-group mt-2">
								<button type="button" class="btn m-0 btn-success" id="submit_update">ثبت
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- END: index -->
<!-- BEGIN: category -->
<div class="card">
	<div class="border-bottom title-part-padding">
		<h4 class="card-title mb-0">مدیریت دسته بندی فایل ها</h4>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-12 col-md-12">
				<div class="treeview js-treeview">
					<ul>
						<li id="treeview">
							<div class="treeview__level" data-level="*">
								<span class="level-title">Root (1 سطحی)</span>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
    <div class="modal-dialog" role="document">
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
                        <input type="text" class="form-control" data-pristine-pattern="/^([a-z])([a-z]|[_]){1,150}$/" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
                        <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                        <input type="number" class="form-control d-none" id="inp_pid" value="0" name="pid" />
                    </div>
                    <div class="mb-3 form-group">
                        <label for="inp_alias" class="control-label">شناسه انگلیسی <span class="text-danger">*</span></label>
                        <input type="text" required class="form-control" id="inp_alias"  data-pristine-pattern="/^([a-z])([a-z]|[_]){1,150}$/" name="alias" />
                    </div>
                    <div class="mb-3 form-group">

                        <label for="inp_thumbnail_size" class="control-label">Thumbnail  Size<span class="text-danger">*</span></label>
                        <input type="text" required class="form-control" id="inp_thumbnail_size" name="thumbnail_size" />
                    </div>
                    <div class="mb-3 form-group">
                        <label for="inp_original_size" class="control-label">Original Size<span class="text-danger">*</span></label>
                        <input type="text" required class="form-control" id="inp_original_size" name="original_size" />
                    </div>
                    <div class="mb-3 form-group">
                        <input type="checkbox" id="inp_publish" name="publish" class="material-inputs filled-in chk-col-red" checked>
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