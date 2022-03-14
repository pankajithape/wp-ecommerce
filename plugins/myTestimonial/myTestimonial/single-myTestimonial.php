<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <h2 class="post-title"><?php the_title(); ?></h2>
    <h2><?php echo get_post_meta('_myTestimonial_name'); ?></h2>
    <h2><?php echo get_post_meta('_myTestimonial_name'); ?></h2>
    <h2><?php echo get_post_meta('_myTestimonial_name'); ?></h2>
  <?php endwhile; ?>
<?php else : ?>
  <h1><?php _e('Sorry, nothing to display.', 'html5blank'); ?></h1>
<?php endif; ?>
<?php get_footer(); ?>