<?php
/*
Plugin Name: Sliderly
Description: The Best Slider, Slideshow, and Gallery Plugin For Wordpress
Version: 1.0.19
Author: Dallas Read
Author URI: http://www.DallasRead.com
License: GPL2

Copyright 2012  Dallas Read  (email : dallas@excitecreative.ca)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__));
    echo "<link rel='stylesheet' type='text/css' href='$url/sliderly.css' />\n";
		wp_enqueue_script(array("jquery-ui-core", "interface", "jquery-ui-widget", "jquery-ui-mouse", "wp-lists", "jquery-ui-sortable"));
		wp_enqueue_media();
}

function register_mysettings() { // whitelist options
  register_setting( 'sliderly-group', 'new_option_name' );
}

function sliderly_menu() {
	add_menu_page( 'Sliderly Slideshows', 'Slideshows', 'manage_options', 'sliderly', 'sliderly_options' );
}

function sliderly_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if ($_POST["update"] == "true")
	{
		$post = array(
			'ID' => $_POST["id"],
			'post_content' => $_POST["html"]
		);
		
		wp_update_post( $post, $wp_error );
	}
	
	if ($_GET["show"] == "true")
	{
		echo "SLIDESHOW: ";
		
		$content = get_post($_GET["id"]);
		$slideshow = $content->post_content;
		
		$slideshow_exploded = explode("SL1D3RLYS3C43T", $slideshow);
		$output = "";

		if (strpos($slideshow,'|02df|') !== false)
		{
			foreach ($slideshow_exploded as $slide)
			{
				$slide_exploded = explode("|02df|", $slide);
				$src = $slide_exploded[0];
				$thumb = $src;
				$title = stripslashes($slide_exploded[1]);
				$desc = $slide_exploded[2];
				$href = $slide_exploded[3];
				$type = $slide_exploded[4];
				
				if ($type == "html")
				{
					$output .= "<li class='" . $type . "_li'>
									<img src='" . get_option('siteurl') . "/wp-content/plugins/" . basename(dirname(__FILE__)) . "/images/trash.png' class='trash' />
									<div>
										<textarea class='html' placeholder='Enter HTML here...'>$src</textarea>
									</div>
							</li>";
				}
				else
				{
					$output .= "<li class='" . $type . "_li'>
									<img src='" . get_option('siteurl') . "/wp-content/plugins/" . basename(dirname(__FILE__)) . "/images/trash.png' class='trash' />
									<img src='" . $thumb . "' class='img' />
									<div>
										<input type='text' class='title' placeholder='Title' value=\"$title\" />
										<textarea class='desc' placeholder='Description'>$desc</textarea>
										<input type='text' class='href' placeholder='Link to Webpage' value=\"$href\" />
									</div>
							</li>";
				}
			}
		}
		
		echo $output;
						
		echo " :::";
	}
	
	if ($_POST["create"] == "true")
	{
		$post = array(
			'post_title' => $_POST["name"],
			'post_status' => 'publish',
			'post_type' => "slideshow"
		);
		
		$id = wp_insert_post( $post, $wp_error );
		
		echo "NEW POST ID: $id :::";
	}
	
	if ($_POST["delete"] == "true")
	{
		wp_delete_post( $_POST["id"], true );
	}
	
	if (isset($_POST["option_page"]))
	{
		
		$opt_name = array(
			'img_size' => 'att_img_size'
		);
		
		$opt_val = array(
			'img_size' => $_POST[ $opt_name['img_size'] ]
		);
		
		update_option( $opt_name['img_size'], $opt_val['img_size'] );
		
?>
		
		<div id="message" class="updated fade">
		  <p>
				<strong>
		    	<?php _e('We have saved your Sliderly slideshow.', 'att_trans_domain' ); ?>
		    </strong>
			</p>
		</div>
		
		<?php
	}
?>

<script type="text/javascript">
	jQuery(function($){
		$(window).resize(function(){
			var wpadminbar_height = $("#wpadminbar").height();
			var footer_height = $("#footer").height();
			var window_height = $(window).height();
			
			$("#sliderly_nav").height( window_height - wpadminbar_height + footer_height - 180 )
		});
		
		$(".new_slideshow input").click(function(){
			$(".form_wrapper").show()
			$(".new_slideshow").hide()
			$(".form_wrapper input:visible:first").focus()
			return false;
		});
		
		$(".trash").live("click", function(){
			if (confirm("Are you sure you want to remove this image?"))
			{
				$(this).parents("li").remove()
				$("#sliderly_admin_gallery").trigger('sortupdate')
			}
		});
		
		$(".cancel").click(function(){
			$(this).parents(".form_wrapper").hide()
			$(".new_slideshow").show()
			$("#slideshow_name").val("")
			return false;
		});
		
		$("#new_sliderly_slideshow").submit(function(){
			var name = $("#slideshow_name").val();
			$(".form_wrapper").hide();
			$(".new_slideshow").show();
			$.post("?page=sliderly", { create: "true", name: name }, function(data){
				var content = data.match("NEW POST ID: (.*) :::")
				var id = content[0].replace("NEW POST ID: ", "").replace(" :::", "")
				$("<li data-id='" + id + "'><a href=\"#\" class=\"sliderly_menu_link\"><span>" + id + "</span> - " + name + "</a></li>").insertAfter( $("#sliderly_nav .spacer") )
				$(".sliderly_menu_link:first").click()
				$("#slideshow_name").val("")
			})
			return false;
		});
		
		$(".sliderly_menu_link").live("click", function(){
			var id = $(this).find("span").text()
			$(".sliderly_menu_link").removeClass("selected")
			$(this).addClass("selected")
			$(".delete_sliderly, #sliderly_admin_gallery").attr("data-id", id)
			$(".sliderly_id").text(id)
			$(".sliderly_shortcode").show()
			$.post("?page=sliderly&show=true&id=" + id, function(data) {
				var content = data.match(/SLIDESHOW: ([\s\S]*) :::/)
				var html = content[0].replace("SLIDESHOW: ", "").replace(" :::", "");
				$("#sliderly_admin_gallery").html(html)
			})
			return false;
		});
		
		$(".sliderly_menu_link:first").click();
		
		$("#sliderly_admin_gallery img").disableSelection();
		
		$("#sliderly_admin_gallery").sortable({
			forcePlaceholdersize: true
		});
		
		$("#sliderly_admin_gallery").bind("sortupdate", function(event, ui) {
			var id = $(this).attr("data-id")
			var html = [];
			
			$("#sliderly_admin_gallery li").each(function(){
				if ($(this).hasClass("html_li"))
				{
					var content = $(this).find(".html").val()
					var info = [content, "", "", "", "html"];
				}
				else
				{
					var src = $(this).find("img:not(.trash)").attr("src")
					var title = $(this).find(".title").val()
					var desc = $(this).find(".desc").val()
					var href = $(this).find(".href").val()

					if (href.indexOf("://") == -1 && href != "" && href != "Link to Webpage")
					{
						href = "http://" + href;
					}

					var info = [src, title, desc, href, "img"];
				}
				html.push(info.join("|02df|"))
			})
			
		  $.post("?page=sliderly", { update: "true", id: id, html: html.join("SL1D3RLYS3C43T") }, function(){
				$("#save_automagically").fadeOut().fadeIn()
			});
		});
		
		$("#sliderly_admin_gallery .title, #sliderly_admin_gallery .desc, #sliderly_admin_gallery .href, #sliderly_admin_gallery .html").live("blur", function(){
			$("#sliderly_admin_gallery").trigger('sortupdate')
		})
		
		$("#sliderly_admin_gallery li").hover(
			function(){
				$(this).css({
					"background": "#fff"
				})
			},
			function(){
				$(this).css({
					"background": ""
				})
			}
		)
		
		var file_frame;
		$('.add_media').live('click', function(event){
			event.preventDefault();
			
			if ( file_frame ) {
				file_frame.open();
				return;
			}
 
			file_frame = wp.media.frames.file_frame = wp.media({
				title: "Add Images to Slideshow",
				button: {
					text: "Add To Slideshow",
			},
				multiple: true
			});
 
			file_frame.on( 'select', function() {
				var selection = file_frame.state().get('selection');
				
				selection.map( function( attachment ) {
					attachment = attachment.toJSON();

					var li = $("<li />").addClass("img_li");
					var trash = $("<img />").attr("src", "<?php echo get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/trash.png' ?>").addClass("trash");
					var thumb = $("<img />").attr("src", attachment.url).addClass("img");
					var details = $("<div />");
				
					var title = $("<input />").attr({
						"type": "text",
						"placeholder": "Title",
						"class": "title"
					}).appendTo(details);
					var desc = $("<textarea />").attr({
						"placeholder": "Description",
						"class": "desc"
					}).appendTo(details);
					var href = $("<input />").attr({
						"type": "text",
						"placeholder": "Link to Webpage",
						"class": "href"
					}).appendTo(details);
				
					trash.appendTo(li);
					thumb.appendTo(li);
					details.appendTo(li);
					li.appendTo("#sliderly_admin_gallery");
				});
				
				$("#sliderly_admin_gallery").trigger('sortupdate')
			});
			
			file_frame.open();
		});
		
		$(".add_html").live("click", function(){
			$("<li class='html_li'>\
					<img src='<?php echo get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/trash.png' ?>' class='trash'>\
					<div>\
						<textarea class='html' placeholder='Enter HTML here...'></textarea>\
					</div>\
				</li>").appendTo( $("#sliderly_admin_gallery") )
			$("#sliderly_admin_gallery").trigger('sortupdate')
		})
		
		$(".delete_sliderly").live("click", function(){
			var id = $(this).attr("data-id")
			if (confirm("Are you sure you want to delete this sliderly?"))
			{
				$.post("?page=sliderly", { delete: "true", id: id }, function(){
					$("#sliderly_nav li[data-id='" + id + "']").remove()
					$("#sliderly_admin_gallery li").remove()
					$(".sliderly_shortcode").hide()
				});
			}
		});
		
		$(".show_how_to_use").live("click", function(){
			$("#how_to_use").toggle()
			return false;
		});
		
		$(window).resize()
	});
</script>

<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2 style="display: inline-block; ">Sliderly <iframe src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fgithub.com%2Fdallas22ca%2FSliderly&amp;send=false&amp;layout=button_count&amp;width=130&amp;show_faces=true&amp;action=recommend&amp;colorscheme=light&amp;font&amp;height=21&amp;appId=341097969303814" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:21px; margin-left: 15px; display: inline-block; " allowTransparency="true"></iframe>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="margin-left: 15px; display: inline-block; ">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="QR2D7NP54XEQW">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
		</form>
	</h2>
	<p style="color: #777; ">
		Changes save automagically. <a href="#" class="show_how_to_use">How do I use Sliderly?</a>
	</p>
	
	<div id="how_to_use">
		<a href="#" class="show_how_to_use">Close this window</a>.<br />
		
		<h3>How do I reorder my slides?</h3><br />
		Simply click and drag the images around. Your changes will save automagically.<br /><br />
		
		<h3>How do I add a slideshow to a page?</h3><br />
		Copy the short code (shown in the dark bar) and paste it into your page!<br /><br />
		
		<h3>What options do I have?</h3><br />
		You can add as many options to your shortcode as you'd like. There are three gallery types:<br /><br />
		
		<b>[sliderly type=gallery]</b> - displays a grid of the images, defaults to 2 columns (can be changed with <b>grid</b>).<br /><br />
		<b>[sliderly type=slideshow]</b> - displays a simple, one-image-at-a-time slideshow with navigation controls.<br /><br />
		<b>[sliderly type=featuredimg]</b> - displays the first image of a gallery, if colorbox=true, clicking on it will pop out a slideshow of all images.<br /><br />
		
		A few other options are:<br /><br />
		<b>[sliderly colorbox=false]</b> - links open in the glorious colorbox (inline dialog box), defaults to <i>false</i>.<br /><br />
		<b>[sliderly width=100 height=100]</b> - width and height must be declared for the slideshow to display properly.<br /><br />
		<b>[sliderly controls=centre]</b> - where the slideshow controls will be positioned (left, centre, right, hide), defaults to <i>centre</i>.<br /><br />
		<b>[sliderly grid=3]</b> - sets how many images wide the gallery should be, defaults to <i>3</i>.<br /><br />
		<b>[sliderly effect=fade]</b> - sets how the slideshow images transition (slide, fade), defaults to <i>fade</i>.<br /><br />
		<b>[sliderly duration=2500]</b> - sets how many milliseconds between slideshow slides, defaults to <i>2500</i>.<br /><br />
		
		<h3>How do I add a slideshow to a sidebar (widget area)?</h3><br />
		You can add a slideshow or gallery to one of your widget areas by visiting your <a href="<?php echo admin_url("widgets.php") ?>">Widgets</a> page (found under appearance). Find the "Sliderly" widget and drag it into your sidebar. The Sliderly wizard will walk you through the display options (transition effects, slide duration, etc.).<br /><br />
		
		<h3>How do I add a slideshow to a custom template?</h3><br />
		Add a PHP line that looks like this...<br />
		echo do_shortcode('[sliderly id=XXX type=slideshow]');<br /><br />
		
		Thanks for using Sliderly!<br /><br />
		
		<a href="#" class="show_how_to_use">Close this window</a>.
	</div>
	
	<div id="sliderly_wrapper">

		<ul id="sliderly_nav">
			<li class="new_slideshow">
				<div class="wrapper">
					<input type="button" class="button-secondary" value="Add New Sliderly" />
				</div>
			</li>
			<li class='form_wrapper'>
				<div class="wrapper">
					<form id="new_sliderly_slideshow">
						<label>What is the name of the Sliderly?<br /><br />
							<input type='text' id="slideshow_name" placeholder="Sliderly Name" />
						</label><br /><br />
						<input type='submit' href='#' class='button-primary' value='Save' />
						<a href='#' class='cancel'>Cancel</a>
					</form>
				</div>
			</li>
			<li class="spacer">&nbsp;</li>
			
			<?php query_posts(array('post_type'=>'slideshow', 'posts_per_page' => 100)); ?>
			
			<?php
				if (have_posts()) : while (have_posts()) : the_post();
			?>
				
				<li data-id="<?php the_id() ?>"><a href="#" class="sliderly_menu_link"><span><?php the_id() ?></span> - <?php the_title() ?></a></li>
			
			<?php
				endwhile;
				endif;
			?>
			
		</ul>
		
		<div id="sliderly_meta">
			<p class="sliderly_shortcode">[sliderly id=<span class="sliderly_id"></span> type=slideshow width=500 height=100]</p>
			<a href="#" class="button-primary add_media" title="Add Media">Add Image</a>
			<a href="#" class="button-primary add_html" title="Add HTML">Add HTML</a>
			<a href="#" class="button-secondary delete_sliderly">Delete This Sliderly</a>
		</div>
	
		<ul id="sliderly_admin_gallery"></ul>
		
		<div style="clear: both; "></div>
		
	</div>
	
	<form method="post" action="">
		<?php settings_fields( 'sliderly-group' ); ?>
		<?php do_settings_fields( 'sliderly-group', 'sliderly-group' ); ?>
		<!--<?php submit_button(); ?>-->
	</form>
</div>

<?php }

include("widget.php");
include("shortcode.php");

function create_slideshows() {
	wp_enqueue_script('jquery');
	
  $labels = array(
    'name' => _x('Sliderlies', 'post type general name'),
    'singular_name' => _x('Sliderly', 'post type singular name'),
    'add_new' => _x('Add New', 'Sliderly'),
    'add_new_item' => __('Add New Sliderly'),
    'edit_item' => __('Edit Sliderly'),
    'new_item' => __('New Sliderly'),
    'view_item' => __('View Sliderly'),
    'search_items' => __('Search Sliderly'),
    'not_found' =>  __('No Sliderly found'),
    'not_found_in_trash' => __('No Sliderly found in Trash'),
    'parent_item_colon' => ''
  );

  $supports = array('title', 'editor', 'custom-fields');

  register_post_type( 'slideshow',
    array(
      'labels' => $labels,
      'public' => false,
      'supports' => $supports
    )
  );
}

add_action( 'init', 'create_slideshows' );
add_action( 'admin_menu', 'sliderly_menu' );
add_action( 'admin_init', 'register_mysettings' );
add_action('admin_head', 'admin_register_head');

?>
