<?php
/**
 * Functions to restrict o2 to the Post post type perimeter.
 *
 * @package  WPTribu\Theme
 */

namespace WPTribu\Theme;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
			if ( 0 === strpos( $js_handle, 'o2' ) || in_array( $js_handle, array( 'jquery-actionstate', 'jquery.autoresize' ), true ) ) {
				wp_dequeue_script( $js_handle );
			}
		}
	}

	if ( isset( $styles->queue ) ) {
		foreach ( $styles->queue as $css_handle ) {
			if ( 0 === strpos( $css_handle, 'o2' ) ) {
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

	if ( is_a( $o2, 'o2' ) && 'post' !== get_post_type() && ! is_post_archive() ) {
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

/**
 * Checks if an o2 filter is active.
 *
 * @since 1.0.0
 *
 * @param string $filter  The name of the filter.
 * @param string $current The current value of the filter.
 * @return boolean        True if the filter is active. False otherwise.
 */
function o2_selected_filter( $filter = '', $current = '' ) {
	$archive     = get_post_type_archive_link( 'post' );
	$current_uri = '';
	$query_args  = array();
	$return      = false;

	if ( isset( $_SERVER['REQUEST_URI'] ) ) {
		$current_uri = wp_parse_url( $_SERVER['REQUEST_URI'] ); // phpcs:ignore
	}

	if ( false === strpos( $archive, $current_uri['path'] ) ) {
		return $return;
	}

	if ( isset( $current_uri['query'] ) ) {
		$query_args = wp_parse_args( $current_uri['query'], $query_args );
	}

	if ( isset( $query_args[ $filter ] ) && $current === $query_args[ $filter ] ) {
		$return = true;
	}

	return $return;
}

/**
 * Checks if the noreplies o2 filter is active.
 *
 * @since 1.0.0
 */
function o2_selected_filter_is_noreplies() {
	return o2_selected_filter( 'replies', 'none' );
}

/**
 * Checks if the mentions o2 filter is active.
 *
 * @since 1.0.0
 */
function o2_selected_filter_is_mentions() {
	$user = wp_get_current_user();

	return o2_selected_filter( 'mentions', $user->user_nicename );
}

/**
 * Checks if the resolved o2 filter is active.
 *
 * @since 1.0.0
 */
function o2_selected_filter_is_resolved() {
	return o2_selected_filter( 'resolved', 'resolved' );
}

/**
 * Checks if the unresolved o2 filter is active.
 *
 * @since 1.0.0
 */
function o2_selected_filter_is_unresolved() {
	return o2_selected_filter( 'resolved', 'unresolved' );
}

/**
 * Fixes o2 widgets to use the right username.
 *
 * @since 1.0.0
 *
 * @param array $filters List of filters for o2 widgets.
 * @return array List of filters for o2 widgets.
 */
function o2_filter_widget_filters( $filters = array() ) {
	$user    = wp_get_current_user();
	$archive = get_post_type_archive_link( 'post' );

	if ( isset( $filters['filter-none.o2'] ) ) {
		$filters['filter-none.o2']['label'] = esc_html__( 'All discussions', 'wptribu-theme' );
		$filters['filter-none.o2']['url']   = esc_url( $archive );
	}

	if ( isset( $filters['filter-recent-comments.o2'] ) ) {
		$filters['filter-recent-comments.o2']['url'] = esc_url( add_query_arg( 'o2_recent_comments', true, $archive ) );
	}

	if ( isset( $filters['filter-noreplies.o2'] ) ) {
		$filters['filter-noreplies.o2']['url']       = esc_url( add_query_arg( 'replies', 'none', $archive ) );
		$filters['filter-noreplies.o2']['is_active'] = __NAMESPACE__ . '\o2_selected_filter_is_noreplies';
	}

	if ( isset( $filters['filter-mentionsMe.o2'] ) ) {
		$filters['filter-mentionsMe.o2']['url']       = esc_url( add_query_arg( 'mentions', $user->user_nicename, $archive ) );
		$filters['filter-mentionsMe.o2']['is_active'] = __NAMESPACE__ . '\o2_selected_filter_is_mentions';
	}

	if ( isset( $filters['filter-myPosts.o2'] ) ) {
		$filters['filter-myPosts.o2']['url'] = esc_url( home_url( '/author/' . $user->user_nicename ) );
	}

	if ( isset( $filters['filter-resolved.o2'] ) ) {
		$filters['filter-resolved.o2']['url']       = esc_url( add_query_arg( 'resolved', 'resolved', $archive ) );
		$filters['filter-resolved.o2']['is_active'] = __NAMESPACE__ . '\o2_selected_filter_is_resolved';
	}

	if ( isset( $filters['filter-unresolved.o2'] ) ) {
		$filters['filter-unresolved.o2']['url']       = esc_url( add_query_arg( 'resolved', 'unresolved', $archive ) );
		$filters['filter-unresolved.o2']['is_active'] = __NAMESPACE__ . '\o2_selected_filter_is_unresolved';
	}

	return $filters;
}
add_filter( 'o2_filter_widget_filters', __NAMESPACE__ . '\o2_filter_widget_filters', 10, 1 );

/**
 * Make sure the expand editor control is available in category template.
 *
 * @since 1.0.0
 */
function o2_bring_expand_editor_control() {
	echo '<div id="o2-expand-editor"><span class="genericon genericon-edit"></span></div>';
}

/**
 * Allow the Front end editor to be loaded on category pages.
 *
 * @since 1.0.0
 *
 * @param array $options o2 options.
 * @return array o2 options.
 */
function o2_filter_options( $options = array() ) {
	if ( is_category() ) {
		$options['options']['showFrontSidePostBox'] = is_user_logged_in() && current_user_can( 'publish_posts' );
		add_action( 'wp_footer', __NAMESPACE__ . '\o2_bring_expand_editor_control' );

	} elseif ( is_search() || isset( $_GET['mentions'] ) ) { // phpcs:ignore
		$options['options']['showFrontSidePostBox'] = false;
	}

	return $options;
}
add_filter( 'o2_options', __NAMESPACE__ . '\o2_filter_options' );

/**
 * Make sure to assign the category corresponding to the displayed page.
 *
 * @since 1.0.0
 *
 * @param WP_Post $post The Post object.
 * @return WP_Post The post object.
 */
function o2_create_post( $post ) {
	$home_url = home_url();
	$url      = '';

	if ( isset( $_SERVER['HTTP_REFERER'] ) ) {
		$url = wp_unslash( $_SERVER['HTTP_REFERER'] ); // phpcs:ignore
	}

	if ( $url !== $home_url ) {
		$parts = wp_parse_url( $url );

		$category_base = 'category';
		if ( $custom_base = get_option( 'category_base' ) ) { // phpcs:ignore
			$category_base = $custom_base;
		}

		$category_part = str_replace( trailingslashit( $home_url ) . $category_base, '', $parts['scheme'] . '://' . $parts['host'] . $parts['path'] );
		$category_slug = explode( '/', trim( $category_part, '/' ) )[0];

		$category = get_category_by_slug( $category_slug );
		if ( isset( $category->term_id ) && $category->term_id ) {
			$post->post_category = array( $category->term_id );
		}
	}

	return $post;
}
add_filter( 'o2_create_post', __NAMESPACE__ . '\o2_create_post', 10, 1 );

/**
 * Make sure empty categories are displayed to allow posting from the front end.
 *
 * @since 1.0.0
 *
 * @param array $args The category widget arguments.
 * @return array      The category widget arguments.
 */
function widget_categories_args( $args = array() ) {
	return array_merge(
		$args,
		array(
			'hide_empty' => false,
		)
	);
}
add_filter( 'widget_categories_args', __NAMESPACE__ . '\widget_categories_args', 10, 1 );

/**
 * Enqueue specific script needed by o2.
 *
 * @since 1.0.0
 */
function o2_scripts() {
	$suffix = get_scripts_min();

	wp_enqueue_script(
		'wptribu-o2',
		get_template_directory_uri() . "/js/o2-reply$suffix.js",
		array( 'o2-app', 'jquery' ),
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\o2_scripts', 200 );

/**
 * Customize the blog title according to o2 context.
 *
 * @since 1.0.0
 *
 * @param string $blog_title The blog title.
 * @return string            The blog title.
 */
function o2_blog_title( $blog_title = '' ) {
	if ( o2_selected_filter_is_noreplies() ) {
		$blog_title = __( 'Discussions without replies', 'wptribu-theme' );
	} elseif ( o2_selected_filter_is_mentions() ) {
		$blog_title = __( 'Discussions where I am mentioned', 'wptribu-theme' );
	} elseif ( o2_selected_filter_is_resolved() ) {
		$blog_title = __( 'Resolved discussions', 'wptribu-theme' );
	} elseif ( o2_selected_filter_is_unresolved() ) {
		$blog_title = __( 'Unresolved Discussions', 'wptribu-theme' );
	} elseif ( o2_selected_filter( 'o2_recent_comments', '1' ) ) {
		$blog_title = __( 'Discussions which recently got comments', 'wptribu-theme' );
	}

	return $blog_title;
}
add_filter( 'wptribu_get_blog_title', __NAMESPACE__ . '\o2_blog_title' );

/**
 * Make sure to ignore stickies when it's not consistent.
 *
 * @since 1.0.0
 *
 * @param WP_Query $wp_query The WordPress Query object.
 */
function o2_parse_query( $wp_query = null ) {
	if ( o2_selected_filter_is_noreplies() || o2_selected_filter_is_mentions() || o2_selected_filter_is_unresolved() || o2_selected_filter_is_resolved() ) {
		$wp_query->query_vars['ignore_sticky_posts'] = true;
	}
}
add_action( 'parse_query', __NAMESPACE__ . '\o2_parse_query' );
