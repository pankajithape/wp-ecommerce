<?php

/**
 * Plugin Name: myTestimonial Plugin
 * Description: This plugin will add a Custom Post Type for myTestimonials
 * Plugin URI: https://vicodemedia.com
 * Author: Victor Rusu
 * Version: 1
 **/
//* Don't access this file directly
defined('ABSPATH') or die();
/*------------------------------------*\
	Create Custom Post Types
\*------------------------------------*/
add_action('init', 'myTestimonial_post_type');
function myTestimonial_post_type()
{
  register_post_type('myTestimonial', array(
    'labels' => array(
      'name' => __('myTestimonials', 'vicodemedia'),
      'singular_name' => __('myTestimonial', 'vicodemedia'),
      'add_new' => __('Add New', 'vicodemedia'),
      'add_new_item' => __('Add New myTestimonial', 'vicodemedia'),
      'edit_item' => __('Edit myTestimonial', 'vicodemedia'),
      'new_item' => __('New myTestimonial', 'vicodemedia'),
      'view_item' => __('View myTestimonial', 'vicodemedia'),
      'view_items' => __('View myTestimonials', 'vicodemedia'),
      'search_items' => __('Search myTestimonials', 'vicodemedia'),
      'not_found' => __('No myTestimonials found.', 'vicodemedia'),
      'not_found_in_trash' => __('No myTestimonials found in trash.', 'vicodemedia'),
      'all_items' => __('All myTestimonials', 'vicodemedia'),
      'archives' => __('myTestimonial Archives', 'vicodemedia'),
      'insert_into_item' => __('Insert into myTestimonial', 'vicodemedia'),
      'uploaded_to_this_item' => __('Uploaded to this myTestimonial', 'vicodemedia'),
      'filter_items_list' => __('Filter myTestimonials list', 'vicodemedia'),
      'items_list_navigation' => __('myTestimonials list navigation', 'vicodemedia'),
      'items_list' => __('myTestimonials list', 'vicodemedia'),
      'item_published' => __('myTestimonial published.', 'vicodemedia'),
      'item_published_privately' => __('myTestimonial published privately.', 'vicodemedia'),
      'item_reverted_to_draft' => __('myTestimonial reverted to draft.', 'vicodemedia'),
      'item_scheduled' => __('myTestimonial scheduled.', 'vicodemedia'),
      'item_updated' => __('myTestimonial updated.', 'vicodemedia')
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
// add myTestimonial date field to myTestimonials post type
function add_post_meta_boxes()
{
  add_meta_box(
    "post_metadata_myTestimonials_post", // div id containing rendered fields
    "myTestimonial Name", // section heading displayed as text
    "post_meta_box_myTestimonials_post_name", // callback function to render fields
    "myTestimonial", // name of post type on which to render fields
    "normal", // location on the screen
    "low" // placement priority
  );
  add_meta_box(
    "post_metadata_myTestimonials_post_position", // div id containing rendered fields
    "myTestimonial Position", // section heading displayed as text
    "post_meta_box_myTestimonials_post_position", // callback function to render fields
    "myTestimonial", // position of post type on which to render fields
    "normal", // location on the screen
    "low" // placement priority
  );
  add_meta_box(
    "post_metadata_myTestimonials_post_comment", // div id containing rendered fields
    "myTestimonial comment", // section heading displayed as text
    "post_meta_box_myTestimonials_post_comment", // callback function to render fields
    "myTestimonial", // comment of post type on which to render fields
    "normal", // location on the screen
    "low" // placement priority
  );
  add_meta_box(
    'aw-meta-box',
    'Testimonial Profile image',
    'render_aw_meta_box',
    'myTestimonial',
    'normal',
    'low'
  );
}
add_action("admin_init", "add_post_meta_boxes");
// save field value
function save_post_meta_boxes($post_id)
{
  global $post;
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if (get_post_status($post->ID) === 'auto-draft') {
    return;
  }

  if (array_key_exists('aw_custom_image', $_POST)) {
    update_post_meta(
      $post_id,
      'aw_custom_image',
      $_POST['aw_custom_image']
    );
  }

  update_post_meta($post->ID, "_myTestimonial_name", sanitize_text_field($_POST["_myTestimonial_name"]));
  update_post_meta($post->ID, "_myTestimonial_position", sanitize_text_field($_POST["_myTestimonial_position"]));
  update_post_meta($post->ID, "_myTestimonial_comment", sanitize_text_field($_POST["_myTestimonial_comment"]));
  // update_post_meta($post->ID, "_myTestimonial_profile", sanitize_text_field($_POST["_myTestimonial_profile"]));
}
add_action('save_post', 'save_post_meta_boxes');
// callback function to render fields
function post_meta_box_myTestimonials_post_name()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $fieldDataName = $custom["_myTestimonial_name"][0];
  echo "<input type=\"name\" name=\"_myTestimonial_name\" value=\"" . $fieldDataName . "\" placeholder=\"myTestimonial name\">";
}
//callback function to render fields
function post_meta_box_myTestimonials_post_position()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $fieldDataPosition = $custom["_myTestimonial_position"][0];
  echo "<input type=\"text\" name=\"_myTestimonial_position\" value=\"" . $fieldDataPosition . "\" placeholder=\"myTestimonial Position\">";
}
// callback function to render fields
function post_meta_box_myTestimonials_post_comment()
{
  global $post;
  $custom = get_post_custom($post->ID);
  $fieldDatacomment = $custom["_myTestimonial_comment"][0];
  echo "<textarea type=\"textarea\"  name=\"_myTestimonial_comment\" placeholder=\"myTestimonial comment\" rows=\"8\" cols=\"150\">$fieldDatacomment</textarea>";
}
// callback function to render fields

function render_aw_meta_box($post)
{
  $image = get_post_meta($post->ID, 'aw_custom_image', true);

?>
  <table>
    <tr>
      <td><a href="#" class="aw_upload_image_button button button-secondary"><?php _e('Upload Image'); ?></a></td>
      <td><input type="text" name="aw_custom_image" id="aw_custom_image" value="<?php echo $image; ?>" /></td>
    </tr>
  </table>
<?php
}

function aw_include_script()
{

  if (!did_action('wp_enqueue_media')) {
    wp_enqueue_media();
  }

  wp_enqueue_script('awscript',   plugin_dir_url(__FILE__) . 'awscript.js', array('jquery'), null, false);
}
add_action('admin_enqueue_scripts', 'aw_include_script');

// generate shortcode
add_shortcode('myTestimonials-list', 'vm_myTestimonials');
function vm_myTestimonials()
{
  global $post;
  $args = array(
    'post_type' => 'myTestimonial',
    'post_status' => 'publish',
    'posts_per_page' => -1,
  );
  $query = new WP_Query($args);
  $content = '<ul>';
  if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();

      $testm_name = get_post_meta(get_the_ID(), '_myTestimonial_name', true);
      $testm_myTestimonial_position = get_post_meta(get_the_ID(), '_myTestimonial_position', true);
      $testm_myTestimonial_comment = get_post_meta(get_the_ID(), '_myTestimonial_comment', true);
      $aw_custom_image = get_post_meta(get_the_ID(), 'aw_custom_image', true);
      echo "<li>" . $testm_name . "</li>" . "<li>" . $testm_myTestimonial_position . "</li>" . "<li>" . $testm_myTestimonial_comment . "</li>" . "<li>" . $aw_custom_image . "</li>"  . "<br>";
    endwhile;
  else :
    _e('Sorry, nothing to display.', 'vicodemedia');
  endif;
  $content .= '</ul>';
  return $content;
}
/* Assign custom template to myTestimonial post type*/
function load_myTestimonial_template($template)
{
  global $post;
  if ('myTestimonial' === $post->post_type && locate_template(array('single-myTestimonial.php')) !== $template) {
    return plugin_dir_path(__FILE__) . 'single-myTestimonial.php';
  }
  return $template;
}
add_filter('single_template', 'load_myTestimonial_template');
