<section class="redlof-block casestudies">
  <div class="container">
    <div class="row">
      <!-- Left Column - Title -->
      <div class="col-lg-2 casestudies__col-title">
        <h2 class="casestudies__title text-uppercase mb-md-4">Case Studies</h2>
      </div>

      <!-- Right Column - Content -->
      <div class="col-lg-7 casestudies__col-content">
        <!-- Description -->
        <p class="casestudies__desc mb-5">
          Our team brings together a unique blend of <strong>digital policy experts, technologists, and
            practitioners</strong> who understand both the opportunities and challenges of advancing DPI in a rapid
          manner. To learn more about our previous work, check out the case studies below.
        </p>

        <!-- Case Study Cards -->
        <div class="casestudies__cards">
          <div class="casestudy-card mb-5">
            <a class="casestudy-card__image" href="/case-study/brasilia-bombay">
              <img src="<?php echo Helper::getImagePath('temp/casestudy-banner1.svg'); ?>" alt="Case Study"
                loading="lazy" />
            </a>
            <h3 class="casestudy-card__title">
              <a href="/case-study/brasilia-bombay">De Brasilia a Bombay: Los Gemelos Improbables que Lideran una
                Revolución Global de Finanzas Abiertas</a>
            </h3>
          </div>
          <div class="casestudy-card mb-5">
            <a class="casestudy-card__image" href="/case-study/multi-stakeholder">
              <img src="<?php echo Helper::getImagePath('temp/casestudy-banner2.svg'); ?>" alt="Case Study"
                loading="lazy" />
            </a>
            <h3 class="casestudy-card__title">
              <a href="/case-study/multi-stakeholder">Multi-stakeholder approaches to govern and design new Digital
                Public Infrastructure Workshop</a>
            </h3>
          </div>
        </div>

        <!-- CTA Button -->
        <a href="/our-work" class="btn casestudies__btn">
          See more of our work
        </a>
      </div>
    </div>
  </div>
</section>

<style>
  .casestudies {
    background-color: #EDECFE;
    padding: 7.25rem 0 7.5rem;
  }

  .casestudies .row {
    column-gap: 3.125rem;
  }

  .casestudies__col-title {
    flex: 0 0 13.75rem;
    max-width: 13.75rem;
  }

  .casestudies__col-content {
    flex: 0 0 48.125rem;
    max-width: 48.125rem;
    margin-left: 0;
  }

  .casestudies__title {
    color: #101828;
    margin: 0;

    font-weight: 500;
    font-size: 1.25rem;
    line-height: 2.125rem;
    letter-spacing: 0.1rem;
    text-transform: uppercase;
  }

  .casestudies__desc {
    color: #5E6979;
    margin: 0;

    font-weight: 400;
    font-size: 0.9375rem;
    line-height: 1.59375rem;
    letter-spacing: 0.01875rem;
    max-width: 48.125rem;

  }

  .casestudies__desc strong {
    color: #101828;
    font-weight: 500;

    font-size: 0.9375rem;
    line-height: 1.59375rem;
    letter-spacing: 0.01875rem;

  }

  .casestudies__desc.mb-5 {
    margin-bottom: 3.5rem;
  }

  /* Case Study Cards Container */
  .casestudies__cards {
    display: flex;
    gap: 1.25rem;
    width: 45rem;
  }

  .casestudies__cards.mb-5 {
    margin-bottom: 3.5rem;
  }

  /* Case Study Card */
  .casestudy-card {
    display: flex;
    flex-direction: column;
    max-width: 21.875rem;
  }

  .casestudy-card__image {
    border-radius: 0.625rem;
    overflow: hidden;
    margin-bottom: 2rem;
  }

  .casestudy-card__image img {
    width: 100%;
    height: 15rem;
    object-fit: cover;
    display: block;
  }

  .casestudy-card__title {
    margin: 0;
    color: #101828;

    font-weight: 500;
    font-size: 1rem;
    line-height: 1.7rem;
    letter-spacing: 0.02rem;
  }

  .casestudy-card__title a {
    text-decoration: underline;
    text-underline-offset: 4px;
  }

  .casestudy-card__title a:hover {
    color: #4f46e5;
    text-decoration-color: #4f46e5;
  }

  /* CTA Button */
  .casestudies__btn {
    background-color: #4B4AEA;
    border: 1px solid #4948E1;
    color: #ffffff;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 1rem 1.5rem;
    border-radius: 0.4375rem;
    line-height: 1.4rem;
    letter-spacing: 0.00875rem;
    height: 3.375rem;
    transition: all 0.2s ease;
  }

  .casestudies__btn:hover {
    background-color: #1C1AE4;
    border-color: #1C1AE4;
    color: #ffffff;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .casestudies__title {
      margin-bottom: 20px;
    }

  .casestudies {
    padding: 2.5rem 0;
  }

    .casestudies .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .casestudies__col-title,
    .casestudies__col-content {
      flex: 0 0 100%;
      max-width: 100%;
    }

    .casestudies__col-content {
      margin-left: 0;
    }

    .casestudies__cards {
      width: 100%;
    }
  }

  @media (width<=768px) {
    .casestudies__cards {
      flex-direction: column;
      max-width: 460px;
    }

    .casestudy-card {
      max-width: 100%;
    }

    .casestudy-card__image img {
      height: 180px;
    }
  }
</style>