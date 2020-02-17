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
            <p class="site-title"><?php esc_html_e( 'Latest news', 'wptribu-theme' ); ?></a></p>

            <nav id="site-navigation" class="main-navigation" role="navigation">
                <button class="menu-toggle dashicons dashicons-arrow-down-alt2" aria-controls="primary-menu" aria-expanded="false" aria-label="<?php esc_attr_e( 'Primary Menu', 'wptribu-theme' ); ?>"></button>

				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'fallback_cb'    => false,
						'depth'          => 1,
						'container_id'   => 'primary-menu',
						'container'      => '',
					)
				);
				?>
            </nav><!-- #site-navigation -->
        </div><!-- .site-branding -->
    </header><!-- #masthead -->
