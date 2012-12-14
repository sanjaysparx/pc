<?php 
include 'script_css_functions.php';
//include 'shortcode_functions.php';
/*
 * 	Register  Navigations
 */
 register_nav_menus( array(
	'topnav' => 'Top Nav Menu'
) );
 register_nav_menus( array(
	'mainnav' => 'Main  Nav Menu'
) );
register_nav_menus( array(
	'footernav' => 'Footer Nav Menu'
) );
/*
 * 	WP walker class for the primary navigation
 */
include_once( TEMPLATEPATH."/includes/primary-menu-walker-class.php" ); 
/*
 * 	Register Widget
 */
register_sidebar(array(
'name'=> 'login',

 'description'   => 'login',
  'before_widget' => '',
 'after_widget' => '',
 'before_title' => '<h2>',
'after_title' => '</h2>',
 )); 
 /*
/*
 * 	Add support for Featured Images
 */

if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
}

/*
 *	Adding .xml in the allowed file type 
 */
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
	// add your extension to the array
	$existing_mimes['xml'] = 'application/xml';
	return $existing_mimes;
}


function bytesTosize($bytes, $precision = 2)
{  
    $kilobyte = 1024;
    $megabyte = $kilobyte * 1024;
    $gigabyte = $megabyte * 1024;
    $terabyte = $gigabyte * 1024;
   
    if (($bytes >= 0) && ($bytes < $kilobyte)) {
        return $bytes . ' B';
 
    } elseif (($bytes >= $kilobyte) && ($bytes < $megabyte)) {
        return round($bytes / $kilobyte, $precision) . ' kb';
 
    } elseif (($bytes >= $megabyte) && ($bytes < $gigabyte)) {
        return round($bytes / $megabyte, $precision) . ' mb';
 
    } elseif (($bytes >= $gigabyte) && ($bytes < $terabyte)) {
        return round($bytes / $gigabyte, $precision) . ' gb';
 
    } elseif ($bytes >= $terabyte) {
        return round($bytes / $terabyte, $precision) . ' tb';
    } else {
        return $bytes . ' B';
    }
}

/*
 * 	Filter wp_get_archives....excluding the current year
 */



/*
 *  Function to truncate the posts
 */

function truncate_posts($amount,$quote_after=false) {

 $truncate = get_the_content(); 
 $truncate = apply_filters('the_content', $truncate);
 $truncate = preg_replace('@<script[^>]*?>.*?</script>@si', '', $truncate);
 $truncate = preg_replace('@<style[^>]*?>.*?</style>@si', '', $truncate);
 $truncate = strip_tags($truncate);
 $truncate = substr($truncate, 0, strrpos(substr($truncate, 0, $amount), ' ')); 
 echo $truncate;
if ($quote_after) echo('<a href="'.get_permalink().'"> (read more)</a>');
} 

/* getting the id of root parent*/

function get_root_parent($page_id, $posttype) {
	global $wpdb;
	$parent = $wpdb->get_var("SELECT post_parent FROM $wpdb->posts WHERE post_type='".$posttype."' AND ID = '$page_id'");
	if ($parent == 0) return $page_id;
	else return get_root_parent($parent, $posttype);
}





 add_action("wp_ajax_newsletter-register", "newsletter_register");
 add_action("wp_ajax_nopriv_newsletter-register", "newsletter_register");
 
 function newsletter_register(){
 
 /* [action] => newsletter-register
    [newsletterEmail] => ashish.anand@sparxtechnologies.com
	&& chk_email($_REQUEST['newsletterEmail']) == 0
	*/
	//echo chk_email($_REQUEST['newsletterEmail']);
	if( ($_REQUEST['action'] == 'newsletter-register') && ( chk_email($_REQUEST['newsletterEmail']) == 0 ) ){
	global $wpdb;
	$server_ip = $_SERVER['REMOTE_ADDR'];
	$email = $_REQUEST['newsletterEmail'];
    $date = date("Y-m-d i:h:s");
	$sql = "INSERT INTO `wp_gsom_subscribers` SET dtTime='".$date."', varIP='".$server_ip."', varEmail='".$email."', textCustomFields='[]', intStatus='1', varUCode=''";
	echo $isQuery = $wpdb->query($sql);
	
	}
 die();
 }
 function chk_email($email){
 global $wpdb;
 $myrows = $wpdb->get_results( "SELECT * FROM `wp_gsom_subscribers` WHERE varEmail='".$email."'" );
 return $count = count($myrows);
 
 }

/*
 *  Function to Register Custom post Type
 */
add_action( 'init', 'register_cpt_home_slider' );

function register_cpt_home_slider() {

    $labels = array( 
        'name' => _x( 'HomepageSlider', 'home_slider' ),
        'singular_name' => _x( 'HomepageSlider', 'home_slider' ),
        'add_new' => _x( 'Add New', 'home_slider' ),
        'add_new_item' => _x( 'Add New HomepageSlider', 'home_slider' ),
        'edit_item' => _x( 'Edit HomepageSlider', 'home_slider' ),
        'new_item' => _x( 'New HomepageSlider', 'home_slider' ),
        'view_item' => _x( 'View HomepageSlider', 'home_slider' ),
        'search_items' => _x( 'Search HomepageSlider', 'home_slider' ),
        'not_found' => _x( 'No homepageslider found', 'home_slider' ),
        'not_found_in_trash' => _x( 'No homepageslider found in Trash', 'home_slider' ),
        'parent_item_colon' => _x( 'Parent HomepageSlider:', 'home_slider' ),
        'menu_name' => _x( 'Home Slider', 'home_slider' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'thumbnail', 'page-attributes' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'home_slider', $args );
	flush_rewrite_rules();
}
add_action( 'init', 'register_cpt_testimonial' );
    function register_cpt_testimonial() {
    $labels = array(
    'name' => _x( 'Testimonials', 'testimonial' ),
    'singular_name' => _x( 'Testimonial', 'testimonial' ),
    'add_new' => _x( 'Add New', 'testimonial' ),
    'add_new_item' => _x( 'Add New Testimonial', 'testimonial' ),
    'edit_item' => _x( 'Edit Testimonial', 'testimonial' ),
    'new_item' => _x( 'New Testimonial', 'testimonial' ),
    'view_item' => _x( 'View Testimonial', 'testimonial' ),
    'search_items' => _x( 'Search Testimonials', 'testimonial' ),
    'not_found' => _x( 'No testimonials found', 'testimonial' ),
    'not_found_in_trash' => _x( 'No testimonials found in Trash', 'testimonial' ),
    'parent_item_colon' => _x( 'Parent Testimonial:', 'testimonial' ),
    'menu_name' => _x( 'Testimonials', 'testimonial' ),
    );
    $args = array(
    'labels' => $labels,
    'hierarchical' => true,
    'supports' => array( 'title', 'editor', 'page-attributes' ),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'show_in_nav_menus' => true,
    'publicly_queryable' => true,
    'exclude_from_search' => true,
    'has_archive' => true,
    'query_var' => true,
    'can_export' => true,
    'rewrite' => true,
    'capability_type' => 'page'
    );
    register_post_type( 'testimonial', $args );
	flush_rewrite_rules();
    } 
add_action( 'init', 'register_cpt_home_featured_box' );

function register_cpt_home_featured_box() {

    $labels = array( 
        'name' => _x( 'Home Featured Boxes', 'home_featured_box' ),
        'singular_name' => _x( 'Home Featured Box', 'home_featured_box' ),
        'add_new' => _x( 'Add New', 'home_featured_box' ),
        'add_new_item' => _x( 'Add New Home Featured Box', 'home_featured_box' ),
        'edit_item' => _x( 'Edit Home Featured Box', 'home_featured_box' ),
        'new_item' => _x( 'New Home Featured Box', 'home_featured_box' ),
        'view_item' => _x( 'View Home Featured Box', 'home_featured_box' ),
        'search_items' => _x( 'Search Home Featured Boxes', 'home_featured_box' ),
        'not_found' => _x( 'No home featured boxes found', 'home_featured_box' ),
        'not_found_in_trash' => _x( 'No home featured boxes found in Trash', 'home_featured_box' ),
        'parent_item_colon' => _x( 'Parent Home Featured Box:', 'home_featured_box' ),
        'menu_name' => _x( 'Featured Boxes', 'home_featured_box' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'page-attributes' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'page'
    );

    register_post_type( 'home_featured_box', $args );
	flush_rewrite_rules();
}
add_action( 'init', 'register_cpt_customer' );

function register_cpt_customer() {

    $labels = array( 
        'name' => _x( 'Customers', 'customer' ),
        'singular_name' => _x( 'Customer', 'customer' ),
        'add_new' => _x( 'Add New', 'customer' ),
        'add_new_item' => _x( 'Add New Customer', 'customer' ),
        'edit_item' => _x( 'Edit Customer', 'customer' ),
        'new_item' => _x( 'New Customer', 'customer' ),
        'view_item' => _x( 'View Customer', 'customer' ),
        'search_items' => _x( 'Search Customers', 'customer' ),
        'not_found' => _x( 'No customers found', 'customer' ),
        'not_found_in_trash' => _x( 'No customers found in Trash', 'customer' ),
        'parent_item_colon' => _x( 'Parent Customer:', 'customer' ),
        'menu_name' => _x( 'Customers', 'customer' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'page'
    );

    register_post_type( 'customer', $args );
	flush_rewrite_rules();
}

add_action( 'init', 'register_cpt_customer_slide' );

function register_cpt_customer_slide() {

    $labels = array( 
        'name' => _x( 'Customer Slides', 'customer_slide' ),
        'singular_name' => _x( 'Customer Slide', 'customer_slide' ),
        'add_new' => _x( 'Add New', 'customer_slide' ),
        'add_new_item' => _x( 'Add New Customer Slide', 'customer_slide' ),
        'edit_item' => _x( 'Edit Customer Slide', 'customer_slide' ),
        'new_item' => _x( 'New Customer Slide', 'customer_slide' ),
        'view_item' => _x( 'View Customer Slide', 'customer_slide' ),
        'search_items' => _x( 'Search Customer Slides', 'customer_slide' ),
        'not_found' => _x( 'No customer slides found', 'customer_slide' ),
        'not_found_in_trash' => _x( 'No customer slides found in Trash', 'customer_slide' ),
        'parent_item_colon' => _x( 'Parent Customer Slide:', 'customer_slide' ),
        'menu_name' => _x( 'Customer Slides', 'customer_slide' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'thumbnail' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => true,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
	
    register_post_type( 'customer_slide', $args );
    flush_rewrite_rules();
}



/**
 * Limit Expert Length
 */

function excerpt($limit) {
  $excerpt = explode(' ', get_the_excerpt(), $limit);
  if (count($excerpt)>=$limit) {
    array_pop($excerpt);
    $excerpt = implode(" ",$excerpt).'...';
  } else {
    $excerpt = implode(" ",$excerpt);
  } 
  $excerpt = preg_replace('`\[[^\]]*\]`','',$excerpt);
  return $excerpt;
}

function excerpt_chars($content, $limit) {
    $text = substr($content,0,$limit);
    $text = $text . "...";
    return $text;
}

/*
 * Function to add shortcode to handle PDF downloads via form
 */

add_shortcode('prcservepdf', 'procserve_pdf_link');

function procserve_pdf_link($atts){
    global $post;
    extract( shortcode_atts( array(
		'linktext' => 'Download PDF',
                'image_url' => '',
                'height' => '',
                'width' => '',
                'download_id' => ''
	), $atts ) );
    $link_inner_html = trim($image_url)?'<img height="'.$height.'" width="'.$width.'" src="'.$image_url.'" alt="'.$linktext.'" />':$linktext;
    $link = '<a class="fancybox fancybox.iframe"  style="height:'.$height.'px; width:'.$width.'px; display:block;" href="'.get_permalink('2879').'?d_id='.base64_encode($download_id).'" title="'.$linktext.'">'.$link_inner_html.'</a>';
    return $link;
}

/*
 * Function to handle highlighter shortcode
 * uses [highlightbox]content[/highlightbox]
 */
add_shortcode('highlightbox', 'procserve_highlight_box');
function procserve_highlight_box($atts, $content = null) {
    return '<div class="highlight">' . $content . '</div>';
}
    
