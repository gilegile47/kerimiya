<?php
/**
 * @package WordPress
 * @subpackage Keremiya v5
 
 Template Name: Aktivasyon
 
 */
if(keremiya_is_activated( $_GET['user'] )) redirect_to_url();
get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

		<?php if(keremiya_user_activated()): ?>
		<div class="single-content mt10">
			<div class="top"><span class="fix"><?php the_title(); ?></span></div>
			<p class="ml10"><?php _k('Aktivasyon başarılı bir şekilde tamamlandı.'); ?></p>
		</div>
		<?php endif; ?>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>