<?php
// create the admin menu

// hook in the action for the admin options page
add_action('admin_menu', 'add_easy_gallery_manager_option_page');

function add_easy_gallery_manager_option_page() {
// hook in the options page function
add_options_page('UP Slideshow', 'Easy Gallery Manager', 6, __FILE__, 'easy_gallery_manager_options_page');
}

function easy_gallery_manager_options_page() {

$size = get_option('easy_gallery_manager_size');
$transition = get_option('easy_gallery_manager_transition');
$speed = get_option('easy_gallery_manager_transition_speed');
$pager_type = get_option('easy_gallery_manager_pager_type');

?>
<div class="wrap" style="width:500px">
<h2>Easy Gallery Manager Options</h2>
<p>Options changed here become the default for all slideshows.</p>

<form method="post" action="options.php">

<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row">Auto insert into content</th>
<td><input type="checkbox" name="easy_gallery_manager_insert_into_content" value="true" <?php if (get_option('easy_gallery_manager_insert_into_content')=="true") {echo' checked="checked"'; }?>/> Enable</td>
</tr>

<tr valign="top">
<th scope="row">Slideshow size<sup>1</sup></th>
<td><select name="easy_gallery_manager_size" value="<?php $size;?>" />
	<option value="thumbnail" <?php if($size == thumbnail) echo " selected='selected'";?>>thumbnail</option>
	<option value="medium" <?php if($size == medium) echo " selected='selected'";?>>medium</option>
	<option value="large" <?php if($size == large) echo " selected='selected'";?>>large</option>
	<option value="full" <?php if($size == full) echo " selected='selected'";?>>full</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Automatically set container width</th>
<td>
<input type="checkbox" name="easy_gallery_manager_auto_width" value="true" <?php if (get_option('easy_gallery_manager_auto_width')=="true") {echo' checked="checked"'; }?>/> Set width according to the size of the bigger image.
</tr>

<tr valign="top">
<th scope="row">Automatically set container height</th>
<td>
<input type="checkbox" name="easy_gallery_manager_auto_height" value="true" <?php if (get_option('easy_gallery_manager_auto_height')=="true") {echo' checked="checked"'; }?>/> Set height according to the size of the bigger image.
</tr>

<tr valign="top">
<th scope="row">Transition FX</th>
<td><select name="easy_gallery_manager_transition" value="<?php echo get_option('easy_gallery_manager_transition'); ?>" />
	<option value="fade" <?php if($transition == fade) echo " selected='selected'";?>>fade</option>
	<option value="scrollHorz" <?php if($transition == scrollHorz) echo " selected='selected'";?>>scrollHorz</option>
	<option value="none" <?php if($transition == none) echo " selected='selected'";?>>none</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Autoplay timeout<sup>2</sup></th>
<td><input type="text" size="6" name="easy_gallery_manager_timeout" value="<?php echo $ps_timeout;?>"/></td>
</tr>	


<tr valign="top">
<th scope="row">Transition speed</th>
<td><select name="easy_gallery_manager_transition_speed" value="<?php echo get_option('easy_gallery_manager_transition_speed'); ?>" />
	<option value="200" <?php if($speed == 200) echo " selected='selected'";?>>200</option>
	<option value="400" <?php if($speed == 400) echo " selected='selected'";?>>400</option>
	<option value="600" <?php if($speed == 600) echo " selected='selected'";?>>600</option>
	<option value="800" <?php if($speed == 800) echo " selected='selected'";?>>800</option>
	<option value="1000" <?php if($speed == 1000) echo " selected='selected'";?>>1000</option>
	<option value="1500" <?php if($speed == 1500) echo " selected='selected'";?>>1500</option>
	<option value="2000" <?php if($speed == 2000) echo " selected='selected'";?>>2000</option>
	<option value="2500" <?php if($speed == 2500) echo " selected='selected'";?>>2500</option>
</select>
</td>
</tr>

<tr valign="top">
<th scope="row">Titles and descriptions</th>
<td><input type="checkbox" name="easy_gallery_manager_show_titles" value="true" <?php if (get_option('easy_gallery_manager_show_titles')=="true") {echo' checked="checked"'; }?>/> Show titles</td>
<td><input type="checkbox" name="easy_gallery_manager_show_descriptions" value="true" <?php if (get_option('easy_gallery_manager_show_descriptions')=="true") {echo' checked="checked"'; }?>/> Show descriptions</td>
</tr>	

<tr valign="top">
<th scope="row">Pagination Type</th>
<td><select name="easy_gallery_manager_pager_type" avalue="<?php echo $pager_type; ?>" />
	<option value="none" <?php if($pager_type == 'none') echo " selected='selected'";?>>None</option>
	<option value="bullets" <?php if($pager_type == 'bullets') echo " selected='selected'";?>>Bullets</option>
	<option value="numbers" <?php if($pager_type == 'numbers') echo " selected='selected'";?>>Numbers</option>
</td>
</tr>

<tr valign="top">
<th scope="row">Controls</th>
<td>
<input type="checkbox" name="easy_gallery_manager_show_controls" value="true" <?php if (get_option('easy_gallery_manager_show_controls')=="true") {echo' checked="checked"'; }?>/> Show Controls (eg. arrows)
Next button id: <input type="text" size="6" name="easy_gallery_manager_controls_next" value="<?php echo get_option('easy_gallery_manager_controls_next');?>"/><br />
Prev button id: <input type="text" size="6" name="easy_gallery_manager_controls_prev" value="<?php echo get_option('easy_gallery_manager_controls_prev');?>"/></td>
</tr>

</table>

<br />

<strong>Override slideshow <a href="http://jquery.malsup.com/cycle/options.html">options</a></strong><br /><br />

<textarea name="easy_gallery_manager_ovveride_options" cols="50" rows="15">
<?php echo get_option('easy_gallery_manager_ovveride_options');?>
</textarea>
<p>eg: {pagerEvent:'mouseover'}</p>

<input type="hidden" name="page_options" value="easy_gallery_manager_ovveride_options, easy_gallery_manager_size, easy_gallery_manager_transition, easy_gallery_manager_transition_speed, easy_gallery_manager_show_titles,
easy_gallery_manager_show_descriptions, easy_gallery_manager_timeout, easy_gallery_manager_nav_position, easy_gallery_manager_pager_type, easy_gallery_manager_show_controls, easy_gallery_manager_controls_next, easy_gallery_manager_controls_prev, easy_gallery_manager_insert_into_content, easy_gallery_manager_auto_height, easy_gallery_manager_auto_width" />
<input type="hidden" name="action" value="update" />	
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>

</div>
<?php } ?>