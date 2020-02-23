<?php
/**
 * Custom template tags
 *
 * @package  WPTribu\Theme
 */

namespace WPTribu\Theme;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( __NAMESPACE__ . '\entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * Create your own  WPTribu\Theme\entry_meta() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function entry_meta() {
		if ( in_array( get_post_type(), array( 'post', 'attachment' ), true ) ) {
			$time_string = sprintf(
				'<a href="%1$s" rel="bookmark">%2$s</a>',
				esc_url( get_permalink() ),
				get_entry_date()
			);

			$author_string = sprintf(
				'<span class="entry-author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);

			printf(
				/* translators: 1: post date 2: post author */
				'<span class="posted-on">' . esc_html__( 'Posted on %1$s by %2$s.', 'wptribu-theme' ) . '</span>',
				$time_string, // phpcs:ignore
				$author_string // phpcs:ignore
			);
		}

		$format = get_post_format();
		if ( current_theme_supports( 'post-formats', $format ) ) {
			printf(
				'<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
				sprintf( '<span class="screen-reader-text">%s </span>', esc_html_x( 'Format', 'Used before post format.', 'wptribu-theme' ) ),
				esc_url( get_post_format_link( $format ) ),
				esc_html( get_post_format_string( $format ) )
			);
		}

		if ( 'post' === get_post_type() ) {
			entry_taxonomies();
		}

		if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					/* translators: Post title. */
					__( 'Leave a comment%s', 'wptribu-theme' ),
					sprintf(
						'<span class="screen-reader-text"> %1$s %2$s</span>',
						esc_html_x( 'on', 'followed by the post title', 'wptribu-theme' ),
						get_the_title()
					)
				)
			);
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\get_entry_date' ) ) :
	/**
	 * Prints HTML with published and updated information for current post.
	 *
	 * Create your own  WPTribu\Theme\get_entry_date() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function get_entry_date() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		return sprintf(
			$time_string, // phpcs:ignore
			esc_attr( get_the_date( 'c' ) ),
			get_the_date(),
			esc_attr( get_the_modified_date( 'c' ) ),
			get_the_modified_date()
		);
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\entry_date' ) ) :
	/**
	 * Prints HTML with date information for current post.
	 *
	 * Create your own  WPTribu\Theme\entry_date() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function entry_date() {
		printf(
			'<span class="posted-on">%1$s <a href="%2$s" rel="bookmark">%3$s</a></span>',
			esc_html_x( 'Posted on', 'Used before publish date.', 'wptribu-theme' ),
			esc_url( get_permalink() ),
			get_entry_date() // phpcs:ignore
		);
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\entry_taxonomies' ) ) :
	/**
	 * Prints HTML with category and tags for current post.
	 *
	 * Create your own WPTribu\Theme\entry_taxonomies() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function entry_taxonomies() {
		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'wptribu-theme' ) );
		if ( $categories_list ) {
			printf(
				'<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				esc_html_x( 'Categories', 'Used before category names.', 'wptribu-theme' ),
				$categories_list // phpcs:ignore
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'wptribu-theme' ) );
		if ( $tags_list ) {
			printf(
				'<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				esc_html_x( 'Tags', 'Used before tag names.', 'wptribu-theme' ),
				$tags_list // phpcs:ignore
			);
		}
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\get_site_description' ) ) :
	/**
	 * Returns the description of the site.
	 *
	 * Create your own  WPTribu\Theme\get_site_description() function to override in a child theme.
	 *
	 * @since 1.0.0
	 *
	 * @return string The site's description.
	 */
	function get_site_description() {
		$description = get_bloginfo( 'description' );

		if ( ! $description ) {
			$description = __( 'The best way to learn WordPress is to contribute to it.', 'wptribu-theme' );
		}

		return $description;
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\the_site_description' ) ) :
	/**
	 * Prints HTML for the description of the site.
	 *
	 * Create your own  WPTribu\Theme\the_site_description() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function the_site_description() {
		echo esc_html( get_site_description() );
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\the_handbook_navigation' ) ) {
	/**
	 * Prints HTML for the Handbook Previous/Next navigation.
	 *
	 * Create your own  WPTribu\Theme\the_handbook_navigation() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function the_handbook_navigation() {
		if ( ! class_exists( '\WPorg_Handbook_Navigation' ) ) {
			return;
		}

		\WPorg_Handbook_Navigation::show_nav_links( 'Contributor Events Table of Contents' );
	}
}

if ( ! function_exists( __NAMESPACE__ . '\the_svg_src' ) ) :
	/**
	 * Prints HTML for the SVG image.
	 *
	 * Create your own  WPTribu\Theme\the_svg_src() function to override in a child theme.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The file name without the svg extension.
	 */
	function the_svg_src( $name = 'wptribu' ) {
		if ( ! in_array( $name, array( 'wptribu', 'groups', 'tickets' ), true ) ) {
			return '';
		}

		echo esc_url( get_template_directory_uri() . '/images/' . $name . '.svg' );
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\the_blog_title' ) ) :
	/**
	 * Prints the Blog's title.
	 *
	 * Create your own  WPTribu\Theme\the_blog_title() function to override in a child theme.
	 *
	 * @since 1.0.0
	 */
	function the_blog_title() {
		$blog_title = __( 'All discussions', 'wptribu-theme' );

		if ( is_category() ) {
			$blog_title = __( 'Discussions about a category', 'wptribu-theme' );
		}

		if ( is_tag() ) {
			$blog_title = __( 'Discussions about a tag', 'wptribu-theme' );
		}

		if ( is_author() ) {
			$blog_title = __( 'Discussions started by a specific author', 'wptribu-theme' );
		}

		/**
		 * Filter here to use a custom title for the blog.
		 *
		 * @since 1.0.0
		 *
		 * @param string $blog_title The title of the blog page.
		 */
		$get_blog_title = apply_filters( 'wptribu_get_blog_title', $blog_title );

		echo esc_html( $get_blog_title );
	}
endif;
