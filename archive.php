<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

if ( is_post_archive( 'post' ) ) {
	get_header( 'blog' );
} else {
	get_header();
}
?>

<?php if ( is_post_archive( 'post' ) ) : ?>
	<div class="blog-wrapper row gutters">
		<main id="main" class="site-main col-9" role="main">
<?php else : ?>
		<main id="main" class="site-main col-8" role="main">
<?php endif; ?>

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->

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

	</main><!-- #main -->
<?php if ( is_post_archive( 'post' ) ) :
	get_sidebar();
?>
	</div>
<?php else :
	get_sidebar();
endif;

get_footer();
