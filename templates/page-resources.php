<?php
/**
 * Template Name: Page - Resources   
 * Description:     
 */

get_header();
?>

<?php include Helper::getBlock('page-resources/resources-hero/index.php'); ?>

<div class="resources-divider-wrap"><hr class="resources-divider"></div>


<?php include Helper::getBlock('page-resources/resources-dpi-assistant/index.php'); ?>

<div class="resources-divider-wrap"><hr class="resources-divider"></div>

<?php include Helper::getBlock('page-resources/resources-curated-materials/index.php'); ?>

<div class="resources-divider-wrap"><hr class="resources-divider"></div>

<?php include Helper::getBlock('page-resources/resources-dpi-in-action/index.php'); ?>

<?php get_footer(); ?>
