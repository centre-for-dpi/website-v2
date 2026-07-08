<section class="redlof-block we-work-in-body py-5 py-lg-6">
  <div class="container">
    <div class="row">
      <!-- Left Sidebar Navigation -->
      <div class="col-lg-2 mb-4 mb-lg-0">
        <ul class="nav we-work-in-body__nav flex-column" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="we-work-in-body__nav-item text-uppercase" id="africa-tab" data-bs-toggle="tab"
              data-bs-target="#africa-content" type="button" role="tab" aria-controls="africa-content"
              aria-selected="false">AFRICA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="we-work-in-body__nav-item text-uppercase" id="asia-tab" data-bs-toggle="tab" data-bs-target="#asia-content"
              type="button" role="tab" aria-controls="asia-content" aria-selected="false">ASIA</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="we-work-in-body__nav-item text-uppercase active" id="latin-america-tab" data-bs-toggle="tab"
              data-bs-target="#latin-america-content" type="button" role="tab" aria-controls="latin-america-content"
              aria-selected="true">LATIN AMERICA & CARIBBEAN</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="we-work-in-body__nav-item text-uppercase" id="transregional-tab" data-bs-toggle="tab"
              data-bs-target="#transregional-content" type="button" role="tab" aria-controls="transregional-content"
              aria-selected="false">TRANSREGIONAL</button>
          </li>
        </ul>
      </div>

      <!-- Right Content Area -->
      <div class="col-lg-7 ms-lg-5">
        <div class="tab-content we-work-in-body__content">

          <!-- Africa Tab Content -->
          <div class="tab-pane fade" id="africa-content" role="tabpanel" aria-labelledby="africa-tab">
            <p class="we-work-in-body__paragraph">
              Africa is rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build more
              inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments, regional
              institutions, and local innovators to strengthen the foundations of DPI and enable sustainable digital
              transformation.
            </p>
            <p class="we-work-in-body__paragraph">
              In the private sector, we have supported some of the region's most influential companies in strengthening
              their digital capabilities, modernizing their technology stack, and adopting agile, data-driven ways of
              working.
            </p>
          </div>
          <!-- Asia Tab Content -->
          <div class="tab-pane fade" id="asia-content" role="tabpanel" aria-labelledby="asia-tab">
            <p class="we-work-in-body__paragraph">
              Asia is rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build more
              inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments, regional
              institutions, and local innovators to strengthen the foundations of DPI and enable sustainable digital
              transformation.
            </p>
            <p class="we-work-in-body__paragraph">
              In the private sector, we have supported some of the region's most influential companies in strengthening
              their digital capabilities, modernizing their technology stack, and adopting agile, data-driven ways of
              working.
            </p>
          </div>

          <!-- Latin America & Caribbean Tab Content -->
          <div class="tab-pane fade show active" id="latin-america-content" role="tabpanel"
            aria-labelledby="latin-america-tab">
            <p class="we-work-in-body__paragraph">
              Latin America is rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build more
              inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments, regional
              institutions, and local innovators to strengthen the foundations of DPI and enable sustainable digital
              transformation.
            </p>
            <p class="we-work-in-body__paragraph">
              In the private sector, we have supported some of the region's most influential companies in strengthening
              their digital capabilities, modernizing their technology stack, and adopting agile, data-driven ways of
              working.
            </p>
            <figure class="we-work-in-body__figure">
              <img src="<?php echo Helper::getImagePath('temp/we-work-in-team.png'); ?>"
                alt="CDPI team with Roberto Lopez and José Inostroza" loading="lazy" />
              <figcaption class="we-work-in-body__caption">
                (L-R) Roberto Lopez, Manager of the Inter-American Network of Digital Government, José Inostroza,
                Director de la Secretaría de Gobierno Digital (SGD) del Ministerio de Hacienda de la República de Chile
                and President 2024/2025 of the Inter-American Network of Digital Government with the CDPI team
              </figcaption>
            </figure>
          </div>

          <!-- Transregional Tab Content -->
          <div class="tab-pane fade" id="transregional-content" role="tabpanel" aria-labelledby="transregional-tab">
            <p class="we-work-in-body__paragraph">
              Across regions, we are rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build
              more inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments,
              regional institutions, and local innovators to strengthen the foundations of DPI and enable sustainable
              digital transformation.
            </p>
            <p class="we-work-in-body__paragraph">
              In the private sector, we have supported some of the region's most influential companies in strengthening
              their digital capabilities, modernizing their technology stack, and adopting agile, data-driven ways of
              working.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .we-work-in-body {
    background-color: #ffffff;
  }

  /* Navigation Sidebar */
  .we-work-in-body__nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .we-work-in-body__nav-item {
    font-size: 12px;
    font-weight: 500;
    color: #5E6979;
    text-decoration: none;
    padding: 12px 16px;
    border-radius: 6px;
    transition: all 0.3s ease;
    line-height: 170%;
    letter-spacing: 8%;
    background-color: transparent;
    border: none;
    text-align: left;
    cursor: pointer;
    width: 100%;
  }

  .we-work-in-body__nav-item:hover {
    background-color: #f9fafb;
    color: #0F0F0F;
  }

  .we-work-in-body__nav-item.active {
    background-color: #4948E1;
    color: #ffffff;
  }

  .we-work-in-body__nav-item.active:hover {
    background-color: #5453c7;
    color: #ffffff;
  }

  /* Content Area */
  .we-work-in-body__content {
    max-width: 100%;
  }

  .we-work-in-body__paragraph {
     font-size: 1rem;
    font-weight: 400;
    color: #4b5563;
    line-height: 1.8;
    margin-bottom: 24px;
  }

  .we-work-in-body__figure {
    margin: 40px 0 0 0;
  }

  .we-work-in-body__figure img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  .we-work-in-body__caption {
     font-size: 0.85rem;
    font-weight: 400;
    color: #6b7280;
    line-height: 1.6;
    font-style: italic;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .we-work-in-body__nav {
      flex-direction: row;
      flex-wrap: wrap;
      gap: 8px;
    }

    .we-work-in-body__nav-item {
      font-size: 0.85rem;
      padding: 10px 14px;
      flex: 1 1 auto;
      min-width: 0;
    }
  }

  @media (max-width: 767px) {
    .we-work-in-body__paragraph {
      font-size: 0.95rem;
    }

    .we-work-in-body__caption {
      font-size: 0.8rem;
    }

    .we-work-in-body__figure {
      margin: 32px 0 0 0;
    }
  }
</style>