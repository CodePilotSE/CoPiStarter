<?php
/**
 * Add gutenberg classes to blocks depending on the block supports settings
 */

function block_element_color($color_support, $color, $element, $classes) {
  if(!$color_support) {
    return $classes;
  }
  if(!empty($color)):
    if(str_contains($color, 'var:preset|color|')) {
      $color = str_replace('var:preset|color|', '', $color);
    }
    if(!empty($element) && !empty($color)) {
    $classes[] = 'has-' . $element . '-color';
    $classes[] = 'has-' . $color . '-' . $element . '-color';
    }
  endif;
  return $classes;
}

function cs_block_class( $block , $classes = [] ) {
  
  // Alignment
  if($block['supports']['align'] && !empty($block['align'])) {
    $classes[] = 'align' . $block['align'];
  }
  if ($block['supports']['alignContent'] && !empty($block['align_content'])) {
    $classes[] = 'has-align-content';
    $classes[] = 'has-align-content-' . $block['align_content'];
  }
  if ($block['supports']['alignText'] && !empty($block['align_text'])) {
    $classes[] = 'has-text-align';
    $classes[] = 'has-text-align-' . $block['align_text'];
  }

  // Colors
  if($block['supports']['color']):
    $color_support = $block['supports']['color'];
    if($color_support['background'] && !empty($block['backgroundColor'])) {
      $classes[] = 'has-background';
      $classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
    }
    if($color_support['text'] && !empty($block['textColor'])) {
      $classes[] = 'has-text-color';
      $classes[] = 'has-' . $block['textColor'] . '-color';
    }
    if(!empty($color_support['gradients']) && !empty($block['gradient'])) {
      $classes[] = 'has-background';
      // Add the specific gradient to the block with a style attribute
    }
    if($color_support['link'] && !empty($block['linkColor'])) {
      $classes[] = 'has-link-color';
      $classes[] = 'has-' . $block['linkColor'] . '-link-color';
    }
    // Button
    if(!empty($block['style']['elements']['button']['color'])):
      $button_color = $block['style']['elements']['button']['color'];
      $classes = block_element_color($color_support['button'] ?? false, $button_color['text'], 'button', $classes);
      $classes = block_element_color($color_support['button'] ?? false, $button_color['background'], 'button-background', $classes);
    endif;

    // Heading
    if(!empty($block['style']['elements']['heading']['color'])):
      $heading_color = $block['style']['elements']['heading']['color'];
      $classes = block_element_color($color_support['heading'] ?? false, $heading_color['text'], 'heading', $classes);
      $classes = block_element_color($color_support['heading'] ?? false, $heading_color['background'], 'heading-background', $classes);
    endif;

    for($x = 1; $x <= 6; $x++) {
      if(!empty($block['style']['elements']['h' . $x]['color'])):
        $heading_color = $block['style']['elements']['h' . $x]['color'];
        $classes = block_element_color($color_support['heading'] ?? false, $heading_color['text'], 'h' . $x, $classes);
        $classes = block_element_color($color_support['heading'] ?? false, $heading_color['background'], 'h' . $x . '-background', $classes);
      endif;
    }
  endif;

  // Class Name
  if(!empty($block['className'])) {
    $classes[] = $block['className'];
  } 

  
	return $classes;
}
