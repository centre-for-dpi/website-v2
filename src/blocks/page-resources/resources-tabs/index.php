<section class="redlof-block read-tabs py-5">
  <div class="container">
    <!-- Top Divider -->
    <div class="read-tabs__divider-top"></div>
    
    <!-- Tab Navigation -->
    <ul class="nav read-tabs__nav justify-content-center my-4" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="read-tabs__tab" id="dpi-tab" data-bs-toggle="tab" data-bs-target="#dpi-content" type="button" role="tab" aria-controls="dpi-content" aria-selected="false">Thought leadership</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="read-tabs__tab active" id="technical-tab" data-bs-toggle="tab" data-bs-target="#technical-content" type="button" role="tab" aria-controls="technical-content" aria-selected="true">Case Studies</button>
      </li>
    </ul>
    
    <!-- Bottom Divider -->
    <div class="read-tabs__divider-bottom"></div>

    <!-- Tab Content -->
    <div class="tab-content">
      <!-- DPI Tab -->
      <div class="tab-pane fade" id="dpi-content" role="tabpanel" aria-labelledby="dpi-tab">
          <?php include Helper::getBlock('page-resources/resources-thought-leadership/index.php'); ?>
      </div>

      <!-- Technical Notes Tab -->
      <div class="tab-pane fade show active" id="technical-content" role="tabpanel" aria-labelledby="technical-tab">
        
        <!-- Discovery and Fulfilment Section -->
        <div class="read-tabs__section">
          <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
              <h3 class="read-tabs__category text-uppercase">DISCOVERY AND FULFILMENT</h3>
            </div>
            <div class="col-lg-9">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="Harvard Business Review" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">Digital Public Infrastructure & Digital Financial Services - CCAF</p>
                    </div>
                  </a>
                </div>
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="FT" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">Digital Energy Grid: A vision for a unified energy infrastructure - FIDE & IEA</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="read-tabs__divider"></div>
        </div>

        <!-- Payments Section -->
        <div class="read-tabs__section">
          <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
              <h3 class="read-tabs__category text-uppercase">PAYMENTS</h3>
            </div>
            <div class="col-lg-9">
              <div class="row">
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="UPI" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">How does UPI work?</p>
                    </div>
                  </a>
                </div>
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="UPI 102" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">UPI 102: The Transaction Cycle</p>
                    </div>
                  </a>
                </div>
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="Digital Transformation" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">Driving the Digital Transformation for a Billion People</p>
                    </div>
                  </a>
                </div>
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="G20" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">G20 Policy Recommendations</p>
                    </div>
                  </a>
                </div>
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="IMF" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">Lessons from India's Digital Journey</p>
                    </div>
                  </a>
                </div>
                <div class="col-md-6 mb-4">
                  <a href="#" class="resource-card">
                    <div class="resource-card__icon">
                      <img src="<?php echo Helper::getImagePath('temp/harvard-business-review.png'); ?>" alt="Digital Financial" loading="lazy" />
                    </div>
                    <div class="resource-card__content">
                      <p class="resource-card__title">The design of digital financial infrastructure</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Blueprints Tab -->
      <div class="tab-pane fade" id="blueprints-content" role="tabpanel" aria-labelledby="blueprints-tab">
        <p class="text-center text-muted">Blueprints content coming soon...</p>
      </div>
    </div>
  </div>
</section>

<style>
.read-tabs {
  background-color: #ffffff;
}

.read-tabs__divider-top{
  margin-bottom: 20px;
}

.read-tabs__divider-top,
.read-tabs__divider-bottom {
  height: 1px;
  background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
}

/* Tab Navigation */
.read-tabs__nav {
  gap: 16px;
  border: none;
  display: flex;
  justify-content: center;
}

.read-tabs__nav .nav-item {
  list-style: none;
}

.read-tabs__tab {
  font-size: 0.85rem;
  font-weight: 500;
  color: #1a1a2e;
  letter-spacing: 0.05em;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 14px 56px;
  cursor: pointer;
  transition: all 0.3s ease;
  border-radius: 8px;
}

.read-tabs__tab:hover {
  color: #4948E1;
  border-color: #e5e7eb;
}

.read-tabs__tab.active {
  background-color: #4948E1;
  color: #ffffff;
  border-color: #4948E1;
  border-radius: 8px;
}

/* Section */
.read-tabs__section {
  padding: 40px 0;
}

.read-tabs__category {
  font-size: 0.8rem;
  font-weight: 600;
  color: #1a1a2e;
  letter-spacing: 0.1em;
  margin: 0;
  line-height: 1.5;
}

.read-tabs__divider {
  height: 1px;
  background: linear-gradient(90deg, #D6E1F1 0%, #6564DB 50%, #D6E1F1 100%);
  margin-top: 20px;
}

/* Resource Card */
.resource-card {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 40px 32px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  text-decoration: none;
  transition: all 0.3s ease;
  height: 150px;
  max-width: 400px;
}

.resource-card:hover {
  border-color: #6564DB;
  box-shadow: 0 4px 12px rgba(101, 100, 219, 0.1);
}

.resource-card__icon {
  flex-shrink: 0;
  width: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.resource-card__icon img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.resource-card__content {
  flex: 1;
}

.resource-card__title {
  font-size: 0.9rem;
  font-weight: 400;
  color: #1a1a2e;
  line-height: 1.5;
  margin: 0;
}

/* Responsive */
@media (max-width: 991px) {
  .read-tabs__category {
    margin-bottom: 24px;
  }
}

@media (max-width: 767px) {
  .read-tabs__tab {
    padding: 12px 24px;
    font-size: 0.75rem;
  }
  
  .resource-card {
    padding: 24px 20px;
    height: auto;
    max-width: 100%;
  }
  
  .resource-card__icon {
    width: 40px;
    height: 40px;
  }
}
</style>
