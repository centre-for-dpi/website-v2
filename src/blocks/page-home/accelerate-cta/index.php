<section class="redlof-block accelerate-cta">
  <div class="accelerate-cta__pattern" aria-hidden="true"></div>
  <div class="container">
    <div class="accelerate-cta__lines accelerate-cta__lines--left" aria-hidden="true"></div>
    <div class="accelerate-cta__lines accelerate-cta__lines--bottom" aria-hidden="true"></div>
    <!-- Main Banner -->
    <div class="accelerate-cta__banner" style="background-image: url('<?php echo Helper::getImagePath('images/accelerate-cta-image.jpg'); ?>')">
      <div class="accelerate-cta__overlay"></div>
      <div class="accelerate-cta__content text-center">
        <h2 class="accelerate-cta__title mb-4">Ready to Accelerate Your DPI Journey?</h2>
        <p class="accelerate-cta__subtitle mb-4">
          Whether you need strategic advice, rapid deployment, or knowledge resources, our team is ready to support your country's digital transformation.
        </p>
        <a href="/contact-us" class="btn accelerate-cta__btn">
          Schedule a Free Consultation
        </a>
      </div>
    </div>
  </div>
</section>

<style>
.accelerate-cta {
  background-color: #ffffff;
  padding: 1.5rem 0 5rem;
  overflow: hidden;
  position: relative !important;
}

.accelerate-cta .container {
  overflow: visible;
  z-index: 1;
}

/* Decorative Lines */
.accelerate-cta__lines {
  display: none;
  position: absolute;
  background-image: repeating-linear-gradient(
    -45deg,
    transparent,
    transparent 6px,
    #6366f1 6px,
    #6366f1 7px
  );
  opacity: 0.6;
  z-index: 1;
  pointer-events: none;
}

.accelerate-cta__lines--left {
  width: 5rem;
  height: 19rem;
  top: -1.25rem;
  left: -2.5rem;
}

.accelerate-cta__lines--bottom {
  width: 12.5rem;
  height: 5rem;
  bottom: -2.5rem;
  left: -1.25rem;
}

/* Main Banner */
.accelerate-cta__banner {
  position: relative;
  background-size: cover;
  background-position: center;
  background-color: #f7f4f4;
  border-radius: 0.75rem;
  overflow: hidden;
  padding: 5.625rem 19.875rem;
  min-height: 23.625rem;
  width: 100%;
  z-index: 2;
}

.accelerate-cta__overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at center, #363575 0%, #6564db 100%);
  mix-blend-mode: multiply;
  border-radius: 0.75rem;
  z-index: 1;
}

.accelerate-cta__pattern {
  position: absolute;
  left: -21.25rem;
  bottom: -27.025rem;
  width: 48.8378125rem;
  height: 48.8378125rem;
  background-image: url('<?php echo Helper::getImagePath('patterns/accelerate-cta-pattern.svg'); ?>');
  background-repeat: no-repeat;
  background-position: center;
  background-size: 100% 100%;
  z-index: 0;
  pointer-events: none;
}

.accelerate-cta__content {
  position: relative;
  z-index: 2;
  max-width: 39rem;
  margin: 0 auto;
}

.accelerate-cta__title {
  font-style: normal;
  font-weight: 300;
  font-size: 1.625rem;
  color: #ffffff;
  line-height: 2.6rem;
  letter-spacing: 0.0325rem;
  text-shadow: 0 0.25rem 0.25rem rgba(0, 0, 0, 1);
}

.accelerate-cta__subtitle {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.87);
  line-height: 1.4rem;
  letter-spacing: 0.0175rem;
  max-width: 39rem;
  margin-left: auto;
  margin-right: auto;
  text-shadow: 0 0.25rem 0.25rem rgba(0, 0, 0, 1);
}

.accelerate-cta__btn {
  background-color: #ffffff;
  color: #0f0f0f;
  border: 1px solid #0f0f0f;
  border-radius: 0.4375rem;
  padding: 0.8125rem 1.5rem;
  font-weight: 400;
  font-size: 0.875rem;
  line-height: 1.4rem;
  letter-spacing: 0.00875rem;
  transition: all 0.2s ease;
}

.accelerate-cta__btn:hover {
  background-color: #ffffff;
  color: #0f0f0f;
  transform: none;
}

.accelerate-cta__title.mb-4 {
  margin-bottom: 1.125rem;
}

.accelerate-cta__subtitle.mb-4 {
  margin-bottom: 3rem;
}

@media (min-width: 1200px) {
  .accelerate-cta__lines--left {
    width: 5.5rem;
    height: 20rem;
    top: -1.5rem;
    left: -3rem;
  }

  .accelerate-cta__lines--bottom {
    width: 14rem;
    height: 5.5rem;
    bottom: -3rem;
    left: -1.5rem;
  }

  .accelerate-cta__banner {
    padding: 5.625rem 19.875rem;
    border-radius: 0.75rem;
  }

  .accelerate-cta__content {
    max-width: 39rem;
  }

  .accelerate-cta__title {
    font-size: 1.625rem;
    line-height: 2.6rem;
  }

  .accelerate-cta__subtitle {
    font-size: 0.875rem;
    line-height: 1.4rem;
    max-width: 39rem;
  }

  .accelerate-cta__btn {
    font-size: 0.875rem;
    padding: 0.8125rem 1.5rem;
    border-radius: 0.4375rem;
  }
}

@media (max-width: 991px) {
  .accelerate-cta {
    padding: 4.75rem 0;
  }

  .accelerate-cta__banner {
    padding: 3.75rem 2rem;
  }
  
  .accelerate-cta__title {
    font-size: 2rem;
  }
  }

@media (max-width: 767px) {
  .accelerate-cta {
    padding: 4rem 0;
  }

  .accelerate-cta__banner {
    padding: 3.25rem 1.75rem;
    border-radius: 1rem;
  }


  .accelerate-cta__title {
    font-size: 1.75rem;
  }

  .accelerate-cta__subtitle {
    font-size: 0.95rem;
  }
}

@media (max-width: 575px) {
  .accelerate-cta {
    padding: 0 0 3.5rem;
  }

  .accelerate-cta__banner {
    padding: 2.75rem 1.25rem;
    border-radius: 0.875rem;
    background-position: center;
  }


  .accelerate-cta__lines--left {
    width: 4rem;
    height: 14rem;
    top: -1rem;
    left: -2rem;
  }

  .accelerate-cta__lines--bottom {
    width: 9rem;
    height: 4rem;
    bottom: -2rem;
    left: -1rem;
  }

  .accelerate-cta__title {
    font-size: 1.5rem;
  }

  .accelerate-cta__subtitle {
    font-size: 0.9rem;
  }

  .accelerate-cta__btn {
    width: 100%;
    max-width: 100%;
    padding: 0.875rem 1.5rem;
  }
}

</style>
