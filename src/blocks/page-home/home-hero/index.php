<section class="redlof-block home-hero">
  <div class="container">
    <div class="row">
      <div class="col">
        <!-- Tagline -->
        <!-- <a class="home-hero__tagline mb-2" href="/our-work">
          <span class="home-hero__tagline-icon">
            <i class="fa-solid fa-globe"></i>
          </span>
          <span class="home-hero__tagline-text text-sm-uppercase">Accelerating DPI across Africa, Asia & Latin America</span>
          <i class="fa-solid fa-chevron-right home-hero__tagline-arrow"></i>
        </a> -->

        <!-- Main Heading -->
        <h1 class="home-hero__title mb-4">
          <span class="home-hero__title-line1">Designing for impact.</span>
          <span class="home-hero__title-line2">Building for scale.</span>
        </h1>

        <!-- Divider -->
        <div class="home-hero__divider mb-md-5 mb-4"></div>

        <!-- CTAs and Description -->
        <div class="d-flex flex-wrap align-items-center gap-2 justify-content-start w-100">
          <div class="home-hero__ctas d-flex gap-3">
            <a href="/our-work" class="btn home-hero__btn home-hero__btn--solid">
              Explore Our Work <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
            <a href="/about" class="btn home-hero__btn home-hero__btn--outline">
              About Us <i class="fa-solid fa-arrow-right ms-2"></i>
            </a>
          </div>
          <p class="home-hero__desc mb-0">
          Catalysing countries’ use of <span class="home-hero__desc-semi-bold">Digital Public Infrastructure</span> to drive inclusive economic growth.<br />
           <strong>Pro Bono. Tech Architects. Impartial. </strong>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Decorative Arc Pattern -->
  <div class="home-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-1.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .home-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 34vh 0 10vh;
    min-height: 70vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
  }

  /* Tagline */
  .home-hero__tagline {
    display: inline-block;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    background-clip: text;
    color: transparent;
    font-weight: 600;
    font-size: 0.75rem;
    line-height: 170%;
    letter-spacing: 0.96px;
    text-decoration: none;
    cursor: pointer;
  }
  
  .text-sm-uppercase {
    text-transform: uppercase;
  }


  .home-hero__tagline-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .home-hero__tagline-arrow {
    font-size: 0.65rem;
  }

  /* Main Title */
  .home-hero__title {
    font-size: 5.125rem;
    line-height: 5.9375rem;
    margin: 0;
    font-weight: 300;
    letter-spacing: -3px;
  }

  .home-hero__title-line1,
  .home-hero__title-line2 {
    display: block;

    font-weight: 300;
    font-size: 5.125rem;
    line-height: 5.9375rem;
    letter-spacing: -3.28px;
  }

  .home-hero__title-line1 {
    color: #101828;
  }

  .home-hero__title-line2 {
    background: linear-gradient(90deg, #9810FA 0%, #155DFC 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    display: inline-block; /* Ensure background-clip works reliably */
    width: 100%;
  }

  /* Divider */
  .home-hero__divider {
    margin-top: 2.5rem;
    width: 100%;
    max-width: 986px;
    height: 1px;
    background: linear-gradient(90deg, rgba(101, 100, 219, 0.66) 0%, rgba(214, 225, 241, 0.66) 100%);
  }

  /* CTA Buttons */
  .home-hero__btn {
    white-space: nowrap;
    height: 62px;
    padding: 18px 24px;
  }

  .home-hero__btn--solid {
    font-weight: 500;
    font-size: 1rem;
    line-height: 160%;
    letter-spacing: 1%;

    border-radius: 8px;
    max-width: 220px;

    background-color: #4B4AEA;
    border: 1px solid #4948E1;
    color: #ffffff;
  }

  .home-hero__btn--solid:hover {
    background-color: #4338ca;
    border-color: #4338ca;
    color: #ffffff;
  }

  .home-hero__btn--outline {
    max-width: 169px;
    height: 62px;
    padding: 18px 24px;

    border-radius: 8px;

    font-weight: 400;
    font-size: 1rem;
    line-height: 160%;
    letter-spacing: 0%;

    background-color: #ffffff;
    border: 1px solid #CEDBEE;
    color: #5E6979;
  }

  .home-hero__btn--outline:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
    color: #1a1a2e;
  }

  .home-hero__btn--outline .fa-play {
    font-size: 0.7rem;
  }

  /* Description */
  .home-hero__ctas {
    order: 2;
  }

  .home-hero__desc {
    max-width: 478px;
    font-weight: 400;
    font-size: 0.9375rem;
    line-height: 150%;
    letter-spacing: 0.03em;
    color: #5E6979;
    padding-left: 0;
    order: 1;
  }

  .home-hero__desc strong {
    font-family: Outfit, sans-serif;
    font-weight: 600;
    font-style: normal;
    font-size: 15px;
    line-height: 150%;
    letter-spacing: 0.03em;
    display: inline-block;
    margin-top: 8px;
    color: #155DFC; /* fallback for low-end browsers */
    background: none;
  }

  @supports ((-webkit-background-clip: text) or (background-clip: text)) {
    .home-hero__desc strong {
      background: linear-gradient(90deg, #9810FA 0%, #155DFC 100%);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      color: transparent;
    }
  }

  .home-hero__desc-semi-bold {
    font-weight: 600;
    color: #5E6979;
  }

  /* Decorative Pattern */
  .home-hero__pattern {
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 50%;
    pointer-events: none;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    --home-hero-pattern-translate-x: 56%;
    --home-hero-pattern-scroll-translate-x: 0px;
    --home-hero-pattern-translate-y: 8%;
    --home-hero-pattern-scroll-translate-y: 0px;
    --home-hero-pattern-scale: 1;
    --home-hero-pattern-opacity: 0.62;
  }

  .home-hero__pattern img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: right center;
    transform-origin: center center;
    transform: translate(
        calc(var(--home-hero-pattern-translate-x) + var(--home-hero-pattern-scroll-translate-x)),
        calc(var(--home-hero-pattern-translate-y) + var(--home-hero-pattern-scroll-translate-y))
      )
      scale(var(--home-hero-pattern-scale));
    opacity: var(--home-hero-pattern-opacity);
    transition: opacity 0.12s linear;
    will-change: transform, opacity;
  }

  .home-hero .container {
    position: relative;
    z-index: 2;
  }

  /* Responsive */
  @media (max-width: 1199px) {
    .home-hero__pattern {
      width: 42%;
      opacity: 0.75;
    }
  }

  @media (max-width: 991px) {
    .home-hero {
      min-height: auto;
      padding: 16vh 0 10vh;
    }

    .home-hero__title,
    .home-hero__title-line1,
    .home-hero__title-line2 {
      font-size: 3.75rem;
      line-height: 4.25rem;
    }

    .home-hero__desc {
      padding-left: 0;
      margin-top: 24px;
      max-width: 100%;
      font-size: 1rem;
    }

    .home-hero__pattern {
      width: 50%;
    }
  }

  @media (max-width: 767px) {
    .home-hero {
      padding: 14vh 6vw 9vh;
    }

    .home-hero__title,
    .home-hero__title-line1,
    .home-hero__title-line2 {
      font-size: 3rem;
      line-height: 3.5rem;
    }

    .home-hero__tagline {
      font-size: 0.6875rem;
    }

    .home-hero__desc {
      font-size: 0.9375rem;
    }

    .home-hero__pattern {
      display: none;
    }
  }

  @media (max-width: 575px) {
    .home-hero {
      padding: 8.75rem 0 5.25rem;
    }

    .home-hero .container {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }

    .home-hero__pattern {
      display: flex;
      width: 13.375rem;
      right: -6.5rem;
      top: 6.5rem;
      bottom: auto;
      --home-hero-pattern-opacity: 0.85;
    }

    .home-hero .d-flex.flex-wrap {
      flex-direction: column-reverse;
      align-items: stretch;
      justify-content: flex-start;
      gap: 0;
    }

    .home-hero__ctas {
      flex-direction: column;
      width: 100%;
      gap: 1.125rem;
      margin-top: 2rem; /* space before the first button */
    }

    .home-hero__ctas,
    .home-hero__desc {
      order: 0;
    }

    .home-hero__btn {
      display: flex;
      width: 100%;
      max-width: 100%;
      justify-content: center;
      height: 3.875rem;
      border-radius: 0.4375rem;
      white-space: normal;
      align-items: center;
    }

    .home-hero__btn--solid {
      gap: 1.5rem;
    }

    .home-hero__btn--outline {
      gap: 0.875rem;
    }

    .home-hero__tagline {
      width: 20rem;
      max-width: 100%;
      padding: 0.375rem 0;
      font-size: 0.8125rem;
      line-height: 1.5;
      letter-spacing: -0.3px;
      font-weight: 400;
      text-transform: none;
    }

    .text-sm-uppercase {
      text-transform: capitalize;
    }

    .home-hero__tagline-icon {
      font-size: 0.8125rem;
    }

    .home-hero__tagline-arrow {
      font-size: 0.6875rem;
    }

    .home-hero__title,
    .home-hero__title-line1,
    .home-hero__title-line2 {
      font-size: 3.375rem;
      line-height: 1.05;
      letter-spacing: -0.0675rem;
    }

    .home-hero__title-line1 {
      font-weight: 400;
      margin-bottom: 0.5rem;
    }

    .home-hero__title-line2 {
      font-weight: 400;
      line-height: 4.095rem;
      letter-spacing: -0.1025rem;
    }

    .home-hero__title {
      margin-bottom: 0.5rem;
    }

    .home-hero__title.mb-4 {
      margin-bottom: 0.5rem;
    }

    .home-hero__divider {
      margin-top: 1.75rem;
      max-width: 22.375rem;
    }

    .home-hero__divider.mb-5 {
      margin-bottom: 1.75rem;
    }

    .home-hero__desc {
      font-size: 0.875rem;
      line-height: 1.7;
      letter-spacing: 0.0175rem;
      padding-left: 0;
      max-width: 21.375rem;
      margin-top: 0;
    }

    .home-hero__btn--solid .fa-arrow-right {
      font-size: 1.125rem;
    }

    .home-hero__btn--outline .fa-play {
      font-size: 1rem;
    }
  }
</style>

<script>
  (function () {
    var pattern = document.querySelector('.home-hero__pattern');
    if (!pattern) return;

    var ticking = false;
    function updatePatternMotion() {
      var y = window.scrollY || window.pageYOffset || 0;
      pattern.style.setProperty('--home-hero-pattern-scroll-translate-x', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--home-hero-pattern-scroll-translate-y', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--home-hero-pattern-scale', (1 + Math.min(y * 0.00025, 0.12)).toFixed(3));
      pattern.style.setProperty('--home-hero-pattern-opacity', (0.62 + Math.min(y * 0.0005, 0.38)).toFixed(3));
      ticking = false;
    }

    window.addEventListener('scroll', function () {
      if (ticking) return;
      ticking = true;
      window.requestAnimationFrame(updatePatternMotion);
    }, { passive: true });

    updatePatternMotion();
  })();
</script>