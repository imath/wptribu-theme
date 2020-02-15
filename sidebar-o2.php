<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

?>

<aside id="secondary" class="widget-area col-3">
	<div id="primary-modal"></div>

	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
