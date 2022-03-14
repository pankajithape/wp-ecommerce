<?php


function estore_post_types()
{
  // event post type
  register_post_type('event', array(
    'capability_type' => 'event',
    'map_meta_cap' => true,
    'supports' => array('title', 'editor', 'excerpt'),
    'rewrite' => array('slug' => 'events'),
    'has_archive' => true,
    'public' => true,
    'show_in_rest' => true,
    'labels' => array(
      'name' => 'Events',
      'add_new_item' => 'Add New Event',
      'edit_item' => 'Edit Event',
      'all_items' => 'All Events',
      'singular_name' => 'Event'
    ),
    'menu_icon' => 'dashicons-calendar'
  ));


  // proferssor post type
  // register_post_type('testimonial', array(
  //   'supports' => array('title', 'editor', 'thumbnail'),
  //   'public' => true,
  //   'show_in_rest' => true,
  //   'labels' => array(
  //     'name' => 'testimonials',
  //     'add_new_item' => 'Add New testimonial',
  //     'edit_item' => 'Edit testimonial',
  //     'all_items' => 'All testimonial',
  //     'singular_name' => 'testimonial'
  //   ),
  //   'menu_icon' => 'dashicons-welcome-learn-more'
  // ));
}

add_action('init', 'estore_post_types');
