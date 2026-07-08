<?php 
/**
 * Template Name: Template - Empty Canvas (Without Header & Footer)
 * Description: Displays page title and content in Hero section above 3 widgets.
 *
 * @package WordPress
 * @subpackage BootstrapWP
 */
include Helper::getBlock("html-header-footer/header.php");

if (have_posts()) : while (have_posts()) : the_post();   
 
?>

<section>
	<?php  the_content(); ?>
</section>

<?php 
endwhile; 
endif;

?>