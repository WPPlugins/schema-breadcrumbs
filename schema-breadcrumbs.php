<?php /*
Plugin Name:  Schema Breadcrumbs
Plugin URI:   http://webdesires.co.uk
Description:  Outputs a fully Schema valid breadcrumb
Version:      1.3.1
Author:       Dean Williams
Author URI:   http://deano.me

Copyright (C) 2008-2010, Dean Williams
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
Neither the name of WebDesires, Dean Williams nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.*/

// Load some defaults
$opt 						= array();
$opt['home'] 				= "Home";
$opt['blog'] 				= "Blog";
$opt['knowledge'] 			= "Knowledge Base";
$opt['portfolio'] 			= "Portfolio";
$opt['sep'] 				= "&raquo;";
$opt['prefix']				= "<div class='breadcrumb breadcrumbs'><p>";
$opt['suffix']				= "</p></div>";
$opt['boldlast'] 			= true;
$opt['blogparent'] 			= true;
$opt['nofollowhome'] 		= false;
$opt['singleparent'] 		= 0;
$opt['singlecatprefix']		= true;
$opt['normalprefix'] 		= "You are here:";
$opt['archiveprefix'] 		= "Archives for";
$opt['searchprefix'] 		= "Search for";
add_option("schema_breadcrumbs",$opt);

if ( ! class_exists( 'WDPanelAdmin' ) ) {
	require_once('WDPanelAdmin.php');
}
if ( ! class_exists( 'Schema_Breadcrumbs_Admin' ) ) {
	class Schema_Breadcrumbs_Admin extends WDPanelAdmin {

		var $hook 		= 'schema-breadcrumbs';
		var $longname	= 'Schema Breadcrumbs Configuration';
		var $shortname	= 'Schema Breadcrumbs';
		var $filename	= 'breadcrumbs/schema-breadcrumbs.php';
		var $ozhicon	= 'script_link.png';

		function config_page() {
			if ( isset($_POST['submit']) ) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the Schema Breadcrumbs options.'));
				check_admin_referer('schema-breadcrumbs-updatesettings');
				
				foreach (array('home', 'blog', 'knowledge', 'portfolio', 'sep', 'singleparent', 'prefix', 'suffix', 'archiveprefix', 'normalprefix', 'searchprefix', 'breadcrumbprefix', 'breadcrumbsuffix') as $option_name) {
				

					if (isset($_POST[$option_name])) {
						$opt[$option_name] = htmlentities(html_entity_decode($_POST[$option_name]));
					}
				}

				foreach (array('boldlast', 'blogparent', 'nofollowhome', 'singlecatprefix', 'trytheme') as $option_name) {
					if (isset($_POST[$option_name])) {
						$opt[$option_name] = true;
					} else {
						$opt[$option_name] = false;
					}
				}
				
				update_option('schema_breadcrumbs', $opt);
			}
			
			$opt  = get_option('schema_breadcrumbs');
			?>
			<div class="wrap">
				
				<h2>Schema Breadcrumbs Configuration</h2>
				<div class="postbox-container" style="width:70%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<form action="" method="post" id="schemabreadcrumbs-conf">
								
								<?php if (function_exists('wp_nonce_field')) 		
										wp_nonce_field('schema-breadcrumbs-updatesettings');
										
								$rows = array();
								$rows[] = array(
									"id" => "sep",
									"label" => __('Separator between breadcrumbs'),
									"content" => '<input type="text" name="sep" id="sep" value="'.htmlentities($opt['sep']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "home",
									"label" => __('Anchor text for the Homepage'),
									"content" => '<input type="text" name="home" id="home" value="'.stripslashes($opt['home']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "blog",
									"label" => __('Anchor text for the Blog'),
									"content" => '<input type="text" name="blog" id="blog" value="'.stripslashes($opt['blog']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "knowledge",
									"label" => __('Anchor text for the Knowledge Base'),
									"content" => '<input type="text" name="knowledge" id="knowledge" value="'.stripslashes($opt['knowledge']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "portfolio",
									"label" => __('Anchor text for the Portfolio'),
									"content" => '<input type="text" name="portfolio" id="portfolio" value="'.stripslashes($opt['portfolio']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "prefix",
									"label" => __('Global Prefix for the breadcrumb path'),
									"content" => '<input type="text" name="prefix" id="prefix" value="'.stripslashes($opt['prefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "suffix",
									"label" => __('Global Suffix for the breadcrumb path'),
									"content" => '<input type="text" name="suffix" id="suffix" value="'.stripslashes($opt['suffix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "normalprefix",
									"label" => __('Prefix for Blog/Page/Category breadcrumbs'),
									"content" => '<input type="text" name="normalprefix" id="normalprefix" value="'.stripslashes($opt['normalprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "archiveprefix",
									"label" => __('Prefix for Archive breadcrumbs'),
									"content" => '<input type="text" name="archiveprefix" id="archiveprefix" value="'.stripslashes($opt['archiveprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "searchprefix",
									"label" => __('Prefix for Search Page breadcrumbs'),
									"content" => '<input type="text" name="searchprefix" id="searchprefix" value="'.stripslashes($opt['searchprefix']).'" style="width:50%" />',
								);
								$rows[] = array(
									"id" => "singlecatprefix",
									"label" => __('Show category in post breadcrumbs?'),
									"desc" => __('Shows the category inbetween Home and the blogpost'),
									"content" => '<input type="checkbox" name="singlecatprefix" id="singlecatprefix" '.checked($opt['singlecatprefix'],true,false).' />',
								);
								$rows[] = array(
									"id" => "singleparent",
									"label" => __('Show Parent Page for Blog posts'),
									"desc" => __('Adds another page inbetween Home and the blogpost'),
									"content" => wp_dropdown_pages("echo=0&depth=0&name=singleparent&show_option_none=-- None --&selected=".$opt['singleparent']),
								);
								$rows[] = array(
									"id" => "blogparent",
									"label" => __('Show Blog in breadcrumb path'),
									"desc" => __('Enable/Disable the Blog crumb in the breadcrumb'),
									"content" => '<input type="checkbox" name="blogparent" id="blogparent" '.checked($opt['blogparent'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "boldlast",
									"label" => __('Bold the last page in the breadcrumb'),
									"content" => '<input type="checkbox" name="boldlast" id="boldlast" '.checked($opt['boldlast'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "nofollowhome",
									"label" => __('Nofollow the link to the home page'),
									"content" => '<input type="checkbox" name="nofollowhome" id="nofollowhome" '.checked($opt['nofollowhome'],true,false).'/>',
								);
								$rows[] = array(
									"id" => "trytheme",
									"label" => __('Try to add automatically'),
									"desc" => __('If you\'re using Hybrid, Thesis or Thematic, check this box for some lovely simple action'),
									"content" => '<input type="checkbox" name="trytheme" id="trytheme" '.checked($opt['trytheme'],true,false).'/>',
								);
								
								$table = $this->form_table($rows);
								
								
								$this->postbox('breadcrumbssettings',__('Setting for Schema Breadcrumbs'), '<b>TIP:</b> Call the breadcrumbs easily in your templates by calling: <b>schema_breadcrumb();</b>'.$table.'<div class="submit"><input type="submit" class="button-primary" name="submit" value="Save Breadcrumbs Settings" /></div>')
								?>
							</form>
							<b>RDFa Plugin:</b>
							<br>
							If you are using the RDFa Breadcrumbs plugin, this plugin will automatically take over, just disable RDFa, and any function calls in your theme will automatically work with Schema Breadcrumb, if you would like to use the same DIVs as RDFa set prefix to:
							<br><i>&lt;div class="breadcrumb breadcrumbs"&gt;&lt;p"&gt;</i><br>
							and set the suffix to:<br>
							<i>&lt;/p"&gt;&lt;/div"&gt;</i>
						</div>
					</div>
				</div>
				<div class="postbox-container" style="width:30%;padding-left:10px;box-sizing: border-box;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<center style="background-color:white;">
								<a href="https://webdesires.co.uk">
									<div style="margin-bottom:20px;padding:5px 10px 10px 10px">
										<img style="width:100%" src="https://webdesires.co.uk/wp-content/themes/webdesires/images/logo/WebDesiresLogo.png" alt="WebDesires - Web Development" title="WebDesires - Web Development" /><br>
										Looking for a developer?<br>
										Professional UK WordPress Web Development Company
									</div>
								</a>
							</center>
							<?php
								$this->plugin_like();
								$this->plugin_support();
								$this->wd_knowledge(); 
								$this->wd_news(); 
							?>
						</div>
						<br/><br/><br/>
					</div>
				</div>
			</div>
		
<?php		}
	}
	
	$ybc = new Schema_Breadcrumbs_Admin();
}
if (!function_exists('rdfa_breadcrumb')) {
	function rdfa_breadcrumb() {
		schema_breadcrumb();
	}
}
if (!function_exists('yoast_breadcrumb')) {
	function yoast_breadcrumb($l='',$r='') {
		schema_breadcrumb();
	}
}
function schema_breadcrumb($prefix = '', $suffix = '', $display = true) {
	global $wp_query, $post;
	
	$opt = get_option("schema_breadcrumbs");

	if (!function_exists('bold_or_not')) {
		function bold_or_not($input, $child = '') {
			if ($child === true) {
				$child = 'itemprop="child" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"';
			}
			$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			$actual_link = $actual_link[0];
			$opt = get_option("schema_breadcrumbs");
			if ($opt['boldlast']) {
				return '<span '.$child.'><a href="'.$actual_link.'" onclick="return false;" style="text-decoration:none" itemprop="url" rel="nofollow"><span itemprop="title"><strong>'.$input.'</strong></span></a></span>';
			} else {
				return $input;
			}
		}	
		function notbold_or_not($input, $child = '') {
			if ($child === true) {
				$child = 'itemprop="child" itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"';
			}
			$actual_link = explode('?', "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
			$actual_link = $actual_link[0];
			$opt = get_option("schema_breadcrumbs");
			if ($opt['boldlast']) {
				return '<span '.$child.'><a href="'.$actual_link.'" onclick="return false;" style="text-decoration:none" itemprop="url"><span itemprop="title">'.$input.'</span></a></span>';
			} else {
				return $input;
			}
		}		
	}

	if (!function_exists('schema_get_category_parents')) {
		// Copied and adapted from WP source
		function schema_get_category_parents($id, $link = FALSE, $separator = '/', $nicename = FALSE){
			$chain = '';
			$parent = &get_category($id);
			if ( is_wp_error( $parent ) )
			   return $parent;

			if ( $nicename )
			   $name = $parent->slug;
			else
			   $name = $parent->cat_name;

			if ( $parent->parent && ($parent->parent != $parent->term_id) )
			   $chain .= get_category_parents($parent->parent, true, $separator, $nicename);

			$chain .= bold_or_not($name, true);
			return $chain;
		}
	}

	if (!function_exists('schema_get_category')) {
		// Copied and adapted from WP source
		function schema_get_category($id, $link = FALSE, $separator = '/', $nicename = FALSE){
			
			$chain = '';
			$parent = &get_category($id);
			
			if ( is_wp_error( $parent ) )
			   return '';

			$chain .= get_category_parents($parent, true, $separator, $nicename);

			return $chain;
		}
	}
	
	$nofollow = ' ';
	if ($opt['nofollowhome']) {
		$nofollow = ' rel="nofollow" ';
	}
	
	$on_front = get_option('show_on_front');
	
	if (!is_404()) {
		if ($on_front == "page") {
			$obj = get_post_type_object( get_post_type() );
			
			$page_url = wp_get_post_type_link(get_post_type());
			

			if (get_post_type() == 'post') {
				$page_name = $opt['blog'];
			} else {
				$obj = get_post_type_object( get_post_type() );
				$page_name = $obj->labels->name;
			}
			
			//echo $obj->labels->singular_name;
			
			
			//$link = explode('/',get_permalink());
			//if(in_array('knowledge-base', $link)){
				//$page_url = "https://webdesires.co.uk/knowledge-base/";
				//$page_name = 'Knowledge Base';
			//} else if(in_array('portfolio', $link)){
				//$page_url = "https://webdesires.co.uk/portfolio/";
				//$page_name = 'Portfolio';
			//}
			
			$homelink = '<a'.$nofollow.'href="'.get_permalink(get_option('page_on_front')).'" itemprop="url"><span itemprop="title">'.$opt['home'].'</span></a>';
			if ( get_post_type() == 'product' ) {
				$bloglink = $homelink.' ';
			} else {
				if (get_post_type() == 'post') {
					if ($opt['blogparent']) {
						$bloglink = $homelink.' '.$opt['sep'].' <a href="'.$page_url.'" itemprop="url"><span itemprop="title">'.$page_name.'</span></a>';
					} else {
						$bloglink = $homelink;
					}
				} else {
					$bloglink = $homelink.' '.$opt['sep'].' <a href="'.$page_url.'" itemprop="url"><span itemprop="title">'.$page_name.'</span></a>';
				}
				
			}
			} else {
			$homelink = '<a'.$nofollow.'href="'.get_bloginfo('url').'" itemprop="url"><span itemprop="title">'.$opt['home'].'</span></a>';
			$bloglink = $homelink;
		}
			if(count($link) > 4){
				//print_r($link);
			}
		
		if ( ($on_front == "page" && is_front_page()) || ($on_front == "posts" && is_home()) ) {
			$output = bold_or_not($opt['home']);
		} elseif ( $on_front == "page" && (is_home() || (is_archive() && !is_author() && !is_category() && !is_tag() && !is_tax())) ) {
			$output = $homelink.' '.$opt['sep'].' '.bold_or_not($page_name, true);
		} elseif ( !is_page() ) {
			
			//todo make this optional?
			$opt['showsinglecategory'] = true;
			$opt['showsinglecategoryifmultiple'] = true;
			
			$linker = '';
			//if ( ( is_single() || is_category() || is_tag() || is_date() || is_author() ) && $opt['singleparent'] != false) {
				//$homelink .= ' '.$opt['sep'].' <a href="'.get_permalink($opt['singleparent']).'">'.get_the_title($opt['singleparent']).'</a>';
				//$linker = 'blog/';
			//} 
			if ($opt['showsinglecategory'] == true) {
				if (is_single() && count(get_the_category()) > 1) {
					if ($opt['showsinglecategoryifmultiple'] == true) {
						$cats = get_the_category();
						$cat = $cats[0]->cat_ID;
						$output = $homelink.' '.$opt['sep'].' '.schema_get_category($cat, false, " ".$opt['sep']." ");
					} else {
						$output = $bloglink.' '.$opt['sep'].' ';
					}
				} else {
					$cats = get_the_category();
					
						$cat = $cats[0]->cat_ID;
						$cat = intval( get_query_var('cat') );
						
					$output = $homelink.' '.$opt['sep'].' '.schema_get_category($cat, false, " ".$opt['sep']." ");
				}
			} else { 
				$output = $bloglink.' '.$opt['sep'].' ';
			}
			
			if (is_single() && $opt['singlecatprefix']) {
				$cats = get_the_category();
				$cat = $cats[0];
				if ( is_object($cat) ) {
					if ($cat->parent != 0) {
						//$output .= get_category_parents($cat->term_id, true, " ".$opt['sep']." ");
					} else {
						//$output .= '<a href="'.get_category_link($cat->term_id).'">'.$cat->name.'</a> '.$opt['sep'].' '; 
					}
				}
			}
			if ( is_category() ) {
				$cat = intval( get_query_var('cat') );
				$output = $homelink.' '.$opt['sep'].' '.schema_get_category_parents($cat, false, " ".$opt['sep']." ");
			} elseif ( get_post_type() == 'product' ) {
				$output .= bold_or_not(get_the_title(), true);
			} elseif ( is_tag() || is_tax()) {
				$output .= bold_or_not("Tag: ".single_cat_title('',false), true);
			} elseif ( is_date() ) { 
				$output .= bold_or_not($opt['archiveprefix']." ".single_month_title(' ',false), true);
			} elseif ( is_author() ) {
				
				$user = get_userdatabylogin($wp_query->query_vars['author_name']);
				$output .= bold_or_not('Author: ' . $user->display_name, true);
			} elseif ( is_search() ) {
				$output .= bold_or_not('Results For: "'.stripslashes(strip_tags(get_search_query())).'"', true);
			} else if ( is_tax() ) {
				$taxonomy 	= get_taxonomy ( get_query_var('taxonomy') );
				$term 		= get_query_var('term');
				$output .= $taxonomy->label .': '.bold_or_not( $term , true);
			} else {
				//Double check the url to ensure breadcrumb is ok
				$output .= bold_or_not(get_the_title(), true);
			}
		} else {
			$post = $wp_query->get_queried_object();

			// If this is a top level Page, it's simple to output the breadcrumb
			if ( 0 == $post->post_parent ) {
				$output = $homelink." ".$opt['sep']." ".bold_or_not(get_the_title(), true);
			} else {
				if (isset($post->ancestors)) {
					if (is_array($post->ancestors))
						$ancestors = array_values($post->ancestors);
					else 
						$ancestors = array($post->ancestors);				
				} else {
					$ancestors = array($post->post_parent);
				}

				// Reverse the order so it's oldest to newest
				$ancestors = array_reverse($ancestors);

				// Add the current Page to the ancestors list (as we need it's title too)
				$ancestors[] = $post->ID;

				$links = array();			
				foreach ( $ancestors as $ancestor ) {
					$tmp  = array();
					$tmp['title'] 	= strip_tags( get_the_title( $ancestor ) );
					$tmp['url'] 	= get_permalink($ancestor);
					$tmp['cur'] = false;
					if ($ancestor == $post->ID) {
						$tmp['cur'] = true;
					}
					$links[] = $tmp;
				}

				$output = $homelink;
				foreach ( $links as $link ) {
					$output .= ' '.$opt['sep'].' ';
					if (!$link['cur']) {
						$output .= '<a href="'.$linker.$link['url'].'" itemprop="url"><span itemprop="title">'.$link['title'].'</span></a>';
					} else {
						$output .= bold_or_not($link['title'], true);
					}
				}
			}
		}
		
		$output = '<span id="breadcrumbs" itemscope="" itemtype="http://data-vocabulary.org/Breadcrumb">' . $output . '</span>';
		
		if (is_archive()) {
			$output = $opt['normalprefix']." " . $output;
		} else if (is_search()) {
			$output = $opt['normalprefix']." " . $output;
		} else {
			$output = $opt['normalprefix']." " . $output;
		}
		
		if ($opt['prefix'] != "") {
			$output = html_entity_decode(stripslashes($opt['prefix']))." ".$output;
		}
		
		if ($opt['suffix'] != "") {
			$output = $output . " " . html_entity_decode(stripslashes($opt['suffix']));
		}
		
		if ($display) {
			echo $output;
		} else {
			return $output;
		}
	}
}

function schema_breadcrumb_output() {
	$opt = get_option('schema_breadcrumbs');
	if ($opt['trytheme'])
		schema_breadcrumb('<div id="schema">','</div>');
	return;
}

if( !function_exists( 'wp_get_post_type_link' )  ) {
    function wp_get_post_type_link( &$post_type ){

        global $wp_rewrite; 

        if ( ! $post_type_obj = get_post_type_object( $post_type ) )
            return false;

        if ( get_option( 'permalink_structure' ) && is_array( $post_type_obj->rewrite ) ) {

            $struct = $post_type_obj->rewrite['slug'] ;
            if ( $post_type_obj->rewrite['with_front'] )
                $struct = $wp_rewrite->front . $struct;
            else
                $struct = $wp_rewrite->root . $struct;

            $link = home_url( user_trailingslashit( $struct, 'post_type_archive' ) );       

        } else if ($post_type == 'post'){
			$link = home_url( '/blog/' );
		} else {
            $link = home_url( '?post_type=' . $post_type );
        }

        return apply_filters( 'the_permalink', $link );
    }
}

add_action('thesis_hook_before_content','schema_breadcrumb_output',10,1);
add_action('hybrid_before_content','schema_breadcrumb_output',10,1);
add_action('thematic_belowheader','schema_breadcrumb_output',10,1);
add_action('framework_hook_content_open','schema_breadcrumb_output',10,1);

?>