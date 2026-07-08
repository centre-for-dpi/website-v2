<?php

class Taxonomy
{
	public static function getPostCount($post_type, $taxonomy_slug, $term_id)
	{
		$args = array(
        	'post_type' => $post_type,  // Change 'post' to your custom post type if needed
        	'tax_query' => array(
            	array(
                	'taxonomy' => $taxonomy_slug,
                	'field' => 'term_id',
                	'terms' => $term_id,
            	),
        	),
        	'posts_per_page' => -1, // Get all posts
    	);

    	$query = new WP_Query($args);
    	$post_count = $query->found_posts;

		return $post_count;
	}

	public static function getField($name, $term_id) {
		$meta_value = get_term_meta($term_id, $name, true);

		return $meta_value;
	}


}
