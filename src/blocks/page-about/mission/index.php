<section class="redlof-block mission">
  <div class="container">
    <div class="row mission__row">
      <!-- Left Column -->
      <div class="col-lg-5 mb-5 mb-lg-0 d-flex align-items-center">
        <div class="mission__left">
          <div class="mission__icon mb-4">
            <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-4.svg'); ?>" alt="" width="24" height="24" loading="lazy" />
          </div>
          <h2 class="mission__title">Mission 1 billion</h2>
          <p class="mission__subtitle">
            Help impact the lives of 1 billion people through a well-designed population scale infrastructure.
          </p>
        </div>
      </div>

      <!-- Right Column -->
      <div class="col-lg-6 offset-lg-1">
        <div class="mission__content">
          <p class="mission__text mb-5">
            The Centre for Digital Public Infrastructure was founded in 2023 to address a critical gap: the lack of technical capacity within governments to design, implement, and scale digital public infrastructure.
          </p>

          <p class="mission__text mb-5">
            <strong>We are builders first.</strong> Our team has hands-on experience creating some of the world's most successful DPI implementations, including India Stack, UPI, and Aadhaar systems that serve over a billion people.
          </p>

          <p class="mission__text mb-0">
            Operating across Africa, Latin America, the Caribbean, Asia, and Europe, we provide pro-bono technical architecture advisory to help countries design their own DPI journeys.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .mission {
    background-color: #ffffff;
    padding: 10.5rem 0 8.25rem;
    overflow-x: hidden;
  }

  .mission__row {
    --bs-gutter-x: 13.875rem;
    align-items: center;
  }

  .mission__left {
    max-width: 26.875rem;
  }

  .mission__icon img {
    width: 1.5rem;
    height: 1.5rem;
    display: block;
  }

  .mission__icon {
    margin-bottom: 1.5rem;
  }

  .mission__title {
    font-family: 'Lora', serif;
    font-size: 2.625rem;
    font-weight: 500;
    line-height: 1.25;
    letter-spacing: -0.0525rem;
    color: #0f0f0f;
    margin: 0 0 1.5rem;
  }

  .mission__subtitle {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem;
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.01875rem;
    color: #5e6979;
    max-width: 26.875rem;
    margin: 0;
  }

  .mission__content {
    font-family: 'Outfit', sans-serif;
    font-size: 1.0625rem;
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.02125rem;
    color: #5e6979;
  }

  .mission__text {
    margin: 0;
  }

  .mission__text + .mission__text {
    margin-top: 4.5rem;
  }

  .mission__text strong {
    font-weight: 500;
    color: #0f0f0f;
  }

  @media (max-width: 991px) {
    .mission {
      padding: 7.5rem 0 6rem;
    }

    .mission__row {
      --bs-gutter-x: 4.5rem;
    }

    .mission__left {
      max-width: 100%;
    }
  }

  @media (max-width: 575px) {
    .mission {
      padding: 4.5rem 0;
    }

    .mission .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .mission__row {
      --bs-gutter-x: 0;
    }

    .mission__icon {
      margin-bottom: 1rem;
    }

    .mission__title {
      font-size: 2rem;
      letter-spacing: -0.04rem;
      margin-bottom: 1rem;
    }

    .mission__subtitle {
      max-width: 100%;
      font-size: 0.9375rem;
      margin-bottom: 1.5rem;
    }

    .mission__content {
      font-size: 1.0625rem;
    }

    .mission__text + .mission__text {
      margin-top: 3rem;
    }
  }
</style>
