<?php
/*
Plugin Name: Easy Gallery Manager
Plugin URI: http://www.unclepear.com/easygallerymanager/
Description: Change your Wordpress Gallery in a jQuery Slideshow. On the fly!
Version: 0.2.1
Author:  Uncle Pear / David Saitta
License: GPL2
*/


add_option('easy_gallery_manager_insert_into_content', 'true');

add_option("easy_gallery_manager_size", 'full'); 
add_option("easy_gallery_manager_transition", 'fade'); 
add_option("easy_gallery_manager_transition_speed", '400'); 

add_option("easy_gallery_manager_show_support", 'false'); 
add_option("easy_gallery_manager_show_pager", 'true'); 
add_option("easy_gallery_manager_pager_type", 'bullets'); 
add_option("easy_gallery_manager_pager_event", 'click'); 

add_option('easy_gallery_manager_ovveride_options', '');

add_option("easy_gallery_manager_show_titles", 'true'); 
add_option("easy_gallery_manager_show_descriptions", 'false'); 

add_option("easy_gallery_manager_show_controls", 'true'); 

add_option("easy_gallery_manager_nav_position", 'top'); 
add_option("easy_gallery_manager_timeout", '0'); 

add_option("easy_gallery_manager_show_controls", 'true'); 
add_option("easy_gallery_manager_controls_next", ''); 
add_option("easy_gallery_manager_controls_prev", ''); 

add_option("easy_gallery_manager_auto_height", 'true');
add_option("easy_gallery_manager_auto_width", 'true');

$easy_gallery_manager_enabled = null;

include("easy_gallery_manager_options.php");

/* Define the custom box */
add_action('add_meta_boxes', 'easy_gallery_manager_add_custom_box');

/* Do something with the data entered */
add_action('save_post', 'easy_gallery_manager_save_postdata');

/* Adds a box to the main column on the Post and Page edit screens */
function easy_gallery_manager_add_custom_box() {
			
    add_meta_box( 'easy_gallery_manager_sectionid', 'Easy Gallery Manager', 
	                'easy_gallery_manager_inner_custom_box', 'post','advanced', 'high'  );
    add_meta_box( 'easy_gallery_manager_sectionid', 'Easy Gallery Manager', 
	                'easy_gallery_manager_inner_custom_box', 'page','advanced', 'high'  );

	$url = plugins_url( 'frameready.js', __FILE__ );
	wp_register_script('frameready', $url, array('jquery'), '1.2.0', false); 
	wp_enqueue_script('frameready');

}

/* Prints the box content */
function easy_gallery_manager_inner_custom_box($data) {
	global $post;
	
  // Use nonce for verification
  	wp_nonce_field( plugin_basename(__FILE__), 'easy_gallery_manager_noncename' );
	ob_start();
	?>
	
	<input type="checkbox" id="enable-slideshow" name="easy_gallery_manager_enabled" value="true" <?php echo((get_post_meta($data->ID,'_easy_gallery_manager_enabled',true) == "true") ? 'checked' : ''); ?>>Enable Slideshow<br /><hr /><br /><br />
	<script type="text/javascript">
	/* <![CDATA[ */	
	jQuery(document).ready(function(){
			jQuery("#enable-slideshow").click(function(){
				show_hide_embedded_media();
			});
		});
		function show_hide_embedded_media(){
			if (jQuery("#enable-slideshow").is(":checked"))
				jQuery("#embedded-media").show();
			else
				jQuery("#embedded-media").hide();
		}
	
		function setup_embedded_media(){
			var frame = jQuery("#embedded-media").contents()
			frame.jQuery = jQuery;
			css = jQuery("head", frame).append("<link id='style_admin' rel='stylesheet' type='text/css' href='<?php echo $url = plugins_url( 'slideshow_admin.css', __FILE__ );?>'>");
    		
			frame.find("#tab-type a").text("Upload");
			
			var rows = frame.find('.media-item');
			rows.each(function(index){
				var delButton = jQuery(this).find('.del-attachment a.button:first');
				var url = delButton.attr('href');
				var delLink = jQuery('<a class="egm-delete-link">Delete</a>');
				delLink.attr('href', url);
				delLink.click(function(){
					if(confirm("Do you really want to delete this image?"))
					{
						return true;
					}
					return false;
				});
				
				jQuery(this).find('.describe-toggle-on').before(delLink);
			});
			
			show_hide_embedded_media();
		}
/* ]]> */
	</script>
	
	<?php
		$photos = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order') );
		
		$tab = "type";
		if(count($photos) > 0)
			$tab = "gallery";
	?>
	
	<iframe id='embedded-media' style="display:none" onload='setup_embedded_media()' name='embedded-media' src="<?php echo get_settings('siteurl') ?>/wp-admin/media-upload.php?post_id=<?php echo $data->ID?>&tab=<?php echo$tab?>&type=image" width="100%" height="300">
	</iframe>
	<?php
	echo ob_get_clean();
}

/* When the post is saved, saves our custom data */
function easy_gallery_manager_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['easy_gallery_manager_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
  // to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }

  	// OK, we're authenticated: we need to find and save the data
	$data = $_POST['easy_gallery_manager_enabled'];

	update_post_meta($post_id,'_easy_gallery_manager_enabled',$data);
	
   return $mydata;
}

function init_slideshow()
{
	global $post;
	global $easy_gallery_manager_enabled;

	if( !is_admin())
	{
		$easy_gallery_manager_enabled = get_post_meta($post->ID,'_easy_gallery_manager_enabled',true) == "true";	
		if($easy_gallery_manager_enabled)
		{
			wp_enqueue_script('jquery');
	
			$url = plugins_url( 'jquery.cycle.all.min.js', __FILE__ );
			wp_register_script('jquery.cycle', $url, array('jquery'), '2.88', false); 
			wp_enqueue_script('jquery.cycle');
	
			$url = plugins_url( 'slideshow.css', __FILE__ );
			wp_register_style('easy_gallery_manager', $url);
			wp_enqueue_style( 'easy_gallery_manager');
		}
	}
}

function show_slideshow($content=""){
	global $post;
	global $easy_gallery_manager_enabled;
	
	if(!$easy_gallery_manager_enabled)
		return $content;
	
	$slideshow = null;
	$photos = get_children( array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order') );
	if ($photos) 
	{
		$counter = 0;
		foreach ($photos as $photo) 
		{
			$img_info = wp_get_attachment_image_src($photo->ID, get_option('easy_gallery_manager_size'));
			$attachment =& get_post($photo->ID);
			
			$alt = trim(strip_tags( get_post_meta($photo->ID, '_wp_attachment_image_alt', true) ));// Use Alt field first
			if(get_option('easy_gallery_manager_show_titles'))
				$title = trim(strip_tags( $attachment->post_title ));
			if(get_option('easy_gallery_manager_show_descriptions'))
			$description = trim(strip_tags( $attachment->post_content ));
			
			$size = 'width="'.$img_info[1].'" height="'.$img_info[2].'"';
			
			$images .= '<img src="'.$img_info[0].'" alt="'.$alt.$index.'" title="'.$title.'" description="'.$description.'" '.(($counter > 0) ? 'style="display:none"' : '').' '.$size.' />';
			$counter++;
		}
		$slideshow = '<div class="slideshow"><div class="wrapper">'.$images.'</div></div>';
	}
	
	return $content . $slideshow; 
}

function set_easy_gallery_manager(){
	global $easy_gallery_manager_enabled;
		
	if(!$easy_gallery_manager_enabled)
		return;
	
	ob_start();
	?>
		<script type="text/javascript">
/* <![CDATA[ */
		(function($) {
			$.fn.slideshow = function() {

				var slider = $(this);

				var controls = slider;
				
				var images = slider.find('img');
				if(images.length <= 1)
					return;
				controls.append("<div style='clear:both'></div>");
				
				<? if(get_option('easy_gallery_manager_show_pager')) :?>
					controls.append('<div class="slideshow_pager"></div>');
				<? endif; ?>
				
				<? if(get_option('easy_gallery_manager_show_titles') || get_option('easy_gallery_manager_show_descriptionss')) : ?>
				controls.append('<div class="slideshow_caption"></div>');
				<? endif; ?>
	
				<? if(get_option('easy_gallery_manager_show_controls'))
					{
						$controls = '<div class="slideshow_controls">';
						if(trim(get_option('easy_gallery_manager_controls_next')) == "" && trim(get_option('easy_gallery_manager_controls_prev')) == "")
							$controls .= '<a class="slideshow_controls_prev"><span>Prev</span></a><a class="slideshow_controls_next"><span>Next</span></a>';
						$controls .= '</div>';
						echo 'controls.append(\''.$controls.'\');';
					}
				?>
				
				controls.append("<div style='clear:both'></div>");
				var prev = <?php echo((trim(get_option('easy_gallery_manager_controls_prev')) == "") ? 'controls.find(\'.slideshow_controls_prev\');' : get_option('easy_gallery_manager_controls_prev').";");?>
				var next = <?php echo((trim(get_option('easy_gallery_manager_controls_next')) == "") ? 'controls.find(\'.slideshow_controls_next\');' : get_option('easy_gallery_manager_controls_next').";");?>
				
				var pager = controls.find('.slideshow_pager');
				var caption = controls.find('.slideshow_caption');
				
				<?php if(get_option('easy_gallery_manager_auto_height')): ?>
				
				 var maxHeight = Math.max.apply( Math, images.map(function(i,e){ return jQuery(e).outerHeight() }).get());
				 if(maxHeight > 0)
				 	slider.find('.wrapper').height(maxHeight+'px');
					
				<?php endif;?>	
				
				<?php if(get_option('easy_gallery_manager_auto_width')): ?>
					var maxWidth = Math.max.apply( Math, images.map(function(i,e){ return jQuery(e).outerWidth() }).get());
				 	if(maxWidth > 0)
				 		slider.width(maxWidth+'px');
				<?php endif;?>
				
				var options = { 
					
					fx: '<?php echo get_option('easy_gallery_manager_transition'); ?>',
					speed: '<?php echo get_option('easy_gallery_manager_transition_speed')?>',
					<?php if(!trim(get_option('easy_gallery_manager_timeout')) == "")
						echo 'timeout: '.get_option('easy_gallery_manager_timeout').',';
					?>
					prev: prev,
			        next: next,
					height: 'auto',
			      	slideExpr: 'img:not(".slideshow_controls")',
					<?php $easy_gallery_manager_pager_type = get_option('easy_gallery_manager_pager_type');
					if(trim($easy_gallery_manager_pager_type) != "none") : ?>
					pager: pager,
					pagerAnchorBuilder: function(idx, slide) {
						return '<a href="#"><?php echo($easy_gallery_manager_pager_type == "bullets" ? '&bull;' : '\'+(idx+1)+\'')?></a>';
					},
					pagerEvent: '<?php echo get_option('easy_gallery_manager_pager_event')?>',
					<?php endif; ?>
					after:     function() {
						var html = "<span class='title'>"+this.title+'</span><span class="description">'+jQuery(this).attr('description')+'</span>';
						caption.html(html);
					}
				}
				
				<?php
					$extra = get_option('easy_gallery_manager_ovveride_options');
					if(trim($extra) != "")
					{?>
				$.extend(true, options, <?php echo $extra?>);
				<?php } ?>
				
				
				slider.cycle(options);
			}
		})(jQuery);
		
		jQuery(window).load(function($) {
			var slide_containers = jQuery('.slideshow');
			jQuery.each(slide_containers, function(index, el)
			{
				jQuery(el).slideshow();
			});
		});
/* ]]> */
		</script>
	
	<?php
	echo ob_get_clean();
}

add_action('wp_head', 'init_slideshow', 1);
add_action('wp_footer', 'set_easy_gallery_manager');

if(get_option('easy_gallery_manager_insert_into_content')=='true')
	add_action('the_content', 'show_slideshow');


/**
 * display the gallery from post inside The Loop
 */
function the_easy_gallery_manager(){
    echo show_slideshow();
}

/**
 * return the gallery from post inside The Loop
 */
function get_the_easy_gallery_manager(){
	return show_slideshow();
}

/**
 * return true if the gallery if enabled for the post inside The Loop
 */
function easy_gallery_manager_is_enabled()
{
	global $post;
	return get_post_meta($post->ID,'_easy_gallery_manager_enabled',true) == "true";
}
?>