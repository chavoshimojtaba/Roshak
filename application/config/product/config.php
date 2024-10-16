<?php

$grid = [
	'container_class' => '  row content-grid ',
	'api' => 'product/list',
	'template' =>[
		' <div class="col-12 col-md-6 mb-2 col-lg-3  product-grid--item ">
		   <div class="position-relative  card rounded-4 bg-white">
			   <figure class="position-relative m-1 p-1">
				   <[a]  target="_blank" class="product-link text-decoration-none" href="[HOST][page]/[slug]" aria-label="دانلود [title]" ></[a]>
				   <img width="150px" height="111px" loading="lazy" src="[HOST][img]" width="[width]" height="[height]" class="img-fit rounded rounded-4" alt="[title]" title="[title]">
				   <div class="figure-hover position-absolute">
					   <div class="d-none position-relative product-action-btns">
						   <button class="btn bg-white ms-1 shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="wishlist" >
							   <i class="icon icon-header-2 fs-4"></i>
						   </button>
						   <button class="btn bg-white shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="share" >
							   <i class="icon icon-shuffle fs-4"></i>
						   </button>
					   </div>
					   <div class="figure-info-product">
						   <[a] href="[HOST][page]/[slug]" class="text-decoration-none " alt="[title]"><h2 class="pt-5 fs-5 fw-bold">[title]</h2></[a]>
						  
					   </div>
				   </div>
			   </figure>
		   </div>
	   </div>',
	   'search'=>'
	   <div class="col-12 col-sm-6 mb-2 product-grid--item col-xl-4 ">
		  <div class="position-relative  card rounded-4 bg-white">
			  <figure class="position-relative m-1 p-1">
				  <[a]  target="_blank" class="product-link text-decoration-none" href="[HOST][page]/[slug]" aria-label="دانلود [title]"></[a]>
				  <img width="150px" height="111px" loading="lazy" src="[HOST][img]" width="[width]" height="[height]" class="img-fit rounded rounded-4" alt="[title]" title="[title]">
				  <div class="figure-hover position-absolute">
					  <div class="d-none position-relative product-action-btns">
						  <button class="btn bg-white ms-1 shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="wishlist" >
							  <i class="icon icon-header-2 fs-4"></i>
						  </button>
						  <button class="btn bg-white shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="share" >
							  <i class="icon icon-shuffle fs-4"></i>
						  </button>
					  </div>
					  <div class="figure-info-product">
						  <[a] href="[HOST][page]/[slug]" class="text-decoration-none " alt="[title]"><h2 class="pt-5 text-truncate fw-bold">[title]</h2></[a]>
						 
					  </div>
				  </div>
			  </figure>
		  </div>
	  </div>',
	]

];
$grid_mobile = array_merge($grid, ['template' =>[
	' <div class="col-12 col-md-6 mb-2 col-lg-3  product-grid--item ">
	   <div class="position-relative  card rounded-4 bg-white">
		   <figure class="position-relative m-1 p-1">
			   <[a]  target="_blank" class="product-link text-decoration-none" href="[HOST][page]/[slug]" aria-label="دانلود [title]"></[a]>
			   <img width="150px" height="111px" loading="lazy" src="[HOST][img]" width="[width]" height="[height]" class="img-fit rounded rounded-4" alt="[title]" title="[title]">
			   <div class="figure-hover  d-flex align-items-end  position-absolute">
				   <div class="d-none position-relative product-action-btns">
					   <button class="btn bg-white ms-1 shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="wishlist" >
						   <i class="icon icon-header-2 fs-4"></i>
					   </button>
					   <button class="btn bg-white shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="share" >
						   <i class="icon icon-shuffle fs-4"></i>
					   </button>
				   </div>
				   <div class="figure-info-product">
				   <h2 class="fs-5 m-0  fw-bold">[title]</h2> 
				   </div>
			   </div>
		   </figure>
	   </div>
   </div>',
   'search'=>'
   <div class="col-12 col-sm-6 mb-2 product-grid--item col-xl-4 ">
	  <div class="position-relative  card rounded-4 bg-white">
		  <figure class="position-relative m-1 p-1">
			  <[a]  target="_blank" class="product-link text-decoration-none" href="[HOST][page]/[slug]"></[a]>
			  <img width="150px" height="111px" loading="lazy" src="[HOST][img]" width="[width]" height="[height]" class="img-fit rounded rounded-4" alt="[title]" title="[title]">
			  <div class="figure-hover d-flex align-items-end  position-absolute">
				  <div class="d-none position-relative product-action-btns">
					  <button class="btn bg-white ms-1 shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="wishlist" >
						  <i class="icon icon-header-2 fs-4"></i>
					  </button>
					  <button class="btn bg-white shadow-custom rounded-3 p-2 product-action-btn" data-id="[id]" data-action="share" >
						  <i class="icon icon-shuffle fs-4"></i>
					  </button>
				  </div>
				  <div class="figure-info-product">
				  	<h2 class="fs-5 m-0  fw-bold">[title]</h2> 
					 
				  </div>
			  </div>
		  </figure>
	  </div>
  </div>',
]]);
