<?php
/**
 * Country advisory section – Our toolkit.
 */
?>
<section class="redlof-block country-advisory">
  <div class="container country-advisory__container">
    <div class="row country-advisory__row">
      <div class="col-lg-4">
        <div class="country-advisory__pattern">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-4.svg'); ?>" alt="" loading="lazy" />
        </div>
        <div class="country-advisory__intro">
          <span class="country-advisory__eyebrow">Our toolkit</span>
          <h2 class="country-advisory__title">Country advisory</h2>
          <p class="country-advisory__body">
            Pro bono advisory services and curated artefacts in line with global best practices addressing
            population‑scale challenges for equitable national growth.
          </p>
        </div>
      </div>
      <div class="col-lg-8">
        <div class="row country-advisory__steps">
          <div class="col-md-4">
            <div class="country-advisory__card">
              <div class="country-advisory__card-icon">
                <i class="fa fa-search"></i>
              </div>
              <div class="country-advisory__card-header">
                <span class="country-advisory__card-step">1. Discover</span>
              </div>
              <ul class="country-advisory__card-list">
                <li>DPI 101 &amp; Capacity Building</li>
                <li>Use‑Case Discovery</li>
                <li>Knowledge Exchange</li>
              </ul>
            </div>
          </div>
          <div class="col-md-4">
            <div class="country-advisory__card">
              <div class="country-advisory__card-icon">
                <i class="fa-regular fa-pen-to-square"></i>
              </div>
              <div class="country-advisory__card-header">
                <span class="country-advisory__card-step">2. Design</span>
              </div>
              <ul class="country-advisory__card-list">
                <li>Architecture blueprint</li>
                <li>Implementation Strategy</li>
                <li>Stakeholder and resource alignment</li>
              </ul>
            </div>
          </div>
          <div class="col-md-4">
            <div class="country-advisory__card">
              <div class="country-advisory__card-icon">
                <i class="fa-regular fa-calendar-check"></i>
              </div>
              <div class="country-advisory__card-header">
                <span class="country-advisory__card-step">3. Deploy</span>
              </div>
              <ul class="country-advisory__card-list">
                <li>POCs and Phase one implementation</li>
                <li>Scaling use‑cases</li>
                <li>Long term strategy</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .country-advisory {
    background-color: #ffffff;
    padding: 140px 10px;
    border-top: 1px solid transparent;
    border-image: linear-gradient(90deg, #d6e1f1 0%, #6564DB 50%, #d6e1f1 100%);
    border-image-slice: 1;
  }

  .country-advisory__container {
    max-width: 1260px;
  }

  .country-advisory__row {
    align-items: stretch;
  }

  .country-advisory__intro {
    max-width: 22.25rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .country-advisory__pattern {
    margin-bottom: 0;
  }

  .country-advisory__pattern img {
    display: block;
    max-width: 100%;
    height: auto;
  }

  .country-advisory__eyebrow {
    display: inline-block;
    margin-bottom: 1.5rem;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .country-advisory__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 0 0 1.5rem 0;
  }

  .country-advisory__body {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 165%;
    letter-spacing: 0.02em;
    color: #5E6979;
    margin: 0;
    max-width: 290px;
  }

  .country-advisory__steps {
    row-gap: 2.5rem;
  }

  .country-advisory__card {
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .country-advisory__card-icon {
    width: 100%;
    height: 116px;
    border-radius: 10px;
    border: 1px solid #d6e1f1;
    background-color: #fdfcff;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 0 1.5rem 0;
  }

  .country-advisory__card-icon i {
    font-size: 1.5rem;
    color: #6564DB;
  }

  .country-advisory__card-header {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
    margin-bottom: 0.75rem;
    font-family: "Outfit", system-ui, sans-serif;
  }

  .country-advisory__card-step {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 17px;
    line-height: 170%;
    letter-spacing: 0.02em;
    text-transform: none;
    color: #0F0F0F;
  }

  .country-advisory__card-title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 17px;
    line-height: 170%;
    letter-spacing: 0.02em;
    text-transform: uppercase;
    color: #0F0F0F;
  }

  .country-advisory__card-list {
    list-style: disc;
    padding-left: 1.25rem;
    margin: 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
    max-width: 190px;
  }

  .country-advisory__card-list li + li {
    margin-top: 0.25rem;
  }

  @media (min-width: 992px) {
    .country-advisory__steps {
      display: flex;
      column-gap: 2.5rem; /* 40px */
    }

    .country-advisory__steps > .col-md-4 {
      flex: 0 0 220px;
      max-width: 220px;
      padding-left: 0;
      padding-right: 0;
    }
  }

  @media (min-width: 992px) and (max-width: 1199px) {
    .country-advisory__container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }

  @media (max-width: 991px) {
    .country-advisory {
      padding: 4rem 0 3rem;
    }

    .country-advisory__container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .country-advisory__intro {
      margin-bottom: 2.5rem;
      max-width: none;
    }
  }

  /* Mobile / small tablet: icon left, title + list right (side-by-side) */
  @media (max-width: 767px) {
    .country-advisory__steps > .col-md-4 {
      flex: 0 0 100%;
      max-width: 100%;
    }

    .country-advisory__card {
      display: grid;
      grid-template-columns: 5.5rem 1fr;
      grid-template-rows: auto auto;
      column-gap: 1rem;
      row-gap: 0.375rem;
      align-items: start;
      height: auto;
      min-width: 0;
    }

    .country-advisory__card-icon {
      grid-column: 1;
      grid-row: 1 / -1;
      width: 5.5rem;
      height: 5.5rem;
      margin: 0;
      flex-shrink: 0;
    }

    .country-advisory__card-icon i {
      font-size: 1.35rem;
    }

    .country-advisory__card-header {
      grid-column: 2;
      grid-row: 1;
      margin-bottom: 0;
    }

    .country-advisory__card-step {
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }

    .country-advisory__card-list {
      grid-column: 2;
      grid-row: 2;
      max-width: none;
      min-width: 0;
    }
  }
</style>

