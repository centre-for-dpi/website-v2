<?php
/**
 * Backed by section – logos of key backers.
 */
?>

<section class="redlof-block backed-by">
  <div class="container">
    <div class="backed-by__inner">
      <div class="backed-by__grid">
        <div class="backed-by__cell backed-by__intro">
          <h3 class="team-section__group-label text-uppercase">Backed by</h3>
          <p class="backed-by__subtext">
          Our work is supported by visionary partners who share our mission to build inclusive and resilient 
          digital public infrastructure.
          </p>
        </div>

       
        <div class="backed-by__cell backed-by__logo-item">
          <img src="<?php echo Helper::getImagePath('logos/gates-foundation.png'); ?>" alt="Bill & Melinda Gates Foundation" loading="lazy" />
        </div>
        <div class="backed-by__cell backed-by__logo-item">
          <img src="<?php echo Helper::getImagePath('logos/co-develop.png'); ?>" alt="Co-Develop" loading="lazy" />
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .backed-by {
    padding: 8.75rem 0 8.75rem; /* 140px top, 140px bottom */
    background-color: #ffffff;
  }

  .backed-by__inner {
    margin-left: 0;
    margin-right: 0;
  }

  .backed-by__grid {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 6.875rem; /* 110px between items from Figma */
    min-width: 0;
  }

  .backed-by__cell {
    flex: 1 1 0;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .backed-by__intro {
    flex: 0 0 auto;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    color: #0f0f0f;
    text-align: left;
    min-width: 0;
  }

  .backed-by__subtext {
    font-family: 'Outfit', sans-serif;
    font-size: 0.875rem;      /* 14px */
    font-weight: 400;
    line-height: 1.7;
    color: #5E6979;
    margin: 0.75rem 0 0;
    max-width: 14.5rem;       /* match open positions subtext width */
    overflow-wrap: break-word;
  }

  .backed-by__logo-item {
    justify-content: center;
  }

  .backed-by__logo-item img {
    height: 102px;
    width: auto;
    display: block;
  }

  @media (max-width: 1199px) {
    .backed-by__inner {
      margin-left: 1.5rem;
      margin-right: 1.5rem;
    }

    .backed-by__grid {
      gap: 4rem;
      flex-wrap: wrap;
    }
  }

  @media (max-width: 767px) {
    .backed-by {
      padding: 3.5rem 0 3.5rem;
    }

    .backed-by__grid {
      justify-content: center;
      row-gap: 2rem;
      width: 100%;
    }

    .backed-by__intro {
      flex: 0 0 100%;
      max-width: 100%;
      width: 100%;
    }

    .backed-by__subtext {
      max-width: 100%;
      width: 100%;
    }
  }
</style>

