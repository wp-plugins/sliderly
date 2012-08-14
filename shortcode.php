<?php

function sliderly_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'id' => '0',
		'type' => 'gallery',
		'width' => '950',
		'height' => '380',
		'colorbox' => 'false',
		'controls' => 'centre',
		'effect' => 'fade',
		'duration' => '2500',
		'grid' => '3'
	), $atts ) );
	
	$html = "";
	$content = get_post($id);
	$slideshow = $content->post_content;
	$slideshow_title = $content->post_title;
	$slideshow_exploded = explode("SL1D3RLYS3C43T", $slideshow);
	
	include("output.php");
	
	return $output;
	
}

add_shortcode( 'sliderly', 'sliderly_shortcode' );

?>