<?php
/**
 * Excerpt Settings
 *
 * @package      Raketsajt
 * @author       CodePilot

 **/
function cs_custom_excerpt_length() {
    return 30;
}
add_filter( 'excerpt_length', 'cs_custom_excerpt_length', 12 );

// In admin, manually trim words because excerpt_length filter doesn't work
function cs_custom_excerpt_in_editor( $excerpt, $post = null ) {
    // Only apply in admin/editor context
    if ( ! is_admin() || has_excerpt( $post ) ) {
        return $excerpt;
    }
    
    // Apply the custom length using wp_trim_words
    return wp_trim_words( $excerpt, cs_custom_excerpt_length(), '...' );
}
add_filter( 'get_the_excerpt', 'cs_custom_excerpt_in_editor', 15, 2 );

function short_excerpt($length = 15, $wrapper_start = '', $wrapper_end = '', $echo = true) {
    $output = '';

    // don't shorten the excerpt if it's manually set
    if (has_excerpt()) {
        $output = $wrapper_start . get_the_excerpt() . $wrapper_end;
    } else {
        if (!is_admin()) {
            // Temporarily adjust excerpt length for the frontend
            $filter = function () use ($length) { return $length; };
            add_filter('excerpt_length', $filter, 20);
            $output = $wrapper_start . get_the_excerpt() . $wrapper_end;
            remove_filter('excerpt_length', $filter, 20);
        } else {
            // In admin, manually trim words because excerpt_length filter doesn't work
            $output = $wrapper_start . wp_trim_words(get_the_excerpt(), $length, '...') . $wrapper_end;
        }
    }

    if ($echo) {
        echo $output;
        return;
    }
    return $output;
}