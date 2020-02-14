<?php
/**
 * WPTribu.org functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

function setup_theme() {
	// Add Title tag support.
	add_theme_support( 'title-tag' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Don't include Adjacent Posts functionality.
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'header'  => esc_html__( 'Header', 'wptribu' ),
		'primary' => esc_html__( 'Primary', 'wptribu' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_theme' );

/**
 * Enqueue styles.
 *
 * @since 1.0.0
 */
function styles() {
    wp_enqueue_style(
        'wptribu',
        get_theme_file_uri( '/css/style.css' ),
        array(
            'dashicons',
            'open-sans'
        ),
        '1.0.0'
    );
    wp_style_add_data( 'wptribu', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\styles', 9 );

/**
 * Set the separator for the document title.
 *
 * @return string Document title separator.
 */
function document_title_separator() {
	return '&#124;';
}
add_filter( 'document_title_separator', __NAMESPACE__ . '\document_title_separator' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wporg_content_width', 612 );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\content_width', 0 );

/**
 * Enqueue scripts and styles.
 */
function scripts() {
	$suffix       = '.min';
    $script_debug = false;

    if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
        $suffix       = '';
        $script_debug = true;
    }

	// Concatenates core scripts when possible.
	if ( ! $script_debug ) {
		$GLOBALS['concatenate_scripts'] = true;
    }

	// phpcs:ignore Squiz.PHP.CommentedOutCode.Found, Squiz.Commenting.InlineComment.InvalidEndChar
	wp_enqueue_script(
		'wptribu-navigation',
		get_template_directory_uri() . "/js/navigation$suffix.js",
		array(),
		'1.0.0',
		true
	);

	wp_enqueue_script(
		'wptribu-plugins-skip-link-focus-fix',
		get_template_directory_uri() . "/js/skip-link-focus-fix$suffix.js",
		array(),
		'1.0.0',
		true
	);

	if ( ! is_front_page() && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts', 10 );

/**
 * Extends the default WordPress body classes.
 *
 * Adds classes to make it easier to target specific pages.
 *
 * @since 1.0.0
 *
 * @param array $classes Body classes.
 * @return array
 */
function body_classes( $classes = array() ) {
	if ( is_page() ) {
        $page = get_queried_object();

        if ( preg_match( '/page-about-hero/', get_page_template_slug( $page ) ) ) {
            $classes[] = 'page-about';
        }
    }

	return array_unique( $classes );
}
add_filter( 'body_class', __NAMESPACE__ . '\body_classes' );

function page_has_parent() {
	if ( ! is_page() ) {
		return false;
	}

	$page = get_queried_object();

	return isset( $page->post_parent ) ? !! $page->post_parent : false;
}

function get_page_parent( $page = null ) {
	if ( is_null( $page ) ) {
		$page = get_post();
	}

	if ( ! page_has_parent() ) {
		return $page;
	}

	$page = get_post( $page->post_parent );

	if ( ! $page || is_wp_error( $page ) ) {
		return false;
	}

	return $page;
}

function page_menu() {
	$page_menu = wp_page_menu( array( 'child_of' => get_page_parent()->ID, 'echo' => false ) );

	if ( preg_match( '/<li.*current_page_item[^>]*>(.*?)<\/li>/', $page_menu, $match ) ) {
		$page_menu = str_replace(
			$match[1],
			str_replace( 'href', 'class="active" href', $match[1] ),
			$page_menu
		);
	}

	echo $page_menu;
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param \WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', __NAMESPACE__ . '\customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function customize_preview_js() {
	wp_enqueue_script(
		'wptribu_plugins_customizer',
		get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ),
		'1.0.0',
		true
	);
}
add_action( 'customize_preview_init', __NAMESPACE__ . '\customize_preview_js' );

/**
 * Custom template tags.
 */
require_once get_theme_file_path( '/inc/template-tags.php' );
