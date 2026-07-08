<?php 
/**
 * Template Name: Template - With Page Header
 * Description: Displays page title and content in Hero section above 3 widgets.
 *
 * @package WordPress
 * @subpackage BootstrapWP
 */
get_header();

if (have_posts()) : while (have_posts()) : the_post();   
 
?>

<div class="stololgl" id="content-container-with-header">
	<section class="stololglbanner text-center">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<h1>
					<?php echo get_the_title(); ?>
					</h1>
				</div>
			</div>
		</div>
	</section>
	<section class="leglCnt">
		<div class="container">
		<div class="row">
			<div class="col-12">
			<?php  the_content(); ?>
			</div>
		</div>
		</div>
	</section>
</div>

<?php 
endwhile; 
endif;
get_footer(); 
?>