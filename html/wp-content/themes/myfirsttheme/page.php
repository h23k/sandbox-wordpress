<?php
/**
  * Template Name: static page
  */
?>
<?php get_header(); ?>

<div class="container">
<?php if (have_posts()) : ?>
  <?php while (have_posts()) : ?>
    <?php the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <div>
      <?php the_content(); ?>
    </div>
  <?php endwhile ?>
<?php endif ?>
</div>

<?php get_footer(); ?>
