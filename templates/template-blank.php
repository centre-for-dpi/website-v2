<?php 
/**
 * Template Name: Template - Blank
 * Description: Displays page title and content in Hero section above 3 widgets.
 *
 * @package WordPress
 * @subpackage BootstrapWP
 */
get_header();

if (have_posts()) : while (have_posts()) : the_post();   
 
?>

<section class="leglCnt" id="content-container-with-header">
	<?php  the_content(); ?>
</section>

<?php 
endwhile; 
endif;
get_footer(); 
?>