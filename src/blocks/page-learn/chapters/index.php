<?php
// Card copy and images: sync with Figma (5837:13710 / 5839:16023). Only 3 images exist: digital-identity.svg, open-network-education.svg, verifiable-credentials.svg. Add other chapter images to public/img/images/learn/ and set $chapter_images per card if needed.
?>
<section class="redlof-block chapters">
  <div class="container">
    <h2 class="chapters__title text-uppercase text-center">Life-stage Chapters</h2>

    <!-- Cards Row -->
    <div class="row">
      <!-- Card 1 -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 0</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Digital Identity" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">DIGITAL IDENTITY</h3>
          <p class="chapter-card__desc">
            Mirra receives her digital ID, automatically registering her with government systems and allowing her to receive entitled benefits—updated through her life.
          </p>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 5</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/open-network-education.svg'); ?>" alt="Open Network for Education" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">OPEN NETWORK FOR EDUCATION AND SKILLING</h3>
          <p class="chapter-card__desc">
            Even in remote areas, Mirra can access high-quality educational and skilling resources by scanning QR codes in textbooks, which unlock curated offline content.
          </p>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-lg-4">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 16</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/verifiable-credentials.svg'); ?>" alt="Verifiable Credentials" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">VERIFIABLE CREDENTIALS</h3>
          <p class="chapter-card__desc">
            Digital storage of all certificates—school, driver's license, tax ID—via e-wallets and secure retrieval through e-lockers upon her consent.
          </p>
        </div>
      </div>
    </div>

    <!-- Cards Row 2 -->
    <div class="row mt-4">
      <!-- Card 4 -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 18</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Interoperable Payments" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">INTEROPERABLE PAYMENTS</h3>
          <p class="chapter-card__desc">
            Mirra can transact with the world in a secure manner from the tip of her fingers. She can use her biometrics, feature phone, or smart phone, to seamlessly transact P2P, P2M, bill payments recurring subscriptions through an interoperable authentication and payment system.
          </p>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 21</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Digital Health" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">DIGITAL HEALTH</h3>
          <p class="chapter-card__desc">
            Everytime Mirra gets a health checkup, her records are documented and with her consent, can be fetched by any other health care provider to ensure continuity of treatment and effective care. She can also avail of tele-medicine facilities in her local language.
          </p>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="col-lg-4">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 22</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Financial Inclusion" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">FINANCIAL INCLUSION</h3>
          <p class="chapter-card__desc">
            Mirra can easily be integrated in the formal financial sector - she can open a bank account while sitting at home through eKYC and video KYC features built on top of her digital ID.
          </p>
        </div>
      </div>
    </div>

    <!-- Cards Row 3 -->
    <div class="row mt-4">
      <!-- Card 7 -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 30</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Small Ticket Loans" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">SMALL TICKET LOANS</h3>
          <p class="chapter-card__desc">
            Leveraging Open Banking (both for data sharing and payments), the cost of credit for large banks has significantly reduced as have the chances of risk or default. Access to affordable formal credit helps Mirra launch her business and contribute to the economy.
          </p>
        </div>
      </div>

      <!-- Card 8 -->
      <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 32</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Open Network for Digital Commerce" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">OPEN NETWORK FOR DIGITAL COMMERCE</h3>
          <p class="chapter-card__desc">
            Using open discovery and fulfilment networks, Mirra can list her business (without a middleman) and directly interact as well as transact with people across the country in a secure, transparent manner.
          </p>
        </div>
      </div>

      <!-- Card 9 -->
      <div class="col-lg-4">
        <div class="chapter-card">
          <div class="chapter-card__badge">AGE 35</div>
          <div class="chapter-card__image">
            <img src="<?php echo Helper::getImagePath('images/learn/digital-identity.svg'); ?>" alt="Climate Resilience" loading="lazy" />
          </div>
          <h3 class="chapter-card__heading">CLIMATE RESILIENCE</h3>
          <p class="chapter-card__desc">
            Mirra can access the latest weather data using open climate platforms to make informed decisions regarding her agri-business and build climate resilience.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  /*
   * Learn page chapters – same Figma context as hero (5837:13710 desktop, 5839:16023 mobile).
   * Spacing: rem throughout; container 9.5rem desktop, 1.5rem L/R at 575px.
   */
  /*
   * Card spacing from Figma card node 5837:14620 (desktop) / 5839:16069 (mobile).
   * Set these when you have values from get_design_context or Inspect:
   *   --chapter-card-padding-x   horizontal padding (default 1.5rem)
   *   --chapter-card-padding-y   vertical padding (default 1.5rem)
   *   --chapter-card-gap         gap between image/heading/desc (default 1.5rem)
   */
  .chapters {
    position: relative;
    padding: 4rem 0;
  }

  .chapters__title {
    font-family: 'Outfit', sans-serif;
    font-size: 1.25rem; /* 20px – align with Secondary H2-style section titles */
    font-weight: 500;
    line-height: 170%;
    letter-spacing: 0.08em;
    color: #0f0f0f;
    margin: 0 0 3rem 0; /* mb-5 equivalent in rem */
  }

  .chapters .row {
    margin-bottom: 2rem;
  }

  .chapters .row:last-of-type {
    margin-bottom: 0;
  }

  /*
   * Card: Figma desktop 5837:14620, mobile 5839:16069 (source of truth).
   * get_variable_defs on these nodes returns {} – padding/margin are fixed in Figma.
   * Values below: use get_design_context(5837:14620) / (5839:16069) response, or set from Inspect.
   */
  .chapter-card {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
    border-radius: 0.625rem;
    padding: 0;
    height: 100%;
    max-width: 24rem;
    width: 100%;
    margin: 0 auto;
    position: relative;
    overflow: hidden;
    transition: box-shadow 0.2s ease;
  }

  .chapter-card:hover {
    box-shadow: 0 0.25rem 1.25rem rgba(0, 0, 0, 0.08);
  }

  /* Badge: 16px Figma. Padding from card 5837:14620 / 5839:16069. */
  .chapter-card__badge {
    display: block;
    font-family: 'Outfit', sans-serif;
    font-size: 1rem; /* 16px Figma */
    font-weight: 600;
    line-height: 1.7;
    letter-spacing: 0.05em;
    background: #4948E1;
    color: #ffffff;
    text-align: center;
    padding: var(--chapter-card-padding-y, 1rem) var(--chapter-card-padding-x, 1.5rem);
    border-radius: 0.625rem 0.625rem 0 0;
    margin: 0;
  }

  /* Image area: padding/gap from card 5837:14620 / 5839:16069. */
  .chapter-card__image {
    width: 100%;
    min-height: 12.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--chapter-card-padding-x, 1.5rem) var(--chapter-card-padding-x, 1.5rem) 2rem;
    margin-bottom: 2rem;
  }

  .chapter-card__image img {
    display: block;
    width: 100%;
    height: auto;
    max-height: 12.5rem;
    object-fit: contain;
    object-position: center;
  }

  /* Heading: 19px Figma. Padding/margin from card 5837:14620 / 5839:16069. */
  .chapter-card__heading {
    font-family: 'Outfit', sans-serif;
    font-size: 1.1875rem; /* 19px Figma */
    font-weight: 600;
    line-height: 1.7;
    letter-spacing: 0.02em;
    color: #171d1b;
    text-transform: uppercase;
    margin: 0 0 var(--chapter-card-gap, 1.5rem) 0;
    padding: 0 var(--chapter-card-padding-x, 1.5rem);
  }

  /* Description: padding from card 5837:14620 / 5839:16069. */
  .chapter-card__desc {
    font-family: 'Outfit', sans-serif;
    font-size: 0.9375rem;
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.02em;
    color: #5e6979;
    margin: 0;
    padding: 0 var(--chapter-card-padding-x, 1.5rem) var(--chapter-card-padding-y, 1.5rem);
  }

  @media (max-width: 1400px) {
    .chapters .container {
      padding-left: 3.125rem;
      padding-right: 3.125rem;
    }
  }

  @media (max-width: 991px) {
    .chapter-card {
      max-width: 24rem;
    }
  }

  @media (max-width: 768px) {
    .chapters .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
  }

  /* 5839:16023 – mobile cards */
  @media (max-width: 575px) {
    .chapters .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }
    .chapters__title {
      font-size: 1rem;
    }
    .chapter-card__image {
      min-height: 9.375rem;
    }
    .chapter-card__image img {
      max-height: 9.375rem;
    }
  }
</style>
