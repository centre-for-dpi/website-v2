<section class="redlof-block continents py-4">
  <div class="container">
    <div class="continents__nav">
      <!-- Africa -->
      <div class="continents__item">
        <div class="continents__map">
          <img src="<?php echo Helper::getImagePath('images/maps/africa-map.svg'); ?>" alt="Africa" width="88" height="104" loading="lazy" />
        </div>
        <span class="continents__name">Africa</span>
        <i class="fa-solid fa-chevron-right continents__arrow"></i>
        <span class="continents__underline"></span>
      </div>

      <!-- Asia -->
      <div class="continents__item">
        <div class="continents__map">
          <img src="<?php echo Helper::getImagePath('images/maps/asia-map.svg'); ?>" alt="Asia" width="88" height="104" loading="lazy" />
        </div>
        <span class="continents__name">Asia</span>
        <i class="fa-solid fa-chevron-right continents__arrow"></i>
        <span class="continents__underline"></span>
      </div>

      <!-- Latin America & Caribbean (Active) -->
      <div class="continents__item continents__item--active">
        <div class="continents__map">
          <img src="<?php echo Helper::getImagePath('images/maps/latin-america-caribbean.svg'); ?>" alt="Latin America & Caribbean" width="88" height="104" loading="lazy" />
        </div>
        <span class="continents__name">Latin America & Caribbean</span>
        <i class="fa-solid fa-chevron-right continents__arrow"></i>
        <span class="continents__underline"></span>
      </div>

      <!-- Transregional -->
      <div class="continents__item">
        <div class="continents__map">
          <img src="<?php echo Helper::getImagePath('images/maps/transreginal.svg'); ?>" alt="Transregional" width="88" height="104" loading="lazy" />
        </div>
        <span class="continents__name">Transregional</span>
        <i class="fa-solid fa-chevron-right continents__arrow"></i>
        <span class="continents__underline"></span>
      </div>
    </div>
  </div>
</section>

<style>
.continents {
  background-color: #ffffff;
}

.continents__nav {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 48px;
  flex-wrap: wrap;
}

.continents__item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 8px;
  position: relative;
  text-align: center;
}

.continents__map {
  width: 88px;
  height: 104px;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0.4;
}

.continents__map img {
  width: 100%;
  height: auto;
  max-height: 100%;
  object-fit: contain;
}

.continents__name {
  font-size: 0.9rem;
  font-weight: 500;
  color: #0F0F0F;
}

.continents__arrow {
  font-size: 0.7rem;
  color: #9ca3af;
}

.continents__underline {
  position: absolute;
  bottom: -8px;
  left: 0;
  right: 0;
  height: 2px;
  background-color: #e5e7eb;
}

/* Active State */
.continents__item--active .continents__map {
  opacity: 1;
}

.continents__item--active .continents__name {
  background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.continents__item--active .continents__arrow {
  color: #6564DB;
}

.continents__item--active .continents__underline {
  height: 3px;
  background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
}

/* Responsive */
@media (max-width: 991px) {
  .continents__nav {
    gap: 32px;
  }

  .continents__map {
    width: 50px;
    height: 35px;
  }

  .continents__name {
    font-size: 0.85rem;
  }
}

@media (max-width: 767px) {
  .continents__nav {
    gap: 16px;
  }

  .continents__map {
    width: 40px;
    height: 30px;
  }

  .continents__name {
    font-size: 0.8rem;
  }

  .continents__arrow {
    font-size: 0.65rem;
  }
}
</style>
