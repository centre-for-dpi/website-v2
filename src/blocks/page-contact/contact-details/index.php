<section class="redlof-block contact-details">
    <div class="container">
        <!-- Contact Us Directly -->
        <div class="row contact-details__row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2 class="contact-details__title">Contact Us Directly</h2>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <p class="contact-details__desc">
                    <strong>We'd love to hear from you.</strong> Whether you have a question, an idea, or an opportunity
                    to collaborate, our team is here to listen and respond. Reach out to start a conversation about
                    building inclusive, impactful digital solutions together.
                </p>
                <div class="contact-details__links">
                    <a href="mailto:info@cdpi.dev">info@cdpi.dev</a>
                    <span class="contact-details__separator">|</span>
                    <a href="tel:+919980044477">+91 99800 44477</a>
                </div>
            </div>
        </div>

        <!-- Start the Conversation -->
        <div class="row contact-details__row">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <h2 class="contact-details__title">Start the Conversation</h2>
            </div>
            <div class="col-lg-6 offset-lg-1">
                <h3 class="contact-details__form-title">Help us with your details</h3>
                <p class="contact-details__form-desc">Our team would analyze your application & get in touch with you.</p>

                <form class="contact-details__form" action="#" method="post">
                    <div class="contact-details__field">
                        <input type="text" name="name" class="contact-details__input" placeholder="Your name*" required>
                    </div>
                    <div class="contact-details__field">
                        <input type="email" name="email" class="contact-details__input"
                            placeholder="Your business email*" required>
                    </div>
                    <div class="contact-details__field">
                        <input type="text" name="subject" class="contact-details__input" placeholder="Subject*" required>
                    </div>
                    <div class="contact-details__field">
                        <textarea name="message" class="contact-details__textarea" placeholder="Your message (optional)"
                            rows="4"></textarea>
                    </div>
                    <button type="submit" class="contact-details__submit text-uppercase">Submit your query</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    /*
     * Figma node IDs (pixel-perfect source for every value):
     * Desktop section: 5841:26924
     * Mobile section:  5841:28655  (variable: Margins = 24px)
     * Submit button:  5841:27056  (h 56px, padding, 16px font, 1.28px tracking, 8px radius)
     * Get In touch Form inputs: 4274:8032 (bg, border, radius 8px, 14px text, padding, placeholder #8691a1)
     */

    .contact-details {
        background-color: #ffffff;
        padding: 4rem 0; /* 64px – from 5841:26924 */
    }

    .contact-details .container {
        padding: 4rem 9.5rem; /* 64px 152px – from 5841:26924 */
    }

    .contact-details__row {
        margin-bottom: 5rem; /* 80px – from 5841:26924 */
    }

    .contact-details__row:last-of-type {
        margin-bottom: 0;
    }

    /* 5841:26924 – Secondary H2: 20px, 500, 170%, letter-spacing 8px */
    .contact-details__title {
        font-family: 'Outfit', sans-serif;
        font-size: 1.25rem; /* 20px */
        font-weight: 500;
        line-height: 170%;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #0f0f0f;
        margin: 0;
        text-align: right;
    }

    .contact-details__desc {
        font-family: 'Outfit', sans-serif;
        font-size: 1.0625rem; /* 17px – 5841:26924 body */
        font-weight: 400;
        line-height: 1.8;
        color: #5e6979;
        margin: 0 0 1.25rem; /* 20px */
    }

    .contact-details__desc strong {
        color: #0f0f0f;
        font-weight: 600;
    }

    .contact-details__links {
        display: flex;
        align-items: center;
        gap: 0.5rem; /* 8px */
    }

    .contact-details__links a {
        font-family: 'Outfit', sans-serif;
        font-size: 1.0625rem; /* 17px – 5841:26924 */
        color: #4948E1;
        text-decoration: underline;
        text-underline-offset: 0.25rem; /* 4px */
        transition: color 0.2s ease;
    }

    .contact-details__links a:hover {
        color: #3730a3;
    }

    .contact-details__separator {
        color: #9ca3af;
        font-size: 1.0625rem; /* 17px */
    }

    /*
     * Form title "Help us with your details" – from Figma only.
     * Desktop: text layer in frame 5841:26924. Mobile: text layer in frame 5841:28655.
     * Copy from Figma Inspect: font, size, weight, line-height, letter-spacing, color.
     * Vertical spacing: copy padding/margin from layout (space above/below this heading).
     * No text-transform unless Figma shows uppercase for this text.
     */
    .contact-details__form-title {
        font-family: 'Outfit', sans-serif;
        /* From Figma – replace with exact values from "Help us with your details" text layer */
        font-size: var(--contact-form-title-size, 1.0625rem);   /* 17px – set from Figma desktop */
        font-weight: var(--contact-form-title-weight, 500);
        line-height: var(--contact-form-title-line-height, 1.7);
        letter-spacing: var(--contact-form-title-tracking, 0.02em);
        color: var(--contact-form-title-color, #0f0f0f);
        margin: var(--contact-form-title-margin, 0 0 0.5rem); /* vertical spacing from Figma */
    }

    /* 5841:26924 – form description: 17px, 400, 170%, 0.02em; bottom gap 32px desktop */
    .contact-details__form-desc {
        font-family: 'Outfit', sans-serif;
        font-size: 1.0625rem; /* 17px */
        font-weight: 400;
        line-height: 170%;
        letter-spacing: 0.02em;
        color: #5e6979;
        margin: 0 0 2rem; /* 32px desktop */
    }

    .contact-details__form {
        max-width: 31.25rem; /* 500px – from frame 5841:26924 */
    }

    /* 5841:26924 desktop: 16px between fields, 24px before button. 5841:28655 mobile: 24px (variable Margins) */
    .contact-details__field {
        margin-bottom: 1rem; /* 16px – desktop 5841:26924 */
    }

    .contact-details__field:last-of-type {
        margin-bottom: 1.5rem; /* 24px – desktop 5841:26924 */
    }

    /* 4274:8032 Get In touch Form: bg #fdfcff, border #bfbee1, radius 8px, 14px text, padding 16/8, placeholder #8691a1 */
    .contact-details__input,
    .contact-details__textarea {
        font-family: 'Outfit', sans-serif;
        width: 100%;
        font-size: 0.875rem; /* 14px */
        color: #0f0f0f;
        background-color: #fdfcff;
        border: 1px solid #bfbee1;
        border-radius: 0.5rem; /* 8px */
        padding: 1rem 0.5rem 1rem 1rem; /* 16px top/bottom & left, 8px right */
        outline: none;
        transition: border-color 0.2s ease;
    }

    .contact-details__input:focus,
    .contact-details__textarea:focus {
        border-color: #4948E1;
    }

    .contact-details__input::placeholder,
    .contact-details__textarea::placeholder {
        color: #8691a1; /* 4274:8032 */
    }

    .contact-details__textarea {
        resize: vertical;
        min-height: 6.75rem; /* 108px – 4274:8032 */
    }

    /* 5841:27056 – height 56px, padding 10px v / 16px h, 8px radius, 16px Medium, 1.28px tracking */
    .contact-details__submit {
        font-family: 'Outfit', sans-serif;
        width: 100%;
        min-height: 3.5rem; /* 56px */
        font-size: 1rem; /* 16px */
        font-weight: 500;
        line-height: 1.7;
        letter-spacing: 0.08rem; /* 1.28px */
        text-transform: uppercase;
        color: #ffffff;
        background-color: #4948E1;
        border: none;
        padding: 0.625rem 1rem; /* 10px 16px */
        border-radius: 0.5rem; /* 8px */
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .contact-details__submit:hover {
        background-color: #3730a3;
    }

    @media (max-width: 1400px) {
        .contact-details .container {
            padding: 4rem 3.125rem; /* 64px 50px */
        }
    }

    @media (max-width: 991px) {
        .contact-details__title {
            text-align: left;
        }

        .contact-details__form {
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        .contact-details .container {
            padding: 4rem 1.5rem; /* 64px 24px */
        }
    }

    /* 5841:28655 – mobile: Margins variable 24px, form title 17px, 24px spacing */
    @media (max-width: 575px) {
        .contact-details {
            padding: 3rem 0; /* 48px – 5841:28655 */
        }

        .contact-details .container {
            padding-left: 1.5rem;   /* 24px – 5841:28655 Margins */
            padding-right: 1.5rem;
        }

        .contact-details__row {
            margin-bottom: 3rem; /* 48px – 5841:28655 */
        }

        .contact-details__form-title {
            font-size: var(--contact-form-title-size-mobile, 1rem); /* From Figma 5841:28655 */
            margin: var(--contact-form-title-margin-mobile);       /* From Figma layout */
        }

        .contact-details__form-desc {
            margin-bottom: 1.5rem; /* 24px – 5841:28655 Margins */
        }

        .contact-details__field,
        .contact-details__field:last-of-type {
            margin-bottom: 1.5rem; /* 24px – 5841:28655 Margins */
        }
    }
</style>
