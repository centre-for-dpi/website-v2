<?php

class Category
{
	public static function getAll($Type = 'post')
	{
		if($Type == 'post')
		{
			$args = array(
				'type' 		  => $Type,
				'orderby' 		  => 'name',
				'hide_empty'     => 1,
				'order' 		  => 'ASC'
				);

			$categories = get_categories($args);
		}
		else
		{
			$taxonomies = array($Type);

			$args = array(
				'orderby' 		  => 'id',
				'hide_empty'     => 0,
				'order' 		  => 'ASC'
				);

			$categories = get_terms($taxonomies, $args);	
		}

		return self::formData($categories);
	}

	public static function getForPost($Id)
	{
		$categories = get_the_category($Id);

		return self::formData($categories);
	}

	private static function formData($Results)
	{
		$AllItems = array();

		foreach ( $Results as $category )
		{
			$data = array();

			$data['id'] 		= $category->term_id;
			$data['name'] 		= $category->name;
			$data['slug']		= $category->slug;
			$data['count']		= $category->count;
			$data['url']		= get_category_link($category->term_id);	

			array_push($AllItems, $data);
		}  

		return $AllItems;
	}		
}

?>