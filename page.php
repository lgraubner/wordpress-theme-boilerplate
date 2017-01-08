<?php
get_template_part('templates/page', 'header');
while (have_posts()): the_post();
?>
    <div class="inner">
      <div class="page-content">
          <?php the_content(); ?>
      </div>
    </div>
<?php
endwhile;
