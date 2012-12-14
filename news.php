<?php /* 
Template Name: News			
*/
?>
<?php get_header(); ?>        
        <div id="maincontent" class="content container" role="main">
            <div class="container-inner">      
                <nav class="breadcrumb"> 
                <?php if ( function_exists('yoast_breadcrumb') ) {
							$breadcrumbs = yoast_breadcrumb("","",TRUE);
					  } 
				?>  
            
                </nav>

                <div class="main size2of3 firstcol push-size1of3">
                <div class="col">
                <h2><?php the_title(); ?></h2>
					<?php if (have_posts()) : while (have_posts()) : the_post(); 
					?>
					<div class="post_container">
					<h3><?php the_title(); ?></h3>
					<?php the_content();?>
					</div>
                   <?php endwhile; 
                   endif;?>
                   </div>
               </div>

               <div class="sub size1of3 pull-size2of3">
                <div class="col">
                 <?php include_once(TEMPLATEPATH.'/sidebar-cat.php'); ?>
                 </div>
                </div>
</div>
<?php get_footer(); ?>