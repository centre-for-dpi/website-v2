<section class="redlof-block partnership-country">
  <div class="container">
    <div class="row">
      <!-- Left Column – same structure as Partnership / Funder (col-lg-5 / col-lg-6 offset-lg-1) -->
      <div class="col-lg-5 mb-4 mb-lg-0">
        <h3 class="partnership-country__label">Country implementation</h3>
        <p class="partnership-country__desc">
          As a pro bono advisory for countries, we support both new implementations and upgrades to existing systems.
        </p>
      </div>

      <!-- Right Column - Logos (col-lg-6 offset-lg-1, same as Partnership / Funder) -->
      <div class="col-lg-6 offset-lg-1">
        <div class="partnership-country__logos">
          <div class="row align-items-center g-3 g-md-4">
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/carnegie-endowment.png'); ?>" alt="Carnegie Endowment for International Peace" class="partnership-country__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/africa-nenda.png'); ?>" alt="AfricaNenda" class="partnership-country__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/50in5.png'); ?>" alt="50in5" class="partnership-country__logo" loading="lazy" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="partnership-country__divider"></div>
  </div>
</section>

<style>
  .partnership-country {
    background-color: #ffffff;
    padding: 2.5rem 0 5rem; /* 40px top, 80px bottom – desktop */
  }

  .partnership-country__label {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;        /* 20px */
    font-weight: 500;          /* Medium */
    line-height: 1.7;
    letter-spacing: 0.1rem;    /* 1.6px */
    text-transform: uppercase;
    color: #0f0f0f;
    margin: 0 0 1rem;          /* 16px */
  }

  .partnership-country__desc {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem;      /* 15px */
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.01875rem;/* 0.3px */
    color: #5e6979;
    margin: 0;
    max-width: 26.875rem;      /* 430px */
  }

  .partnership-country__logos {
    padding-left: 0;
  }

  .partnership-country__logo {
    max-width: 9.375rem;
    max-height: 5rem;
    width: 100%;
    height: auto;
    object-fit: contain;
  }

  .partnership-country__divider {
    height: 1px;
    margin-top: 5rem;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Responsive */
  @media (max-width: 991px) {
    .partnership-country__logos {
      margin-top: 1.5rem;
    }

    .partnership-country__desc {
      max-width: 100%;
    }
  }

  @media (max-width: 575px) {
    .partnership-country {
      padding: 1rem 0 4rem;  /* 16px top, 64px bottom – mobile */
    }
    .partnership-country .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .partnership-country__logos {
      margin-top: 1.5rem;
    }

    .partnership-country__logo {
      max-height: 3.5rem;
    }
  }
</style>
