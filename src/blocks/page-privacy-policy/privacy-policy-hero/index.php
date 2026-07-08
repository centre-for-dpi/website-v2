<?php
/**
 * Privacy Policy Hero Block
 */
?>

<section class="redlof-block privacy-hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <span class="privacy-hero__label text-uppercase">EFFECTIVE JUNE 2025</span>
                <h1 class="privacy-hero__title">Privacy Policy</h1>
            </div>
        </div>
    </div>

    <!-- Decorative Pattern -->
    <div class="privacy-hero__pattern">
        <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-10.svg'); ?>" alt="" loading="lazy">
    </div>
</section>

<style>
.privacy-hero {
    background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
    padding: 80px 0;
    position: relative;
    overflow: hidden;
    min-height: 280px;
    display: flex;
    align-items: center;
}

.privacy-hero .container {
    position: relative;
    z-index: 2;
}

.privacy-hero__label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 1.5px;
    color: #6366f1;
    margin-bottom: 16px;
}

.privacy-hero__title {
    font-size: 48px;
    font-weight: 600;
    color: #1a1a2e;
    margin: 0;
    letter-spacing: -0.5px;
}

.privacy-hero__pattern {
    position: absolute;
    right: 0;
    bottom: 0;
    width: 45%;
    pointer-events: none;
    z-index: 1;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.privacy-hero__pattern img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    object-position: right center;
}

/* Responsive */
@media (max-width: 991px) {
    .privacy-hero {
        padding: 60px 0;
        min-height: 220px;
    }

    .privacy-hero__title {
        font-size: 40px;
    }

    .privacy-hero__pattern {
        width: 35%;
        opacity: 0.6;
    }
}

@media (max-width: 767px) {
    .privacy-hero {
        padding: 96px 0 48px;
        min-height: auto;
    }

    .privacy-hero__title {
        font-size: 32px;
    }

    .privacy-hero__pattern {
        display: none;
    }
}
</style>
