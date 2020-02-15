<?php
/**
 * Functions to restrict o2 to the Post post type perimeter.
 *
 * @package  WPTribu\Theme
 */

namespace WPTribu\Theme;

/**
 * Dequeue o2 scripts & styles when needed.
 *
 * @since 1.0.0
 */
function dequeue_assets() {
	$scripts = wp_scripts();
	$styles  = wp_styles();

	if ( isset( $scripts->queue ) ) {
		foreach ( $scripts->queue as $js_handle ) {
			if ( 0 === strpos( $js_handle, 'o2') || in_array( $js_handle, array( 'jquery-actionstate', 'jquery.autoresize' ), true ) ) {
				wp_dequeue_script( $js_handle );
			}
		}
	}

	if ( isset( $styles->queue ) ) {
		foreach ( $styles->queue as $css_handle ) {
			if ( 0 === strpos( $css_handle, 'o2') ) {
				wp_dequeue_script( $css_handle );
			}
		}
	}
}

/**
 * Dequeue o2 scripts & styles when needed.
 *
 * @since 1.0.0
 *
 * @param null|string $content Null or The content of the post.
 * @return null|string Null or The content of the post.
 */
function unhook( $content = '' ) {
	$current = current_filter();
	global $wp_filter;

	if ( 'wp_footer' === $current && ( ! isset( $wp_filter['wp_footer'] ) || ! $wp_filter['wp_footer'] ) ) {
		return;
	}

	foreach ( $wp_filter[ $current ] as $priority => $hook ) {
		if ( 0 === $priority ) {
			continue;
		}

		foreach ( $hook as $function ) {
			if ( ! isset( $function['function'] ) || ! $function['function'] ) {
				continue;
			}

			if ( is_array( $function['function'] ) ) {
				list( $object, $callback ) = $function['function'];

				if ( $object && $callback && (
						in_array( $callback, array( 'xpost_link_post', 'before_post_likes', 'after_post_likes' ), true ) ||
						is_a( $object, 'o2_Offline' ) || is_a( $object, 'o2_Editor' ) || is_a( $object, 'o2_Basic_Timers' ) ||
						is_a( $object, 'o2_Performance_Monitor' )
					) ) {
					if ( 'wp_footer' === $current ) {
						remove_action( 'wp_footer', array( $object, $callback ) );
					} else {
						remove_filter( 'the_content', array( $object, $callback ), $priority );
					}
				}
			}
		}
	}

	if ( 'the_content' === $current ) {
		return $content;
	}
}

/**
 * Make sure to remove as much as possible of o2 hooks
 *
 * @since 1.0.0
 */
function restrict_o2() {
	global $o2;

	if ( is_a( $o2, 'o2' ) && 'post' !== get_post_type() ) {
		remove_action( 'wp_head', array( $o2, 'wp_head' ), 100 );
		remove_action( 'wp_footer', array( $o2, 'wp_footer' ) );
		remove_action( 'wp_footer', array( $o2, 'scripts_and_styles' ) );
		remove_filter( 'the_excerpt', array( 'o2', 'add_json_data' ), 999999 );
		remove_filter( 'the_content', array( 'o2', 'add_json_data' ), 999999 );
		remove_action( 'wp_enqueue_scripts', array( $o2, 'register_plugin_styles' ) );
		remove_action( 'wp_enqueue_scripts', array( $o2, 'register_plugin_scripts' ) );

		remove_filter( 'body_class', array( $o2, 'body_class' ) );
		remove_filter( 'post_class', array( $o2, 'post_class' ), 10, 3 );

		remove_action( 'wp_enqueue_scripts', array( $o2->keyboard, 'register_scripts' ) );
		remove_action( 'wp_footer', array( $o2->keyboard, 'help_text' ) );
		remove_filter( 'o2_app_controls', array( $o2->keyboard, 'help_link' ) );
		remove_action( 'wp_footer', 'o2_post_action_states_in_footer' );
		remove_action( 'wp_footer', 'o2_live_comments_widget_footer_bootstrap' );
		remove_action( 'o2_loaded', 'o2_todos' );

		if ( $o2->xposts ) {
			remove_action( 'wp_footer', array( $o2->xposts, 'inline_js' ) );
		}

		if ( $o2->templates ) {
			remove_action( 'wp_footer', array( $o2->templates, 'embed_templates' ) );
		}

		if ( $o2->post_list_creator ) {
			remove_filter( 'the_content', array( $o2->post_list_creator, 'parse_lists_in_post' ), 1 );
		}

		add_filter( 'o2_process_the_content', '__return_false', 200 );
		remove_filter( 'the_content', array( 'o2_Tags', 'append_old_tags' ), 14 );
		remove_filter( 'the_content', array( 'o2_Tags', 'tag_links' ), 15 );
		add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\dequeue_assets', 100 );
		add_action( 'wp_footer', __NAMESPACE__ . '\unhook', 0 );
		add_filter( 'the_content', __NAMESPACE__ . '\unhook', 0 );
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\restrict_o2' );
