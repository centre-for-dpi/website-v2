<?php
/**
 * Template Name: Page - About Us
 * Description: 
 */

get_header();
?>

<?php include Helper::getBlock('page-about/about-hero/index.php'); ?>

<div class="purple-section-separator" aria-hidden="true"></div>

<?php //include Helper::getBlock('page-about/global/index.php'); ?>

<?php //include Helper::getBlock('page-about/team/index.php'); ?>

<?php include Helper::getBlock('page-about/team-new/index.php'); ?>

<?php include Helper::getBlock('video/index.php'); ?>

<?php include Helper::getBlock('page-about/core-philosophy/index.php'); ?>

<?php include Helper::getBlock('page-about/executive-board/index.php'); ?>

<?php include Helper::getBlock('page-about/quote-section/index.php'); ?>

<?php include Helper::getBlock('page-about/talent-led-organisation/index.php'); ?>

<?php include Helper::getBlock('page-about/our-culture-code/index.php'); ?>

<div class="purple-section-separator" aria-hidden="true"></div>


<?php include Helper::getBlock('page-about/open-positions/index.php'); ?>

<?php include Helper::getBlock('page-about/backed-by/index.php'); ?>


<?php get_footer(); ?>
