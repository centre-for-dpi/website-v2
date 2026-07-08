<footer class="site-footer redlof-block">
  <div class="container">
    <div class="footer__main">
      <!-- Main Row: Brand / Company / Newsletter -->
      <div class="row g-0 align-items-start footer__row-main">
        <!-- Left Column - Logo, Address, Contact, Social -->
        <div class="col-12 col-md-4 mb-5 mb-md-0">
          <div class="footer__brand">
            <div class="footer__logo-row">
              <a href="/">
                <img src="<?php echo Helper::getImagePath('logos/cdpi-logo-white.png'); ?>" alt="CDPI"
                  class="footer__logo" />
              </a>
            </div>
            <p class="footer__copyright">© CDPI 2025</p>
          </div>

          <div class="footer__partner">
            <img src="<?php echo Helper::getImagePath('logos/iiit_bangalore.png'); ?>" alt="IIIT-B" />
          </div>


          <div class="footer__contact">
            <a href="mailto:info@cdpi.dev">info@cdpi.dev</a>
            <span class="footer__contact-separator">|</span>
            <a href="tel:+919980044477">+91 99800 44477</a>
          </div>

          <hr class="footer__divider" />

          <p class="footer__social-text">
            Follow us on social media to stay on top of the latest news and insights from CDPI.
          </p>

          <div class="footer__social">
            <a href="https://www.linkedin.com/company/centre-for-dpi/" target="_blank" rel="noreferrer"
              aria-label="LinkedIn">
              <i class="fa-brands fa-linkedin-in"></i>
            </a>
            <a href="https://twitter.com/CentreforDPI" target="_blank" rel="noreferrer" aria-label="X">
              <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a href="https://www.youtube.com/@CentreforDPI" target="_blank" rel="noreferrer" aria-label="YouTube">
              <i class="fa-brands fa-youtube"></i>
            </a>
          </div>
        </div>

        <!-- Middle Column - Company Links -->
        <div class="col-12 col-md-1 footer__company">
          <h4 class="footer__nav-title text-uppecase">CDPI</h4>
          <ul class="footer__nav-list">
            <li><a href="<?php echo Helper::getPageUrl('about'); ?>">About</a></li>
            <li><a href="<?php echo Helper::getPageUrl('our-work'); ?>">Our Work</a></li>
            <li><a href="<?php echo Helper::getPageUrl('daas'); ?>">DaaS</a></li>
            <li><a href="<?php echo Helper::getPageUrl('resources'); ?>">Resources</a></li>
          </ul>
        </div>

        <!-- Right Column - Newsletter Signup -->
        <div class="col-12 col-md-5 footer__newsletter">
          <h3 class="footer__newsletter-title">
            Sign up for our newsletter to stay up to date on news from CDPI and our portfolio.
          </h3>

          <div class="footer__newsletter-form">
            <?php echo do_shortcode('[mc4wp_form id=1574]'); ?>
          </div>
        </div>
      </div>
    </div>
    <hr class="footer__divider__legal">
    <!-- Bottom Bar -->
    <div class="footer__bottom">
      <ul class="footer__legal">
        <!-- <li><a href="<?php echo Helper::getPageUrl('terms-of-use'); ?>">Terms Of Use</a></li> -->
        <li><a href="<?php echo Helper::getPageUrl('privacy-policy'); ?>">Privacy Policy</a></li>
        <!-- <li><a href="#">Cookie Preferences</a></li>
        <li><a href="<?php echo home_url('/sitemap.xml'); ?>">Sitemap</a></li> -->
      </ul>
    </div>
  </div>
</footer>

<style>
  .site-footer {
    background-color: #000000;
    padding: 5.5rem 5.625rem 3rem;
  }



  /* Brand Section */
  .footer__brand {
    margin-bottom: 2rem;
  }

  .footer__logo-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.5rem;
  }

  .footer__logo {
    height: 3.18rem;
    width: auto;
  }

  .footer__brand-text {
    display: flex;
    flex-direction: column;
    font-size: 0.95rem;
    color: #ffffff;
    line-height: 1.4;
  }

  .footer__copyright {
    font-size: 0.75rem;
    font-weight: 300;
    line-height: 1.275rem;
    letter-spacing: 0.015rem;
    color: rgba(255, 255, 255, 0.54);
    margin: 0;
  }

  /* Partner Logo */
  .footer__partner {
    margin-bottom: 2rem;
  }

  .footer__partner img {
    height: 3.375rem;
    width: 4rem;
    opacity: 1;
  }

  /* Address */
  .footer__address {
    color: rgba(255, 255, 255, 0.87);
    margin-bottom: 1.5rem;
    max-width: 17rem;
    font-weight: 300;
    font-size: 0.9375rem;
    line-height: 1.59375rem;
    letter-spacing: 0.01875rem;
  }

  /* Contact */

  .footer__contact a {
    color: rgba(255, 255, 255, 0.87);
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color 0.2s ease;
    font-weight: 300;
    font-size: 0.9375rem;
    line-height: 1.59375rem;
  }

  .footer__contact a:hover {
    color: #ffffff;
  }

  .footer__contact {
    display: flex;
    align-items: center;
    letter-spacing: 0.05625rem;
  }

  .footer__contact-separator {
    color: rgba(255, 255, 255, 0.87);
    margin: 0 0.375rem;
  }

  /* Divider */
  .footer__divider {
    border: none;
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    margin: 2rem 0;
    max-width: 17rem;
  }

  /* Social Section */
  .footer__social-text {
    color: rgba(255, 255, 255, 0.54);
    margin-bottom: 1rem;
    max-width: 17rem;
    font-weight: 400;
    font-size: 0.875rem;
    line-height: 1.4875rem;
    letter-spacing: 0.0175rem;
  }

  .footer__social {
    display: flex;
    gap: 1rem;
  }

  .footer__social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 1rem;
    height: 1rem;
    color: #ffffff;
    font-size: 1rem;
    transition: color 0.2s ease;
  }

  .footer__social a:hover {
    color: #4948E1;
  }

  /* Navigation & Main Row Layout */
  .footer__row-main {
    row-gap: 3.5rem;
  }

  .footer__nav-title {
    color: rgba(255, 255, 255, 0.54);
    margin-bottom: 1.375rem;
    font-weight: 400;
    font-size: 0.75rem;
    line-height: 1.275rem;
    letter-spacing: 0.075rem;
    text-transform: uppercase;
  }

  .footer__nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer__nav-list li {
    margin-bottom: 0.875rem;
    min-width: 6.3125rem;
  }

  .footer__nav-list a {
    color: rgba(255, 255, 255, 0.87);
    text-decoration: none;
    transition: color 0.2s ease;
    font-weight: 300;
    font-size: 0.875rem;
    line-height: 1.4875rem;
    letter-spacing: 0.0175rem;
  }

  .footer__nav-list a:hover {
    color: #ffffff;
  }

  .footer__nav-list a i {
    font-size: 0.7rem;
    margin-left: 6px;
  }

  .footer__nav-list li:last-child a {
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .arrow-up-right-icon-footer {
    width: 1.125rem;
    height: 1.125rem;
  }

  /* Newsletter */
  .footer__newsletter {
    color: #ffffff;
    max-width: 33.875rem; /* 542px */
  }

  .footer__newsletter-title {
    font-weight: 400;
    font-size: 1.25rem;
    line-height: 1.6;
    letter-spacing: 0;
    margin: 0 0 1.5rem;
  }

  .footer__newsletter-form {
    max-width: 33.875rem; /* 542px */
  }

  .footer__newsletter-input-row {
    display: flex;
    align-items: center;
    border-bottom: 1px solid rgba(255, 255, 255, 0.6);
    padding-bottom: 0.75rem;
    gap: 0.5rem;
  }

  .footer__newsletter-input {
    flex: 1 1 auto;
    background: transparent;
    border: none;
    outline: none;
    color: #ffffff;
    font-family: "Outfit", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 300;
    font-size: 0.9375rem;
    line-height: 1.6;
  }

  .footer__newsletter-input::placeholder {
    color: rgba(255, 255, 255, 0.7);
  }

  .footer__newsletter-submit {
    flex: 0 0 auto;
    width: 3rem;
    height: 3rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0;
    border: 1px solid #ffffff;
    background-color: #ffffff;
    color: #000000;
    cursor: pointer;
    padding: 0;
  }

  .footer__newsletter-submit i {
    font-size: 0.9rem;
  }

  .footer__newsletter-note {
    margin: 0.75rem 0 0;
    max-width: 33.875rem;
    font-weight: 300;
    font-size: 0.75rem;
    line-height: 1.5;
    color: rgba(255, 255, 255, 0.7);
  }

  .footer__newsletter-note a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: underline;
    text-underline-offset: 2px;
  }

  .footer__newsletter-note a:hover {
    color: #ffffff;
  }

  @media (max-width: 767px) {
    .footer__newsletter,
    .footer__newsletter-form,
    .footer__newsletter-note {
      max-width: 100%;
    }
  }

  /* Bottom Bar */
  .footer__divider__legal {
    border: none;
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    margin: 2rem 0;
    max-width: 78.75rem;
  }

  .footer__legal {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 0.4375rem;
  }

  .footer__legal li {
    display: flex;
    align-items: center;
  }

  .footer__legal li:not(:last-child)::after {
    content: '•';
    color: rgba(209, 213, 219, 0.24);
    margin-left: 0.4375rem;
    font-size: 0.375rem;
    line-height: 1;
  }

  .footer__legal a {
    color: rgba(255, 255, 255, 0.54);
    text-decoration: none;
    transition: color 0.2s ease;
    font-weight: 400;
    font-size: 0.6875rem;
    line-height: 1.16875rem;
    letter-spacing: 0.01375rem;
  }

  .footer__main > .row {
    row-gap: 3.5rem;
  }

  @media (min-width: 1200px) {
    .footer__main > .row {
      column-gap: 4rem;
      align-items: flex-start;
    }
  }

  .footer__legal a:hover {
    color: #ffffff;
  }

  /* Responsive */
  @media (max-width: 1024px) {
    .site-footer {
      display: block !important;
      visibility: visible !important;
      opacity: 1 !important;
    }

    .footer__row-main > [class*="col-"] {
      flex: 0 0 100%;
      max-width: 100%;
    }

    .footer__newsletter {
      margin-top: 0.5rem;
    }
  }

  @media (max-width: 991px) {
    .site-footer {
      display: block;
    }

    .footer__main > .row {
      column-gap: 2rem;
    }

    .footer__nav-title {
      margin-bottom: 16px;
    }

    .footer__divider {
      max-width: 100%;
    }

    .footer__social-text {
      max-width: 100%;
    }
  }

  @media (max-width: 768px) {
    .site-footer {
      padding: 3.5rem 1.5rem 3rem;
    }

    .footer__legal {
      flex-direction: column;
      gap: 0.75rem;
    }

    .footer__legal li::after {
      display: none;
    }
  }

  .floating-footer-cta {
    position: fixed;
    right: 16px;
    left: auto;
    bottom: 16px;
    z-index: 9999;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 14px 12px 16px;
    border: none;
    border-radius: 999px;
    background: #4948E1;
    color: #ffffff;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18);
    cursor: pointer;
    user-select: none;
  }

  .floating-footer-cta:focus-visible {
    outline: 2px solid rgba(255, 255, 255, 0.85);
    outline-offset: 2px;
  }

  .floating-footer-cta__icon {
    width: 32px;
    height: 32px;
    border-radius: 999px;
    background: rgba(255, 255, 255, 0.16);
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  .floating-footer-cta__icon i {
    font-size: 16px;
    line-height: 1;
  }

  @media (max-width: 575px) {
    .floating-footer-cta {
      right: 12px;
      left: auto;
      bottom: 12px;
      padding: 10px 12px 10px 14px;
      font-size: 12px;
    }

    .floating-footer-cta__icon {
      width: 28px;
      height: 28px;
    }
  }
</style>

<button type="button" class="floating-footer-cta" aria-label="Reach out to us - scroll to footer">
  <span class="floating-footer-cta__text">Reach out to us</span>
  <span class="floating-footer-cta__icon" aria-hidden="true">
    <i class="fa-regular fa-envelope"></i>
  </span>
</button> 

<?php include_once Helper::getBlock("html-header-footer/footer.php"); ?>
