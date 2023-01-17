<?php get_header(); ?>

<div id="content">
<div class="content wrapper clearfix">

	<div class="film-content search">
	<?php 
		$args = array(
			's' => get_query_var('s'),
			'post_type' => 'post',
		);
		keremiya_category_list_movies($args); 

	?>
	</div>

	<div class="sidebar-content search">
	<?php 
		$args = array(
			's' => get_query_var('s'),
			'post_type' => 'news',
			'news_title' => 'İlgili haberler',	
		);
		keremiya_sidebar_latest_news($args); 

	?>
	</div>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>