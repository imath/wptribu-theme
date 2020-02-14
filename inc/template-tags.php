<?php
/**
 * Custom template tags
 *
 * @package  WPTribu\Theme
 */

namespace WPTribu\Theme;

if ( ! function_exists( __NAMESPACE__ . '\entry_meta' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * Create your own  WordPressdotorg\Theme\entry_meta() function to override in a child theme.
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

			// phpcs:disable WordPress.XSS.EscapeOutput.OutputNotEscaped
			printf(
				/* translators: 1: post date 2: post author */
				'<span class="posted-on">' . __( 'Posted on %1$s by %2$s.', 'wptribu' ) . '</span>',
				$time_string,
				$author_string
			);
			// phpcs:enable WordPress.XSS.EscapeOutput.OutputNotEscaped
		}

		$format = get_post_format();
		if ( current_theme_supports( 'post-formats', $format ) ) {
			printf(
				'<span class="entry-format">%1$s<a href="%2$s">%3$s</a></span>',
				sprintf( '<span class="screen-reader-text">%s </span>', esc_html_x( 'Format', 'Used before post format.', 'wptribu' ) ),
				esc_url( get_post_format_link( $format ) ),
				esc_html( get_post_format_string( $format ) )
			);
		}

		if ( 'post' === get_post_type() ) {
			entry_taxonomies();
		}

		if ( ! is_singular() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link( sprintf(
				/* translators: Post title. */
				__( 'Leave a comment<span class="screen-reader-text"> on %s</span>', 'wptribu' ),
				get_the_title()
			) );
			echo '</span>';
		}
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\get_entry_date' ) ) :
	/**
	 * Prints HTML with published and updated information for current post.
	 *
	 * Create your own  WordPressdotorg\Theme\get_entry_date() function to override in a child theme.
	 */
	function get_entry_date() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		return sprintf(
			$time_string,
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
	 * Create your own  WordPressdotorg\Theme\entry_date() function to override in a child theme.
	 */
	function entry_date() {
		printf(
			'<span class="posted-on">%1$s <a href="%2$s" rel="bookmark">%3$s</a></span>',
			esc_html_x( 'Posted on', 'Used before publish date.', 'wptribu' ),
			esc_url( get_permalink() ),
			get_entry_date() // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
		);
	}
endif;

if ( ! function_exists( __NAMESPACE__ . '\entry_taxonomies' ) ) :
	/**
	 * Prints HTML with category and tags for current post.
	 *
	 * Create your own WordPressdotorg\Theme\entry_taxonomies() function to override in a child theme.
	 */
	function entry_taxonomies() {
		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'wptribu' ) );
		if ( $categories_list && categorized_blog() ) {
			printf(
				'<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				esc_html_x( 'Categories', 'Used before category names.', 'wptribu' ),
				$categories_list // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			);
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'wptribu' ) );
		if ( $tags_list ) {
			printf(
				'<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
				esc_html_x( 'Tags', 'Used before tag names.', 'wptribu' ),
				$tags_list // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
			);
		}
	}
endif;
