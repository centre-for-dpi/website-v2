<?php
/**
 * Template Name: Page - News Single
 * Description: 
 */

get_header();
?>

<section>

<?php include Helper::getBlock('page-news-single/news-single-banner/index.php'); ?>

<?php include Helper::getBlock('page-news-single/news-single-tittle/index.php'); ?>

<?php include Helper::getBlock('page-news-single/news-single-content/index.php'); ?>

<?php include Helper::getBlock('page-news-single/news-single-related/index.php'); ?>

<?php include Helper::getBlock('newsletter/index.php'); ?>
</section>

<?php get_footer(); ?>