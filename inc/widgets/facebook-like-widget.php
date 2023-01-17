<?php
add_action('widgets_init', 'facebook_like_load_widgets');

function facebook_like_load_widgets()
{
	register_widget('Facebook_Like_Widget');
}

class Facebook_Like_Widget extends WP_Widget {
	
	function Facebook_Like_Widget()
	{
		$widget_ops = array('classname' => 'facebook_like', 'description' => _kp_('Facebook sayfanız için kutu oluşturur.'));

		$control_ops = array('id_base' => 'facebook-like-widget');

		$this->WP_Widget('facebook-like-widget', _kp_('+ Keremiya Facebook Beğeni Kutusu'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$page_url = $instance['page_url'];
		$show_faces = isset($instance['show_faces']) ? 'true' : 'false';
		$show_stream = isset($instance['show_stream']) ? 'timeline' : 'false';
		$height = $instance['height'];
		
		if(!$height) {
			$height = '260';
		}
		
		echo $before_widget;
		if($page_url): ?>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0];
		  if (d.getElementById(id)) return;
		  js = d.createElement(s); js.id = id;
		  js.src = "//connect.facebook.net/tr_TR/sdk.js#xfbml=1&version=v2.5";
		  fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));</script>
		<div class="facebook-box">
		<div class="fb-page" data-href="<?php echo $page_url; ?>" data-tabs="<?php echo $show_stream; ?>" data-height="<?php echo $height; ?>" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="<?php echo $show_faces; ?>">
		<div class="fb-xfbml-parse-ignore"></div>
		</div>
		</div>	
		<?php endif;
		
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_url'] = $new_instance['page_url'];
		$instance['height'] = $new_instance['height'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['show_stream'] = $new_instance['show_stream'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => '', 'page_url' => '', 'width' => '240', 'height' => '260', 'color_scheme' => 'dark', 'show_faces' => 'on', 'show_stream' => false, 'show_header' => false);
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('page_url'); ?>"><?php _kp('Facebook Sayfa URL'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" value="<?php echo $instance['page_url']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _kp('Uzunluk'); ?>:</label>
			<input class="widefat" style="width: 100px;" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" value="<?php echo $instance['height']; ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_faces'], 'on'); ?> id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _kp('Yüzleri Göster'); ?> (faces)</label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_stream'], 'on'); ?> id="<?php echo $this->get_field_id('show_stream'); ?>" name="<?php echo $this->get_field_name('show_stream'); ?>" /> 
			<label for="<?php echo $this->get_field_id('show_stream'); ?>"><?php _kp('Akışı Göster'); ?> (stream)</label>
		</p>
		
	<?php
	}
}
?>