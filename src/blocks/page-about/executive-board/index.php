<?php
$executive_board_members_handler = new CustomPost('cdpi_executive_board', null);

$executive_board_members_data = $executive_board_members_handler->getListOfPosts([
  'meta_fields'
]);

// Get image url from photo ID stored in meta_fields['photo'] (attachment ID)
$executive_board_members = [];
foreach ($executive_board_members_data as $member) {
    $photo_id = $member['meta_fields']['photo'] ?? '';
    $image_url = '';
    if (!empty($photo_id) && is_numeric($photo_id)) {
        $image_url = wp_get_attachment_url((int)$photo_id);
    }
    
    $executive_board_members[] = [
        'image' => $image_url,
        'name'  => $member['title'] ?? '',
        'role'  => $member['meta_fields']['designation'] ?? '',
    ];
}
    ?>
    


<section class="redlof-block executive-section">
  <div class="container">
    <div class="executive-section__group executive-section__group--board">
      <div class="row">
        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
          <div class="executive-section__pattern">
            <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-2.svg'); ?>" alt="" loading="lazy" />
          </div>
          <h3 class="executive-section__group-label text-uppercase">CDPI executive board</h3>
        </div>
        <div class="col-lg-9 col-md-12">
          <div class="row">
          <?php foreach ($executive_board_members as $member): ?>
              <div class="col-12 col-sm-6 col-lg-3 executive-col">
                <div class="executive-card">
                  <div class="executive-card__image">
                  <img src="<?php echo $member['image']; ?>" alt="<?php echo esc_attr($member['name']); ?>" loading="lazy" />
                  </div>
                  <h4 class="executive-card__name"><?php echo esc_html($member['name']); ?></h4>
                  <span class="executive-card__role text-uppercase"><?php echo esc_html($member['role']); ?></span>
                </div>
              </div>
          <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .executive-section {
    background-color: #ffffff;
    padding: 0; /* no extra top/bottom padding for executive board wrapper */
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

/* Ensure inner row (heading + cards) always spans full width,
   regardless of how many executive members are rendered */
.executive-section__group > .row {
  flex: 1 1 100%;
  width: 100%;
}

.executive-section__group--board {
  border-top: none; /* avoid double purple line after team section */
}

.executive-section__group:last-of-type {
  border-bottom: 1px solid;
  padding-bottom: 0; /* avoid double spacing before next section */
}

.executive-section__group-label {
  font-family: 'Outfit', sans-serif;
  font-size: 1.25rem;       /* 20px */
  font-weight: 500;
  line-height: 1.7;
  letter-spacing: 0.1rem;   /* 1.6px */
  text-transform: uppercase;
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

.executive-section__group--board .col-lg-9 > .row {
  --bs-gutter-x: 34px;
  --bs-gutter-y: 34px;
}

.executive-section__group--board .col-lg-9 {
  padding-left: 0;
}

/* Executive Board Card */
.executive-card {
  margin-right: 0;
}

.executive-col {
  margin-bottom: 0;  /* remove default 24px bottom margin from mb-4 */
}

.executive-card__image {
  width: 100%;
  width: 182px;     /* 182px */
  height: 224px;     /* 224px */
  aspect-ratio: 182 / 224;  /* 182x224 layout */
  overflow: hidden;
  margin-bottom: 1.25rem;   /* 20px */
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
  font-family: 'Outfit', sans-serif;
  font-size: 1.0625rem;     /* 17px */
  font-weight: 600;
  line-height: 1.7;         /* 170% */
  letter-spacing: 0.02em;   /* 2% approx */
  color: #0f0f0f;
  margin: 0 0 0.25rem;
}

.executive-card__role {
  font-family: 'Outfit', sans-serif;
  font-size: 0.6875rem;     /* 11px */
  font-weight: 600;
  line-height: 1.7;         /* 170% */
  letter-spacing: 0.075rem; /* 1.2px */
  text-transform: uppercase;
  color: #0f0f0f;
  display: block;
  margin-bottom: 1.25rem;   /* 20px */
}

.executive-card__desc {
  font-family: 'Outfit', sans-serif;
  font-size: 0.875rem;      /* 14px */
  font-weight: 400;
  line-height: 1.6;
  letter-spacing: 0.0175rem;
  color: #5e6979;
  margin: 0 0 0.75rem;
}

.executive-card__link {
  font-family: 'Outfit', sans-serif;
  font-size: 0.875rem;      /* 14px */
  font-weight: 400;
  color: #6564db;
  text-decoration: underline;
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
}

.executive-card__link i {
  font-size: 0.7rem;
}

/* Responsive */
@media (max-width: 991px) {
  .executive-section__title {
    font-size: 2.625rem;
  }

  .executive-section__title br {
    display: none;
  }

  .executive-section__group {
    padding: 2.25rem 0 3.25rem;
  }

  .executive-section__group-label {
    margin-bottom: 1.25rem;
  }

  .executive-card {
    padding-right: 0.75rem;
    margin-bottom: 1.25rem;
  }

  /* keep base 182x224 dimensions; no override here */
}

@media (max-width: 767px) {
  .executive-section__title {
    font-size: 2.625rem;
  }

  .executive-card {
    padding-right: 0;
    margin-bottom: 1.5rem;
  }

  .executive-card__image {
    max-width: 11.375rem; /* keep 182px on small screens as well */
  }
}

@media (max-width: 575px) {
  .executive-section__header {
    margin-bottom: 1.25rem;
  }

  .executive-section__title {
    font-size: 2.625rem;
  }

  .executive-section__group {
    padding: 3rem 0 1.5rem;
  }

  .executive-col {
    text-align: center;
  }

  .executive-card {
    margin-right: 0;
    margin-left: auto;
    margin-right: auto;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .executive-card__name {
    font-size: 0.95rem;
  }
}
</style>
