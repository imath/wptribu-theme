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

	<div id="secondary-content">
		<?php do_action( 'before_sidebar' ); ?>

		<?php dynamic_sidebar( 'sidebar-o2' ); ?>
	</div>
</aside><!-- #secondary -->
