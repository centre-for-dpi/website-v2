<?php

/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

// Get Custom Content length
function custom_excerpt_length($length)
{
	return 40;
}

add_filter('excerpt_length', 'custom_excerpt_length', 999);

//remove_filter('the_content', 'wpautop');

//Create a Function to Flush Permalinks
function redlof_flush_permalinks() {

	//Ensure the $wp_rewrite global is loaded
	global $wp_rewrite;

	//Call flush_rules() as a method of the $wp_rewrite object
	$wp_rewrite->flush_rules( true );
}

//Add Function "n_flush_permalinks()" to the "save_post" action hook:
add_action( 'save_post', 'redlof_flush_permalinks' );

/*Remove empty paragraph tags from the_content*/
function removeShortCodeBreaks($content) {

	$block = join("|",array("section", 
							"container", 
							"row", 
							"col12", 
							"col10", 
							"col9",
							"col8",
							"col7",
							"col6",
							"col5",
							"col4",
							"col3",
							"col2",
							"col1"
							));
    
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);

	return $rep;
}

add_filter('the_content', 'removeShortCodeBreaks');
