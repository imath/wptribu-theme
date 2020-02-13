<?php
/**
 * The blog's home template file.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

get_header( 'blog' );
?>

	<div class="blog-wrapper row gutters">
		<main id="main" class="site-main col-9" role="main">

			<?php do_action( 'breathe_post_editor' ); ?>

			<div id="content" class="entry-content">

				<?php
				if ( have_posts() ) :

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						get_template_part( 'template-parts/content' );
					endwhile;

					//breathe_content_nav( 'nav-below' );

				else :
					get_template_part( 'template-parts/content', 'none' );
				endif;
				?>

			</div><!-- #content -->

		</main><!-- #main -->

		<?php get_sidebar( 'o2' ); ?>

	</div><!-- .blog-wrapper -->

<?php
get_footer();
