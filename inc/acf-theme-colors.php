<?php
// Theme colors as acf options

function get_theme_color_palette(){
  $wp_settings = wp_get_global_settings();
  $theme_color_collection = $wp_settings['color']['palette']['theme'];
  return $theme_color_collection; 
}

// Fields that should have theme colors
$color_fields = array(
    'example-field',
);

foreach($color_fields as $single_field):
  add_filter('acf/load_field/name='.$single_field ,   __NAMESPACE__ . '\acf_dynamic_colors_load');
endforeach;


function acf_dynamic_colors_load( $field) {
  
  $colors = get_theme_color_palette();
  if( ! empty( $colors ) ) {

    // Default ignore colors
    $ignore_colors = array(
      'dark',
      'light',
      'none'
    );

    // Add colors that are ignored but should be added as exceptions for this field
    $exceptions_add = array(
      // 'example-field' => array(
      //   'dark'
      // ),
    );
    // Remove colors that are not ignored, but should be removed as exceptions for this field
    $exceptions_remove = array(
      // 'example-field' => array(
      // ),
    );

    foreach(   $colors as $color ) {
      // If color is not in ignore colors or is in exceptions for this field, add it to choices
      if( ( !empty($exceptions_add[$field['name']]) && in_array( $color['slug'], $exceptions_add[$field['name']] )) // allways add colors that are in exceptions_add
          || !in_array( $color['slug'], $ignore_colors ) && (!empty($exceptions_remove[$field['name']]) && !in_array( $color['slug'], $exceptions_remove[$field['name']] ) || empty($exceptions_remove[$field['name']]) )) { // allways remove colors that are in exceptions_remove
        $field['choices'][ $color['slug'] ] = $color['name'];
      }
    }

    // Add none to choices if it's not in ignore colors
    if( ! in_array( 'none', $ignore_colors ) && (!empty($exceptions_remove[$field['name']]) && !in_array( 'none', $exceptions_remove[$field['name']] ) 
        || empty($exceptions_remove[$field['name']])) || (!empty($exceptions_add[$field['name']]) && in_array( 'none', $exceptions_add[$field['name']] )) ) {
      $field['choices'][ 'none' ] = 'none';
    }

    // Add class to wrapper for styling
    $field['wrapper']['class'] = 'color-picker';
  }

  return $field;
}