<?php

get_header();

if (have_posts()) : while (have_posts()) : the_post();   
 
?>

<div class="cdpilgl">
	<section class="cdpilglbanner text-center">
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