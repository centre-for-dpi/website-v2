
<?php
$open_positions_handler = new CustomPost('open_positions', null);

$open_positions_data = $open_positions_handler->getListOfPosts([
  'meta_fields'
]);

$open_positions = [];

if (!empty($open_positions_data) && is_array($open_positions_data)) {
    foreach ($open_positions_data as $position) {
        $deadline_raw = $position['meta_fields']['last_date_to_apply'] ?? '';
        $deadline_formatted = '';

        // Format the deadline from YYYYMMDD to d/m/Y
        if (!empty($deadline_raw) && preg_match('/^\d{8}$/', $deadline_raw)) {
            $date_obj = DateTime::createFromFormat('Ymd', $deadline_raw);
            if ($date_obj) {
                $deadline_formatted = $date_obj->format('M Y');
            }
        }
        $open_positions[] = [
            'title'    => $position['title'] ?? '',
            'deadline' => $deadline_formatted,
            'link_url' => $position['meta_fields']['link'] ?? '',
        ];
    }
}

?>

<section class="redlof-block open-positions">
  <div class="container">
    <div class="open-positions__inner">
      <div class="row g-0 align-items-start">
        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
          <div class="open-positions__intro">
            <h3 class="team-section__group-label text-uppercase">Open positions</h3>
            <p class="open-positions__subtext">
              Join us in building digital public infrastructure that reaches millions.
            </p>
          </div>
        </div>
        <div class="col-lg-9 col-md-12">
          <div class="open-positions__grid">
            <?php if (!empty($open_positions)) : ?>
              <?php foreach ($open_positions as $position) : ?>
                <article class="open-positions-card">
                  <div class="open-positions-card__content">
                    <h4 class="open-positions-card__title">
                      <?php echo esc_html($position['title']); ?>
                    </h4>

                    <?php if (!empty($position['deadline'])) : ?>
                      <p class="open-positions-card__meta">
                          Deadline: <?php echo esc_html($position['deadline']); ?>
                      </p>
                    <?php endif; ?>

                    <?php if (!empty($position['link_url'])) : ?>
                      <a href="<?php echo esc_url($position['link_url']); ?>" class="open-positions-card__link" target="_blank" rel="noopener">
                        <?php echo esc_html($position['link_label'] ?? 'View Job description'); ?>
                        <img src="<?php echo Helper::getImagePath('icons/arrow-up-right-header.svg'); ?>" alt="" loading="lazy" />
                      </a>
                    <?php endif; ?>
                  </div>
                </article>
              <?php endforeach; ?>
            <?php else : ?>
              <article class="open-positions-card open-positions-card--no-roles">
                <div class="open-positions-card__content">
                  <h4 class="open-positions-card__title">We don’t have open roles at the moment.</h4>
                  <p class="open-positions-card__meta">
                    But if you believe in scaling DPI globally and have the agency to build what doesn’t yet exist, write to us.
                  </p>
                  <a href="mailto:careers@cdpi.dev" class="open-positions-card__link">
                    careers@cdpi.dev
                    <img src="<?php echo Helper::getImagePath('icons/arrow-up-right-header.svg'); ?>" alt="" loading="lazy" />
                  </a>
                </div>
              </article>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .open-positions {
    padding: 7.5rem 0; /* 120px top/bottom */
    background-color: #ffffff;
    border-bottom: 1px solid;
    border-image-source: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    border-image-slice: 1;
  }

  .open-positions__inner {
    border-radius: 0.75rem; /* 12px */
    padding: 3.5rem 3.75rem 3.5rem 0; /* remove extra left padding so heading aligns with others */
    background-color: #ffffff; /* plain white – no gradient */
  }

  .open-positions__intro {
    color: #0f0f0f;
  }

  .open-positions__subtext {
    font-family: 'Outfit', sans-serif;
    font-size: 0.875rem;      /* 14px */
    font-weight: 400;
    line-height: 1.7;
    color: #5e6979;
    margin: 0.75rem 0 0;
    max-width: 14.5rem;       /* keep to ~230px like design */
  }

  .open-positions__grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 24.6875rem)); /* 395px cards */
    gap: 2.5rem; /* 40px */
    max-width: 52rem; /* ~830px: 2 cards + gap */
    margin-left: auto; /* align cards to the far right of parent */
  }

  .open-positions-card {
    border-radius: 0.75rem;           /* 12px */
    background: #ffffff;
    border: 1px solid #D6E1F1;
    display: flex;
    min-height: 14.875rem;            /* 238px */
  }

  .open-positions-card--no-roles {
    background-color: #EDECFE;
  }

  .open-positions-card__content {
    padding: 2.5rem;                  /* 40px */
    display: flex;
    flex-direction: column;
    gap: 0;                           /* control spacing via margins so link can sit at bottom */
    height: 100%;
  }

  .open-positions-card__title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem;      /* 20px */
    font-weight: 300;        /* Light */
    line-height: 1.6;        /* 160% */
    letter-spacing: 0.02em;  /* 2% */
    color: #0f0f0f;
    margin: 0;
  }

  .open-positions-card__meta {
    font-family: 'Outfit', sans-serif;
    font-size: 0.75rem;      /* 12px */
    font-weight: 400;        /* Regular */
    line-height: 1.7;        /* 170% */
    letter-spacing: 0.075rem;/* 1.2px */
    text-transform: uppercase;
    color: #5E6979;
    margin-top: 0.9375rem;            /* 15px below heading */
    margin-bottom: auto;              /* push link to bottom of card */
  }

  .open-positions-card__link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;                 /* space before arrow */
    font-family: 'Outfit', sans-serif;
    font-size: 1.0625rem;        /* 17px */
    font-weight: 300;            /* Light */
    line-height: 1.7;            /* 170% */
    letter-spacing: 0.02em;      /* ~2% */
    color: #6564DB;
    margin-top: 0.75rem;
    text-decoration: underline;
    text-underline-offset: 0.25em;    /* ≈25% */
    text-decoration-thickness: 0.5px; /* very thin */
    text-decoration-skip-ink: auto;
  }

  .open-positions-card__link:hover {
    color: #4948E1;
  }

  .open-positions-card__link img {
    width: 1.125rem;
    height: 1.125rem;
  }

  @media (max-width: 991px) {
    .open-positions__inner {
      padding: 2.5rem 2rem;
    }

    .open-positions__grid {
      grid-template-columns: 1fr;
      gap: 1.75rem;
    }
  }

  @media (max-width: 575px) {
    .open-positions {
      padding: 4.5rem 0;
    }

    .open-positions__inner {
      padding: 2rem 1.5rem;
    }
  }
</style>

