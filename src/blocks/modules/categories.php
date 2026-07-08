<ul class="nav nav-tabs d-flex" id="myTab" role="tablist">
    <li class="nav-item active-learn" role="presentation">
        <a href="<?php echo Helper::getPageUrl($pageConfig['page_url']); ?>" 
           class="<?php echo (empty($pageConfig['current_term']) ? 'active' : ''); ?>" 
           id="all-tab" 
           aria-label="all-tab" 
           role="tab"
           aria-controls="all-content" 
           aria-selected="<?php echo (empty($pageConfig['current_term']) ? 'true' : 'false'); ?>">All</a>
    </li>
    <?php
    $terms = $postHandler->getAvailableCategories();

    foreach ($terms as $term): 
        $isActive = isset($pageConfig['current_term']) && $pageConfig['current_term'] && $pageConfig['current_term']->slug === $term['slug'];
    ?>
        <li class="nav-item" role="presentation">
            <a href="<?php echo $term['link']; ?>" 
               class="nav-link <?php echo $isActive ? 'active' : ''; ?>" 
               id="tab-<?php echo esc_attr($term['id']); ?>" 
               role="tab"
               aria-controls="content-<?php echo esc_attr($term['id']); ?>" 
               aria-selected="<?php echo $isActive ? 'true' : 'false'; ?>">
                <?php echo esc_html($term['title']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>