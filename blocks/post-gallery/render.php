<?php
/**
 * Post Gallery block
 *
 * @package      CoPiStarter
 * @author       CoPiStarter
 **/

use CoPiStarter\Blocks\Post_Gallery;
$classes = Post_Gallery\classes( $block );

$query_data = Post_Gallery\query( $block );

echo '<section '. $classes . ' '. block_gradient_style_tag( $block ) . '>';
  $query = new WP_Query($query_data);
  if($query->have_posts()) {
    while($query->have_posts()) {
      $query->the_post();
      $post_id = get_the_ID();
      get_template_part('partials/content/archive');
    }
  }
  wp_reset_postdata();
echo '</section>';

