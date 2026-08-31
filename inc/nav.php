<?php
/**
 * Register nav menus
 */
function be_register_menus() {
	register_nav_menus(
		[
			'primary' => esc_html__( 'Huvudmeny', 'copistarter' ),
			'footer-menu' => esc_html__( 'Sidfotsmeny', 'copistarter' ),
		]
	);

}
add_action( 'after_setup_theme', 'be_register_menus' );