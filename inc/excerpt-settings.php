<?php
/**
 * Excerpt Settings
 *
 * @package      CoPiStarter
 * @author       CodePilot

 **/
function cs_custom_excerpt_length() {
    return 30;
}
add_filter( 'excerpt_length', 'cs_custom_excerpt_length', 12 );

/**
 * Outputs or returns a shortened post excerpt with optional HTML wrappers.
 *
 * If a manual excerpt is set for the post, that excerpt is used as-is. Otherwise,
 * the excerpt length is temporarily adjusted on the frontend using the
 * `excerpt_length` filter, while in the admin area the excerpt is trimmed
 * manually with `wp_trim_words()`.
 *
 * @param int         $length        Number of words to include in the excerpt.
 * @param string      $wrapper_start HTML to prepend to the excerpt.
 * @param string      $wrapper_end   HTML to append to the excerpt.
 * @param bool        $echo          Whether to echo the excerpt. If false, the
 *                                   excerpt string is returned instead.
 *
 * @return string|void The excerpt string when `$echo` is false, otherwise void.
 */
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

function cs_short_excerpt($length = 15, $wrapper_start = '', $wrapper_end = '', $echo = true) {
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
        echo esc_html($output);
        return;
    }
    return $output;
}