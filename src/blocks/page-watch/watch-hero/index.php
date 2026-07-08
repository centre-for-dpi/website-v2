<section class="watch-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- Label -->
        <span class="watch-hero__label text-uppercase">EXCLUSIVE WATCH</span>

        <!-- Title -->
        <h1 class="watch-hero__title">VOICES THAT <span>INSPIRE</span></h1>

        <!-- Description -->
        <p class="watch-hero__desc">
          Find the resources below designed to help you understand, build, and strengthen inclusive and competitive
          digital economies
        </p>
      </div>
    </div>
  </div>

  <!-- Decorative Pattern -->
  <div class="watch-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-9.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .watch-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 174px 80px 54px;
    position: relative;
    overflow: hidden;
  }

  .watch-hero__label {
    font-size: 0.8rem;
    font-weight: 500;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: 0.15em;
    display: block;
    margin-bottom: 24px;
  }

  .watch-hero__title {
    color: #0F0F0F;
    margin: 0 0 24px 0;

    font-weight: 500;
    font-size: 48px;
    line-height: 72px;
    letter-spacing: 8%;
  }

  .watch-hero__title span {
    font-weight: 500;
    font-size: 48px;
    line-height: 72px;
    letter-spacing: 8%;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    color: transparent;
    -webkit-background-clip: text;
  }

  .watch-hero__desc {
    font-size: 15px;
    font-weight: 400;
    line-height: 170%;
    letter-spacing: 2%;

    color: #5E6979;
    margin: 0 auto;
    max-width: 580px;
  }

  .watch-hero__pattern {
    position: absolute;
    left: 0;
    bottom: 0;
    height: auto;
    pointer-events: none;
  }

  .watch-hero__pattern img {
    width: 100%;
    height: auto;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .watch-hero {
      padding: 100px 0 60px;
    }

    .watch-hero__title {
      font-size: 2.75rem;
    }

    .watch-hero__pattern {
      width: 250px;
    }
  }

  @media (max-width: 767px) {
    .watch-hero {
      padding: 80px 0 50px;
    }

    .watch-hero__title {
      font-size: 2rem;
    }

    .watch-hero__desc {
      font-size: 0.95rem;
    }

    .watch-hero__pattern {
      display: none;
    }
  }
</style>