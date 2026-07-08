<?php 
get_header();

// Initialize for a specific post type
$postHandler = new CustomPost('video');

$postHandler->set

// Get complete post details
$post = $postHandler->getListOfPosts(["meta_fields", "content"]);

$categories = $postHandler->getAvailableCategories();


?>

<section>
<br><br><br>   <br><br><br>    


<?php
echo "<pre>";
var_dump($categories);
var_dump($post);
echo "</pre>"
?>

</section>

<?php get_footer(); ?>
