<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
 
<!-- BEGIN: loc --> 
<div class="col-12 col-md-12">
    <div class="card">
        <div class="border-bottom title-part-padding">
            <h4 class="card-title mb-0">مدیریت {LANG_BC_LOC}  </h4>
        </div>
        <div class="card-body p-2 p-md-4">
            <div class="treeview js-treeview">
                <ul>
                    <li id="treeview">
                        <div class="treeview__level" data-level="*">
                            <span class="level-title">Root (4 سطحی)</span>
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
                    افزودن/ویرایش
                </h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-treeview-submit" novalidate>
                    <div class="row"> 
                        <div class=" col-md-4 col-xl-3">
                            <div class="mb-3 form-group">
                                <label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
                                <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                                <input type="number" class="form-control d-none" id="inp_pid" value="0" name="pid" />
                                <input type="hidden" class="form-control " id="inp_path" value="" name="path" />
                            </div>
                        </div>
                        <div class=" col-md-8 col-xl-7 d-none tags-container" >
                            <div class="mb-3 form-group">
                                <label for="inp_tags" class="control-label">محله های مرتبط  <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_tags" name="tags"   data-pristine-required-message="لطفا تگ ها را وارد کنید" /> 
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
                        <div class="col-12"><h3 class="border-bottom my-3 pb-1">تنظیمات سئو</h3></div>  
                        <div class=" col-6 col-md-4 col-xl-3">
                            <div class="mb-3 form-group">
                                <label for="inp_seo_title" class="control-label">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_seo_title" value="{seo_title}" name="seo_title" required data-pristine-required-message="لطفا Title را   وارد کنید" />
                            </div>
                        </div>
                        <div class=" col-6 col-md-4 col-xl-3">
                            <input type="hidden" />
                            <div class="mb-3 form-group">
                                <label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_slug" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
                            </div>
                        </div>
                        <div class="col-12 col-xl-6">
                            <div class="mb-3 form-group">
                                <label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="inp_meta" rows="1"  name="meta" required></textarea>
                            </div>
                        </div> 
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class=" btn btn-light-danger text-danger font-weight-medium " data-bs-dismiss="modal">
                    انصراف
                </button>
                <button type="button" class="btn btn-success" id="submit_treeview">ثبت</button>
            </div>
        </div>
    </div>
</div>
<!-- END: loc --> 



<!-- BEGIN: category -->

<div class="col-12 col-md-12">
    <div class="card">
        <div class="border-bottom title-part-padding">
            <h4 class="card-title mb-0">مدیریت دسته بندی {LANG_PRODUCTS} </h4>
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
                                <label for="inp_title" class="control-label">{LANG_TITLE} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_title" name="title" required data-pristine-required-message="لطفا عنوان را وارد کنید" />
                                <input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
                                <input type="number" class="form-control d-none" id="inp_pid" value="0" name="pid" />
                                <input type="hidden" class="form-control " id="inp_path" value="" name="path" />
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
                        <div class="col-12"><h3 class="border-bottom my-3 pb-1">تنظیمات سئو</h3></div> 
                        <div class=" col-6 col-md-4 col-xl-3">
                            <div class="mb-3 form-group">
                                <label for="inp_seo_title" class="control-label">Title<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_seo_title" value="{seo_title}" name="seo_title" required data-pristine-required-message="لطفا Title را   وارد کنید" />
                            </div>
                        </div>
                        <div class=" col-6 col-md-4 col-xl-3">
                            <input type="hidden" />
                            <div class="mb-3 form-group">
                                <label for="inp_slug" class="control-label">{LANG_SLUG}<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="inp_slug" name="slug" required data-pristine-required-message="لطفا slug را   وارد کنید" minlength="1" maxlength="150" data-pristine-pattern="/^([a-z])([a-z]|[-]){1,150}$/" />
                            </div>
                        </div>
                        <div class="col-12 col-xl-6">
                            <div class="mb-3 form-group">
                                <label for="inp_meta" class="control-label">Meta(Desc)<span class="text-danger">*</span></label>
                                <textarea class="form-control" id="inp_meta" rows="1"  name="meta" required></textarea>
                            </div>
                        </div>
                        <div class="col-12"><h3 class="border-bottom my-3 pb-1">چند رسانه ای</h3></div>

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

 