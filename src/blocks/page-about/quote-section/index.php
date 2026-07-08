<section class="quote-section">
  <div class="quote-section__wrapper">
    <div class="container quote-section__container">
      <div class="quote-section__layout">
        <div class="quote-section__content">
          <div class="quote-section__inner">
            <div class="row align-items-start">
              <div class="col-lg-12">
                <div class="quote-section__quote">
                  <p class="quote-section__text">
                    Digital Public Infrastructure is about enabling a technology-led model for growth that is collaborative, equitable, and democratises opportunity at population scale.
                  </p>
                  <p class="quote-section__text-highlight">The Centre for DPI can help countries move faster in this journey.</p>
                  <div class="quote-section__author">
                    <h4 class="quote-section__name">Nandan Nilekani</h4>
                  </div>
                </div>
              </div>
            </div>    
          </div>
        </div>
      </div>
    </div>

    <div class="quote-section__image">
      <img src="<?php echo Helper::getImagePath('images/about/nandan-nilekani.png'); ?>"
        alt="Advisor portrait" loading="lazy" />
    </div>
  </div>
</section>

<style>
  .quote-section {
    position: relative;
    background-color: #0D0C36;
    overflow: hidden;
    height: 560px;
  }

  .quote-section__wrapper {
    height: 100%;
  }

  .quote-section__layout {
    display: flex;
    height: 100%;
  }

  .quote-section__content {
    flex: 0 0 60%;
    display: flex;
    align-items: center;
    padding: 8.5rem 0 3.75rem;
    position: relative;
    z-index: 2;
  }

  .quote-section__eyebrow {
    display: inline-block;
  }

  .quote-section__eyebrow span {
    font-weight: 500;
    font-size: 20px;
    line-height: 170%;
    letter-spacing: 8%;
    color: #ffffff;
    display: block;
    padding-bottom: 16px;
    position: relative;
  }

  .quote-section__quote {
    width: 100%;
    max-width: 100%;
  }

  .quote-section__text {
    font-family: Lora, serif;
    font-weight: 400;
    font-style: normal;
    font-size: 20px;
    line-height: 150%;
    letter-spacing: 0;
    color: #FFFFFF;
    margin-bottom: 0;
    max-width: 570px;
  }

  .quote-section__text-highlight {
    font-family: Lora, serif;
    font-weight: 700;
    font-style: normal;
    font-size: 20px;
    line-height: 150%;
    letter-spacing: 0;
    color: #FFFFFF;
    margin: 32px 0 32px;
    max-width: 570px;

  }

  .quote-section__author {
    margin-bottom: 0;
  }

  .quote-section__name {
    font-size: 16px;
    font-weight: 600;
    line-height: 170%;
    letter-spacing: 2%;
    color: #FFFFFF;
    margin-bottom: 8px;
  }

  .quote-section__title {
    font-size: 11px;
    font-weight: 600;
    line-height: 170%;
    letter-spacing: 1.2px;
    color: #9ca3af;
    margin: 0;
  }

  .quote-section__nav {
    display: flex;
    justify-content: flex-start;
    gap: 4px;
    margin: 80px 0 0;
  }

  .quote-section__nav span {
    width: 32px;
    height: 1px;
    background-color: rgba(255, 255, 255, 0.33);
    display: block;
  }

  .quote-section__nav span.active {
    background-color: #ffffff;
  }

  .quote-section__image {
    position: absolute;
    top: 0;
    right: 0;
    width: 40%;
    height: 100%;
    overflow: hidden;
    background: transparent;
    z-index: 1;
  }

  .quote-section__image::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, #0D0C36 10%, rgba(13, 12, 54, 0.79) 30%, rgba(13, 12, 54, 0) 100%);
    z-index: 2;
    pointer-events: none;
  }

  .quote-section__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    filter: grayscale(100%);
  }

  @media (max-width: 1199px) {
    .quote-section__container {
      padding-left: 40px;
    }

    .quote-section__text {
      font-size: 18px;
    }
  }

  @media (max-width: 991px) {
    .quote-section {
      height: auto;
    }

    .quote-section__layout {
      flex-direction: column-reverse;
      height: auto;
    }

    .quote-section__content {
      flex: 1;
      padding: 40px 0;
    }

    .quote-section__container {
      padding-left: 15px;
      padding-right: 15px;
    }

    .quote-section__image {
      position: relative;
      top: auto;
      right: auto;
      flex: none;
      width: 100%;
      height: auto;
      z-index: 1;
    }

    .quote-section__image::before {
      width: 100%;
      height: 30%;
      bottom: 0;
      top: auto;
      background: linear-gradient(0deg, #0f0f1a 0%, transparent 100%);
    }

    .quote-section__image img {
      height: auto;
      min-height: 280px;
      max-height: 320px;
    }

    .quote-section__eyebrow {
      margin-bottom: 20px;
    }

    .quote-section__quote {
      max-width: 100%;
    }
  }

  @media (max-width: 991px) {
    .quote-section {
      height: 55.1875rem;
    }

    .quote-section__layout {
      position: relative;
      height: 100%;
    }

    .quote-section__container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      height: 100%;
    }

    .quote-section__content {
      padding: 21.96875rem 0 0;
      position: relative;
      z-index: 2;
    }

    .quote-section__eyebrow span {
      font-size: 1.25rem;
      line-height: 2.125rem;
      letter-spacing: 0.1rem;
    }

    .quote-section__quote {
      max-width: 19.9375rem;
    }

    .quote-section__text {
      font-family: "Lora", serif;
      font-style: normal;
      font-size: 1.25rem;
      line-height: 1.875rem;
      letter-spacing: 0;
      margin-bottom: 2.5rem;
    }

    .quote-section__name {
      font-size: 1.0625rem;
      line-height: 1.80625rem;
      letter-spacing: 0.02125rem;
      margin-bottom: 0.5rem;
    }

    .quote-section__title {
      font-size: 0.6875rem;
      line-height: 1.16875rem;
      letter-spacing: 0.075rem;
    }

    .quote-section__nav {
      margin-top: 2rem;
    }

    .quote-section__image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 25.8125rem;
      z-index: 1;
    }

    .quote-section__image img {
      height: 100%;
      min-height: 0;
      max-height: none;
    }

    .quote-section__image::before {
      width: 100%;
      height: 100%;
      top: 0;
      bottom: auto;
      background: linear-gradient(180deg, rgba(13, 12, 54, 0) 0%, rgba(13, 12, 54, 0.79) 67.586%, rgba(13, 12, 54, 1) 100%);
    }
  }
</style>