<?php
/**
 * Block Demo block
 *
 * @package      CoPiStarter
 * @author       CodePilot
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use CoPiStarter\Blocks\Block_Demo;
use function CoPiStarter\ACF\{cs_print_acf_inline_edit};
$styles = block_gradient_style_tag( $block );
$classes = cs_block_classes( $block , ['cwp-block-demo']);
echo '<section '. $classes . ' '. $styles . '>';
  cs_print_acf_inline_edit('block_demo_title', $block['id'], 'h2');
  echo Block_Demo\block_content( $block );
echo '</section>';