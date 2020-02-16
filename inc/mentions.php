<?php
/**
 * Mentions functions
 *
 * @package  WPTribu\Theme
 */

namespace WPTribu\Theme;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Looks for mentioned users.
 *
 * @since 1.0.0
 *
 * @param string $text The text to find mentions in.
 * @return array       The list of user data to mention.
 */
function get_mentions( $text = '' ) {
	$mentions = array();

	if ( preg_match_all( '/[@]+([A-Za-z0-9-_\.@]+)\b/', $text, $slugs ) ) {
		// Make sure there's only one instance of each username.
		$slugs = array_unique( $slugs[1] );

		// Bail if no usernames.
		if ( empty( $slugs ) ) {
			return $mentions;
		}

		$users = get_users(
			array(
				'nicename__in' => $slugs,
			)
		);

		if ( ! $users ) {
			return $mentions;
		}

		// We've found some mentions! Check to see if users exist.
		foreach ( $users as $user ) {
			$mentions[ $user->ID ] = (object) array(
				'slug'  => $user->user_nicename,
				'name'  => $user->display_name,
				'url'   => home_url( '/author/' . $user->user_nicename ),
				'email' => $user->user_email,
			);
		}
	}

	return $mentions;
}

/**
 * Filters the post or the comment content to render mention links.
 *
 * @since 1.0.0
 *
 * @param string $text The post or the comment content.
 * @return string The post or comment content with rendered mention links.
 */
function set_mentions( $text = '' ) {
	$mentions = get_mentions( $text );

	if ( $mentions ) {
		$replace_count = 0;
		$replacements  = array();

		foreach ( $mentions as $mention ) {
			// Prevent @ name linking inside <a> tags.
			preg_match_all( '/(<a.*?(?!<\/a>)@' . $mention->slug . '.*?<\/a>)/', $text, $content_matches );
			if ( ! empty( $content_matches[1] ) ) {
				foreach ( $content_matches[1] as $replacement ) {
					$replacements[ '#O2CM' . $replace_count ] = $replacement;

					// Replace the content.
					$text = str_replace( $replacement, '#O2CM' . $replace_count, $text );
					$replace_count++;
				}
			}
		}

		// Linkify the mentions with the username.
		foreach ( $mentions as $user_id => $user_data ) {
			$text = preg_replace( '/(@' . $user_data->slug . '\b)/', "<a class='o2-citron-mention' href='" . esc_url( $user_data->url ) . "' rel='nofollow'>@{$user_data->slug}</a>", $text );
		}

		// Put everything back.
		if ( ! empty( $replacements ) ) {
			foreach ( $replacements as $placeholder => $original ) {
				$text = str_replace( $placeholder, $original, $text );
			}
		}
	}

	return $text;
}
add_filter( 'the_content', __NAMESPACE__ . '\set_mentions', 100 );
add_filter( 'comment_text', __NAMESPACE__ . '\set_mentions', 100 );

/**
 * Registers Mentions dependency script.
 *
 * @since 1.0.0
 */
function register_mention_script() {
	wp_register_script(
		'tribute',
		get_template_directory_uri() . '/js/tribute.min.js',
		array(),
		'4.1.1',
		true
	);
}
add_action( 'init', __NAMESPACE__ . '\register_mention_script' );

/**
 * Enqueue Mentions script & dependency.
 *
 * @since 1.0.0
 */
function enqueue_mention_script() {
	if ( in_array( 'o2-editor', wp_scripts()->queue, true ) ) {
		$suffix = get_scripts_min();

		wp_enqueue_script(
			'wptribu-mentions',
			get_template_directory_uri() . "/js/mentions$suffix.js",
			array( 'tribute', 'wp-api-request' ),
			'1.0.0',
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_mention_script', 20 );

/**
 * Allows users to list others.
 *
 * @since 1.O.O
 *
 * @param array           $prepared_args Array of arguments for WP_User_Query.
 * @param WP_REST_Request $request       The current request.
 * @return array Array of arguments for WP_User_Query.
 */
function allow_mentions_query( $prepared_args, $request ) {
	if ( 'wptribu_mentions' === $request->get_param( 'search_type' ) ) {
		unset( $prepared_args['has_published_posts'] );
		$prepared_args['exclude'] = array( get_current_user_id() );
	}

	return $prepared_args;
}
add_filter( 'rest_user_query', __NAMESPACE__ . '\allow_mentions_query', 10, 2 );

/**
 * Saves mentions for a post or a comment.
 *
 * @since 1.0.0
 *
 * @param WP_Post|WP_Comment $object The post or the comment object.
 */
function save_mentions( $object = null ) {
	$mentions   = array();
	$is_post    = is_a( $object, 'WP_Post' );
	$is_comment = is_a( $object, 'WP_Comment' );
	$blogname   = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	if ( $is_post ) {
		// Disable mentions filter.
		remove_filter( 'the_content', __NAMESPACE__ . '\set_mentions', 100 );

		$content  = apply_filters( 'the_content', $object->post_content );

		// Enable mentions filter.
		add_filter( 'the_content', __NAMESPACE__ . '\set_mentions', 100 );

		// Get mentions for the post.
		$mentions = get_mentions( $content );

		/* translators: Mention notification email subject. %s: Site title. */
		$subject = sprintf( __( '[%s] New post mention', 'wptribu' ), $blogname );

		/* translators: %s: Post title. */
		$notify_message = sprintf( __( 'You have been mentioned into the post: "%s"', 'wptribu' ), esc_html( $object->post_title ) ) . "\r\n\r\n";

		/* translators: %s: Post URL. */
		$notify_message .= sprintf( __( 'Go to the post: %s', 'wptribu' ), get_permalink( $object->ID ) ) . "\r\n";
	} elseif ( $is_comment ) {
		// Disable mentions filter.
		remove_filter( 'comment_text', __NAMESPACE__ . '\set_mentions', 100 );

		$content  = apply_filters( 'comment_text', $object->comment_content );

		// Enable mentions filter.
		add_filter( 'comment_text', __NAMESPACE__ . '\set_mentions', 100 );

		// Get mentions for the comment.
		$mentions = get_mentions( $content );

		/* translators: %s: Site title. */
		$subject = sprintf( __( '[%s] New comment mention', 'wptribu' ), $blogname );

		/* translators: %s: Post title. */
		$notify_message = sprintf( __( 'You have been mentioned into a comment of the post: "%s"', 'wptribu' ), esc_html( $object->post_parent->post_title ) ) . "\r\n\r\n";

		/* translators: %s: Comment URL. */
		$notify_message .= sprintf( __( 'Go to the comment: %s', 'wptribu' ), get_comment_link( $object ) ) . "\r\n";
	}

	if ( $mentions ) {
		$server_name = 'example.com';
		if ( isset( $_SERVER['SERVER_NAME'] ) ) {
			$server_name = strtolower( wp_unslash( $_SERVER['SERVER_NAME'] ) ); // phpcs:ignore
		}

		$wp_email = 'wordpress@' . preg_replace( '#^www\.#', '', $server_name );

		$object_id = 0;
		if ( $is_post && $object->ID ) {
			$object_id = $object->ID;
		} elseif ( $is_comment && $object->post_parent->ID ) {
			$object_id = $object->post_parent->ID;
		}

		$db_mentions = (array) get_post_meta( $object_id, '_wptribu_mentions' );
		$db_mentions = array_map( 'intval', $db_mentions );

		/**
		 * Add mentions.
		 */
		foreach ( $mentions as $user_id => $user_data ) {
			$is_new_mention = false;

			if ( $object_id ) {
				$is_new_mention = ! in_array( $user_id, $db_mentions, true );

				if ( $is_new_mention ) {
					add_post_meta( $object_id, '_wptribu_mentions', $user_id );
				}

				if ( $is_new_mention || $is_comment ) {
					$from     = "From: \"$user_data->name\" <$wp_email>";
					$reply_to = "Reply-To: \"$user_data->email\" <$user_data->email>";

					$message_headers  = "$from\n"
					. 'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . "\"\n";
					$message_headers .= $reply_to . "\n";

					wp_mail( $user_data->email, wp_specialchars_decode( $subject ), $notify_message, $message_headers );
				}
			}
		}
	}
}

/**
 * Inserts post mentions.
 *
 * @since 1.0.0
 *
 * @param int     $post_ID Post ID.
 * @param WP_Post $post    Post object.
 */
function insert_post_mentions( $post_ID = 0, $post = null ) {
	if ( ! isset( $post->post_status ) || 'publish' !== $post->post_status ) {
		return;
	}

	save_mentions( $post );
}
add_action( 'save_post', __NAMESPACE__ . '\insert_post_mentions', 10, 2 );

/**
 * Inserts post mentions.
 *
 * @since 1.0.0
 *
 * @param int        $id      The comment ID.
 * @param WP_Comment $comment Comment object.
 */
function insert_comment_mentions( $id = 0, $comment = null ) {
	if ( ! isset( $comment->comment_approved ) || 1 !== (int) $comment->comment_approved ) {
		return;
	}

	$post = get_post( $comment->comment_post_ID );
	$comment->post_parent = $post;

	save_mentions( $comment );
}
add_action( 'wp_insert_comment', __NAMESPACE__ . '\insert_comment_mentions', 10, 2 );

/**
 * Neutralize mention links from moderated links.
 *
 * @since 1.0.0
 *
 * @param int    $num_links The number of links found.
 * @param string $url       Comment author's URL. Included in allowed links total.
 * @param string $comment   Content of the comment.
 * @return int              The number of links found (without mention links).
 */
function comment_max_links_url( $num_links = 0, $url = '', $comment = '' ) {
	$pattern       = '/<a [^>]*' . addcslashes( home_url( '/author' ), '/' ) . '/i';
	$mention_links = preg_match_all( $pattern, $comment, $out );
	if ( $mention_links ) {
		$num_links -= $mention_links;
	}

	return $num_links;
}
add_filter( 'comment_max_links_url', __NAMESPACE__ . '\comment_max_links_url', 10, 3 );
