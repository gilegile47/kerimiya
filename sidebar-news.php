<?php do_action('keremiya_sidebar_before'); ?>

<div class="sidebar-content list-categories">
	<div class="tags">
	<div class="top">
		<span><?php _k('Kategoriler'); ?></span>
	</div>
	<ul>
		<?php keremiya_get_news_categories( keremiya_news_category_name() ); ?>
	</ul>
	</div>
</div>

<?php if(is_single()): ?>
<div class="sidebar-content latest-news">
	<?php
	global $post;
	$args = array(
		'posts_per_page' => 5,
		'post__not_in' => array($post->ID),
	);

	keremiya_sidebar_latest_news($args); 
	?>
</div>
<?php endif; ?>

<?php
	if(is_active_sidebar('news')) {
		dynamic_sidebar('news');
	}else {
		dynamic_sidebar('main');
	}
?>