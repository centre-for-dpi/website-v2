<section class="redlof-block work-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 text-center">

        <!-- Main Title -->
        <h1 class="work-hero__title text-uppercase">
          <span class="work-hero__title-line1">Accelerating DPI execution for
          </span>
          <span class="work-hero__title-line2">25+ countries</span>
        </h1>
        
        <!-- Description -->
        <p class="work-hero__desc">
          Showcasing the people we help, the places we've worked, and the services that we provide.
        </p>
      </div>
    </div>
  </div>
  
  <!-- Decorative Pattern -->
  <div class="work-hero__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-4.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
.work-hero {
  background: linear-gradient(90deg, #F5F0FF 0%, #FFFFFF 100%);
  padding: 160px 0 120px;
  min-height: 511px;
  position: relative;
  overflow: hidden;
}

.work-hero__label {
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

.work-hero__title {
  font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-weight: 500;
  font-size: 48px;
  line-height: 72px;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  text-align: center;
  color: #101828;
  margin: 0 0 24px 0;
  max-width: 936px;
  width: 100%;
  margin-left: auto;
  margin-right: auto;
}

.work-hero__title-line1 {
  display: block;
  color: #101828;
}

.work-hero__title-line2 {
  display: block;
  background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.work-hero__desc {
  font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  font-weight: 400;
  font-size: 15px;
  line-height: 170%;
  letter-spacing: 0.02em;
  text-align: center;
  color: #5E6979;
  margin: 0 auto;
  max-width: 932px;
}

.work-hero__pattern {
  position: absolute;
  right: 0;
  bottom: 0px;
  width: 250px;
  height: auto;
  opacity: 0.6;
  pointer-events: none;
  z-index: 0;
  --work-hero-pattern-translate-x: 0px;
  --work-hero-pattern-translate-y: 0px;
  --work-hero-pattern-scroll-translate-x: 0px;
  --work-hero-pattern-scroll-translate-y: 0px;
  --work-hero-pattern-scale: 1;
  --work-hero-pattern-opacity: 0.62;
}

.work-hero__pattern img {
  width: 100%;
  height: auto;
  transform-origin: center center;
  transform: translate(
    calc(var(--work-hero-pattern-translate-x) + var(--work-hero-pattern-scroll-translate-x)),
    calc(var(--work-hero-pattern-translate-y) + var(--work-hero-pattern-scroll-translate-y))
  ) scale(var(--work-hero-pattern-scale));
  opacity: var(--work-hero-pattern-opacity);
  transition: opacity 0.12s linear;
  will-change: transform, opacity;
}

.work-hero .container {
  position: relative;
  z-index: 1;
}

/* Responsive */
@media (max-width: 991px) {
  .work-hero {
    padding: 100px 0 60px;
    min-height: 0;
  }
  
  .work-hero__title {
    font-size: 2.75rem;
    line-height: 1.2;
    margin-bottom: 20px;
  }
  
  .work-hero__pattern {
    width: 250px;
    bottom: -150px;
  }
}

@media (max-width: 767px) {
  .work-hero {
    padding: 140px 0 24px;
    min-height: 0;
  }

  .work-hero .container {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
  
  .work-hero__title {
    font-size: 2rem;
    line-height: 1.2;
    margin-bottom: 16px;
  }
  
  .work-hero__desc {
    font-size: 0.95rem;
    line-height: 1.5;
  }
  
  .work-hero__pattern {
    display: none;
  }
}
</style>

<script>
  (function () {
    var pattern = document.querySelector('.work-hero__pattern');
    if (!pattern) return;

    var ticking = false;
    function updatePatternMotion() {
      var y = window.scrollY || window.pageYOffset || 0;
      pattern.style.setProperty('--work-hero-pattern-scroll-translate-x', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--work-hero-pattern-scroll-translate-y', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--work-hero-pattern-scale', (1 + Math.min(y * 0.00025, 0.12)).toFixed(3));
      pattern.style.setProperty('--work-hero-pattern-opacity', (0.62 + Math.min(y * 0.0005, 0.38)).toFixed(3));
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
