<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_ROLES}"> 
		<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center "   data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="ti-plus"></i>&nbsp;
                            {LANG_ROLE} جدید
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
				<form id="form-role-submit" novalidate>
					<input type="hidden" />
					<div class="mb-3 form-group">
						<label for="inp_name" class="control-label">عنوان نقش <span class="text-danger">*</span></label>
						<input type="text" class="form-control" id="inp_name" name="name" required data-pristine-required-message="لطفا عنوان نقش را وارد کنید" />
						<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
					</div> 
					<div class="mb-3 form-group">
						<label for="inp_desc" class="control-label">توضیحات <span class="text-danger">*</span></label>
						<textarea class="form-control" id="inp_desc" name="desc" required data-pristine-required-message="لطفا توضیحات را وارد کنید"></textarea>
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
				<button type="button" class="btn btn-success" id="submit_role">ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="permModal" tabindex="-1" aria-labelledby="permModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center">
				<h4 class="modal-title" id="permModalLabel">
					مدیریت مجوز ها
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-0">
				<div class="table-responsive" id="table-roles"> 
					<form id="form-permissions-submit" novalidate>

						<table class="table table-sm mb-0 v-middle">
							<thead class="table-light">
								<tr>
									<th class="border-bottom border-top">بخش</th> 
									<!-- <th class="border-bottom border-top">مشاهده</th>
									<th class="border-bottom border-top">ایجاد</th>
									<th class="border-bottom border-top">ویرایش</th>
									<th class="border-bottom border-top">حذف</th> -->
								</tr>
							</thead>
							<tbody>
								<!-- BEGIN: resource -->
								<tr>
									<td class="w-100"> 
										<input type="checkbox" id="inp_perm_{id}_2" value="2" name="{id}" class="material-inputs resource_inp filled-in chk-col-red">
										<label for="inp_perm_{id}_2">{title_fa}</label> 
									</td>
									<td class="d-none">
										<input type="checkbox" id="inp_perm_{id}_1" value="1" name="{id}" class="material-inputs resource_inp filled-in chk-col-red">
										<label for="inp_perm_{id}_1"></label>
									</td> 
									<td class="d-none">
										<input type="checkbox" id="inp_perm_{id}_3" value="3" name="{id}" class="material-inputs resource_inp filled-in chk-col-red">
										<label for="inp_perm_{id}_3"></label>
									</td> 
									<td class="d-none">
										<input type="checkbox" id="inp_perm_{id}_4" value="4" name="{id}" class="material-inputs resource_inp filled-in chk-col-red">
										<label for="inp_perm_{id}_4"></label>
									</td> 
								</tr> 
								<!-- END: resource -->
							</tbody>
						</table> 
						<input type="number" class="d-none" id="inp_role_id" name="role_id" /> 
					</form>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="
                              btn btn-light-danger
                              text-danger
                              font-weight-medium
                            " data-bs-dismiss="modal">
					انصراف
				</button>
				<button type="button" class="btn btn-success" id="submit_permissons">ثبت
				</button>
			</div>
		</div>
	</div>
</div>

<!-- END: index -->