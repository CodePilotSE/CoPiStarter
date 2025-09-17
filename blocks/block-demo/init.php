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
  $classes = cs_block_class( $block , ['wp-block-cwp-block-demo'] );

  // return classes
  if( !empty( $classes ) ){return 'class="'.implode( ' ', $classes ).'"';}
  return '';
}