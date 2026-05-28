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
function slash_edit_get_url() {
  if ( !slash_edit_check() || !isset($_SERVER['REQUEST_URI']) ) {
    return null;
  }
  $uri = wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ); 
  if (is_string($uri)) {
    // If the URL is /edit or /edit/, redirect to the home page edit link
    if ($uri === '/edit' || $uri === '/edit/') {
      if ( get_option( 'show_on_front' ) === 'page' ) {
        $home_page_id = (int) get_option( 'page_on_front' );
        if ( $home_page_id) {
          $home_page = get_post( $home_page_id );
          if ( $home_page instanceof WP_Post ) {
            $home_page_edit_link = get_edit_post_link( $home_page_id, 'raw' );
            if ( empty( $home_page_edit_link ) ) {
              $home_page_edit_link = admin_url( 'post.php?post=' . $home_page_id . '&action=edit' );
            }
            wp_safe_redirect( $home_page_edit_link );
            exit;
          }
        }
      }
      return null;
    }
    if ( substr($uri, -6) === '/edit/' ) {
      return substr($uri, 0, -6);
    }
    elseif ( substr($uri, -5) === '/edit' ) {
      return substr($uri, 0, -5);
    }
  }
  return null;
}

function slash_edit_post( $clean_path ) {
  if ( empty( $clean_path ) ) {
    return;
  }

  $path = '/' . ltrim( $clean_path, '/' );
  $post_id = url_to_postid( home_url( $path ) );

  if ( ! $post_id && trailingslashit( $path ) !== $path ) {
    $post_id = url_to_postid( home_url( trailingslashit( $path ) ) );
  }

  if ( ! $post_id ) {
    return;
  }

  $edit_link = get_edit_post_link( $post_id, 'raw' );
  if ( empty( $edit_link ) ) {
    $edit_link = admin_url( 'post.php?post=' . $post_id . '&action=edit' );
  }
  if ( ! empty( $edit_link ) ) {
    wp_safe_redirect( $edit_link );
    exit;
  }
}

function slash_edit_term($taxonomy, $term_slug) {
  if ( empty( $term_slug ) ) {
    return;
  }
  
  if ( $term = get_term_by( 'slug', $term_slug, $taxonomy )) {
    $edit_link = get_edit_term_link( $term->term_id, $taxonomy );
    if ( !empty( $edit_link ) ) {
      wp_safe_redirect( $edit_link );
      exit;
    }
  }
}

function slash_edit_get_taxonomy_rewrite_base( $taxonomy ) {
  $taxonomy_obj = get_taxonomy( $taxonomy );
  if ( ! $taxonomy_obj instanceof WP_Taxonomy || ! $taxonomy_obj->rewrite ) {
    return null;
  }
  if ( ! empty( $taxonomy_obj->rewrite_slug ) ) {
    return trim( $taxonomy_obj->rewrite_slug, '/' );
  }
  $slug = $taxonomy_obj->rewrite['slug'] ?? '';
  if ( '' === $slug ) {
    if ( 'category' === $taxonomy ) {
      $slug = get_option( 'category_base' ) ?: 'category';
    } elseif ( 'post_tag' === $taxonomy ) {
      $slug = get_option( 'tag_base' ) ?: 'tag';
    } else {
      $slug = $taxonomy;
    }
  }
  return trim( $slug, '/' );
}

function run_slash_edits() {
  $clean_path = slash_edit_get_url();
  if ( empty( $clean_path ) ) return;
  slash_edit_post( $clean_path );
  $taxonomies = get_taxonomies();
  $path_segments = array_values( array_filter( explode( '/', (string) $clean_path ), 'strlen' ) );
  foreach ( $taxonomies as $taxonomy ) {
    $rewrite_base = slash_edit_get_taxonomy_rewrite_base( $taxonomy );
    if ( null === $rewrite_base || '' === $rewrite_base ) {
      continue;
    }
    $base_segments = array_values( array_filter( explode( '/', $rewrite_base ), 'strlen' ) );
    if ( empty( $path_segments ) || empty( $base_segments ) || count( $path_segments ) < count( $base_segments ) ) {
      continue;
    }
    if ( array_slice( $path_segments, 0, count( $base_segments ) ) !== $base_segments ) {
      continue;
    }
    $term_segments = array_slice( $path_segments, count( $base_segments ) );
    $term_slug = implode( '/', $term_segments );
    slash_edit_term( $taxonomy, $term_slug );
  }

}
add_action('init', 'run_slash_edits');