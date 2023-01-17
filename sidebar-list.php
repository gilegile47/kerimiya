<div class="fix_list-categories">

	<div class="tags sort">
		<h4 class="title"><span><?php _k('Sırala'); ?></span></h4>
		<ul>
		<?php keremiya_list_sort(); ?>
		</ul>
	</div>

	<?php
	// Costumize
	if(is_active_sidebar('category')) {
		dynamic_sidebar('category');
	} else {
	?>
	
	<?php
		$gtag = 'tags';
		if( keremiya_get_option('category_list_one') )
			$gtag .= ' full';
	?>
	<div class="<?php echo $gtag; ?>">
		<h4 class="title"><span><?php _k('Türler'); ?></span></h4>

		<?php
			$args = array(
				'style' => 'list',
				'title_li' => '',
				'separator' => '',
				'current_category' => '',
				'orderby' => 'name',
			);
			echo '<ul class="scroll genre">';
			wp_list_categories($args);
			echo '</ul>';
		?>
	</div>

	<?php if( keremiya_get_option('category_list_lang') ): ?>
	<div class="tags">
		<h4 class="title"><span><?php _k('Film Dili'); ?></span></h4>
		<ul>
		<?php keremiya_list_langs(); ?>
		</ul>

	</div>
	<?php endif; ?>

	<?php if( keremiya_get_option('category_list_year') ): ?>
	<div class="tags">
		<h4 class="title"><span><?php _k('Yapım Yılı'); ?></span></h4>
		
		<ul>
		<?php keremiya_list_years(); ?>
		</ul>

	</div>
	<?php endif; ?>

	<?php }; #is_active_sidebar ?>

</div>