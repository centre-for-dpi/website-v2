<?php
/**
 * Deploying DPI - the DaaS way section.
 */
?>
<section class="redlof-block deploying-dpi">
  <div class="deploying-dpi__separator"></div>

  <div class="container deploying-dpi__container">
    <div class="deploying-dpi__main">
      <div class="deploying-dpi__intro">
        <div class="deploying-dpi__icon">
          <img src="<?php echo Helper::getImagePath('icons/deploying-dpi.svg'); ?>" alt="" width="24" height="24" loading="lazy" />
        </div>
        <h2 class="deploying-dpi__title">
          <span class="deploying-dpi__title-line1">Deploying DPI</span>
          <span class="deploying-dpi__title-line2">the DaaS way</span>
        </h2>
        <div class="deploying-dpi__text">
          <p class="deploying-dpi__body">
          Using robust open-source DPGs with pre-qualified technology service providers, and ready-to-deploy program and policy packages helps governments to avoid design errors and reduce deployment time to weeks. This is the DPI-as-packaged Solution (DaaS) way.
          </p>
          <p class="deploying-dpi__body deploying-dpi__body--muted">
            Co-created with Co-Develop Foundation and EkStep Foundation.
          </p>
          <p class="deploying-dpi__body deploying-dpi__body--muted deploying-dpi__body--funded">
            Funded in countries by Co-Develop, VélezReyes+ and World Bank.
          </p>
        </div>
      </div>

      <div class="deploying-dpi__cards" aria-label="DaaS highlights">
        <div class="deploying-dpi__card">
          <img class="deploying-dpi__card-icon" src="<?php echo Helper::getImagePath('images/our-work/deploy-dpi2.svg'); ?>" alt="" loading="lazy" />
          <div class="deploying-dpi__card-body">
            <p class="deploying-dpi__card-text">
              Best in class <strong>open source code</strong>
            </p>
          </div>
        </div>

        <div class="deploying-dpi__card">
          <img class="deploying-dpi__card-icon" src="<?php echo Helper::getImagePath('images/our-work/deploy-dpi3.svg'); ?>" alt="" loading="lazy" />
          <div class="deploying-dpi__card-body">
            <p class="deploying-dpi__card-text">
              Pre-qualified, pre-trained </br>
              <strong>Service Providers</strong>
            </p>
          </div>
        </div>

        <div class="deploying-dpi__card">
          <img class="deploying-dpi__card-icon" src="<?php echo Helper::getImagePath('images/our-work/deploy-dpi4.svg'); ?>" alt="" loading="lazy" />
          <div class="deploying-dpi__card-body">
            <p class="deploying-dpi__card-text">
              <strong>Co-funded</strong> deployments
            </p>
          </div>
        </div>

        <div class="deploying-dpi__card deploying-dpi__card--artefacts">
          <img class="deploying-dpi__card-icon" src="<?php echo Helper::getImagePath('images/our-work/deploy-dpi1.svg'); ?>" alt="" loading="lazy" />
          <div class="deploying-dpi__card-body">
            <p class="deploying-dpi__card-kicker">Pre-packed Artefacts</p>
            <ul class="deploying-dpi__card-list">
              <li>Technical Scope Templates</li>
              <li>Standard Legal Arrangements</li>
              <li>Program &amp; Adoption Playbooks</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .deploying-dpi {
    background-color: #ffffff;
    padding: 0 10px 5.5rem;
    overflow-x: hidden;
    box-sizing: border-box;
  }

  .deploying-dpi__separator {
    height: 1px;
    width: 100%;
    background: linear-gradient(90deg, #d6e1f1 0%, #6564DB 50%, #d6e1f1 100%);
    margin-bottom: 7.5rem;
  }

  .deploying-dpi__container {
    max-width: 1260px;
    box-sizing: border-box;
  }

  .deploying-dpi__main {
    display: flex;
    flex-wrap: nowrap;
    align-items: flex-start;
    justify-content: flex-start;
    gap: 55px;
  }

  /* Intro only as wide as its content; avoids flex-grow empty space beside 370px text */
  .deploying-dpi__intro {
    flex: 0 0 auto;
    width: fit-content;
    max-width: 100%;
    min-width: 0;
  }

  .deploying-dpi__icon {
    width: 24px;
    height: 24px;
    margin-bottom: 1.5rem;
  }

  .deploying-dpi__icon img {
    width: 100%;
    height: 100%;
    display: block;
  }

  .deploying-dpi__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 0 0 1.5rem 0;
    min-width: 0;
  }

  .deploying-dpi__title-line1,
  .deploying-dpi__title-line2 {
    display: block;
  }

  .deploying-dpi__title-line1 {
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .deploying-dpi__title-line2 {
    color: #101828;
  }

  .deploying-dpi__body {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 165%;
    letter-spacing: 0.02em;
    color: #5E6979;
    margin: 0;
  }

  .deploying-dpi__body + .deploying-dpi__body {
    margin-top: 1.5rem;
  }

  .deploying-dpi__body--muted {
    color: #5E6979;
  }

  .deploying-dpi__body--funded {
    margin-top: 16px;
  }

  .deploying-dpi__text {
    max-width: 370px;
    min-width: 0;
  }

  .deploying-dpi__cards {
    flex: 1 1 0;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1.25rem;
    min-width: 0;
  }

  .deploying-dpi__card {
    background-color: #ffffff;
    border-radius: 8px;
    border: 1px solid #d6e1f1;
    padding: 1.25rem 1.25rem;
    height: 190px;
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
    justify-content: flex-start;
    box-sizing: border-box;
  }

  .deploying-dpi__card--artefacts {
    height: auto;
    min-height: 190px;
  }

  .deploying-dpi__card-body {
    flex: 1 1 auto;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-height: 0;
  }

  .deploying-dpi__card-icon {
    width: 48px;
    height: 48px;
    flex-shrink: 0;
    align-self: center;
    display: block;
    object-fit: contain;
    margin: 0;
  }

  .deploying-dpi__card-text {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
    margin: 0;
  }

  .deploying-dpi__card-text strong {
    font-weight: 700;
  }

  .deploying-dpi__card-kicker {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
    margin: 0 0 0.5rem 0;
  }

  .deploying-dpi__card-list {
    margin: 0;
    padding-left: 1.25rem;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 165%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
  }

  .deploying-dpi__card-list li + li {
    margin-top: 0.25rem;
  }

  @media (max-width: 991px) {
    .deploying-dpi {
      padding: 4.5rem 0 4rem;
    }

    .deploying-dpi__main {
      flex-direction: column;
      gap: 2.5rem;
    }

    .deploying-dpi__intro,
    .deploying-dpi__cards {
      flex: none;
      width: 100%;
    }

    .deploying-dpi__cards {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 767px) {
    .deploying-dpi__container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      width: 100%;
      max-width: 100%;
    }

    .deploying-dpi__text {
      max-width: 100%;
    }

    .deploying-dpi__cards {
      grid-template-columns: 1fr;
    }

    .deploying-dpi__intro {
      max-width: none;
    }

    .deploying-dpi__separator {
      margin-bottom: 3rem;
      display: none;
    }
  }

  @media (max-width: 575px) {
    .deploying-dpi__container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .deploying-dpi__title {
      font-size: 32px;
    }
  }
</style>

