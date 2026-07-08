<?php

class Helper
{
	public static function slugify($urlString)
	{
		$slug = preg_replace('/[^A-Za-z0-9-]+/', '-', strtolower(trim($urlString)));

		$slug = str_replace('_', '-', $slug);

		$slug = rtrim($slug, '-');
		
		return $slug;
	}

	public static function deslugify($slug)
	{
		// Replace both dashes and underscores with spaces
		$string = str_replace(['-', '_'], ' ', $slug);
		
		// Capitalize each word and return
		return ucwords($string);
	}

	public static function getCodePath($Path)
	{
		return trailingslashit(get_template_directory()).$Path ;
	}

	public static function getModule($Path)
	{
		return trailingslashit(get_template_directory()).'src/modules/'.$Path ;
	}

	public static function getBlock($Path)
	{
		return trailingslashit(get_template_directory()).'src/blocks/'.$Path ;
	}

	public static function getPublicPath($Path)
	{
		return trailingslashit(get_template_directory_uri()).'public/'.$Path ;
	}

	public static function getImage($Path)
	{
		echo self::getImagePath($Path);
	}

	public static function getImagePath($Path)
	{
		$ImgFilePath = get_template_directory().'/public/img/'.$Path;

		if(file_exists($ImgFilePath))
		{
			return get_template_directory_uri().'/public/img/'.$Path;
		}

		return get_template_directory_uri().'/public/img/default.jpg';		
	}


	public static function getPageUrl($PageName = '', $return = false)
	{
		// if pagename doesn't have / at the end, add it
		if(substr($PageName, -1) !== '/') {
			$PageName .= '/';
		}

		$finalUrl = home_url('/'.$PageName);

		// Remove multiple consecutive slashes after http:// or https://
		$finalUrl = preg_replace('#(?<!:)//+#', '/', $finalUrl);

		if($return) {
			return $finalUrl;
		}

		echo $finalUrl;
	}

	public static function getWebsite($Property)
	{
		echo bloginfo($Property);
	}

	public static function getGravatar($Email)
	{
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $Email ) ) ) . "?d=monsterid&s=50";
		
		echo $grav_url;
	}

	public static function getCustomField($Field, $Id = null, $Default = null)
	{
		if($Id == null)
		{
			$Id = get_the_ID();
		}

		$Value = get_post_meta($Id, $Field, TRUE); 

		if(empty($Value))
		{
			$Value = $Default;
		}

		return $Value;
	}

	public static function getPostTypeList($query_args)
	{
		$args = wp_parse_args( $query_args, array(
		'post_type'   => 'post',
		'numberposts' => 100,
		) );

	    $posts = get_posts( $args );

	    $post_options = array();

	    if ( $posts ) 
	    {
	        foreach ( $posts as $post ) 
	        {
				$post_options[ $post->ID ] = $post->post_title;
	        }
	    }

	    return $post_options;
	}

	//categories list of all posts.
	public static function getPostCategoryList($query_args_cat)
	{

		$args = wp_parse_args( $query_args_cat, array(
			'numberposts' => 100,
			'hide_empty' => true,
			'parent'  => 0

		) );

		$categories = get_categories( $args );

	    $post_cat_options = array();

	        foreach ( $categories as $category ) 
	        {
				$post_cat_options[ $category->name ] = $category->name;
	        }

	    return $post_cat_options;
	}

	public static function getBreadCrumbs()
	{
		$home      = 'Home'; 
		$sep       = '<span class="divider"></span>';
		$before    = '<li class="active">'; 
		$after     = '</li>'; 

		if (!is_home() && !is_front_page() || is_paged()) 
		{
			echo '<ul class="breadcrumb">';
			echo '<li>YOU ARE HERE : &nbsp;&nbsp;</li>';

			global $post;
			$homeLink = home_url();
			echo '<li><a href="' . $homeLink . '">' . $home . '</a> '.$sep. '</li> ';
			if (is_category()) 
			{
				global $wp_query;
				$cat_obj   = $wp_query->get_queried_object();
				$thisCat   = $cat_obj->term_id;
				$thisCat   = get_category($thisCat);
				$parentCat = get_category($thisCat->parent);
				if ($thisCat->parent != 0) 
				{
					echo get_category_parents($parentCat, true, $sep);
				}
				echo $before . 'Archive by category "' . single_cat_title('', false) . '"' . $after;
			} 
			elseif (is_day()) 
			{
				echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time(
					'Y'
					) . '</a></li> ';
				echo '<li><a href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '">' . get_the_time(
					'F'
					) . '</a></li> ';
				echo $before . get_the_time('d') . $after;
			} 
			elseif (is_month()) 
			{
				echo '<li><a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time(
					'Y'
					) . '</a></li> ';
				echo $before . get_the_time('F') . $after;
			} 
			elseif (is_year()) 
			{
				echo $before . get_the_time('Y') . $after;
			} 
			elseif (is_single() && !is_attachment()) 
			{
				if(get_post_type() != 'post') 
				{
					$post_type = get_post_type_object(get_post_type());
					$slug      = $post_type->rewrite;
					echo '<li><a href="' . $homeLink . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>'.$sep.'</li> ';
					echo $before . get_the_title() . $after;
				} 
				else 
				{
					$cat = get_the_category();
					$cat = $cat[0];
					echo '<li>'.get_category_parents($cat, true, $sep).'</li>';
					echo $before . get_the_title() . $after;
				}
			} 
			elseif (!is_single() && !is_page() && get_post_type() != 'post' && !is_404()) 
			{
				$post_type = get_post_type_object(get_post_type());
				echo $before . $post_type->labels->singular_name . $after;
			} 
			elseif (is_attachment()) 
			{
				$parent = get_post($post->post_parent);
				$cat    = get_the_category($parent->ID);
				$cat    = $cat[0];
				echo get_category_parents($cat, true, $sep);
				echo '<li><a href="' . get_permalink(
					$parent
					) . '">' . $parent->post_title . '</a></li> ';
				echo $before . get_the_title() . $after;

			} 
			elseif (is_page() && !$post->post_parent) 
			{
				echo $before . get_the_title() . $after;
			} 
			elseif (is_page() && $post->post_parent) 
			{
				$parent_id   = $post->post_parent;
				$breadcrumbs = array();
				while ($parent_id) 
				{
					$page          = get_page($parent_id);
					$breadcrumbs[] = '<li><a href="' . get_permalink($page->ID) . '">' . get_the_title(
						$page->ID
						) . '</a>' . $sep . '</li>';
					$parent_id     = $page->post_parent;
				}
				$breadcrumbs = array_reverse($breadcrumbs);
				foreach ($breadcrumbs as $crumb) 
				{
					echo $crumb;
				}
				echo $before . get_the_title() . $after;
			} 
			elseif (is_search()) 
			{
				echo $before . 'Search results for "' . get_search_query() . '"' . $after;
			} 
			elseif (is_tag()) 
			{
				echo $before . 'Posts tagged "' . single_tag_title('', false) . '"' . $after;
			} 
			elseif (is_author()) 
			{
				global $author;
				$userdata = get_userdata($author);
				echo $before . 'Articles posted by ' . $userdata->display_name . $after;
			} 
			elseif (is_404()) 
			{
				echo $before . 'Error 404' . $after;
			}

			echo '</ul>';
		}
	}

	public static function getBlogSearch()
	{
		?>

		<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="hidden" value="post" name="post_type" id="post_type" />
			<div class="input-group">
				<input type="text" class="form-control" value="<?php echo get_search_query(); ?>" name="s" id="s" placeholder="search blog here" />
				<span class="input-group-btn">
					<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
				</span>
			</div>
		</form>

		<?php
	}

	public static function createPage($PageTitle, $PageSlug, $Content, $Template)
	{
		$post_data = array(
			'post_title'    => wp_strip_all_tags($PageTitle),
			'post_name'    => $PageSlug,
			'post_content'  => $Content,
			'post_type'     => 'page',
			'page_template' => $Template,
			'post_status' 	=> 'publish'
		);

		$post = get_page_by_title($PageTitle, OBJECT, 'page');
				
		if(empty($post))			
		{
			wp_insert_post($post_data);
		}
	}	

}
