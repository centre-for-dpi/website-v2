<section class="redlof-block voices-we-serve-hero">
  <div class="container">
    <div class="row align-items-center ">
      <!-- Hero Text -->
      <div class="col-md-7 col-sm-5 mb-4 mb-lg-0  text-left voices-we-serve-hero__content">
        <!-- Tagline -->
        <div class="voices-we-serve-hero__tagline mb-3 text-uppercase">Voices we serve</div>
        <!-- Main Title -->
        <h1 class="voices-we-serve-hero__title text-uppercase">Education</h1>
      </div>

      <!-- Hero Map -->
      <div class=" col text-md-end text-center voices-we-serve-hero__map ">
        <img src="<?php echo Helper::getImagePath('patterns/map-pattern-1.svg'); ?>" alt="Latin America & Caribbean Map"
          loading="lazy" />
      </div>
    </div>
  </div>
</section>

<style>
  .voices-we-serve-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 102px 90px 0px 361px;
    position: relative;
    overflow: hidden;
  }

  .voices-we-serve-hero__tagline {
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    color: transparent;
    background-clip: text;
    font-size: 12px;
    font-weight: 500;
    line-height: 170%;
    letter-spacing: 1.2px;
  }

  .voices-we-serve-hero__content {
    padding: 59px 0px;
  }

  .voices-we-serve-hero__title {
    font-size: 48px;
    font-weight: 500;
    color: #0F0F0F;
    line-height: auto;
    letter-spacing: 8%;
  }

  .voices-we-serve-hero__map {
    width: 258px;
    height: 306px;
  }


  /* Responsive */
  @media (width<=1072px) {
    .voices-we-serve-hero {
      padding: 102px 50px 0px 160px;
    }

    .voices-we-serve-hero__title {
      font-size: 36px;
    }

    .voices-we-serve-hero__map {
      width: 25%;
    }
  }

  @media (width<768px) {
    .voices-we-serve-hero {
      padding: 102px 70px 0px;
    }

    .voices-we-serve-hero__title {
      font-size: 28px;
    }

    .voices-we-serve-hero__tagline {
      font-size: 11px;
    }
  }

  @media (width<=575px) {
    .voices-we-serve-hero {
      padding: 102px 50px 0px 50px;
    }

    .voices-we-serve-hero__title {
      font-size: 24px;
    }

  }
</style>