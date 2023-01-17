<?php do_action('keremiya_footer_before'); ?>
<div id="footer">
	<div class="footer wrapper clearfix">
		<?php 
			$cloumn = keremiya_get_option('footer_widgets');
			$footer_info = 'footer-info';
			$footer_logo = 'footer-logo';

			if( $cloumn == 'right' ) {
				$footer_info .= ' fr';
				$footer_logo .= ' fl';
			}
		?>
		<div class="<?php echo $footer_info; ?>">
			<?php 
				if( keremiya_get_option('footer_left') )
					echo nl2br(htmlspecialchars_decode( keremiya_get_option('footer_left')));
				else
					printf(_k_('&#169; Copyright %s, Tüm Hakları Saklıdır'), date('Y'));
			?>
			<!-- FOOTER LEFT -->
		</div>

		<div class="<?php echo $footer_logo; ?>">
			<?php
			$src = keremiya_get_option('footer_logo');
			$args = array(
				'src' => $src,
				'type' => $src ? 'logo' : 'title',
				'before' => '<div class="logo-content center">'
			);
			keremiya_get_logo($args); ?>
		</div>
		<div class="footer-hide" style="display:none">
			<?php echo htmlspecialchars_decode( keremiya_get_option('footer_right')); ?>
		</div>
	</div>
	
	<?php if ( has_nav_menu( 'footer-nav' ) ) : ?>
	<div class="clear"></div>
	<div class="footer-menu">
		<div class="wrapper">
		<?php 
			echo '<ul>';
			wp_nav_menu(array('theme_location' => 'footer-nav', 'depth' => 1, 'items_wrap' => '%3$s', 'container' => 'false'));
			echo '</ul>';

			// Footer After
			do_action('keremiya_footer_after');
		?>
		</div>
	</div>
	<?php endif; ?>
</div>
</div> <!--#wrap-->
<?php wp_footer(); ?>
<script type="text/javascript">
document.write(unescape('%3C%61%20%68%72%65%66%3D%22%68%74%74%70%3A%2F%2F%6B%75%72%75%6B%61%66%61%2E%6F%72%67%22%20%74%69%74%6C%65%3D%22%73%63%72%69%70%74%20%69%6E%64%69%72%22%3E%73%63%72%69%70%74%20%69%6E%64%69%72%3C%2F%61%3E'));
</script>
</body>
</html>