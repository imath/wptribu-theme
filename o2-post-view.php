<?php
/**
 * The customized o2 post view.
 *
 * Uses the Theme post navigation instead of the one provided
 * by o2.
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

?>
<div class="o2-post"></div>
<div class="o2-post-comments"></div>
<div class="o2-post-comment-controls"></div>

<# if ( data.showNavigation ) { #>
	<nav class="navigation post-navigation" role="navigation" aria-label="Publications">
		<h2 class="screen-reader-text"><?php esc_html_e( 'Post Navigation', 'wptribu-theme' ); ?></h2>
		<div class="nav-links">
		<# if ( data.hasPrevPost ) { #>
			<div class="nav-previous">
				<a href="{{ data.prevPostURL }}" title="{{ data.prevPostTitle }}" rel="prev">
					<span class="meta-nav" aria-hidden="true"><?php echo esc_html_x( 'Previous', 'Post Navigation', 'wptribu-theme' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Previous post:', 'wptribu-theme' ); ?></span>
					<span class="post-title">{{{ data.prevPostTitle }}}</span>
				</a>
			</div>
		<# } #>
		<# if ( data.hasNextPost ) { #>
			<div class="nav-next">
				<a href="{{ data.nextPostURL }}" title="{{ data.nextPostTitle }}" rel="next">
					<span class="meta-nav" aria-hidden="true"><?php echo esc_html_x( 'Next', 'Post Navigation', 'wptribu-theme' ); ?></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Next post:', 'wptribu-theme' ); ?></span>
					{{{ data.nextPostTitle }}}
				</a>
			</div>
		<# } #>
		</div>
	</nav>
<# } #>
