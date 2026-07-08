<?php
/**
 * Our culture code section – About page.
 * Left: static text. Right: image carousel reusing the country slider pattern.
 */
?>

<section class="redlof-block our-culture-code">
  <div class="container">
    <div class="row align-items-start g-0 our-culture-code__row">
      <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
        <div class="our-culture-code__intro">
          <h3 class="team-section__group-label text-uppercase">Our culture code</h3>
        </div>
      </div>

      <div class="col-lg-9 col-md-12">
        <div class="our-culture-code__slider">
          <div class="our-culture-slider">
            <?php
            $culture_slides = [
              'images/about/our-culture-code1.png',
              'images/about/our-culture-code2.png',
              'images/about/our-culture-code3.png',
              'images/about/our-culture-code4.png',
            ];
            ?>
            <?php foreach ($culture_slides as $image_path) : ?>
              <div class="our-culture-slide">
                <div class="our-culture-code-card">
                  <div class="our-culture-code-card__image">
                    <img src="<?php echo Helper::getImagePath($image_path); ?>" alt="Our culture at CDPI" loading="lazy" />
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

         <!-- Navigation Arrows -->
         <div class="culture_code_navigation d-flex gap-2">
            <button class="culture-code-nav-btn culture-code-nav-prev" aria-label="Previous">
              <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button class="culture-code-nav-btn culture-code-nav-next" aria-label="Next">
              <i class="fa-solid fa-arrow-right"></i>
            </button>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .our-culture-code {
    padding: 5rem 0 7.25rem; /* 80px top, 116px bottom */
    background-color: #ffffff;
  }

  .our-culture-code__intro {
    color: #0f0f0f;
  }

  .our-culture-code__slider {
    display: flex;
    flex-direction: column;
    max-width: 52rem;
    margin-left: auto;
  }

  .our-culture-code-card {
    background: #FFFFFF;
    border: 0;
    padding: 0;
    max-width: 100%;
    margin-left: 0;
  }

  .our-culture-code-card__image {
    width: 100%;
    overflow: hidden;
  }

  .our-culture-code-card__image img {
    width: 100%;
    height: auto;
    display: block;
  }

  .culture_code_navigation {
    margin-top: 20px;
    margin-right: 0;
    width: 100%;
    justify-content: flex-end;
  }

  .culture-code-nav-btn {
    width: 40px;
    height: 40px;
    border-radius: 5px;
    border: 1px solid #D6E1F1;
    background: #ffffff;
    color: #5E6979;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 8px;
    box-shadow: none;
  }

  .culture-code-nav-btn:hover {
    border-color: #4f46e5;
    color: #4f46e5;
  }

  .culture-code-nav-btn.slick-disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  .our-culture-slider {
    width: 100%;
  }

  .our-culture-slider .slick-track {
    display: flex;
  }

  .our-culture-slider .slick-slide {
    height: auto;
  }

  .our-culture-slider .slick-slide>div {
    height: 100%;
  }

  .our-culture-slide {
    height: 100%;
  }


  @media (max-width: 1199px) {
    .our-culture-code__row {
      margin-left: 1.5rem;
      margin-right: 1.5rem;
    }

    .culture_code_navigation {
      justify-content: left;
      margin-top: 1.5rem;
      margin-right: 0;
      margin-left: 580px;
    }

    .culture-code-nav-btn {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.3125rem;
    }
  }

  @media (max-width: 767px) {
    .our-culture-code {
      padding: 4.5rem 0;
    }

    .culture_code_navigation {
      justify-content: center;
      margin-right: 0;
    }
  }
</style>

<script>
  (function initOurCultureSlider() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.slick === 'undefined') {
      setTimeout(initOurCultureSlider, 100);
      return;
    }

    jQuery(function ($) {
      var $slider = $('.our-culture-slider');
      if (!$slider.length || $slider.hasClass('slick-initialized')) {
        return;
      }

      $slider.slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        infinite: true,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 10000,
        pauseOnHover: false,
        prevArrow: $('.culture-code-nav-prev'),
        nextArrow: $('.culture-code-nav-next')
      });
    });
  })();
</script>

