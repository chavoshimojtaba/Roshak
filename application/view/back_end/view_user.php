<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
	<div class="row">
		<div class="col-12">
			<div class="card table-card" data-title="{LANG_USERS}">
			<a type="button" class="btn ms-3 btn-light-info text-info d-flex align-items-center "   data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="ti-plus"></i>&nbsp;
                            {LANG_USER} جدید
                        </a>
			</div>
		</div>
	</div>
	<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header d-flex align-items-center">
					<h4 class="modal-title" id="editModalLabel">
						افزودن/ویرایش {LANG_USER}
					</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="form-user-submit" novalidate method="post">
						<input type="number" class="form-control d-none" id="inp_id" value="0" name="id" />
						<div class="row">
							<div class="col-4">
								<div class="mb-3 form-group">
									<label for="inp_role">نقش<span class="text-danger">*</span></label>

									<select class="form-control" id="inp_rid" name="rid" required data-pristine-required-message="لطفا نقش بندی    را انتخاب کنید">
										<option value="">انتخاب کنید</option>
										<!-- BEGIN: roles -->
										<option value="{id}">{name}</option>
										<!-- END: roles -->
									</select>
								</div>
							</div>
							<div class="col-4">
								<div class="mb-3 form-group">
									<label for="inp_name" class="control-label">نام<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_name" name="name" required data-pristine-required-message="لطفا نام را وارد کنید" />
								</div>
							</div>
							<div class="col-4">
								<div class="mb-3 form-group">
									<label for="inp_family" class="control-label">نام خانوادگی<span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_family" name="family" required data-pristine-required-message="لطفا نام خانوادگی را وارد کنید" />
								</div>
							</div>
							<div class="col-4">
								<div class="mb-3 form-group">
									<label for="inp_mobile" class="control-label">شماره همراه<span class="text-danger">*</span></label>
									<input type="number" class="form-control" id="inp_mobile" name="mobile" required data-pristine-required-message="لطفا شماره همراه را وارد کنید" />
								</div>
							</div>
							<div class="col-4">
								<div class="mb-3 form-group">
									<label for="inp_expertise" class="control-label">تخصص <span class="text-danger">*</span></label>
									<input type="text" class="form-control" id="inp_expertise" name="expertise" required data-pristine-required-message="لطفا تخصص   را وارد کنید" />
								</div>
							</div>
							<div class="col-4">
								<div class="mb-3 form-group">
									<label for="inp_mail" class="control-label">ایمیل<span class="text-danger">*</span></label>
									<input type="email" class="form-control" id="inp_email" name="email" required data-pristine-required-message="لطفا ایمیل را وارد کنید" />
								</div>
							</div>
							<div class="col-12">
								<div class="mb-3 form-group">
									<label for="inp_address" class="control-label">آدرس<span class="text-danger">*</span></label>
									<textarea required data-pristine-required-message="لطفا آدرس  را وارد کنید" id="inp_address" name="address" rows="2" class="form-control "></textarea>
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3 form-group">
									<label class="control-label">وضعیت<span class="text-danger">*</span></label>
									<br>
									<input type="checkbox" id="inp_status" name="status" class="form-control material-inputs filled-in chk-col-blue" checked="">
									<label for="inp_status">فعال</label>
								</div>
							</div>
							<div class="col-3 d-none">
								<div class="mb-3 form-group">
									<label class="control-label">نمایش در سایت<span class="text-danger">*</span></label>
									<br>
									<input type="checkbox"  id="inp_as_team_member" name="as_team_member" class="form-control material-inputs filled-in chk-col-blue" >
									<label for="inp_as_team_member">نمایش</label>
								</div>
							</div>
							<div class="col-12">
								<div class="mb-1 form-group mt-1">
									<div class="upload-file pic-file">
                                    </div>
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
					<button type="button" class="btn btn-success" id="submit_user">ثبت
					</button>
				</div>
			</div>
		</div>
	</div>
<!-- END: index -->

<!-- BEGIN: profile -->
<div class="row">
	<div class="col-lg-4 col-xlg-3 col-md-5">
		<div class="card">
			<div class="card-body">
				<center class="mt-4">
					<img src="{HOST}{pic}" class="rounded-circle" width="150" height="150" id="user_pic">
					<h4 class="card-title mt-2" id="user_full_name">{full_name}</h4>
					<h6 class="card-subtitle">{email}</h6>
					<div class="row text-center justify-content-center">
						<div class="col-12">
							<span class="badge bg-light-primary text-primary">{role}</span>
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
				<small class="text-muted pt-4 db">آدرس</small>
				<h6 id="user_address">{address}</h6>
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-xlg-9 col-md-7">
		<div class="card">
			<ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
				<li class="nav-item d-none">
					<a class="nav-link " id="pills-timeline-tab" data-bs-toggle="pill" href="#tab-timline" role="tab" aria-controls="pills-timeline" aria-selected="false">Timeline</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" id="pills-info-tab" data-bs-toggle="pill" href="#tab-info" role="tab" aria-controls="pills-info" aria-selected="false">ویرایش اطلاعات</a>
				</li>
				<li class="nav-item">
					<a class="nav-link " id="pills-password-tab" data-bs-toggle="pill" href="#tab-password" role="tab" aria-controls="pills-password" aria-selected="true">تغییر رمز عبور</a>
				</li>
			</ul>
			<div class="tab-content" id="pills-tabContent">
				<div class="tab-pane fade " id="tab-timline" role="tabpanel" aria-labelledby="pills-timeline-tab">
					<div class="card-body">
						<div class="profiletimeline mt-0">
							<div class="sl-item d-flex align-items-start">
								<div class="sl-left">
									<img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle">
								</div>
								<div class="sl-right">
									<div>
										<a href="javascript:void(0)" class="link">John Doe</a>
										<span class="sl-date">5 minutes ago</span>
										<p>
											assign a new task
											<a href="javascript:void(0)">
												Design weblayout</a>
										</p>
										<div class="row">
											<div class="col-lg-3 col-md-6 mb-3">
												<img src="../../assets/images/big/img1.jpg" class="img-fluid rounded">
											</div>
											<div class="col-lg-3 col-md-6 mb-3">
												<img src="../../assets/images/big/img2.jpg" class="img-fluid rounded">
											</div>
											<div class="col-lg-3 col-md-6 mb-3">
												<img src="../../assets/images/big/img3.jpg" class="img-fluid rounded">
											</div>
											<div class="col-lg-3 col-md-6 mb-3">
												<img src="../../assets/images/big/img4.jpg" class="img-fluid rounded">
											</div>
										</div>
										<div class="like-comm">
											<a href="javascript:void(0)" class="link me-2">2 comment</a>
											<a href="javascript:void(0)" class="link me-2"><i class="fa fa-heart text-danger"></i> 5
												Love</a>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="sl-item d-flex align-items-start">
								<div class="sl-left">
									<img src="../../assets/images/users/2.jpg" alt="user" class="rounded-circle">
								</div>
								<div class="sl-right">
									<div>
										<a href="javascript:void(0)" class="link">John Doe</a>
										<span class="sl-date">5 minutes ago</span>
										<div class="mt-3 row">
											<div class="col-md-3 col-xs-12">
												<img src="../../assets/images/big/img1.jpg" alt="user" class="img-fluid rounded">
											</div>
											<div class="col-md-9 col-xs-12">
												<p>
													Lorem ipsum dolor sit amet, consectetur
													adipiscing elit. Integer nec odio. Praesent
													libero. Sed cursus ante dapibus diam.
												</p>
												<a href="javascript:void(0)" class="btn btn-success">
													Design weblayout</a>
											</div>
										</div>
										<div class="like-comm mt-3">
											<a href="javascript:void(0)" class="link me-2">2 comment</a>
											<a href="javascript:void(0)" class="link me-2"><i class="fa fa-heart text-danger"></i> 5
												Love</a>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<div class="sl-item d-flex align-items-start">
								<div class="sl-left">
									<img src="../../assets/images/users/3.jpg" alt="user" class="rounded-circle">
								</div>
								<div class="sl-right">
									<div>
										<a href="javascript:void(0)" class="link">John Doe</a>
										<span class="sl-date">5 minutes ago</span>
										<p class="mt-2">
											Lorem ipsum dolor sit amet, consectetur
											adipiscing elit. Integer nec odio. Praesent
											libero. Sed cursus ante dapibus diam. Sed nisi.
											Nulla quis sem at nibh elementum imperdiet. Duis
											sagittis ipsum. Praesent mauris. Fusce nec
											tellus sed augue semper
										</p>
									</div>
									<div class="like-comm mt-3">
										<a href="javascript:void(0)" class="link me-2">2 comment</a>
										<a href="javascript:void(0)" class="link me-2"><i class="fa fa-heart text-danger"></i> 5
											Love</a>
									</div>
								</div>
							</div>
							<hr>
							<div class="sl-item d-flex align-items-start">
								<div class="sl-left">
									<img src="../../assets/images/users/4.jpg" alt="user" class="rounded-circle">
								</div>
								<div class="sl-right">
									<div>
										<a href="javascript:void(0)" class="link">John Doe</a>
										<span class="sl-date">5 minutes ago</span>
										<blockquote class="mt-2">
											Lorem ipsum dolor sit amet, consectetur
											adipisicing elit, sed do eiusmod tempor
											incididunt
										</blockquote>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane fade " id="tab-password" role="tabpanel" aria-labelledby="pills-password-tab">
					<div class="card-body">
					<form id="form-password-submit" novalidate method="post">
							<div class="row">
								<div class="col-12">
									<div class="mb-3 form-group">
										<label for="inp_cur_password" class="control-label">رمز عبور فعلی<span class="text-danger">*</span></label>
										<input type="password" class="form-control" id="inp_cur_password" name="cur_password" required data-pristine-required-message="لطفا رمز عبور فعلی را وارد کنید" autocomplete="off" />
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label for="inp_new_password" class="control-label">رمز عبور<span class="text-danger">*</span></label>
										<input
											type="password"
											class="form-control"
											id="inp_new_password"
											data-pristine-pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/" data-pristine-pattern-message="حداقل 2 کاراکتر،شامل حداقل یک حرف و یک عدد"
											name="new_password"
											required data-pristine-required-message="لطفا رمز عبور را وارد کنید"
											autocomplete="off"/>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label for="inp_rep-new_password" class="control-label">تکرار رمز عبور<span class="text-danger">*</span></label>
										<input
											type="password" class="form-control"
											id="inp_rep-new_password"
											name="rep_new_password"
											data-pristine-pattern="/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{2,}$/" data-pristine-pattern-message="حداقل 8 کاراکتر،شامل حداقل یک حرف و یک عدد"
											required data-pristine-required-message="لطفا تکرار رمز عبور را وارد کنید"
											autocomplete="off"/>
									</div>
								</div>
								<div class="col-12 pt-2">
									<button type="button" class="btn btn-info d-flex align-items-center justify-content-center" id="submit_password">
										<i class="ti-save"></i>&nbsp;ثبت
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="tab-pane fade  active show" id="tab-info" role="tabpanel" aria-labelledby="pills-info-tab">
					<div class="card-body">
						<form id="form-user-submit" novalidate method="post">
							<div class="row">
								<div class="col-12">
									<div class="mb-3 form-group">
										<label for="inp_role">نقش<span class="text-danger">*</span></label>

										<select class="form-control" disabled  >
											<!-- BEGIN: roles -->
											<option value="{id}" {selected}>{name}</option>
											<!-- END: roles -->
										</select>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label for="inp_name" class="control-label">نام<span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="inp_name" name="name" value="{name}" required data-pristine-required-message="لطفا نام را وارد کنید" />
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label for="inp_family" class="control-label">نام خانوادگی<span class="text-danger">*</span></label>
										<input type="text" class="form-control" id="inp_family" name="family" value="{family}" required data-pristine-required-message="لطفا نام خانوادگی را وارد کنید" />
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label for="inp_mobile" class="control-label">شماره همراه<span class="text-danger">*</span></label>
										<input type="number" class="form-control" id="inp_mobile" name="mobile" value="{mobile}" disabled readonly />
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label for="inp_email" class="control-label">ایمیل<span class="text-danger">*</span></label>
										<input type="email" class="form-control" id="inp_email" name="email" value="{email}" disabled readonly />
									</div>
								</div>
								<div class="col-12">
									<div class="mb-3 form-group">
										<label for="inp_address" class="control-label">آدرس<span class="text-danger">*</span></label>
										<textarea required data-pristine-required-message="لطفا آدرس  را وارد کنید" id="inp_address" name="address" rows="2" class="form-control ">{address}</textarea>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label class="control-label">وضعیت<span class="text-danger">*</span></label>
										<br>
										<input type="checkbox" disabled id="inp_status" name="status" class="form-control material-inputs filled-in chk-col-blue" checked="">
										<label for="inp_status">فعال</label>
									</div>
								</div>
								<div class="col-6">
									<div class="mb-3 form-group">
										<label class="control-label">نمایش در سایت<span class="text-danger">*</span></label>
										<br>
										<input type="checkbox"  id="inp_as_team_member" name="as_team_member" class="form-control material-inputs filled-in chk-col-blue" checked="">
										<label for="inp_as_team_member">فعال</label>
									</div>
								</div>
								<div class="col-12">
									<div class="mb-1 form-group mt-1">
										<div class="upload-file ">
											<h4>بارگذاری تصویر</h4>
											<i class="fas fa-cloud-upload-alt  mb-2"></i>
											<span class="text-info  mb-2 d-block">
												فرمت های مجاز :
												<span class="upload-file__formats ">
													.webp , .jpeg , .jpg , .png , .gif
												</span>
											</span>
											<div class="upload-file__items">
											</div>
											<button type="button" class="add_file btn btn-light-info text-info  d-inline-flex align-items-center justify-content-center">
												<i class="ti-plus"></i>&nbsp;
												افزودن تصویر
											</button>
										</div>
									</div>
								</div>
								<div class="col-12 pt-2">
									<button type="button" class="btn btn-info d-flex align-items-center justify-content-center" id="submit_user">
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
<!-- END: profile -->

<!-- BEGIN: log_in_out -->
<div class="row">
		<div class="col-12">
			<div class="card table-card">
				<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center	">
					<h4 class="card-title mb-0">گزارش ورود کاربران</h4>
				</div>
				<div class="card-body">
					<div class="table-responsive" id="table-users">
					</div>
				</div>
			</div>
		</div>
	</div>
<!-- END: log_in_out -->