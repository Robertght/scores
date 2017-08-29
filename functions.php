<?php
/**
 * Boilerplate functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Boilerplate
 * @since Boilerplate 1.0
 */

/**
 * First the Pixelgrade Components.
 */
require get_template_directory() . '/components/power-up.php';

if ( ! function_exists( 'boilerplate_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function boilerplate_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on boilerplate, use a find and replace
		 * to change 'boilerplate' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'boilerplate', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/**
		 * Add image sizes used by theme.
		 */
		// Used for blog archive(the height is flexible)
		add_image_size( 'boilerplate-card-image', 450, 9999, false );
		// Used for sliders(fixed height)
		add_image_size( 'boilerplate-slide-image', 9999, 800, false );
		// Used for hero image
		add_image_size( 'boilerplate-hero-image', 2700, 9999, false );

		/**
		 * Add theme support for site logo
		 *
		 * First, it's the image size we want to use for the logo thumbnails
		 * Second, the 2 classes we want to use for the "Display Header Text" Customizer logic
		 */
		add_theme_support( 'custom-logo', apply_filters( 'boilerplate_header_site_logo', array(
			'height'      => 600,
			'width'       => 1360,
			'flex-height' => true,
			'flex-width'  => true,
			'header-text' => array(
				'site-title',
				'site-description-text',
			)
		) ) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		/**
		 * Remove themes' post formats support
		 */
		remove_theme_support( 'post-formats' );

		add_editor_style(
			array(
				'editor-style.css',
				boilerplate_woodfordbourne_font_url(),
				boilerplate_google_fonts_url()
			)
		);

		add_theme_support( 'pixelgrade_care', array(
				'support_url'   => 'https://pixelgrade.com/docs/boilerplate/',
				'changelog_url' => 'https://wupdates.com/boilerplate-changelog',
			)
		);
	}
}
add_action( 'after_setup_theme', 'boilerplate_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function boilerplate_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'boilerplate_content_width', 640 );
}
add_action( 'after_setup_theme', 'boilerplate_content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function boilerplate_scripts() {
	$theme = wp_get_theme();

	/* The main theme stylesheet */
	if ( !is_rtl() ) {
		wp_enqueue_style( 'boilerplate-style', get_stylesheet_uri(), array(), $theme->get( 'Version' ) );
	}

	/* Default Self-hosted Fonts */
	wp_enqueue_style( 'boilerplate-fonts-woodfordbourne', boilerplate_woodfordbourne_font_url() );
	/* Default Google Fonts */
	wp_enqueue_style( 'boilerplate-google-fonts', boilerplate_google_fonts_url() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	/* Scripts */
	wp_enqueue_script( 'boilerplate-navigation', pixelgrade_get_theme_file_uri( '/js/navigation.js' ), array(), '20160915', true );
	wp_enqueue_script( 'boilerplate-skip-link-focus-fix', pixelgrade_get_theme_file_uri( '/js/skip-link-focus-fix.js' ), array(), '20160915', true );

	// The main script
	wp_enqueue_script( 'boilerplate-main-scripts', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'imagesloaded', 'masonry', 'rellax' ), $theme->get( 'Version' ), true );

	$translation_array = array(
		'ajaxurl'      => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'boilerplate-main-scripts', 'boilerplate_js_strings', $translation_array );

}
add_action( 'wp_enqueue_scripts', 'boilerplate_scripts' );

function boilerplate_load_wp_admin_style() {
	wp_register_style( 'boilerplate_wp_admin_css', get_template_directory_uri() . '/admin-style.css', false, '1.0.0' );
	wp_enqueue_style( 'boilerplate_wp_admin_css' );
}
add_action( 'admin_enqueue_scripts', 'boilerplate_load_wp_admin_style' );

function boilerplate_inline_performance_script() {
	echo '';
}
add_action('wp_head', 'boilerplate_inline_performance_script');

/**
 * Custom template tags for this theme.
 */
require pixelgrade_get_parent_theme_file_path( '/inc/template-tags.php' );

/**
 * Custom functions that act independently of the theme templates.
 */
require pixelgrade_get_parent_theme_file_path( '/inc/extras.php' );

/**
 * Sidebars and custom widgets
 */
require pixelgrade_get_parent_theme_file_path( '/inc/widgets.php' );

/**
 * Customizer additions.
 */
require pixelgrade_get_parent_theme_file_path( '/inc/customizer.php' );

/**
 * Load Recommended/Required plugins notification
 */
require pixelgrade_get_parent_theme_file_path( '/inc/required-plugins/required-plugins.php' );

/**
 * Various plugins integrations.
 */
require pixelgrade_get_parent_theme_file_path( '/inc/integrations.php' );

/**
 * Customization for the used Pixelgrade Components
 */
require pixelgrade_get_parent_theme_file_path( '/inc/components.php' );

/* Automagical updates */
// Add here the WUpdates code
