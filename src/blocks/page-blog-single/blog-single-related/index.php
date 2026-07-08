<?php if (!empty($related_posts) && is_array($related_posts)) : ?>
<section class="more-blogs py-5">
  <div class="container">
    <!-- Section Title -->
    <h2 class="more-blogs__title text-center text-uppercase">Read more</h2>

    <!-- Blog Cards Grid -->
    <div class="row g-4 mt-4">
      <!-- Blog Card 1 -->
        <?php
        // Get related posts (excluding current post)
        foreach ($related_posts as $related_post) :
            $post_link = $related_post['link'];
            $thumbnail = $related_post['thumbnail'] ?? '';
            $category_title = $related_post['categories'][0]['title'] ?? '';
        ?>
        <div class="col-md-6 col-lg-4">
          <article class="more-blog-card">
            <div class="more-blog-card__image">
              <img src="<?php echo !empty($thumbnail) ? esc_url($thumbnail) : Helper::getImagePath('temp/news-1.png'); ?>" alt="<?php echo esc_attr($related_post['title']); ?>" loading="lazy" />
            </div>
            <div class="more-blog-card__meta">
              <span class="more-blog-card__category text-uppercase"><?php echo esc_html($category_title); ?></span>
              <span class="more-blog-card__separator">|</span>
              <span class="more-blog-card__date text-uppercase"><?php echo $related_post['published_date']; ?></span>
            </div>
            <h3 class="more-blog-card__heading">
              <a href="<?php echo esc_url($post_link); ?>"><?php echo esc_html($related_post['title']); ?></a>
            </h3>
            <span class="more-blog-card__author"><?php echo isset($related_post['author']['first_name']) ? $related_post['author']['first_name'] . ' ' . $related_post['author']['last_name'] : ''; ?></span>
          </article>
        </div>
        <?php
        endforeach;
        ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<style>
.more-blogs {
  background-color: #ffffff;
}

.more-blogs__title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1a1a2e;
  letter-spacing: 0.1em;
  margin: 0;
}

/* Blog Card */
.more-blog-card {
  display: flex;
  flex-direction: column;
}

.more-blog-card__image {
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
}

.more-blog-card__image img {
  width: 100%;
  height: 220px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.more-blog-card:hover .more-blog-card__image img {
  transform: scale(1.05);
}

.more-blog-card__meta {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
}

.more-blog-card__category {
  font-size: 0.75rem;
  font-weight: 500;
  color: #6564DB;
  letter-spacing: 0.05em;
}

.more-blog-card__separator {
  color: #9ca3af;
  font-size: 0.75rem;
}

.more-blog-card__date {
  font-size: 0.75rem;
  font-weight: 400;
  color: #6b7280;
}

.more-blog-card__heading {
  font-size: 1.1rem;
  font-weight: 500;
  line-height: 1.5;
  margin: 0 0 12px 0;
}

.more-blog-card__heading a {
  color: #1a1a2e;
  text-decoration: underline;
  text-underline-offset: 3px;
  transition: color 0.3s ease;
}

.more-blog-card__heading a:hover {
  color: #4948E1;
}

.more-blog-card__author {
  font-size: 0.9rem;
  font-weight: 400;
  color: #6b7280;
}

/* Responsive */
@media (max-width: 767px) {
  .more-blogs__title {
    font-size: 1.25rem;
  }
  
  .more-blog-card__heading {
    font-size: 1rem;
  }
  
  .more-blog-card__image img {
    height: 180px;
  }
}
</style>
