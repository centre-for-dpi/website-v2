<section
    class="col daas-hero redlof-block text-center d-flex flex-column justify-content-center align-items-center position-relative border-bottom">

    <h1 class="daas-hero__title">
      DEPLOYING DPI<br>
      THE <span class="daas-hero__highlight">DaaS</span> WAY
    </h1>

    <!-- Decorative Pattern Right (match Our Work hero) -->
    <div class="daas-hero__pattern">
        <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-4.svg'); ?>" alt="" loading="lazy" />
    </div>
</section>

<style>
    .daas-hero {
        padding: 205px 106px 160px;
        background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
        gap: 34px;
        overflow: hidden;
    }

    .daas-hero__pattern {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 250px;
        height: auto;
        opacity: 0.6;
        pointer-events: none;
        z-index: 0;
        --daas-hero-pattern-translate-x: 0px;
        --daas-hero-pattern-translate-y: 0px;
        --daas-hero-pattern-scroll-translate-x: 0px;
        --daas-hero-pattern-scroll-translate-y: 0px;
        --daas-hero-pattern-scale: 1;
        --daas-hero-pattern-opacity: 0.62;
    }

    .daas-hero__pattern img {
        width: 100%;
        height: auto;
        transform-origin: center center;
        transform: translate(
            calc(var(--daas-hero-pattern-translate-x) + var(--daas-hero-pattern-scroll-translate-x)),
            calc(var(--daas-hero-pattern-translate-y) + var(--daas-hero-pattern-scroll-translate-y))
        ) scale(var(--daas-hero-pattern-scale));
        opacity: var(--daas-hero-pattern-opacity);
        transition: opacity 0.12s linear;
        will-change: transform, opacity;
    }

    .daas-hero__subheading {
        background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
        -webkit-background-clip: text;
        color: transparent;
        -webkit-background-clip: text;
        /* Ensures support for older or specific Safari versions*/
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
        font-size: 12px;
        font-weight: 500;
        line-height: 170%;
        letter-spacing: 1.2px;
    }

    .daas-hero__title {
        font-family: 'Outfit', sans-serif;
        font-weight: 500;
        font-size: 48px;
        line-height: 72px;
        letter-spacing: 0.08em;
        text-align: center;
        color: #0F0F0F;
        margin: 0;
    }

    .daas-hero__highlight {
        font-family: 'Outfit', sans-serif;
        font-weight: 500;
        font-size: 48px;
        line-height: 72px;
        letter-spacing: 0.08em;
        background: linear-gradient(90deg, #9810fa 0%, #6564db 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        -webkit-text-fill-color: transparent;
    }

    .daas-hero__p1 {
        color: #5E6979;
        font-weight: 400;
        font-size: 15px;
        line-height: 170%;
        letter-spacing: 2%;
        text-align: center;
        max-width: 764px;
    }

    .daas-hero__p2 {
        color: #5E6979;
        font-weight: 400;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
        text-align: center;
        max-width: 764px;
    }

    .daas-hero__p2>span {
        color: #0F0F0F;
        font-weight: 600;
        font-size: 17px;
        line-height: 170%;
        letter-spacing: 2%;
        text-align: center;
    }


    .daas-hero__cta {
        cursor: pointer;
        color: white;
        background-color: #4B4AEA;
        border-radius: 7px;
        font-weight: 400;
        font-size: 14px;
        line-height: 160%;
        letter-spacing: 1%;
    }

    .daas-hero__cta:hover {
        color: white;
        background-color: #1C1AE4;
    }

    @media (width<=425px) {
        .daas-hero {
            gap: 34px;
        }

        .daas-hero__title {
            font-size: 32px;
            line-height: 1.2;
            letter-spacing: 0.06em;
        }

        .daas-hero__highlight {
            font-size: 32px;
            line-height: 1.2;
            letter-spacing: 0.06em;
        }

        .daas-hero__p1 {
            font-size: 17px;
        }

        .daas-hero__cta {
            font-size: 15px;
        }

        .daas-hero__pattern {
            display: none;
        }
    }

    @media (width<=768px) {
        .daas-hero {
            padding: 190px 1.5rem 120px;
        }

        .daas-hero__pattern {
            display: none;
        }
    }
</style>

<script>
  (function () {
    var pattern = document.querySelector('.daas-hero__pattern');
    if (!pattern) return;

    var ticking = false;
    function updatePatternMotion() {
      var y = window.scrollY || window.pageYOffset || 0;
      pattern.style.setProperty('--daas-hero-pattern-scroll-translate-x', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--daas-hero-pattern-scroll-translate-y', Math.min(y * 0.35, 200) + 'px');
      pattern.style.setProperty('--daas-hero-pattern-scale', (1 + Math.min(y * 0.00025, 0.12)).toFixed(3));
      pattern.style.setProperty('--daas-hero-pattern-opacity', (0.62 + Math.min(y * 0.0005, 0.38)).toFixed(3));
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