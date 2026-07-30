<section class="redlof-block work-banner">
  <div class="work-banner__wrapper">
    <img src="<?php echo Helper::getImagePath('images/our-work/our-work-banner-v1.jpg'); ?>" alt="Our work" loading="lazy" />
  </div>
</section>

<style>
.work-banner {
  width: 100%;
  margin: 0;
  padding: 0;
}

.work-banner__wrapper {
  width: 100%;
  line-height: 0;
  overflow: hidden;
}

.work-banner__wrapper img {
  width: 100%;
  height: auto;
  display: block;
  object-fit: cover;
}

@media (min-width: 768px) {
  .work-banner__wrapper {
    max-height: 580px;
  }

  .work-banner__wrapper img {
    height: 580px;
    width: 100%;
    object-fit: cover;
    object-position: center;
  }
}

/* Responsive */
@media (max-width: 767px) {
  .work-banner__wrapper img {
    min-height: 300px;
    object-fit: cover;
  }
}
</style>
