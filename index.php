<?php

die("thi is index")

get_header(); ?>

<section class="cdpi-blogs text-center">
	<div class="container position-relative">
		<div class="row">
			<div class="col-12 my-auto">
				<h1>
					<?php echo the_title(); ?>
				</h1>
			</div>
		</div>
	</div>
</section>

<section class="allBlogs">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="blogsGrid">
					<?php if (have_posts()):
						while (have_posts()):
							the_post(); ?>

							<div class="blogCard">
								<a href="<?php the_permalink(); ?>">
									<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full'); ?>"
										alt="<?php echo the_title() ?>" class="img-fluid">
								</a>
								<div class="cnt">
									<div class="pblshDt">
										<i class="fa fa-calendar" aria-hidden="true"></i>
										<?php echo get_the_date(); ?>
									</div>
									<h2>
										<a href="<?php the_permalink(); ?>">
											<?php echo the_title() ?>
										</a>
									</h2>
									<p>
										<?php echo wp_trim_words(get_the_content(), 25, '...'); ?>
									</p>
									<a href="<?php the_permalink(); ?>" class="rdmr">Read More </a>
								</div>
							</div>
							<?php
						endwhile;
						Blog::getPostsPagination();
					endif;
					?>
					<?php wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>