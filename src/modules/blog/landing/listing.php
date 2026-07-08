<?php

// Get current taxonomy term object
$current_term = get_queried_object();

if(isset($current_term) && $current_term->post_type == 'page') {
    $current_term = null;
}

// Configuration array for blogs page
$pageConfig = [
    'post_type' => 'post',
    'taxonomy' => 'category',
    'page_url' => '/blog',
    'current_term' => $current_term,
];

// Initialize for a specific post type
$postHandler = new CustomPost($pageConfig['post_type'], $pageConfig['taxonomy']);

if (empty($current_term)) {
    $posts = $postHandler->getListOfPosts(["thumbnail", "published_date"]);
} else {
    $posts = $postHandler->getPostsByCategory($current_term->slug, ["thumbnail", "published_date"]);
}

?>

<?php require_once Helper::getCodePath('modules/blog/landing/header.php'); ?>

<section class="module-categories">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="d-block d-md-flex justify-content-between align-items-center">
					<?php require_once Helper::getBlock('modules/categories.php'); ?>
					<?php require_once Helper::getBlock('modules/search.php'); ?>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="module-cards-list">
	<div class="container">
		<div class="row">
			<?php
			// Get all posts
			foreach ($posts as $post):
				include Helper::getCodePath('modules/blog/landing/card.php');
			endforeach;
			?>
		</div>
	</div>
</section>
