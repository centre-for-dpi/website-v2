<section class="blogs-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- Label -->
        <span class="blogs-hero__label text-uppercase">Thought Leadership</span>

        <!-- Title -->
        <h1 class="blogs-hero__title">THE CDPI BLOG</h1>

        <!-- Description -->
        <p class="blogs-hero__desc">
          Stories, lessons, and reflections from all of our work in digital transformation worldwide.
        </p>
      </div>
    </div>
  </div>

  <!-- Decorative Pattern -->
  <div class="blogs-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-blogs.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .blogs-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);

    padding: 178px 0 54px;
    position: relative;
    overflow: hidden;
  }

  .blogs-hero::after {
    content: "";
    position: absolute;
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);

    bottom: 0;
    left: 0;
    right: 0;
  }

  .blogs-hero__label {
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

  .blogs-hero__title {
    color: #0F0F0F;
    margin: 0 0 24px 0;

    font-weight: 500;
    font-size: 48px;
    line-height: 72px;
    letter-spacing: 8%;
  }

  .blogs-hero__desc {
    color: #5E6979;
    margin: 0 auto;
    max-width: 520px;

    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 2%;
  }

  .blogs-hero__pattern {
    position: absolute;
    left: 0px;
    bottom: 0px;
    pointer-events: none;
  }

  .blogs-hero__pattern img {
    width: 100%;
    height: auto;
  }

  /* Responsive */
  @media (max-width: 1300px) {
    .blogs-hero__pattern {
      width: 320px;
    }
  }

  @media (max-width: 991px) {
    .blogs-hero {
      padding: 150px 0 60px;
    }

    .blogs-hero__title {
      font-size: 2.75rem;
    }

    .blogs-hero__pattern {
      width: 220px;
    }

  }

  @media (max-width: 767px) {
    .blogs-hero {
      padding: 150px 0 50px;
    }

    .blogs-hero__title {
      font-size: 2rem;
    }

    .blogs-hero__desc {
      font-size: 0.95rem;
    }

    .blogs-hero__pattern {
      display: none;
    }
  }
</style>