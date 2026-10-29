<?php
/**
 * Archive
 *
 * @package      CoPiStarter
 * @author       CodePilot AB
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Full width.
add_filter( 'be_page_layout', 'be_return_full_width_content' );

/**
 * Body Class
 *
 * @param array $classes Body classes.
 */
function be_archive_body_class( $classes ) {
	$classes[] = 'archive';
	return $classes;
}
add_filter( 'body_class', 'be_archive_body_class' );
function be_archive_content_before( ) {
  $page_id = get_option( 'page_for_posts' );
  if ( empty( $page_id ) || ! is_home() || get_query_var( 'paged' ) ) return;
  echo '<div class="block-area block-area-home">';
    $loop = new WP_Query( [ 'post_type' => 'page', 'p' => $page_id ] );
    while ( $loop->have_posts() ) {
      $loop->the_post();
      the_content();
    }
    wp_reset_postdata();
  echo '</div>';
}
add_filter( 'tha_content_before', 'be_archive_content_before' );

// Build the page.
require get_template_directory() . '/index.php';
