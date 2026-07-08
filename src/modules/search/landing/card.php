<div class="searchCard mb-4">
    <div class="card">
        <div class="row g-0">
               
            <div class="col-12">
                <div class="card-body">
                    <?php
                    // Get post type label
                    $post_type_obj = get_post_type_object(get_post_type());
                    $post_type_label = $post_type_obj ? $post_type_obj->labels->singular_name : 'Post';
                    ?>
                    <div class="post-type-badge mb-2">
                        <?php echo esc_html($post_type_label); ?>
                    </div>
                    
                    <h2 class="card-title mb-0">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    
                    <p class="card-text">
                        <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                    </p>
                    
                    <div class="meta-info">
                        <span class="date"><?php echo get_the_date(); ?></span>
                        <?php
                        // Show categories if they exist
                        $taxonomy = get_post_type() . '-category';
                        
                        if (taxonomy_exists($taxonomy)) {
                            $terms = get_the_terms(get_the_ID(), $taxonomy);
                            if ($terms && !is_wp_error($terms)) {
                                echo ' • ';
                                $term_names = array_map(function($term) {
                                    return $term->name;
                                }, $terms);
                                echo esc_html(implode(', ', $term_names));
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>