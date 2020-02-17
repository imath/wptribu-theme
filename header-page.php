<?php
/**
 * The Header template for pages in our theme.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

get_template_part( 'header' );
?>
    <header id="masthead" class="site-header col-12" role="banner">
        <div class="site-branding">
			<?php if ( page_has_parent() ) : ?>
            	<p class="site-title"><a href="<?php echo esc_url( get_permalink( get_page_parent() ) ); ?>" rel="bookmark"><?php echo get_the_title( get_page_parent() ); ?></a></p>
			<?php else : ?>
				<p class="site-title"><?php echo get_the_title( get_page_parent() ); ?></p>
			<?php endif; ?>

            <nav id="site-navigation" class="main-navigation" role="navigation">
                <button class="menu-toggle dashicons dashicons-arrow-down-alt2" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Primary Menu', 'wptribu-theme' ); ?>"></button>

                <?php page_menu(); ?>
            </nav><!-- #site-navigation -->
        </div><!-- .site-branding -->
    </header><!-- #masthead -->
