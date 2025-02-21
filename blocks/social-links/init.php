<?php
/**
 * Social Links block
 *
 * @package      CoPiStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace CoPiStarter\Blocks\Social_Links;

/**
 * Site social links
 */
function classes( $block ){
  $classes = [];
  $classes[] = 'wp-block-cwp-social-links';
  $classes = cs_block_class( $block , $classes );
  return implode( ' ', $classes );
}
function styles( $block ){
  $styles = [];
  if( !empty( $block['style']['color']['gradient'] ) ){
    $styles[] = 'background: ' . $block['style']['color']['gradient'] . ';';
  }
  return implode( ' ', $styles );
}
