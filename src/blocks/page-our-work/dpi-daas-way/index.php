<?php
/**
 * DPI DaaS way – horizontal card: image left, content + CTA right.
 */
?>
<section class="redlof-block dpi-daas-way">
  <div class="dpi-daas-way__wrap">
    <div class="dpi-daas-way__inner">
      <div class="dpi-daas-way__media">
        <img src="<?php echo esc_url( Helper::getImagePath('images/our-work/dpi-daas-way.jpg') ); ?>" alt="Countries building DPI the DaaS way" loading="lazy" />
      </div>
      <div class="dpi-daas-way__content">
        <p class="dpi-daas-way__text">
          <span class="dpi-daas-way__text-accent">8+ countries</span> <span class="dpi-daas-way__text-rest">are building DPI the DaaS way.</span>
        </p>
        <a href="<?php echo esc_url( Helper::getPageUrl('daas') ); ?>" class="dpi-daas-way__btn">
          <span>Learn more</span>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
  <div class="dpi-daas-way__separator"></div>
</section>

<style>
  .dpi-daas-way {
    background: #ffffff;
    padding: 0;
  }

  .dpi-daas-way__separator {
    margin-top: 140px;
    height: 1px;
    background: linear-gradient(90deg, #d6e1f1 0%, #6564DB 50%, #d6e1f1 100%);
  }

  .dpi-daas-way__inner {
    max-width: 1260px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    border-radius: 10px;
    overflow: hidden;
    padding: 0 40px;
  }

  .dpi-daas-way__media {
    flex: 0 0 400px;
    width: 400px;
    height: 355px;
    overflow: hidden;
    border-radius: 10px 0 0 10px;
  }

  .dpi-daas-way__media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .dpi-daas-way__content {
    flex: 1 1 0;
    min-width: 280px;
    background-color: #EDECFE;
    border-radius: 0 10px 10px 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4rem 4.5rem;
  }

  .dpi-daas-way__text {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32px;
    line-height: 160%;
    letter-spacing: 0.02em;
    margin: 0 0 2.5rem 0;
  }

  .dpi-daas-way__text-accent {
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .dpi-daas-way__text-rest {
    color: #0F0F0F;
  }

  .dpi-daas-way__btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 24px;
    background-color: #4948DB;
    color: #ffffff;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 16px;
    line-height: 1.5;
    text-decoration: none;
    border-radius: 8px;
    width: fit-content;
    transition: background-color 0.2s ease;
  }

  .dpi-daas-way__btn:hover {
    background-color: #3937c4;
    color: #ffffff;
  }

  @media (max-width: 991px) {
    .dpi-daas-way__media {
      flex: 0 0 100%;
      width: 100%;
      height: 280px;
      border-radius: 10px 10px 0 0;
    }

    .dpi-daas-way__content {
      padding: 3rem 2rem;
      border-radius: 0 0 10px 10px;
    }

    .dpi-daas-way__text {
      font-size: 26px;
    }
  }

  @media (max-width: 575px) {
    .dpi-daas-way__wrap {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      box-sizing: border-box;
    }

    .dpi-daas-way__inner {
      margin: 0;
    }

    .dpi-daas-way__media {
      height: 220px;
    }

    .dpi-daas-way__content {
      padding: 2rem 1.5rem;
    }

    .dpi-daas-way__text {
      font-size: 22px;
      margin-bottom: 1.5rem;
    }
  }
</style>
