<?php
// add_theme_support('post-thumbnails');


function create_my_post_type() {
  // カスタム投稿(my_qa)
  register_post_type('my_qa',
    array(
      'labels' => array(
          'name' => 'お問い合わせ'
        , 'singular_name' => 'お問い合わせ'
        , 'all_items' => 'お問い合わせ一覧'
      )
      , 'description' => 'お問い合わせの投稿'
      , 'public' => true
      , 'show_in_rest' => true
      , 'menu_position' => 5
      , 'supports' => array(
          'title'
        , 'editor'
        // , 'thumbnail'
        , 'page-attribute'
        , 'revisions'
      )
      , 'hierarchical' => true
      , 'taxonomies' => array(
          'my_category'
        , 'my_tag'
      )
      , 'has_archive' => true
    )
  );
}
add_action('init', 'create_my_post_type');


function create_my_taxonomy() {
  // カスタム分類(my_category)
  register_taxonomy(
    'my_category'
    , array('my_qa')
    , array(
      'labels' => array(
          'name' => 'カテゴリー'
        , 'singular_name' => 'カテゴリー'
        , 'add_new_item' => '新規カテゴリーを追加'
        , 'edit_item' => 'カテゴリーの編集'
        , 'update_item' => 'カテゴリーを更新'
        , 'parent_item' => '親カテゴリー'
        , 'search_items' => 'カテゴリーを検索'
        , 'not_found' => 'カテゴリーが見つかりませんでした。'
      )
      , 'description' => 'カテゴリーの分類'
      , 'public' => true
      , 'show_in_rest' => true
      , 'show_admin_column' => true
      , 'hierarchical' => true
    )
  );

  // カスタム分類(my_tag)
  register_taxonomy(
    'my_tag'
    , array('my_qa')
    , array(
      'labels' => array(
          'name' => 'タグ'
        , 'singular_name' => 'タグ'
        , 'add_new_item' => '新規タグを追加'
        , 'edit_item' => 'タグの編集'
        , 'update_item' => 'タグを更新'
        , 'search_items' => 'タグを検索'
        , 'not_found' => 'タグが見つかりませんでした。'
      )
      , 'description' => 'タグの分類'
      , 'public' => true
      , 'show_in_rest' => true
      , 'show_admin_column' => true
    )
  );
}
add_action('init', 'create_my_taxonomy', 0);
// $taxonomies = get_taxonomies();
// foreach ($taxonomies as $taxonomiy) {
//   error_log($taxonomiy);
// }


function create_my_fields() {
  // カスタム項目(my_status_field)
  add_meta_box(
      'my_user_field'
    , 'お問い合わせ者'
    , 'insert_my_user_fields'
    , 'my_qa'
    , 'normal'
  );

  // カスタム項目(my_status_field)
  add_meta_box(
      'my_status_field'
    , 'ステータス'
    , 'insert_my_status_fields'
    , 'my_qa'
    , 'normal'
  );
}

function insert_my_user_fields() {
  global $post;

  echo '<input type="text" class="" name="my_user_field" value="'. get_post_meta($post->ID, 'my_user_field', true) . '">';
}

function insert_my_status_fields() {
  global $post;
  $status = get_post_meta($post->ID, 'my_status_field', true);

  $checked = array('', '', '');
  switch ($status) {
    case 'hang':
      $checked[1] = ' checked';
      break;
    case 'fixed':
      $checked[2] = ' checked';
      break;
    default:
      $checked[0] = ' checked';
      break;
  }
  // error_log('my_status: ' . $status . ' => ' . print_r($checked, true));

  echo '<label><input type="radio" class="" name="my_status_field" value="unans"' . $checked[0] . '>未回答</label>';
  echo '<label><input type="radio" class="" name="my_status_field" value="hang"' . $checked[1] . '>未解決</label>';
  echo '<label><input type="radio" class="" name="my_status_field" value="fixed"' . $checked[2] . '>解決済</label>';
}
add_action('admin_menu', 'create_my_fields');

function save_my_status_fields($post_id) {
  if (!empty($_POST['my_status_field'])) {
    update_post_meta($post_id, 'my_status_field', $_POST['my_status_field']);
  } else {
    delete_post_meta($post_id, 'my_status_field');
  }
}
add_action('save_post', 'save_my_status_fields');
