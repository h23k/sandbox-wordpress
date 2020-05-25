<!DOCTYPE html>
<html lang="ja">
<head>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>">
</head>
<body>
  <header>
    <div class="site-logo">
      <div>
        <a class="site-logo__title" href="<?php esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a>
      </div>
      <p class="site-logo__description"><?php bloginfo('description'); ?></p>
    </div>
  </header>
