<?php
// 親cssを設定
function theme_enqueue_styles() {
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

// 投稿者アーカイブを無効
add_filter('author_rewrite_rules', '__return_empty_array');

function disable_author_archive() {
  if ((array_key_exists('author', $_GET) && $_GET['author'])
       || preg_match('#/author/.+#', $_SERVER['REQUEST_URI'])) {
    wp_redirect(home_url('/404.php'));
    exit;
  }
}
add_action('init', 'disable_author_archive');

/**
 * REQUIRED FILES
 * Include required files.
 */
require get_stylesheet_directory() . '/inc/template-tags.php';
