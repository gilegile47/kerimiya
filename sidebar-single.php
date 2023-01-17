<?php 

do_action('keremiya_sidebar_before');

	// İlgili Filmler Listelenir
    $seri = keremiya_get_meta('seri');
    if($seri) {
    $args = array (
        'posts_per_page' => 15,
        'post__not_in'  => array(get_the_ID()),
        'meta_query'    => array(
            array(
                'key'       => 'seri',
                'value'     => $seri,
            ),
        ),
    );

    // The Query
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) {
        echo '<div class="sidebar-content"><div id="similar-movies" class="clearfix"><div class="top"><span>'._k_('Serinin Diğer Filmleri').'</span></div><ul class="flexcroll">';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            echo '
				<li>
					<a href="'.get_the_permalink().'" title="'.get_the_title().'">
					<div class="info-left">
						<div class="poster" style="background: url('.keremiya_get_image(array('type' => 'url', 'size' => 'izlenen-resim')).') no-repeat center center" title="'.get_the_title().'"></div>
					</div>

					<div class="info-right">
						<div class="title">'.get_the_title().'</div>
						<div class="release">'.keremiya_get_meta('yapim').'</div>
					</div>
					</a>
				</li>
            ';
            $seri_ids[] = get_the_ID();
        }
        echo '</ul></div></div>';
    }
    wp_reset_postdata();
    }

    // İlgili Haberler Listelenir
    $news = keremiya_get_meta('news');
    $args = array (
        'posts_per_page' => 5,
        'meta_query'    => array(
            array(
                'key'       => 'news',
                'value'     => $news,
            ),
        ),
        'news_title' => _k_('İlgili Haberler'),
    );

    if($news) {
	    echo '<div class="sidebar-content">';
		keremiya_sidebar_latest_news($args);
		echo '</div>';
	}

    // İlgili Filmler Listelenir
    if( keremiya_get_option('similar') ):

    $cat = keremiya_get_meta('similar');
    $category_ids = array();
    if(!$cat) {
        $categories = get_the_category( get_the_ID() );
        if ($categories) { 
                
            foreach($categories as $individual_category)
                $category_ids[] = $individual_category->term_id;
        }
    } else {
        $category_ids[] = $cat;
    }

    $not_in = get_the_ID();
    $not_in = array( $not_in );

    if($seri_ids) {
        $seri_ids[] = get_the_ID();
        $not_in = $seri_ids;
    }

    $args = array (
        'posts_per_page' => keremiya_get_option('similar_number'),
        'category__in'  => $category_ids,
        'post__not_in'  => $not_in,
    );

    // The Query
    $the_query = new WP_Query( $args );

    // The Loop
    if ( $the_query->have_posts() ) {
        echo '<div class="sidebar-content"><div id="similar-movies" class="clearfix"><div class="top"><span>'._k_('Benzer Filmler').'</span></div><ul class="flexcroll">';
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            echo '
                <li>
                    <a href="'.get_the_permalink().'" title="'.get_the_title().'">
                    <div class="info-left">
                        <div class="poster"><img src="'.keremiya_get_image(array('type' => 'url', 'size' => 'izlenen-resim')).'" alt="'.get_the_title().'" width="60px" height="70px" /></div>
                    </div>

                    <div class="info-right">
                        <div class="title">'.get_the_title().'</div>
                        <div class="release">'.keremiya_get_meta('yapim').'</div>
                    </div>
                    </a>
                </li>
            ';
        }
        echo '</ul></div></div>';
    }
    wp_reset_postdata();

    endif;

    if(is_active_sidebar('single')) dynamic_sidebar('single'); 
?>