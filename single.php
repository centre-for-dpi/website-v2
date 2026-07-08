<?php 
get_header();

if (have_posts()):
    while (have_posts()):
        the_post();

// Initialize for a specific post type
$postHandler = new CustomPost('post', 'category');

$postHandler->setID(get_the_ID());

// Get complete post details
$post = $postHandler->getPost(["all"]);

// Get related posts
$related_posts = $postHandler->getRelatedPosts(3);
?>

<section>

<?php

include Helper::getBlock('page-blog-single/blog-single-hero/index.php');

include Helper::getBlock('page-blog-single/blog-single-banner/index.php');

include Helper::getBlock('page-blog-single/blog-single-content/index.php');

include Helper::getBlock('page-blog-single/blog-single-related/index.php');


?>

</section>

<?php
    endwhile;
endif;

get_footer();
?>
