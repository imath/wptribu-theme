<?php
/**
 * The sidebar template used in a handbook.
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

if ( ! is_active_sidebar( get_current_handbook() ) )
	return;
?>

    <aside id="secondary" class="widget-area col-3">
		<a href="#" id="secondary-toggle"></a>
		<div id="secondary-content">
			<?php do_action( 'before_handbook_sidebar' ); ?>

			<?php dynamic_sidebar( get_current_handbook() ); ?>
		</div>
	</aside><!-- #secondary -->
