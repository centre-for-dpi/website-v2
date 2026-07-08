<section class="redlof-block our-approach-body py-5 py-lg-6">
  <div class="container">
    <div class="row">
      <!-- Left Sidebar Navigation -->
      <div class="col-lg-2 mb-4 mb-lg-0">
        <ul class="nav our-approach-body__nav flex-column" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="our-approach-body__nav-item text-uppercase" id="advisory-tab" data-bs-toggle="tab"
              data-bs-target="#advisory-content" type="button" role="tab" aria-controls="advisory-content"
              aria-selected="false">Advisory</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="our-approach-body__nav-item text-uppercase active" id="daas-tab" data-bs-toggle="tab"
              data-bs-target="#daas-content" type="button" role="tab" aria-controls="daas-content"
              aria-selected="true">DaaS</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="our-approach-body__nav-item text-uppercase" id="our-plus-one-approach-tab" data-bs-toggle="tab"
              data-bs-target="#our-plus-one-approach-content" type="button" role="tab"
              aria-controls="our-plus-one-approach-content" aria-selected="false">Our +1 Approach</button>
          </li>
        </ul>
      </div>

      <!-- Right Content Area -->
      <div class="col-lg-7 ms-lg-5">
        <div class="tab-content our-approach-body__content">
          <!-- Advisory Tab Content -->
          <div class="tab-pane fade" id="advisory-content" role="tabpanel" aria-labelledby="advisory-tab">
            <p class="our-approach-body__paragraph">
              Advisory is rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build more
              inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments, regional
              institutions, and local innovators to strengthen the foundations of DPI and enable sustainable digital
              transformation.
            </p>
            <p class="our-approach-body__paragraph">
              In the private sector, we have supported some of the region's most influential companies in strengthening
              their digital capabilities, modernizing their technology stack, and adopting agile, data-driven ways of
              working.
            </p>
          </div>

          <!-- DaaS Tab Content -->
          <div class="tab-pane fade show active" id="daas-content" role="tabpanel" aria-labelledby="daas-tab">
            <p class="our-approach-body__paragraph">
              DaaS is rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build more
              inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments, regional
              institutions, and local innovators to strengthen the foundations of DPI and enable sustainable digital
              transformation.
            </p>
            <p class="our-approach-body__paragraph">
              In the private sector, we have supported some of the region's most influential companies in strengthening
              their digital capabilities, modernizing their technology stack, and adopting agile, data-driven ways of
              working.
            </p>
            <figure class="our-approach-body__figure">
              <img src="<?php echo Helper::getImagePath('temp/our-approach-team.png'); ?>"
                alt="CDPI team with Roberto Lopez and José Inostroza" loading="lazy" />
              <figcaption class="our-approach-body__caption">
                (L-R) Roberto Lopez, Manager of the Inter-American Network of Digital Government, José Inostroza,
                Director de la Secretaría de Gobierno Digital (SGD) del Ministerio de Hacienda de la República de Chile
                and President 2024/2025 of the Inter-American Network of Digital Government with the CDPI team
              </figcaption>
            </figure>
          </div>

          <!-- Our plus-one approach Tab Content -->
          <div class="tab-pane fade" id="our-plus-one-approach-content" role="tabpanel"
            aria-labelledby="our-plus-one-approach-tab">
            <p class="our-approach-body__paragraph">
              Our +1 approach is rapidly embracing the opportunities of Digital Public Infrastructure (DPI) to build
              more
              inclusive, resilient, and competitive digital economies. At CDPI, we partner with governments, regional
              institutions, and local innovators to strengthen the foundations of DPI and enable sustainable digital
              transformation.
            </p>
            <p class="our-approach-body__paragraph">
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
  .our-approach-body {
    background-color: #ffffff;
  }

  /* Navigation Sidebar */
  .our-approach-body__nav {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .our-approach-body__nav-item {
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

  .our-approach-body__nav-item:hover {
    background-color: #f9fafb;
    color: #0F0F0F;
  }

  .our-approach-body__nav-item.active {
    background-color: #4948E1;
    color: #ffffff;
  }

  .our-approach-body__nav-item.active:hover {
    background-color: #5453c7;
    color: #ffffff;
  }

  /* Content Area */
  .our-approach-body__content {
    max-width: 100%;
  }

  .our-approach-body__paragraph {
    font-size: 1rem;
    font-weight: 400;
    color: #4b5563;
    line-height: 1.8;
    margin-bottom: 24px;
  }

  .our-approach-body__figure {
    margin: 40px 0 0 0;
  }

  .our-approach-body__figure img {
    width: 100%;
    height: auto;
    border-radius: 8px;
    margin-bottom: 12px;
  }

  .our-approach-body__caption {
    font-size: 0.85rem;
    font-weight: 400;
    color: #6b7280;
    line-height: 1.6;
    font-style: italic;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .our-approach-body__nav {
      flex-direction: row;
      flex-wrap: wrap;
      gap: 8px;
    }

    .our-approach-body__nav-item {
      font-size: 0.85rem;
      padding: 10px 14px;
      flex: 1 1 auto;
      min-width: 0;
    }
  }

  @media (max-width: 767px) {
    .our-approach-body__paragraph {
      font-size: 0.95rem;
    }

    .our-approach-body__caption {
      font-size: 0.8rem;
    }

    .our-approach-body__figure {
      margin: 32px 0 0 0;
    }
  }
</style>