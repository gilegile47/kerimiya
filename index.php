<?php get_header(); ?>

<div id="content">
<div class="content wrapper clearfix">

<?php
	// Sidebar Varmı
	$on_sidebar = keremiya_get_option('on_sidebar');
	if($on_sidebar):
		echo '<div class="content-left">';
	endif; 

	if( keremiya_get_option('on_slider') )
		keremiya_get_slider();

	if( keremiya_get_option('on_home') != 'builder' ){

		get_template_part( 'loop', 'index' );

	}else{

		$cats = get_option( 'keremiya_home_cats' ) ;
		if($cats)
			foreach ($cats as $cat)	keremiya_get_home_cats($cat);
		else _k( 'Anasayfa oluşturucuyu kullanabilirsiniz.' );

	}
	
	if($on_sidebar): 

		echo '</div>';
		echo '<div class="content-right">';
		get_sidebar();
		echo '</div>';

	endif;
?>

</div><!--content-wrapper-->
</div><!--#content-->
<?php get_footer(); ?>