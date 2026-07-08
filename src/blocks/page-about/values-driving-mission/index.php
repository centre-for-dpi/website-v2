<section class="redlof-block values-driving">
  <div class="container">
    <!-- Header -->
    <div class="values-driving__header text-center">
      <div class="values-driving__icon">
        <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-5.svg'); ?>" alt="" width="32" height="32"
          loading="lazy" />
      </div>
      <h2 class="values-driving__title">Values driving our mission</h2>
    </div>

    <!-- Values List -->
    <div class="values-driving__list">
      <!-- Value 1 -->
      <div class="values-driving__item">
        <div class="row justify-content-center values-driving__row">
          <div class="col-md-3 text-md-end">
            <h3 class="values-driving__label text-uppercase">Action Bias<sup>1</sup></h3>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <p class="values-driving__desc">We prioritise doing over talking, delivering tangible outcomes and showing
              results rather than simply theorising.</p>
          </div>
        </div>
      </div>

      <!-- Value 2 -->
      <div class="values-driving__item">
        <div class="row justify-content-center values-driving__row">
          <div class="col-md-3 text-md-end">
            <h3 class="values-driving__label text-uppercase">Global Mindset<sup>2</sup></h3>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <p class="values-driving__desc">We approach our work with an understanding of diverse contexts, designing
              and delivering solutions that are globally informed but locally rooted.</p>
          </div>
        </div>
      </div>

      <!-- Value 3 -->
      <div class="values-driving__item">
        <div class="row justify-content-center values-driving__row">
          <div class="col-md-3 text-md-end">
            <h3 class="values-driving__label text-uppercase">Impartiality<sup>3</sup></h3>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <p class="values-driving__desc">We remain vendor-agnostic and politically neutral, always acting in the best
              interests of the countries and communities we serve.</p>
          </div>
        </div>
      </div>

      <!-- Value 4 -->
      <div class="values-driving__item values-driving__item--last">
        <div class="row justify-content-center values-driving__row">
          <div class="col-md-3 text-md-end">
            <h3 class="values-driving__label text-uppercase">Expertise<sup>4</sup></h3>
          </div>
          <div class="col-md-1"></div>
          <div class="col-md-4">
            <p class="values-driving__desc">We help countries build secure, scalable DPI through expert design and
              architecture—avoiding costly mistakes from the start.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  /* Base (desktop-first) – 2-column layout, label right, desc left */
  .values-driving {
    background-color: #ffffff;
    padding: 9.25rem 0 8.75rem;
  }

  .values-driving__header {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    max-width: 63.5rem; /* 1016px */
    margin: 0 auto;
  }

  .values-driving__icon img {
    width: 2rem;
    height: 2rem;
    display: block;
  }

  .values-driving__icon {
    margin-bottom: 1.5rem;
  }

  .values-driving__title {
    font-family: 'Outfit', sans-serif;
    font-weight: 300;
    font-size: 2rem;
    line-height: 1.4;
    letter-spacing: 0;
    color: #101828;
    margin: 0;
  }

  .values-driving__list {
    margin-top: 4rem;
  }

  .values-driving__item {
    position: relative;
    overflow: hidden;
    padding: 3.375rem 0;
  }

  .values-driving__item:not(:last-child)::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  .values-driving__row {
    align-items: center;
    --bs-gutter-x: 0;
  }

  .values-driving__label {
    font-family: 'Outfit', sans-serif;
    font-weight: 500;
    font-size: 1.25rem;
    line-height: 1.7;
    letter-spacing: 0.1rem;
    text-align: right;
    text-transform: uppercase;
    color: #0f0f0f;
    margin: 0;
  }

  .values-driving__label sup {
    font-size: 0.65em;
    vertical-align: super;
  }

  .values-driving__desc {
    font-family: 'Outfit', sans-serif;
    font-weight: 400;
    font-size: 1.0625rem;
    line-height: 1.7;
    letter-spacing: 0.02125rem;
    color: #5e6979;
    margin: 0;
  }

  /* Desktop 2-column layout: 1025px and above */
  @media (min-width: 1025px) {
    .values-driving__list {
      max-width: 78.75rem;
      margin-left: auto;
      margin-right: auto;
    }

    .values-driving__row {
      display: flex;
    }

    .values-driving__row .col-md-1 {
      flex: 0 0 8.75rem; /* 140px */
      max-width: 8.75rem;
    }

    .values-driving__row .col-md-4 {
      flex: 0 0 29.25rem; /* 468px */
      max-width: 29.25rem;
    }
  }

  /* Mobile/tablet stacked layout: 0–1024px (one layout) */
  @media (max-width: 1024px) {
    .values-driving {
      padding: 4.5rem 0;
    }

    .values-driving .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .values-driving__list {
      margin-top: 2.5rem;
    }

    .values-driving__item {
      padding: 3rem 0;
    }

    .values-driving__row {
      display: block;
      --bs-gutter-x: 0;
    }

    .values-driving__row .col-md-3,
    .values-driving__row .col-md-1,
    .values-driving__row .col-md-4 {
      width: 100%;
      max-width: 100%;
    }

    .values-driving__label {
      text-align: left;
      margin-bottom: 0.75rem;
    }

    .values-driving__desc {
      margin: 0 auto;
      text-align: left;
    }
  }

  /* Mobile-only overrides (xs): title typography */
  @media (max-width: 575px) {
    .values-driving__title {
      font-weight: 500;
      font-size: 2rem;
      line-height: 1.3;
      letter-spacing: -0.04rem;
    }
  }
</style>