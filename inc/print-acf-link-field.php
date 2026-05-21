<?php
/**
 * Print ACF Link Field
 *
 * @param string $field The field name or field key.
 * @param string $post_id The ID of the post/ page where the field is located.
 * @param string $link_text The text to display for the link. Will override the field title if set.
 * @param string $fallback_text The text to display if the field is empty.
 * @return void
 * @example cs_print_acf_link_field( 'field_69d5144cc6ccf', get_the_ID(), 'This is a custom title' , 'Läs mer' );
 */
function cs_print_acf_link_field( $field, $post_id = false, $link_text = '', $fallback_text = '' ) {
  if ( empty( $field ) ) return;
  if ( empty( $fallback_text ) ) {
    $fallback_text = __( 'Läs mer', 'copistarter' );
  }
  if ( empty( $post_id ) ){
    $field_object = get_field_object( $field );
  } else {
    $field_object = get_field_object( $field, $post_id );
  }
  
  if ( empty( $field_object['type'] ) || $field_object['type'] !== 'link' ) return;
  
  $field_value = $field_object['value'];
  $return_format = $field_object['return_format'] ?? '';
  $link = '';
  
  if ( $return_format === 'array' ) :
    // If the field is an array, check if the url is set and if not, return
    if ( empty( $field_value['url'] ) ) return;

    $link = $field_value['url'];
    if ( empty( $link_text ) ) :
      $link_text = !empty( $field_value['title'] ) ? $field_value['title'] : $fallback_text;
    endif;
  else :
    $link = $field_value;
    if ( empty( $link_text ) ) :
      $link_text = $fallback_text;
    endif;
  endif;
  
  if ( empty( $link_text ) ) :
    $link_text = $fallback_text;
  endif;

  printf( '<a href="%1$s"> %2$s</a>', esc_url( $link ), esc_html( $link_text ) );
}