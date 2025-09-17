<?php
/**
 * Block Demo block
 *
 * @package      CoPiStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

namespace CoPiStarter\Blocks\Block_Demo;

/**
 * Block Demo
 */
function classes( $block ){
  // add classes
  $classes = ['wp-block-cwp-block-demo'];
  $classes = cs_block_class( $block , $classes );

  // return classes
  if( !empty( $classes ) ){return 'class="'.implode( ' ', $classes ).'"';}
  return '';
}
function styles( $block ){
  // add styles
  $styles = [];
  if( !empty( $block['style']['color']['gradient'] ) ){
    $styles[] = 'background: ' . $block['style']['color']['gradient'] . ';';
  }

  // return styles
  if( !empty( $styles ) ){return 'style="'.implode( ' ', $styles ).'"';}
  return '';
}
