<?php
/**
 * Functions
 *
 * @package      CoPiStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
 **/

// Theme.
require_once get_template_directory() . '/inc/tha-theme-hooks.php';
require_once get_template_directory() . '/inc/layouts.php';
require_once get_template_directory() . '/inc/helper-functions.php';
require_once get_template_directory() . '/inc/wordpress-cleanup.php';
require_once get_template_directory() . '/inc/comments.php';
include_once get_template_directory() . '/inc/site-header.php';
include_once get_template_directory() . '/inc/site-footer.php';
include_once get_template_directory() . '/inc/archive-header.php';
include_once get_template_directory() . '/inc/archive-navigation.php';
include_once get_template_directory() . '/inc/template-tags.php';

// Functionality.
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/block-areas.php';
require_once get_template_directory() . '/inc/loop.php';
include_once get_template_directory() . '/inc/login-logo.php';
include_once get_template_directory() . '/inc/acf-theme-colors.php';

// Plugin Support.
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/wordpress-seo.php';
include_once get_template_directory() . '/inc/gravity-forms.php';
/**
 * Enqueue scripts and styles.
 */
function be_scripts() {

	wp_enqueue_script( 'theme-global', get_theme_file_uri( '/assets/js/global.js' ), [], filemtime( get_theme_file_path( '/assets/js/global.js' ) ), true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_enqueue_style( 'theme-style', get_theme_file_uri( '/assets/css/main.css' ), array(), filemtime( get_theme_file_path( '/assets/css/main.css' ) ) );

}
add_action( 'wp_enqueue_scripts', 'be_scripts' );

/**
 * Gutenberg scripts and styles
 */
function be_gutenberg_scripts() {
	wp_enqueue_script( 'theme-editor', get_theme_file_uri( '/assets/js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), filemtime( get_theme_file_path( '/assets/js/editor.js' ) ), true );
}
add_action( 'enqueue_block_editor_assets', 'be_gutenberg_scripts' );

if ( ! function_exists( 'be_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function be_setup() {
		/*
		 * Make theme available for translation.
		 */
		load_theme_textdomain( 'copistarter', get_template_directory() . '/languages' );

		// Editor Styles.
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor-style.css' );

		// Admin Bar Styling.
		add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Body open hook.
		add_theme_support( 'body-open' );

		// Remove block templates.
		remove_theme_support( 'block-templates' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 */
		$GLOBALS['content_width'] = apply_filters( 'be_content_width', 800 );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
			]
		);

		// Gutenberg.

		// -- Responsive embeds
		add_theme_support( 'responsive-embeds' );

		// add custom logo
		add_theme_support( 'custom-logo' );
	}

endif;
add_action( 'after_setup_theme', 'be_setup' );


/**
 * Template Hierarchy
 *
 * @param string $template Template.
 */
function be_template_hierarchy( $template ) {

	if ( is_search() ) {
		$template = get_query_template( 'archive' );
	}
	return $template;
}
add_filter( 'template_include', 'be_template_hierarchy' );

/**
 * Add gutenberg classes to blocks depending on the block supports settings
 */
function cs_block_class( $block , $classes = [] ) {
  
  // Alignment
  if($block['supports']['align'] && !empty($block['align'])) {
    $classes[] = 'align' . $block['align'];
  }
  if ($block['supports']['alignContent'] && !empty($block['align_content'])) {
    $classes[] = 'has-align-content';
    $classes[] = 'has-align-content-' . $block['align_content'];
  }
  if ($block['supports']['alignText'] && !empty($block['align_text'])) {
    $classes[] = 'has-text-align';
    $classes[] = 'has-text-align-' . $block['align_text'];
  }

  // Colors
  if($block['supports']['color']):
    $color_support = $block['supports']['color'];
    if($color_support['background'] && !empty($block['backgroundColor'])) {
      $classes[] = 'has-background';
      $classes[] = 'has-' . $block['backgroundColor'] . '-background-color';
    }
    if($color_support['text'] && !empty($block['textColor'])) {
      $classes[] = 'has-text-color';
      $classes[] = 'has-' . $block['textColor'] . '-color';
    }
    if($color_support['gradients'] && !empty($block['gradient'])) {
      $classes[] = 'has-background';
      // Add the specific gradient to the block with a style attribute
    }
    if($color_support['link'] && !empty($block['style']['elements']['link']['color']['text'])) {
      $link_color = str_replace('var:preset|color|', '', $block['style']['elements']['link']['color']['text']);
      $classes[] = 'has-link-color';
      $classes[] = 'has-' . $link_color . '-link-color';
    }
    if($color_support['link'] && !empty($block['style']['elements']['link'][':hover']['color']['text'])) {
      $link_hover_color = str_replace('var:preset|color|', '', $block['style']['elements']['link'][':hover']['color']['text']);
      $classes[] = 'has-link-hover-color';
      $classes[] = 'has-' . $link_hover_color . '-link-hover-color';
    }

    // Button
    if(!empty($block['style']['elements']['button']['color'])):
      $button_color = $block['style']['elements']['button']['color'];
      if(!empty($button_color['text'])):
        $classes[] = block_element_color($color_support['button'], $button_color['text'], 'button-color');
      endif;
      if(!empty($button_color['background'])):
        $classes[] = block_element_color($color_support['button'], $button_color['background'], 'button-background-color');
      endif;
    endif;

    // Heading
    if(!empty($block['style']['elements']['heading']['color'])):
      $heading_color = $block['style']['elements']['heading']['color'];
      if(!empty($heading_color['text'])):
        $classes[] = block_element_color($color_support['heading'], $heading_color['text'], 'heading-color');
      endif;
      if(!empty($heading_color['background'])):
        $classes[] = block_element_color($color_support['heading'], $heading_color['background'], 'heading-background-color', true);
      endif;
    endif;

    for($x = 1; $x <= 6; $x++) {
      if(!empty($block['style']['elements']['h' . $x]['color'])):
        $heading_color = $block['style']['elements']['h' . $x]['color'];
        if(!empty($heading_color['text'])):
          $classes[] = block_element_color($color_support['heading'], $heading_color['text'], 'h' . $x . '-color');
        endif;
        if(!empty($heading_color['background'])):
          $classes[] = block_element_color($color_support['heading'], $heading_color['background'], 'h' . $x . '-background-color', true);
        endif;
      endif;
    }
  endif;

  // Class Name
  if(!empty($block['className'])) {
    $classes[] = $block['className'];
  } 

  
	return $classes;
}
