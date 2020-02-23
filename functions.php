<?php
/**
 * WPTribu.org functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets Theme supports.
 *
 * @since 1.0.0
 */
function setup_theme() {
	// Load translations.
	load_theme_textdomain(
		'wptribu-theme',
		get_theme_file_path( '/languages/' )
	);

	// Add Title tag support.
	add_theme_support( 'title-tag' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Don't include Adjacent Posts functionality.
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'header'    => esc_html__( 'Header', 'wptribu-theme' ),
			'primary'   => esc_html__( 'Blog top menu', 'wptribu-theme' ),
			'secondary' => esc_html__( 'Handbook top menu', 'wptribu-theme' ),
			'footer'    => esc_html__( 'Site‘s footer menu', 'wptribu-theme' ),
		)
	);

	/**
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

	add_theme_support(
		'starter-content',
		array(
			// Create initial pages.
			'posts'   => array(
				'home' => array(
					'post_title' => __( 'Home', 'wptribu-theme' ),
					'post_type'  => 'page',
					'post_name'  => _x( 'home', 'slug to use for the home page', 'wptribu-theme' ),
					'template'   => 'page-about-hero-landing.php',
				),
				'blog' => array(
					'post_title' => __( 'Discussions', 'wptribu-theme' ),
					'post_type'  => 'page',
					'post_name'  => _x( 'discussions', 'slug to use for the blog page', 'wptribu-theme' ),
				),
			),
			// Default to a static front page and assign the front and posts pages.
			'options' => array(
				'show_on_front'  => 'page',
				'page_on_front'  => '{{home}}',
				'page_for_posts' => '{{blog}}',
			),
		),
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup_theme' );

/**
 * Register widgets.
 *
 * @since 1.0.0
 */
function widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Sidebar', 'wptribu-theme' ),
			'id'            => 'sidebar-1',
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4><div class="widget-content">',
		)
	);

	register_sidebar(
		array(
			'name'          => __( 'o2 Sidebar', 'wptribu-theme' ),
			'id'            => 'sidebar-o2',
			'before_widget' => '<div id="%1$s" class="box gray widget %2$s">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4><div class="widget-content">',
		)
	);
}
add_action( 'widgets_init', __NAMESPACE__ . '\widgets_init' );

/**
 * Enqueues styles.
 *
 * @since 1.0.0
 */
function styles() {
	wp_enqueue_style(
		'wptribu-theme',
		get_theme_file_uri( '/css/style.css' ),
		array(
			'dashicons',
			'open-sans',
		),
		'1.0.0'
	);
	wp_style_add_data( 'wptribu-theme', 'rtl', 'replace' );
	wp_add_inline_style(
		'wptribu-theme',
		sprintf(
			'.o2-save-spinner {
				height: 40px;
				background-size: 40px 40px;
				background: url( \'%1$s\' ) no-repeat right bottom;
			}
			.o2-editor .o2-editor-text.autocomplete-loading {
				background: url( \'%1$s\' ) no-repeat right 15px bottom 15px/16px;
			}

			nav.handbook-navigation .meta-nav {
				color: #fff;
			}

			.handbook-navigation .meta-nav:before,
			.handbook-navigation .meta-nav:after {
				color: #777;
			}

			.handbook-navigation [rel="previous"] .meta-nav:before {
				content: \'%2$s\';
			}

			.handbook-navigation [rel="next"] .meta-nav:after {
				content: \'%3$s\';
			}',
			esc_url( admin_url( 'images/spinner-2x.gif' ) ),
			esc_html_x( 'Previous', 'Post navigation', 'wptribu-theme' ),
			esc_html_x( 'Next', 'Post navigation', 'wptribu-theme' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\styles', 9 );

/**
 * Sets the separator for the document title.
 *
 * @since 1.0.0
 *
 * @return string Document title separator.
 */
function document_title_separator() {
	return '&#124;';
}
add_filter( 'document_title_separator', __NAMESPACE__ . '\document_title_separator' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since 1.0.0
 *
 * @global integer $content_width
 */
function content_width() {
	$GLOBALS['content_width'] = apply_filters( 'wporg_content_width', 612 );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\content_width', 0 );

/**
 * Gets the minimized suffix.
 *
 * @since 1.0.0
 *
 * @return string The minimized suffix.
 */
function get_scripts_min() {
	$suffix       = '.min';
	$script_debug = false;

	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		$suffix       = '';
		$script_debug = true;
	}

	// Concatenates core scripts when possible.
	if ( ! $script_debug ) {
		$GLOBALS['concatenate_scripts'] = true; // phpcs:ignore
	}

	return $suffix;
}

/**
 * Checks whether the displayed page is displaying posts.
 *
 * @since 1.0.0
 *
 * @return boolean True if the displayed page is displaying posts.
 *                 False otherwise.
 */
function is_post_archive() {
	global $wp_query;

	return $wp_query->is_posts_page || is_post_type_archive( 'post' ) || is_tag() || is_category() || is_date() || is_archive();
}

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function scripts() {
	$suffix  = get_scripts_min();
	$version = '1.0.0';

	if ( ! $suffix ) {
		$version = filemtime( get_theme_file_path( "/js/navigation$suffix.js" ) );
	}

	// phpcs:ignore Squiz.PHP.CommentedOutCode.Found, Squiz.Commenting.InlineComment.InvalidEndChar
	wp_enqueue_script(
		'wptribu-navigation',
		get_template_directory_uri() . "/js/navigation$suffix.js",
		array(),
		$version,
		true
	);

	if ( ! $suffix ) {
		$version = filemtime( get_theme_file_path( "/js/skip-link-focus-fix$suffix.js" ) );
	}

	wp_enqueue_script(
		'wptribu-plugins-skip-link-focus-fix',
		get_template_directory_uri() . "/js/skip-link-focus-fix$suffix.js",
		array(),
		$version,
		true
	);

	if ( ! is_front_page() && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_home() || is_post_archive() || ( is_single() && 'post' === get_post_type() ) ) {
		if ( ! $suffix ) {
			$version = filemtime( get_theme_file_path( "/js/mobile-helper$suffix.js" ) );
		}

		wp_enqueue_script(
			'wptribu-mobile-helper',
			get_template_directory_uri() . "/js/mobile-helper$suffix.js",
			array(),
			$version,
			true
		);

		if ( ! $suffix ) {
			$version = filemtime( get_theme_file_path( "/js/breathe$suffix.js" ) );
		}

		wp_enqueue_script(
			'wptribu-breathe',
			get_template_directory_uri() . "/js/breathe$suffix.js",
			array( 'wptribu-mobile-helper', 'o2-enquire' ),
			$version,
			true
		);
	}

	if ( is_single() && 'handbook' === get_queried_object()->post_type ) {
		if ( ! $suffix ) {
			$version = filemtime( get_theme_file_path( "/js/chapters$suffix.js" ) );
		}

		wp_enqueue_script(
			'wptribu-chapters',
			get_template_directory_uri() . "/js/chapters$suffix.js",
			array( 'jquery' ),
			$version,
			true
		);
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
 * @return array The Body classes.
 */
function body_classes( $classes = array() ) {
	if ( is_page() ) {
		$page = get_queried_object();

		if ( preg_match( '/page-about-hero/', get_page_template_slug( $page ) ) ) {
			$classes[] = 'page-about';
		}
	}

	if ( is_post_archive() ) {
		$classes[] = 'archive-post';
	}

	return array_unique( $classes );
}
add_filter( 'body_class', __NAMESPACE__ . '\body_classes' );

/**
 * Checks whether the current displayed page has a parent page.
 *
 * @since 1.0.0
 *
 * @return boolean True if the current displayed page has a parent page.
 *                 False otherwise.
 */
function page_has_parent() {
	if ( ! is_page() ) {
		return false;
	}

	$page = get_queried_object();

	return isset( $page->post_parent ) ? !! $page->post_parent : false; // phpcs:ignore
}

/**
 * Gets the parent page for a given post object.
 *
 * @since 1.0.0
 *
 * @param WP_Post $page The post object for the page.
 * @return WP_Post      The post's parent page.
 */
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

/**
 * Displays the menu for child pages.
 *
 * @since 1.0.0
 */
function page_menu() {
	$page_menu = wp_page_menu(
		array(
			'child_of' => get_page_parent()->ID,
			'echo'     => false,
		)
	);

	if ( preg_match( '/<li.*current_page_item[^>]*>(.*?)<\/li>/', $page_menu, $match ) ) {
		$page_menu = str_replace(
			$match[1],
			str_replace( 'href', 'class="active" href', $match[1] ),
			$page_menu
		);
	}

	echo $page_menu; // phpcs:ignore
}

/**
 * Make sure the right parent menu item is active for the handbook pages.
 *
 * @since 1.0.0
 *
 * @param array $menu_items The menu items.
 * @return array            The menu items.
 */
function nav_menu_objects_for_handbook( $menu_items = array() ) {
	if ( is_single() && 'handbook' === get_queried_object()->post_type ) {
		foreach ( $menu_items as $key => $item ) {
			if ( 'handbook' === $item->object ) {
				continue;
			}

			$menu_items[ $key ]->classes = array_diff(
				$menu_items[ $key ]->classes,
				array(
					'current-menu-item',
					'current_page_item',
					'current_page_parent',
					'current_page_ancestor',
					'current-menu-ancestor',
					'current-menu-parent',
				)
			);
		}
	}

	return $menu_items;
}
add_filter( 'wp_nav_menu_objects', __NAMESPACE__ . '\nav_menu_objects_for_handbook' );

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
 * Appends a contribute menu when the user is not yet logged in.
 *
 * @since 1.0.0
 *
 * @param string $items The HTML list content for the menu items.
 * @return string The HTML list content for the menu items.
 */
function entete_menu_items( $items = '' ) {
	if ( ! is_user_logged_in() && class_exists( 'wpTribu\SSO\WPTribu_SSO' ) ) {
		$contributor_page = get_posts(
			array(
				'post_type' => 'page',
				'post_name' => 'contribuer',
			)
		);

		if ( $contributor_page ) {
			$contributor_page = reset( $contributor_page );
			$url              = get_permalink( $contributor_page->ID );
		} else {
			$blog_page_id = get_option( 'page_for_posts', 0 );

			if ( $blog_page_id ) {
				$url = wp_login_url( get_permalink( $blog_page_id ) );
			} else {
				$url = wp_login_url();
			}
		}

		$items .= sprintf(
			'<li id="contribute" class="button contribute-button"><a href="%1$s">%2$s</a></li>',
			esc_url( $url ),
			esc_html__( 'Contribute', 'wptribu-theme' )
		);
	}

	return $items;
}
add_filter( 'wp_nav_menu_entete_items', __NAMESPACE__ . '\entete_menu_items', 10, 1 );

/**
 * Wrapper for wporg_get_current_handbook() to avoid fatal errors
 * if the handbook plugin is not active.
 *
 * @since 1.0.0
 *
 * @return string the current handbook name.
 */
function get_current_handbook() {
	if ( ! function_exists( 'wporg_get_current_handbook' ) ) {
		return '';
	}

	return wporg_get_current_handbook();
}

/**
 * Custom template tags.
 */
require_once get_theme_file_path( '/inc/template-tags.php' );

/**
 * Mentions.
 */
require_once get_theme_file_path( '/inc/mentions.php' );

/**
 * Customizations of o2.
 */
if ( class_exists( 'o2' ) ) {
	require_once get_theme_file_path( '/inc/o2.php' );
}
