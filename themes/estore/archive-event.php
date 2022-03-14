<?php
  get_header();
 
  ?>
  
    <div class="container container--narrow page-section">
      <?php
        while(have_posts()){
          the_post(); 
          get_template_part('template-parts/content-event');
        }
        echo paginate_links();
      ?>
      <hr class="section-break">
   

    </div>
 <?php get_footer();
?>