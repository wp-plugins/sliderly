<?php
/*
Plugin Name: Sliderly
Description: Awesomest slider plugin
Version: 1.0.8
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
				$output .= "<li>
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
	var $ = jQuery.noConflict();
	$(function(){
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
		
		$("#sliderly_admin_gallery").disableSelection();
		$("#sliderly_admin_gallery").sortable({
			forcePlaceholdersize: true
		});
		
		$("#sliderly_admin_gallery").bind( "sortupdate", function(event, ui) {
			var id = $(this).attr("data-id")
			var html = [];
			
			$("#sliderly_admin_gallery li").each(function(){
				var src = $(this).find("img:not(.trash)").attr("src")
				var title = $(this).find(".title").val()
				var desc = $(this).find(".desc").val()
				var href = $(this).find(".href").val()
				
				if (href.indexOf("://") == -1 && href != "" && href != "Link to Webpage")
				{
					href = "http://" + href;
				}
				
				var info = [src, title, desc, href];
				html.push(info.join("|02df|"))
			})
			
		  $.post("?page=sliderly", { update: "true", id: id, html: html.join("SL1D3RLYS3C43T") }, function(){
				$("#save_automagically").fadeOut().fadeIn()
			});
		});
		
		$("#sliderly_admin_gallery .title, #sliderly_admin_gallery .desc, #sliderly_admin_gallery .href").live("blur", function(){
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
		
		window.send_to_editor = function(html) {
			imgurl = jQuery('img', html).attr('src');
			tb_remove()
			$("<li>\
				<img src='<?php echo get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/images/trash.png' ?>' class='trash'>\
				<img src='" + imgurl + "' class='img' />\
				<div>\
					<input type='text' placeholder='Title' class='title' />\
					<textarea class='desc' placeholder='Description'></textarea>\
					<input type='text' placeholder='Link to Webpage' class='href' />\
				</div>\
			</li>").appendTo( $("#sliderly_admin_gallery") )
			$("#sliderly_admin_gallery").trigger('sortupdate')
		}
		
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
		
		$.fn.resizer();
		
		$(window).resize(function(){
			$.fn.resizer();
		});
	});
	
	$.fn.resizer = function() {
		var wpadminbar_height = $("#wpadminbar").height();
		var footer_height = $("#footer").height();
		var window_height = $(window).height();

		$("#sliderly_nav").height( window_height - wpadminbar_height + footer_height - 180 )
	}
</script>

<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Sliderly</h2>
	<p>Drag and Drop your images to reorder. Copy the short code in the dark bar (below) and paste it on one of your pages. Or use the Sliderly widget.</p>
	
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
			
			<?php query_posts(array('post_type'=>'slideshow')); ?>
			
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
			<a href="<?php echo admin_url(); ?>/media-upload.php?TB_iframe=1&amp;width=640&amp;height=85" class="thickbox button-primary add_media" id="content-add_media" title="Add Media" onclick="return false;">Add Image</a>
			<a href="#" class="button-secondary delete_sliderly">Delete This Sliderly</a>
		</div>
	
		<ul id="sliderly_admin_gallery"></ul>
		
		<div style="clear: both; "></div>
		
		<p id="save_automagically" style="float: right; margin: -23px 10px 0 0; color: #777; ">Your changes are saved automagically.</p>
		
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

function media_scripts() {
wp_enqueue_script('media-upload');
wp_enqueue_script('thickbox');
wp_register_script('my-upload', array('media-upload','thickbox'));
}
 
function media_styles() {
wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'media_scripts');
add_action('admin_print_styles', 'media_styles');
add_action( 'init', 'create_slideshows' );
add_action( 'admin_menu', 'sliderly_menu' );
add_action( 'admin_init', 'register_mysettings' );
add_action('admin_head', 'admin_register_head');

?>