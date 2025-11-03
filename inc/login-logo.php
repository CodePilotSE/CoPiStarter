<?php
/**
 * Login Logo
 *
 * @package      CoPiStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

/**
 * Login Logo URL
 *
 * @param string $url URL.
 */
function be_login_header_url( $url ) {
	return esc_url( home_url() );
}
add_filter( 'login_headerurl', 'be_login_header_url' );
add_filter( 'login_headertext', '__return_empty_string' );

/**
 * Login Logo
 */
function be_login_logo() {
  $custom_logo_id = get_theme_mod( 'custom_logo' );
  $logo_url = '';
  if (file_exists( get_theme_file_path( '/assets/icons/logo/logo.svg' ) ) ) {
    $logo_url = get_template_directory_uri() . '/assets/icons/logo/logo.svg';
  }
  elseif ( !empty( $custom_logo_id ) ) {
    $logo_url = wp_get_attachment_image_url( $custom_logo_id , 'full' );
  } else {
    return;
  }
	?>
	<style>
		.login h1 a {
			background-image: url(<?php echo esc_url( $logo_url ); ?>);
			background-size: contain;
			width: 312px;
			height: 100px;
		}
	</style>
	<?php
}
add_action( 'login_head', 'be_login_logo' );
