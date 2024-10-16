<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->

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
                            <span class="level-title">Root</span>
                        </div>
                    </li>
                </ul>
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
                        <label for="inp_title_fa" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inp_title_fa" name="title_fa" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
                        <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                        <input type="number" class="form-control d-none" id="inp_pid" value="0" name="pid" />
                    </div>
                    <div class="mb-3 form-group">
                        <label for="inp_title_en" class="control-label">{LANG_TITLE} {LANG_EN}<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inp_title_en" name="title_en" required data-pristine-required-message="لطفا عنوان را انگلیسی وارد کنید" />
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
                <button type="button" class="btn btn-success" id="submit_tag">ثبت
                </button>
            </div>
        </div>
    </div>
</div>
<!-- END: index -->

<!-- BEGIN: category -->
<div class="col-12 col-md-12">
    <div class="card">
        <div class="border-bottom title-part-padding">
            <h4 class="card-title mb-0">مدیریت دسته بندی {LANG_BLOGS} </h4>
        </div>
        <div class="card-body">
            <div class="treeview js-treeview">
                <ul>
                    <li id="treeview">
                        <div class="treeview__level" data-level="*">
                            <span class="level-title">Root (n سطحی)</span>
							<div class="treeview__level-btns" >
                                <div class="btn btn-primary btn-sm level-expand"><span class="ti-"></span></div>
                                <div class="btn btn-success btn-sm level-root"><span class="ti-plus"></span></div>
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
						<input type="text" class="form-control" id="inp_slug"  name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/"  />
					</div>
					<div class="mb-3 form-group">
						<label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
						<textarea class="form-control" id="inp_meta"  maxlength="150"  name="meta" required> </textarea>
					</div>
					<div class="mb-3 form-group mt-2" id="form-group_desc">
						<label for="inp_desc" class="control-label"> {LANG_DESCRIPTION} <span class="text-danger">*</span></label>
						<textarea id="inp_desc" class="field_desc form-control editor"> </textarea>
						<div class="invalid-feedback">
							لطفا  {LANG_DESCRIPTION} را وارد کنید
						</div>
					</div>
                    <div class="mb-3 form-group">
                        <input type="checkbox" id="inp_publish" name="publish" class="material-inputs filled-in chk-col-red"  >
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