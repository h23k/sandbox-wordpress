<?php get_header(); ?>

<div class="hero">
  <h1><?php bloginfo('name'); ?></h1>
  <p><?php bloginfo('description'); ?></p>
</div>

<section>
  <div class="content">
  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : ?>
      <?php the_post(); ?>
      <div class="post">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
