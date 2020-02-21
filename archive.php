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
				if ( is_author() ) {
					if ( have_posts() ) {
						/* Queue the first post, that way we know
						* what author we're dealing with (if that is the case).
						*/
						the_post();
						printf(
							'<h1 class="page-title">%s</h1>',
							sprintf(
								/* Translators: %s is the author link */
								esc_html__( 'Author Archives: %s', 'wptribu-theme' ),
								'<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>'
							)
						);

						if ( (bool) get_the_author_meta( 'description' ) ) {
							printf(
								'<div class="author-description">%s</div><!-- .author-description -->',
								wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) )
							);
						}

						/* Since we called the_post() above, we need to
						* rewind the loop back to the beginning that way
						* we can run the loop properly, in full.
						*/
						rewind_posts();
					} elseif ( is_a( get_queried_object(), 'WP_User' ) ) {
						$user = get_queried_object();
						printf(
							'<h1 class="page-title">%s</h1>',
							sprintf(
								esc_html__( 'Author Archives: %s', 'wptribu-theme' ),
								'<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( $user->ID ) ) . '" title="' . esc_attr( $user->display_name ) . '" rel="me">' . esc_html( $user->display_name ) . '</a></span>'
							)
						);

						printf(
							'<div class="author-empty">%s</div>',
							esc_html__( 'This author has no posts yet.', 'wptribu-theme' )
						);
					}
				} else {
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );

					if ( is_category() && ! have_posts() && current_user_can( 'publish_posts' ) ) {
						printf(
							'<div class="taxonomy-empty">%s</div>',
							esc_html__( 'This category has no posts yet. Be the first to add one!', 'wptribu-theme' )
						);
					}
				}
				?>
			</header><!-- .page-header -->
			<div id="content">

			<?php do_action( 'breathe_post_editor' ); ?>
<?php else : ?>
		<main id="main" class="site-main col-8" role="main">
<?php endif; ?>

	<?php if ( have_posts() ) : ?>

		<?php if ( ! is_post_archive() ) : ?>
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
