<?php

class SliderlyWidget extends WP_Widget {

	function SliderlyWidget() {
		// Instantiate the parent object
		parent::__construct( false, 'Sliderly' );
	}

	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		$id = $instance['slideshow_id'];
		$type = $instance['type'];
		$width = $instance['width'];
		$height = $instance['height'];
		$colorbox = $instance['colorbox'];
		$controls = $instance['controls'];
		$grid = $instance['grid'];
		$effect = $instance['effect'];
		$duration = $instance['duration'];
		$html = "";
		$content = get_post($id);
		$slideshow = $content->post_content;
		$slideshow_title = $content->post_title;
		$slideshow_exploded = explode("SL1D3RLYS3C43T", $slideshow);

		include("output.php");

		echo $output . $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['type'] = strip_tags($new_instance['type']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['height'] = strip_tags($new_instance['height']);
		$instance['colorbox'] = strip_tags($new_instance['colorbox']);
		$instance['controls'] = strip_tags($new_instance['controls']);
		$instance['grid'] = strip_tags($new_instance['grid']);
		$instance['effect'] = strip_tags($new_instance['effect']);
		$instance['duration'] = strip_tags($new_instance['duration']);
		$instance['slideshow_id'] = strip_tags($new_instance['slideshow_id']);
		return $instance;
	}

	function form($instance) {
			$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'slideshow_id' => '', 'type' => '' ) );
			$title = strip_tags($instance['title']);
			$width = strip_tags($instance['width']);
			$height = strip_tags($instance['height']);
			$colorbox = strip_tags($instance['colorbox']);
			$controls = strip_tags($instance['controls']);
			$grid = strip_tags($instance['grid']);
			$effect = strip_tags($instance['effect']);
			$duration = strip_tags($instance['duration']);
			$slideshow_id = strip_tags($instance['slideshow_id']);
			$type = strip_tags($instance['type']);
	?>
				<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
				
				<p>
					<label for="<?php echo $this->get_field_id('slideshow_id'); ?>">
						Slideshow: 
						<select class="widefat" id="<?php echo $this->get_field_id('slideshow_id'); ?>" name="<?php echo $this->get_field_name('slideshow_id'); ?>">
							
							
							<?php query_posts(array('post_type'=>'slideshow')); ?>

							<?php
								if (have_posts()) : while (have_posts()) : the_post();
							?>

								<option value="<?php echo get_the_ID(); ?>" <?php if (attribute_escape($slideshow_id) == get_the_ID()) { echo "selected='selected'"; } ?>><?php the_title() ?></option>

							<?php
								endwhile;
								endif;
							?>
							
						</select>
					</label>
				</p>
				
				<p>
					<label for="<?php echo $this->get_field_id('type'); ?>">
						Type: 
						<select class="widefat" id="<?php echo $this->get_field_id('type'); ?>" name="<?php echo $this->get_field_name('type'); ?>">
							<option value="slideshow" <?php if (attribute_escape($type) == "slideshow") { echo "selected='selected'"; } ?>>Slideshow (1 Big Rotating Image)</option>
							<option value="gallery" <?php if (attribute_escape($type) == "gallery") { echo "selected='selected'"; } ?>>Gallery (Grid of Images)</option>
							<option value="featuredimg" <?php if (attribute_escape($type) == "featuredimg") { echo "selected='selected'"; } ?>>Feature Image (First Image - Link Opens Slideshow)</option>
						</select>
					</label>
				</p>
				
				<p>
					<label for="<?php echo $this->get_field_id('effect'); ?>">
						Transition Effect (if slideshow): 
						<select class="widefat" id="<?php echo $this->get_field_id('effect'); ?>" name="<?php echo $this->get_field_name('effect'); ?>">
							<option value="fade" <?php if (attribute_escape($effect) == "fade") { echo "selected='selected'"; } ?>>Fade</option>
							<option value="slide" <?php if (attribute_escape($effect) == "slide") { echo "selected='selected'"; } ?>>Slide</option>
						</select>
					</label>
				</p>
				
				<p><label for="<?php echo $this->get_field_id('duration'); ?>">Duration (in milliseconds, if slideshow): <input class="widefat" id="<?php echo $this->get_field_id('duration'); ?>" name="<?php echo $this->get_field_name('duration'); ?>" type="text" value="<?php echo attribute_escape($duration); ?>" /></label></p>
				
				<p><label for="<?php echo $this->get_field_id('width'); ?>">Width (if slideshow): <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo attribute_escape($width); ?>" /></label></p>
				
				<p><label for="<?php echo $this->get_field_id('height'); ?>">Height (if slideshow): <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo attribute_escape($height); ?>" /></label></p>
					
				<p>
					<label for="<?php echo $this->get_field_id('controls'); ?>">
						Controls Position (if slideshow): 
						<select class="widefat" id="<?php echo $this->get_field_id('controls'); ?>" name="<?php echo $this->get_field_name('controls'); ?>">
							<option value="left" <?php if (attribute_escape($controls) == "left") { echo "selected='selected'"; } ?>>Left</option>
							<option value="centre" <?php if (attribute_escape($controls) == "centre") { echo "selected='selected'"; } ?>>Centred</option>
							<option value="right" <?php if (attribute_escape($controls) == "right") { echo "selected='selected'"; } ?>>Right</option>
						</select>
					</label>
				</p>
				
				<p><label for="<?php echo $this->get_field_id('grid'); ?>">How many images wide? (if gallery): <input class="widefat" id="<?php echo $this->get_field_id('grid'); ?>" name="<?php echo $this->get_field_name('grid'); ?>" type="text" value="<?php echo attribute_escape($grid); ?>" /></label></p>
				
				<p><label for="<?php echo $this->get_field_id('colorbox'); ?>">Colorbox (<i>"true" or "false"</i>. If true, all links will open in a popover dialog): <input class="widefat" id="<?php echo $this->get_field_id('colorbox'); ?>" name="<?php echo $this->get_field_name('colorbox'); ?>" type="text" value="<?php echo attribute_escape($colorbox); ?>" /></label></p>
	<?php
		}
}

function sliderly_register_widgets() {
	register_widget( 'SliderlyWidget' );
}

add_action( 'widgets_init', 'sliderly_register_widgets' );

?>