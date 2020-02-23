<?php
/**
 * Template Name: Landing
 *
 * The template for displaying the About community page.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package  WPTribu\Theme
 */

namespace WPTribu\Theme;

get_header();
the_post();
?>

	<main id="main" class="site-main col-12" role="main">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<h1 class="entry-title"><?php the_site_description(); ?></h1>

				<div class="dashicons dashicons-wordpress-alt"></div>

				<p class="entry-description">
					<?php esc_html_e( 'The freedom to meet. The freedom to connect. The freedom to contribute to WordPress.', 'wptribu-theme' ); ?>
				</p>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php the_content(); ?>

				<div class="home-landing">

					<div class="inner-wrap section">

						<div class="box box-wordcamp">
							<h3 class="widget-title"><img class="icon icon-wordcamp" src="<?php the_svg_src( 'tickets' ); ?>" width="150" height="150"><?php esc_html_e( 'WordCamp', 'wptribu-theme' ); ?></h3>
							<p class="widget-description"><?php esc_html_e( 'Before, during or after.', 'wptribu-theme' ); ?></p>
						</div>

						<div class="box box-meetup">
							<h3 class="widget-title"><img class="icon icon-wordcamp" src="<?php the_svg_src( 'groups' ); ?>" width="150" height="150"><?php esc_html_e( 'Meetup', 'wptribu-theme' ); ?></h3>
							<p class="widget-description"><?php esc_html_e( 'During a local group meeting.', 'wptribu-theme' ); ?></p>
						</div>

						<div class="box box-contributor-meeting">
							<h3 class="widget-title"><img class="icon icon-wordcamp" src="<?php the_svg_src( 'wptribu' ); ?>" width="150" height="150"><?php esc_html_e( 'asap!', 'wptribu-theme' ); ?></h3>
							<p class="widget-description"><?php esc_html_e( 'Morning, afternoon, day, evening.', 'wptribu-theme' ); ?></p>
						</div>

					</div>

					<?php call_to_action(); ?>

				</div>

				<?php
				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wptribu-theme' ),
						'after'  => '</div>',
					)
				);
				?>
			</div><!-- .entry-content -->

			<footer class="entry-footer">
				<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'wptribu-theme' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
				?>
			</footer><!-- .entry-footer -->
		</article>

	</main><!-- #main -->

<?php
get_footer();
