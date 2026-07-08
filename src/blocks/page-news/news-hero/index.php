<section class="news-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- Label -->
        <span class="news-hero__label text-uppercase">IIn the spotlight</span>

        <!-- Title -->
        <h1 class="news-hero__title">NEWS & UPDATES</h1>

        <!-- Description -->
        <p class="news-hero__desc">
          News and statements from our team as we advance digital public infrastructure globally.
        </p>
      </div>
    </div>
  </div>

  <!-- Decorative Pattern -->
  <div class="news-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-8.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .news-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 173px 0 66px;
    position: relative;
    overflow: hidden;
  }

  .news-hero::after {
    content: "";

    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;

    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    height: 1px;
  }

  .news-hero__label {
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;

    display: block;
    margin-bottom: 24px;

    font-weight: 500;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 1.2px;
  }

  .news-hero__title {
    color: #0F0F0F;
    margin: 0 0 24px 0;

    font-weight: 500;
    font-size: 48px;
    line-height: 72px;
    letter-spacing: 8%;
  }

  .news-hero__desc {
    color: #5E6979;
    margin: 0 auto;
    max-width: 520px;

    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 2%;
  }

  .news-hero__pattern {
    position: absolute;
    left: 0;
    bottom: 0;
    width: 400px;
    height: auto;
    opacity: 0.8;
    pointer-events: none;
  }

  .news-hero__pattern img {
    width: 100%;
    height: auto;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .news-hero {
      padding: 100px 0 60px;
    }

    .news-hero__title {
      font-size: 2.75rem;
    }

    .news-hero__pattern {
      width: 200px;
    }
  }

  @media (max-width: 767px) {
    .news-hero {
      padding: 80px 0 50px;
    }

    .news-hero__title {
      font-size: 2rem;
    }

    .news-hero__desc {
      font-size: 0.95rem;
    }

    .news-hero__pattern {
      display: none;
    }
  }
</style>