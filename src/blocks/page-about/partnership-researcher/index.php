<section class="redlof-block partnership-researcher">
  <div class="container">
    <div class="row">
      <!-- Left Column – same structure as Partnership / Funder (col-lg-5 / col-lg-6 offset-lg-1) -->
      <div class="col-lg-5 mb-4 mb-lg-0">
        <h3 class="partnership-researcher__label">Researcher</h3>
        <p class="partnership-researcher__desc">
          As a pro bono advisory for countries, we support both new implementations and upgrades to existing systems.
        </p>
      </div>

      <!-- Right Column - Logos (col-lg-6 offset-lg-1, same as Partnership / Funder) -->
      <div class="col-lg-6 offset-lg-1">
        <div class="partnership-researcher__logos">
          <div class="row align-items-center g-3 g-md-4">
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/stanford.png'); ?>" alt="Stanford Doerr School of Sustainability" class="partnership-researcher__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/harvard.png'); ?>" alt="Harvard Kennedy School" class="partnership-researcher__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/cambridge.png'); ?>" alt="University of Cambridge Judge Business School" class="partnership-researcher__logo" loading="lazy" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="partnership-researcher__divider"></div>
  </div>
</section>

<style>
  .partnership-researcher {
    background-color: #ffffff;
    padding: 2.5rem 0 5rem; /* 40px top, 80px bottom – desktop */
  }

  .partnership-researcher__label {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;        /* 20px */
    font-weight: 500;          /* Medium */
    line-height: 1.7;
    letter-spacing: 0.1rem;    /* 1.6px */
    text-transform: uppercase;
    color: #0f0f0f;
    margin: 0 0 1rem;          /* 16px */
  }

  .partnership-researcher__desc {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem;      /* 15px */
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.01875rem;/* 0.3px */
    color: #5e6979;
    margin: 0;
    max-width: 26.875rem;      /* 430px */
  }

  .partnership-researcher__logos {
    padding-left: 0;
  }

  .partnership-researcher__logo {
    max-width: 9.375rem;       /* 150px */
    max-height: 5rem;          /* 80px */
    width: 100%;
    height: auto;
    object-fit: contain;
  }

  .partnership-researcher__divider {
    height: 1px;
    margin-top: 5rem;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Responsive */
  @media (max-width: 991px) {
    .partnership-researcher__logos {
      margin-top: 1.5rem;
    }

    .partnership-researcher__desc {
      max-width: 100%;
    }
  }

  @media (max-width: 575px) {
    .partnership-researcher {
      padding: 1rem 0 4rem;  /* 16px top, 64px bottom – mobile */
    }

    .partnership-researcher .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .partnership-researcher__logos {
      margin-top: 1.5rem;
    }

    .partnership-researcher__logo {
      max-height: 3.5rem;
    }
  }
</style>
