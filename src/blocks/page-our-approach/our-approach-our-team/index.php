<section class="redlof-block our-team py-5 py-lg-6">
  <div class="container">
    <!-- Header Row: Title on Left, Intro on Right -->
    <div class="row mb-5">
      <div class="col-lg-2 mb-4">
        <h2 class="our-team__title text-uppercase">Our Team</h2>
      </div>
      <div class="col-lg-7 ms-lg-5">
        <p class="our-team__paragraph">
          Our Latin America team brings together a unique blend of <strong>digital policy experts, technologists, and
            practitioners</strong> who understand both the opportunities and challenges of advancing DPI in the region.
        </p>
        <p class="our-team__paragraph">
          Many of our members have worked with national and regional governments in countries such as Mexico, Brazil,
          Argentina, and Chile, contributing to digital transformation initiatives that strengthen trust and inclusion.
          Alongside this, our team includes researchers and ecosystem builders who have collaborated with international
          organisations, development banks, and local communities to co-create digital strategies grounded in openness
          and equity.
        </p>
        <p class="our-team__paragraph">
          What unites our Latin America team is a <strong>commitment to people-first digital systems</strong>—ensuring
          that DPI isn't just a technical framework, but a foundation for inclusive growth, innovation, and resilience
          across the region.
        </p>
      </div>
    </div>

    <!-- Team Members Grid -->
    <div class="row our-team__row">
      <!-- Member 1: Daniel Abadie -->
      <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
        <div class="our-team__card mx-auto">
          <div class="our-team__image">
            <img src="<?php echo Helper::getImagePath('team/daniel-abadie.png'); ?>" alt="Daniel Abadie"
              loading="lazy" />
          </div>
          <h4 class="our-team__name">Daniel Abadie</h4>
          <span class="our-team__role text-uppercase">HEAD OF TECHNOLOGY & PARTNERSHIPS</span>
          <p class="our-team__desc">Former Head of Digital Government, Argentina</p>
          <a href="https://www.linkedin.com/in/danielabadie/" class="our-team__link" target="_blank"
            rel="noopener">LinkedIn <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
        </div>
      </div>

      <!-- Member 2: Ysaias Alvarez Castillo -->
      <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
        <div class="our-team__card mx-auto">
          <div class="our-team__image">
            <img src="<?php echo Helper::getImagePath('team/daniel-abadie.png'); ?>" alt="Ysaias Alvarez Castillo"
              loading="lazy" />
          </div>
          <h4 class="our-team__name">Ysaias Alvarez Castillo</h4>
          <span class="our-team__role text-uppercase">TECHNICAL ARCHITECT</span>
          <p class="our-team__desc">Former Head of Digital Government, Argentina</p>
          <a href="#" class="our-team__link" target="_blank" rel="noopener">LinkedIn <i
              class="fa-solid fa-arrow-up-right-from-square"></i></a>
        </div>
      </div>

      <!-- Member 3: Manuel Aguilera -->
      <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
        <div class="our-team__card mx-auto">
          <div class="our-team__image">
            <img src="<?php echo Helper::getImagePath('team/daniel-abadie.png'); ?>" alt="Manuel Aguilera"
              loading="lazy" />
          </div>
          <h4 class="our-team__name">Manuel Aguilera</h4>
          <span class="our-team__role text-uppercase">LAC REGIONAL LEAD</span>
          <p class="our-team__desc">Latin America & Caribbean</p>
          <a href="#" class="our-team__link" target="_blank" rel="noopener">LinkedIn <i
              class="fa-solid fa-arrow-up-right-from-square"></i></a>
        </div>
      </div>

      <!-- Member 4: Ana Bermudez -->
      <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">
        <div class="our-team__card mx-auto">
          <div class="our-team__image">
            <img src="<?php echo Helper::getImagePath('team/daniel-abadie.png'); ?>" alt="Ana Bermudez"
              loading="lazy" />
          </div>
          <h4 class="our-team__name">Ana Bermudez</h4>
          <span class="our-team__role text-uppercase">LAC COUNTRY OPS OFFICER</span>
          <p class="our-team__desc">Colombia</p>
          <a href="#" class="our-team__link" target="_blank" rel="noopener">LinkedIn <i
              class="fa-solid fa-arrow-up-right-from-square"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .our-team {
    background-color: #ffffff;
  }

  .our-team__title {
    font-size: 20px;
    font-weight: 500;
    color: #0F0F0F;
    line-height: 170%;
    letter-spacing: 8%;
    margin: 0;
  }

  .our-team__paragraph {
    font-size: 17px;
    font-weight: 400;
    line-height: 170%;
    letter-spacing: 2%;

    color: #5E6979;
    margin-bottom: 40px;
  }

  .our-team__paragraph:last-child {
    margin-bottom: 14px;
  }

  .our-team__paragraph strong {
    color: #0F0F0F;
    font-weight: 500;
    font-style: Medium;
    font-size: 17px;
    line-height: 170%;
    letter-spacing: 2%;
  }

  /* Team Card */

  .our-team__row {
    padding: 0px 50px 40px 265px;
  }

  .our-team__card {
    max-width: 200px;
  }

  .our-team__image {
    width: 100%;
    height: auto;
    overflow: hidden;
    margin-bottom: 16px;
    border-radius: 10px;
  }

  .our-team__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 10px;
  }

  .our-team__name {
    font-size: 1.1rem;
    font-weight: 500;
    color: #1a1a2e;
    margin: 0 0 4px 0;
  }

  .our-team__role {
    font-size: 0.7rem;
    font-weight: 600;
    color: #0F0F0F;
    letter-spacing: 0.05em;
    display: block;
    margin-bottom: 12px;
  }

  .our-team__desc {
    font-size: 0.85rem;
    font-weight: 400;
    color: #6b7280;
    line-height: 1.5;
    margin: 0 0 12px 0;
  }

  .our-team__link {
    font-size: 0.85rem;
    font-weight: 400;
    color: #6564DB;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.3s ease;
  }

  .our-team__link:hover {
    color: #5453c7;
    text-decoration: underline;
  }

  .our-team__link i {
    font-size: 0.7rem;
  }

  /* Responsive */

  @media (width<1400px) {
    .our-team__row {
      padding-left: 238px;
    }
  }

  @media (width<1200px) {
    .our-team__row {
      padding-left: 205px;
    }
  }

  @media (width<=991px) {
    .our-team__row {
      padding-left: 0px;
      padding-right: 0px;
    }
  }

  @media (width<=767px) {
    .our-team__card {
      margin-bottom: 32px;
    }

    .our-team__image {
      max-width: 200px;
      height: auto;
    }
  }
</style>