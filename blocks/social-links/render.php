<?php
/**
 * Social Links block
 *
 * @package      CoPiStarter
 * @author       CodePilot
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

use CoPiStarter\Blocks\Social_Links;

$classes = Social_Links\classes( $block );
$styles = Social_Links\styles( $block );
?>
<section class="<?= $classes; ?>" <?= !empty( $styles ) ? 'style="' . $styles . '"' : ''; ?>>
  <?php 
  echo 'this is just a placeholder block'; 
  echo 'remember to add replace the "social links" block name with the actual block name'; 
  ?>
</section>