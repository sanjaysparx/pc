<?php /*
Template Name: Login
 */ ?>
<?php get_header(); ?>        
        <div id="maincontent" class="content container" role="main">
            <div class="container-inner">      
                <nav class="breadcrumb"> 
                <?php if ( function_exists('yoast_breadcrumb') ) {
							$breadcrumbs = yoast_breadcrumb("","",TRUE);
					  } 
				?>  
                    <!-- <ol>
                        <li><a href="#">Top Level</a><span class="divider">></span></li>
                        <li><a href="#">Second Level</a><span class="divider">></span></li>
                        <li><a href="#">Third Level</a><span class="divider">></span></li>
                        <li>Current Item</li>
                    </ol>  -->
                </nav>

                <div class="main full-width-col firstcol col logos" style="padding: 25px 0px 40px 0px;">
                <div class="lft">
				 <?php if (have_posts()) : while (have_posts()) : the_post(); 
                        the_content();
                        endwhile; endif; 
                        ?>
                          <?php if(get_field('download_practice_guide')){
                    $file = get_field('download_practice_guide');
                    $file_path =  wp_get_attachment_url( $file );
                    $filename = basename ( get_attached_file( $file ) );
                	?>
               <div id="cspt">
             <div class="dwonlaod">
                        <h3>Download</h3>
                       <span><a target="_blank" href="<?php echo $file_path ; ?>"><?php echo $filename; ?></a></span>
                     </div> </div>  
                <?php }?>
            
                   <!-- <div class="video-embed">
                        <iframe src="http://player.vimeo.com/video/28536713?title=0&amp;byline=0&amp;portrait=0&amp;color=bb0b07" width="100%" height="341" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                    </div> --> 
               </div>
               <div class="rht">
                <?php 
                	$rightsidebar = get_post_meta($post->ID, 'right_sidebar', true);
                	echo $rightsidebar = wpautop( $rightsidebar );
                ?>
                
                 <!-- <h3>Customer Support</h3>
                  <ul>
                    <li>Buying Organisations ProcServe<br />+44(0)845 603 6727 or <small><a href="mailto:emailaddress">email</a></small></li>
                    <hr />
                    <li>Zanzibar<br />+44(0)845 603 2885 or <small><a href="mailto:emailaddress">email</a></small></li>
                    <hr />
                    <li>OPEN<br />+44(0)845 600 6736 or <small><a href="mailto:emailaddress">email</a></small></li>
                    <hr />
                    <li>xchangewales e Trading<br />+44(0)845 602 9802 or <small><a href="mailto:emailaddress">email</a></small></li>
                    <hr />
                    <li><sxchangewales e Trading for Schools<br />+44(0)845 602 9803 or <small><a href="mailto:emailaddress">email</a></small></li>
                    <hr />
                    <li>Procurement for Housing<br />+44(0)845 865 5299 or <small><a href="mailto:emailaddress">email</a></small></li>
                    <hr />
                    <li>Supplier Organisations<br />+44(0)845 604 2328 or <small><a href="mailto:emailaddress">email</a></small></li>
                  </ul> -->
                  
               </div>

               </div>
</div>
<?php get_footer(); ?>