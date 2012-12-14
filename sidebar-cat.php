<?php 
$rootid = get_root_parent( get_the_ID(), 'page' );
$root_ref = get_post( $rootid );
$section_title = $root_ref->post_title;
$children = wp_list_pages("title_li=&child_of=".$rootid."&echo=0");
$get_current_year = (get_query_var('year')) ? get_query_var('year') : "";
?>
<input type="hidden" name="current-year" id="current-year" value="<?php echo $get_current_year; ?>" />
<input type="hidden" name="curr-year" id="curr-year" value="<?php echo date('Y'); ?>" />
<h3 class="h2">News and Events</h3>
<ul class="nav-secondary">
<?php 
   $current_cat_id = get_query_var('cat');
   $categories = get_categories('title_li&orderby=ID&hide_empty=0');
   foreach($categories as $category){
   		$cat_name = $category->cat_name;
		$term_id = $category->term_id;
		$cat_ID = $category->cat_ID;
		$cat_url = get_category_link( $cat_ID );
		$current_cat = "";
		if($current_cat_id == $cat_ID){
		$current_cat = 'class="current-cat"'; 
		}
		
		$cat_str = "";
		$cat_str .= '<li '.$current_cat.'><a title="View all posts filed under '.$cat_name.'" href="'.$cat_url.'">'.$cat_name.'</a>';
		$archive = wp_get_archives('type=yearly&echo=0&cat='.$cat_ID);
		if($archive){
			$cat_str .= '<ul class="children archive">'.$archive.'</ul>';
		}
		$cat_str .= '</li>';
		echo $cat_str;
   }
   //wp_list_categories( array( 'orderby' => 'ID', 'title_li' => '') ); 
   
   ?>
</ul>

<?php // wp_get_archives('type=yearly&echo=0&cat='.$cat_ID); ?>

<ul class="action-buttons v-list">
    <li><a href="<?php echo get_permalink(122);?>" class="btn btn-block btn-large btn-red"><i class="button-icons-phone"></i>Contact Us</a></li>
    <li><a href="<?php echo get_permalink(122);?>" class="btn btn-block btn-large btn-blue"><i class="button-icons-computer"></i>Request a Demo</a></li>
</ul>