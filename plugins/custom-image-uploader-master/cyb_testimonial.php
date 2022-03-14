<?php
/*
Plugin Name: Cybage Testimonial
Description: Used to demonstrate how to work with the media uploader in WordPress.
Author: cybage ecm
Version: 1.0.0
*/



function cyTestimonial_post_type()
{
	register_post_type('Testimonial', array(
		'labels' => array(
			'name' => __('Testimonials', 'cybmedia'),
			'singular_name' => __('Testimonial', 'cybmedia'),
			'add_new' => __('Add New', 'cybmedia'),
			'add_new_item' => __('Add New Testimonial', 'cybmedia'),
			'edit_item' => __('Edit Testimonial', 'cybmedia'),
			'new_item' => __('New Testimonial', 'cybmedia'),
			'view_item' => __('View Testimonial', 'cybmedia'),
			'view_items' => __('View Testimonials', 'cybmedia'),
			'archives' => __('Testimonial Archives', 'cybmedia'),
			'item_published' => __('Testimonial published.', 'cybmedia'),
			'item_published_privately' => __('Testimonial published privately.', 'cybmedia'),
			'item_reverted_to_draft' => __('Testimonial reverted to draft.', 'cybmedia'),
			'item_updated' => __('Testimonial updated.', 'cybmedia')
		),
		'has_archive'   => true,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'custom-fields', 'revisions'),
		'can_export' => true
	));
}
add_action('init', 'cyTestimonial_post_type');

function register_metaboxes()
{
	add_meta_box(
		'image_metabox',
		'Profile Image',
		'image_uploader_callback',
		"Testimonial",
		"normal",
		"low"
	);

	add_meta_box(
		"post_metadata_Testimonials_post", // div id containing rendered fields
		"Testimonial Name", // section heading displayed as text
		"post_meta_box_Testimonials_post_name", // callback function to render fields
		"Testimonial", // name of post type on which to render fields
		"normal", // location on the screen
		"low" // placement priority
	);

	add_meta_box(
		"post_metadata_Testimonials_post_position",
		"Testimonial Position",
		"post_meta_box_Testimonials_post_position",
		"Testimonial",
		"normal",
		"low"
	);

	add_meta_box(
		"post_metadata_Testimonials_post_comment",
		"Testimonial comment",
		"post_meta_box_Testimonials_post_comment",
		"Testimonial",
		"normal",
		"low"
	);
}
add_action('add_meta_boxes', 'register_metaboxes');

function image_uploader_callback($post_id)
{
	wp_nonce_field(basename(__FILE__), 'custom_image_nonce'); ?>

	<div id="metabox_wrapper">
		<img id="image-tag">
		<input type="hidden" id="img-hidden-field" name="custom_image_data">
		<input type="button" id="image-upload-button" class="button" value="Add Image">
		<input type="button" id="image-delete-button" class="button" value="Delete Image">
	</div>

<?php
}

function post_meta_box_Testimonials_post_name()
{
	global $post;
	$custom = get_post_custom($post->ID);
	$fieldDataName = $custom["_Testimonial_name"][0];
	echo "<input type=\"name\" name=\"_Testimonial_name\" value=\"" . $fieldDataName . "\" placeholder=\"e.g. Om Raut\">";
}

function post_meta_box_Testimonials_post_position()
{
	global $post;
	$custom = get_post_custom($post->ID);
	$fieldDataPosition = $custom["_Testimonial_position"][0];
	echo "<input type=\"text\" name=\"_Testimonial_position\" value=\"" . $fieldDataPosition . "\" placeholder=\"e.g. student\">";
}

function post_meta_box_Testimonials_post_comment()
{
	global $post;
	$custom = get_post_custom($post->ID);
	$fieldDatacomment = $custom["_Testimonial_comment"][0];
	echo "<textarea type=\"textarea\"  name=\"_Testimonial_comment\" placeholder=\"e.g. Greate place for shopping. Loved the new summer collections.  \" rows=\"8\" cols=\"150\">$fieldDatacomment</textarea>";
}

function register_admin_script()
{
	wp_enqueue_script('wp_img_upload', plugin_dir_url(__FILE__) . '/cyb_testimonial.js', array('jquery', 'media-upload'), '0.0.2', true);
	wp_localize_script('wp_img_upload', 'customUploads', array('imageData' => get_post_meta(get_the_ID(), 'custom_image_data', true)));
}
add_action('admin_enqueue_scripts', 'register_admin_script');


function save_custom_image($post_id)
{
	$is_autosave = wp_is_post_autosave($post_id);
	$is_revision = wp_is_post_revision($post_id);
	$is_valid_nonce = (isset($_POST['custom_image_nonce']) && wp_verify_nonce($_POST['custom_image_nonce'], basename(__FILE__)));

	// Exits script depending on save status
	if ($is_autosave || $is_revision || !$is_valid_nonce) {
		return;
	}

	if (isset($_POST['custom_image_data'])) {
		$image_data = json_decode(stripslashes($_POST['custom_image_data']));
		if (is_object($image_data[0])) {
			$image_data = array('id' => intval($image_data[0]->id), 'src' => esc_url_raw(
				$image_data[0]->url
			));
		} else {
			$image_data = [];
		}

		update_post_meta($post_id, 'custom_image_data', $image_data);
	}
	update_post_meta($post_id, "_Testimonial_name", sanitize_text_field($_POST["_Testimonial_name"]));
	update_post_meta($post_id, "_Testimonial_position", sanitize_text_field($_POST["_Testimonial_position"]));
	update_post_meta($post_id, "_Testimonial_comment", sanitize_text_field($_POST["_Testimonial_comment"]));
}
add_action('save_post', 'save_custom_image');


// generate shortcode
add_shortcode('cybTestimonials-list', 'cyb_Testimonials');
function cyb_Testimonials()
{
	global $post;
	$args = array(
		'post_type' => 'Testimonial',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	);
	$query = new WP_Query($args);
	$content = '<ul>';
	if ($query->have_posts()) :
		while ($query->have_posts()) : $query->the_post();
			$content .= '<li>' . get_post_meta(get_the_ID(), '_Testimonial_name', true) . '</li>';
			$content .= '<li>' . get_post_meta(get_the_ID(), '_Testimonial_position', true) . '</li>';
			$content .= '<li>' . get_post_meta(get_the_ID(), '_Testimonial_comment', true) . '</li>';
			$content .= '<li>' . get_post_meta(get_the_ID(), 'aw_custom_image', true) . '</li>';
		endwhile;
	else :
		_e('Sorry, nothing to display.', 'vicodemedia');
	endif;
	$content .= '</ul>';
	return $content;
}
/* Assign custom template to Testimonial post type*/
function load_Testimonial_template($template)
{
	global $post;
	if ('Testimonial' === $post->post_type && locate_template(array('single-Testimonial.php')) !== $template) {
		return plugin_dir_path(__FILE__) . 'single-Testimonial.php';
	}
	return $template;
}
add_filter('single_template', 'load_Testimonial_template');
