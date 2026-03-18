<?php
/**
 * ACF Customizations
 *
 * @package      CoPiStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace CoPiStarter\ACF;

// Disable CPT and taxonomy functionality
add_filter( 'acf/settings/enable_post_types', '__return_false' );

// Don't output empty message on blocks
add_filter( 'acf/blocks/no_fields_assigned_message', '__return_empty_string' );

/**
 * Remove ACF admin menu
 */
function remove_acf_admin_menu() {
	if ( ! ( function_exists( 'wp_get_environment_type' ) && 'production' === wp_get_environment_type() ) ) {
		return;
	}

	$slug = 'edit.php?post_type=acf-field-group';
	remove_submenu_page( $slug, $slug );
	remove_submenu_page( $slug, 'post-new.php?post_type=acf-field-group' );
}
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_acf_admin_menu' );

/**
 * Register Options Page
 */
function register_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page(
			[
				'title'      => __( 'Site Options', 'copistarter' ),
				'capability' => 'manage_options',
			]
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_options_page' );

/**
 * Print ACF field with optional inline text editing support.
 *
 * @param string $field      Field name/key.
 * @param string $context    Context (e.g. block ID) or empty for global.
 * @param string $element    HTML element to wrap value in.
 * @param array  $attributes Optional HTML attributes as key => value pairs.
 *
 * Usage:
 * cs_print_acf_inline_edit( 'field name', 'current block id', 'element to wrap the field in', 'array of attributes as "key => value" pairs' )
 *
 * @return void
 */
function cs_print_acf_inline_edit( $field, $context = '', $element = 'h2', $attributes = [] ) {
	$allowed_elements     = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'span', 'div' ];
	$allowed_field_types  = [ 'text', 'textarea', 'number', 'email', 'url', 'tel' ];
	$field_object         = get_field_object( $field );
	$field_type           = $field_object['type'] ?? null;
	if ( ! $field_object || empty( $field_type ) || ( $field_type && ! in_array( $field_type, $allowed_field_types, true ) ) || ! in_array( $element, $allowed_elements, true ) ) {
		return;
	}

	if ( empty( $context ) ) {
		$field_value = \get_field( $field );
	} else {
		$field_value = \get_field( $field, $context );
	}

	$attributes_string = '';
  if( !empty( $attributes ) && is_array( $attributes ) ) {
    foreach ( $attributes as $key => $value ) {
      $attributes_string .= esc_attr( $key ) . '="' . esc_attr( $value ) . '" ';
    }
	}

	if ( function_exists( 'acf_inline_text_editing_attrs' ) ) {
		if ( empty( $context ) ) {
			$field_editing_attrs = acf_inline_text_editing_attrs( $field );
		} else {
			$field_editing_attrs = acf_inline_text_editing_attrs( $field, $context );
		}
		printf(
			'<%1$s %2$s %3$s>%4$s</%1$s>',
			esc_attr( $element ),
			$field_editing_attrs,
			$attributes_string,
			wp_kses_post( $field_value )
		);
	} else {
		printf(
			'<%1$s %2$s>%3$s</%1$s>',
			esc_attr( $element ),
			$attributes_string,
			wp_kses_post( $field_value )
		);
	}
}