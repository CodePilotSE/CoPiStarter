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
  if ( !is_admin() && ( substr($_SERVER['REQUEST_URI'], -5) === '/edit' || substr($_SERVER['REQUEST_URI'], -6) === '/edit/' ) ) {

    return true;
  }
  return false;
}
function slash_edit_check_login() {
  if ( !is_user_logged_in() || !current_user_can( 'edit_posts' ) ) {
    wp_redirect( wp_login_url( $_SERVER['REQUEST_URI'] ) );
    exit;
  }
  return true;
}
function slash_edit_get_url() {
  if ( !slash_edit_check() ) {
    return null;
  }
  if ( substr($_SERVER['REQUEST_URI'], -6) === '/edit/' ) {
    return substr($_SERVER['REQUEST_URI'], 0, -6);
  }
  elseif ( substr($_SERVER['REQUEST_URI'], -5) === '/edit' ) {
    return substr($_SERVER['REQUEST_URI'], 0, -5);
  }
}

function slash_edit_post($post_type) {
  slash_edit_check_login();
  $clean_url = slash_edit_get_url();
  // Get the post slug from the url
  $post_slug = trim($clean_url, '/');

  // Get the post by slug
  if ( $post = get_page_by_path( $post_slug, OBJECT, $post_type ) ) {
    // Check if user can edit this specific post
    if ( !current_user_can( 'edit_post', $post->ID ) ) {
      wp_die( 'You do not have permission to edit this post.' );
    }
    
    $edit_link = get_edit_post_link( $post->ID, 'raw' );
    if ( !empty( $edit_link ) ) {
      wp_redirect( $edit_link );
      exit;
    }
  }    
}

function slash_edit_term($taxonomy) {
  slash_edit_check_login();
  $clean_url = slash_edit_get_url();
  $term_slug = trim($clean_url, '/'.$taxonomy.'/');
  if ( $term = get_term_by( 'slug', $term_slug, $taxonomy )) {
    // Check if user can edit this specific term
    if ( !current_user_can( 'edit_term', $term->term_id, $taxonomy ) ) {
      wp_die( 'You do not have permission to edit this term.' );
    }
    
    $edit_link = get_edit_term_link( $term->term_id, $taxonomy, 'raw' );
    if ( !empty( $edit_link ) ) {
      wp_redirect( $edit_link );
      exit;
    }
  }
}

function slash_edit_archive($taxonomy) {
  slash_edit_check_login();
  $clean_url = slash_edit_get_url();
  $term_slug = trim($clean_url, '/'.$taxonomy.'/');
  if ( $term = get_term_by( 'slug', $term_slug, $taxonomy )) {
    $edit_link = get_edit_term_link( $term->term_id, $taxonomy, 'raw' );
    if ( !empty( $edit_link ) ) {
      wp_redirect( $edit_link );
      exit;
    }  
  }
}

function run_slash_edits() {
  if ( !slash_edit_check() ) {
    return;
  }
  // Loop through all post types and check if the current URL matches a post type
  $post_types = get_post_types();
  if (count($post_types) > 0) {
    foreach ($post_types as $post_type) {
      slash_edit_post($post_type);
    }
  }
  $taxonomies = get_taxonomies();
  foreach ($taxonomies as $taxonomy) {
      // Check if the current URL matches a taxonomy term
      slash_edit_term($taxonomy);
  }

}
add_action('init', 'run_slash_edits');