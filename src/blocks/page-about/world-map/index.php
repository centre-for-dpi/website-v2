<section class="world-map">
  <div class="world-map__wrapper">
    <img src="<?php echo Helper::getImagePath('images/world-map.svg'); ?>" alt="World Map" loading="lazy" />
    <!-- Divider -->
    <div class="world-map__divider mt-5"></div> 
</div>
</section>

<style>
.world-map {
  width: 100%;
}

.world-map__wrapper {
  width: 100%;
  line-height: 0;
  text-align: center;
}

.world-map__wrapper img {
  width: 100%;
  height: auto;
  object-fit: cover;
  margin: 0 auto;
}

.world-map__divider {
  height: 1px;
  background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
}
</style>
