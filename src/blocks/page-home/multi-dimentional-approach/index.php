<section class="multi-approach">
  <div class="container">
    <!-- Header -->
    <div class="text-center multi-approach__header">
      <div class="multi-approach__icon">
        <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-3.svg'); ?>" alt="" width="40" height="40" loading="lazy" />
      </div>
      <h2 class="multi-approach__title">Our approach to scaling DPI</h2>
      <!-- <p class="multi-approach__subtitle mx-auto">
        Three comprehensive approaches to help countries design, build, and scale digital public infrastructure that serves their citizens.
      </p> -->
    </div>

    <!-- Cards Grid -->
    <div class="row g-4 multi-approach__cards">
      <!-- Card 1: Advisory -->
      <div class="col-lg-4 col-md-6 multi-approach__col">
        <a class="approach-card approach-card--link h-100" href="/our-work">
          <div class="approach-card__image">
            <img src="<?php echo Helper::getImagePath('images/multi-approach-card-1.jpeg'); ?>" alt="Pro Bono Advisory" class="img-fluid" loading="lazy" />
          </div>
          <div class="approach-card__content">
            <h3 class="approach-card__title text-uppercase fw-bold mb-3">advisory ON Tech architecture & strategy </h3>
            <p class="approach-card__desc mb-4">
            Helping countries make the key technology & rollout decisions that shape DPI success.
                      </p>
            <ul class="approach-card__list mb-4">
              <li>Recommendations on DPI blocks different departments can build</li>
              <li>First use-cases and sectors to drive adoption</li>
              <li>Aligning  and stakeholder engagement</li>
            </ul>
            <div class="approach-card__link">
              Learn more <i class="fa-solid fa-arrow-right ms-2"></i>
            </div>
          </div>
        </a>
      </div>

      <!-- Card 2: DaaS -->
      <div class="col-lg-4 col-md-6 multi-approach__col">
        <a class="approach-card approach-card--link h-100" href="/daas">
          <div class="approach-card__image">
            <img src="<?php echo Helper::getImagePath('images/multi-approach-card-2.jpeg'); ?>" alt="DaaS Package" class="img-fluid" loading="lazy" />
          </div>
          <div class="approach-card__content">
            <h3 class="approach-card__title text-uppercase fw-bold mb-3">Orchestrating COUNTRY execution</h3>
            <p class="approach-card__desc mb-4">
              Helping countries move from intent to implementations.
            </p>
            <ul class="approach-card__list mb-4">
              <li>Accelerated, secure deployments</li>
              <li>Reusable artefacts & open source code</li>
              <li>Bootcamps & Co-creation workshops</li>
              <li>Designed to scale</li>
            </ul>
            <div class="approach-card__link">
              Learn more <i class="fa-solid fa-arrow-right ms-2"></i>
            </div>
          </div>
        </a>
      </div>

      <!-- Card 3: Knowledge & Resources -->
      <div class="col-lg-4 col-md-6 multi-approach__col">
        <a class="approach-card approach-card--link h-100" href="/resources">
          <div class="approach-card__image">
            <img src="<?php echo Helper::getImagePath('images/multi-approach-card-3.jpeg'); ?>" alt="DPI Knowledge & Resources" class="img-fluid" loading="lazy" />
          </div>
          <div class="approach-card__content">
            <h3 class="approach-card__title text-uppercase fw-bold mb-3">REUSABLE knowledge & resources</h3>
            <p class="approach-card__desc mb-4">
            Helping teams <strong>understand</strong>, explain, and apply DPI in real country contexts.
            </p>
            <ul class="approach-card__list mb-4">
              <li>Technical notes and blueprints</li>
              <li>Masterclasses to drive alignment</li>
              <li>Country case studies for evidence of success</li>
              <li>AI tools for simulation and execution</li>
            </ul>
            <div class="approach-card__link">
              Learn more <i class="fa-solid fa-arrow-right ms-2"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<style>
.multi-approach {
  background-color: #ffffff;
  padding: 7.25rem 0 120px;
  position: relative;
  overflow: hidden;
}

.multi-approach::before {
  content: "";
  position: absolute;
  left: 0;
  bottom: 0;
  width: 584px;
  height: 239px;
  background: url("<?php echo Helper::getImagePath('patterns/hero-pattern-3.svg'); ?>") no-repeat left bottom;
  background-size: contain;
  transform: rotate(-8deg) translate(-6%, 18%);
  transform-origin: left bottom;
  opacity: 0.9;
  pointer-events: none;
  z-index: 0;
}

.multi-approach > .container {
  position: relative;
  z-index: 1;
}

.multi-approach__header {
  margin-bottom: 5rem;
}

.multi-approach__icon {
  display: inline-block;
  margin-bottom: 1.25rem;
}

.multi-approach__title {
  margin-bottom: 1.25rem;
  color: #101828;
  font-family: "Outfit", sans-serif;
  font-size: 2rem;
  font-weight: 300;
  line-height: 2.8rem;
  letter-spacing: 0;
  text-align: center;
}

.multi-approach__subtitle {
  max-width: 34.125rem;
  margin-bottom: 0;
  color: #5e6979;
  font-family: "Outfit", sans-serif;
  font-size: 0.9375rem;
  font-weight: 400;
  line-height: 1.59375rem;
  letter-spacing: 0.01875rem;
  text-align: center;
}

.multi-approach__cards {
  row-gap: 1.5rem;
}

.multi-approach__col {
  display: flex;
}

/* Approach Card Styles */
.approach-card {
  display: flex;
  flex-direction: column;
  width: 100%;
  max-width: 25rem;
  background: #ffffff;
  border: 1px solid #d6e1f1;
  border-radius: 0.75rem;
  overflow: hidden;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
}

.approach-card--link {
  text-decoration: none;
  color: inherit;
  cursor: pointer;
}

.approach-card--link:hover {
  color: inherit;
  border-color: #c7c6f4;
  box-shadow: 0 0.75rem 1.5rem rgba(16, 24, 40, 0.08);
  transform: translateY(-2px);
}

.approach-card__image {
  overflow: hidden;
  margin: -1px -1px 0 -1px;
}

.approach-card__image img {
  width: calc(100% + 2px);
  height: 11.25rem;
  object-fit: cover;
  border-radius: 0.75rem 0.75rem 0 0;
}

.approach-card__content {
  padding: 2.5rem;
}

.approach-card__title {
  margin-bottom: 1.25rem;
  color: #101828;
  font-family: "Outfit", sans-serif;
  font-size: 1.1809375rem;
  font-weight: 600;
  line-height: 2.007625rem;
  letter-spacing: 0.0944765625rem;
  text-transform: uppercase;
}

.approach-card__desc {
  margin-bottom: 1.25rem;
  color: #5e6979;
  font-family: "Outfit", sans-serif;
  font-size: 0.9375rem;
  font-weight: 400;
  line-height: 1.59375rem;
  letter-spacing: 0.01875rem;
}

.approach-card__list {
  margin: 0 0 1.25rem;
  padding-left: 1rem;
  list-style: disc;
}

.approach-card__list li {
  margin-bottom: 0.375rem;
  color: #101828;
  font-family: "Outfit", sans-serif;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.4875rem;
  letter-spacing: 0.02625rem;
}

.approach-card__list li:last-child {
  margin-bottom: 0;
}

.approach-card__link {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: #6564db;
  text-decoration: none;
  font-family: "Outfit", sans-serif;
  font-weight: 400;
  font-size: 0.875rem;
  line-height: 1.4rem;
  transition: color 0.2s ease;
}

.approach-card__link:hover {
  color: #544dd2;
}

@media (max-width: 1199px) {
  .multi-approach .container {
    /* padding-left: 3.5rem;
    padding-right: 3.5rem; */
  }

  .approach-card {
    max-width: 100%;
  }
}

@media (max-width: 991px) {
  .multi-approach {
    padding: 5.625rem 0;
  }

  .multi-approach .container {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }

  .multi-approach__header {
    margin-bottom: 2.5rem;
  }
}

@media (max-width: 767px) {
  .multi-approach {
    padding: 5.625rem 0;
  }

  .multi-approach .container {
    padding-left: 1rem;
    padding-right: 1rem;
  }

  .multi-approach__title {
    font-weight: 300;
    line-height: 2.6rem;
    letter-spacing: -0.04rem;
  }

  .multi-approach__subtitle {
    max-width: 19.375rem;
  }

  .approach-card {
    margin: 0 auto;
  }

  .approach-card__content {
    padding: 2.5rem;
  }
}
</style>
