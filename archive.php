<?php get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

	<div class="content-left">
		<div class="film-content">
		<h1 class="title">
			<span>
				<?php
						if ( is_day() ) :
							printf(_k_('Günlük Arşiv: %s'), get_the_date());

						elseif ( is_month() ) :
							printf(_k_('Aylık Arşiv: %s'), get_the_date('F Y'));

						elseif ( is_year() ) :
							printf(_k_('Yıllık Arşiv: %s'), get_the_date('Y'));

						elseif ( is_tag() ):
							single_tag_title();
						else :
							wp_title('', true);

						endif;
				?>
			</span>
		</h1>
			<div class="fix-<?php echo keremiya_get_option('home_layout'); ?>_item fix_taxonomy clearfix list_items">
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<?php keremiya_get_layout(keremiya_get_option('home_layout')); ?>
				<?php endwhile; else: ?>
					<p><?php _k('Henüz hiç film eklenmemiş.'); ?></p>
				<?php endif; ?>
			</div>

			<?php keremiya_the_pagenavi(); ?>
		</div>
	</div>

	<div class="content-right">
		<div class="single-content sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>