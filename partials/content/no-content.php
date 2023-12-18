<?php
/**
 * 404 / No Results partial
 *
 * @package      CoPiStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use CoPiStarter\Block_Areas;

echo '<section class="no-results not-found">';

echo '<header class="entry-header"><h1 class="entry-title">' . esc_html__( 'Nothing Found', 'copistarter' ) . '</h1></header>';
echo '<div class="entry-content">';

if ( is_search() ) {

	echo '<p>' . esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'copistarter' ) . '</p>';
	get_search_form();

} else {

	$output = Block_Areas\show( '404', $echo = false );

	if ( empty( $output ) ) {
		echo '<p>' . esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'copistarter' ) . '</p>';
		get_search_form();
	} else {
		echo $output;
	}
}

echo '</div>';
echo '</section>';
