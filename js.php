<?php 
	$siteurl = get_option('siteurl'); 
	$url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__));
?>
<script type='text/javascript' src='<?php echo $url; ?>/slides/js/slides.min.jquery.js'></script>
<script type='text/javascript' src='<?php echo $url; ?>/colorbox/jquery.colorbox-min.js'></script>

<script type="text/javascript">
	var $ = jQuery.noConflict();
	
	$(document).ready(function(){
		$(".colorbox").colorbox({
			rel: 'colorbox',
			maxHeight: '90%',
			maxWidth: '90%'
		});
		
		$('.sliderly-slideshow').each(function(){
				if (!$(this).hasClass("set"))
				{
					$('.sliderly-slideshow').slides({
						preload: true,
						preloadImage: '<?php echo $url; ?>/slides/img/loading.gif',
						play: 2500,
						pause: 1000,
						hoverPause: true
					});
					$(".sliderly-slideshow").addClass("set");
				}
			});
	});
</script>