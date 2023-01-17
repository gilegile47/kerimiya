<?php
// Pagenavi
function keremiya_pagenavi($query = '', $before = '', $after = '') {

    global $wp_query;
    
    if($query) 
        $wp_query = $query;

    $pagenavi_options = array();
    $pagenavi_options['pages_text'] = ('Page %CURRENT_PAGE% of %TOTAL_PAGES%:');
    $pagenavi_options['current_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['page_text'] = '%PAGE_NUMBER%';
    $pagenavi_options['first_text'] = _k_('İlk');
    $pagenavi_options['last_text'] = _k_('Son');
    $pagenavi_options['next_text'] = _k_('İleri &raquo;');
    $pagenavi_options['prev_text'] = _k_('&laquo; Geri');
    $pagenavi_options['dotright_text'] = '...';
    $pagenavi_options['dotleft_text'] = '...';
    $pagenavi_options['num_pages'] = 5; //continuous block of page numbers
    $pagenavi_options['always_show'] = 0;
    $pagenavi_options['num_larger_page_numbers'] = 0;
    $pagenavi_options['larger_page_numbers_multiple'] = 5;
 
    if (!is_single()) {
        $request = $wp_query->request;
        $posts_per_page = intval(get_query_var('posts_per_page'));
        $paged = intval(get_query_var('paged'));
        $numposts = $wp_query->found_posts;
        $max_page = $wp_query->max_num_pages;
 
        if(empty($paged) || $paged == 0) {
            $paged = 1;
        }
 
        $pages_to_show = intval($pagenavi_options['num_pages']);
        $larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
        $larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start = floor($pages_to_show_minus_1/2);

        $half_page_end = ceil($pages_to_show_minus_1/2);
        $start_page = $paged - $half_page_start;
 
        if($start_page <= 0) {
            $start_page = 1;
        }
 
        $end_page = $paged + $half_page_end;
        if(($end_page - $start_page) != $pages_to_show_minus_1) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }
        if($end_page > $max_page) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }
        if($start_page <= 0) {
            $start_page = 1;
        }

        $larger_per_page = $larger_page_to_show*$larger_page_multiple;

        $larger_start_page_start = (keremiya_round_num($start_page, 10) + $larger_page_multiple) - $larger_per_page;
        $larger_start_page_end = keremiya_round_num($start_page, 10) + $larger_page_multiple;
        $larger_end_page_start = keremiya_round_num($end_page, 10) + $larger_page_multiple;
        $larger_end_page_end = keremiya_round_num($end_page, 10) + ($larger_per_page);
 
        if($larger_start_page_end - $larger_page_multiple == $start_page) {
            $larger_start_page_start = $larger_start_page_start - $larger_page_multiple;
            $larger_start_page_end = $larger_start_page_end - $larger_page_multiple;
        }
        if($larger_start_page_start <= 0) {
            $larger_start_page_start = $larger_page_multiple;
        }
        if($larger_start_page_end > $max_page) {
            $larger_start_page_end = $max_page;
        }
        if($larger_end_page_end > $max_page) {
            $larger_end_page_end = $max_page;
        }
        if($max_page > 1 || intval($pagenavi_options['always_show']) == 1) {

            $pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
            $pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
            echo $before.'<div class="clear"></div><div class="navigation keremiya-pagenavi">'."\n";
 
           if ( $paged > 1 ) { 
		   echo '<div class="navileft">';
		   previous_posts_link($pagenavi_options['prev_text']);
		   echo '</div>';
		   } else { 
		   echo '<span class="navileft dis">'.$pagenavi_options['prev_text'].'</span>';
		   }
 
			echo '<div class="navicenter">';
            if ($start_page >= 2 && $pages_to_show < $max_page) {
                $first_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['first_text']);

                echo '<a href="'.esc_url(get_pagenum_link()).'" class="first" title="'.$first_page_text.'">1</a>';
                if(!empty($pagenavi_options['dotleft_text'])) {
                    echo '<span class="expand">'.$pagenavi_options['dotleft_text'].'</span>';
                }
            }
 
            if($larger_page_to_show > 0 && $larger_start_page_start > 0 && $larger_start_page_end <= $max_page) {
                for($i = $larger_start_page_start; $i < $larger_start_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
 
            for($i = $start_page; $i  <= $end_page; $i++) {
                if($i == $paged) {
                    $current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
                    echo '<span class="current">'.$current_page_text.'</span>';
                } else {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
 
            if ($end_page < $max_page) {
                if(!empty($pagenavi_options['dotright_text'])) {
                    echo '<span class="expand">'.$pagenavi_options['dotright_text'].'</span>';
                }
                $last_page_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pagenavi_options['last_text']);
                echo '<a href="'.esc_url(get_pagenum_link($max_page)).'" class="last" title="'.$last_page_text.'">'.$max_page.'</a>';
            }
			
			echo "</div>";
			
			$nextpage = intval($paged) + 1;
			if ( $nextpage <= $max_page ) {
				echo '<div class="naviright">';
				next_posts_link($pagenavi_options['next_text'], $max_page);
				echo '</div>';
			}else {
				echo '<span class="naviright dis">'.$pagenavi_options['next_text'].'</span>';
			}

            if($larger_page_to_show > 0 && $larger_end_page_start < $max_page) {
                for($i = $larger_end_page_start; $i <= $larger_end_page_end; $i+=$larger_page_multiple) {
                    $page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
                    echo '<a href="'.esc_url(get_pagenum_link($i)).'" class="single_page" title="'.$page_text.'">'.$page_text.'</a>';
                }
            }
            echo '</div>'.$after."\n";
        }
    }
}

// Round num
function keremiya_round_num($num, $to_nearest) {
   return floor($num/$to_nearest)*$to_nearest;
}


function keremiya_loadnavi($query = '') {
    global $wp_query;

    if($query)
        $wp_query = $query;

    $paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;
    $max = $wp_query->max_num_pages;
    $next_link = next_posts($max, false);

    if($max > 1 ):
    echo '<div class="keremiya-loadnavi-page-'.($paged+1).'"></div>';

    echo '<a href="'.$next_link.'" class="loadnavi noselect" data-paged="'.$paged.'" data-max="'.$max.'">
            
            <div class="pages">
                <span class="current">'.$paged.'</span>
                <span class="delimater">/</span>
                <span class="total">'.$max.'</span>
            </div>

            <div class="loader">
                <span class="icon-loop">'._k_('Daha fazla yükle').'</span>
            </div>
        </a>';
    endif;
}

function keremiya_comment_loadnavi() {

    $paged = ( get_query_var('cpage') > 1 ) ? get_query_var('cpage') : 1;
    $max = get_comment_pages_count();
    $next_link = next_posts($max, false);

    if($max > 1 ):
    echo '<div class="keremiya-loadnavi-page-'.($paged+1).'"></div>';

    echo '<a href="'.$next_link.'" class="loadnavi noselect" data-paged="'.$paged.'" data-max="'.$max.'">
            
            <div class="loader">
                <span class="icon-loop">'._k_('Daha fazla yükle').'</span>
            </div>
        </a>';
    endif;
}

?>