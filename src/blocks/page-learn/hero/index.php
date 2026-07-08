<section class="learn-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- Label: Figma 5837:13710 Secondary H4 -->
        <span class="learn-hero__label text-uppercase">Learn</span>
        <!-- Title: Figma H1 Secondary 48px / 72px line-height desktop; 32px / 48px mobile -->
        <h1 class="learn-hero__title">MIRRA'S JOURNEY</h1>
        <!-- Description: Figma B2 15px -->
        <p class="learn-hero__desc">
          Understanding DPI and it's use cases through Mirra's journey from birth to old age.
        </p>
      </div>
    </div>
  </div>

  <div class="learn-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-4.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  /*
   * Figma: Desktop 5837:13710 (Top-Bottom padding 88, Margins 90, H1 Secondary 48/72, Secondary H4 12px, B2 15px).
   * Mobile 5839:16023 (Top-Bottom padding 56, Margins 24, H1 Secondary 32/48). rem, 1.5rem L/R at 575px.
   */
  .learn-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 10.5rem 0 5.125rem; /* 88px – Figma desktop Top-Bottom padding */
    position: relative;
    overflow: hidden;
  }

  .learn-hero::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  .learn-hero .container {
    padding-left: 9.5rem;
    padding-right: 9.5rem;
  }

  .learn-hero__label {
    font-family: 'Outfit', sans-serif;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
    margin-bottom: 1.5rem; /* 24px */
    font-weight: 500;
    font-size: 0.75rem; /* 12px – Secondary H4 */
    line-height: 170%;
    letter-spacing: 0.075rem; /* 1.2px */
  }

  .learn-hero__title {
    font-family: 'Outfit', sans-serif;
    font-weight: 500;
    font-size: 3rem; /* 48px – H1 Secondary desktop */
    line-height: 1.5; /* 72px */
    letter-spacing: 0.08em;
    color: #0f0f0f;
    margin: 0 0 1.5rem 0;
  }

  .learn-hero__desc {
    font-family: 'Outfit', sans-serif;
    color: #5e6979;
    margin: 0 auto;
    max-width: 32.5rem; /* 520px */
    font-weight: 400;
    font-size: 0.9375rem; /* 15px – B2 */
    line-height: 170%;
    letter-spacing: 0.02em;
  }

  .learn-hero__pattern {
    position: absolute;
    right: 0;
    top: 17.5rem; /* 280px */
    transform: translateY(-50%);
    height: auto;
    pointer-events: none;
  }

  .learn-hero__pattern img {
    width: 100%;
    height: auto;
  }

  @media (max-width: 1400px) {
    .learn-hero .container {
      padding-left: 3.125rem;
      padding-right: 3.125rem;
    }
  }

  @media (max-width: 991px) {
    .learn-hero__title {
      font-size: 2.75rem;
    }
    .learn-hero__pattern {
      width: 12.5rem;
      right: -5rem;
    }
  }

  @media (max-width: 768px) {
    .learn-hero .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }

  /* Figma 5839:16023 – Top-Bottom padding 56, H1 32px/48px, Margins 24 */
  @media (max-width: 575px) {
    .learn-hero .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
    .learn-hero__title {
      font-size: 2rem; /* 32px */
      line-height: 1.5; /* 48px */
    }
    .learn-hero__pattern {
      display: none;
    }
  }
</style>