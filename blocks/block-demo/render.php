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

echo '<section '. block_props( $block ) . '>';
  echo 'this is just a placeholder block'; 
  echo 'remember to add replace the "block demo" block name with the actual block name'; 
echo '</section>';