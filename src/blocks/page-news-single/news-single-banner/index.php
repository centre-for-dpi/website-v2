<section class="news-single-banner">
  <div class="news-single-banner__wrapper">
    <a href="javascript:history.back()" class="news-single-banner__back" aria-label="Go back">
      <i class="fa-solid fa-arrow-left"></i>
    </a>
    <img src="<?php echo Helper::getImagePath('temp/news-banner.svg'); ?>" alt="News banner" loading="lazy" />
  </div>
</section>

<style>
.news-single-banner {
  background-color: #ffffff;
}

.news-single-banner__wrapper {
  position: relative;
  width: 100%;
  overflow: hidden;
}

.news-single-banner__wrapper img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  display: block;
}

.news-single-banner__back {
  position: absolute;
  top: 24px;
  left: 24px;
  width: 44px;
  height: 44px;
  background-color: #ffffff;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.news-single-banner__back i {
  color: #6564DB;
  font-size: 1rem;
}

.news-single-banner__back:hover {
  background-color: #6564DB;
}

.news-single-banner__back:hover i {
  color: #ffffff;
}

/* Responsive */
@media (max-width: 767px) {
  .news-single-banner__wrapper img {
    height: 280px;
  }
  
  .news-single-banner__back {
    top: 16px;
    left: 16px;
    width: 40px;
    height: 40px;
  }
}
</style>
