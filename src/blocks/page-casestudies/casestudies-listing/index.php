<?php



// Initialize for a specific post type
$postHandler = new CustomPost('case_studies', 'category');

$posts = $postHandler->getListOfPosts(["thumbnail", "published_date","author"]);

?>



<section class="redlof-block casestudies-listing">
  <div class="container">
    <!-- Search -->
    <div class="casestudies-listing__search" data-casestudies-search>
      <button type="button" class="casestudies-listing__search-toggle" aria-label="Search case studies" aria-expanded="false">
        <svg class="casestudies-listing__search-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <circle cx="8" cy="8" r="4.5" stroke="#0F0F0F" stroke-width="1.2" />
          <line x1="11.1818" y1="11.1818" x2="14" y2="14" stroke="#0F0F0F" stroke-width="1.2" stroke-linecap="round" />
        </svg>
        <span class="casestudies-listing__search-label" aria-hidden="true">Search</span>
      </button>
      <input
        type="text"
        class="casestudies-listing__search-input"
        placeholder="Search case studies"
        aria-label="Search case studies by title"
      />
    </div>
    <!-- Case Studies Cards Grid -->
    <?php
      $posts_per_page = 9;
      $total_posts    = is_array($posts) ? count($posts) : 0;
      $total_pages    = $posts_per_page > 0 ? (int) ceil($total_posts / $posts_per_page) : 0;
    ?>
    <div class="casestudies-listing__row" data-casestudies-list>
      <?php foreach ($posts as $index => $post): ?>
        <article class="case-study-card" data-casestudy-card data-index="<?php echo (int) $index; ?>">
          <div class="case-study-card__image">
            <img src="<?php echo !empty($post['thumbnail']) ? $post['thumbnail'] : Helper::getImagePath('temp/news-1.png'); ?>" alt="<?php echo esc_attr($post['title']); ?>" loading="lazy" />
          </div>
          <div class="case-study-card__meta">
            <span class="case-study-card__date text-uppercase"><?php echo $post['published_date']; ?></span>
          </div>
          <h3 class="case-study-card__title" data-casestudy-title>
            <a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a>
          </h3>
          <span class="case-study-card__author"><?php echo isset($post['author']['first_name']) ? $post['author']['first_name'] . ' ' . $post['author']['last_name'] : ''; ?></span>
        </article>
      <?php endforeach; ?>
    </div>

      
    <!-- Pagination (client-side controlled) -->
    <?php if ($total_pages > 1): ?>
      <nav class="casestudies-listing__pagination" aria-label="Case Studies pagination" data-casestudies-pagination>
        <ul class="pagination justify-content-center">
          <li class="page-item disabled" data-page-prev>
            <span class="page-link page-link--arrow page-link--prev" aria-hidden="true">
              <svg class="casestudies-listing__pagination-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <path d="M11 4.5L7 9L11 13.5" stroke="#D0D5DD" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </li>

          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?php echo ($i === 1) ? 'active' : ''; ?>" data-page-number="<?php echo (int) $i; ?>">
              <button type="button" class="page-link"><?php echo $i; ?></button>
            </li>
          <?php endfor; ?>

          <li class="page-item" data-page-next>
            <button type="button" class="page-link page-link--arrow page-link--next" aria-label="Next">
              <svg class="casestudies-listing__pagination-icon" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
                <path d="M7 4.5L11 9L7 13.5" stroke="#5E6979" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  </div>

</section>

<style>
  .casestudies-listing {
    background-color: #ffffff;
    padding: 72px 0 128px;
  }

  .casestudies-listing .container {
    --casestudies-cards-max-width: 1114px; /* 3 * 350 + 2 * 32 gap */
  }

  .casestudies-listing__search {
    width: 100%;
    max-width: var(--casestudies-cards-max-width);
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin: 0 auto 32px auto;
    gap: 8px;
  }

  .casestudies-listing__search-toggle {
    height: 40px;
    padding: 0 14px;
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

  .casestudies-listing__search-toggle:hover {
    background-color: #F5F5FF;
    box-shadow: 0 0 0 1px #C7C4FF;
  }

  .casestudies-listing__search-toggle:active {
    transform: scale(0.97);
  }

  .casestudies-listing__search-icon {
    width: 18px;
    height: 18px;
  }

  .casestudies-listing__search-label {
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 14px;
    line-height: 1;
    color: #9CA3AF;
    white-space: nowrap;
  }

  .casestudies-listing__search--open .casestudies-listing__search-label {
    display: none;
  }

  .casestudies-listing__search-input {
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

  .casestudies-listing__search-input::placeholder {
    color: #9CA3AF;
  }

  .casestudies-listing__search--open .casestudies-listing__search-input {
    width: 260px;
    opacity: 1;
    padding: 10px 16px;
    pointer-events: auto;
  }

  .casestudies-listing__row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 32px;
    max-width: var(--casestudies-cards-max-width);
    margin-left: auto;
    margin-right: auto;
    margin-top: 64px;
  }

  /* Tab Navigation */
  .casestudies-listing__nav-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 48px;
    padding: 0px 50px;
  }

  .casestudies-listing__nav {
    display: flex;
    gap: 8px;
    border: none;
    flex-wrap: nowrap;
    justify-content: center;
  }

  .casestudies-listing__nav .nav-item {
    list-style: none;
  }

  .casestudies-listing__tab {
    color: #5E6979;
    background-color: #FAF5FF;

    border: none;
    padding: 10px 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
    white-space: nowrap;

    font-weight: 500;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 8%;
  }

  .casestudies-listing__tab:hover {
    color: #4948E1;
    border-color: #4948E1;
  }

  .casestudies-listing__tab.active {
    background-color: #4948E1;
    color: #ffffff;
    border-color: #4948E1;
  }

  .casestudies-listing__nav-arrow {

    height: 40px;
    width: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;

    color: #5E6979;
    background-color: #FAF5FF;

    border: none;
    padding: 10px 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    border-radius: 8px;
    white-space: nowrap;
  }

  .casestudies-listing__nav-arrow:hover {
    border-color: #4948E1;
    color: #4948E1;
  }

  .casestudies-listing__nav-arrow i {
    font-size: 0.9rem;
    color: #1a1a2e;
  }

  /* Case Study Card */
  .case-study-card {
    display: flex;
    flex-direction: column;
    width: 21.875rem;  /* 350px */
    max-width: 100%;
    height: 26.375rem; /* 422px */
  }

  .case-study-card__image {
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
  }

  .case-study-card__image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }

  .case-study-card:hover .case-study-card__image img {
    transform: scale(1.05);
  }

  .case-study-card__meta {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
  }

  .case-study-card__category {
    font-size: 0.75rem;
    font-weight: 500;
    color: #6564DB;
    letter-spacing: 0.05em;
  }

  .case-study-card__separator {
    color: #9ca3af;
    font-size: 0.75rem;
  }

  .case-study-card__date {
    font-size: 0.75rem;
    font-weight: 400;
    color: #6b7280;
  }

  .case-study-card__title {
    font-size: 1.1rem;
    font-weight: 500;
    line-height: 1.5;
    margin: 0 0 12px 0;
  }

  .case-study-card__title a {
    color: #1a1a2e;
    text-decoration: underline;
    text-underline-offset: 3px;
    transition: color 0.3s ease;
  }

  .case-study-card__title a:hover {
    color: #4948E1;
  }

  .case-study-card__author {
    font-size: 0.9rem;
    font-weight: 400;
    color: #6b7280;
  }

  .casestudies-listing__divider {
    height: 1px;
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Pagination */
  .casestudies-listing__pagination .pagination {
    gap: 6px;
  }

  .casestudies-listing__pagination .page-link {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    font-weight: 500;
    color: #5E6979;
    border: none;
    background-color: #ffffff;
    transition: all 0.3s ease;
    padding: 0;
  }

  .casestudies-listing__pagination .page-link:hover {
    color: #5E6979;
    background-color: #ffffff;
  }

  .casestudies-listing__pagination .page-item.active .page-link {
    background-color: #4948E1;
    color: #ffffff;
  }

  .casestudies-listing__pagination .page-link--arrow {
    color: #5E6979;
  }

  .casestudies-listing__pagination .page-item.disabled .page-link--arrow {
    cursor: default;
    opacity: 0.5;
  }

  .casestudies-listing__pagination-icon {
    width: 18px;
    height: 18px;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .casestudies-listing__nav {
      flex-wrap: nowrap;
      overflow-x: auto;
      justify-content: flex-start;
      padding-bottom: 8px;
      -webkit-overflow-scrolling: touch;
    }

    .casestudies-listing__nav::-webkit-scrollbar {
      display: none;
    }
  }

  @media (max-width: 767px) {
    .casestudies-listing__tab {
      padding: 10px 18px;
      font-size: 0.75rem;
    }

    .case-study-card {
      width: 100%;
      max-width: 21.875rem; /* 350px */
      height: auto;
      margin-left: auto;
      margin-right: auto;
    }

    .case-study-card__title {
      font-size: 1rem;
    }

    .case-study-card__image img {
      height: 180px;
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.casestudies-listing__tab');
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (t) {
          t.classList.remove('active');
        });
        this.classList.add('active');
      });
    });

    // Client-side pagination + search
    const searchWrapper = document.querySelector('[data-casestudies-search]');
    const cards = Array.from(document.querySelectorAll('[data-casestudy-card]'));
    const pagination = document.querySelector('[data-casestudies-pagination]');

    if (!cards.length) return;

    const pageSize = 9;
    let currentPage = 1;

    const pageItems = pagination ? pagination.querySelectorAll('[data-page-number]') : [];
    const prevItem = pagination ? pagination.querySelector('[data-page-prev]') : null;
    const nextItem = pagination ? pagination.querySelector('[data-page-next]') : null;

    function applyPagination() {
      const totalPages = Math.max(1, Math.ceil(cards.length / pageSize));
      if (currentPage > totalPages) currentPage = totalPages;

      cards.forEach(function (card, index) {
        const start = (currentPage - 1) * pageSize;
        const end = start + pageSize;
        card.style.display = (index >= start && index < end) ? '' : 'none';
      });

      if (pagination) {
        pageItems.forEach(function (item) {
          const pageNum = parseInt(item.getAttribute('data-page-number'), 10);
          if (pageNum === currentPage) item.classList.add('active'); else item.classList.remove('active');
        });

        if (prevItem) {
          if (currentPage === 1) prevItem.classList.add('disabled'); else prevItem.classList.remove('disabled');
        }
        if (nextItem) {
          if (currentPage === totalPages) nextItem.classList.add('disabled'); else nextItem.classList.remove('disabled');
        }
      }
    }

    function showAllForSearch(matchesCallback) {
      cards.forEach(function (card) {
        const titleEl = card.querySelector('[data-casestudy-title]');
        const titleText = titleEl ? titleEl.textContent.toLowerCase() : '';
        const matches = matchesCallback ? matchesCallback(titleText) : true;
        card.style.display = matches ? '' : 'none';
      });
    }

    function resetSearchAndPagination() {
      if (searchWrapper) {
        const input = searchWrapper.querySelector('.casestudies-listing__search-input');
        if (input) input.value = '';
        searchWrapper.classList.remove('casestudies-listing__search--open');
      }
      if (pagination) pagination.style.display = '';
      currentPage = 1;
      applyPagination();
    }

    // Initial page render
    applyPagination();

    // Wire pagination controls
    if (pagination) {
      pageItems.forEach(function (item) {
        item.addEventListener('click', function () {
          const pageNum = parseInt(this.getAttribute('data-page-number'), 10);
          if (!isNaN(pageNum)) {
            currentPage = pageNum;
            applyPagination();
          }
        });
      });

      if (prevItem) {
        prevItem.addEventListener('click', function () {
          if (this.classList.contains('disabled')) return;
          currentPage = Math.max(1, currentPage - 1);
          applyPagination();
        });
      }

      if (nextItem) {
        nextItem.addEventListener('click', function () {
          if (this.classList.contains('disabled')) return;
          const totalPages = Math.max(1, Math.ceil(cards.length / pageSize));
          currentPage = Math.min(totalPages, currentPage + 1);
          applyPagination();
        });
      }
    }

    // Search behaviour
    if (searchWrapper) {
      const toggleButton = searchWrapper.querySelector('.casestudies-listing__search-toggle');
      const input = searchWrapper.querySelector('.casestudies-listing__search-input');
      const wrapperOpenClass = 'casestudies-listing__search--open';

      function clearSearch() {
        if (!input) return;
        input.value = '';
        if (pagination) pagination.style.display = '';
        applyPagination();
      }

      if (toggleButton && input) {
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

        input.addEventListener('input', function () {
          const query = input.value.trim().toLowerCase();
          if (!query) {
            if (pagination) pagination.style.display = '';
            applyPagination();
            return;
          }

          if (pagination) pagination.style.display = 'none';
          showAllForSearch(function (titleText) {
            return titleText.indexOf(query) !== -1;
          });
        });

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
      }
    }
  });
</script>