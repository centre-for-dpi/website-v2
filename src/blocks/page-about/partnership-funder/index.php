<section class="redlof-block partnership-funder">
  <div class="container">
    <div class="row">
      <!-- Left Column – same structure as Partnership section (col-lg-5 / col-lg-6 offset-lg-1) -->
      <div class="col-lg-5 mb-4 mb-lg-0">
        <h3 class="partnership-funder__label">Funder</h3>
        <p class="partnership-funder__desc">
          As a pro bono advisory for countries, we support both new implementations and upgrades to existing systems.
        </p>
      </div>

      <!-- Right Column - Logos (same col layout as Partnership: col-lg-6 offset-lg-1) -->
      <div class="col-lg-6 offset-lg-1">
        <div class="partnership-funder__logos">
          <div class="row align-items-center g-3 g-md-4">
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/bill-melinda-gates-foundation.png'); ?>" alt="Bill & Melinda Gates Foundation" class="partnership-funder__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/rockefeller-foundation.png'); ?>" alt="Rockefeller Foundation" class="partnership-funder__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/ministry-of-external-affairs.png'); ?>" alt="Ministry of External Affairs" class="partnership-funder__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/norad.png'); ?>" alt="Norad" class="partnership-funder__logo" loading="lazy" />
            </div>
            <div class="col-6 col-lg-4 text-center mb-3 mb-lg-0">
              <img src="<?php echo Helper::getImagePath('logos/co-develop.png'); ?>" alt="Co-Develop" class="partnership-funder__logo" loading="lazy" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="partnership-funder__divider"></div>
  </div>
</section>

<style>
  .partnership-funder {
    background-color: #ffffff;
    padding: 2.5rem 0 5rem; /* 40px top, 80px bottom – desktop */
  }

  .partnership-funder__label {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;        /* 20px */
    font-weight: 500;          /* Medium */
    line-height: 1.7;
    letter-spacing: 0.1rem;    /* 1.6px */
    text-transform: uppercase;
    color: #0f0f0f;
    margin: 0 0 1rem;          /* 16px */
  }

  .partnership-funder__desc {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem;      /* 15px */
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.01875rem;/* 0.3px */
    color: #5e6979;
    margin: 0;
    max-width: 26.875rem;      /* 430px */
  }

  .partnership-funder__logos {
    padding-left: 0;
  }

  .partnership-funder__logo {
    max-width: 9.375rem;       /* 150px */
    max-height: 5rem;          /* 80px */
    width: 100%;
    height: auto;
    object-fit: contain;
  }

  .partnership-funder__divider {
    height: 1px;
    margin-top: 5rem;          /* 80px */
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Responsive */
  @media (max-width: 991px) {
    .partnership-funder__logos {
      margin-top: 1.5rem;
    }

    .partnership-funder__desc {
      max-width: 100%;
    }
  }

  @media (max-width: 575px) {
    .partnership-funder {
      padding: 1rem 0 4rem;  /* 16px top, 64px bottom – mobile */
    }

    .partnership-funder .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .partnership-funder__logos {
      margin-top: 1.5rem;
    }

    .partnership-funder__logo {
      max-height: 3.5rem;      /* 56px approx */
    }
  }
</style>
