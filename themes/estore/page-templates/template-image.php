<?php

/**
 * Template Name: Image part
 *
 * @package ThemeGrill
 * @subpackage eStore
 * @since 1.0
 */

get_header();
// echo rtrim(dirname(__FILE__), '/') . '\custom33.js';

function enqueue_scripts_trigger()
{
	wp_enqueue_media();
	wp_enqueue_script('my_custom_js', rtrim(dirname(__FILE__), '/') . '\custom33.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_scripts_trigger');

?>

<div id="content" class="clearfix">
	<input type="button" value="Upload Image" class="button-primary" id="upload_image" />
	<input type="hidden" name="attachment_id" class="wp_attachment_id" value="" /> </br>
	<img src="" class="image" style="display:none;margin-top:10px;" />

</div>

<?php get_footer(); ?>