<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
<div class="about container-md">
	{breadcrump}
	<!-- prettier-ignore -->
	<div class="  mt-5">
		<div class="about-header text-center mb-5 pb-3">
			<h1>
				<span class="text-primary">طــــــــرح پیـــــــــچ</span>
				در یک نگاه
				<div class="bg-right"></div>
				<div class="bg-bottom"></div>
			</h1>
			<p class="text-center text-muted my-3 fs-6 px-5 mx-5">{desc}</p>
			<div class="mt-4 d-flex justify-content-center socials-widget">
				<div class="col-auto mt-0 ms-3 p-0">
					<a href="{social_pinterest}" rel="nofollow" class="facebook rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich instagram">
						<i class="icon icon-dribbble fs-3 p-2 d-block"></i>
					</a>
				</div>
				<div class="col-auto mt-0 ms-3 p-0">
					<a href="{social_whatsapp}" rel="nofollow" class="facebook rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich instagram">
						<i class="icon icon-whatsapp-bold fs-3 p-2 d-block"></i>
					</a>
				</div>
				<div class="col-auto ms-3 p-0">
					<a href="{social_youtube}" rel="nofollow" class="youtube rounded-3 text-white d-block text-center" target="_blank" aria-label="youtube">
						<i class="icon icon-video-square5 fs-3 p-2 d-block"></i>
					</a>
				</div>
				<div class="col-auto mt-0 ms-3 p-0">
					<a href="{social_instagram}" rel="nofollow" class="facebook rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich instagram">
						<i class="icon icon-instagram-bold fs-3 p-2 d-block"></i>
					</a>
				</div>
				<div class="col-auto mt-0 ms-2 p-0">
					<a href="{social_telegram}" rel="nofollow" class="telegram rounded-3 text-white d-block text-center" target="_blank" aria-label="tarhpich telegram">
						<i class="icon icon-send-2 fs-3 p-2 d-block"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="about-content position-relative">
			<div class="row">
				<!-- BEGIN: user -->
				<div class="col-sm-12 col-md-6 mb-3 col-lg-3">
					<div class="position-relative card rounded-4 bg-white item ml-3">
						<figure class="m-1 p-1">
							<img width="150px" height="150px" loading="lazy" data-src="{pic}" class="img-fit rounded rounded-4" src="{pic}" alt="{full_name}" title="{full_name}" />
						</figure>
						<div class="card-info text-center p-3">
							<div class="text-decoration-none mt-1"  >
								<h2 class="fs-6 text-secondary bold">{full_name}</h2>
								<span class="fs-6 text-primary">{expert}</span>
							</div>
							<div class="d-flex justify-content-center mt-3">
								<a target="_blank" href="{social_1}" class="btn btn-social">
									<i class="icon icon-twitter fs-5"></i>
								</a>
								<a target="_blank" href="{social_2}" class="btn btn-social">
									<i class="icon icon-instagram fs-5"></i>
								</a>
								<a target="_blank" href="{social_3}" class="btn btn-social">
									<i class="icon icon-linkedin fs-5"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
				<!-- END: user -->
			</div>
		</div>
	</div>
</div>
<!-- END: index -->