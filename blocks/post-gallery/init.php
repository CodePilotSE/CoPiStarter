<?php
/**
 * Post Gallery block
 *
 * @package      CoPiStarter
 * @author       CoPiStarter
 **/

namespace CoPiStarter\Blocks\Post_Gallery;

/**
 * Site post gallery
 */

function query($block) {
  $args = [
    'post_type' => 'post',
    'orderby' => 'date',
    'order' => 'DESC',
    'post_status' => 'publish',
  ];
  
  if (function_exists('get_field')):
    switch(get_field('post_gallery_mode')):
      case 'latest-posts':
        $args['posts_per_page'] = get_field('post_gallery_count');
        break;
      case 'from-category':
        $args['tax_query'][] = array(
          'taxonomy' => 'category',
          'field' => 'term_id',
          'terms' => get_field('post_gallery_category'),
          'operator' => 'IN',
        );
        $args['posts_per_page'] = get_field('post_gallery_count');
        break;
      case 'selected-posts':
        $args['post__in'] = get_field('selected_posts');
        break;
    endswitch;
  endif;
  return $args;
}
