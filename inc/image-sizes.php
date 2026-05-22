<?php

// Add new image sizes ---------------------------------------------------------
// X-Large image-size
add_image_size( 'x-large', 1600, 0, true ); 

// Update existing wp image sizes ---------------------------------------------------------
// Thumbnail image-size
update_option( 'thumbnail_size_w', 200 );
update_option( 'thumbnail_size_h', 200 );

// Medium image-size
update_option( 'medium_size_w', 400 );
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

// Add custom image sizes to the resolution list in the WordPress editor
function custom_image_sizes_to_editor( ) {
 
    $sizes['thumbnail']    = 'Liten';
    $sizes['medium']       = 'Medium';
    $sizes['medium_large'] = 'Mediumstor';
    $sizes['large']        = 'Stor';
    $sizes['x-large']      = 'Extra stor';
    $sizes['full']         = 'Full storlek';
  return $sizes;
}
add_filter( 'image_size_names_choose', 'custom_image_sizes_to_editor' );
