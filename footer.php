<?php
/**
 * The template for displaying the footer.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WPTribu\Theme
 */

namespace WPTribu\Theme;

?>

	</div><!-- #content -->
</div><!-- #page -->

<div id="wptribu-footer">
	<div class="wrapper">
		<div class="footer-sections">
			<div class="footer-credits">
				&copy;
					<?php
					echo esc_html(
						date_i18n(
							/* translators: Copyright date format, see https://secure.php.net/date */
							_x( 'Y', 'copyright date format', 'wptribu-theme' )
						)
					);
					?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
				&nbsp;|&nbsp;

				<span class="powered-by-wordpress">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'wptribu-theme' ) ); ?>">
						<?php esc_html_e( 'Powered by WordPress', 'wptribu-theme' ); ?>
					</a>
				</span><!-- .powered-by-wordpress -->

			</div><!-- .footer-credits -->

			<div class="footer-utils">
				<a class="to-the-top" href="#wptribu-header">
					<span class="to-the-top-long">
						<?php
						/* translators: %s: HTML character for up arrow */
						printf( esc_html__( 'To the top %s', 'wptribu-theme' ), '<span class="arrow" aria-hidden="true">&uarr;</span>' );
						?>
					</span><!-- .to-the-top-long -->
				</a><!-- .to-the-top -->
			</div>
		</div>
	</div>
	<p class="cip cip-image"><?php esc_html_e( 'Code is Poetry.', 'wptribu-theme' ); ?></p>
</div>

<?php wp_footer(); ?>

</body>
</html>
