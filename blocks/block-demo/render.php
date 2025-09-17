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

$classes = Block_Demo\classes( $block );
?>
<section <?= $classes; ?> <?= block_gradient_style_tag( $block ) ?>>
  <?php 
  echo 'this is just a placeholder block'; 
  echo 'remember to add replace the "block demo" block name with the actual block name'; 
  ?>
</section>