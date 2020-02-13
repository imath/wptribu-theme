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
    register_nav_menus( array(
		'header'  => esc_html__( 'Header', 'wptribu' ),
		'primary' => esc_html__( 'Primary', 'wptribu' ),
	) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_theme' );

/**
 * Enqueue styles.
 *
 * @since 1.0.0
 */
function styles() {
    // Disable the parent theme scripts
    remove_action( 'wp_enqueue_scripts', 'WordPressdotorg\Theme\scripts' );

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

/**
 * Remove WPOrg parent's theme hooks.
 *
 * @since 1.0.0
 */
function remove_wporg_head_hook() {
    remove_action( 'wp_head', 'WordPressdotorg\Theme\hreflang_link_attributes' );
    remove_filter( 'style_loader_src', 'WordPressdotorg\Theme\style_src', 10, 2 );
}
add_action( 'wp_head', __NAMESPACE__ . '\remove_wporg_head_hook', 0 );

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
 * Custom template tags.
 */
require_once get_theme_file_path( '/inc/template-tags.php' );
