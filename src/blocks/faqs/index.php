<section class="redlof-block faqs">
  <div class="container">
    <div class="row faqs__row">
      <!-- Left Column - Title & Description -->
      <div class="col-lg-4 mb-4 mb-lg-0 mt-lg-4">
        <h2 class="faqs__title text-uppercase">Frequently Asked Questions</h2>
        <p class="faqs__desc">
        Rapid fire answers to the most common questions about our work and approach.
        </p>
      </div>

      <!-- Right Column - FAQ Accordion -->
      <div class="col-lg-8">
        <div class="accordion faq-accordion" id="faqAccordion">
          <!-- FAQ Item 1 -->
          <div class="accordion-item faq-item">
            <h3 class="accordion-header" id="faqHeadingOne">
              <button class="accordion-button faq-item__question" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="true" aria-controls="faqCollapseOne">
                <span>How can governments collaborate with CDPI?</span>
                <span class="faq-item__icon"></span>
              </button>
            </h3>
            <div id="faqCollapseOne" class="accordion-collapse collapse show" aria-labelledby="faqHeadingOne" data-bs-parent="#faqAccordion">
              <div class="accordion-body faq-item__answer">
                <p>Governments can engage us for pro bono advisory, DPI phase one deployments, or to access reusable DPI resources.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 2 -->
          <div class="accordion-item faq-item">
            <h3 class="accordion-header" id="faqHeadingTwo">
              <button class="accordion-button collapsed faq-item__question" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
                <span>Do you offer any software solutions?</span>
                <span class="faq-item__icon"></span>
              </button>
            </h3>
            <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#faqAccordion">
              <div class="accordion-body faq-item__answer">
                <p>No. CDPI does not offer proprietary software products. We support countries in choosing, designing, and deploying open and interoperable digital systems that fit their context and long-term needs.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 3 -->
          <div class="accordion-item faq-item">
            <h3 class="accordion-header" id="faqHeadingThree">
              <button class="accordion-button collapsed faq-item__question" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
                <span>Do you only work with open source solutions?</span>
                <span class="faq-item__icon"></span>
              </button>
            </h3>
            <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#faqAccordion">
              <div class="accordion-body faq-item__answer">
                <p>No. CDPI does not have a preference for open source or proprietary solutions. We offer technically sound, impartial advice to countries for any technology solution of interest to them.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 4 -->
          <div class="accordion-item faq-item">
            <h3 class="accordion-header" id="faqHeadingFour">
              <button class="accordion-button collapsed faq-item__question" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
                <span>Do you provide technical blueprints or resources?</span>
                <span class="faq-item__icon"></span>
              </button>
            </h3>
            <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour" data-bs-parent="#faqAccordion">
              <div class="accordion-body faq-item__answer">
                <p>Yes. We have published over 150 implementation focused DPI articles, toolkits, and guides on our DPI Wiki.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 5 -->
          <div class="accordion-item faq-item">
            <h3 class="accordion-header" id="faqHeadingFive">
              <button class="accordion-button collapsed faq-item__question" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFive" aria-expanded="false" aria-controls="faqCollapseFive">
                <span>What makes CDPI’s approach unique?</span>
                <span class="faq-item__icon"></span>
              </button>
            </h3>
            <div id="faqCollapseFive" class="accordion-collapse collapse" aria-labelledby="faqHeadingFive" data-bs-parent="#faqAccordion">
              <div class="accordion-body faq-item__answer">
                <p>We are vendor neutral, action oriented, and focused on delivering population scale results with countries.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.faqs {
  background-color: #ffffff;
  padding: 116px 0;
}

.faqs__row {
  --bs-gutter-x: 4.625rem;
  align-items: flex-start;
}

.faqs__title {
  font-family: "Outfit", sans-serif;
  font-size: 1.25rem;
  font-weight: 600;
  letter-spacing: 0.1rem;
  color: #0f0f0f;
  line-height: 2.125rem;
  margin-bottom: 1.5rem;
  text-transform: uppercase;
  max-width: 354px;
}

.faqs__desc {
  font-family: "Outfit", sans-serif;
  font-weight: 500;
  font-style: normal;
  font-size: 14px;
  line-height: 170%;
  letter-spacing: 0.02em;
  color: #5E6979;
  margin: 0;
  max-width: 255px;
}

/* FAQ Accordion */
.faq-accordion {
  display: flex;
  flex-direction: column;
  gap: 0;
}

.faq-item.accordion-item {
  border: none;
  border-bottom: 1px solid #e5e7eb;
  background: transparent;
}

.faq-item__question.accordion-button {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 24px 0;
  background: none;
  border: none;
  cursor: pointer;
  text-align: left;
  box-shadow: none;
}

.faq-item__question.accordion-button:not(.collapsed) {
  background: none;
  color: inherit;
  box-shadow: none;
}

.faq-item__question.accordion-button::after {
  display: none;
}

.faq-item__question span:first-child {
  font-family: "Outfit", sans-serif;
  font-size: 1rem;
  font-weight: 600;
  color: #101828;
  line-height: 1.7rem;
  letter-spacing: 0.02rem;
}

.faq-item__icon {
  position: relative;
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.faq-item__icon::before,
.faq-item__icon::after {
  content: '';
  position: absolute;
  background-color: #6b7280;
  transition: transform 0.3s ease;
}

.faq-item__icon::before {
  width: 14px;
  height: 2px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.faq-item__icon::after {
  width: 2px;
  height: 14px;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.faq-item__question.accordion-button:not(.collapsed) .faq-item__icon::after {
  transform: translate(-50%, -50%) rotate(90deg);
  opacity: 0;
}

.faq-item__answer {
  padding: 0 0 24px;
}

.faq-item__answer p {
  font-family: "Outfit", sans-serif;
  font-size: 0.875rem;
  font-weight: 400;
  line-height: 1.44375rem;
  letter-spacing: 0.0175rem;
  color: #5e6979;
  margin: 0;
}

/* Responsive */
@media (max-width: 991px) {
  .faqs {
    padding: 116px 0;
  }

  .faqs__row {
    --bs-gutter-x: 1.5rem;
  }

  .faqs__title {
    margin-bottom: 16px;
    max-width: 100%;
  }
  
  .faqs__desc {
    max-width: 100%;
  }
}

@media (max-width: 575px) {
  .faqs .container {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
  .faqs__title {
    font-weight: 600;
  }

  .faqs__desc {
    max-width: 21.375rem;
  }
}
</style>
