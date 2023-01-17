<?php
add_action('widgets_init', 'keremiya_kategori_widgets');

function keremiya_kategori_widgets()
{
	register_widget('Keremiya_Kategoriler');
}

class Keremiya_Kategoriler extends WP_Widget {
	
	function Keremiya_Kategoriler()
	{
		$widget_ops = array('classname' => 'widget_categories', 'description' => _kp_('Kategorileri isteğinize göre listeler.'));

		$control_ops = array('id_base' => 'keremiya-kategoriler');

		$this->WP_Widget('keremiya-kategoriler', _kp_('+ Keremiya Kategoriler'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$number = $instance['number'];
		$cloumn = $instance['cloumn'];
		
		echo $before_widget;

		if($title) {
			echo $before_title.$title.$after_title;
		}
		
		if($number)
			$args = 'orderby=name&hide_empty=0&title_li=&depth=1&child_of=' . $number;
		else
			$args = 'show_option_all&orderby=name&title_li=&depth=0';


		$class = 'scroll';
		if( $cloumn )
			$class .= ' full';

		echo '<ul class="'. $class .'">';
			wp_list_categories($args);
		echo '</ul>';

		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = $new_instance['number'];
		$instance['cloumn'] = $new_instance['cloumn'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => _kp_('Kategoriler'), 'number' => '', 'cloumn' => 0);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _kp('Başlık'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _kp('Ana Kategori ID'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
			<span><?php _kp("Boş bıraktığınızda tüm kategoriler listelenir."); ?></span>
		</p>

		<p>
			<input class="widefat" type="checkbox" id="<?php echo $this->get_field_id('cloumn'); ?>" name="<?php echo $this->get_field_name('cloumn'); ?>" <?php if ( $instance['cloumn'] ) { echo ' checked="checked"' ; } ?>/>
			<label for="<?php echo $this->get_field_id('cloumn'); ?>"><?php _kp('Tek sıra halinde göster'); ?></label>
		</p>
	<?php
	}
}
?>