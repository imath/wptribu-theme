<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

if ( is_post_archive() ) {
	get_header( 'blog' );
} else {
	get_header();
}
?>

<?php if ( is_post_archive() ) : ?>
	<div class="blog-wrapper row gutters">
		<main id="main" class="site-main col-9" role="main">
			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );

					if ( ! have_posts() && current_user_can( 'publish_posts' ) ) {
						echo sprintf(
							'<div class="taxonomy-empty">%s</div>',
							esc_html__( 'This category has no posts yet. Be the first to add one!', 'wptribu' )
						);
					}
				?>
			</header><!-- .page-header -->
			<div id="content">

			<?php do_action( 'breathe_post_editor' ); ?>
<?php else : ?>
		<main id="main" class="site-main col-8" role="main">
<?php endif; ?>

	<?php if ( have_posts() ) : ?>

		<?php if ( ! is_post_archive( 'post' ) ) : ?>
			<header class="page-header">
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->
		<?php endif; ?>

		<?php
		/* Start the Loop */
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content' );
		endwhile;

		the_posts_pagination();

	else :
		get_template_part( 'template-parts/content', 'none' );

	endif;
	?>

<?php if ( ! is_post_archive() ) : ?>
	</main><!-- #main -->

<?php
	get_sidebar();

else : ?>
			</div><!-- #content -->
		</main><!-- #main -->
		<?php get_sidebar( 'o2' ); ?>
	</div><!-- .blog-wrapper -->

<?php endif;

get_footer();
