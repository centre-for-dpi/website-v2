<section class="redlof-block res-assistant">
  <div class="container">
    <div class="row align-items-center">

      <!-- Left Column -->
      <div class="col-lg-5 res-assistant__left">
        <div class="res-assistant__icon mb-4">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-1.svg'); ?>" alt="CDPI" loading="lazy" />
        </div>
        <h2 class="res-assistant__title mb-4">
          Learn with the DPI AI assistant
        </h2>
        <p class="res-assistant__desc mb-5">
          Have questions about Digital Public Infrastructure?
        </p>
        <a 
          href="https://assistant.cdpi.dev/" 
          target="_blank" 
          class="res-assistant__cta btn btn-primary" 
          aria-label="Try the DPI Assistant" 
          style="display: inline-flex; align-items: center; gap: 14px; min-width: 264px; min-height: 60px; border-radius: 7px; background: #4B4AEA; box-shadow: none; border: none; justify-content: center;">
          Try the DPI Assistant
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-left: 8px;">
            <path d="M6 12H18M18 12L13.5 7.5M18 12L13.5 16.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </a>
      </div>

      <!-- Right Column -->
      <div class="col-lg-7 res-assistant__right">
        <img src="<?php echo Helper::getImagePath('images/resources/dpi-assistant-preview.png'); ?>" alt="DPI AI Assistant preview" class="res-assistant__preview-img" loading="lazy" />
      </div>

    </div>
  </div>
</section>

<style>
  .res-assistant {
    background-color: #ffffff;
    padding: 120px 0;
  }

  /* Left column */
  .res-assistant__left {
    padding-right: 48px;
  }

  .res-assistant__icon {
    width: 32px;
    height: 32px;
    margin-bottom: 40px;
  }

  .res-assistant__icon img {
    width: 100%;
    height: auto;
    object-fit: contain;
  }

  .res-assistant__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32.04px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 0 0 20px 0;
    max-width: 370px;
  }

  .res-assistant__desc {
    font-size: 15px;
    font-weight: 400;
    line-height: 170%;
    color: #5E6979;
    margin: 0 0 64px 0;
    max-width: 300px;
  }

  .res-assistant__cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background-color: #4B4AEA;
    color: #FFFFFF;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 16px;
    line-height: 160%;
    letter-spacing: 0.01em;
    text-align: center;
    padding: 14px 24px;
    border-radius: 7px;
    text-decoration: none;
    transition: background-color 0.2s ease;
  }

  .res-assistant__cta:hover {
    background-color: #3d3cd6;
    color: #FFFFFF;
  }

  /* Right column */
  .res-assistant__right {
    display: flex;
    align-items: center;
  }

  .res-assistant__preview-img {
    width: 100%;
    height: auto;
    display: block;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .res-assistant__left {
      padding-right: 15px;
      margin-bottom: 40px;
    }
  }

  @media (max-width: 767px) {
    .res-assistant {
      padding: 60px 0;
    }

    .res-assistant .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .res-assistant__title {
      font-size: 28px;
    }

    .res-assistant__card {
      padding: 24px 20px;
    }

    .res-assistant__card-title {
      font-size: 18px;
    }
  }
</style>
