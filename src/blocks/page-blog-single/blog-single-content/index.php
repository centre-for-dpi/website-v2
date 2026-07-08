<section class="blog-single-content py-5">
  <div class="container">
    <div class="row">
      <!-- Author Sidebar -->
      <div class="col-lg-3">
        <div class="blog-author">
          <div class="blog-author__avatar">
            <img src="<?php echo $post['author']['avatar']; ?>" alt="<?php echo $post['author']['display_name']; ?>" loading="lazy" />
          </div>
          <h4 class="blog-author__name"><?php echo $post['author']['first_name'] . ' ' . $post['author']['last_name']; ?></h4>
          <span class="blog-author__meta text-uppercase"><?php echo $post['published_date']; ?> | <?php echo $post['published_time']; ?></span>
        </div>
      </div>

      <!-- Content Area -->
      <div class="col-lg-9">
        <div class="blog-content">
          <p>
            <?php echo $post['content']; ?>
          </p>

          <!-- Share Section -->
          <?php
          $current_post_id = get_queried_object_id();
          $permalink = $current_post_id ? get_permalink($current_post_id) : '';
          if (!$permalink && isset($_SERVER['REQUEST_URI'])) {
            $permalink = home_url(wp_unslash($_SERVER['REQUEST_URI']));
          }
          $current_url = rawurlencode((string) $permalink);
          $current_title = rawurlencode((string) get_the_title($current_post_id ?: null));
          ?>
          <div class="blog-share">
            <span class="blog-share__label text-uppercase">Share</span>
            <span class="blog-share__separator">|</span>
            <div class="blog-share__icons">
              <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $current_url; ?>&title=<?php echo $current_title; ?>" target="_blank" rel="noreferrer" aria-label="Share on LinkedIn">
                <i class="fa-brands fa-linkedin-in"></i>
              </a>
              <a href="https://twitter.com/intent/tweet?url=<?php echo $current_url; ?>&text=<?php echo $current_title; ?>" target="_blank" rel="noreferrer" aria-label="Share on X">
                <i class="fa-brands fa-x-twitter"></i>
              </a>
              <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $current_url; ?>" target="_blank" rel="noreferrer" aria-label="Share on Facebook">
                <i class="fa-brands fa-facebook-f"></i>
              </a>
              <a href="mailto:?subject=<?php echo $current_title; ?>&body=<?php echo $current_url; ?>" target="_blank" rel="noreferrer" aria-label="Share via Email">
                <i class="fa-solid fa-envelope"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.blog-single-content {
  background-color: #ffffff;
}

/* Author Sidebar */
.blog-author {
  position: sticky;
  top: 100px;
}

.blog-author__avatar {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  overflow: hidden;
  margin-bottom: 16px;
}

.blog-author__avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.blog-author__name {
  font-size: 1.1rem;
  font-weight: 600;
  color: #1a1a2e;
  margin: 0 0 8px 0;
}

.blog-author__role {
  font-size: 0.7rem;
  font-weight: 600;
  color: #6b7280;
  letter-spacing: 0.1em;
  display: block;
  margin-bottom: 4px;
}

.blog-author__location {
  font-size: 0.85rem;
  font-weight: 400;
  color: #6564DB;
  display: block;
  margin-bottom: 16px;
}

.blog-author__meta {
  font-size: 0.7rem;
  font-weight: 400;
  color: #9ca3af;
  letter-spacing: 0.05em;
  display: block;
}

/* Blog Content */
.blog-content {
  max-width: 680px;
}

.blog-content p {
  font-size: 1rem;
  font-weight: 400;
  color: #4b5563;
  line-height: 1.8;
  margin-bottom: 24px;
}

.blog-content p strong {
  color: #1a1a2e;
  font-weight: 600;
}

.blog-content__figure {
  margin: 40px 0;
}

.blog-content__figure img {
  width: 100%;
  height: auto;
  border-radius: 8px;
  margin-bottom: 12px;
}

.blog-content__figure figcaption {
  font-size: 0.8rem;
  font-weight: 400;
  color: #6b7280;
  line-height: 1.6;
}

.blog-content__list {
  list-style: disc;
  padding-left: 20px;
  margin-bottom: 24px;
}

.blog-content__list li {
  font-size: 1rem;
  font-weight: 400;
  color: #4b5563;
  line-height: 1.8;
  margin-bottom: 16px;
}

.blog-content__list li strong {
  color: #1a1a2e;
  font-weight: 600;
}

.blog-single-content__divider {
  height: 1px;
  background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
}

/* Share Section */
.blog-share {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-top: 48px;
  padding-top: 32px;
  border-top: 1px solid #e5e7eb;
}

.blog-share__label {
  font-size: 0.75rem;
  font-weight: 500;
  color: #6b7280;
  letter-spacing: 0.1em;
}

.blog-share__separator {
  color: #d1d5db;
}

.blog-share__icons {
  display: flex;
  align-items: center;
  gap: 16px;
}

.blog-share__icons a {
  color: #4948E1;
  font-size: 1rem;
  transition: color 0.3s ease;
}

.blog-share__icons a:hover {
  color: #6564DB;
}

/* Responsive */
@media (max-width: 991px) {
  .blog-author {
    position: relative;
    top: 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 16px;
    margin-bottom: 40px;
    padding-bottom: 24px;
    border-bottom: 1px solid #e5e7eb;
  }
  
  .blog-author__avatar {
    margin-bottom: 0;
  }
  
  .blog-author__name {
    margin-bottom: 0;
  }
  
  .blog-author__role,
  .blog-author__location {
    margin-bottom: 0;
  }
  
  .blog-author__meta {
    width: 100%;
  }
}

@media (max-width: 767px) {
  .blog-content p {
    font-size: 0.95rem;
  }
  
  .blog-content__list li {
    font-size: 0.95rem;
  }
}
</style>
