<section class="redlof-block backed-by">
  <div class="container position-relative">
    <div class="row align-items-center backed-by__row">
      <!-- Left Column - Title & Description -->
      <div class="col-lg-4 mb-4 mb-lg-0 backed-by__col-text">
        <h2 class="backed-by__title">BACKED BY</h2>
        <p class="backed-by__desc">
          Our work is supported by visionary partners who share our mission to build inclusive and resilient digital
          public infrastructure.
        </p>
      </div>

      <!-- Right Column - Partner Logos -->
      <div class="col-lg-8 backed-by__col-logos">
        <div class="backed-by__logos">
          <div class="backed-by__logo">
            <img src="<?php echo Helper::getImagePath('logos/nilakani.png'); ?>" alt="Nilekani Philanthropies"
              loading="lazy" />
          </div>
          <div class="backed-by__logo">
            <img src="<?php echo Helper::getImagePath('logos/gates-foundation.png'); ?>" alt="Gates Foundation"
              loading="lazy" />
          </div>
          <div class="backed-by__logo">
            <img src="<?php echo Helper::getImagePath('logos/co-develop.png'); ?>" alt="Co-Develop" loading="lazy" />
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<style>
  .backed-by {
    background-color: #ffffff;
    position: relative;
    overflow: hidden;
    padding: 9.25rem 0 5rem;
  }

  .backed-by::after {
    content: "";
    position: absolute;
    bottom: 0;
    height: 1px;
    background: linear-gradient(90deg, #d6e1f1 0%, #6564db 50%, #d6e1f1 100%);
    left: 4.75rem;
    right: 4.75rem;
  }

  .backed-by__row {
    row-gap: 2rem;
  }

  .backed-by__title {
    font-family: "Outfit", sans-serif;
    font-size: 1.25rem;
    font-weight: 500;
    letter-spacing: 0.1rem;
    color: #0f0f0f;
    margin-bottom: 1.25rem;
    text-transform: uppercase;
    line-height: 2.125rem;
  }

  .backed-by__desc {
    font-family: "Outfit", sans-serif;
    font-size: 0.9375rem;
    line-height: 1.59375rem;
    letter-spacing: 0.01875rem;
    color: #5e6979;
    margin: 0;
    max-width: 26.875rem;
  }

  /* Partner Logos */
  .backed-by__logos {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.9375rem;
    width: 100%;
  }

  .backed-by__logo {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 12.5rem;
    height: 6.375rem;
  }

  .backed-by__logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    filter: none;
    opacity: 1;
  }

  /* Responsive */
  @media (max-width: 1199px) {
    .backed-by::after {
      left: 3.5rem;
      right: 3.5rem;
    }

    .backed-by__logo {
      width: 10rem;
      height: 5.25rem;
    }
  }

  @media (max-width: 991px) {
    .backed-by {
      padding: 4.5rem 0 3.5rem;
    }

    .backed-by .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .backed-by::after {
      left: 1.5rem;
      right: 1.5rem;
    }

    .backed-by__logos {
      flex-wrap: wrap;
      gap: 1.5rem;
    }
  }

  @media (max-width: 575px) {
    .backed-by {
      padding: 4.5rem 0 3.5rem;
    }

    .backed-by .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .backed-by::after {
      left: 1.5rem;
      right: 1.5rem;
    }

    .backed-by__col-text,
    .backed-by__desc {
      max-width: 21.375rem;
    }

    .backed-by__title {
      font-weight: 600;
    }

    .backed-by__logos {
      flex-direction: column;
      align-items: center;
      justify-content: flex-start;
      gap: 0.9375rem;
    }
  }
</style>