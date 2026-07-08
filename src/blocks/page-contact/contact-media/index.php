<section class="contact-media border-bottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2 class="contact-media__title">PR and Media Inquiries</h2>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <p class="contact-media__content">
                    For press and media inquiries, please contact us at:<br>
                    <a class="contact-media__links" href="mailto:media@cdpi.dev">media@cdpi.dev</a>
                </p>
            </div>
        </div>
    </div>
</section>

<style>
    /* Spacing: match contact-details – rem, container 1.5rem L/R on mobile (575px) */
    .contact-media .container {
        padding: 4rem 9.5rem; /* 64px 152px – desktop, same as contact-details */
    }

    .contact-media__title {
        font-family: 'Outfit', sans-serif;
        font-weight: 500;
        font-size: 1.25rem; /* 20px – Secondary H2, match contact-details */
        line-height: 170%;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #0f0f0f;
        margin: 0;
        text-align: right;
    }

    .contact-media__content {
        font-family: 'Outfit', sans-serif;
        color: #5e6979;
        font-size: 1.0625rem; /* 17px – match body text */
        font-weight: 400;
        line-height: 170%;
        letter-spacing: 0.02em;
        margin: 0;
    }

    .contact-media__links {
        font-family: 'Outfit', sans-serif;
        color: #4948e1;
        text-decoration: underline;
        text-underline-offset: 0.25rem; /* 4px */
        font-size: 1.0625rem; /* 17px */
        font-weight: 400;
        line-height: 170%;
        letter-spacing: 0.02em;
    }

    @media (max-width: 1400px) {
        .contact-media .container {
            padding: 4rem 3.125rem; /* 64px 50px */
        }
    }

    @media (max-width: 991px) {
        .contact-media__title {
            text-align: left;
        }
    }

    @media (max-width: 768px) {
        .contact-media .container {
            padding: 4rem 1.5rem; /* 64px 24px – 1.5rem L/R mobile */
        }
    }

    @media (max-width: 575px) {
        .contact-media .container {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }
    }
</style>