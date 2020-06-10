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

  <ul class="post-nav">
    <li><?php previous_post_link(); ?></li>
    <li><?php next_post_link(); ?></li>
  </ul>
</div>

<?php get_footer(); ?>
