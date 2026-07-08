<?php
/**
 * Template Name: Page - Home
 * Description: 
 */

get_header();
?>

<?php include Helper::getBlock('page-home/home-hero/index.php'); ?>

<?php include Helper::getBlock('foundational-facts/index.php'); ?>

<?php include Helper::getBlock('page-home/country-selection/index.php'); ?>

<div class="purple-section-separator" aria-hidden="true"></div>

<?php include Helper::getBlock('page-home/multi-dimentional-approach/index.php'); ?>

<?php include Helper::getBlock('page-home/testimonials/index.php'); ?>

<?php include Helper::getBlock('page-home/latest-news/index.php'); ?>

<?php include Helper::getBlock('faqs/index.php'); ?>

<?php get_footer(); ?>
