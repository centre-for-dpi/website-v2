<?php
/**
* Template Name: Dynamic - Blogs
* Description: Displays page title and content in Hero section above 3 widgets.
*
* @package WordPress
* @subpackage BootstrapWP
*/


get_header();
?>

<?php include Helper::getBlock('page-blogs/blogs-hero/index.php'); ?>

<?php include Helper::getBlock('page-blogs/blogs-listing/index.php'); ?>


<?php get_footer(); ?>
 
