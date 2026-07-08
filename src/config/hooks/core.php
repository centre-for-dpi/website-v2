<?php

/* Register template redirect action callback */
add_action('template_redirect', 'meks_remove_wp_archives');
 
/* Remove archives */
function meks_remove_wp_archives(){
  //If we are on category or tag or date or author archive
  if(  is_tag() || is_date() || is_author() ) {
    global $wp_query;
    $wp_query->set_404(); //set to 404 not found page
  }
}

function wp_version_remove_version() {
    return '';
}

add_filter('the_generator', 'wp_version_remove_version');
?>