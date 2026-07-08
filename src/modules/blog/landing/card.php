<div class="col-12 col-md-6 col-lg-4 mb-3">
    <a href="<?php echo $post["link"] ?>">
        <div class="card h-100 module-card">
            <img src="<?php echo $post["thumbnail"] ?>" alt="<?php echo $post["title"]; ?>" class="img-fluid module-card-image" height="372" width="166">
            <p class="card-category-name mb-2">
                <?php
                foreach ($post['categories'] as $category):
                    echo $category['title'];
                endforeach;
                ?>
            </p>
            <h2 class="card-title mb-0"><?php echo $post['title']; ?></h2>
            <p class="card-date mb-0"><?php echo $post['published_date']; ?></p>
            <div class="card-desc">
                <p><?php echo esc_html(wp_trim_words($post['excerpt'], 20, '...')); ?></p>
            </div>
        </div>
    </a>
</div>