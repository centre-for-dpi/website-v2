<section class="redlof-block core-philosophy">
  <div class="container">
    <div class="core-philosophy__inner">
      <div class="core-philosophy__header text-center">
        <div class="core-philosophy__icon">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-5.svg'); ?>" alt="" width="32" height="32"
            loading="lazy" />
        </div>
        <h2 class="core-philosophy__title">Core Philosophy</h2>
        <p class="core-philosophy__subtitle">
          Tech architects who are Pro Bono, Impartial, and have a Bias for Action.
        </p>
      </div>

      <div class="core-philosophy__grid">

        <!-- Card 1 -->
      <article class="core-philosophy-card">
          <div class="core-philosophy-card__image">
            <img
              src="<?php echo Helper::getImagePath('images/about/core-philosophy1.jpeg'); ?>"
              alt="Pro bono support"
              loading="lazy"
            />
          </div>
          <div class="core-philosophy-card__body">
            <h3 class="core-philosophy-card__title">Pro bono support</h3>
            <ul class="core-philosophy-card__list">
              <li><strong>No fee</strong> regardless of length of engagement</li>
            </ul>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="core-philosophy-card">
          <div class="core-philosophy-card__image">
            <img
              src="<?php echo Helper::getImagePath('images/about/core-philosophy2.jpeg'); ?>"
              alt="Neutrality"
              loading="lazy"
            />
          </div>
          <div class="core-philosophy-card__body">
            <h3 class="core-philosophy-card__title">IMPARTIAL & Neutral</h3>
            <ul class="core-philosophy-card__list">
              <li>Vendor and product neutral</li>
              <li>Support both open source and proprietary deployments</li>
              <li>No political alignment</li>
              <li>Built in line with global DPI safeguards</li>
            </ul>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="core-philosophy-card">
          <div class="core-philosophy-card__image">
            <img
              src="<?php echo Helper::getImagePath('images/about/core-philosophy3.jpeg'); ?>"
              alt="Bias for action"
              loading="lazy"
            />
          </div>
          <div class="core-philosophy-card__body">
            <h3 class="core-philosophy-card__title">Bias for action</h3>
            <ul class="core-philosophy-card__list">
              <li><strong>Implementation-focused</strong> practical guidance</li>
              <li><strong>Citizen-centric</strong>, impact-first lens</li>
              <li>Prefer <strong>upgrades</strong> to existing infrastructure</li>
            </ul>
          </div>
        </article>

        <!-- Card 4 -->
        <article class="core-philosophy-card">
          <div class="core-philosophy-card__image">
            <img
              src="<?php echo Helper::getImagePath('images/about/core-philosophy4.jpeg'); ?>"
              alt="Partnerships driven"
              loading="lazy"
            />
          </div>
          <div class="core-philosophy-card__body">
            <h3 class="core-philosophy-card__title">Partnerships driven</h3>
            <ul class="core-philosophy-card__list">
              <li>Collaborate with local stakeholders</li>
              <li>Work with international partners to drive scale-up</li>
            </ul>
          </div>
        </article>

     
      </div>
    </div>
  </div>
</section>

<style>
  .core-philosophy {
    padding: 8.75rem 5.625rem; /* 140px vertical, 90px horizontal */
    background-color: #ffffff;
    border-bottom: 1px solid;
    border-image-source: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    border-image-slice: 1;
  }

  .core-philosophy__inner {
    max-width: 78.75rem; /* 1260px */
    margin: 0 auto;
  }

  .core-philosophy__header {
    margin-bottom: 4rem; /* 64px */
  }

  .core-philosophy__icon {
    width: 2rem;
    height: 2rem;
    margin: 0 auto 1.5rem; /* center icon above heading */
  }

  .core-philosophy__icon img {
    width: 100%;
    height: 100%;
    display: block;
  }

  .core-philosophy__title {
    font-family: 'Outfit', sans-serif;
    font-size: 2rem; /* 32px */
    font-weight: 300; /* Light */
    line-height: 1.4; /* 140% */
    letter-spacing: 0;
    text-align: center;
    color: #101828;
    margin: 0 0 1.5rem; /* 24px below heading */
  }

  .core-philosophy__subtitle {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem; /* 15px */
    font-weight: 400;      /* Regular */
    line-height: 1.7;      /* 170% */
    letter-spacing: 0.02em;/* ~2% */
    text-align: center;
    color: #5E6979;
    margin: 0;
  }

  .core-philosophy__grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 1.25rem; /* 20px */
  }

  .core-philosophy-card {
    background-color: #ffffff;
    border: 1px solid #D6E1F1;
    border-radius: 0.75rem; /* 12px */
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 26.875rem; /* 430px */
  }

  .core-philosophy-card__image {
    width: 100%;
    height: 10.9375rem; /* 175px */
    overflow: hidden;
  }

  .core-philosophy-card__image img {
    width: 100%;
    /* height: 100%; */
    object-fit: cover;
  }

  .core-philosophy-card__body {
    padding: 2rem 1.5rem 2.5rem; /* 32px, 24px, 40px */
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .core-philosophy-card__title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.125rem;      /* 18px */
    font-weight: 600;         /* SemiBold */
    line-height: 1.7;         /* 170% */
    letter-spacing: 0.08em;   /* 8% */
    text-transform: uppercase;
    color: #0F0F0F;
    margin: 0;
  }

  .core-philosophy-card__list {
    list-style: disc;
    padding-left: 1.25rem;
    margin: 0;
    font-family: 'Outfit', sans-serif;
    font-size: 0.875rem;      /* 14px */
    font-weight: 400;         /* Regular */
    line-height: 1.7;         /* 170% */
    letter-spacing: 0.02em;   /* ~2% */
    color: #5E6979;
  }

  .core-philosophy-card__list li + li {
    margin-top: 0.5rem;
  }

  @media (max-width: 1199px) {
    .core-philosophy__grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
      row-gap: 1.5rem;
    }
  }

  @media (max-width: 767px) {
    .core-philosophy {
      padding: 4.5rem 0;
    }

    .core-philosophy__inner {
      padding: 0 1.5rem;
    }

    .core-philosophy__grid {
      grid-template-columns: 1fr;
    }
  }
</style>

