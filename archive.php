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
/**
 * Posts page introduction (inside <main>).
 */
function be_archive_content_top() {
	$page_id = get_option( 'page_for_posts' );
	if ( empty( $page_id ) || ! is_home() ) {
		return;
	}
	echo '<div class="block-area block-area-home">';
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Filtered through the_content.
	echo apply_filters( 'the_content', get_post_field( 'post_content', $page_id ) );
	echo '</div>';
}
add_action( 'tha_content_top', 'be_archive_content_top' );

/**
 * Open post listing grid wrapper.
 */
function be_archive_listing_open() {
	echo '<div class="archive-listing">';
}
add_action( 'tha_content_while_before', 'be_archive_listing_open' );

/**
 * Close post listing grid wrapper (after pagination).
 */
function be_archive_listing_close() {
	echo '</div>';
}
add_action( 'tha_content_while_after', 'be_archive_listing_close', 20 );

// Build the page.
require get_template_directory() . '/index.php';
