<?php

declare(strict_types=1);

// Initialize for 'post' post type and 'category' taxonomy
$postHandler = new CustomPost('post', 'category');

// Get posts only in 'news' category
$posts = $postHandler->getPostsByCategory('news', ["thumbnail", "published_date", "author", "categories"]);

// Render only first 3 cards in the UI.
if (is_array($posts)) {
  $posts = array_slice($posts, 0, 3);
} elseif ($posts instanceof Traversable) {
  $posts = array_slice(iterator_to_array($posts, false), 0, 3);
}


?>

<section class="redlof-block latest-news">
  <div class="container">
    <!-- Title -->
    <h2 class="latest-news__title">LATEST NEWS AND ARTICLES</h2>

    <!-- News Cards -->
    <div class="row g-4 latest-news__cards">
      <?php foreach ($posts as $post) : ?>  
      <div class="col-md-6 col-lg-4">
        <article class="news-card">
          <div class="news-card__image">
            <a href="<?php echo $post['link']; ?>">
              <img src="<?php echo $post['thumbnail'] ? $post['thumbnail'] : Helper::getImagePath('temp/news-1.png'); ?>" alt="<?php echo $post['title']; ?>" loading="lazy" />
            </a>
          </div>
          <div class="news-card__content">
            <div class="news-card__meta">
              <span class="news-card__category"><?php echo $post['categories'][0]['title']; ?></span>
              <span class="news-card__separator">|</span>
              <span class="news-card__date"><?php echo $post['published_date']; ?></span>
            </div>
            <h3 class="news-card__heading">
              <a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a>
            </h3>
            <a href="<?php echo $post['link']; ?>" class="news-card__link">Read more</a>
          </div>
        </article>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
  .latest-news{
    background-color: #ffffff;
    position: relative;
    overflow: hidden;
    padding: 7.25rem 0 5.125rem;
  }

  .latest-news::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 5.625rem;
    right: 5.625rem;
    height: 1px;
    background: linear-gradient(90deg, #d6e1f1 0%, #6564db 50%, #d6e1f1 100%);
  }


  .latest-news__cards {
    margin-bottom: 0;
  }


  .latest-news__title {
    font-family: "Outfit", sans-serif;
    font-size: 1.25rem;
    font-weight: 500;
    letter-spacing: 0.1rem;
    color: #0f0f0f;
    margin-bottom: 2.625rem;
    text-transform: uppercase;
    line-height: 2.125rem;
  }

  /* News Card */
  .news-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    margin-bottom: 1.5rem;
  }

  .news-card__image {
    border-radius: 0.625rem;
    overflow: hidden;
    margin-bottom: 1.25rem;
  }

  .news-card__image img {
    width: 100%;
    height: 12.5rem;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
  }

  .news-card__image:hover img {
    transform: scale(1.03);
  }

  .news-card__content {
    display: flex;
    flex-direction: column;
    flex: 1;
  }

  .news-card__meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
  }

  .news-card__category,
  .news-card__separator,
  .news-card__date {
    font-family: "Outfit", sans-serif;
    font-size: 0.6875rem;
    font-weight: 500;
    letter-spacing: 0.055rem;
    color: #5e6979;
    text-transform: uppercase;
    line-height: 1.16875rem;
  }

  .news-card__heading {
    font-family: "Outfit", sans-serif;
    font-size: 1rem;
    font-weight: 500;
    line-height: 1.7rem;
    margin-bottom: 0.75rem;
    letter-spacing: 0.02rem;
  }

  .news-card__heading a {
    color: #101828;
    text-decoration: underline;
    text-decoration-color: #101828;
    text-underline-offset: 2px;
    transition: all 0.2s ease;
  }

  .news-card__heading a:hover {
    color: #4948e1;
    text-decoration-color: #4948e1;
  }

  .news-card__link {
    font-family: "Outfit", sans-serif;
    font-size: 0.875rem;
    font-weight: 400;
    color: #4948e1;
    text-decoration: underline;
    text-decoration-color: #4948e1;
    text-underline-offset: 2px;
    transition: color 0.2s ease;
    letter-spacing: 0.0175rem;
    line-height: 1.4875rem;
  }

  .news-card__link:hover {
    color: #3f3ecb;
  }

  /* Responsive */
  @media (max-width: 1199px) {
    .latest-news::after {
      left: 3.5rem;
      right: 3.5rem;
    }
  }

  @media (max-width: 991px) {
    .latest-news::after {
      left: 1.5rem;
      right: 1.5rem;
    }

    .news-card__image img {
      height: 12.5rem;
    }
  }

  @media (max-width: 767px) {
    .news-card__image img {
      height: 12.5rem;
    }
  }

  @media (max-width: 575px) {
    .latest-news .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .latest-news::after {
      left: 1.5rem;
      right: 1.5rem;
    }

    .latest-news__title {
      font-weight: 600;
    }

    .news-card__heading {
      font-size: 1.0625rem;
      letter-spacing: 0.02125rem;
    }

    .news-card__link {
      text-decoration: underline;
      text-decoration-color: #4948e1;
      text-underline-offset: 2px;
    }
  }
</style>