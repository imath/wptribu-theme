<?php
/**
 * The customized o2 no posts post view.
 *
 * Uses Feedback messages more consistent with the context.
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

?>
<div class="o2-post">
	<?php if ( o2_selected_filter_is_mentions() ) : ?>
		<p><?php esc_html_e( 'You currently have no mentions.', 'wptribu-theme' ); ?></p>
	<?php elseif ( o2_selected_filter_is_resolved() ) : ?>
		<p><?php esc_html_e( 'There are no resolved discussions so far.', 'wptribu-theme' ); ?></p>
	<?php elseif ( o2_selected_filter_is_unresolved() ) : ?>
		<p><?php esc_html_e( 'There are no unresolved discussions so far.', 'wptribu-theme' ); ?></p>
	<?php else : ?>
		<p>{{ data.text }}</p>
	<?php endif; ?>
</div>
