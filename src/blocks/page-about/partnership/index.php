<section class="redlof-block partnership">
  <div class="container">
    <div class="row">
      <!-- Left Column -->
      <div class="col-lg-5 mb-4 mb-lg-0">
        <span class="partnership__label">Partnership</span>
        <h2 class="partnership__title">Global partners driving<br> DPI forward</h2>
      </div>

      <!-- Right Column -->
      <div class="col-lg-6 offset-lg-1">
        <div class="partnership__content">
          <p class="partnership__text mb-4">
            CDPI provides pro bono advisory services for countries building DPI, supporting both new implementations and upgrades to existing systems.
          </p>
          <p class="partnership__text mb-0">
            Our three pillars of engagement help countries effectively design, build, and sustain their DPI.
          </p>
        </div>
      </div>
    </div>

    <!-- Divider -->
    <div class="partnership__divider"></div>
  </div>
</section>

<style>
  .partnership {
    background-color: #ffffff;
    padding: 7.75rem 0 5rem; /* 124px top, 80px bottom (desktop Figma) */
  }

  /* Left column: label + title */
  .partnership__label {
    display: inline-block;
    font-family: 'Outfit', sans-serif;
    font-size: 0.75rem;        /* 12px */
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.075rem;  /* 1.2px */
    text-transform: uppercase;
    background: linear-gradient(90deg, #9810fa 0%, #6564db 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
    margin-bottom: 1.5rem;     /* 24px */
  }

  .partnership__title {
    font-family: 'Outfit', sans-serif;
    font-size: 2.625rem;       /* 42px */
    font-weight: 400;
    line-height: 1.25;
    letter-spacing: -0.0525rem;/* -0.84px */
    color: #0f0f0f;
    margin: 0;
  }

  /* Right column: body copy */
  .partnership__content {
    font-family: 'Outfit', sans-serif;
    font-size: 1.0625rem;      /* 17px */
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.02125rem;/* 0.34px */
    color: #5e6979;
  }

  .partnership__text {
    margin: 0 0 2.5rem;        /* 40px gap between paragraphs */
  }

  .partnership__text:last-of-type {
    margin-bottom: 0;
  }

  .partnership__divider {
    height: 1px;
    margin-top: 5rem;          /* 80px from Figma */
    background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  }

  /* Tablet / small desktop */
  @media (max-width: 991px) {
    .partnership__title {
      font-size: 2.625rem;     /* keep 42px, just remove <br> on small */
    }

    .partnership__title br {
      display: none;
    }
  }

  /* Mobile: padding & container only (typography same as desktop Figma) */
  @media (max-width: 575px) {
    .partnership {
      padding: 4.5rem 0 4rem;  /* 72px top, 64px bottom (mobile Figma) */
    }

    .partnership .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }
</style>
