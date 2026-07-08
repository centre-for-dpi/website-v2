<?php
$testimonialHandler = new CustomPost('testimonial', null);

$testimonials = $testimonialHandler->getListOfPosts([
    'meta_fields',
    'thumbnail'
]);

?>


<section class="testimonials">
  <?php
  if (!empty($testimonials)) :
    foreach ($testimonials as $testimonial) :
      $country = $testimonial['meta_fields']['region'] ?? '';
      $text = $testimonial['meta_fields']['testimonial_text'] ?? '';
      $name = $testimonial['meta_fields']['person_name'] ?? '';
      $title = $testimonial['meta_fields']['person_designation'] ?? '';
    endforeach;
  else :
    $testimonials = [];
  endif;
  ?>

  <div class="testimonials__wrapper">
    <div class="container testimonials__container">
      <div class="testimonials__layout">
        <!-- Content Area -->
        <div class="testimonials__content">
          <div id="testimonialsCarousel" class="carousel slide testimonials__carousel" data-bs-ride="carousel">
            <div class="row align-items-start testimonials__country-row">
              <div class="col-lg-4 mb-4 mb-lg-0">
                <div class="testimonials__country text-uppercase">
                  <span>HEAR FROM OUR PARTNERS</span>
                </div>
              </div>
            </div>
            <div class="carousel-inner">
              <?php foreach ($testimonials as $index => $item) : ?>
                <div class="carousel-item testimonials__carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-interval="10000">
                  <div class="row align-items-start">
                    <div class="col-lg-7 offset-lg-4">
                      <div class="testimonials__quote">
                        <p class="testimonials__text">
                          <?php echo esc_html($item['meta_fields']['testimonial_text'] ?? ''); ?>
                        </p>
                      <div class="testimonials__author">
                        <h4 class="testimonials__name"><?php echo esc_html($item['meta_fields']['person_name'] ?? ''); ?></h4>
                        <p class="testimonials__title text-uppercase">
                          <?php echo esc_html($item['meta_fields']['person_designation'] ?? ''); ?>
                        </p>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="row align-items-start testimonials__nav-row">
              <div class="col-lg-7 offset-lg-4">
                <!-- Navigation Dots -->
                <div class="carousel-indicators testimonials__nav">
                  <?php for ($i = 0; $i < count($testimonials); $i++) : ?>
                    <button type="button" data-bs-target="#testimonialsCarousel"
                      data-bs-slide-to="<?php echo esc_attr($i); ?>"
                      class="<?php echo $i === 0 ? 'active' : ''; ?>"
                      aria-current="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                      aria-label="Slide <?php echo esc_attr($i + 1); ?>"
                      title="Slide <?php echo esc_attr($i + 1); ?>"></button>
                  <?php endfor; ?>
                </div>
              </div>
            </div>          
          </div>
        </div>
      </div>
    </div>

    <!-- Image Area -->
    <div class="testimonials__image">
      <?php if (!empty($testimonials)) : ?>
        <?php foreach ($testimonials as $index => $item) : ?>
          <?php
            $testimonial_image = '';
            if (!empty($item['id'])) {
              $testimonial_image = get_the_post_thumbnail_url((int) $item['id'], 'full') ?: '';
            }
            if (!$testimonial_image && !empty($item['thumbnail'])) {
              $testimonial_image = $item['thumbnail'];
            }
            if (!$testimonial_image) {
              $testimonial_image = Helper::getImagePath('images/testimonials/roberto-lopez.jpg');
            }
          ?>
          <img
            class="testimonials__image-item <?php echo $index === 0 ? 'active' : ''; ?>"
            src="<?php echo esc_url($testimonial_image); ?>"
            alt="<?php echo esc_attr($item['meta_fields']['person_name'] ?? 'Testimonials Image'); ?>"
            loading="lazy"
          />
        <?php endforeach; ?>
      <?php else : ?>
        <img
          class="testimonials__image-item active"
          src="<?php echo esc_url(Helper::getImagePath('images/testimonials/roberto-lopez.jpg')); ?>"
          alt="Testimonials Image"
          loading="lazy"
        />
      <?php endif; ?>
    </div>
  </div>
</section>

<style>
  .testimonials {
    position: relative;
    background-color: #0D0C36;
    overflow: hidden;
    height: 560px;
  }

  .testimonials__carousel {
    width: 100%;
    display: flex;
    flex-direction: column;
    min-height: 0;
    position: relative;
    padding-bottom: 72px;
  }

  .testimonials__carousel .carousel-inner {
    flex: 0 0 auto;
  }

  /* Keep nav controls from shifting when the quote text length changes */
  .testimonials__carousel-item {
    position: relative;
  }

  .testimonials__nav-row {
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    margin-top: 0;
    z-index: 3;
  }

  .testimonials__country-row {
    margin-bottom: 0;
    position: absolute;
    top: 0;
    left: 0;
    width: 33.3333%;
    z-index: 3;
    pointer-events: none;
  }

  .testimonials__country-row .col-lg-4 {
    width: 100%;
    max-width: 100%;
    flex: 0 0 100%;
    margin-bottom: 0 !important;
  }

  .testimonials__wrapper {
    height: 100%;
  }

  .testimonials__layout {
    display: flex;
    height: 100%;
  }

  .testimonials__content {
    flex: 0 0 60%;
    display: flex;
    align-items: center;
    padding: 8.5rem 0 3.75rem;
  }

  .testimonials__country {
    display: inline-block;
  }

  .testimonials__country span {
    font-weight: 500;
    font-size: 20px;
    line-height: 170%;
    letter-spacing: 8%;

    color: #ffffff;
    display: block;
    padding-bottom: 16px;
    position: relative;
  }

  .testimonials__quote {
    max-width: 480px;
    height: 320px;
    display: flex;
    flex-direction: column;
  }

  .testimonials__text {
    font-family: "Lora", serif;
    font-style: normal;
    font-size: 20px;
    font-weight: 400;
    line-height: 30px;
    letter-spacing: 0;

    color: #ffffff;
    margin: 0;
    overflow: auto;
  }

  .testimonials__author {
    margin-bottom: 0;
    flex: 0 0 auto;
  }

  .testimonials__name {
    font-size: 16px;
    font-weight: 600;
    line-height: 170%;
    letter-spacing: 2%;

    color: #FFFFFF;
    margin-top: 32px;
    margin-bottom: 8px;
  }

  .testimonials__title {
    font-size: 11px;
    font-weight: 600;
    line-height: 170%;
    letter-spacing: 1.2px;
    color: #9ca3af;
    margin: 0;
  }

  /* Navigation Dots */
  .testimonials__nav {
    display: flex;
    justify-content: flex-start;
    gap: 4px;
    margin-top: 20px;
    margin-bottom: 40px;
    position: static !important; /* Override Bootstrap default indicator positioning */
    align-items: center;
    flex-wrap: nowrap;
    width: 100%;
    text-align: left;
    padding: 0;
    margin-left: 0;
    margin-right: 0;
  }

  .testimonials__nav-hint {
    font-size: 12px;
    line-height: 1;
    letter-spacing: 0.04em;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 500;
    margin-right: 6px;
    white-space: nowrap;
  }

  .testimonials__nav [data-bs-target] {
    width: 48px;
    height: 12px;
    background-color: rgba(255, 255, 255, 0.28);
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, 0.22);
    margin: 0;
    opacity: 1;
    cursor: pointer;
    transition: background-color 0.2s ease;
  }

  .testimonials__nav [data-bs-target].active {
    background-color: #ffffff;
    border-color: #ffffff;
  }

  .testimonials__nav [data-bs-target]:hover {
    background-color: #6366f1;
    border-color: #6366f1;
  }

  .testimonials__nav [data-bs-target]:focus-visible {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
  }

  /* Image Area */
  .testimonials__image {
    position: absolute;
    top: 0;
    right: 0;
    width: 40%;
    height: 100%;
    overflow: hidden;
    background: transparent;
    z-index: 1;
  }

  .testimonials__image::before {
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

  .testimonials__image-item {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    filter: grayscale(100%);
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
  }

  .testimonials__image-item.active {
    opacity: 1;
  }

  .testimonials__content {
    position: relative;
    z-index: 2;
  }

  /* Responsive */
  @media (max-width: 1199px) {
    .testimonials__container {
      padding-left: 40px;
    }

    .testimonials__text {
      font-size: 18px;
    }
  }

  @media (max-width: 991px) {
    .testimonials {
      height: auto;
    }

    .testimonials__layout {
      flex-direction: column-reverse;
      height: auto;
    }

    .testimonials__content {
      flex: 1;
      padding: 40px 0;
    }

    .testimonials__country-row {
      position: static;
      width: auto;
      pointer-events: auto;
    }

    .testimonials__carousel {
      padding-bottom: 0;
    }

    .testimonials__nav-row {
      position: static;
      margin-top: 32px;
    }

    .testimonials__container {
      padding-left: 15px;
      padding-right: 15px;
    }

    .testimonials__image {
      position: relative;
      top: auto;
      right: auto;
      flex: none;
      width: 100%;
      height: auto;
      z-index: 1;
    }

    .testimonials__image::before {
      width: 100%;
      height: 30%;
      bottom: 0;
      top: auto;
      background: linear-gradient(0deg, #0f0f1a 0%, transparent 100%);
    }

    .testimonials__image-item {
      height: auto;
      min-height: 280px;
      max-height: 320px;
      position: relative;
      inset: auto;
    }

    .testimonials__country {
      margin-bottom: 20px;
    }

    .testimonials__quote {
      max-width: 100%;
    }
  }

  @media (max-width: 991px) {
    .testimonials {
      height: 55.1875rem;
    }

    .testimonials__layout {
      position: relative;
      height: 100%;
    }

    .testimonials__container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
      height: 100%;
    }

    .testimonials__content {
      padding: 21.96875rem 0 0;
      position: relative;
      z-index: 2;
    }

    .testimonials__country span {
      font-size: 1.25rem;
      line-height: 2.125rem;
      letter-spacing: 0.1rem;
    }

    .testimonials__quote {
      max-width: 19.9375rem;
      height: 400px;
      display: flex;
      flex-direction: column;
    }

    .testimonials__text {
      font-family: "Lora", serif;
      font-style: italic;
      font-size: 1.25rem;
      line-height: 1.875rem;
      letter-spacing: 0;
      margin: 0;
    }

    .testimonials__name {
      font-size: 1.0625rem;
      line-height: 1.80625rem;
      letter-spacing: 0.02125rem;
      margin-bottom: 0.5rem;
    }

    .testimonials__title {
      font-size: 0.6875rem;
      line-height: 1.16875rem;
      letter-spacing: 0.075rem;
    }

    .testimonials__nav {
      margin-top: 32px;
    }

    .testimonials__image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 25.8125rem;
      z-index: 1;
    }

    .testimonials__image-item {
      height: 100%;
      min-height: 0;
      max-height: none;
      position: absolute;
      inset: 0;
    }

    .testimonials__image::before {
      width: 100%;
      height: 100%;
      top: 0;
      bottom: auto;
      background: linear-gradient(180deg, rgba(13, 12, 54, 0) 0%, rgba(13, 12, 54, 0.79) 67.586%, rgba(13, 12, 54, 1) 100%);
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const carouselEl = document.getElementById('testimonialsCarousel');
    const images = Array.from(document.querySelectorAll('.testimonials__image-item'));

    if (!carouselEl || !images.length) return;

    const setActiveImage = function (index) {
      images.forEach(function (img, idx) {
        img.classList.toggle('active', idx === index);
      });
    };

    carouselEl.addEventListener('slide.bs.carousel', function (event) {
      if (typeof event.to === 'number') {
        setActiveImage(event.to);
      }
    });
  });
</script>