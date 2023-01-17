<?php
/**
 * Keremiya v5 Recent Comments Widget
 *
 * Display the most recent comments.
 * 
 * @package Keremiya
 * @subpackage Widgets
 * @since Keremiya 5.2
 */
 
class Keremiya_Widget_Comments extends WP_Widget {
	
	function Keremiya_Widget_Comments() {
	
		$widget_ops = array( 'classname' => 'widget-comments', 'description' => _kp_("Yeni yorumları göster") );
		$control_ops = array( 'id_base' => "keremiya-comments" );
		$this->WP_Widget( "keremiya-comments", _kp_("+ Keremiya Yorumlar"), $widget_ops, $control_ops );
		$this->alt_option_name = "keremiya_widget_comments";

		add_action( 'comment_post', array(&$this, 'flush_widget_cache') );
		add_action( 'transition_comment_status', array(&$this, 'flush_widget_cache') );
	}

	function flush_widget_cache() {
		wp_cache_delete("keremiya_widget_comments", 'widget');
	}
	
	function widget( $args, $instance ) {
		
		global $comments, $comment;

		$cache = wp_cache_get("keremiya_widget_comments", 'widget');

		if ( ! is_array( $cache ) )
			$cache = array();

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

 		extract($args, EXTR_SKIP);
 		$output = '';
		$title = apply_filters('widget_title', $instance['title']);
		
		
 		$output .= $before_widget;
		if ( $title ) {
			$output .= $before_title . $title . $after_title;
		}
		$output .= keremiya_list_comments_widget($instance,false);
		$output .= $after_widget;

		echo $output;
		
		$cache[$args['widget_id']] = $output;
		wp_cache_set("keremiya_widget_comments", $cache, 'widget');
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance = $new_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['show_date'] = isset($new_instance['show_date']) ? 1 : 0;
		$instance['show_avatar'] = isset($new_instance['show_avatar']) ? 1 : 0;
		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions["keremiya_widget_comments"]) )
			delete_option("keremiya_widget_comments");

		return $instance;
	}
	
	function form( $instance ) {
		$defaults = array( 
			'title' => _kp_('Son Yorumlar'), 
			'number' => 5,
			'show_avatar' => true,
			'show_date' => true,
			'comment_length' => 80
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _kp('Başlık'); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _kp('Yorum Sayısı'); ?></label>
			<input class="small-text" type="text" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
			<input class="checkbox" type="checkbox" <?php checked( $instance['show_date'], true ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" /> <?php _kp( 'Yorum Zamanı Göster' ); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'comment_length' ); ?>"><?php _kp('Yorum Uzunluğu'); ?></label>
			<input size="4" type="text" id="<?php echo $this->get_field_id( 'comment_length' ); ?>" name="<?php echo $this->get_field_name( 'comment_length' ); ?>" value="<?php echo $instance['comment_length']; ?>" />
		</p>
		<?php
	}
}

/** 
 * Function to return the most recent comments
 *
 * @uses get_comments() return array List of comments
 */
function keremiya_list_comments_widget($args = '',$echo = false) {
	global $comments, $comment;
	
	$defaults = array( 
		'title' =>  _kp_('Son Yorumlar'), 
		'number' => 5,
		'show_date' => true ,
		'show_avatar' => true,
		'avatar_size' => 30,
		'comment_length' => 80
	);
	
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );

	$comments =  get_comments(array(
		'number' => $number,
		'status' => 'approve',
		'type' => 'comment'
	));
		
	$output = '<ul class="has-avatar">';
	
	if($comments) {
		foreach ($comments as $comment) {
			$output .=  '<li>';

			$output .= '<a href="'.get_comment_link().'">';
			$output .=  get_avatar($comment->comment_author_email, $avatar_size, keremiya_custom_gravatar());
			$output .= '</a>';
				
			$output .= '<div class="data">';
			$output .= '<span class="author"><a href="'.get_comment_link().'">'.get_comment_author().'</a></span> ';
					
			if($show_date)
			$output .= '<span class="date">'.keremiya_zaman('comment').'</span> ';
			$output .= '<div class="clear"></div>';
			$output .= '<p class="excerpt">'.mb_strimwidth(strip_tags(apply_filters('comment_content', $comment->comment_content)), 0, $comment_length, "...").'</p>';
				
			$output .= '</div></li>';
		}
	}
	
	$output .= '</ul>';
	
	if($echo)
		echo $output;
	else
		return $output;
}

register_widget('Keremiya_Widget_Comments');
?>