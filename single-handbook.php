<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

get_header( 'handbook' );
?>

<div class="handbook-wrapper row gutters">
	<?php get_sidebar( 'handbook' ); ?>

	<main id="main" class="site-main col-9" role="main">
		<?php do_action( 'handbook_breadcrumbs' ); ?>

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content-handbook', 'single' );

			// Previous/next handbook page navigation.
			the_handbook_navigation();

			endwhile; // End of the loop.
		?>
	</main><!-- #main -->
</div><!-- .handbook-wrapper -->

<?php
get_footer();
