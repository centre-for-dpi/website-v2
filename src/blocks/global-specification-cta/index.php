<section class="redlof-block global-spec-cta py-5 py-lg-6">
  <div class="container">
    <div class="global-spec-cta__card">
      <div class="row align-items-center g-0">
        <!-- Left: Image -->
        <div class="col-lg-5 col-md-12">
          <div class="global-spec-cta__image">
            <img src="<?php echo Helper::getImagePath('images/global-specification-image.svg'); ?>" alt="Global Specifications" loading="lazy" />
          </div>
        </div>
        
        <!-- Right: Content -->
        <div class="col-lg-7 col-md-12">
          <div class="global-spec-cta__content">
            <h2 class="global-spec-cta__title">
              To fast track your journey, see the ready to use global specifications
            </h2>
            <a href="#" class="global-spec-cta__btn">Read global specifications</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Decorative Pattern -->
  <div class="global-spec-cta__pattern">
    <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-6.svg'); ?>" alt="" loading="lazy" />
  </div>
</section>

<style>
.global-spec-cta {
  background-color: #EDE9FE;
  position: relative;
  overflow: hidden;
}

.global-spec-cta__card {
  background-color: #ffffff;
  border-radius: 16px;
  overflow: hidden;
  position: relative;
  z-index: 1;
}

.global-spec-cta__image {
  height: 100%;
}

.global-spec-cta__image img {
  width: 100%;
  height: 100%;
  min-height: 280px;
  object-fit: cover;
}

.global-spec-cta__content {
  padding: 40px;
}

.global-spec-cta__title {
  font-size: 1.75rem;
  font-weight: 400;
  color: #1a1a2e;
  line-height: 1.4;
  margin: 0 0 32px 0;
}

.global-spec-cta__btn {
  display: inline-block;
  font-size: 0.9rem;
  font-weight: 500;
  color: #ffffff;
  background-color: #4948E1;
  padding: 14px 28px;
  border-radius: 8px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.global-spec-cta__btn:hover {
  background-color: #5453c7;
  color: #ffffff;
}

.global-spec-cta__pattern {
  position: absolute;
  right: 0;
  bottom: -400px;
  width: 300px;
  height: auto;
  opacity: 0.8;
  pointer-events: none;
}

.global-spec-cta__pattern img {
  width: 100%;
  height: auto;
}

/* Responsive */
@media (max-width: 991px) {
  .global-spec-cta__card {
    border-radius: 12px;
  }
  
  .global-spec-cta__image img {
    min-height: 220px;
    max-height: 280px;
  }
  
  .global-spec-cta__content {
    padding: 32px;
  }
  
  .global-spec-cta__title {
    font-size: 1.5rem;
    margin-bottom: 24px;
  }
  
  .global-spec-cta__pattern {
    width: 200px;
    opacity: 0.5;
  }
}

@media (max-width: 767px) {
  .global-spec-cta {
    padding: 32px 0;
  }
  
  .global-spec-cta__image img {
    min-height: 180px;
    max-height: 220px;
  }
  
  .global-spec-cta__content {
    padding: 24px;
  }
  
  .global-spec-cta__title {
    font-size: 1.25rem;
    margin-bottom: 20px;
  }
  
  .global-spec-cta__btn {
    padding: 12px 24px;
    font-size: 0.85rem;
  }
  
  .global-spec-cta__pattern {
    display: none;
  }
}

@media (max-width: 575px) {
  .global-spec-cta__image img {
    min-height: 160px;
    max-height: 200px;
  }
  
  .global-spec-cta__content {
    padding: 20px;
  }
  
  .global-spec-cta__title {
    font-size: 1.1rem;
    line-height: 1.5;
  }
  
  .global-spec-cta__btn {
    width: 100%;
    text-align: center;
  }
}
</style>
