<?php get_header(); ?>

<div class="hero">
  <h1><?php bloginfo('name'); ?></h1>
  <p><?php bloginfo('description'); ?></p>
</div>

<?php if (have_posts()) : ?>
<section>
  <div class="header">
    <h2>投稿</h2>
  </div>
  <div class="content">
  <?php while (have_posts()) : ?>
    <?php the_post(); ?>
    <div class="post">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </div>
  <?php endwhile; ?>
  </div>
</section>
<?php endif; ?>

<?php
$args = array(
  'post_type' => 'my_qa'
);
$qa_posts = get_posts($args);
?>
<?php if ($qa_posts) : ?>
<section>
  <div class="header">
    <h2>お問い合わせ</h2>
  </div>
  <div class="content">
  <?php foreach ($qa_posts as $post) : ?>
    <?php setup_postdata($post); ?>
    <div class="post">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </div>
  <?php endforeach; ?>
  </div>
</section>
<?php endif; ?>
<section>
  <div class="container">
    <h2><?php myfirstplugin_say_hello(); ?></h2>
    <div id="calendar"></div>
  </div>
</section>
<section>
  <div class="container">
    <h2><?php myfirstplugin_say_hello(); ?></h2>
    <div id="calendar"></div>
  </div>
</section>

<?php get_footer(); ?>
