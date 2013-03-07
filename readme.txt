=== Plugin Name ===
Contributors: dallas22ca
Tags: slider, easy slider, easiest slider, simple slider, ajax
Requires at least: 3.4.1
Tested up to: 3.4.1
Stable tag: 1.0.19
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Wordpress plugin for making slideshows and galleries with simple ajax admin.

== Description ==

After trying a plethora of gallery management plugins, I didn't find one that I would want to provide for my clients. Up 'til now, I would use a plugin for home page sliders, a different plugin for a gallery widget, and a different one for galleries and slideshows embedded into posts.

Sliderly is my opinion of how galleries should be managed, whether they're for a slideshow or gallery.

**How easy is it? Click on the Slideshows tab, upload your images via the core WP Media Gallery, and Drag-and-Drop your images to re-order them. Show them on your site with a shortcode or a widget.**

In the front end of things, I chose to go with [Slides](http://slidesjs.com) and [Colorbox](http://www.jacklmoore.com/colorbox) because they're awesome, flexible, and reliable.

It comes with:

* Quick edit access via a menu item.
* Simple shortcode for `slideshow` or `gallery`.
* `[sliderly id=123 type=gallery]`
* Slideshows must declare their width and height `[sliderly id=123 type=slideshow width=500 height=100]`.
* To force links to open in colorbox (a pop-off-the-page image viewer), set colorbox to true `[sliderly id=123 type=gallery colorbox=true]`.
* Widget with intuitive interface for selecting gallery and type.
* Hard-codable into a template via `<?php echo do_shortcode('[sliderly id=88 type=slideshow]'); ?>`.

For those developers out there,the javascript is found at `js.php`. With a little magic, you can leverage the backend of Sliderly with your own custom plugin.

In the back end of things, it creates a custom post type called `slideshow` and just stuffs a hash of info into the post's content.

== Installation ==

1. Search for `Sliderly` in the 'Plugin Gallery' and use the one-click install *OR* [Download](https://github.com/dallas22ca/Sliderly/downloads) sliderly and Upload the `sliderly` folder to the `/wp-content/plugins/` directory in your own WordPress installation.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Use the shortcode `[sliderly id=123 type=gallery]` or use the fancy-schmancy widget.

== Frequently Asked Questions ==

= What are the different `type` I can use for the shortcode? =

* `gallery` - displays a grid of the images in two columns.
* `slideshow` - displays a simple, one-image-at-a-time slideshow with navigation controls.
* `featuredimg` - displays the first image of a gallery, if colorbox=true, clicking on it will show a slideshow of the images.

= What are the other features in the shortcode? =

* `colorbox` - links open in the glorious colorbox (inline dialog box), defaults to *false*
* `width` and `height` - must be declared for the slideshow to display properly
* `controls` - where the slideshow controls will be positioned (left, centre, right, hide), defaults to *centre*
* `grid` - sets how many images wide the gallery should be, defaults to *3*
* `effect` - sets slideshow transition (slide, fade), defaults to *fade*
* `duration` - sets milliseconds between slideshow slides, defaults to *2500*

== Screenshots ==

1. The drag-and-drop, ajax-ified management interface.

== Changelog ==

= 1.0.19 =
Add multiple files at once! - Thanks to Mike Jolley - http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
Fix where the Sliderly sidebar only showed 10 latest slideshows (limit is now 100).


= 1.0.18 =
Added donate button =) and unique ID for each slide. The ID is `sliderly_{sliderly_id}_{slide_index}`.

= 1.0.17 =
Added support for HTML slides, fade transition for slideshows, and slideshow slide duration. Also added "How do I use Sliderly?" in the admin panel.

= 1.0.16 =
Added support to hide slideshow controls with `[sliderly controls=hide]`.

= 1.0.15 =
Added grid (`[sliderly type=gallery grid=3]`) for widget and shortcode. This lets you choose how many columns of images you want to see.

= 1.0.14 =
Added type of "Featured Image" for widget and shortcode (`[sliderly type=featuredimg]`). This will let you just add just the first image of a gallery to the page. If colorbox is turned on, it will show a slideshow of all the images in that particular gallery.

= 1.0.13 =
Sliderly plays better with other plugins now.

= 1.0.12 =
Support for moving the slideshow controls.

= 1.0.11 =
Make the widget extend to surround gallery. Backend drag and drop bug fix (being able to edit description, title, and link).

= 1.0.10 =
A few more if statements regarding output (declaring width and height only if present, caption and title only if present).

= 1.0.9 =
More silly changes regarding colorbox.

= 1.0.8 =
Properly require jQuery if not already required.

= 1.0.7 =
Fixed slideshow error if multiple instances are displayed on the same page.

= 1.0.6 =
Updated readme.

= 1.0.5 =
Gallery images stripped of their declared height and width.

= 1.0.4 =
Bugs fixed that shouldn't have gotten by...

= 1.0.0 =
A first attempt at greatness.