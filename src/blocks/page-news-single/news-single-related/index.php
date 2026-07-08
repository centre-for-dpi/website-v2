<section class="more-news py-5">
  <div class="container">
    <!-- Section Title -->
    <h2 class="more-news__title text-center text-uppercase">More News</h2>

    <!-- News Cards Grid -->
    <div class="row g-4 mt-4">
      <!-- News Card 1 -->
      <div class="col-md-6 col-lg-4">
        <article class="more-news-card">
          <div class="more-news-card__image">
            <img src="<?php echo Helper::getImagePath('temp/news-1.png'); ?>" alt="Building" loading="lazy" />
          </div>
          <div class="more-news-card__meta">
            <span class="more-news-card__category text-uppercase">INDUSTRY EVENTS</span>
            <span class="more-news-card__separator">|</span>
            <span class="more-news-card__date text-uppercase">12 JAN 2024</span>
          </div>
          <h3 class="more-news-card__heading">CDPI to Host Workshop on Building Resilient Digital Infrastructure</h3>
          <a href="#" class="more-news-card__link">Read More</a>
        </article>
      </div>

      <!-- News Card 2 -->
      <div class="col-md-6 col-lg-4">
        <article class="more-news-card">
          <div class="more-news-card__image">
            <img src="<?php echo Helper::getImagePath('temp/news-1.png'); ?>" alt="Handshake" loading="lazy" />
          </div>
          <div class="more-news-card__meta">
            <span class="more-news-card__category text-uppercase">PARTNER NEWS</span>
            <span class="more-news-card__separator">|</span>
            <span class="more-news-card__date text-uppercase">03 JAN 2024</span>
          </div>
          <h3 class="more-news-card__heading">Acme Corp Deploys CDPI Solutions to Improve Data Security and Compliance</h3>
          <a href="#" class="more-news-card__link">Read More</a>
        </article>
      </div>

      <!-- News Card 3 -->
      <div class="col-md-6 col-lg-4">
        <article class="more-news-card">
          <div class="more-news-card__image">
            <img src="<?php echo Helper::getImagePath('temp/news-1.png'); ?>" alt="Architecture" loading="lazy" />
          </div>
          <div class="more-news-card__meta">
            <span class="more-news-card__category text-uppercase">CASE STUDIES</span>
            <span class="more-news-card__separator">|</span>
            <span class="more-news-card__date text-uppercase">27 DEC 2023</span>
          </div>
          <h3 class="more-news-card__heading">How the City of Willow Creek Transformed Citizen Services with CDPI</h3>
          <a href="#" class="more-news-card__link">Read More</a>
        </article>
      </div>
    </div>
  </div>
</section>

<style>
.more-news {
  background-color: #ffffff;
}

.more-news__title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1a1a2e;
  letter-spacing: 0.1em;
  margin: 0;
}

/* News Card */
.more-news-card {
  display: flex;
  flex-direction: column;
}

.more-news-card__image {
  border-radius: 12px;
  overflow: hidden;
  margin-bottom: 16px;
}

.more-news-card__image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.more-news-card:hover .more-news-card__image img {
  transform: scale(1.05);
}

.more-news-card__meta {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
}

.more-news-card__category {
  font-size: 0.7rem;
  font-weight: 500;
  color: #6b7280;
  letter-spacing: 0.05em;
}

.more-news-card__separator {
  color: #9ca3af;
  font-size: 0.7rem;
}

.more-news-card__date {
  font-size: 0.7rem;
  font-weight: 400;
  color: #6b7280;
}

.more-news-card__heading {
  font-size: 1rem;
  font-weight: 500;
  color: #1a1a2e;
  line-height: 1.5;
  margin: 0 0 12px 0;
}

.more-news-card__link {
  font-size: 0.9rem;
  font-weight: 400;
  color: #6564DB;
  text-decoration: underline;
  text-underline-offset: 3px;
  transition: color 0.3s ease;
}

.more-news-card__link:hover {
  color: #4948E1;
}

/* Responsive */
@media (max-width: 767px) {
  .more-news__title {
    font-size: 1.25rem;
  }
  
  .more-news-card__heading {
    font-size: 0.95rem;
  }
  
  .more-news-card__image img {
    height: 180px;
  }
}
</style>
