<?php

$thoughtLeadershipHandler = new CustomPost('publications', null);

$thoughtLeadershipRaw = $thoughtLeadershipHandler->getListOfPosts([
    'meta_fields'
]);

$thoughtLeadership = array_map(function($publication) {
    if (
        isset($publication['meta_fields']['publication_date']) && 
        !empty($publication['meta_fields']['publication_date'])
    ) {
        $date_raw = $publication['meta_fields']['publication_date'];
        $dt = DateTime::createFromFormat('Ymd', $date_raw);
        if ($dt !== false) {
            $publication['meta_fields']['publication_date'] = $dt->format('M Y');
        }
    }
    return $publication;
}, $thoughtLeadershipRaw);


?>
<section class="redlof-block publication-listing">
  <div class="container">
    <!-- Publication Group -->
    <div class="publication-listing__group">
      <!-- Search -->
      <div class="publication-listing__search" data-publication-search>
        <button type="button" class="publication-listing__search-toggle" aria-label="Search publications" aria-expanded="false">
          <svg class="publication-listing__search-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <circle cx="8" cy="8" r="4.5" stroke="#0F0F0F" stroke-width="1.2" />
            <line x1="11.1818" y1="11.1818" x2="14" y2="14" stroke="#0F0F0F" stroke-width="1.2" stroke-linecap="round" />
          </svg>
          <span class="publication-listing__search-label" aria-hidden="true">Search</span>
        </button>
        <input
          type="text"
          class="publication-listing__search-input"
          placeholder="Search publications"
          aria-label="Search publications by title"
        />
      </div>
      <div class="row">

        <!-- Publications -->
        <div class="col-lg-12" data-publication-list>
          <?php foreach ($thoughtLeadership as $publication) : ?>
          <div class="publication-card mb-4" data-publication-card>
            <div class="row g-0">
              <!-- Left Content -->
              <div class="col-md-8">
                <div class="publication-card__content"> 
                  <span class="publication-card__date text-uppercase"><?php echo $publication['meta_fields']['publication_date']; ?></span>
                  <h4 class="publication-card__title" data-publication-title><?php echo $publication['title']; ?></h4>
                  <a target="_blank" href="<?php echo $publication['meta_fields']['publication_link']; ?>" class="publication-card__link" rel="noopener noreferrer">
                    See Publication
                    <span class="publication-card__link-icon" aria-hidden="true">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M7 17L17 7M17 7H10M17 7V14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    </span>
                  </a>
                </div>
              </div>
              <!-- Right Preview -->
              <div class="col-md-4">
                <div class="publication-card__preview">
                  <img src="<?php echo $publication['meta_fields']['publication_image'] ? wp_get_attachment_image_url($publication['meta_fields']['publication_image'], 'medium') : ''; ?>" alt="<?php echo esc_attr($publication['title']); ?>" class="publication-card__preview-img" loading="lazy" />
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>
</section>

<style>
  .publication-listing {
    background-color: #ffffff;
    padding: 72px 0 140px !important;
  }

  .publication-listing .container {
    margin-left: auto;
    margin-right: auto;
  }

  .publication-listing__group {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .publication-listing__search {
    width: 100%;
    max-width: 830px;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin: 0 auto 32px auto;
    gap: 8px;
  }

  .publication-listing__search-toggle {
    height: 40px;
    padding: 0 14px;
    height: 40px;
    border-radius: 999px;
    border: none;
    background-color: #ffffff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 0 0 1px #E3E0E0;
    transition: box-shadow 0.2s ease, background-color 0.2s ease, transform 0.1s ease;
    gap: 8px;
  }

  .publication-listing__search-toggle:hover {
    background-color: #F5F5FF;
    box-shadow: 0 0 0 1px #C7C4FF;
  }

  .publication-listing__search-toggle:active {
    transform: scale(0.97);
  }

  .publication-listing__search-icon {
    width: 18px;
    height: 18px;
  }

  .publication-listing__search-label {
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 14px;
    line-height: 1;
    color: #9CA3AF;
    white-space: nowrap;
  }

  .publication-listing__search--open .publication-listing__search-label {
    display: none;
  }

  .publication-listing__search-input {
    width: 0;
    opacity: 0;
    padding: 0;
    border: none;
    border-radius: 999px;
    background-color: #F8FAFC;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    color: #0F0F0F;
    transition: width 0.25s ease, opacity 0.2s ease, padding 0.25s ease;
    pointer-events: none;
  }

  .publication-listing__search-input::placeholder {
    color: #9CA3AF;
  }

  .publication-listing__search--open .publication-listing__search-input {
    width: 260px;
    opacity: 1;
    padding: 10px 16px;
    pointer-events: auto;
  }

  .publication-listing__group .row {
    width: 100%;
  }

  .publication-listing__category {
    color: #0F0F0F;
    margin: 0;

    font-weight: 500;
    font-size: 20px;
    line-height: 170%;
    letter-spacing: 8%;

    max-width: 200px;
  }

  .publication-listing__divider {
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Publication Card */
  .publication-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    max-width: 830px;
    margin-left: auto;
    margin-right: auto;
  }

  .publication-card__content {
    padding: 40px;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .publication-card__date {
    font-weight: 400;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 1.2px;

    color: #6564DB;
    background: none;

    margin-bottom: 30px;
  }

  @supports ((-webkit-background-clip: text) or (background-clip: text)) {
    .publication-card__date {
      background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      -webkit-text-fill-color: transparent;
    }
  }

  .publication-card__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 24px;
    line-height: 160%;
    letter-spacing: 0.02em;
    color: #0F0F0F;
    margin: 0 0 30px 0;
    flex-grow: 1;
  }

  .publication-card__link {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 17px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #6564DB;
    text-decoration: underline;
    text-decoration-style: solid;
    text-underline-offset: 25%;
    text-decoration-thickness: 1px;
    text-decoration-skip-ink: auto;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
  }

  .publication-card__link:hover {
    color: #4948E1;
  }

  .publication-card__link-icon {
    display: inline-flex;
    align-items: center;
    margin-left: 4px;
    color: inherit;
  }

  .publication-card__link-icon svg {
    width: 14px;
    height: 14px;
  }

  /* Preview Section */
  .publication-card__preview {
    height: 100%;
    min-height: 200px;
    position: relative;
    overflow: hidden;
  }

  .publication-card__preview-img {
    width: 100%;
    height: 100%;
    min-height: 200px;
    object-fit: cover;
    display: block;
    vertical-align: middle;
  }

  .publication-card__preview::before {
    content: none;
  }

  .publication-card__icon {
    width: 30px;
    height: 30px;
    margin-bottom: 16px;
    position: relative;
    z-index: 0;
  }

  .publication-card__icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .publication-card__preview-title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #FFFFFF;
    max-width: 150px;
    margin: 0 0 auto 0;
    position: relative;
    z-index: 1;
  }

  .publication-card__tags {
    display: flex;
    flex-direction: column;
    gap: 4px;
    margin-top: 16px;
    position: relative;
    z-index: 1;
  }

  .publication-card__tags span {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #FFFFFFA8;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .publication-listing__category {
      margin-bottom: 24px;
    }

    .publication-listing__search {
      max-width: 100%;
    }
  }

  @media (max-width: 767px) {
    .publication-listing .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .publication-card__content {
      padding: 24px;
    }

    .publication-card__title {
      font-size: 1.1rem;
    }

    .publication-card__preview {
      min-height: 180px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const searchWrapper = document.querySelector('[data-publication-search]');
    if (!searchWrapper) return;

    const toggleButton = searchWrapper.querySelector('.publication-listing__search-toggle');
    const input = searchWrapper.querySelector('.publication-listing__search-input');
    const cards = Array.from(document.querySelectorAll('[data-publication-card]'));

    if (!toggleButton || !input || !cards.length) return;

    const wrapperOpenClass = 'publication-listing__search--open';

    function resetSearch() {
      input.value = '';
      cards.forEach(function (card) {
        card.style.display = '';
      });
    }

    function applyFilter() {
      const query = input.value.trim().toLowerCase();
      cards.forEach(function (card) {
        const titleEl = card.querySelector('[data-publication-title]');
        const titleText = titleEl ? titleEl.textContent.toLowerCase() : '';
        const matches = !query || titleText.indexOf(query) !== -1;
        card.style.display = matches ? '' : 'none';
      });
    }

    toggleButton.addEventListener('click', function () {
      if (searchWrapper.classList.contains(wrapperOpenClass)) {
        return;
      }
      searchWrapper.classList.add(wrapperOpenClass);
      toggleButton.setAttribute('aria-expanded', 'true');
      setTimeout(function () {
        input.focus();
      }, 150);
    });

    input.addEventListener('input', applyFilter);

    input.addEventListener('blur', function () {
      window.setTimeout(function () {
        if (searchWrapper.contains(document.activeElement)) {
          return;
        }
        if (input.value.trim() !== '') {
          return;
        }
        searchWrapper.classList.remove(wrapperOpenClass);
        toggleButton.setAttribute('aria-expanded', 'false');
      }, 0);
    });
  });
</script>