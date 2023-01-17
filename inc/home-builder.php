<?php 

function keremiya_get_home_cats($cat_data){

	switch( $cat_data['type'] ){
	
		case 'n':
			get_home_cats( $cat_data );
			break;
		
		case 's':
			echo '<script type="text/javascript" src="'.get_bloginfo('template_directory').'/js/scroll.js"></script>';
			get_home_scroll( $cat_data );
			break;
			
		case 'recent':
			get_home_recent( $cat_data );
			break;	
		
		case 'popular':
			get_home_popular( $cat_data );
			break;

		case 'cats':
			get_home_categories( $cat_data );
			break;

		case 'ads': ?>
			<div class="ad-home"><?php echo htmlspecialchars_decode(stripslashes ($cat_data['text']) ) ?></div>
			<div class="clear"></div>
		<?php
			break;
	}
}

function get_home_cats( $cat_data ){ ?>
<?php	
	$cat_ID = $cat_data['id'];
	$number = $cat_data['number'];
	$display = $cat_data['display'];
	$title = $cat_data['title'];
	$columns = $cat_data['columns'];
	$_GLOBAL['keremiya_columns'] = $columns;
	
	if(!$title)
		$title = get_the_category_by_ID($cat_ID);

	$query = $cat_data['query']; 
	if($query == "views") {
		$orderby = 'meta_value_num';
		$meta_key = $query;
	}

	$args = array(
		"cat" => $cat_ID,
		'posts_per_page' => $number,
		'showposts' => $number,
		'orderby' => $orderby,
		'meta_key' => $meta_key,
		);

	$cat_query = new WP_Query($args);

	if($cat_query->found_posts > $number) {
		$link = get_category_link($cat_ID);
		$link = '<a class="show-more" href="'.$link.'">'._k_("Daha fazlası").'</a>';
	}

    echo '<div class="film-content">';
    echo '<h2 class="title"><span>'.$title.'</span></h2>';
    // The Loop
    if ( $cat_query->have_posts() ) {
        echo '<div class="fix-'.$display.'_item fix_builder_cats clearfix">';
        while ( $cat_query->have_posts() ) {
            $cat_query->the_post();
            keremiya_get_layout($display);
        }
        echo '</div>';

    } else {
        echo '<p class="mt10 ml9">'._k_('Şuan için gösterilebilecek hiçbir film bulunamadı.').'</p>';
    }
    echo '</div>';

	wp_reset_query(); 
} 

function get_home_scroll( $cat_data ){ ?>
	
<?php

	$cat_ID = $cat_data['id'];
	$number = $cat_data['number'];
	$title = $cat_data['title'];

	$query = $cat_data['query']; 
	if($query == "views") {
		$orderby = 'meta_value_num';
		$meta_key = $query;
	}

	$args = array(
		"cat" => $cat_ID,
		"posts_per_page" => $number,
		'showposts' => $number,
		'orderby' => $orderby,
		'meta_key' => $meta_key,
		);

	$cat_query = new WP_Query($args); 

	if($cat_query->found_posts > $number) {
		$link = get_category_link($cat_ID);
		$link = '<a class="show-more" href="'.$link.'">'._k_("Daha fazlası").'</a>';
	}

	wp_reset_query(); 
}

function get_home_recent( $cat_data ){

	$exclude = $cat_data['exclude'];
	$number = $cat_data['number'];
	$title = $cat_data['title'];
	$display = $cat_data['display'];
	$pagenavi = $cat_data['pagenavi'];
	
	$args=array(
		'posts_per_page' => $number,
		'paged' => get_query_var('paged'), 
		'category__not_in' => $exclude
		); 

	$cat_query = new WP_Query($args);

    echo '<div class="film-content">';
    echo '<h2 class="title"><span>'.$title.'</span></h2>';
    // The Loop
    if ( $cat_query->have_posts() ) {
        echo '<div class="fix-'.$display.'_item fix_builder_recent clearfix list_items">';
        while ( $cat_query->have_posts() ) {
            $cat_query->the_post();
            keremiya_get_layout($display);
        }
        echo '</div>';

        keremiya_the_pagenavi($cat_query);

    } else {
        echo '<p class="mt10 ml9">'._k_('Şuan için gösterilebilecek hiçbir film bulunamadı.').'</p>';
    }
    echo '</div>';

	wp_reset_query();  
}

function get_home_popular( $cat_data ){

	$include = $cat_data['include'];
	$number = $cat_data['number'];
	$title = $cat_data['title'];
	$display = $cat_data['display'];
	$pagenavi = $cat_data['pagenavi'];
	$query = $cat_data['query']; 

	if($query == "views" || $query == "imdb" || $query == "_rating_average") {
		$orderby = 'meta_value_num';
		$meta_key = $query;
	}
	elseif ($query == "comment") {
		$orderby = "comment_count";
	}


	$args=array(
		'posts_per_page' => $number,
		'showposts' => $number,
		'orderby' => $orderby,
		'meta_key' => $meta_key,
		'category__in' => $include
		); 

	$cat_query = new WP_Query($args);
    echo '<div class="film-content">';
    echo '<h2 class="title"><span>'.$title.'</span></h2>';
    // The Loop
    if ( $cat_query->have_posts() ) {
        echo '<div class="fix-'.$display.'_item fix_builder_popular clearfix">';
        while ( $cat_query->have_posts() ) {
            $cat_query->the_post();
            keremiya_get_layout($display);
        }
        echo '</div>';

    } else {
        echo '<p class="mt10 ml9">'._k_('Şuan için gösterilebilecek hiçbir film bulunamadı.').'</p>';
    }
    echo '</div>';

    wp_reset_query(); 
}
function get_home_categories( $cat_data ){

	$exclude = $cat_data['exclude'];
	$args = array(
		'exclude' => $exclude
	);
	keremiya_home_list_categories($args); 
}
?>