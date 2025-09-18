<?php
/**
 * Add gutenberg classes to blocks depending on the block supports settings
 */

function cs_block_class( $block , $classes = [] ) {
  if (!empty($block['supports']['alignContent']) && $block['supports']['alignContent'] && !empty($block['align_content'])) {
    $classes[] = 'has-align-content';
    $classes[] = 'has-align-content-' . $block['align_content'];
  }
  if (!empty($block['supports']['alignText']) && $block['supports']['alignText'] && !empty($block['align_text'])) {
    $classes[] = 'has-text-align';
    $classes[] = 'has-text-align-' . $block['align_text'];
  }

	return $classes;
}

function block_props( $block ){
  $block_props = get_block_wrapper_attributes(['class' => implode( ' ', cs_block_class( $block ) )]);
  return !empty( $block_props ) ? $block_props : '';
}