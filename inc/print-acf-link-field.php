<?php
/**
 * Print ACF Link Field
 *
 * @param string $field The field name or field key.
 * @param string $post_id The ID of the post/ page where the field is located.
 * @param string $param_title_value The text value to display. Will override the field title if set.
 * @param string $text_wrapper_element The text wrapper element.
 * @return void
 * @example cs_print_acf_link_field( 'field_69d5144cc6ccf', get_the_ID(), 'This is a custom title', 'span' );
 */
function cs_print_acf_link_field( $field, $post_id = false, $param_title_value = '', $text_wrapper_element = '' ) {
  if ( empty( $field ) ) return;
  if ( empty( $post_id ) ){
    $field_object = get_field_object( $field );
  } else {
    $field_object = get_field_object( $field, $post_id );
  }
  
  if ( empty( $field_object['type'] ) || $field_object['type'] !== 'link' ) return;
  
  $field_value = $field_object['value'];
  $return_format = $field_object['return_format'] ?? '';
  $link = '';
  $title = '';
  
  if ( $return_format === 'array' ) :
    // If the field is an array, check if the url is set and if not, return
    if ( empty( $field_value['url'] ) ) return;
    $link = $field_value['url'];
    $acf_field_title_value = $field_value['title'];
  else :
    $link = $field_value;
    $acf_field_title_value = $field_value;
  endif;
  
  empty( $param_title_value ) ? $title_value = $acf_field_title_value : $title_value = $param_title_value;

  $allowed_wrapper_elements = [ 'span', 'div', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'strong', 'em', 'b', 'i' ];
  $wrapper_open = '';
  $wrapper_close = '';
  if ( !empty( $text_wrapper_element ) && in_array( $text_wrapper_element, $allowed_wrapper_elements ) ) {
    $wrapper_open = '<' . $text_wrapper_element . '>';
    $wrapper_close = '</' . $text_wrapper_element . '>';
  }
  
  printf( '<a href="%1$s"> %2$s%3$s%4$s</a>', $link, $wrapper_open, $title_value, $wrapper_close );
}