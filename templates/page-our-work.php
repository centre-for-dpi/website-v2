<?php
/**
 * Template Name: Page - Our Work   
 * Description: 
 */

get_header();
?>

<?php include Helper::getBlock('page-our-work/work-hero/index.php'); ?>

<?php include Helper::getBlock('page-our-work/work-banner/index.php'); ?>

<?php include Helper::getBlock('page-our-work/country-advisory/index.php'); ?>

<div class="purple-section-separator" aria-hidden="true"></div>

<?php include Helper::getBlock('page-our-work/work-continents/index.php'); ?>

<?php include Helper::getBlock('page-our-work/deploying-dpi/index.php'); ?>

<?php include Helper::getBlock('page-our-work/dpi-daas-way/index.php'); ?>

<?php include Helper::getBlock('page-our-work/ecosystem/index.php'); ?>

<?php include Helper::getBlock('page-our-work/resources-cta/index.php'); ?>

<?php //include Helper::getBlock('page-our-work/work-voices-we-serve/index.php'); ?>

<?php //include Helper::getBlock('page-our-work/work-our-approach/index.php'); ?>

<?php //include Helper::getBlock('opportunities-cta/index.php'); ?>

<?php get_footer(); ?>
