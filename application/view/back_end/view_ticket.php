<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="row statistics-boxes">
	<!-- Column -->
	<div class="col-md-6 col-lg-4 col-xlg-3">
		<div class="card  border-bottom border-primary">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-primary display-6 d-flex">
							<i class="ti-layout-list-thumb-alt"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{statistics_total}</h2>
						<h6 class="text-primary mb-0">تعداد کل تیکت ها</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-4 col-xlg-3">
		<div class="card  border-bottom border-success">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-success display-6 d-flex">
							<i class="mdi mdi-comment-processing"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{statistics_open}</h2>
						<h6 class="text-success mb-0">تیکت های باز</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-4 col-xlg-3">
		<div class="card  border-bottom border-danger">
			<div class="card-body">
				<div class="d-flex no-block align-items-center">
					<div>
						<span class="text-danger display-6 d-flex">
							<i class="mdi mdi-comment-check"></i>
						</span>
					</div>
					<div class="ms-4">
						<h2>{statistics_closed}</h2>
						<h6 class="text-danger mb-0">تیکت های بسته شده</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Column -->
</div>
<div class="row">
	<div class="col-12">
		<div class="card table-card" data-title="{LANG_TICKETS}">
		</div>
	</div>
</div>
<!-- END: index -->
<!-- BEGIN: view -->
<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-body">
				<h4 class="card-title" id="el_title"></h4>
				<p id="el_comment">
				</p>
			</div>
		</div>
		<div class="card m-0">
			<div class="border-bottom title-part-padding justify-content-between d-flex align-items-center 	">
				<h4 class="card-title mb-0"><i class="fas fa-caret-down"></i> پاسخ ها </h4>
				<span class="d-none action-btn">
					<button type="button" class="btn btn-light-info text-in	fo d-flex align-items-center " data-bs-toggle="modal" id="reply-btn" data-bs-target="#replyModal">
						<i class="fas fa-reply"></i>&nbsp;
						پاسخ
					</button>
				</span>
			</div>
		</div>
		<ul class="list-unstyled reply-list" id="reply-list">
		</ul>
	</div>
	<div class="col-lg-4">
		<div class="card">
			<h5 class="card-title mb-2 border-bottom p-2 d-flex align-items-center justify-content-start lh-1"><i class="fs-3 fas fa-info-circle"></i>&nbsp; اطلاعات تیکت</h5>
			<div class="card-body pt-0 pb-2 bg-extra-light">
				<div class="d-flex align-items-center justify-content-between mb-1">
					<small class="text-muted">تاریخ ثبت</small>
					<h6 class="mt-2 ltr" id="el_createAt"></h6>
				</div>
				<div class="d-flex align-items-center justify-content-between mb-1">
					<small class="text-muted">وضعیت</small>
					<h6 class="mt-2" id="el_status"><span class="badge bg-warning">{category}</span></h6>
				</div>
				<div class="d-flex align-items-center justify-content-between mb-1">
					<small class="text-muted">ارجاع شده به</small>
					<h6 class="mt-2" id="el_role"><span class="badge bg-primary"></span></h6>
				</div>
				<div class="d-flex align-items-center justify-content-between mb-0">
					<small class="text-muted">اولویت</small>
					<h6 class="mt-2" id="el_priority"></h6>
				</div>
				<div class="d-flex align-items-center flex-column border-top pt-2 mt-2 d-none action-btn">
					<a type="button" class="d-flex ms-0 mb-2 d-none  align-items-center justify-content-center btn w-100 mx-0   waves-effect waves-light btn-primary" id="refer-ticket" data-bs-toggle="modal" id="reply-btn" data-bs-target="#referModal">
						<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve" width="16px" fill="#FFFFFF" style="margin-left: 6px;">
							<g>
								<g transform="translate(0.000000,511.000000) scale(0.100000,-0.100000)">
									<path d="M2745.6,4633.8c-655.5-125.2-1175.1-594.2-1377.6-1241.7c-79.9-258.5-85.3-724.8-10.7-972.6c170.5-567.5,607.5-1025.9,1161.8-1215c183.9-61.3,634.2-98.6,807.4-66.6l90.6,16l16,207.8c58.6,754.1,559.6,1534.8,1169.8,1825.2c181.2,85.3,181.2,85.3,165.2,178.5c-8,50.6-63.9,194.5-122.6,319.8c-146.5,303.8-471.6,634.2-767.4,778.1C3513,4641.8,3105.3,4703.1,2745.6,4633.8z" />
									<path d="M5676.6,3272.3c-850-191.9-1502.8-815.4-1721.3-1652c-79.9-301.1-79.9-794,0-1092.5c215.8-815.4,834-1430.9,1641.4-1638.7c426.3-109.2,1031.2-61.3,1430.9,114.6c612.9,271.8,1089.8,818,1276.3,1462.9c79.9,279.8,98.6,743.4,40,1036.5c-215.8,1063.2-1135.1,1814.6-2211.6,1806.6C5969.8,3309.6,5764.6,3290.9,5676.6,3272.3z" />
									<path d="M1418.7,1249.8C1061.6,1015.4,749.9,690.3,526,317.2C384.8,82.8,230.3-295.6,161-588.7C62.4-988.4,30.4-951.1,486.1-951.1h394.4l538.2,538.3l540.9,540.9l423.7-423.7l423.7-423.7l223.8,223.8c189.2,191.8,556.9,474.3,660.8,508.9c24,8,10.7,109.2-53.3,378.4l-87.9,365.1l-175.9-21.3c-642.2-79.9-1175.1,85.3-1644,506.3l-135.9,122.6L1418.7,1249.8z" />
									<path d="M1056.3-1358.8l-756.7-724.8l421-13.3l423.7-13.3l71.9-282.4c245.1-964.6,887.3-1614.7,1915.8-1937.1c138.6-42.6,295.8-85.3,346.4-95.9l93.3-16l-79.9,58.6c-181.2,133.2-453,421-586.2,620.8c-255.8,383.7-397,839.3-431.7,1385.6l-16,279.8l445,5.3l445,8l-759.4,727.4c-418.3,397-762.1,724.8-767.4,724.8C1815.7-631.3,1472-959.1,1056.3-1358.8z" />
									<path d="M4024.6-1025.7c-133.2-85.3-317.1-218.5-410.3-295.8l-167.9-143.9L3902-1921c250.5-250.5,455.6-466.3,455.6-477c0-10.7-322.4-18.7-714.1-18.7h-716.8l24-165.2c29.3-239.8,143.9-591.5,253.1-794c191.8-354.4,381-546.2,897.9-916.6l210.5-149.2h2792.5H9900v221.2c-2.7,1398.9-551.6,2467.4-1630.7,3181.5l-258.5,170.5l-213.2-183.9c-341.1-295.8-692.8-479.6-1111.1-583.6c-285.1-69.3-866-61.3-1156.4,16c-415.7,111.9-852.7,357-1119.1,628.8c-63.9,66.6-125.2,119.9-133.2,119.9S4155.2-940.4,4024.6-1025.7z" />
								</g>
							</g>
						</svg>
						ارجاع تیکت
					</a>
					<a type="button" class="d-flex d-none align-items-center justify-content-center btn  w-100 mx-0  waves-effect waves-light btn-danger" id="close-ticket">
						<i class="ti-check-box me-2"></i>
						بستن تیکت
					</a>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="card-body text-center p-2">
				<h4 class="card-title mt-2">اطلاعات کاربر</h4>
				<div class="profile-pic mb-0 mt-3">
					<img src="" id="el_pic" width="150" height="150" class="rounded-circle" alt="user" />
					<h4 class="mt-3 mb-0" id="el_fullname">{user_name}</h4>
					<a href="mailto:{user_email}" class="pt-2 text-muted d-block" id="el_email">{user_email}</a>
				</div>
			</div>
			<div class="p-2 border-top  ">
				<div class="row text-center">
					<div class="col-12">
						<a id="el_href" class="
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
			<div class="modal-header d-flex align-items-center border-bottom">
				<h4 class="modal-title" id="replyModalLabel">
					ثبت پاسخ
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-ticket-submit" novalidate>
					<input type="hidden" />
					<div class="form-group">
						<label for="inp_desc" class="control-label">پاسخ<span class="text-danger">*</span></label>
						<textarea rows="6" class="form-control" id="inp_reply" name="text" required data-pristine-required-message="لطفا پاسخ را وارد کنید"></textarea>
						<input type="hidden" id="inp_tid" value="0">
					</div>

					<div class="mb-1 form-group mt-3">
						<div class="upload-file pics-file"> 
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">
					انصراف
				</button>
				<button type="button" class="btn btn-success" id="submit_reply">
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="referModal" tabindex="-1" aria-labelledby="referModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header d-flex align-items-center border-bottom">
				<h4 class="modal-title" id="referModalLabel">
					ارجاع تیکت
				</h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="form-refer-submit" novalidate>
					<input type="hidden" />
					<div class="form-group ">
						<label for="inp_role">بخش(نقش)</label>
						<select class="form-control mt-2" id="inp_role" name="role" required data-pristine-required-message="لطفا نقش را انتخاب کنید">
							<option value="">انتخاب کنید</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-danger text-danger font-weight-medium" data-bs-dismiss="modal">
					انصراف
				</button>
				<button type="button" class="btn btn-success" id="submit_refer">
					ثبت
				</button>
			</div>
		</div>
	</div>
</div>
<!-- END: view -->