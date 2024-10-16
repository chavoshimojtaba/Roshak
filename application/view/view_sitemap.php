<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- BEGIN: main -->
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" {image}> 
	<!-- BEGIN: item -->
	<url>
		<loc>{HOST}{url}</loc>
		<!-- BEGIN: image -->
		<image:image>
		  <image:loc>{image}</image:loc> 
		</image:image>
		<!-- END: image --> 
	  <lastmod>{update}</lastmod>
	  <changefreq>monthly</changefreq>
	  <priority>1</priority>
	</url>
	<!-- END: item --> 
</urlset>
<!-- END: main -->