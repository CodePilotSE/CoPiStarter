<?php
function copi_setup_image_size_options() {
  // ---------------------------------------------------------
  // Add new image sizes
  // ---------------------------------------------------------
  
  // X-Large image-size
  add_image_size( 'x-large', 1600, 0, false ); 

  // ---------------------------------------------------------
  // Update existing wp image sizes
  // ---------------------------------------------------------
  
  // Thumbnail image-size
  update_option( 'thumbnail_size_w', 200 );
  update_option( 'thumbnail_size_h', 200 );

  // Medium image-size
  update_option( 'medium_size_w', 1000 );
  update_option( 'medium_size_h', 0 );
  update_option( 'medium_size_crop', 0 );

  // Medium-large image-size
  update_option( 'medium_large_size_w', 800 );
  update_option( 'medium_large_size_h', 0 );
  update_option( 'medium_large_size_crop', 0 );

  // Large image-size
  update_option( 'large_size_w', 1200 );
  update_option( 'large_size_h', 0 );
  update_option( 'large_size_crop', 0 );

}
add_action( 'after_switch_theme', 'copi_setup_image_size_options' );

// ---------------------------------------------------------
// Add custom image sizes to the resolution list in the WordPress editor
// ---------------------------------------------------------

function custom_image_sizes_to_editor( $sizes ) {
  $sizes = array();
  $sizes['thumbnail']    = __( 'Liten', 'copistarter' );
  $sizes['medium']       = __( 'Medium', 'copistarter' );
  $sizes['medium_large'] = __( 'Mediumstor', 'copistarter' );
  $sizes['large']        = __( 'Stor', 'copistarter' );
  $sizes['x-large']      = __( 'Extra stor', 'copistarter' );
  $sizes['full']         = __( 'Full storlek', 'copistarter' );
  return $sizes;
}
add_filter( 'image_size_names_choose', 'custom_image_sizes_to_editor' );
