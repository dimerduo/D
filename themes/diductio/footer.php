<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php
				/**
				 * Fires before the Twenty Fifteen footer text for footer customization.
				 *
				 * @since Twenty Fifteen 1.0
				 */
				do_action( 'diductio_credits' );
			?>
			<div class="footer-menu">
				<div class="fMenu-item inline">Сделано на Diductio platform. Управление знаниями и задачами.</div>
			</div>
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>
<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function(event) {
		$(function () {
			$('[data-toggle="tooltip"]').tooltip()
		});
	});
</script>
</body>
</html>
