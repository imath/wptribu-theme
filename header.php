<?php
/**
 * The Header template for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body id="wptribu-org" <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="wptribu-header">
	<div class="wrapper">
		<a class="skip-link screen-reader-text" href="#page-content"><?php esc_html_e( 'Skip to content', 'wptribu-theme' ); ?></a>
		<h1><a href="<?php echo esc_url( get_home_url( null, '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div style="clear:both"></div>
		<button id="mobile-menu-button" aria-expanded="false"><span class="screen-reader-text"><?php esc_html_e( 'Toggle Menu', 'wptribu-theme' ); ?></span></button>

		<?php

		wp_nav_menu(
			array(
				'theme_location' => 'header',
				'fallback_cb'    => false,
				'depth'          => 1,
				'menu_id'        => 'wptribu-header-menu',
				'container'      => '',
			)
		);
		?>
	</div>
</div>

<div id="page" class="site">
	<div id="page-content" class="site-content row gutters">
