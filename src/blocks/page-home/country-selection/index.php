<?php

$countryHandler = new CustomPost('country', null);

$countries = $countryHandler->getListOfPosts([
    'meta_fields',
    'orderby' => 'date',
    'order' => 'ASC',
]);

$status_priority = [
    'live' => 0,
    'engaged' => 1,
];

usort($countries, static function ($a, $b) use ($status_priority) {
    $a_status = strtolower(trim($a['meta_fields']['status'] ?? ''));
    $b_status = strtolower(trim($b['meta_fields']['status'] ?? ''));
    $a_rank = $status_priority[$a_status] ?? 2;
    $b_rank = $status_priority[$b_status] ?? 2;
    return $a_rank <=> $b_rank;
});

foreach ($countries as &$country) {
    $flag = $country['meta_fields']['flag'] ?? $country['meta_fields']['country_flag'] ?? '';
    if (is_numeric($flag)) {
        $flag = wp_get_attachment_image_url((int) $flag, 'full') ?: $flag;
    } elseif (is_array($flag) && !empty($flag['url'])) {
        $flag = $flag['url'];
    } elseif (is_string($flag)) {
        $un = @unserialize($flag);
        if (is_array($un) && !empty($un['url'])) {
            $flag = $un['url'];
        }
    }
    $country['meta_fields']['flag'] = $flag;
}

$tags = $country['meta_fields']['tags'] ?? '';

unset($country);

if (!empty($countries)) {
?>

<section class="redlof-block country-selection">
  <div class="container">
    <div class="row align-items-start justify-content-between">
      <!-- Left Content -->
      <div class="col-md-4 country-selection__intro">
        <div class="country-selection__icon">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-1.svg'); ?>" alt="" width="24" height="24"
            loading="lazy" />
        </div>
        <h2 class="country-selection__title">
        Global momentum
        </h2>
        <p class="country-selection__subtitle">
        <span class="country-selection__subtitle-strong">DPI is no longer a concept; for our partner countries, it is a reality.</span> Supported by CDPI, these nations are currently deploying verifiable identity systems, interoperable payment networks, and consented data sharing infrastructure, including verifiable credentials at scale. </br>
        </p>
        <p class="country-selection__subtitle country-selection__subtitle--mt20">
          These countries are <span class="country-selection__subtitle-strong">combining minimalist technology</span> interventions, public-private <span class="country-selection__subtitle-strong">governance</span>, and vibrant <span class="country-selection__subtitle-strong">private market innovation</span>. These targeted interventions <span class="country-selection__subtitle-strong">rapidly formalise economies</span> and drive <span class="country-selection__subtitle-strong">readiness for an AI era</span>.
        </p>
      </div>

      <!-- Right Carousel -->
      <div class="col-md-8">
        <div class="country-selection__slider">
          <div class="country-slider">

           <?php foreach ($countries as $country) : ?>
            <?php
              $updates_raw = $country['meta_fields']['update'] ?? '';
              $updates_text = '';
              if (is_string($updates_raw)) {
                $updates_text = trim($updates_raw);
              } elseif (is_array($updates_raw)) {
                $updates_text = trim(implode(' ', array_filter(array_map(static function ($value) {
                  return is_scalar($value) ? (string) $value : '';
                }, $updates_raw))));
              }
              $has_updates = $updates_text !== '';
            ?>
            <div class="country-slide">
            <div class="country-card <?php echo $has_updates ? 'country-card--has-updates' : 'country-card--no-updates'; ?>">
              <div class="country-card__top">
                <div class="country-card__flag">
                  <?php if (!empty($country['meta_fields']['flag'])) : ?>
                  <img src="<?php echo esc_url($country['meta_fields']['flag']); ?>" alt="<?php echo esc_attr($country['title']); ?> Flag" width="33"
                    height="22" loading="lazy" />
                  <?php endif; ?>
                </div>
                <?php
                  $status_raw = $country['meta_fields']['status'] ?? '';
                  if (is_string($status_raw)) {
                    $status_value = strtolower(trim($status_raw));
                  } else {
                    $status_value = '';
                  }

                  if (!empty($status_value)) {
                    $status_label = $status_value === 'live'
                      ? esc_html__('Live', 'cdpi-wp-theme')
                      : esc_html(ucfirst($status_value));
                  ?>
                  <div class="country-card__status">
                    <?php if ($status_value === 'live') { ?>
                      <span class="country-card__status-indicator"></span>
                    <?php } ?>
                    <span class="country-card__status-text"><?php echo $status_label; ?></span>
                  </div>
                  <?php } ?>
              </div>
              <div class="country-card__content">
                <h3 class="country-card__name text-uppercase"><?php echo $country['title']; ?></h3>

                <?php
                  $tags_un = @unserialize($country['meta_fields']['tags'] ?? '');
                  if (is_array($tags_un) && !empty($tags_un)) :
                ?>
                  <div class="country-card__stats">
                <?php
                    foreach ($tags_un as $tag) :
                      $normalized_tag = strtolower(trim($tag));
                      $icon_class = '';
                      $pill_class = 'country-card__stat country-card__stat--border';
// TODO - This needs to be taken as input from users instead of hardcoding the icons in the next phase
                      switch ($normalized_tag) {
                        case 'verifiable credentials':
                          $icon_class = 'fa-regular fa-credit-card';
                          $pill_class = 'country-card__stat country-card__stat--green';
                          break;
                        case 'education':
                          $icon_class = 'fa-solid fa-graduation-cap';
                          break;
                        case 'employment':
                          $icon_class = 'fa-solid fa-briefcase';
                          break;
                        case 'agriculture':
                          $icon_class = 'fa-solid fa-money-bill-1';
                          break;
                        case 'access to credit':
                          $icon_class = 'fa-solid fa-money-bill-1';
                          break;
                        case 'healthcare':
                          $icon_class = 'fa-solid fa-medkit';
                          break;
                        case 'national id':
                          $icon_class = 'fa-solid fa-id-card';
                          break;
                        case "drivers license":
                          $icon_class = 'fa-solid fa-car-side';
                          break;
                        case "identity authentication":
                            $icon_class = 'fa-solid fa-face-grin';
                            $pill_class = 'country-card__stat country-card__stat--purple';
                            break;
                        case "payments":
                            $icon_class = 'fa-solid fa-hand-holding-dollar';
                            $pill_class = 'country-card__stat country-card__stat--yellow';
                            break;
                        case "trust infra":
                            $icon_class = 'fa-solid fa-handshake';
                            $pill_class = 'country-card__stat country-card__stat--light-blue';
                            break;
                        case "discovery and fulfillment":
                            $icon_class = 'fa-solid fa-magnifying-glass';
                            $pill_class = 'country-card__stat country-card__stat--orange';
                            break;
                        case "digital identity":
                            $icon_class = 'fa-solid fa-fingerprint';
                            break;
                        case "sme identity":
                          $icon_class = 'fa-solid fa-store';
                          break;
                        case "kyc/finance":
                          $icon_class = 'fa-solid fa-sack-dollar';
                          break;
                          default:
                          $icon_class = 'fa-regular fa-circle-dot';
                          break;
                      }
                ?>
                      <div class="<?php echo esc_attr($pill_class); ?>">
                        <i class="<?php echo esc_attr($icon_class); ?> me-2"></i>
                        <span><?php echo esc_html($tag); ?></span>
                      </div>
                <?php
                    endforeach;
                ?>
                  </div>
                <?php
                  endif;
                ?>
              </div>
              <div class="country-card__bottom">
                <?php if ($updates_text !== '') : ?>
                  <?php
                    $updates_html = wp_kses_post(make_clickable(esc_html($updates_text)));
                    $updates_html = preg_replace('/<a\s+href=/i', '<a target="_blank" rel="noopener noreferrer" href=', $updates_html);
                  ?>
                  <div class="country-card__updates">
                    <p class="country-card__updates-title mb-0">UPDATES</p>
                    <p class="country-card__updates-text mt-0 mb-0">
                      <?php echo $updates_html; ?>
                    </p>
                  </div>
                <?php endif; ?>
                <p class="country-card__usecase-title mb-0">INITIAL USE-CASE(S):</p>
                <p class="country-card__desc mt-0">
                <?php echo $country['meta_fields']['description']; ?>
                </p>
              </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <!-- Navigation Arrows -->
          <div class="country-selection__nav d-flex gap-2">
            <button class="country-nav-btn country-nav-prev" aria-label="Previous">
              <i class="fa-solid fa-arrow-left"></i>
            </button>
            <button class="country-nav-btn country-nav-next" aria-label="Next">
              <i class="fa-solid fa-arrow-right"></i>
            </button>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<?php } ?>

<style>
  .country-selection {
    background-color: #FFFFFF;
    padding: 120px 0 64px;
  }

  .country-selection__intro {
    width: 100%;
    max-width: 370px;
  }



  .country-selection__title {
    font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 300;        /* Light */
    font-size: 32px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 40px 0 16px;
    max-width: 370px;
  }

  .country-selection__subtitle {
    font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 400;        /* Regular */
    font-size: 15px;
    line-height: 165%;
    letter-spacing: 0.02em;  /* ~2% */
    color: #5E6979;
    margin: 0 0 24px;
    max-width: 370px;
  }

  .country-selection__subtitle-strong {
    font-weight: 700;
    color: #5E6979;
  }

  .country-selection__subtitle--mt20 {
    margin-top: 20px;
    margin-bottom: 0;
  }

  .country-selection__cta {
    color: #FFFFFF;
    background-color: #4B4AEA;
    border-color: #4948E1;
    border-radius: 7px;
    padding: 16px 24px;

    font-weight: 500;
    font-size: 16px;
    line-height: 160%;
    letter-spacing: 1%;

    max-width: 200px
  }

  .country-selection__cta:hover {
    background-color: #4338ca;
    border-color: #4338ca;
  }

  /* Country Card Styles */

  .country-card {
    background: #FFFFFF;
    border: 1px solid #D6E1F1;
    border-radius: 12px;
    padding: 40px 32px;
    max-width: 392px;
    margin-left: 0;
    min-height: 620px;
    height: auto;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  .country-card__content {
    width: 100%;
  }

  .country-card__bottom {
    margin-top: auto;
    width: 100%;
  }

  .country-card--no-updates .country-card__bottom {
    margin-top: 24px;
  }

  .country-card--no-updates .country-card__desc {
    margin-bottom: 0 !important;
  }

  .country-card:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  }

  .country-card__name {
    font-weight: 600;
    font-size: 19px;
    line-height: 170%;
    letter-spacing: 8%;
    color: #101828;

    margin: 54px 0px 6px;
  }

  .country-card__subtitle {
    font-weight: 400;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0%;

    color: #5E6979;
  
  }

  .country-card__flag img {
    box-shadow: 0 0 0 1px rgba(214, 225, 241, 0.9);
  }

  .country-card__top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    min-height: 32px;
    width: 100%;
  }

  .country-card__status {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 999px;
    border: 1px solid #E2F7E7;
    background-color: #FFFFFF;
    font-weight: 500;
    font-size: 12px;
    line-height: 1.4;
  }

  .country-card__status-indicator {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background-color: #009440;
    margin-right: 6px;
    animation: country-card-live-dot 1.2s ease-in-out infinite;
  }

  @keyframes country-card-live-dot {
    0% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.25; transform: scale(0.75); }
    100% { opacity: 1; transform: scale(1); }
  }

  @media (prefers-reduced-motion: reduce) {
    .country-card__status-indicator {
      animation: none;
    }
  }

  .country-card__stat {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    margin: 0;
    height: 38px;
    border-radius: 999px;
    font-weight: 500;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0;
  }

  .country-card__stats {
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    gap: 8px;
    margin-top: 8px;
    min-height: 84px; /* fixed space for up to two pill rows */
  }

  .country-card__stat--border {
    background-color: #ffffff;
    color: #101828;
    border: 1px solid #D6E1F1;
  }

  .country-card__stat--green {
    background-color: #BFFFBD;
    color: #101828;
    border: 1px solid #BFFFBD;
  }

  .country-card__stat--yellow {
    background-color:#FFF4BD;
    color: #101828;
    border: 1px solid #FFF4BD;
  }

  .country-card__stat--purple {
    background-color: #D3BDFF;
    color: #101828;
    border: 1px solid #D3BDFF;
  }

  .country-card__stat--light-blue {
    background-color: #AAE1FF;
    color: #101828;
    border: 1px solid #AAE1FF;
  }

  .country-card__stat--orange {
    background-color: #FED1B8;
    color: #101828;
    border: 1px solid #FED1B8;
  }


  .country-card__stat--blue {
    background-color: #DBEAFE;
    color: #101828;
  }

  .country-card__desc {
    font-weight: 400;
    font-size: 16px;
    line-height: 160%;
    letter-spacing: 0%;
    color: #5E6979;
  }

  .country-card__usecase-title {
    font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 500;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0;
    color: #101828;
    border-top: 1px solid #D6E1F1;
    padding-top: 24px;
    margin-top: 24px !important; /* 24px below the pills area */
    margin-bottom: 24px !important;
  }

  .country-card--has-updates .country-card__usecase-title {
    border-top: 0;
    padding-top: 0;
    margin-top: 0 !important;
  }

  .country-card__updates {
    border-top: 1px solid #D6E1F1;
    border-bottom: 1px solid #D6E1F1;
    margin-top: 24px;
    margin-bottom: 24px;
    padding-top: 24px;
    padding-bottom: 24px;
  }

  .country-card__updates-title {
    font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 500;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0;
    color: #101828;
    margin-bottom: 12px !important;
  }

  .country-card__updates-text {
    font-weight: 400;
    font-size: 16px;
    line-height: 160%;
    letter-spacing: 0;
    color: #5E6979;
    word-break: break-word;
  }

  .country-card__updates-text a {
    color: #1E69FF;
  }

  .country-card__link {
    font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 400;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0%;
    color: #6564DB;
    text-decoration: none;
  }

  .country-card__link:hover {
    color: #4338ca;
  }

    /* Navigation Buttons */
    .country-selection__nav {
    margin-top: 20px;
    margin-right: 0;
    width: 100%;
    justify-content: flex-end;
  }

  @media (min-width: 992px) {
    .country-card {
      margin-left: 16px;
    }
  }

  @media (min-width: 768px) and (max-width: 1199px) {
    .country-selection .row {
      flex-wrap: nowrap;
    }

    .country-selection .country-selection__intro {
      flex: 0 0 34%;
      max-width: 34%;
    }

    .country-selection .country-selection__slider {
      margin-top: 0;
    }

    .country-selection .col-md-8 {
      flex: 0 0 66%;
      max-width: 66%;
    }

    .country-selection__nav {
      justify-content: flex-start;
    }
  }

  @media (min-width: 992px) and (max-width: 1079px) {
    .country-selection__nav {
      padding-left: 16px;
    }
  }

  @media (min-width: 1080px) and (max-width: 1199px) {
    .country-selection__nav {
      padding-left: 36px;
    }
  }

  @media (min-width: 1080px) {
    .country-card {
        margin-left: 36px;
    }
  }

  @media (max-width: 991px) {
    .country-selection {
      padding: 4rem 0 5rem;
    }

    .country-selection__intro {
      margin-bottom: 3rem;
    }

    .country-selection__icon {
      margin-top: 0;
      margin-bottom: 1.5rem;
    }

    .country-selection__title {
      margin: 0 0 2.5rem;
      max-width: 23.125rem;
      font-size: 2rem;
      line-height: 2.8rem;
    }

    .country-selection__cta {
      border-radius: 0.4375rem;
      padding: 1rem 1.5rem;
      font-size: 1rem;
      line-height: 1.6rem;
      letter-spacing: 0.01rem;
      max-width: 12.5rem;
    }

    .country-selection__slider {
      margin-top: 0;
    }

    .country-card {
      max-width: 24.5rem;
      margin-right: 0;
      padding: 2.5rem 1.5rem;
      border-radius: 0.75rem;
      min-height: 620px;
      height: auto;
    }

    .country-card__stats {
      min-height: 0;
    }

    .country-card__name {
      font-size: 1.181rem;
      line-height: 2.0076rem;
      letter-spacing: 0.0945rem;
      margin: 2rem 0 0.375rem;
    }

    .country-card__subtitle {
      font-size: 0.875rem;
      line-height: 1.4rem;
      letter-spacing: 0;
    }

    .country-card__stat {
      padding: 0.5rem 1.25rem;
      margin: 0.5rem 0;
      height: 38px;
      border-radius: 1.3125rem;
      font-size: 0.875rem;
      line-height: 1.4rem;
    }

    .country-card__desc {
      font-size: 1rem;
      line-height: 1.6rem;
      letter-spacing: 0;
    }

    .country-card__link {
      font-size: 0.875rem;
      line-height: 1.4rem;
    }

    .country-selection__nav {
      justify-content: left;
      margin-top: 1.5rem;
      margin-right: 0;
    }

    .country-nav-btn {
      width: 2.5rem;
      height: 2.5rem;
      border-radius: 0.3125rem;
    }

    .country-slider .slick-slide {
      padding-right: 1rem;
    }
  }

  @media (max-width: 575px) {
    .country-selection {
      padding: 4rem 0 4.5rem;
    }

    .country-selection .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .country-card {
      max-width: 100%;
    }

    .country-slider .slick-slide {
      padding-right: 0;
    }

    .country-selection__nav {
      justify-content: center;
    }
  }


  .country-nav-btn {
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
  }

  .country-nav-btn:hover {
    border-color: #4f46e5;
    color: #4f46e5;
  }

  .country-nav-btn.slick-disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Slick Overrides */
  .country-selection__slider {
    display: flex;
    flex-direction: column;
  }

  .country-slider {
    width: 100%;
  }

  .country-slider .slick-track {
    display: flex;
  }

  .country-slider .slick-slide {
    height: auto;
  }

  .country-slider .slick-slide>div {
    height: 100%;
  }

  .country-slide {
    height: 100%;
  }
</style>

<script>
  (function initCountrySlider() {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.slick === 'undefined') {
      setTimeout(initCountrySlider, 100);
      return;
    }

    jQuery(function ($) {
      if ($('.country-slider').hasClass('slick-initialized')) return;

      $('.country-slider').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: false,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 3500,
        prevArrow: $('.country-nav-prev'),
        nextArrow: $('.country-nav-next'),
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 1
            }
          },
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 2
            }
          },
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1
            }
          }
        ]
      });
    });
  })();
</script>
