<?php
/**
 * Template Name: Page - DaaS   
 * Description:     
 */

get_header();
?>


<?php include Helper::getBlock('page-daas/daas-hero/index.php'); ?>
<div class="daas-section-separator"></div>

<?php include Helper::getBlock('page-daas/global-momentum/index.php'); ?>

<div class="daas-section-separator"></div>

<?php include Helper::getBlock('page-daas/approach/index.php'); ?>

<div class="daas-section-separator"></div>

<?php include Helper::getBlock('page-daas/ecosystem/index.php'); ?>
<div class="daas-section-separator"></div>

<?php include Helper::getBlock('page-daas/advisory-board/index.php'); ?>

<?php get_footer(); ?>
