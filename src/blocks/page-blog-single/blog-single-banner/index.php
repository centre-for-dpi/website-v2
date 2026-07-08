<?php
$banner_image = CustomPost::getImageUrl($post['meta_fields']['banner_image']);
?>

<section class="blog-single-banner">
  <div>
    <div class="blog-single-banner__image">
      <img src="<?php echo !empty($banner_image) ? $banner_image : Helper::getImagePath('temp/news-1.png'); ?>" alt="<?php echo esc_attr($post['title']); ?>" loading="lazy" />
    </div>
  </div>
</section>

<style>
.blog-single-banner {
  padding: 0 0 60px;
  background-color: #ffffff;
}

.blog-single-banner__image {
  overflow: hidden;
}

.blog-single-banner__image img {
  width: 100%;
  height: auto;
  display: block;
  object-fit: cover;
}

@media (min-width: 768px) {
  .blog-single-banner__image {
    max-height: 580px;
  }

  .blog-single-banner__image img {
    height: 580px;
    width: 100%;
    object-fit: cover;
    object-position: center;
  }
}

/* Responsive */
@media (max-width: 767px) {
  .blog-single-banner {
    padding: 0 0 40px;
  }
  
}
</style>
