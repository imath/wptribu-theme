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
                <h1 class="entry-title"><?php esc_html_e( 'Thanks for sharing some of your time with the wpTribe', 'wptribu' ); ?></h1>

                <p class="entry-description">
                    <?php esc_html_e( 'The freedom to meet. The freedom to connect. The freedom to contribute to WordPress.', 'wptribu' ); ?>
                </p>
            </header><!-- .entry-header -->

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'wptribu' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div><!-- .entry-content -->

            <footer class="entry-footer">
                <?php
                edit_post_link(
                    sprintf(
                        /* translators: %s: Name of current post */
                        esc_html__( 'Edit %s', 'wptribu' ),
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
