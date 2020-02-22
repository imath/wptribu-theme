<?php
/**
 * The Header template for Handbook pages.
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

get_template_part( 'header' );
?>
	<header id="masthead" class="site-header col-12" role="banner">
		<div class="site-branding">
			<p class="site-title"><?php esc_html_e( 'Handbook', 'wptribu-theme' ); ?></p>

			<nav id="site-navigation" class="navigation-main clear" role="navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'secondary',
						'fallback_cb'    => false,
						'depth'          => 1,
					)
				);
				?>
			</nav><!-- .navigation-main -->
		</div><!-- .site-branding -->
	</header><!-- #masthead -->
