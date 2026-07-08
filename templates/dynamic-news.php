<?php
/**
 * Template Name: Dynamic - News   
 * Description:     
 */

get_header();
?>

<?php include Helper::getBlock('page-news/news-hero/index.php'); ?>

<?php include Helper::getBlock('page-news/news-listing/index.php'); ?>

<?php get_footer(); ?>
 