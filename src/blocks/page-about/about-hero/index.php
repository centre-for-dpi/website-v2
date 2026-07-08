<section class="redlof-block about-hero">
  <!-- Decorative Pattern Left -->
  <div class="about-hero__pattern about-hero__pattern--left">
    <img src="<?php echo Helper::getImagePath('patterns/about-hero-pattern.png'); ?>" alt="" loading="lazy" />
  </div>



  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10 col-xl-10 text-center">
        <!-- Label -->
        <span class="about-hero__label">CDPI HELPS</span>

        <!-- Main Heading -->
        <h1 class="about-hero__title">
          Design, Deploy and Scale <br> DPI that is <span class="about-hero__highlight">open, inclusive </span> and <span class="about-hero__highlight">country-led</span>
        </h1>

      </div>
    </div>
  </div>
</section>

<style>
  .about-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 11.25rem 5.625rem 8.75rem; /* 140px bottom padding */
    position: relative;
    overflow: hidden;
    min-height: 33.25rem;
  }

  .about-hero .container {
    max-width: 68.75rem;
    padding-left: 0;
    padding-right: 0;
  }

  /* Decorative Patterns */
  .about-hero__pattern {
    position: absolute;
    pointer-events: none;
    z-index: 1;
    --about-hero-pattern-translate-x: 0px;
    --about-hero-pattern-translate-y: 0px;
    --about-hero-pattern-scroll-translate-x: 0px;
    --about-hero-pattern-scroll-translate-y: 0px;
    --about-hero-pattern-scale: 1;
    --about-hero-pattern-opacity: 0.62;
  }

  .about-hero__pattern img {
    width: 100%;
    height: auto;
    transform-origin: center center;
    transform: translate(
      calc(var(--about-hero-pattern-translate-x) + var(--about-hero-pattern-scroll-translate-x)),
      calc(var(--about-hero-pattern-translate-y) + var(--about-hero-pattern-scroll-translate-y))
    ) scale(var(--about-hero-pattern-scale));
    opacity: var(--about-hero-pattern-opacity);
    transition: opacity 0.12s linear;
    will-change: transform, opacity;
  }

  .about-hero__pattern--left {
    left: -15.5rem;
    bottom: -23rem;
    width: 41.5rem;
    --about-hero-pattern-translate-x: 0px;
    --about-hero-pattern-translate-y: 0px;
  }


  /* Label */
  .about-hero__label {
    display: inline-block;
    font-family: 'Outfit', sans-serif;
    font-size: 0.75rem;
    font-weight: 500;
    line-height: 1.275rem;
    letter-spacing: 0.075rem;
    text-transform: uppercase;
    background: linear-gradient(90deg, #9810fa 0%, #6564db 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
    margin-bottom: 2.3125rem;
  }

  /* Title */
  .about-hero__title {
    font-family: 'Outfit', sans-serif;
    font-size: 5.125rem;
    font-weight: 300;
    line-height: 5.9rem;
    letter-spacing: -0.205rem;
    color: #0F0F0F;
    margin-bottom: 2.5rem;
  }

  .about-hero__highlight {
    font-family: 'Outfit', sans-serif;
    background: linear-gradient(90deg, #9810fa 0%, #6564db 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
  }

  /* Subtitle */
  .about-hero__subtitle {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem;
    font-weight: 400;
    line-height: 1.59375rem;
    letter-spacing: 0.01875rem;
    color: #5e6979;
    margin: 0 auto;
  }

  /* Arrow */
  .about-hero__arrow {
    display: inline-block;
    margin-top: 3.125rem;
    cursor: pointer;
    transition: opacity 0.3s ease, transform 0.3s ease;
  }

  .about-hero__arrow img {
    width: 3.625rem;
    height: 3.625rem;
    display: block;
  }

  .about-hero__arrow:hover {
    opacity: 0.8;
    transform: translateY(4px);
  }

  /* Responsive */
  @media (max-width: 991px) {
    .about-hero__title br {
      display: none;
    }

    .about-hero__pattern {
      display: none;
    }
  }

  @media (max-width: 575px) {
    .about-hero {
      padding: 10.75rem 1.5rem 5.25rem;
      min-height: 0;
    }

    .about-hero .container {
      max-width: 100%;
    }

    .about-hero__label {
      font-weight: 600;
      font-size: 0.75rem;
      line-height: 170%;
      letter-spacing: 0.96px;
      margin-bottom: 1rem;
    }

    .about-hero__title,
    .about-hero__highlight {
      font-size: 3.375rem;          /* match home-hero mobile title */
      line-height: 1.125em;
      font-weight: 400;
      letter-spacing: -0.09rem;
    }

    .about-hero__subtitle {
      font-size: 0.875rem;          /* 14px to match home-hero desc mobile */
      line-height: 1.7;
      letter-spacing: 0.0175rem;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const arrow = document.querySelector('.about-hero__arrow');
    const pattern = document.querySelector('.about-hero__pattern--left');
    if (arrow) {
      arrow.addEventListener('click', function (e) {
        e.preventDefault();
        const heroSection = document.querySelector('.about-hero');
        const heroBottom = heroSection.offsetTop + heroSection.offsetHeight;
        window.scrollTo({ top: heroBottom, behavior: 'smooth' });
      });
    }

    if (pattern) {
      let ticking = false;
      const updatePatternMotion = () => {
        const y = window.scrollY || window.pageYOffset || 0;
        pattern.style.setProperty('--about-hero-pattern-scroll-translate-x', (-Math.min(y * 0.35, 200)) + 'px');
        pattern.style.setProperty('--about-hero-pattern-scroll-translate-y', Math.min(y * 0.35, 200) + 'px');
        pattern.style.setProperty('--about-hero-pattern-scale', (1 + Math.min(y * 0.00025, 0.12)).toFixed(3));
        pattern.style.setProperty('--about-hero-pattern-opacity', (0.62 + Math.min(y * 0.0005, 0.38)).toFixed(3));
        ticking = false;
      };

      window.addEventListener('scroll', function () {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(updatePatternMotion);
      }, { passive: true });

      updatePatternMotion();
    }
  });
</script>