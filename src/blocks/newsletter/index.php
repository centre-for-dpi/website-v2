<section class="redlof-block newsletter">
  <div class="container">
      <div class="newsletter__card">
      <div class="row g-0 align-items-start">
        <!-- Left Column -->
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="newsletter__left">
            <span class="newsletter__label">NEWSLETTER</span>
            <h2 class="newsletter__title">Stay connected with us</h2>
          </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
          <div class="newsletter__right">
            <p class="newsletter__desc">
              Sign up for our newsletter to stay up to date on news from CDPI and our portfolio.
            </p>

            <form class="newsletter__form" action="#" method="post">
              <div class="newsletter__input-group">
                <input type="email" name="email" placeholder="Enter your email here" required />
                <button type="submit" class="newsletter__submit" aria-label="Subscribe">
                  <svg class="newsletter__submit-icon" width="10.5" height="10.5" viewBox="0 0 10.5 10.5"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 5.25H9.5L6.25 1M9.5 5.25L6.25 9.5" stroke="#ffffff" stroke-width="1.5"
                      stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                </button>
              </div>
            </form>

            <p class="newsletter__disclaimer">
              You may unsubscribe from these communications at any time. For information on how to unsubscribe, as well
              as our privacy practices and commitment to protecting your privacy, check out our <a
                href="/privacy-policy">Privacy Policy</a>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  .newsletter {
    background-color: #ffffff;
    padding: 7.25rem 0;
  }

  .newsletter__card {
    background-color: #eeecfe;
    border-radius: 0.75rem;
    padding: 7.25rem 6rem;
  }

  /* Left Column */
  .newsletter__left {
    padding-right: 2.5rem;
  }

  .newsletter__label {
    font-size: 0.75rem;
    font-weight: 400;
    letter-spacing: 0.075rem;
    text-transform: uppercase;
    background: linear-gradient(90deg, #9810fa 0%, #6564db 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    -webkit-text-fill-color: transparent;
    display: block;
    line-height: 1.7;
    margin-bottom: 1rem;
  }

  .newsletter__title {
    font-family: 'Lora', sans-serif;
    font-size: 3.25rem;
    font-weight: 400;
    color: #0F0F0F;
    line-height: 4.0625rem;
    letter-spacing: -0.065rem;
    margin-bottom: 0;
    max-width: 24.25rem;
  }

  /* Right Column */
  .newsletter__desc {
    font-weight: 300;
    font-size: 1.5rem;
    line-height: 2.4rem;
    letter-spacing: -0.03rem;
    color: #0F0F0F;
    margin-bottom: 1.5rem;
    max-width: 33.875rem;
  }

  .newsletter__form {
    margin-bottom: 1rem;
  }

  .newsletter__input-group {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #0f0f0f;
    gap: 0.25rem;
    padding-bottom: 1rem;
  }

  .newsletter__input-group input {
    flex: 1;
    background: transparent;
    border: none;
    padding: 0;
    font-size: 1.125rem;
    font-weight: 300;
    line-height: 1.8rem;
    letter-spacing: -0.0225rem;
    color: #0f0f0f;
    outline: none;
  }

  .newsletter__input-group input::placeholder {
    color: #8691a1;
  }

  .newsletter__submit {
    width: 3.125rem;
    height: 2.625rem;
    background-color: #0f0f0f;
    border: 1px solid #0f0f0f;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s ease;
    flex-shrink: 0;
  }

  .newsletter__submit:hover {
    background-color: #1f1f1f;
  }

  .newsletter__submit-icon {
    display: block;
    width: 0.65625rem;
    height: 0.65625rem;
  }

  .newsletter__disclaimer {
    font-size: 0.8125rem;
    font-weight: 300;
    line-height: 1.1375rem;
    letter-spacing: -0.01625rem;
    color: #5e6979;
    margin: 0;
  }

  .newsletter__disclaimer a {
    color: #1a1a2e;
    text-decoration: underline;
    text-underline-offset: 2px;
  }

  .newsletter__disclaimer a:hover {
    color: #4f46e5;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .newsletter__card {
      padding: 4rem 2rem;
    }

    .newsletter__left {
      padding-right: 0;
    }

    .newsletter__title {
      margin-bottom: 1rem;
      max-width: 100%;
    }

    .newsletter__desc {
      max-width: 100%;
    }
  }

  @media (max-width: 575px) {
    .newsletter {
      padding: 0 0 4.5rem;
    }

    .newsletter__card {
      padding: 3.8125rem 1.5rem;
      border-radius: 0.75rem;
    }

    .newsletter__title {
      font-size: 2rem;
      line-height: 2.5rem;
      letter-spacing: -0.04rem;
    }

    .newsletter__desc {
      font-size: 1.5rem;
      line-height: 1.875rem;
      letter-spacing: -0.03rem;
      margin-bottom: 1.5rem;
    }

    .newsletter__input-group {
      flex-wrap: nowrap;
    }

    .newsletter__input-group input {
      min-width: 0;
      font-size: 1.125rem;
      line-height: 1.8rem;
    }

    .newsletter__submit {
      width: 3.125rem;
      height: 2.625rem;
    }

    .newsletter__disclaimer {
      font-size: 0.7rem;
      line-height: 1.7;
    }
  }
</style>