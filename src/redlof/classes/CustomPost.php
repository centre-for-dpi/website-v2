<?php

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class CustomPost
{
    private $post_id;
    private $post_type;
    private $category_taxonomy;

    public function __construct($post_type, $category_taxonomy = null) {
        $this->post_id = null;
        $this->post_type = $post_type;
        $this->category_taxonomy = $category_taxonomy ?? $post_type . '-category';

    }

    public function setID($post_id) {
        $this->post_id = $post_id;
    }

    private function _formatPost($post, $args = []) {

        if (!$post) {
            return null;
        };

        if(empty($args) || in_array("all", $args)) {
            // Default args
            $args = [
                "id",
                "title",
                "slug",
                "link",
                "excerpt",
                "status",
                "thumbnail",
                "featured_image",
                "content",
                "categories",
                "meta_fields",
                "published_date",
                "published_time",
                "author"
            ];
        }


        $post_data = [];

        $post_data['id'] = $post->ID;
        $post_data['title'] = $post->post_title;
        $post_data['slug'] = $post->post_name;
        $post_data['link'] = get_the_permalink($post->ID);
        $post_data['excerpt'] = empty($post->post_excerpt) ? wp_trim_words(strip_tags($post->post_content), 50, '...') : $post->post_excerpt;
        $post_data['status'] = $post->post_status;
        
        if (in_array("thumbnail", $args)) {
            $post_data['thumbnail'] = get_the_post_thumbnail_url($this->post_id, 'medium');
        }
                
        if (in_array("featured_image", $args)) {
            $post_data['featured_image'] = $this->getPostFeaturedImage();
        }
        
        if (in_array("content", $args)) {
            $post_data['content'] = apply_filters('the_content', $post->post_content);
        }

        if (in_array("categories", $args)) {
            $post_data['categories'] = $this->getCategories();
        }

        if (in_array("meta_fields", $args)) {
            $post_data['meta_fields'] = $this->getAllPostMeta();
        }

        if (in_array("published_date", $args)) {
            $post_data['published_date'] = get_the_date('M j, Y', $post->ID);
        }

        if (in_array("published_time", $args)) {
            $post_data['published_time'] = get_the_time('g:i a', $post->ID);
        }

        if (in_array("author", $args)) {
            $author_id = $post->post_author;
            $post_data['author'] = [
                'display_name' => get_the_author_meta('display_name', $author_id),
                'email' => get_the_author_meta('user_email', $author_id),
                'first_name' => get_the_author_meta('first_name', $author_id),
                'last_name' => get_the_author_meta('last_name', $author_id),
                'avatar' => get_avatar_url($author_id),
            ];
        }

        return $post_data;
    }

    public function getPost($args = [])
    {
        $post = get_post($this->post_id);
        
        if (!$post) {
            return null;
        };

        $post_data = $this->_formatPost($post, $args);
        
        return $post_data;
    }

    public function getCategories() {
        $categories = wp_get_post_terms($this->post_id, $this->category_taxonomy);

        if (!$categories || is_wp_error($categories)) {
            return [];
        }

        return array_map(function($category) {
            return [
                'id' => $category->term_id,
                'title' => $category->name,
                'slug' => $category->slug,
                'link' => get_term_link($category)
            ];
        }, $categories);
    }

    public function getPostMeta($meta_key)
    {
        return get_post_meta($this->post_id, $meta_key, true);
    }

    public function getAllPostMeta()
    {
        $meta = get_post_meta($this->post_id);
        $formatted_meta = [];

        if(empty($meta)) {
            return $formatted_meta;
        }
        
        foreach ($meta as $key => $values) {
            $formatted_meta[$key] = $values[0];
        }
        
        return $formatted_meta;
    }

    public function getPostFeaturedImage()
    {
        $image_id = get_post_thumbnail_id($this->post_id);
        
        if (!$image_id) {
            return null;
        }

        return [
            'url' => get_the_post_thumbnail_url($this->post_id, 'full'),
            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
            'id' => $image_id
        ];
    }
    
    public function getRelatedPosts($limit = 5) {
        
        $post_categories = wp_get_post_terms($this->post_id, $this->category_taxonomy, ['fields' => 'ids']);
        
        if (empty($post_categories) || is_wp_error($post_categories)) {
            return [];
        }
        
        $args = [
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => $limit,
            'post__not_in' => [$this->post_id],
            'tax_query' => [
                [
                    'taxonomy' => $this->category_taxonomy,
                    'field' => 'term_id',
                    'terms' => $post_categories
                ]
            ],
            'orderby' => 'rand'
        ];
        
        $related_posts = get_posts($args);

        $mapped_posts = array_map(function($post) {
            
            $postHandler = new CustomPost($this->post_type, $this->category_taxonomy);
            
            $postHandler->setID($post->ID);
            
            return $postHandler->getPost(["thumbnail","published_date","author"]);
        }, $related_posts);
        
        return $mapped_posts;
    }

    // List of Posts methods

    public function getListOfPosts($args = []) {
            $query_args = wp_parse_args($args, [
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ]);

        $posts = get_posts($query_args);

        $formatted_posts = array_map(function($post) use ($args) {
            $postHandler = new CustomPost($this->post_type, $this->category_taxonomy);
            $postHandler->setID($post->ID);
            return $postHandler->getPost(array_merge($args, ["categories"]));
        }, $posts);

        return $formatted_posts;
    }


    public function getAvailableCategories() {
        // Get all categories for the post type
        $categories = get_terms([
            'taxonomy' => $this->category_taxonomy,
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);
    
        if (!$categories || is_wp_error($categories)) return [];
    
        return array_map(function($category) {
            return [
                'id' => $category->term_id,
                'title' => $category->name,
                'slug' => $category->slug,
                'link' => esc_url(get_term_link($category)),
                'count' => $category->count // Add count of posts in this category
            ];
        }, $categories);
    }

    public function getPostsByCategory($category_slug, $args = [], $query_args = []) {
        // Merge with default query arguments
        $default_query_args = [
            'post_type' => $this->post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'tax_query' => [
                [
                    'taxonomy' => $this->category_taxonomy,
                    'field' => 'slug',
                    'terms' => $category_slug
                ]
            ]
        ];

        $final_query_args = wp_parse_args($query_args, $default_query_args);
        
        $posts = get_posts($final_query_args);

        $formatted_posts = array_map(function($post) use ($args) {
            $postHandler = new CustomPost($this->post_type, $this->category_taxonomy);
            $postHandler->setID($post->ID);
            return $postHandler->getPost(array_merge($args, ["categories"]));
        }, $posts);

        return $formatted_posts;
    }


    public static function getImageUrl($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'full');

        if (!$image_url) {
            return null;
        }

        return $image_url;
    }
}
