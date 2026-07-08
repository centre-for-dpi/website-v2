<?php
/**
 * DaaS – Advisory board section.
 * Currently uses a static array of members; can be wired to a CPT later.
 */

$daas_advisory_members_handler = new CustomPost('dash_advisory_board', null);
$daas_advisory_members_raw = $daas_advisory_members_handler->getListOfPosts([
    'meta_fields',
]);

$daas_advisory_members_raw = array_values($daas_advisory_members_raw);
usort($daas_advisory_members_raw, function ($a, $b) {
    $name_a = isset($a['title']) ? strtolower(trim((string) $a['title'])) : '';
    $name_b = isset($b['title']) ? strtolower(trim((string) $b['title'])) : '';
    return $name_a <=> $name_b;
});

$daas_advisory_members = [];

foreach ($daas_advisory_members_raw as $member) {
    $daas_advisory_members[] = [
        'image' => $member['meta_fields']['photo'] ? wp_get_attachment_image_url($member['meta_fields']['photo'], 'medium') : '',
        'name' => $member['title'] ?? '',
        'role' => $member['meta_fields']['designation'] ?? '',
        'linkedin' => $member['meta_fields']['linkedin_url'] ?? '',
    ];
}

?>

<section class="redlof-block executive-section daas-advisory">
  <div class="container">
    <div class="executive-section__group executive-section__group--board">
      <div class="row">
        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
        <div class="executive-section__pattern">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-2.svg'); ?>" alt="" loading="lazy" />
        </div>
        <h3 class="executive-section__group-label ">DaaS</h3>
          <h3 class="executive-section__group-label text-uppercase">advisory board</h3>
        </div>
        <div class="col-lg-9 col-md-12">
          <div class="row">
            <?php foreach ($daas_advisory_members as $member) : ?>
              <div class="col-12 col-sm-6 col-lg-3 executive-col">
                <div class="executive-card">
                  <div class="executive-card__image">
                    <img src="<?php echo $member['image']; ?>" alt="<?php echo esc_attr($member['name']); ?>" loading="lazy" />
                  </div>
                  <h4 class="executive-card__name"><?php echo esc_html($member['name']); ?></h4>
                  <span class="executive-card__role text-uppercase"><?php echo esc_html($member['role']); ?></span>
                  <?php if (!empty($member['linkedin'])) : ?>
                    <a href="<?php echo esc_url($member['linkedin']); ?>" class="executive-card__link" target="_blank" rel="noopener noreferrer">
                      LinkedIn
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M7 17L17 7M17 7H10M17 7V14" stroke="currentColor" stroke-width="0.75" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Reuse executive board styles so visuals match About page -->
<style>
  .executive-section {
    background-color: #ffffff;
    padding: 0; /* base wrapper */
  }

  .daas-advisory {
    padding: 144px 0 80px 0;
  }

  .executive-section__label {
    display: inline-block;
    font-family: 'Outfit', sans-serif;
    font-size: 0.75rem;           /* 12px */
    font-weight: 400;             /* Regular */
    line-height: 1.7;
    letter-spacing: 0.075rem;     /* 1.2px */
    text-transform: uppercase;
    color: #5e6979;
    margin-bottom: 1.5rem;
  }

  .executive-section__title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.625rem;          /* 42px */
    font-weight: 500;
    line-height: 1.25;
    letter-spacing: -0.0525rem;   /* -0.84px */
    color: #0f0f0f;
    margin: 0;
  }

  /* Mobile: container padding only (per project rule) */
  @media (max-width: 575px) {
    .executive-section .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }

  .executive-section__group {
    padding: 0 0 6rem; /* no top padding, 96px bottom */
    min-height: 37.1875rem; /* 595px */
    display: flex;
    align-items: center; /* vertically center inner row content */
    border-top: 1px solid;
    border-image-source: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    border-image-slice: 1;
  }

  /* Ensure inner row (heading + cards) always spans full width */
  .executive-section__group > .row {
    flex: 1 1 100%;
    width: 100%;
  }

  .executive-section__group--board {
    border-top: none; /* avoid double purple line if stacked after another group */
  }

  .executive-section__group:last-of-type {

  }

  .executive-section__group-label {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;       /* 20px */
    font-weight: 500;
    line-height: 1.7;
    letter-spacing: 0.1rem;   /* 1.6px */
    color: #0f0f0f;
    margin: 0;
  }

  .executive-section__pattern {
    margin-bottom: 1rem;
  }

  .executive-section__pattern img {
    display: block;
    max-width: 100%;
    height: auto;
  }

  /* Advisory Board Card (same as executive-card) */
  .executive-card {
    margin-right: 2.125rem;  /* 34px gap between cards on larger screens */
  }

  .executive-col {
    padding-left: 0;   /* remove default 12px col padding */
    padding-right: 0;  /* remove default 12px col padding */
    margin-bottom: 64px;  /* spacing between rows of cards */
  }

  .executive-card__image {
    width: 100%;
    max-width: 11.375rem; /* 182px */
    aspect-ratio: 182 / 177;
    height: auto;
    overflow: hidden;
    margin-bottom: 20px;
    border-radius: 0.625rem;  /* 10px */
    background-color: #f7f4f4;
  }

  .executive-card__image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 0.625rem;
  }

  .executive-card__name {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 17px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
    margin: 0 0 4px 0;
  }

  .executive-card__role {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 11px;
    line-height: 170%;
    letter-spacing: 0.075rem; /* 1.2px */
    text-transform: uppercase;
    color: #0F0F0F;
    display: block;
    margin-bottom: 13px;
  }

  .executive-card__link {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 14px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #6564DB;
    text-decoration: underline;
    text-decoration-style: solid;
    text-underline-offset: 25%;
    text-decoration-skip-ink: auto;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
  }

  .executive-card__link svg {
    stroke: #6564DB;
    color: #6564DB;
  }

  /* Responsive tweaks */
  @media (max-width: 991px) {
    .executive-section__group {
      padding: 2.25rem 0 3.25rem;
    }

    .executive-section__group-label {
      margin-bottom: 1.25rem;
    }
  }

  @media (max-width: 575px) {
    .daas-advisory {
      padding-block: 50px;
    }

    .executive-section__group--board .col-lg-3 .executive-section__group-label {
      display: inline;
      margin: 0;
    }

    .executive-section__group--board .col-lg-3 .executive-section__group-label:first-of-type {
      margin-right: 0.4rem;
    }

    .executive-card {
      margin-right: 0;
      margin-left: auto;
      margin-right: auto;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .executive-col {
      text-align: center;
    }
  }
</style>

