<?php
add_action('widgets_init', 'keremiya_today_movie_widgets');

function keremiya_today_movie_widgets()
{
	register_widget('keremiya_today_movie');
}

class keremiya_today_movie extends WP_Widget {
	
	function keremiya_today_movie()
	{
		$widget_ops = array('classname' => 'widget_today-movie', 'description' => _kp_('Günün filmi özelliği'));

		$control_ops = array('id_base' => 'keremiya-today-movie');

		$this->WP_Widget('keremiya-today-movie', _kp_('+ Keremiya Günün Filmi'), $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', $instance['title']);
		$id = $instance['id'];
		
		echo $before_widget;

		if($title)
			echo $before_title . $title . $after_title;
	
		if($id) { 

		    $args = array(
		        'p' => $id
		    );
		    
		    $the_query = new WP_Query( $args );
		    // The Loop
			if ( $the_query->have_posts() ):

			while ( $the_query->have_posts()) :  $the_query->the_post(); ?>
	<div class="single-content movie today">
        <div class="info-left">
            <a href="<?php the_permalink(); ?>">
            <div class="poster">
                <?php keremiya_get_image( array('size' => 'anasayfa-resim', 'post_ID' => $post_ID) ); ?>
            </div>
            </a>
        </div>

        <div class="info-right">
            <div class="title">
                <div class="name"><a href="<?php echo get_the_permalink( $post_ID ); ?>" title="<?php echo get_the_title( $post_ID ); ?>"><?php echo get_the_title( $post_ID ); ?></a></div>
                <?php 
                    $release = keremiya_get_meta('yapim');
                    if ( $release ):
                        echo '<div class="release">';
                        echo "({$release})";
                        echo '</div>';
                    endif;
                ?>
            </div>

            <div class="cast">
                <?php
                    $director = keremiya_get_meta('yonetmen');
                    if ( $director ):
                        echo '<div class="director">';
                        echo "<span>"._k_('Yönetmen').":</span> {$director}";
                        echo '</div>';
                    endif;
                ?>
                <?php
                    $actor = keremiya_get_meta('oyuncular');
                    if ( $actor ):
                        echo '<div class="actor">';
                        echo "<span>"._k_('Oyuncular').":</span> {$actor}";
                        echo '</div>';
                    endif;
                ?>
            </div>

            <div class="rating">

                <div class="vote">
                    <div class="site-vote">
                        <span class="icon-star"><span><?php echo keremiya_get_average( $post_ID ); ?></span></span>
                    </div>
                </div>

                <div class="rating-bottom">
                <?php
                    $imdb_rating = keremiya_get_meta('imdb');
                    if ( $imdb_rating ):
                        echo '<span class="imdb-rating">';
                        echo "{$imdb_rating} <small>"._k_('IMDB Puanı')."</small>";
                        echo '</span>';
                    endif;
                ?>
                <?php
                    $num_comments = get_comments_number();
                    if ( $num_comments ):
                        echo '<span class="comments-number">';
                        echo "{$num_comments} <small>"._k_('Yorum')."</small>";
                        echo '</span>';
                    endif;
                ?>
                <?php
                    $views = keremiya_izlenme();
                    if ( $views ):
                        echo '<span class="views-number">';
                        echo "{$views} <small>"._k_('Görüntülenme')."</small>";
                        echo '</span>';
                    endif;
                ?>
                </div>
            </div>
        </div>
        </div><!--today-movie-->
			<?php endwhile; else: ?>
            	<p class="mt10"><?php _k('Bugün için önerilen film bulunmuyor.'); ?></p>
        	<?php endif;

		} else {
			echo _k_('Şuan için gösterilebilecek hiçbir film bulunamadı.');
		}

		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;

		$instance['title'] = strip_tags($new_instance['title']);
		$instance['id'] = $new_instance['id'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => _kp_('Günün Filmi'), 'id' => '');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _kp('Başlık'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('id'); ?>"><?php _kp('ID'); ?>:</label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" value="<?php echo $instance['id']; ?>" />
			<span><?php _kp("Film ID numarası"); ?></span>
		</p>
	<?php
	}
}
?>