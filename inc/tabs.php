<?php
/*
 * POST TABS EDITED BY KEREMIYA
 * Powered by: Post Tabs
 * Author: Leo Germani, Rodrigo Primo

    PostTabs is released under the GNU General Public License (GPL)
    http://www.gnu.org/licenses/gpl.txt
*/

// CONTENT FILTER
if( !function_exists('postTabs_filter') ) {
function postTabs_filter($a){
	
	$b = "[tab:";
	$c = 0;
	$op = '';
	
	#Search for tabs inside the post
	if(is_int(strpos($a, $b, $c))){
		global $user_ID;	
		
		# What kind of link should be used?
		$linktype = keremiya_get_option('tabs_linktype'); // keremiya_get_option('tabs_linktype') permalink
		
		$vai = true;
		$cooikes = 0;
		$results_i = array();
		$results_f = array();
		$results_t = array();
		$post = get_the_ID();

		wp_enqueue_script('postTabs', keremiya_get_js('postTabs'), array('jquery'));
		wp_localize_script('postTabs', 'postTabs', array('use_cookie' => ($cooikes && !isset($_GET['tabs'])), 'post_ID' => $post));

		do_action('posttabs-include-custom-css');

		#find the begining, the end and the title fo the tabs
		while ($vai)  {	
			$r = strpos($a, $b, $c);
			if (is_int($r)){
				array_push($results_i, $r);
				$c=$r+1;
				$f = strpos($a, "]", $c);
				if($f){
					array_push($results_f, $f);
					array_push($results_t, substr($a, $r+5, $f-($r+5)));
				}	
			}else $vai = false;		
		};

		#If there is text before the first tab, print it
		if ($results_i[0] > 0) {
			$op .= substr($a, 0, $results_i[0]);
		}
		
		if (isset($_GET['tabs']) && !empty($_GET['tabs'])) {
			$currentTab = $_GET['tabs'];
		} else {
			$currentTab = 0;
		}

		#Print the list of tabs only when we are not in RSS feed
		if(!is_feed()){
			
			#Print the tabs links
			$op .= "<div class='postTabs_list flexcroll'>\n";
			$op .= "<i class='icon-video'>"._k_('Alternatifler')." <div class='postTabs_count'></div></i>";

			$op .= "<div class='postTabs_parts flexcroll'><ul id='postTabs_ul_$post' class='postTabs'>\n";
			
			for ($x = 0; $x < sizeof($results_t); $x++){
				if($results_t[$x]!="END"){
					$op .= "<li id='postTabs_li_".$x."_$post' ";

					if ($x == $currentTab) {
						$op .= "class='postTabs_curr'";
					}		
							
					$link = ($linktype=="permalink") ? "href='" . get_postTabs_permalink($x) ."'" : " class='postTabsLinks'";		
					$op .= "><a  id=\"" . $post . "_$x\" onMouseOver=\"posTabsShowLinks('".$results_t[$x]."'); return true;\"  onMouseOut=\"posTabsShowLinks();\" $link>".$results_t[$x]."</a></li>\n";
				}		
			}
			$op .= "</ul></div>\n\n";
			$op .= "<span id='postTabs_count' data-count='".$x."'></span>\n\n";
			$op .= "</div>\n\n";
		}

		#print tabs content
		for ($x=0; $x<sizeof($results_t); $x++){
			
			#if tab title is END, just print the rest of the post
			if ($results_t[$x]=="END") {
				
				$op .= substr($a, $results_f[$x]+1);
				break;	
			}
			
			$op .= "<div class='postTabs_divs";
			if ($x == $currentTab) {
				$op .= " postTabs_curr_div";
			}
			$op .= "' id='postTabs_".$x."_$post'>\n";
			
			#This is the hidden title that only shows up on RSS feed or somewhere outside the context like a print page
			$op .= "<span class='postTabs_titles'><b>".$results_t[$x]."</b></span>";
			
			$ini = $results_f[$x]+1;
			if (sizeof($results_t)-$x==1){
				$op .= substr($a, $results_f[$x]+1);
			}else{
				$op .= substr($a, $results_f[$x]+1, $results_i[$x+1]-$results_f[$x]-1);
			}
			
			$op .= "</div>\n\n";
		}
		
		return $op;
	}else{
		return $a;	
	}

}
add_filter('the_content', 'postTabs_filter');

function get_postTabs_permalink($tab){
	$requested_url  = is_ssl() ? 'https://' : 'http://';                                                                                                                                           
	$requested_url .= $_SERVER['HTTP_HOST'];                                                                                                                                                       
	$requested_url .= $_SERVER['REQUEST_URI'];
	
	return add_query_arg(array('tabs' => $tab), $link);
}

function postTabs_addCSS(){
	?>
	<style type="text/css">
	.postTabs_list {
	    position: absolute;
	    bottom: 0;
	    top: 0;
	    right: 0;
	    width: 174px;
	    margin-right: -174px;
	    background: #151414;
	    height: 100%;
	    font-family: "Noto Sans", arial, helvetica, sans-serif;

	    overflow-y: auto;
	    padding-bottom: 1px;
	    line-height: 15px;
	    border-top-right-radius: 2px;
	    border-bottom-right-radius: 2px;
	}

	.postTabs_list i {
	    font-style: normal;
	    padding: 0px 0px 10px;
	    margin: 10px 10px 10px;
	    display: block;
	    font-size: 13px;
	    border-bottom: 2px solid rgba(255, 255, 255, 0.02);
	}
	.postTabs_list i:before {
	    margin-right: 10px;
	}

	.postTabs_parts {
	    padding: 10px;
	    position: absolute;
	    top: 36px;
	    bottom: 0;
	    right: 0;
	    left: 0;

	    overflow-y: auto;
	}

	.postTabs_parts li a {
	    display: block;
	    box-sizing: border-box;
	    margin-bottom: 5px;
	    padding: 8px 10px;
	    margin-bottom: 6px;
	    float: left;
	    width: 100%;
	    background: rgba(255, 255, 255, 0.02);
	    border-radius: 3px;
	    box-shadow: 0px 2px 1px rgba(0, 0, 0, 0.18);
	    line-height: 14px;
	    color: #ccc;
	    cursor: pointer;
	    text-decoration:none;
	}
	.postTabs_parts li a:active {
		box-shadow: inset 0px 3px 4px rgba(0, 0, 0, 0.36);
	}

	.postTabs_parts li a:hover {
	    background: rgba(255, 255, 255, 0.10);
	}

	.postTabs_parts li.postTabs_curr a {
	    background: rgba(0, 0, 0, 0.15);
	    color: #F3D872;
	}
	.postTabs_parts li.postTabs_curr a:hover {
	    background: rgba(0, 0, 0, 0.15);
	    cursor:default;
	}

	.postTabs_parts li:last-child a {
	    margin-bottom: 0;
	}

	.postTabs_count {
		display: inline-block;
	    float: right;
	    background: rgba(0, 0, 0, 0.23);
		padding: 3px 6px;
   		border-radius: 4px;
	    margin-top: -3px;
	    color: rgba(255, 255, 255, 0.23);
	    cursor: default;
	    font-size: 12px;
	}

	.postTabs_titles { display: none }

	.autosize-container {
	    margin-right: 174px;
	}
	@media only screen and (max-width : 479px) {
		.autosize-container {
			margin-right: 0px !important;
	    	margin-bottom: 200px !important;
		}
	}
	</style>
	<?php
}
add_action('posttabs-include-custom-css', 'postTabs_addCSS');
}

?>