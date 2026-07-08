<section class="read-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 text-center">
        <!-- Label -->
        <span class="read-hero__label text-uppercase">Resources</span>

        <!-- Title -->
        <h1 class="read-hero__title">Resource Library</h1>

        <!-- Description -->
        <p class="read-hero__desc">
        Reusable tools to  understand DPI and build your own.
        </p>
      </div>
    </div>
  </div>

  <!-- Decorative Pattern -->
  <div class="read-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-5.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .read-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 198px 0 96px;
    position: relative;
    overflow: hidden;
  }

  .read-hero__label {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 1.2px;
    text-align: center;
    text-transform: uppercase;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
  }

  .read-hero__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 48px;
    line-height: 72px;
    letter-spacing: 0.08em;
    text-align: center;
    text-transform: uppercase;
    color: #0F0F0F;
    margin: 24px 0 24px 0;
  }

  .read-hero__desc {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    text-align: center;
    color: #5E6979;
    margin: 0 auto;
    max-width: 376px;
  }

  .read-hero__pattern {
    position: absolute;
    left: 0px;
    bottom: 0px;
    width: 400px;
    height: auto;
    opacity: 0.8;
    pointer-events: none;
    --read-hero-pattern-translate-x: 0px;
    --read-hero-pattern-translate-y: 0px;
    --read-hero-pattern-scroll-translate-x: 0px;
    --read-hero-pattern-scroll-translate-y: 0px;
    --read-hero-pattern-scale: 1;
    --read-hero-pattern-opacity: 0.62;
  }

  .read-hero__pattern img {
    width: 100%;
    height: auto;
    transform-origin: center center;
    transform: translate(
      calc(var(--read-hero-pattern-translate-x) + var(--read-hero-pattern-scroll-translate-x)),
      calc(var(--read-hero-pattern-translate-y) + var(--read-hero-pattern-scroll-translate-y))
    ) scale(var(--read-hero-pattern-scale));
    opacity: var(--read-hero-pattern-opacity);
    transition: opacity 0.12s linear;
    will-change: transform, opacity;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .read-hero {
      padding: 100px 0 60px;
    }

    .read-hero__title {
      font-size: 2.75rem;
    }

    .read-hero__pattern {
      width: 140px;
      left: -20px;
    }
  }

  @media (max-width: 767px) {
    .read-hero {
      padding: 120px 0 80px;
    }

    .read-hero .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .read-hero__title {
      font-size: 2rem;
      margin-top: 48px;
      margin-bottom: 32px;
      line-height: 1.25;
    }

    .read-hero__desc {
      font-size: 0.95rem;
      line-height: 1.6;
      margin-top: 0;
    }

    .read-hero__pattern {
      display: none;
    }
  }
</style>

<script>
  (function () {
    var pattern = document.querySelector('.read-hero__pattern');
    if (!pattern) return;

    var ticking = false;
    function updatePatternMotion() {
      var y = window.scrollY || window.pageYOffset || 0;
      pattern.style.setProperty('--read-hero-pattern-scroll-translate-x', (-Math.min(y * 0.35, 200)) + 'px');
      pattern.style.setProperty('--read-hero-pattern-scroll-translate-y', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--read-hero-pattern-scale', (1 + Math.min(y * 0.00025, 0.12)).toFixed(3));
      pattern.style.setProperty('--read-hero-pattern-opacity', (0.62 + Math.min(y * 0.0005, 0.38)).toFixed(3));
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

<style>
  .resources-divider {
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    border: none;
    opacity: 1;
    margin: 0;
  }

  @media (max-width: 767px) {
    .resources-divider-wrap {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }
</style>