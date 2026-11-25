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
 * Use the field label as placeholder when none is set.
 *
 * Applies only to text-based field types. Existing placeholders are left unchanged.
 *
 * @param array $field ACF field settings.
 * @return array
 */
function default_placeholder_from_label( $field ) {
	$text_field_types = [ 'text', 'textarea', 'number', 'email', 'url', 'tel', 'password' ];

	if ( empty( $field['type'] ) || ! in_array( $field['type'], $text_field_types, true ) ) return $field;

if ( isset( $field['placeholder'] ) && '' !== (string) $field['placeholder'] ) return $field;

	$label = isset( $field['label'] ) ? trim( (string) $field['label'] ) : '';
	if ( '' === $label ) return $field;

	$field['placeholder'] = $label . '...';

	return $field;
}
add_filter( 'acf/load_field', __NAMESPACE__ . '\\default_placeholder_from_label' );

/**
 * Print ACF field with optional inline text editing support.
 *
 * @param string $field      Field name/key.
 * @param string $block_id   Block ID or empty for global.
 * @param string $element    HTML element to wrap value in.
 * @param array  $attributes Optional HTML attributes as key => value pairs.
 *
 * Usage:
 * cs_print_acf_inline_edit( 'field name', '$block_id', 'element to wrap the field in', 'array of attributes as "key => value" pairs' )
 *
 * @return void
 */
function cs_print_acf_inline_edit( $field, $block_id = '', $element = 'h2', $attributes = [] ) {
  $allowed_elements          = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div' ];
  $allowed_field_types       = [ 'text', 'textarea', 'number', 'email', 'url', 'tel', 'password' ];
  // ACF only enables contenteditable (and its ::before placeholder CSS) for these types.
  $contenteditable_types     = [ 'text', 'textarea' ];
  $field_object              = empty( $block_id ) ? get_field_object( $field ) : get_field_object( $field, $block_id );
  if ( ! is_array( $field_object ) ) return;

  $field_type = $field_object['type'] ?? '';
  if ( 
    empty( $field_type ) 
    || ! in_array( $field_type, $allowed_field_types, true ) 
    || ! in_array( $element, $allowed_elements, true )
  ){
    return;
  }

  if ( empty( $block_id ) ) {
    $field_value = \get_field( $field, false, true, true );
  } else {
    $field_value = \get_field( $field, $block_id, true, true );
  }

  // ACF auto-inline editing replaces empty values with a marker string in previews.
  if ( is_string( $field_value ) && 0 === strpos( $field_value, 'acf_auto_inline_editing_field_name_' ) ) {
    $field_value = '';
  }

  $is_preview = function_exists( 'acf_get_data' ) && (bool) acf_get_data( 'acf_doing_block_preview' );

  // Return early if we are in frontend and the field value is empty.
  if ( '' === $field_value && ! $is_preview ) return;

  if ( ! is_array( $attributes ) ) $attributes = [];

  if ( empty( $attributes['id'] ) ) {
    $attributes['id'] = sanitize_key( $field . '_' . $block_id );
  }

  $placeholder             = ! empty( $field_object['placeholder'] ) ? (string) $field_object['placeholder'] : '';
  $is_contenteditable      = in_array( $field_type, $contenteditable_types, true );
  $show_visual_placeholder = $is_preview && '' === $field_value && '' !== $placeholder && ! $is_contenteditable;

  if ( $show_visual_placeholder ) {
    $attributes['style'] = rtrim( trim( (string) ( $attributes['style'] ?? '' ) ), ';' ) . '; opacity:0.62;';
    $display_value       = esc_html( $placeholder );
  } else {
    $display_value = $field_value;
  }

  $attributes_strings = [];
  foreach ( $attributes as $key => $value ) {
    $attributes_strings[] = esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
  }
  $attributes_string = implode( ' ', $attributes_strings );

  $field_editing_attrs = '';
  if ( $is_contenteditable && function_exists( 'acf_inline_text_editing_attrs' ) ) {
    $inline_args = [];
    if ( '' !== $placeholder ) {
      $inline_args['placeholder'] = $placeholder;
    }
    $field_editing_attrs = acf_inline_text_editing_attrs( $field, $inline_args );
  }

  printf(
    '<%1$s %2$s %3$s>%4$s</%1$s>',
    esc_attr( $element ),
    $field_editing_attrs,
    $attributes_string,
    $display_value
  );
}
