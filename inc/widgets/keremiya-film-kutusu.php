<?php // Keremiya Film Kutusu Başlangıçı
add_action('widgets_init', 'post_boxes_widgets');

function post_boxes_widgets() {
	register_widget('Keremiya_Kutu');
}

class Keremiya_Kutu extends WP_Widget {
	function Keremiya_Kutu() {
		$widget_ops = array('classname' => 'widget-movies', 'description' => _kp_("Kategori bazlı film listelemesi."));
		$this->WP_Widget( 'keremiya_kutu-widget', _kp_('+ Keremiya Film Kutusu'), $widget_ops);
	}

	function widget( $args, $instance ) {
		global $post;
		extract( $args );
		$title = apply_filters('widget_title', $instance['title'] );
		$posts_per_page = $instance['posts_per_page'];
		$cat = $instance['cat'];
		$post_sort = $instance['post_sort'];
		$post_order = $instance['post_order'];
		$diout = $instance['diout'];
		
		if($post_order == 'views')  {
			$orderby = 'meta_value_num';
			$meta_key = 'views';
		} elseif($post_order == 'comment')  {
			$orderby = 'comment_count';
		} elseif($post_order == 'imdb')  {
			$orderby = 'meta_value_num';
			$meta_key = 'imdb';
		} elseif($post_order == 'date')  {
			$orderby = 'date';
			$meta_key ='';
		} else {
			$orderby = 'date';
			$meta_key ='';
		}

		$NewQuery = new WP_Query(array( 'posts_per_page' => $posts_per_page, 'cat' => $cat, 'meta_key' => $meta_key, 'orderby' => $orderby, 'post_status' => 'publish', 'caller_get_posts' => 1, 'post_type' => 'post')); 

		// Widget Başlangıçı
		echo $before_widget;
		
		echo $before_title . $title . $after_title;

		if ($NewQuery->have_posts()) : 
		echo '<div class="film-content">';
			echo '<div class="fix-series_item">';
			while ($NewQuery->have_posts()) : $NewQuery->the_post();
				keremiya_get_layout('series');
			endwhile; 
			echo '</div>';
		echo '</div>';
			else: 
				echo '<p>'._k_("Şuan için gösterilebilecek hiçbir film bulunamadı.").'</p>'; 
		endif; 

		wp_reset_query();

		echo "\r\t<div class=\"clear\"></div>";
		echo $after_widget;
}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['cat'] = strip_tags( $new_instance['cat'] );
		$instance['posts_per_page'] = $new_instance['posts_per_page'];	
		$instance['post_sort'] = $new_instance['post_sort'];
		$instance['post_order'] = $new_instance['post_order'];
		return $instance;
	} 


	function form( $instance ) {
		$defaults = array( 'title' => '', 'cat' => '', 'posts_per_page' => 5, 'post_sort' => '', 'post_order' => 'date'); 
		$instance = wp_parse_args( (array) $instance, $defaults );
		
	$categories_obj = get_categories('hide_empty=0');
	$categories = array();
	foreach ($categories_obj as $pn_cat) {
		$categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
	}
	?>
	
	<p>
	<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _kp("Başlık"); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text">
	</p>
	
	<p>
	<label for="<?php echo $this->get_field_id( 'cat' ); ?>"><?php _kp("Kategori"); ?></label>
	<select class="widefat" id="<?php echo $this->get_field_id( 'cat' ); ?>" name="<?php echo $this->get_field_name( 'cat' ); ?>">
		<option value="" <?php if ( $instance['cat']  == $key) { echo ' selected="selected"' ; } ?>><?php _kp("Tüm Kategoriler"); ?></option>
			<?php foreach ($categories as $key => $option) { ?>
				<option value="<?php echo $key ?>" <?php if ( $instance['cat']  == $key) { echo ' selected="selected"' ; } ?>><?php echo $option; ?></option>
			<?php } ?>
	</select>
	</p>
	
	<p>
	<label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _kp("Gösterilecek Film Sayısı"); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php echo $instance['posts_per_page']; ?>" type="text">
	</p>
	
	<style type="text/css">
		.query-radio { margin-right: 3px; display: inline-block;}
	</style>
	<p>
	<label for="<?php echo $this->get_field_id( 'post_order' ); ?>"><?php _kp("Query Sıralaması"); ?></label>
	<br>
	<label class="query-radio"><input type="radio" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" value="date" <?php if ( $instance['post_order']  == 'date') { echo ' checked="checked"' ; } ?>><?php _kp("Tarih"); ?></label>
	<label class="query-radio"><input type="radio" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" value="views" <?php if ( $instance['post_order']  == 'views') { echo ' checked="checked"' ; } ?>><?php _kp("İzlenme"); ?></label>
	<label class="query-radio"><input type="radio" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" value="comment" <?php if ( $instance['post_order']  == 'comment') { echo ' checked="checked"' ; } ?>><?php _kp("Yorum"); ?></label>
	<label class="query-radio"><input type="radio" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" value="imdb" <?php if ( $instance['post_order']  == 'imdb') { echo ' checked="checked"' ; } ?>><?php _kp("IMDB"); ?></label>
	</p>

	<input type="hidden" name="widget-options" id="widget-options" value="1" />
	<?php
	}
} 
// Son ?>