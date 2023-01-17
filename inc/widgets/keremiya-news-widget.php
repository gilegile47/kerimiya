<?php // Keremiya Film Kutusu Başlangıçı
add_action('widgets_init', 'Keremiya_Kutu_News_widgets');

function Keremiya_Kutu_News_widgets() {
    register_widget('Keremiya_Kutu_News');
}

class Keremiya_Kutu_News extends WP_Widget {
    function Keremiya_Kutu_News() {
        $widget_ops = array('classname' => 'widget-news', 'description' => _kp_("Haber listelemesi."));
        $this->WP_Widget( 'keremiya_kutu_news-widget', _kp_('+ Keremiya Haber Kutusu'), $widget_ops);
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
        
        // Widget Başlangıçı
        echo $before_widget;
        
        if($post_order == 'comment')  {
            $orderby = 'comment_count';
        } else {
            $orderby = 'date';
            $meta_key ='';
        }

        $args = array(
        'posts_per_page' => $posts_per_page,
        'news_title' => $title,
        'meta_key' => $meta_key,
        'orderby' => $orderby
        );

        keremiya_sidebar_latest_news($args);


        /*if ($NewQuery->have_posts()) : 
        echo '<div class="single-content news">';
            $icon = keremiya_get_meta('icon');
            $icon = (!$icon)?"star":$icon;
            echo '<div class="news-content">';
            while ( $NewQuery->have_posts() ) {
                $NewQuery->the_post();
                echo '
                <a href="'.get_the_permalink().'" class="item" title="'.get_the_title().'">
                    <div class="child-content">
                        <div class="col-1"><span class="icon-'.$icon.'"></span></div>
                        <div class="col-2">
                            <div class="title">'.get_the_title().'</div>
                            <div class="date">'.get_the_time("d M").'</div>
                        </div>
                    </div>
                </a>
                ';
            }
            echo '</div>';
        echo '</div>';
        else: 
            echo '<p>'._k_("Hiç haber bulunamadı.").'</p>'; 
        endif; 

        wp_reset_query();*/

        echo "\r\t<div class=\"clear\"></div>";
        echo $after_widget;
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['posts_per_page'] = $new_instance['posts_per_page'];  
        $instance['post_sort'] = $new_instance['post_sort'];
        $instance['post_order'] = $new_instance['post_order'];
        return $instance;
    } 


    function form( $instance ) {
        $defaults = array( 'title' => '', 'cat' => '', 'posts_per_page' => 5, 'post_sort' => '', 'post_order' => 'date'); 
        $instance = wp_parse_args( (array) $instance, $defaults );
    ?>
    
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _kp("Başlık"); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" type="text">
    </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'posts_per_page' ); ?>"><?php _kp("Gösterilecek haber sayısı"); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id( 'posts_per_page' ); ?>" name="<?php echo $this->get_field_name( 'posts_per_page' ); ?>" value="<?php echo $instance['posts_per_page']; ?>" type="text">
    </p>
    
    <style type="text/css">
        .query-radio { margin-right: 3px; display: inline-block;}
    </style>
    <p>
    <label for="<?php echo $this->get_field_id( 'post_order' ); ?>"><?php _kp("Query Sıralaması"); ?></label>
    <br>
    <label class="query-radio"><input type="radio" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" value="date" <?php if ( $instance['post_order']  == 'date') { echo ' checked="checked"' ; } ?>><?php _kp("Tarih"); ?></label>
    <label class="query-radio"><input type="radio" id="<?php echo $this->get_field_id( 'post_order' ); ?>" name="<?php echo $this->get_field_name( 'post_order' ); ?>" value="comment" <?php if ( $instance['post_order']  == 'comment') { echo ' checked="checked"' ; } ?>><?php _kp("Yorum"); ?></label>
     </p>

    <input type="hidden" name="widget-options" id="widget-options" value="1" />
    <?php
    }
} 
// Son ?>