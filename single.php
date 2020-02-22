<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

if ( 'post' === get_post_type() ) {
	get_header( 'blog' );
} else {
	get_header();
}
?>

<?php if ( 'post' === get_post_type() ) : ?>
	<div class="blog-wrapper row gutters">
		<main id="main" class="site-main col-9" role="main">
			<div id="content">
				<header class="page-header">
					<h2>
						<span class="controls">
							<?php do_action( 'breathe_view_controls' ); ?>
						</span>
					</h2>
				</header><!-- .page-header -->
<?php else : ?>
		<main id="main" class="site-main col-8" role="main">
<?php endif; ?>

			<?php
			while ( have_posts() ) :
				the_post();

				get_template_part( 'template-parts/content', 'single' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

				// Previous/next post navigation.
				the_post_navigation(
					array(
						// phpcs:disable WordPress.WhiteSpace.PrecisionAlignment.Found
						'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'wptribu-theme' ) . '</span> ' .
									'<span class="screen-reader-text">' . __( 'Next post:', 'wptribu-theme' ) . '</span> ' .
									'<span class="post-title">%title</span>',
						'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'wptribu-theme' ) . '</span> ' .
									'<span class="screen-reader-text">' . __( 'Previous post:', 'wptribu-theme' ) . '</span> ' .
									'<span class="post-title">%title</span>',
						// phpcs:enable WordPress.WhiteSpace.PrecisionAlignment.Found
					)
				);
				endwhile; // End of the loop.
			?>

<?php if ( 'post' !== get_post_type() ) : ?>
		</main><!-- #main -->

	<?php get_sidebar(); ?>

<?php else : ?>
			</div><!-- #content -->
		</main><!-- #main -->
		<?php get_sidebar( 'o2' ); ?>
	</div><!-- .blog-wrapper -->

<?php endif; ?>

<?php
get_footer();
