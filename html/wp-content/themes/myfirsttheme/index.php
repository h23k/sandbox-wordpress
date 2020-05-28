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
<section>
  <div class="container">
    <h2><?php myfirstplugin_say_hello(); ?></h2>
    <div id="calendar"></div>
  </div>
</section>

<?php get_footer(); ?>
