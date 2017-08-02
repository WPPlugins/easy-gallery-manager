=== Easy Gallery Manager ===
Contributors: unclepear
Tags: gallery, slideshow, automatic, image, admin, easy, eazy
Tested up to: 3.0.3
Stable tag: 1.0
Requires at least: 3.0

Change your Wordpress Gallery in a jQuery Slideshow. On the fly!

== Description ==
Change your Wordpress Gallery in a jQuery Slideshow. On the fly!

<h4>Don't worry about the code</h4>
It's so easy!
With this plugin you don't have to code anything: just write your post with the usual wysiwyg editor in Wordpress and activate the slideshow...it's done!

<h4>Sort your images with drag&drop</h4>
You can rearrange the images in the slideshow straight below your post with a simple drag 'n' drop.
No cut, no paste, no numbered images, no metabox. Manage them on the fly.

<h4>Customize your slideshow in seconds</h4>
Wanna change the transition? Customize the timing of the slideshow? Use bullets instead of numbers?
Now you can, using the settings panel and lots of customizable features.

== Installation ==

You can download and install Easy Gallery Manager using the built in WordPress plugin installer. If you download Easy Gallery Manager manually, make sure it is uploaded to "/wp-content/plugins/easy-gallery-manager/".

Activate Easy Gallery Manager in the "Plugins" admin panel using the "Activate" link.



A NOTE ABOUT SLIDESHOW SIZE

You can force the container width and height according to the bigger image in the slideshow by enabling the options in the settings panel.

Automatically set container width, will set the slideshow width according to the size of the bigger image.
Automatically set container height, will set the slideshow height according to the size of the bigger image.


== Frequently Asked Questions ==

= Where can I get support? =

For any kind of information or support, feel free to reach us at http://unclepear.com/easygallerymanager

= How can I change prev/next buttons style? =

You can provide your own style for prev/next buttons by using css.

/* set an image for next button */
.slideshow .slideshow_controls .slideshow_controls_next {
	background: url(here_the_path_to_the_image_file.png) no-repeat;
	width: 68px;
	height: 68px;
}

/* hide the text */
.slideshow .slideshow_controls .slideshow_controls_next span
{
	display: none;
}

= How can I manually include Easy Gallery Manager?

If you choose not to automatically include the gallery into the post/page content, you can use some functions like:
`<?php the_easy_gallery_manager() ?>`
or
`<?php echo get_the_easy_gallery_manager() ?>`

If you want to know if the gallery is enabled for the post inside The Loop
`<?php 
if(easy_gallery_manager_is_enabled())
{
	// gallery enabled
}
 ?>`

== Changelog ==

= 0.2.1 = 
* fixed readme

= 0.2 =
* fixed installation problem 'the plugin does not have a valid header'.
* fixed some php compatibility.
* fixed slideshow size.
* some minor bugfixes.

= 0.1 =
* Initial Version.