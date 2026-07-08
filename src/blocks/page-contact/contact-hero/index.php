<section class="contact-details__hero text-uppercase text-center d-flex flex-column justify-content-center align-items-center position-relative">

    <!-- Header Content -->
    <p>We&rsquo;re Listening</p>
    <h1>Connect for <span>Impact</span></h1>

    <!-- Cube -->
    <div class="contact-details__cube">
        <img src="<?php echo Helper::getImagePath('patterns/hero-pattern-contact.svg'); ?>" alt="Rotating cube" loading="lazy"/>
    </div>
</section>

<style>
    .contact-details__hero {
        background: linear-gradient(107.56deg, #F0E3FD 0%, #FAF8FF 50.23%, #E3F0FF 100%);
        /* Figma desktop: 184px top, 82px bottom */
        padding: 12.5rem 0 5.125rem;
        position: relative;
    }

    .contact-details__hero::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 1px;
        background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
    }

    /* Figma: Secondary H4 – 12px, 500, line-height 1.7, letter-spacing 1.2px */
    .contact-details__hero>p {
        font-family: 'Outfit', sans-serif;
        font-size: 0.75rem;
        font-weight: 500;
        line-height: 1.7;
        letter-spacing: 0.075rem;
        background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        color: transparent;
        margin: 0 0 1.5rem;
    }

    /* Figma: H1 Secondary – 48px, 500, line-height 72px, letter-spacing 8px */
    .contact-details__hero>h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 3rem;
        font-weight: 500;
        line-height: 1.5;
        letter-spacing: 0.3rem;
        color: #0f0f0f;
        margin: 0;
    }

    .contact-details__hero>h1>span {
        color: #4948E1;
    }

    .contact-details__cube {
        position: absolute;
        left: -185px;
        height: 500px;
        width: 500px;
        top: 118px;
    }

    @media (max-width: 768px) {
        .contact-details__cube {
            display: none;
        }
        /* Figma mobile: 190px top, 38px sides, 88px bottom */
        .contact-details__hero {
            padding: 11.875rem 2.375rem 5.5rem;
        }
    }
</style>
