<?php
// add_theme_support('post-thumbnails');


// カスタム投稿(my_news)
function create_my_news_post_type() {
  register_post_type('my_news',
    array(
      'labels' => array(
          'name' => 'ニュース'
        , 'singular_name' => 'ニュース'
        , 'all_items' => 'ニュース一覧'
      )
      , 'description' => 'ニュースの投稿'
      , 'public' => true
      , 'show_in_rest' => true
      , 'menu_position' => 5
      , 'supports' => array(
          'title'
        , 'editor'
        // , 'thumbnail'
        // , 'page-attribute'
        , 'revisions'
      )
      // , 'hierarchical' => true
      , 'taxonomies' => array(
          'my_brand'
        , 'my_location'
      )
      , 'has_archive' => true
    )
  );
}
add_action('init', 'create_my_news_post_type');


// カスタム分類(my_brand, my_location)
function create_my_brand_taxonomy() {
  register_taxonomy(
    'my_brand'
    , array('my_news')
    , array(
      'labels' => array(
          'name' => 'ブランド'
        , 'singular_name' => 'ブランド'
        , 'add_new_item' => '新規ブランドを追加'
        , 'edit_item' => 'ブランドの編集'
        , 'update_item' => 'ブランドを更新'
        , 'search_items' => 'ブランドを検索'
        , 'not_found' => 'ブランドが見つかりませんでした。'
      )
      , 'description' => 'ブランドの分類'
      , 'public' => true
      , 'show_in_rest' => true
      , 'show_admin_column' => true
    )
  );

  register_taxonomy(
    'my_location'
    , array('my_news')
    , array(
      'labels' => array(
          'name' => '地域'
        , 'singular_name' => '地域'
        , 'add_new_item' => '新規地域を追加'
        , 'edit_item' => '地域の編集'
        , 'update_item' => '地域を更新'
        , 'parent_item' => '親地域'
        , 'search_items' => '地域を検索'
        , 'not_found' => '地域が見つかりませんでした。'
      )
      , 'description' => '地域の分類'
      , 'public' => true
      , 'show_in_rest' => true
      , 'show_admin_column' => true
      , 'hierarchical' => true
    )
  );
}
add_action('init', 'create_my_brand_taxonomy', 0);
// $taxonomies = get_taxonomies();
// foreach ($taxonomies as $taxonomiy) {
//   error_log($taxonomiy);
// }


// カスタム項目
function create_my_news_fields() {
  add_meta_box(
      'my_copy_field'
    , 'キャッチコピー'
    , 'insert_my_copy_fields'
    , 'my_news'
    , 'normal'
  );
}
function insert_my_copy_fields() {
  global $post;

  // echo '<div class="wp-block editor-post-title__block">';
  echo '<textarea class="" name="my_copy_field" placeholder="キャッチコピーを追加" rows="1" style="overflow:hidden; resize:none;">';
  echo get_post_meta($post->ID, 'my_copy_field', true);
  echo '</textarea>';
  // echo '</div>';
}
add_action('admin_menu', 'create_my_news_fields');

function save_my_news_fields($post_id) {
  if (!empty($_POST['my_copy_field'])) {
    update_post_meta($post_id, 'my_copy_field', $_POST['my_copy_field']);
  } else {
    delete_post_meta($post_id, 'my_copy_field');
  }
}
add_action('save_post', 'save_my_news_fields');
