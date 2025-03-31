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
$styles = Block_Demo\styles( $block );
?>
<section class="<?= $classes; ?>" <?= !empty( $styles ) ? 'style="' . $styles . '"' : ''; ?>>
  <?php 
  echo 'this is just a placeholder block'; 
  echo 'remember to add replace the "block demo" block name with the actual block name'; 
  ?>
</section>