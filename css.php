<?php

	$siteurl = get_option('siteurl');
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__));
	echo "<link rel='stylesheet' type='text/css' href='$url/colorbox/colorbox.css' />\n";
	
?>

<style type='text/css'>
	.sliderly-widget {
		margin-top: 5px !important;
	}
	
	.widget_sliderlywidget {
		clear: both;
	}
	
	.sliderly-gallery {
		width: 100%;
	}
	
	.sliderly-gallery .caption {
		display: none;
	}
	
	.sliderly-gallery .slide {
		width: 47%;
		float: left;
	}
	
	.sliderly-gallery .slide:nth-child(odd) {
		padding: 3% 3% 3% 0%;
	}
	
	.sliderly-gallery .slide:nth-child(even) {
		padding: 3% 0% 3% 3%;
	}
	
	.sliderly-gallery img {
		width: 100%;
		cursor: pointer;
	}
	
	.sliderly_wrapper {
		position: relative;
		width: 100%;
	}
	
	.sliderly_nav {
		list-style: none;
		clear: both;
		text-align: center;
		width: 100%;
		margin: 20px 0 0 -40px;
	}
	
	.sliderly_nav li {
		display: inline;
	}
	
	.sliderly_nav a {
		display: inline-block;
		padding: 5px;
	}
	
	.slide {
		position: relative;
	}
	
	.caption {
		position: absolute;
		width: 100%;
		margin-bottom: -1px;
		background: url(<?php echo get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/black_85.png'; ?>);
		padding: 15px;
	}
	
	.sliderly-slideshow {
		position: relative;
	}
	
	.sliderly-slideshow.controls-right, .sliderly-slideshow.controls-left {
		margin-bottom: -4px;
	}
	
	.sliderly-slideshow.controls-right .pagination {
		position: absolute;
		right: 3px;
		margin-top: -33px;
		z-index: 999999;
	}
	
	.sliderly-slideshow.controls-left .pagination {
		position: absolute;
		left: -33px;
		margin-top: -33px;
		z-index: 999999;
	}
	
	.sliderly-slideshow.controls-centre .pagination {
		margin: 8px 0 10px -40px;
		width: 100%;
		text-align: center;
		list-style: none;
		z-index: 99999;
	}

	.pagination li {
		display: inline;
		margin: 0 3px;
		z-index: 99999;
	}

	.pagination li a {
		display: inline-block;
		width: 20px;
		height: 0px;
		padding-top: 20px;
		background-position: 0 0;
		overflow: hidden;
		z-index: 99999;
		background-image: url(<?php echo get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)); ?>/slides/img/pagination.png);
	}

	.pagination li.current a {
		background-position: 0 -21px;
	}
	
	.caption h2 {
		color: #fff;
		font-size: 1.4em;
	}
	
	.caption p {
		font-size: 1em;
		font-style: oblique;
		color: #999;
	}
	
	.slide a {
		text-decoration: none !important;
	}
</style>