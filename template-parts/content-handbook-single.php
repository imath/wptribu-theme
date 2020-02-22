<?php
/**
 * The template part for displaying content
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-meta"><?php entry_meta(); ?></div>

	<div class="entry-content">
		<?php
		the_content(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Continue reading%s &rarr;', 'wptribu-theme' ),
				sprintf(
					'<span class="screen-reader-text"> "%s"</span>',
					get_the_title()
				)
			)
		);

		wp_link_pages(
			array(
				'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wptribu-theme' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span>',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'wptribu-theme' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
		edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit%s', 'wptribu-theme' ),
				sprintf(
					'<span class="screen-reader-text"> "%s"</span>',
					get_the_title()
				)
			),
			'<span class="edit-link">',
			'</span>'
		);
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
