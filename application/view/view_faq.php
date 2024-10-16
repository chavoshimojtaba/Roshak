<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: index -->
	<div class="faq container-md faq_page_bg mt-3">
		{breadcrump} 
		<div class="p-5 m-5">
			<div class="faq-header mb-5 pb-3">
				<h1 class="text-center">سوالات متداول از
					<span class="text-primary">طــــــــرح پیـــــــــچ</span>
					<div class="bg-right"></div>
				</h1>
				<p class="text-center text-muted my-3 fs-5">
					{sub_title}			
				</p>
			</div>
			<div class="accordion position-relative" id="accordionExample">
				<div class="bg-circle"></div>
				<!-- BEGIN: faq -->
				<div class="accordion-item">
					<div class="accordion-header" id="heading{id}">
						<button class="accordion-button collapsed py-4 text-dark fs-5" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{id}" aria-expanded="false" aria-controls="collapse{id}">
							{title}
						</button>
					</div>
					<div id="collapse{id}" class="accordion-collapse collapse" aria-labelledby="heading{id}" data-bs-parent="#accordionExample">
						<div class="accordion-body text-muted">
							<p>
							{desc}
							</p>
						</div>
					</div>
				</div>
				<!-- END: faq -->
			</div>
		</div>
	</div>
<!-- END: index -->