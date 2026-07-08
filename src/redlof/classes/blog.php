<?php

class Blog
{
    public static function getRecent($Count = -1)
    {
        $args = array(
            'posts_per_page' => $Count,
            'offset' => 0,
            'category' => '',
            'category_name' => '',
            'orderby' => 'post_date',
            'order' => 'DESC',
            'include' => '',
            'exclude' => '',
            'meta_key' => '',
            'meta_value' => '',
            'post_type' => 'post',
            'post_mime_type' => '',
            'post_parent' => '',
            'post_status' => 'publish',
            'suppress_filters' => false, );

        $posts_array = get_posts($args);

        return self::formPostData($posts_array);
    }

    public static function getFeatured($Count = -1)
  	{
  		$args = array(
  			'posts_per_page'   => $Count,
  			'offset'           => 0,
  			'category'         => '',
  			'category_name'    => '',
  			'orderby'          => 'post_date',
  			'order'            => 'DESC',
  			'include'          => '',
  			'exclude'          => '',
  			'meta_key'         => '',
  			'meta_value'       => '',
  			'post_type'        => 'post',
  			'post_mime_type'   => '',
  			'post_parent'      => '',
  			'post_status'      => 'publish',
  			'meta_query' 	   => array(
  				array(
  					'key' =>  '_post_isfeatured',
  					'value' => 'on',
  					)),
  			'suppress_filters' => true );

  		$posts_array = get_posts( $args );

  		return self::formPostData($posts_array);
  	}

    private static function formPostData($Results)
    {
        $AllItems = array();

        foreach ($Results as $post) {
            setup_postdata($post);

            $Data = array();

            $Data['id'] = $post->ID;
            $Data['title'] = $post->post_title;

            $Data['author'] = get_the_author();
            $Data['author_id'] = get_the_author_meta('ID');
            $Data['date'] = get_the_date('', $post->ID);
            $Data['url'] = get_permalink($post->ID);
            $Data['order'] = $post->menu_order;

            $Data['categories'] = Category::getForPost($post->ID);

            $Data['content'] = $post->post_content;
            $Data['content'] = apply_filters('the_content', $Data['content']);

            self::getPostCustom($Data, $post->ID);

            array_push($AllItems, $Data);
        }

        wp_reset_postdata();

        return $AllItems;
    }

    private static function getPostCustom(&$Data, $ID)
    {
        $Custom = get_post_custom($ID);

        foreach ($Custom as $Key => $Value) {
            $Data[$Key] = $Value[0];
        }
    }

    public static function getCategories($Count = -1)
    {
        $args = array(
            'type' => 'post',
            'orderby' => 'name',
            'hide_empty' => 1,
            'order' => 'ASC',
            );

        $categories = get_categories($args);

        return $categories;
    }

    public static function getPost($PostId)
    {
        $Data = array();

        $PostData = get_post($PostId);

        $Data['title'] = $PostData->post_title;

        $Data['content'] = $PostData->post_content;
        $Data['content'] = apply_filters('the_content', $Data['content']);

        return $Data;
    }

    public static function getRelatedPosts()
    {
        echo '<ul id="redlof-related-posts">';

        global $post;

        $tags = wp_get_post_tags($post->ID);

        if ($tags) {
            foreach ($tags as $tag) {
                $tag_arr .= $tag->slug.',';
            }

            $args = array(
                'tag' => $tag_arr,
                'numberposts' => 5, /* you can change this to show more */
                'post__not_in' => array($post->ID),
                );

            $related_posts = get_posts($args);

            if ($related_posts) {
                foreach ($related_posts as $post) : setup_postdata($post);
                ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute();
                ?>"><?php the_title();
                ?></a></li>
			<?php endforeach;
            } else {
                ?>
			<?php echo '<li class="no_related_post">'.__('No Related Posts Yet!', 'redloftheme').'</li>';
                ?>
			<?php

            }
        }

        wp_reset_postdata();

        echo '</ul>';
    }

    public static function getPostsPagination($query = null)
    {
        global $wp_query;

        $query = $query ? $query : $wp_query;
        $big = 999999999;

        $paginate = paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'type' => 'array',
            'total' => $query->max_num_pages,
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
            )
        );

        if ($query->max_num_pages > 1) :
            ?>
		<ul class="pagination">
			<?php
            foreach ($paginate as $page) {
                echo '<li>'.$page.'</li>';
            }
        ?>
		</ul>
		<?php
        endif;
    }

    public static function getPostsArchives()
    {
        $args = array(
            'type' => 'monthly',
            'limit' => '',
            'format' => 'html',
            'before' => '',
            'after' => '',
            'show_post_count' => false,
            'echo' => 1,
            'order' => 'DESC',
            );

        echo '<ul class="archive-list">';
        wp_get_archives($args);
        echo '</ul>';
    }

    public static function getCategoriesList()
    {
    	$Categories = Category::getAll();

        echo '<ul class="category-list">';

        foreach ($Categories as $Cat) {
            echo '<li><a href="'.$Cat['url'].'">'.$Cat['name'].' ('.$Cat['count'].')</a></li>';
        }

        echo '</ul>';
    }

    public static function getTagCloud()
    {
        $args = array(
            'smallest' => 0.8,
            'largest' => 2,
            'unit' => 'rem',
            'number' => 30,
            'format' => 'flat',
            'separator' => "\n",
            'orderby' => 'name',
            'order' => 'RAND',
            'exclude' => null,
            'include' => null,
            'link' => 'view',
            'taxonomy' => 'post_tag',
            'echo' => true,
            'child_of' => null, // see Note!
        );

        wp_tag_cloud($args);
    }

    public static function getTagList()
    {
        $tags = get_tags();

        $TagArray = array();

        foreach($tags as $tag)
        {
            $TagArray[$tag->term_id]['name'] = $tag->name;
            $TagArray[$tag->term_id]['slug'] = $tag->slug;
            $TagArray[$tag->term_id]['count'] = $tag->count;
            $TagArray[$tag->term_id]['url'] = get_tag_link($tag->term_id);
        }

        return $TagArray;
    }

    public static function getTags()
    {
        $Tags = get_the_tags();

        if(empty($Tags))
        {
            $Data = new stdClass();

            $Data->name = '';
            $Data->term_id = -1;
            $Data->url = '#';
            $Data->count = 0;

            $Tags[0] = $Data;
        }

        return $Tags;
    }

    public static function getPostImageUrl()
    {
        $img_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');

        echo $img_url[0];
    }
}
