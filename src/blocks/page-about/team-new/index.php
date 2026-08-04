<?php


$global_team_handler = new CustomPost('team_members', null);
$global_team_posts = $global_team_handler->getListOfPosts([
    'meta_fields',
    'featured_image',
    'thumbnail',
]);

// Sort the team members by [meta_fields][sequence]
usort($global_team_posts, function ($a, $b) {
    return ($a['meta_fields']['sequence'] ?? 1000) <=> ($b['meta_fields']['sequence'] ?? 1000);
});


$team_members = [];
$countries_handler = new CustomPost('countries', null);
$country_cache = [];
$resolve_languages = static function ($languages_field): array {
    if (empty($languages_field)) {
        return [];
    }

    if (is_string($languages_field)) {
        $languages_field = maybe_unserialize($languages_field);
    }

    if (is_array($languages_field)) {
        return array_values(array_filter(array_map('trim', $languages_field)));
    }

    return is_string($languages_field)
        ? array_values(array_filter(array_map('trim', explode(',', $languages_field))))
        : [];
};
$resolve_country_data = static function ($country_field) use ($countries_handler, &$country_cache): array {
    $country_data = ['name' => '', 'flag' => ''];

    if (empty($country_field)) {
        return $country_data;
    }

    if (is_string($country_field)) {
        $country_field = maybe_unserialize($country_field);
    }

    if (is_array($country_field)) {
        $country_id = (int) ($country_field[0] ?? 0);
    } else {
        $country_id = (int) $country_field;
    }

    if ($country_id > 0) {
        if (!isset($country_cache[$country_id])) {
            $countries_handler->setID($country_id);
            $country_post = $countries_handler->getPost(['title', 'meta_fields']);
            $country_cache[$country_id] = [
                'name' => $country_post['title'] ?? '',
                'flag' => CustomPost::getImageUrl($country_post['meta_fields']['flag']) ?? '',
            ];
        }
        $country_data = $country_cache[$country_id];
    }

    return $country_data;
};

foreach ($global_team_posts as $member_post) {
    $country_data = $resolve_country_data($member_post['meta_fields']['country'] ?? '');
    $languages = $resolve_languages($member_post['meta_fields']['language'] ?? '');

    $team_members[] = [
        'name' => $member_post['title'],
        "image" => $member_post['meta_fields']['photo'] ? wp_get_attachment_image_url($member_post['meta_fields']['photo'], 'medium') : '',
        "role" => $member_post['meta_fields']['role'],
        "bio" => $member_post['meta_fields']['bio'],
        "linkedin" => $member_post['meta_fields']['linkedin_url'] ?? '',
        // Handle serialized country field that may contain an array of term IDs
        "country" => $country_data['name'],
        "country_flag" => $country_data['flag'],
        "languages" => $languages,
    ];

}
?>

<section class="redlof-block team-section">
  <div class="container">
    <div class="team-section__pattern">
      <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-5.svg'); ?>" alt="" loading="lazy" />
    </div>
    <!-- Header -->
    <div class="team-section__header mb-5">
      <span class="team-section__label">Team Behind CDPI</span>
      <h2 class="team-section__title">Expert team.<br> Global experience.</h2>
    </div>

    <!-- Leadership -->
    <div class="team-section__group">
      <div class="row">
        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
          <h3 class="team-section__group-label text-uppercase">Team</h3>
        </div>
        <div class="col-lg-9 col-md-12">
          <div class="row">
            <?php foreach ($team_members as $member) : ?>
              <?php
                $member_languages = $member['languages'] ?? ($member['language'] ?? []);
                if (is_string($member_languages)) {
                  $member_languages = array_values(array_filter(array_map('trim', explode(',', $member_languages))));
                } elseif (!is_array($member_languages)) {
                  $member_languages = [];
                }
                $member_payload = [
                  'image' => $member['image'],
                  'name' => $member['name'] ?? '',
                  'role' => $member['role'] ?? '',
                  'country' => $member['country'] ?? '',
                  'country_flag' => $member['country_flag'] ?? '',
                  'languages' => $member_languages,
                  'bio' => $member['bio'] ?? '',
                ];
              ?>
              <div class="col-6 col-lg-4 mb-4">
                <div class="team-card">
                  <button type="button" class="team-card__image team-card__image-btn" data-team-member="<?php echo esc_attr(wp_json_encode($member_payload)); ?>" aria-label="View <?php echo esc_attr($member['name']); ?> details">
                    <?php if (!empty($member['country_flag'])) : ?>
                      <span class="team-card__country-flag" aria-hidden="true">
                        <img src="<?php echo esc_url($member['country_flag']); ?>" alt="" loading="lazy" />
                      </span>
                    <?php endif; ?>
                    <img src="<?php echo esc_url($member['image']); ?>" alt="<?php echo esc_attr($member['name']); ?>" loading="lazy" />
                  </button>
                  <h4 class="team-card__name"><?php echo esc_html($member['name']); ?></h4>
                  <div class="team-card__role-row">
                    <span class="team-card__role text-uppercase"><?php echo esc_html($member['role']); ?>
                    <button type="button" class="team-card__info-btn" data-team-member="<?php echo esc_attr(wp_json_encode($member_payload)); ?>" aria-label="View <?php echo esc_attr($member['name']); ?> details">
                      <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                    </button>
                  </span>
                  </div>
                  <a href="<?php echo esc_url($member['linkedin']); ?>" class="team-card__link" target="_blank" rel="noopener">LinkedIn <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <div class="team-member-modal" id="teamMemberModal" aria-hidden="true">
      <div class="team-member-modal__backdrop" data-team-modal-close></div>
      <div class="team-member-modal__dialog" role="dialog" aria-modal="true" aria-label="Team member details">
        <button type="button" class="team-member-modal__close" data-team-modal-close aria-label="Close member details">&times;</button>
        <div class="team-member-modal__top">
          <img class="team-member-modal__image" src="" alt="" />
          <div class="team-member-modal__meta">
            <h3 class="team-member-modal__name"></h3>
            <p class="team-member-modal__role"></p>
            <p class="team-member-modal__country"></p>
            <div class="team-member-modal__languages-wrap">
              <span class="team-member-modal__languages-label">Languages</span>
              <div class="team-member-modal__languages"></div>
            </div>
          </div>
        </div>
        <p class="team-member-modal__bio"></p>
      </div>
    </div>

</section>

<style>
  .team-section {
    background-color: #ffffff;
    padding-bottom: 0; !important
  }

  .team-section__pattern {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
  }

  .team-section__pattern img {
    width: auto;
    height: auto;
    max-width: 100%;
    display: block;
  }

  .team-section__label {
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

  .team-section__title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.625rem;          /* 42px */
    font-weight: 500;
    line-height: 1.25;
    letter-spacing: -0.0525rem;   /* -0.84px */
    color: #0f0f0f;
    margin: 0;
  }

  .team-section__header {
    text-align: center;
  }

  /* Mobile: container padding only (per project rule) */
  @media (max-width: 575px) {
    .team-section {
      padding: 4.5rem 0 4.5rem; /* 72px top/bottom from Figma mobile */
    }

    .team-section .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }

.team-section__group {
  padding: 4rem 0 6rem; /* 64px top, 96px bottom */
  border-top: 1px solid;
  border-image-source: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  border-image-slice: 1;
}

.team-section__group:last-of-type {
  border-bottom: 1px solid;
}

.team-section__group-label {
  font-family: 'Outfit', sans-serif;
  font-size: 1.25rem;       /* 20px */
  font-weight: 500;
  line-height: 1.7;
  letter-spacing: 0.1rem;   /* 1.6px */
  text-transform: uppercase;
  color: #0f0f0f;
  margin: 0;
}

/* Team Card – shared for Leadership & Portfolio Services  */
.team-card {
  padding-right: 2rem;      /* 32px gap between cards */
}

.team-card__image {
  width: 100%;
  max-width: 15.875rem;     /* 254px */
  aspect-ratio: 254 / 276;  /* keep all images same ratio */
  overflow: hidden;
  margin-bottom: 1.25rem;   /* 20px */
  background-color: #f7f4f4;
}

.team-card__image-btn {
  position: relative;
  border: 0;
  padding: 0;
  cursor: pointer;
  text-align: left;
}

.team-card__country-flag {
  position: absolute;
  top: 0.625rem;
  left: 0.625rem;
  width: 2rem;
  height: 1.35rem;
  z-index: 1;
  overflow: hidden;
  border: 1px solid #e5e7eb;
  background: #ffffff;
}

.team-card__country-flag img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.team-card__image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.team-member-modal {
  position: fixed;
  inset: 0;
  z-index: 9999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}

.team-member-modal.is-open {
  display: flex;
}

.team-member-modal__backdrop {
  position: absolute;
  inset: 0;
  background: rgba(15, 23, 42, 0.45);
}

.team-member-modal__dialog {
  position: relative;
  z-index: 1;
  width: min(46rem, calc(100% - 1.5rem));
  max-height: calc(100vh - 2rem);
  background: #ffffff;
  border: 1px solid #9ca3af;
  border-radius: 1.5rem;
  padding: 2rem;
  overflow-y: auto;
}

.team-member-modal__close {
  position: absolute;
  top: 1rem;
  right: 1.25rem;
  border: 0;
  background: transparent;
  color: #4b5563;
  font-size: 2rem;
  line-height: 1;
  cursor: pointer;
}

.team-member-modal__top {
  display: flex;
  gap: 1.5rem;
  margin-bottom: 1.25rem;
}

.team-member-modal__image {
  width: 12.5rem;
  height: 12.5rem;
  object-fit: cover;
  border-radius: 1rem;
  flex-shrink: 0;
}

.team-member-modal__name {
  margin: 0;
  font-family: 'Outfit', sans-serif;
  font-weight: 700;
  font-style: normal;
  font-size: 23.21px;
  line-height: 100%;
  letter-spacing: 0;
  color: #0f172a;
}

.team-member-modal__role,
.team-member-modal__country,
.team-member-modal__languages-label,
.team-member-modal__bio {
  font-family: 'Outfit', sans-serif;
}

.team-member-modal__role {
  margin: 0.25rem 0 0;
  font-weight: 400;
  font-style: normal;
  font-size: 15.47px;
  line-height: 100%;
  letter-spacing: 0;
  color: #0f172a;
}

.team-member-modal__country {
  margin: 0.25rem 0 0.5rem;
  display: inline-flex;
  align-items: center;
  font-weight: 400;
  font-style: normal;
  font-size: 20px;
  line-height: 100%;
  letter-spacing: 0;
  color: #111827;
}

.team-member-modal__country-flag {
  width: 1.85rem;
  height: 1.85rem;
  min-width: 1.85rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 0;
  overflow: hidden;
  font-size: 1.05rem;
  line-height: 1;
  margin-right: 0.5rem;
  vertical-align: middle;
  box-sizing: border-box;
}

.team-member-modal__country-flag img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  display: block;
}

.team-member-modal__languages-label {
  display: block;
  font-size: 1.125rem;
  color: #4b5563;
  margin-bottom: 0.5rem;
}

.team-member-modal__languages {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.team-member-modal__language-pill {
  border: 1px solid #111827;
  border-radius: 999px;
  padding: 0.25rem 0.875rem;
  font-size: 1.25rem;
  color: #111827;
}

.team-member-modal__bio {
  margin: 0;
  font-size: 1.125rem;
  line-height: 1.5;
  color: #111827;
  white-space: pre-wrap;
}

.team-card__name {
  font-family: 'Outfit', sans-serif;
  font-size: 1.375rem;      /* 22px */
  font-weight: 600;
  letter-spacing: 0.0275rem;
  color: #0f0f0f;
  margin: 0 0 0.25rem;
}

.team-card__role {
  font-family: 'Outfit', sans-serif;
  font-size: 0.8125rem;     /* 13px */
  font-weight: 600;
  letter-spacing: 0.075rem; /* ~1.2px */
  text-transform: uppercase;
  color: #0f0f0f;
  display: block;
}

.team-card__role-row {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  width: 100%;
}

.team-card__info-btn {
  border: 0;
  background: transparent;
  color: #6564db;
  padding: 0;
  line-height: 1;
  cursor: pointer;
}

.team-card__info-btn:hover {
  color: #4b4aea;
}

.team-card__desc {
  font-family: 'Outfit', sans-serif;
  font-size: 0.875rem;      /* 14px */
  font-weight: 400;
  line-height: 1.6;
  letter-spacing: 0.0175rem;
  color: #5e6979;
  margin: 0 0 0.75rem;
}

.team-card__link {
  font-family: 'Outfit', sans-serif;
  font-size: 0.875rem;      /* 14px */
  font-weight: 400;
  color: #6564db;
  text-decoration: underline;
  display: inline-flex;
  align-items: center;
  gap: 0.375rem;
  margin-top: 0;
}

.team-card__link i {
  font-size: 0.7rem;
}

/* Responsive */
@media (max-width: 991px) {
  .team-section__title {
    font-size: 2.625rem;
  }

  .team-section__title br {
    display: none;
  }

  .team-section__group {
    padding: 2.25rem 0 3.25rem;
  }

  .team-section__group-label {
    margin-bottom: 1.25rem;
  }

  .team-card {
    padding-right: 0.75rem;
    margin-bottom: 1.25rem;
  }

  .team-card__image {
    max-width: 100%;
  }
}

@media (max-width: 767px) {
  .team-section__title {
    font-size: 2.625rem;
  }

  .team-card {
    padding-right: 0;
    margin-bottom: 1.5rem;
  }

  .team-card__image {
    max-width: 12.5rem; /* 200px */
  }
}

@media (max-width: 575px) {
  .team-section__header {
    margin-bottom: 1.25rem;
  }

  .team-section__title {
    font-size: 2.625rem;
  }

  .team-section__group {
    padding: 3rem 0 1.5rem;
  }

  .team-card__image {
    max-width: 10rem; /* 160px */
  }

  .team-card__name {
    font-size: 0.95rem;
  }

  .team-member-modal__dialog {
    width: 100%;
    max-height: calc(100vh - 1rem);
    padding: 1.25rem;
    border-radius: 1rem;
  }

  .team-member-modal__top {
    flex-direction: column;
  }

  .team-member-modal__image {
    width: 100%;
    height: auto;
    max-height: 16rem;
  }

  .team-member-modal__name {
    font-size: 2rem;
  }

  .team-member-modal__role {
    font-size: 1rem;
  }
  .team-member-modal__country {
    font-size: 1.5rem;
  }

  .team-member-modal__language-pill {
    font-size: 1rem;
  }

  .team-member-modal__bio {
    font-size: 1rem;
  }
}
</style>

<script>
  (function () {
    var modal = document.getElementById('teamMemberModal');
    if (!modal) return;

    var body = document.body;
    var imageEl = modal.querySelector('.team-member-modal__image');
    var nameEl = modal.querySelector('.team-member-modal__name');
    var roleEl = modal.querySelector('.team-member-modal__role');
    var countryEl = modal.querySelector('.team-member-modal__country');
    var langsWrapEl = modal.querySelector('.team-member-modal__languages-wrap');
    var langsEl = modal.querySelector('.team-member-modal__languages');
    var bioEl = modal.querySelector('.team-member-modal__bio');

    function countryWithFlag(country, countryFlag) {
      var key = String(country || '').trim().toLowerCase();
      if (!key) return '';
      if (countryFlag) {
        return '<span class="team-member-modal__country-flag" aria-hidden="true"><img src="' + countryFlag + '" alt="" loading="lazy" /></span>' + country;
      }
      return country;
    }

    function openModal(data) {
      imageEl.src = data.image || '';
      imageEl.alt = data.alt || data.name || 'Team member photo';
      nameEl.textContent = data.name || '';
      roleEl.textContent = data.role || '';
      countryEl.innerHTML = countryWithFlag(data.country || '', data.country_flag || '');
      bioEl.textContent = data.bio || '';

      langsEl.innerHTML = '';
      var languages = Array.isArray(data.languages) ? data.languages : [];
      langsWrapEl.style.display = languages.length ? '' : 'none';
      languages.forEach(function (lang) {
        var pill = document.createElement('span');
        pill.className = 'team-member-modal__language-pill';
        pill.textContent = lang;
        langsEl.appendChild(pill);
      });

      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      body.style.overflow = 'hidden';
    }

    function closeModal() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      body.style.overflow = '';
    }

    document.querySelectorAll('.team-card__image-btn, .team-card__info-btn').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var raw = btn.getAttribute('data-team-member') || '{}';
        var data = {};
        try { data = JSON.parse(raw); } catch (e) {}
        openModal(data);
      });
    });

    modal.querySelectorAll('[data-team-modal-close]').forEach(function (el) {
      el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && modal.classList.contains('is-open')) {
        closeModal();
      }
    });
  })();
</script>
