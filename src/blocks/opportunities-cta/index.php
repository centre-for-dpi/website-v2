<section class="redlof-block opportunities-cta">
  <div class="container p-0 ">
    <div class="row g-0">
      <!-- Left: Image -->
      <div class="col-lg-4 col-md-5">
        <div class="opportunities-cta__image">
          <img src="<?php echo Helper::getImagePath('images/opportunities-cta.png'); ?>"
            alt="Transform challenges into opportunities" loading="lazy" />
        </div>
      </div>

      <!-- Right: Content -->
      <div class="col-lg-8 col-md-7">
        <div class="opportunities-cta__content">
          <h2 class="opportunities-cta__title">
            Transform all of your challenges into opportunities with us.
          </h2>
          <a href="/contact" class="opportunities-cta__btn">Reach out to us</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Decorative Pattern -->
  <div class="opportunities-cta__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-6.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
  .opportunities-cta {
    background-color: #EAEAFE;
    position: relative;
    overflow: hidden;
    padding: 116px 100px;
  }

  .opportunities-cta>.container {
    background-color: #ffffff;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    z-index: 1;
  }

  /* Image */
  .opportunities-cta__image {
    width: 100%;
    height: 100%;
  }

  .opportunities-cta__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  /* Content */
  .opportunities-cta__content {
    padding: 72px 64px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
  }

  .opportunities-cta__title {
    color: #0F0F0F;
    margin: 0 0 32px 0;
    font-weight: 300;
    font-size: 32px;
    line-height: 1.6;
    letter-spacing: 0.02em;
  }

  .opportunities-cta__btn {
    display: inline-block;
    font-weight: 400;
    font-size: 14px;
    line-height: 1.6;
    letter-spacing: 0.01em;
    color: #ffffff;
    background-color: #4948E1;
    padding: 16px 24px;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  .opportunities-cta__btn:hover {
    background-color: #4a3dd4;
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(89, 73, 233, 0.3);
  }

  /* Decorative Pattern */
  .opportunities-cta__pattern {
    position: absolute;
    right:-70px;
    top: 310px;
    pointer-events: none;
    z-index: 3;
  }

  .opportunities-cta__pattern img {
    width: 100%;
    height: auto;
  }

  /* Responsive  */

  @media (max-width: 1440px) {

    .opportunities-cta__title {
      font-size: 30px;
    }

    .opportunities-cta__content {
      padding: 56px 48px;
    }
  }

  @media (max-width: 1024px) {
    .opportunities-cta__title {
      font-size: 26px;
    }

    .opportunities-cta__content {
      padding: 48px 40px;
    }
  }

  @media (max-width: 992px) {
    .opportunities-cta {
      padding: 48px 24px;
    }

    .opportunities-cta__content {
      padding: 40px 32px;
      align-items: left;
      text-align: left;
    }

    .opportunities-cta__title {
      font-size: 24px;
      margin-bottom: 20px;
    }

    .opportunities-cta__btn {
      padding: 12px 20px;
      font-size: 13px;
    }
  }

  @media (max-width: 768px) {

    .opportunities-cta {
      padding: 24px 16px;
    }

    .opportunities-cta>.container {
      max-width: 100%;
      background-color: #ffffff;
      border-radius: 16px;
      padding: 0;
      overflow: hidden;
    }

    .opportunities-cta__image {
      width: 100%;
      height: auto;
      overflow: hidden;
      border-radius: 16px 16px 0 0;
    }

    .opportunities-cta__image img {
      width: 100%;
      height: auto;
      border-radius: 16px 16px 0 0;
      object-fit: cover;
      display: block;
      border-radius: 0;

    }

    .opportunities-cta__content {
      padding: 24px 20px 28px;
      align-items: flex-start;
      text-align: left;
    }

    .opportunities-cta__btn {
      width: 100%;
      text-align: center;
      padding: 14px 16px;
      border-radius: 10px;
    }

    .opportunities-cta__pattern {
      display: none;
    }
  }

  @media (max-width: 480px) {
    .opportunities-cta__title {
      font-size: 20px;
    }
  }
</style>