<section class="casestudies-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8 text-center">
        <!-- Label -->
        <span class="casestudies-hero__label text-uppercase">Resources</span>

        <!-- Title -->
        <h1 class="casestudies-hero__title">CASE STUDIES</h1>

        <!-- Description -->
        <p class="casestudies-hero__desc">
        Explore DPI and its applications highlighted in various case studies.
        </p>
      </div>
    </div>
  </div>

  <!-- Decorative Pattern -->
  <div class="casestudies-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-7.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .casestudies-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 180px 0 78px;
    position: relative;
    overflow: hidden;
  }

  .casestudies-hero::after {
    content: "";

    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;

    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  .casestudies-hero__label {
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

  .casestudies-hero__title {
    color: #0F0F0F;
    margin: 0 0 24px 0;

    font-weight: 500;
    font-size: 48px;
    line-height: 72px;
    letter-spacing: 8%;
  }

  .casestudies-hero__desc {
    color: #5E6979;
    margin: 0 auto;
    max-width: 376px;

    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 2%;
  }

  .casestudies-hero__pattern {
    position: absolute;
    left: 0;
    bottom: 0;
    pointer-events: none;
    --casestudies-hero-pattern-scroll-translate-x: 0px;
    --casestudies-hero-pattern-scroll-translate-y: 0px;
    --casestudies-hero-pattern-scale: 1;
    --casestudies-hero-pattern-opacity: 1;
    transform: translate3d(
      var(--casestudies-hero-pattern-scroll-translate-x),
      var(--casestudies-hero-pattern-scroll-translate-y),
      0
    ) scale(var(--casestudies-hero-pattern-scale));
    opacity: var(--casestudies-hero-pattern-opacity);
    transform-origin: left bottom;
    will-change: transform, opacity;
  }

  .casestudies-hero__pattern img {
    width: 100%;
    height: auto;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .casestudies-hero {
      padding: 100px 0 60px;
    }

    .casestudies-hero__title {
      font-size: 2.75rem;
    }

    .casestudies-hero__pattern {
      width: 200px;
    }
  }

  @media (max-width: 767px) {
    .casestudies-hero {
      padding: 80px 0 50px;
    }

    .casestudies-hero__title {
      font-size: 2rem;
    }

    .casestudies-hero__desc {
      font-size: 0.95rem;
    }

    .casestudies-hero__pattern {
      display: none;
    }
  }
</style>

<script>
  (function () {
    var pattern = document.querySelector('.casestudies-hero__pattern');
    if (!pattern) return;

    var ticking = false;
    function updatePatternMotion() {
      var y = window.scrollY || window.pageYOffset || 0;
      pattern.style.setProperty('--casestudies-hero-pattern-scroll-translate-x', Math.max(-y * 0.35, -200) + 'px');
      pattern.style.setProperty('--casestudies-hero-pattern-scroll-translate-y', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--casestudies-hero-pattern-scale', (1 + Math.min(y * 0.00025, 0.12)).toFixed(3));
      pattern.style.setProperty('--casestudies-hero-pattern-opacity', (0.62 + Math.min(y * 0.0005, 0.38)).toFixed(3));
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
