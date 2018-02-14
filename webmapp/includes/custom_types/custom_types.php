<?php



add_action('init', 'codex_cpt_init');
include_once "custom_type_voucher.php";
include_once "custom_route_taxonomies.php";
/**
 * Register a POI, TRACK, ROUTE, MAP, WEBMAPP CATEGORIES
 */
function codex_cpt_init() {

  $labels = array(
    'name' => _x('Webmapp categories', 'taxonomy general name', 'webmap_net7'),
    'singular_name' => _x('Webmapp category', 'taxonomy singular name', 'webmap_net7'),
    'search_items' => __('Search Webmapp categories', 'webmap_net7'),
    'all_items' => __('All Webmapp categories', 'webmap_net7'),
    'parent_item' => __('Parent Webmapp category', 'webmap_net7'),
    'parent_item_colon' => __('Parent Webmapp category:', 'webmap_net7'),
    'edit_item' => __('Edit Webmapp category', 'webmap_net7'),
    'update_item' => __('Update Webmapp category', 'webmap_net7'),
    'add_new_item' => __('Add New Webmapp category', 'webmap_net7'),
    'new_item_name' => __('New Webmapp category Name', 'webmap_net7'),
    'menu_name' => __('Webmapp category', 'webmap_net7'),
  );

  $args = array(
    'hierarchical' => TRUE,
    'labels' => $labels,
    'show_ui' => TRUE,
    'show_admin_column' => TRUE,
    'query_var' => TRUE,
    'show_in_rest' => TRUE,
  );

  register_taxonomy('webmapp_category', array(
    'poi',
    'track',
    'route',
    'map'
  ), $args);

  insert_taxonomy_values();


  $labels = array(
    'name' => _x('POI', 'post type general name', 'webmap_net7'),
    'singular_name' => _x('POI', 'post type singular name', 'webmap_net7'),
    'menu_name' => _x('POI', 'admin menu', 'webmap_net7'),
    'name_admin_bar' => _x('POI', 'add new on admin bar', 'webmap_net7'),
    'add_new' => _x('Add New', 'poi', 'webmap_net7'),
    'add_new_item' => __('Add New POI', 'webmap_net7'),
    'new_item' => __('New POI', 'webmap_net7'),
    'edit_item' => __('Edit POI', 'webmap_net7'),
    'view_item' => __('View POI', 'webmap_net7'),
    'all_items' => __('All POI', 'webmap_net7'),
    'search_items' => __('Search POI', 'webmap_net7'),
    'parent_item_colon' => __('Parent POI:', 'webmap_net7'),
    'not_found' => __('No POI found.', 'webmap_net7'),
    'not_found_in_trash' => __('No POI found in Trash.', 'webmap_net7')
  );

  $args = array(
    'labels' => $labels,
    'description' => __('Points of interests.', 'webmap_net7'),
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'show_in_menu' => TRUE,
    'show_in_rest' => TRUE,
    'query_var' => TRUE,
    'rewrite' => array('slug' => 'poi'),
    'capability_type' => 'post',
    'has_archive' => TRUE,
    'hierarchical' => FALSE,
    'menu_icon' => 'dashicons-location',
    'menu_position' => 33,
    'supports' => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'excerpt',
      'revisions'
    )
  );

  register_post_type('poi', $args);

  $labels = array(
    'name' => _x('Tracks', 'post type general name', 'webmap_net7'),
    'singular_name' => _x('Track', 'post type singular name', 'webmap_net7'),
    'menu_name' => _x('Tracks', 'admin menu', 'webmap_net7'),
    'name_admin_bar' => _x('Track', 'add new on admin bar', 'webmap_net7'),
    'add_new' => _x('Add New', 'track', 'webmap_net7'),
    'add_new_item' => __('Add New Track', 'webmap_net7'),
    'new_item' => __('New Track', 'webmap_net7'),
    'edit_item' => __('Edit Track', 'webmap_net7'),
    'view_item' => __('View Track', 'webmap_net7'),
    'all_items' => __('All Tracks', 'webmap_net7'),
    'search_items' => __('Search Tracks', 'webmap_net7'),
    'parent_item_colon' => __('Parent Tracks:', 'webmap_net7'),
    'not_found' => __('No Tracks found.', 'webmap_net7'),
    'not_found_in_trash' => __('No Tracks found in Trash.', 'webmap_net7')
  );

  $args = array(
    'labels' => $labels,
    'description' => __('Track in maps', 'webmap_net7'),
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'show_in_menu' => TRUE,
    'query_var' => TRUE,
    'rewrite' => array('slug' => 'track'),
    'capability_type' => 'post',
    'has_archive' => TRUE,
    'hierarchical' => FALSE,
    'menu_position' => 32,
    'show_in_rest' => TRUE,
    'menu_icon' => 'dashicons-location-alt',
    'supports' => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'excerpt',
      'revisions'
    )
  );

  register_post_type('track', $args);

  $labels = array(
    'name' => _x('Routes', 'post type general name', 'webmap_net7'),
    'singular_name' => _x('Route', 'post type singular name', 'webmap_net7'),
    'menu_name' => _x('Routes', 'admin menu', 'webmap_net7'),
    'name_admin_bar' => _x('Route', 'add new on admin bar', 'webmap_net7'),
    'add_new' => _x('Add New', 'route', 'webmap_net7'),
    'add_new_item' => __('Add New Route', 'webmap_net7'),
    'new_item' => __('New Route', 'webmap_net7'),
    'edit_item' => __('Edit Route', 'webmap_net7'),
    'view_item' => __('View Route', 'webmap_net7'),
    'all_items' => __('All Routes', 'webmap_net7'),
    'search_items' => __('Search Routes', 'webmap_net7'),
    'parent_item_colon' => __('Parent Routes:', 'webmap_net7'),
    'not_found' => __('No Routes found.', 'webmap_net7'),
    'not_found_in_trash' => __('No Routes found in Trash.', 'webmap_net7')
  );

  $args = array(
    'labels' => $labels,
    'description' => __('Routes', 'webmap_net7'),
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'show_in_menu' => TRUE,
    'query_var' => TRUE,
    'rewrite' => array('slug' => 'route'),
    'capability_type' => 'post',
    'has_archive' => TRUE,
    'hierarchical' => FALSE,
    'menu_position' => 31,
    'show_in_rest' => TRUE,
    'menu_icon' => 'dashicons-share',
    'supports' => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'excerpt',
      'revisions'
    )
  );

  register_post_type('route', $args);

  $labels = array(
    'name' => _x('Maps', 'post type general name', 'webmap_net7'),
    'singular_name' => _x('Map', 'post type singular name', 'webmap_net7'),
    'menu_name' => _x('Maps', 'admin menu', 'webmap_net7'),
    'name_admin_bar' => _x('POI', 'add new on admin bar', 'webmap_net7'),
    'add_new' => _x('Add New', 'poi', 'webmap_net7'),
    'add_new_item' => __('Add New Map', 'webmap_net7'),
    'new_item' => __('New Map', 'webmap_net7'),
    'edit_item' => __('Edit Map', 'webmap_net7'),
    'view_item' => __('View Map', 'webmap_net7'),
    'all_items' => __('All Maps', 'webmap_net7'),
    'search_items' => __('Search Map', 'webmap_net7'),
    'parent_item_colon' => __('Parent Map:', 'webmap_net7'),
    'not_found' => __('No Map found.', 'webmap_net7'),
    'not_found_in_trash' => __('No Map found in Trash.', 'webmap_net7')
  );

  $args = array(
    'labels' => $labels,
    'description' => __('Maps', 'webmap_net7'),
    'public' => TRUE,
    'publicly_queryable' => TRUE,
    'show_ui' => TRUE,
    'show_in_menu' => TRUE,
    'show_in_rest' => TRUE,
    'query_var' => TRUE,
    'rewrite' => array('slug' => 'map'),
    'capability_type' => 'post',
    'has_archive' => TRUE,
    'hierarchical' => FALSE,
    'menu_icon' => 'dashicons-format-image',
    'menu_position' => 30,
    'supports' => array(
      'title',
      'editor',
      'author',
      'thumbnail',
      'excerpt',
      'revisions'
    )
  );

  register_post_type('map', $args);
}

