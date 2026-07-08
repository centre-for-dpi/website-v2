<section class="redlof-block our-approach-hero">
  <div class="container">
    <div class="row align-items-center ">
      <!-- Hero Text -->
      <div class="col-md-7 col-sm-5 mb-4 mb-lg-0  text-left our-approach-hero__content">
        <!-- Tagline -->
        <div class="our-approach-hero__tagline mb-3 text-uppercase">Transforming Regions</div>
        <!-- Main Title -->
        <h1 class="our-approach-hero__title text-uppercase">DaaS</h1>
      </div>

      <!-- Hero Map -->
      <div class=" col text-md-end text-center our-approach-hero__map ">
        <img src="<?php echo Helper::getImagePath('patterns/map-pattern-1.svg'); ?>" alt="Latin America & Caribbean Map"
          loading="lazy" />
      </div>
    </div>
  </div>
</section>

<style>
  .our-approach-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 102px 90px 0px 361px;
    position: relative;
    overflow: hidden;
  }

  .our-approach-hero__tagline {
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    color: transparent;
    background-clip: text;
    font-size: 12px;
    font-weight: 500;
    line-height: 170%;
    letter-spacing: 1.2px;
  }

  .our-approach-hero__content {
    padding: 59px 0px;
  }

  .our-approach-hero__title {
    font-size: 48px;
    font-weight: 500;
    color: #0F0F0F;
    line-height: auto;
    letter-spacing: 8%;
  }

  .our-approach-hero__map {
    width: 258px;
    height: 306px;
  }


  /* Responsive */
  @media (width<=1072px) {
    .our-approach-hero {
      padding: 102px 50px 0px 160px;
    }

    .our-approach-hero__title {
      font-size: 36px;
    }

    .our-approach-hero__map {
      width: 25%;
    }
  }

  @media (width<768px) {
    .our-approach-hero {
      padding: 102px 70px 0px;
    }

    .our-approach-hero__title {
      font-size: 28px;
    }

    .our-approach-hero__tagline {
      font-size: 11px;
    }
  }

  @media (width<=575px) {
    .our-approach-hero {
      padding: 102px 50px 0px 50px;
    }

    .our-approach-hero__title {
      font-size: 24px;
    }

  }
</style>