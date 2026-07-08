<?php
/**
 * Resources CTA – card with heading, subtext, description and "Explore the Resource Library" button.
 */
$resources_cta_url = class_exists('Helper') ? Helper::getPageUrl('resources', true) : '#';
?>
<section class="redlof-block resources-cta">
  <div class="resources-cta__inner">
    <div class="resources-cta__card">
      <div class="resources-cta__row">
        <div class="resources-cta__left">
          <h2 class="resources-cta__title">Resources</h2>
          <p class="resources-cta__subtitle">A curated collection from DPI builders</p>
          <span class="resources-cta__arrow" aria-hidden="true">
            <!-- <svg class="resources-cta__arrow-icon" width="56" height="16" viewBox="0 0 56 16" fill="none" xmlns="http://www.w3.org/2000/svg" role="presentation" focusable="false">
              <path d="M1 8H53" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M47 2L53 8L47 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg> -->
          </span>
        </div>
        <div class="resources-cta__right">
          <p class="resources-cta__desc">Equipping global builders with the frameworks and technical blueprints needed to scale inclusive DPI. From strategic white papers to bite-sized implementation guides, find everything you need to start.</p>
          <a href="<?php echo esc_url($resources_cta_url); ?>" class="resources-cta__btn">
            <span>Explore the Resource Library</span>
            <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .resources-cta {
    background: #fff;
    padding: 140px 0;
    overflow-x: hidden;
  }

  .resources-cta__inner {
    max-width: 1260px;
    margin: 0 auto;
    padding: 0 40px;
    box-sizing: border-box;
  }

  .resources-cta__card {
    background: #EDECFF;
    border-radius: 12px;
    padding: 72px 80px;
    box-sizing: border-box;
    max-width: 100%;
  }

  .resources-cta__row {
    display: flex;
    flex-wrap: wrap;
    gap: 48px 80px;
    align-items: flex-start;
    min-width: 0;
  }

  .resources-cta__left {
    flex: 0 0 390px;
    max-width: 100%;
    min-width: 0;
  }

  .resources-cta__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 52px;
    line-height: 125%;
    letter-spacing: -0.02em;
    color: #0F0F0F;
    margin: 0 0 12px 0;
  }

  .resources-cta__subtitle {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
    margin: 0 0 66px 0;
  }

  .resources-cta__arrow {
    display: inline-block;
    color: #0F0F0F;
    opacity: 0.6;
  }

  .resources-cta__arrow-icon {
    display: block;
    width: 56px;
    height: 16px;
  }

  .resources-cta__right {
    flex: 1 1 0;
    min-width: 280px;
    min-width: 0;
  }

  .resources-cta__desc {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #5E6979;
    margin: 0 0 82px 0;
  }

  .resources-cta__btn {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 18px 24px;
    background: #4B4AEA;
    color: #fff;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.5;
    text-decoration: none;
    border-radius: 7px;
    transition: background 0.2s ease;
  }

  .resources-cta__btn:hover {
    background: #3d3cd4;
    color: #fff;
  }

  @media (max-width: 991px) {
    .resources-cta {
      padding: 80px 24px;
    }

    .resources-cta__card {
      padding: 48px 32px;
    }

    .resources-cta__row {
      flex-direction: column;
      gap: 0px;
    }

    .resources-cta__left {
      flex: 0 0 auto;
    }

    .resources-cta__title {
      font-size: 32px;
    }

    .resources-cta__subtitle {
      margin-bottom: 28px;
    }
  }

  @media (max-width: 575px) {
    .resources-cta {
      padding: 60px 1.5rem;
      width: 100%;
      box-sizing: border-box;
    }

    .resources-cta__inner {
      width: 100%;
      max-width: 100%;
      padding: 0;
    }

    .resources-cta__card {
      padding: 32px 1.5rem;
      width: 100%;
      max-width: 100%;
    }

    .resources-cta__row {
      width: 100%;
      gap: 12px;
    }

    .resources-cta__right {
      min-width: 0;
      width: 100%;
    }

    .resources-cta__desc {
      max-width: 100%;
      margin-bottom: 28px;
    }

    .resources-cta__title {
      font-size: 28px;
    }

    .resources-cta__subtitle {
      margin-bottom: 0px;
    }

    .resources-cta__btn {
      width: 100%;
      max-width: 100%;
      justify-content: center;
      box-sizing: border-box;
    }
  }
</style>
