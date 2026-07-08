<?php
/**
 * Talent-led organisation section – static content block.
 * Layout from Figma: white section, 90px horizontal margin, 148px top / 80px bottom padding,
 * icon + headline + body, 24px spacing, content max-width 1016px.
 */
?>
<section class="redlof-block talent-led-organisation">
  <div class="talent-led-organisation__inner">
    <div class="talent-led-organisation__content">
      <div class="talent-led-organisation__icon">
        <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-5.svg'); ?>" alt="" width="32" height="32" loading="lazy" />
      </div>
      <h2 class="talent-led-organisation__title">We are a talent led organization</h2>
      <p class="talent-led-organisation__body">
        If you share our vision and have the motivation to drive DPI at a global scale,
        you can reach out to us on <a class="talent-led-organisation__email" href="mailto:careers@cdpi.dev">careers@cdpi.dev</a>
      </p>
    </div>
  </div>
</section>

<style>
  .talent-led-organisation {
    background-color: #ffffff;
    padding: 9.25rem 5.625rem 5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 405px;
  }

  .talent-led-organisation__inner {
    max-width: 1260px;
    width: 100%;
    margin: 0 auto;
  }

  .talent-led-organisation__content {
    max-width: 63.5rem;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    text-align: center;
  }

  .talent-led-organisation__icon {
    width: 2rem;
    height: 2rem;
    flex-shrink: 0;
  }

  .talent-led-organisation__icon img {
    width: 100%;
    height: 100%;
    display: block;
  }

  .talent-led-organisation__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 2rem;
    line-height: 1.4;
    letter-spacing: 0;
    color: #101828;
    margin: 0;
  }

  .talent-led-organisation__body {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-style: normal;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    text-align: center;
    color: #5E6979;
    margin: 0;
    max-width: 33.5rem;
  }

  .talent-led-organisation__body .talent-led-organisation__email {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-style: normal;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    text-align: center;
    text-decoration: underline;
    text-decoration-style: solid;
    text-underline-offset: 2px;
    text-decoration-thickness: 1px;
    text-decoration-skip-ink: auto;
    color: #4948E1;
  }

  @media (max-width: 991px) {
    .talent-led-organisation {
      padding: 4rem 1.5rem 3rem;
      min-height: 320px;
    }

    .talent-led-organisation__title {
      font-size: 1.5rem;
    }

    .talent-led-organisation__body {
      font-size: 0.875rem;
    }
  }
</style>
