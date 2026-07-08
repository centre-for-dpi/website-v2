<?php

// Get current taxonomy term object
$current_term = get_queried_object();

if(isset($current_term) && $current_term->post_type == 'page') {
    $current_term = null;
}

// Configuration array for blogs page
$pageConfig = [
    'post_type' => 'post',
    'taxonomy' => 'category',
    'page_url' => '/blog',
    'current_term' => $current_term,
];

// Initialize for a specific post type
$postHandler = new CustomPost($pageConfig['post_type'], $pageConfig['taxonomy']);

if (empty($current_term)) {
    $posts = $postHandler->getListOfPosts(["thumbnail", "published_date","author"]);
} else {
    $posts = $postHandler->getPostsByCategory($current_term->slug, ["thumbnail", "published_date"]);
}

$categories = $postHandler->getAvailableCategories();


?>

<section class="redlof-block blogs-listing py-5">
  <div class="container">
    <!-- Tab Navigation -->
    <!-- <div class="blogs-listing__nav-wrapper">
      <ul class="nav blogs-listing__nav" role="tablist">
      <li class="nav-item" role="presentation">
          <a href="<?php echo Helper::getPageUrl($pageConfig['page_url']); ?>" class="blogs-listing__tab text-uppercase <?php echo empty($current_term) ? 'active' : ''; ?>">ALL</a>
        </li>
        <?php foreach ($categories as $category): 
          $isActive = isset($current_term) && $current_term && $current_term->slug === $category['slug'];
        ?>
          <li class="nav-item" role="presentation">
            <a href="<?php echo $category['link']; ?>" class="blogs-listing__tab text-uppercase <?php echo $isActive ? 'active' : ''; ?>"><?php echo $category['title']; ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php if (count($categories) > 5): ?>
      <button class="blogs-listing__nav-arrow" aria-label="Scroll tabs">
        <i class="fa-solid fa-arrow-right"></i>
      </button>
      <?php endif; ?>
    </div> -->

    <!-- Blog Cards Grid -->
    <div class="row g-4 mt-4">
      <?php foreach ($posts as $post): ?>
        <!-- Blog Card 1 -->
        <div class="col-md-6 col-lg-4">
        <article class="blog-card">
          <div class="blog-card__image">
            <img src="<?php echo !empty($post['thumbnail']) ? $post['thumbnail'] : Helper::getImagePath('temp/news-1.png'); ?>" alt="<?php echo esc_attr($post['title']); ?>" loading="lazy" />
          </div>
          <div class="blog-card__meta">
            <span class="blog-card__category text-uppercase"><?php echo isset($post['categories'][0]['title']) ? $post['categories'][0]['title'] : ''; ?></span>
            <span class="blog-card__separator">|</span>
            <span class="blog-card__date text-uppercase"><?php echo $post['published_date']; ?></span>
          </div>
          <h3 class="blog-card__title">
            <a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a>
          </h3>
          <span class="blog-card__author"><?php echo isset($post['author']['first_name']) ? $post['author']['first_name'] . ' ' . $post['author']['last_name'] : ''; ?></span>
        </article>
        </div>
      <?php endforeach; ?>
    </div>

      
    <!-- Pagination -->
    <?php
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $post_counts = wp_count_posts($pageConfig['post_type']);
    $total_posts = isset($post_counts->publish) ? $post_counts->publish : 0;
    $posts_per_page = get_option('posts_per_page');
    $total_pages = ($posts_per_page > 0) ? ceil($total_posts / $posts_per_page) : 0;
    
    if ($total_pages > 1): ?>
    <nav class="blogs-listing__pagination mt-5" aria-label="Blog pagination">
      <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?php echo ($paged == $i) ? 'active' : ''; ?>">
            <a class="page-link" href="<?php echo esc_url(get_pagenum_link($i)); ?>"><?php echo $i; ?></a>
          </li>
        <?php endfor; ?>
        <?php if ($paged < $total_pages): ?>
        <li class="page-item">
          <a class="page-link page-link--next" href="<?php echo esc_url(get_pagenum_link($paged + 1)); ?>" aria-label="Next">
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </nav>
    <?php endif; ?>
  </div>

  <!-- Divider -->
  <div class="blogs-listing__divider mt-5"></div>
</section>

<style>
  .blogs-listing {
    background-color: #ffffff;
  }

  /* Tab Navigation */
  .blogs-listing__nav-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 48px;
    padding: 0px 50px;
  }

  .blogs-listing__nav {
    display: flex;
    gap: 8px;
    border: none;
    flex-wrap: nowrap;
    justify-content: center;
  }

  .blogs-listing__nav .nav-item {
    list-style: none;
  }

  .blogs-listing__tab {
    color: #5E6979;
    background-color: #FAF5FF;

    border: none;
    padding: 10px 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
    white-space: nowrap;

    font-weight: 500;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 8%;
  }

  .blogs-listing__tab:hover {
    color: #4948E1;
    border-color: #4948E1;
  }

  .blogs-listing__tab.active {
    background-color: #4948E1;
    color: #ffffff;
    border-color: #4948E1;
  }

  .blogs-listing__nav-arrow {

    height: 40px;
    width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;

    color: #5E6979;
    background-color: #FAF5FF;

    border: none;
    padding: 10px 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
    white-space: nowrap;
  }

  .blogs-listing__nav-arrow:hover {
    border-color: #4948E1;
    color: #4948E1;
  }

  .blogs-listing__nav-arrow i {
    font-size: 0.9rem;
    color: #1a1a2e;
  }

  /* Blog Card */
  .blog-card {
    display: flex;
    flex-direction: column;
  }

  .blog-card__image {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
  }

  .blog-card__image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .blog-card:hover .blog-card__image img {
    transform: scale(1.05);
  }

  .blog-card__meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
  }

  .blog-card__category {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6564DB;
    letter-spacing: 0.05em;
  }

  .blog-card__separator {
    color: #9ca3af;
    font-size: 0.75rem;
  }

  .blog-card__date {
    font-size: 0.75rem;
    font-weight: 400;
    color: #6b7280;
  }

  .blog-card__title {
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.5;
    margin: 0 0 12px 0;
  }

  .blog-card__title a {
    color: #1a1a2e;
    text-decoration: underline;
    text-underline-offset: 3px;
    transition: color 0.3s ease;
  }

  .blog-card__title a:hover {
    color: #4948E1;
  }

  .blog-card__author {
    font-size: 0.9rem;
    font-weight: 400;
    color: #6b7280;
  }

  .blogs-listing__divider {
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Pagination */
  .blogs-listing__pagination .pagination {
    gap: 8px;
  }

  .blogs-listing__pagination .page-link {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 500;
    color: #1a1a2e;
    border: 1px solid #e5e7eb;
    background-color: transparent;
    transition: all 0.3s ease;
    padding: 0;
  }

  .blogs-listing__pagination .page-link:hover {
    border-color: #4948E1;
    color: #4948E1;
    background-color: transparent;
  }

  .blogs-listing__pagination .page-item.active .page-link {
    background-color: #4948E1;
    border-color: #4948E1;
    color: #ffffff;
  }

  .blogs-listing__pagination .page-link--next {
    border: none;
  }

  .blogs-listing__pagination .page-link--next i {
    font-size: 0.85rem;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .blogs-listing__nav {
      flex-wrap: nowrap;
      overflow-x: auto;
      justify-content: flex-start;
      padding-bottom: 8px;
      -webkit-overflow-scrolling: touch;
    }

    .blogs-listing__nav::-webkit-scrollbar {
      display: none;
    }
  }

  @media (max-width: 767px) {
    .blogs-listing__tab {
      padding: 10px 18px;
      font-size: 0.75rem;
    }

    .blog-card__title {
      font-size: 1rem;
    }

    .blog-card__image img {
      height: 180px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.blogs-listing__tab');

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        // Remove active class from all tabs
        tabs.forEach(function (t) {
          t.classList.remove('active');
        });
        // Add active class to clicked tab
        this.classList.add('active');

        // Filter functionality can be added here
        const filter = this.getAttribute('data-filter');
        console.log('Filter by:', filter);
      });
    });

    // Scroll tabs on arrow click
    const navArrow = document.querySelector('.blogs-listing__nav-arrow');
    const navContainer = document.querySelector('.blogs-listing__nav');

    if (navArrow && navContainer) {
      navArrow.addEventListener('click', function () {
        navContainer.scrollBy({ left: 200, behavior: 'smooth' });
      });
    }
  });
</script>