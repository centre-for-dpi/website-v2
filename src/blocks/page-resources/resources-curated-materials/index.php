<section class="redlof-block res-curated">
  <div class="container">
    <div class="row">

      <!-- Left Column -->
      <div class="col-lg-3 res-curated__left">
        <div class="res-curated__icon">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-1.svg'); ?>" alt="CDPI" loading="lazy" />
        </div>
        <h2 class="res-curated__title">Curated materials</h2>
        <p class="res-curated__desc">Want to learn more about Digital Public Infrastructure?</p>
      </div>

      <!-- Right Column: 2x2 card grid -->
      <div class="col-lg-9 res-curated__right">
        <div class="row g-4">

          <!-- Card 1: Thought Leadership -->
          <div class="col-md-6">
            <a href="/thought-leadership" class="res-curated__card">
              <div class="res-curated__card-img">
                <img src="<?php echo Helper::getImagePath('images/resources/thought-leadership.jpeg'); ?>" alt="Thought Leadership" loading="lazy" />
              </div>
              <div class="res-curated__card-body">
                <h3 class="res-curated__card-category">Thought-Leadership</h3>
                <p class="res-curated__card-desc">DPI VC, DPI-AI, DPI x Child protection</p>
                <span class="res-curated__learn-more">
                  Learn more
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.5 7H11.5M11.5 7L7.5 3M11.5 7L7.5 11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
              </div>
            </a>
          </div>

          <!-- Card 2: Case Studies -->
          <div class="col-md-6">
            <a href="https://www.youtube.com/@CentreforDPI" target="_blank" class="res-curated__card">
              <div class="res-curated__card-img">
                <img src="<?php echo Helper::getImagePath('images/resources/masterclasses.jpeg'); ?>" alt="Masterclasses" loading="lazy" />
              </div>
              <div class="res-curated__card-body">
                <h3 class="res-curated__card-category">Masterclasses</h3>
                <p class="res-curated__card-desc">40+ videos from DPI builders</p>
                <span class="res-curated__learn-more">
                  Learn more
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.5 7H11.5M11.5 7L7.5 3M11.5 7L7.5 11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
              </div>
            </a>
          </div>

          <!-- Card 3: Tech Implementation Articles -->
          <div class="col-md-6">
            <a href="https://docs.cdpi.dev/" target="_blank" class="res-curated__card">
              <div class="res-curated__card-img">
                <img src="<?php echo Helper::getImagePath('images/resources/tech-implementation.jpeg'); ?>" alt="Tech Implementation Articles" loading="lazy" />
              </div>
              <div class="res-curated__card-body">
                <h3 class="res-curated__card-category">Tech Implementation Articles</h3>
                <p class="res-curated__card-desc">150+ bite-sized 101s curate: Implementation Guidance, Mythbusters, Sectoral implementation, Technical Specification and more..</p>
                <span class="res-curated__learn-more">
                  Learn more
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.5 7H11.5M11.5 7L7.5 3M11.5 7L7.5 11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
              </div>
            </a>
          </div>

          <!-- Card 4: Masterclasses -->
          <div class="col-md-6">
            <a href="/casestudies" class="res-curated__card">
              <div class="res-curated__card-img">
                <img src="<?php echo Helper::getImagePath('images/resources/casestudies.jpeg'); ?>" alt="Case Studies" loading="lazy" />
              </div>
              <div class="res-curated__card-body">
                <h3 class="res-curated__card-category">Case Studies</h3>
                <p class="res-curated__card-desc">Learn from DPI deployments in the Global South with deep insights and first-person government accounts</p>
                <span class="res-curated__learn-more">
                  Learn more
                  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.5 7H11.5M11.5 7L7.5 3M11.5 7L7.5 11" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                </span>
              </div>
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<style>
  .res-curated {
    background-color: #ffffff;
    padding: 120px 0;
  }

  /* Right column – cards align right */
  .res-curated__right .col-md-6 {
    display: flex;
    justify-content: flex-end;
  }

  /* Left column */
  .res-curated__left {
    padding-right: 32px;
  }

  .res-curated__icon {
    width: 32px;
    height: 32px;
    margin-bottom: 40px;
  }

  .res-curated__icon img {
    width: 100%;
    height: auto;
    object-fit: contain;
  }

  .res-curated__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32.04px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 0 0 20px 0;
    max-width: 370px;
  }

  .res-curated__desc {
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 15px;
    font-weight: 400;
    line-height: 170%;
    color: #5E6979;
    margin: 0;
    max-width: 370px;
  }

  /* Card – Figma Frame 37761 */
  .res-curated__card {
    display: flex;
    flex-direction: column;
    width: 392px;
    height: 470px;
    max-width: 100%;
    box-sizing: border-box;
    border: 1px solid #E3E0E0;
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    gap: 0;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .res-curated__card:hover {
    border-color: #6564DB;
    box-shadow: 0 4px 16px rgba(101, 100, 219, 0.1);
  }

  .res-curated__card-img {
    width: 100%;
    height: 180px;
    overflow: hidden;
    flex-shrink: 0;
  }

  .res-curated__card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .res-curated__card-body {
    padding: 32px;
    margin-bottom: 32px;
    display: flex;
    flex-direction: column;
    gap: 20px;
    flex: 1;
  }

  .res-curated__card-category {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 20px;
    line-height: 170%;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #0F0F0F;
    margin: 0;
  }

  .res-curated__card-desc {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #5E6979;
    margin: 0;
    flex: 1;
  }

  .res-curated__learn-more {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    font-weight: 500;
    color: #6564DB;
    margin: 0;
    transition: gap 0.2s ease;
  }

  .res-curated__learn-more svg {
    color: #6564DB;
  }

  .res-curated__card:hover .res-curated__learn-more {
    gap: 12px;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .res-curated__left {
      padding-right: 15px;
      margin-bottom: 40px;
    }
  }

  @media (max-width: 767px) {
    .res-curated {
      padding: 60px 0;
    }

    .res-curated .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .res-curated__title {
      font-size: 28px;
    }

    .res-curated__card {
      width: 100%;
      height: auto;
      min-height: 470px;
    }

    .res-curated__card-img {
      height: 160px;
    }
  }
</style>
