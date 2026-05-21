<?php
/**
 * Slash Edit
 *
 * @package      CoPiStarter
 * @author       CodePilot
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

function slash_edit_check() {
  if ( !is_admin() && isset($_SERVER['REQUEST_URI']) ) {
    $uri = wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
    if ( is_string($uri) && ( substr($uri, -5) === '/edit' || substr($uri, -6) === '/edit/' ) ) {
      return true;
    }
  }
  return false;
}
function slash_edit_check_login() {
  if ( !is_user_logged_in() ) {
    $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( $_SERVER['REQUEST_URI'] ) : '';
    wp_safe_redirect( wp_login_url( $request_uri ) );
    exit;
  }
  return true;
}
function slash_edit_get_url() {
  if ( !slash_edit_check()  && !isset($_SERVER['REQUEST_URI']) ) {
    return null;
  }
  $uri = wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ); 
  if (is_string($uri)) {
    if ( substr($uri, -6) === '/edit/' ) {
      return substr($uri, 0, -6);
    }
    elseif ( substr($uri, -5) === '/edit' ) {
      return substr($uri, 0, -5);
    }
  }
  return null;
}

function slash_edit_post($post_type, $post_slug) {
  
  if ( empty( $post_slug ) ) {
    return;
  }

  // Get the post by slug
  if ( $post = get_page_by_path( $post_slug, OBJECT, $post_type ) ) {
    $edit_link = get_edit_post_link( $post->ID, 'raw' );
    if ( !empty( $edit_link ) ) {
      wp_safe_redirect( $edit_link );
      exit;
    }
  }    
}

function slash_edit_term($taxonomy, $term_slug) {
  if ( empty( $term_slug ) ) {
    return;
  }
  
  if ( $term = get_term_by( 'slug', $term_slug, $taxonomy )) {
    $edit_link = get_edit_term_link( $term->term_id, $taxonomy, 'raw' );
    if ( !empty( $edit_link ) ) {
      wp_safe_redirect( $edit_link );
      exit;
    }
  }
}

function run_slash_edits() {
  if ( !slash_edit_check() ) {
    return;
  }
  slash_edit_check_login();
  $clean_path = slash_edit_get_url();
  // Loop through all post types and check if the current URL matches a post type
  $post_types = get_post_types();
  if (count($post_types) > 0) {
    // Get the post slug from the url
    $post_slug = trim($clean_path, '/');
    foreach ($post_types as $post_type) {
      slash_edit_post($post_type, $post_slug);
    }
  }
  $taxonomies = get_taxonomies();
  foreach ($taxonomies as $taxonomy) {
    $term_slug = trim($clean_path, '/'.$taxonomy.'/');
    // Check if the current URL matches a taxonomy term
    slash_edit_term($taxonomy, $term_slug);
  }

}
add_action('init', 'run_slash_edits');