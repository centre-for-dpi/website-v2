<?php

/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function redlof_scripts_and_styles() {

  	global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  	if (!is_admin()) {


		

	  	//wp_enqueue_script( 'main-js', get_stylesheet_directory_uri() . '/public/js/main.min.js', array( 'jquery' ), '', true );

	    wp_localize_script( 'form-js', 'FACAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );            
		
	  
	  	//wp_enqueue_script('main-js');

 	}
}
 
