<?php
//--------------------------------
// EDIT THIS FILE TO ADD/REMOVE COLOR THEMES FOR YOUR FIELDS
//--------------------------------

// Fields that should have theme colors, mapped to the color theme they allow
$color_fields = array(
 'block_demo_color' => 'main',
);

// Colors that should be added to a specific field even though its color
// theme leaves them out, keyed by field name
$exceptions_add = array(
  'block_demo_color' => array('white'),
);

// Colors that should be removed from a specific field, keyed by field name.
// Takes precedence over $exceptions_add.
$exceptions_remove = array(
  'block_demo_color' => array('secondary'),
);

//--------------------------------
// PALETTES
//--------------------------------

function get_theme_color_palette(){
  $wp_settings = wp_get_global_settings();
  $theme_color_collection = $wp_settings['color']['palette']['theme'];
  return $theme_color_collection; 
}
function get_theme_color_main_colors() {
  return array(
    'primary',
    'secondary',
  );
}

function get_theme_color_accent_colors() {
  return array(
    'white',
    'black',
  );
}

/**
 * Slugs allowed for a color theme, or null when the theme allows every color.
 *
 * @param string $theme Color theme: all, main, accent or main+accent.
 * @return array<string>|null
 */
function get_theme_color_theme_slugs( $theme = 'all' ) {
  $main = get_theme_color_main_colors();
  $accent = get_theme_color_accent_colors();

  $themes = array(
    'main'        => $main,
    'accent'      => $accent,
  );

  return $themes[ $theme ] ?? null;
}


//--------------------------------
// FUNCTIONS
//--------------------------------

/**
 * Register the theme color choices filter for a field.
 *
 * ACF only passes $field to acf/load_field, so the theme is bound in a closure.
 *
 * @param string $filter Full acf/load_field filter name.
 * @param string $theme  Color theme to limit the choices to.
 */
function add_theme_colors_to_field( $filter, $theme = 'all', $exceptions_add = array(), $exceptions_remove = array() ) {
  add_filter(
    $filter,
    function( $field ) use ( $theme, $exceptions_add, $exceptions_remove ) {
      return acf_dynamic_colors_load( $field, $theme, $exceptions_add, $exceptions_remove );
    }
  );
}

foreach ( $color_fields as $single_field => $single_field_theme ):
  add_theme_colors_to_field( 'acf/load_field/name=' . $single_field, $single_field_theme, $exceptions_add, $exceptions_remove );
endforeach;


function acf_dynamic_colors_load( $field, $theme = 'all', $exceptions_add, $exceptions_remove ) {
  
  $colors = get_theme_color_palette();
  if( ! empty( $colors ) ) {

    $field_name = $field['name'] ?? '';

    // Slugs allowed by the color theme, null when every color is allowed
    $theme_slugs = get_theme_color_theme_slugs( $theme );



    $added_colors = $exceptions_add[ $field_name ] ?? array();
    $removed_colors = $exceptions_remove[ $field_name ] ?? array();

    foreach ( $colors as $color ) {
      $slug = $color['slug'] ?? '';

      if ( '' === $slug || in_array( $slug, $removed_colors, true ) ) {
        continue;
      }

      $in_theme = ! is_array( $theme_slugs ) || in_array( $slug, $theme_slugs, true );

      if ( ! $in_theme && ! in_array( $slug, $added_colors, true ) ) {
        continue;
      }

      $field['choices'][ $slug ] = $color['name'];
    }

    $wrapper_class = $field['wrapper']['class'] ?? '';
    $field['wrapper']['class'] = trim( $wrapper_class . ' color-picker' );
  }

  return $field;
}