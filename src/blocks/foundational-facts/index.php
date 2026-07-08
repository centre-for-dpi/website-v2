<?php

$foundationalFactsHandler = new CustomPost('foundational_facts', null);

$foundationalFacts = $foundationalFactsHandler->getListOfPosts([
    'meta_fields'
]);

?>

<section class="foundational-facts">
  <div class="container">
    <!-- Header -->
    <div class="row align-items-end foundational-facts__header">
      <div class="col-lg-8 foundational-facts__header-content">
        <div class="foundational-facts__icon mb-3">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-2.svg'); ?>" alt="" width="32" height="32" loading="lazy" />
        </div>
        <h2 class="foundational-facts__title h2 fw-normal mb-3">Snapshot of our work</h2>
      </div>
      <div class="col-lg-4 text-lg-end foundational-facts__header-cta">
        <a href="/resources/" class="btn btn-primary foundational-facts__cta">
        Explore Resources <i class="fa-solid fa-arrow-right ms-2"></i>
        </a>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="row g-4">
      <?php foreach ($foundationalFacts as $index => $foundationalFact) : ?>
        <div class="col-12 col-md-6 col-lg-3">
          <?php if (3 === $index) : ?>
            <a href="https://docs.cdpi.dev/" class="text-decoration-none d-block h-100">
          <?php endif; ?>
          <div class="fact-card">
            <div class="fact-card__value"><?php echo $foundationalFact['meta_fields']['value']; ?></div>
            <div class="fact-card__divider"></div>
            <p class="fact-card__desc"><?php echo $foundationalFact['meta_fields']['description']; ?></p>
          </div>
          <?php if (3 === $index) : ?>
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
.foundational-facts {
  background-color: #EDECFE;
  padding: 6.25rem 0 8.75rem;
}

.foundational-facts__header {
  justify-content: space-between;
  gap: 2.5rem;
  margin-bottom: 3.5rem;
  flex-wrap: nowrap;
}

.foundational-facts__header-content {
  flex: 1 1 auto;
  max-width: 59.875rem;
}

.foundational-facts__header-cta {
  flex: 0 0 auto;
  align-self: flex-end;
}

.foundational-facts__title {
  color: #101828;
  font-weight: 300;
  font-size: 2rem;
  line-height: 2.8rem;
  letter-spacing: 0;
}

.foundational-facts__subtitle {
  font-size: 0.9375rem;
  line-height: 1.546875rem;
  letter-spacing: 0.01875rem;
  max-width: 59.875rem;
  color: #5E6979;
}

.foundational-facts__cta {
  background-color: #4B4AEA;
  border-color: #4948E1;
  border-radius: 0.4375rem;
  padding: 1rem 1.5rem;
  font-weight: 500;
  font-size: 1rem;
  line-height: 1.6rem;
  letter-spacing: 0.01rem;
  white-space: nowrap;
}

.foundational-facts__cta:hover {
  background-color: #1C1AE4;
  border-color: #1C1AE4;
}

/* Fact Card Styles */
.fact-card {
  background: #ffffff;
  border-radius: 0.5rem;
  padding: 2.25rem 1rem;
  width: 297px;
  height: 241px;
  max-width: 100%;
}

.fact-card__value {
  /* font-family: "Lora", serif; */
  font-family: "Outfit", system-ui, sans-serif;
  font-size: 2rem;
  font-weight: 400;
  color: #101828;
  line-height: 2rem;
  letter-spacing: -0.02rem;
  margin-bottom: 1rem;
}

.fact-card__divider {
  width: 100%;
  height: 1px;
  background: linear-gradient(90deg, #6564DB 0%, rgba(214, 225, 241, 0.2) 100%);
  margin-bottom: 1rem;
}

.fact-card__desc {
  font-size: 0.9375rem;
  color: #101828;
  font-weight: 300;
 /* line-height: 1.5rem; */
  letter-spacing: 0.01875rem;
  margin-bottom: 0;
}

@media (max-width: 991px) {
  .foundational-facts {
    padding: 4rem 0 5rem;
  }

  .foundational-facts__header {
    margin-bottom: 3rem;
    flex-wrap: wrap;
  }

  .foundational-facts__header-cta {
    width: 100%;
    text-align: left;
  }
}

@media (max-width: 575px) {
  .foundational-facts .container {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
}
</style>
