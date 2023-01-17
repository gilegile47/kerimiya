<?php
ob_start();
/*
Template Name: İletişim Sayfası
*/
get_header(); ?>
<div id="content">
<div class="content wrapper clearfix">

	<div class="content-left">
		<div class="single-content page">
			<h1 class="title"><span><?php the_title(); ?></span></h1>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="article-content">
					<?php the_content(); ?>		
				</div>
			<?php endwhile; else: ?>
				<p><?php _k('Gösterilecek sayfa bulunamadı.'); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( comments_open() ): ?>
		<div class="single-content tabs">
			<div class="tab-buttons noselect">
				<li data-id="comments" class="tab comments-tab active"><span class="icon-chat iconfix"><?php _k('Yorumlar'); ?></span></li>
			</div>
		</div>
		<div class="single-content details">
			<div id="comments">
				<?php comments_template(); ?>
			</div>
		</div>
		<?php endif; ?>

	</div><!--content-left-->
	
	<div class="single-content sidebar">
		<?php get_sidebar(); ?>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>