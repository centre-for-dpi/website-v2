<?php


// Get the data from Custom post type 'country' and resolve partnerships relationship field.
$countryHandler    = class_exists('CustomPost') ? new CustomPost('daas_countries', null) : null;
$global_momentum_countries = [];

if ($countryHandler) {
  $global_momentum_countries = $countryHandler->getListOfPosts(['meta_fields', "thumbnail"]);

  usort($global_momentum_countries, static function ($a, $b): int {
    $get_rank = static function ($country): int {
      $meta = $country['meta_fields'] ?? [];
      $status = strtolower(trim((string) ($meta['status'] ?? '')));
      $service_provider_status = strtolower(trim((string) ($meta['service_provider_status'] ?? '')));
      $rank = 5;

      if ($status === 'live') {
        $rank = 1;
      } elseif ($service_provider_status === 'selected') {
        $rank = 2;
      } elseif ($status === 'formal commitments') {
        $rank = 3;
      } elseif ($status === 'engaged') {
        $rank = 4;
      }

      return $rank;
    };

    $rank_a = $get_rank($a);
    $rank_b = $get_rank($b);

    if ($rank_a !== $rank_b) {
      return $rank_a <=> $rank_b;
    }

    return strcasecmp((string) ($a['title'] ?? ''), (string) ($b['title'] ?? ''));
  });
}


$global_momentum_stats = [
  [
    'value' => isset($global_momentum_countries) && is_array($global_momentum_countries) ? count($global_momentum_countries) : '7',
    'label' => 'Countries onboarded',
    'icon'  => Helper::getImagePath('patterns/icon-globe.svg'),
  ],
    [
      'value' => isset($global_momentum_countries) && is_array($global_momentum_countries) ? count(array_filter($global_momentum_countries, function($country) { return strtolower($country['meta_fields']['status'] ?? '') === 'live'; })) : '2',
    'label' => 'Countries live',
    'icon'  => Helper::getImagePath('patterns/icon-rocket.svg'),
  ],
  [
    'value' => '7.5',
    'label' => 'months average time to deploy',
    'icon'  => Helper::getImagePath('patterns/icon-checklist.svg'),
  ],
];


?>

<section class="global-momentum redlof-block">
  <div class="container">
    <div class="global-momentum__header text-center">
      <span class="global-momentum__eyebrow text-uppercase">DPI Adoption</span>
      <h2 class="global-momentum__title">Global Momentum</h2>
      <p class="global-momentum__subtitle">Countries building DPI the DaaS way</p>
    </div>
    <div class="global-momentum__cards-view global-momentum__view is-active">
      <div class="global-momentum__stats row g-4 justify-content-center">
      <?php
        $global_momentum_bg_images = [
          Helper::getImagePath('images/daas/daas-global-momentum1.png'),
          Helper::getImagePath('images/daas/daas-global-momentum2.png'),
          Helper::getImagePath('images/daas/daas-global-momentum3.png'),
        ];
      ?>
      <?php foreach ($global_momentum_stats as $index => $stat) : ?>
        <div class="col-12 col-md-4">
          <div class="global-momentum__stat-card">
            <div class="global-momentum__stat-bg">
              <img src="<?php echo esc_url($global_momentum_bg_images[$index] ?? $global_momentum_bg_images[0]); ?>" alt="" loading="lazy" />
            </div>
            <div class="global-momentum__stat-main">
              <div class="global-momentum__stat-value"><?php echo esc_html($stat['value']); ?></div>
              <p class="global-momentum__stat-label"><?php echo esc_html($stat['label']); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      </div>

      <div class="global-momentum__engagements-header d-flex justify-content-between align-items-center flex-wrap">
        <h3 class="global-momentum__engagements-title text-uppercase mb-3 mb-md-0">Our DaaS country engagements</h3>
        <a href="#global-momentum-table" class="btn btn-primary global-momentum__table-btn">See full table view</a>
      </div>

      <div class="global-momentum__slider-wrapper">
        <div class="global-momentum__slider" data-autoplay="true" data-autoplay-interval="3000">
        <?php foreach ($global_momentum_countries as $country) :
          $raw_dpi_blocks   = $country['meta_fields']['dpi_blocks'] ?? [];
          $dpi_blocks_arr   = is_array($raw_dpi_blocks) ? $raw_dpi_blocks : maybe_unserialize($raw_dpi_blocks);
          if (!is_array($dpi_blocks_arr)) { $dpi_blocks_arr = $raw_dpi_blocks !== '' ? [$raw_dpi_blocks] : []; }

          $raw_impacts      = $country['meta_fields']['impacts'] ?? [];
          $impacts_arr      = is_array($raw_impacts) ? $raw_impacts : maybe_unserialize($raw_impacts);
          if (!is_array($impacts_arr)) { $impacts_arr = $raw_impacts !== '' ? [$raw_impacts] : []; }

          $raw_partnerships = $country['meta_fields']['partnerships'] ?? [];
          $partner_ids      = is_array($raw_partnerships) ? $raw_partnerships : maybe_unserialize($raw_partnerships);
          
          $partnerships_arr = [];
          if (is_array($partner_ids)) {
            foreach ($partner_ids as $pid) {
              $pid = (int) $pid;
              if ($pid) {
                $partnerships_arr[] = [
                  'logo' => get_the_post_thumbnail_url($pid, 'full') ?: '',
                  'name' => get_the_title($pid),
                ];
              }
            }
          }

          $card_data = [
            'name'        => $country['title'],
            'block'       => $dpi_blocks_arr,
            'details'     => $country['excerpt'] ?? '',
            'flag'        => $country['thumbnail'],
            'status'      => $country['meta_fields']['status'] ?? '',
            'impacts'     => $impacts_arr,
            'partnerships'=> $partnerships_arr,
            'population'  => $country['meta_fields']['population_impacted'] ?? '',
            'legal_artefacts_status'  => $country['meta_fields']['legal_artefacts'] ?? '',
            'technical_scope_status'  => $country['meta_fields']['technical_scope'] ?? '',
            'service_provider_status' => $country['meta_fields']['service_provider_status'] ?? '',
            'service_provider_name'   => $country['meta_fields']['service_provider_name'] ?? '',
            'hosting_status'          => $country['meta_fields']['hosting_choice_status'] ?? '',
            'hosting_name'            => $country['meta_fields']['hosting_choice_name'] ?? '',
            'funding_status'          => $country['meta_fields']['funding_status'] ?? '',
            'funder_name'             => $country['meta_fields']['funder_name'] ?? '',
            'program_status'          => $country['meta_fields']['program_management_status'] ?? '',
            'program_name'            => $country['meta_fields']['program_management_name'] ?? '',
          ];
        ?>
          <button
            type="button"
            class="global-momentum__country-card"
            data-country='<?php echo esc_attr(wp_json_encode($card_data)); ?>'
          >
            <div class="global-momentum__country-flag">
              <img src="<?php echo esc_url($country["thumbnail"]); ?>" alt="<?php echo esc_attr($country['title']); ?>" loading="lazy" />
            </div>
            <div class="global-momentum__country-body">
              <h4 class="global-momentum__country-name"><?php echo esc_html($country['title']); ?></h4>
              <div class="global-momentum__country-meta">
                <?php if (!empty($dpi_blocks_arr)) : ?>
                  <span class="global-momentum__country-block-label">DPI Block</span>
                  <span class="global-momentum__country-block"><?php echo esc_html(implode(', ', $dpi_blocks_arr)); ?></span>
                <?php endif; ?>
              </div>
            </div>
          </button>
        <?php endforeach; ?>
        </div>
        <div class="global-momentum__nav d-flex gap-2">
        <button
          type="button"
          class="global-momentum__nav-btn global-momentum__nav-prev"
          aria-label="Previous country"
        >
          <i class="fa-solid fa-arrow-left"></i>
        </button>
        <button
          type="button"
          class="global-momentum__nav-btn global-momentum__nav-next"
          aria-label="Next country"
        >
          <i class="fa-solid fa-arrow-right"></i>
        </button>
        </div>
      </div>
    </div>

  <div
    id="global-momentum-table"
    class="global-momentum__table-view global-momentum__view"
  >
    <div class="global-momentum__table-dialog">
      <p class="global-momentum__subtitle">
        DPI deployments done the DaaS way can reuse all the offered artefacts (such as technical scopes, legal agreements and program management playbooks) or some of them – depending on the country’s choice. They also support a range of service providers, hosting choices, implementation partners and funders – ensuring the country remains in control of their national infrastructure with implementations accelerated through best in class technologies and global experts!
      </p>

      <div class="global-momentum__table-wrapper table-responsive">
        <table class="table global-momentum__table mb-0">
          <thead>
            <tr>
              <th scope="col">Country</th>
              <th scope="col">Use case</th>
              <th scope="col">DPI Block</th>
              <th scope="col">Legal Artefact</th>
              <th scope="col">Technical Scope</th>
              <th scope="col">Service Provider</th>
              <th scope="col">Hosting choice</th>
              <th scope="col">DaaS Funding</th>
              <th scope="col">Program Manag..</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($global_momentum_countries as $country) :
              $legal_artefacts_status  = $country['meta_fields']['legal_artefacts'] ?? '';
              $technical_scope_status  = $country['meta_fields']['technical_scope'] ?? '';
              $service_provider_status = $country['meta_fields']['service_provider_status'] ?? '';
              $service_provider_name   = $country['meta_fields']['service_provider_name'] ?? '';
              $hosting_status          = $country['meta_fields']['hosting_choice_status'] ?? '';
              $hosting_name            = $country['meta_fields']['hosting_choice_name'] ?? '';
              $funding_status          = $country['meta_fields']['funding_status'] ?? '';
              $funder_name             = $country['meta_fields']['funder_name'] ?? '';
              $program_status          = $country['meta_fields']['program_management_status'] ?? '';
              $program_name            = $country['meta_fields']['program_management_name'] ?? '';
            ?>
              <tr>
                <td>
                  <?php if (!empty($country['meta_fields']['status'])) :
                    $raw_status     = (string) $country['meta_fields']['status'];
                    $row_status     = ucfirst(strtolower($raw_status));
                    $row_status_key = strtolower(trim($raw_status));
                    $status_class   = '';

                    if ($row_status_key === 'formal commitments') {
                      $status_class = ' global-momentum__table-row-status--formal';
                    } elseif ($row_status_key === 'engaged') {
                      $status_class = ' global-momentum__table-row-status--engaged';
                    } elseif ($row_status_key === 'live') {
                      $status_class = ' global-momentum__table-row-status--live';
                    }else{
                      $status_class = ' global-momentum__table-row-status--formal';
                    }
                  ?>
                    <span class="global-momentum__table-row-status<?php echo $status_class; ?>">
                      <?php if ($row_status_key === 'live') : ?>
                        <span class="global-momentum__modal-status-dot"></span>
                      <?php endif; ?>
                      <?php echo esc_html($row_status); ?>
                    </span>
                  <?php endif; ?>
                  <div class="global-momentum__table-country">
                    <span class="global-momentum__table-flag">
                      <img
                        src="<?php echo esc_url($country["thumbnail"]); ?>"
                        alt="<?php echo esc_attr($country['title']); ?>"
                        loading="lazy"
                      />
                    </span>
                    <span class="global-momentum__table-country-name">
                      <?php echo esc_html($country['title']); ?>
                    </span>
                  </div>
                </td>
                <td>
                  <?php $country_excerpt = $country['excerpt'] ?? ''; ?>
                  <span class="global-momentum__table-text<?php echo strtolower(trim((string) $country_excerpt)) === 'in progress' ? ' global-momentum__table-text--in-progress' : ''; ?>">
                    <?php echo esc_html($country_excerpt); ?>
                  </span>
                </td>
                <td>
                  <?php
                  $row_dpi_raw = $country['meta_fields']['dpi_blocks'] ?? [];
                  $row_dpi     = is_array($row_dpi_raw) ? $row_dpi_raw : maybe_unserialize($row_dpi_raw);
                  if (!is_array($row_dpi)) { $row_dpi = $row_dpi_raw !== '' ? [$row_dpi_raw] : []; }

                  foreach ($row_dpi as $dpi_block) :
                  ?>
                    <span class="global-momentum__table-block">
                      <?php echo esc_html($dpi_block); ?>
                    </span>
                  <?php endforeach; ?>
                </td>
                <td class="text-center">
                  <?php if ($legal_artefacts_status === 'using') : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--check">
                      <i class="fa-solid fa-check"></i>
                    </span>
                  <?php elseif ($legal_artefacts_status === 'in_progress') : ?>
                    <span class="global-momentum__table-status-text">In progress</span>
                  <?php else : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--cross">
                      <i class="fa-solid fa-xmark"></i>
                    </span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($technical_scope_status === 'using') : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--check">
                      <i class="fa-solid fa-check"></i>
                    </span>
                  <?php elseif ($technical_scope_status === 'in_progress') : ?>
                    <span class="global-momentum__table-status-text">In progress</span>
                  <?php else : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--cross">
                      <i class="fa-solid fa-xmark"></i>
                    </span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($service_provider_status === 'selected') : ?>
                    <span class="global-momentum__table-entity-name"><?php echo esc_html($service_provider_name); ?></span>
                  <?php elseif ($service_provider_status === 'in_progress') : ?>
                    <span class="global-momentum__table-status-text">In progress</span>
                  <?php else : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--cross">
                      <i class="fa-solid fa-xmark"></i>
                    </span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($hosting_status === 'selected') : ?>
                    <span class="global-momentum__table-entity-name"><?php echo esc_html($hosting_name); ?></span>
                  <?php elseif ($hosting_status === 'in_progress') : ?>
                    <span class="global-momentum__table-status-text">In progress</span>
                  <?php else : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--cross">
                      <i class="fa-solid fa-xmark"></i>
                    </span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($funding_status === 'with_fund') : ?>
                    <span class="global-momentum__table-entity-name"><?php echo esc_html($funder_name ?: 'Funded'); ?></span>
                  <?php elseif ($funding_status === 'in_progress') : ?>
                    <span class="global-momentum__table-status-text">In progress</span>
                  <?php else : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--cross">
                      <i class="fa-solid fa-xmark"></i>
                    </span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($program_status === 'selected') : ?>
                    <span class="global-momentum__table-entity-name"><?php echo esc_html($program_name); ?></span>
                  <?php elseif ($program_status === 'in_progress') : ?>
                    <span class="global-momentum__table-status-text">In progress</span>
                  <?php else : ?>
                    <span class="global-momentum__table-status-icon global-momentum__table-status-icon--cross">
                      <i class="fa-solid fa-xmark"></i>
                    </span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="global-momentum__table-footer text-center">
        <button
          type="button"
          class="btn btn-outline-primary global-momentum__table-download"
          data-xlsx-url="<?php echo esc_url(admin_url('admin-ajax.php?action=cdpi_global_momentum_xlsx&nonce=' . wp_create_nonce('cdpi_global_momentum_xlsx'))); ?>"
        >
          Download full table in Excel
          <i class="fa-solid fa-download ms-1"></i>
        </button>
        <button type="button" class="btn btn-primary global-momentum__table-footer-close">
          Close
        </button>
      </div>
    </div>
  </div>
  </div>

  <div class="global-momentum__modal" aria-hidden="true" role="dialog" aria-modal="true">
    <div class="global-momentum__modal-backdrop"></div>
    <div class="global-momentum__modal-dialog" role="document">
      <button type="button" class="global-momentum__modal-close" aria-label="Close">
        <span>&times;</span>
      </button>
      <div class="global-momentum__modal-layout">
        <div class="global-momentum__modal-body">
          <div class="global-momentum__modal-left">
            <div class="global-momentum__modal-flag-card">
              <div class="global-momentum__modal-status-badge">
                <span class="global-momentum__modal-status-dot"></span>
                <span class="global-momentum__modal-status-text"></span>
              </div>
              <div class="global-momentum__modal-flag">
                <img src="" alt="" loading="lazy" />
              </div>
              <h3 class="global-momentum__modal-country"></h3>
            </div>
          </div>

          <div class="global-momentum__modal-right">
            <div class="global-momentum__modal-population">
              <div class="global-momentum__modal-population-icon">
                <i class="fa-solid fa-user-group"></i>
              </div>
              <span class="global-momentum__modal-population-value"></span>
              <span class="global-momentum__modal-population-label">Population Impacted</span>
            </div>
            <div class="global-momentum__modal-meta">
              <div class="global-momentum__modal-meta-row">
                <span class="global-momentum__modal-label">DPI Block</span>
                <span class="global-momentum__modal-block"></span>
              </div>
              <div class="global-momentum__modal-meta-row">
                <span class="global-momentum__modal-label">First Use Case</span>
                <p class="global-momentum__modal-details"></p>
              </div>
              <div class="global-momentum__modal-meta-row">
                <span class="global-momentum__modal-label">Impact</span>
                <div class="global-momentum__modal-impacts"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
        <div class="global-momentum__modal-daas-details">
          <div class="global-momentum__modal-daas-grid"></div>
        </div>

        <div class="global-momentum__modal-partnerships">
          <span class="global-momentum__modal-label mb-2">Partnerships</span>
          <div class="global-momentum__modal-partners"></div>
        </div>
    </div>
    <button type="button" class="global-momentum__modal-nav global-momentum__modal-nav--prev" aria-label="Previous country">
      <i class="fa-solid fa-chevron-left"></i>
    </button>
    <button type="button" class="global-momentum__modal-nav global-momentum__modal-nav--next" aria-label="Next country">
      <i class="fa-solid fa-chevron-right"></i>
    </button>
  </div>
</section>

<style>
  .global-momentum {
    background-color: #ffffff;
    padding: 120px 0 104px;
  }

  .global-momentum__header {
    margin-bottom: 80px;
  }

  .global-momentum__eyebrow {
    display: inline-block;
    margin-bottom: 20px;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    text-align: center;
    color: transparent;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    background-clip: text;
  }

  .global-momentum__title {
    margin: 0 0 20px;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 40px;
    line-height: 130%;
    letter-spacing: -0.02em;
    text-align: center;
    color: #0F0F0F;
  }

  .global-momentum__subtitle {
    color: #5E6979;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    text-align: center;
    max-width: 864px;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 80px;
  }

  .global-momentum__stats {
    margin-bottom: 3.75rem;
  }

  .global-momentum__stat-card {
    position: relative;
    background-color: #ffffff;
    border-radius: 15px;
    border: 1px solid #D6E1F1;
    padding: 40px 24px;
    height: 200px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .global-momentum__stat-bg {
    position: absolute;
    right: -30px;
    bottom: -30px;
    width: 154px;
    height: 154px;
    opacity: 0.33;
    pointer-events: none;
    transform: rotate(-45deg);
  }

  .global-momentum__stat-bg img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  .global-momentum__stat-main {
    position: relative;
    z-index: 1;
  }

  .global-momentum__stat-value {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 54.22px;
    line-height: 100%;
    letter-spacing: 0;
    color: #000000;
  }

  .global-momentum__stat-label {
    margin: 8px 0 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 20.33px;
    line-height: 100%;
    letter-spacing: 0;
    color: #000000;
    max-width: 155px;
  }

  .global-momentum__engagements-header {
    margin-top: 104px;
    margin-bottom: 3rem;
    row-gap: 1rem;
  }

  .global-momentum__engagements-title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 20px;
    line-height: 170%;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #0F0F0F;
  }

  .global-momentum__table-btn {
    background-color: #4B4AEA;
    border-color: #4948E1;
    border-radius: 0.4375rem;
    padding: 0.875rem 1.5rem;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0.01em;
    color: #FFFFFF;
  }

  .global-momentum__table-btn:hover {
    background-color: #1C1AE4;
    border-color: #1C1AE4;
  }

  .global-momentum__slider-wrapper {
    position: relative;
    display: flex;
    flex-direction: column;
  }

  .global-momentum__slider {
    display: flex;
    gap: 1.5rem;
    overflow-x: auto;
    padding-bottom: 0.5rem;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
  }

  .global-momentum__slider-arrow {
    border: 1px solid #D6E1F1;
    background-color: #ffffff;
    width: 40px;
    height: 40px;
    border-radius: 0.3125rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    transition: background-color 0.2s ease, border-color 0.2s ease;
  }

  .global-momentum__slider-arrow:hover {
    background-color: #F4F5FB;
    border-color: #C4D2F3;
  }

  .global-momentum__slider-arrow--prev {
    margin-right: 0.5rem;
  }

  .global-momentum__slider-arrow--next {
    margin-left: 0.5rem;
  }

  .global-momentum__slider::-webkit-scrollbar {
    display: none;
  }

  .global-momentum__nav {
    margin-top: 20px;
    width: 100%;
    display: flex;
    justify-content: flex-end;
  }

  .global-momentum__nav-btn {
    width: 40px;
    height: 40px;
    border-radius: 5px;
    border: 1px solid #D6E1F1;
    background: #ffffff;
    color: #5E6979;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 8px;
  }

  .global-momentum__nav-btn:hover {
    border-color: #4f46e5;
    color: #4f46e5;
  }

  .global-momentum__country-card {
    flex: 0 0 206px;
    max-width: 206px;
    height: 322px;
    background-color: #ffffff;
    border-radius: 1.5rem;
    border: 1px solid #D6E1F1;
    padding: 2.5rem 1.5rem 2.25rem;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    gap: 1.25rem;
    box-shadow: none;
    scroll-snap-align: start;
    cursor: pointer;
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
  }

  .global-momentum__country-card:hover {
    transform: translateY(-4px);
    box-shadow: 0px 16px 36px rgba(15, 29, 68, 0.06);
    border-color: #C4D2F3;
  }

  .global-momentum__country-flag img {
    width: 94px;
    height: 63px;
    border-radius: 0;
    object-fit: cover;
  }

  .global-momentum__country-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1 1 auto;
  }

  .global-momentum__country-name {
    margin: 0 auto 0.75rem;
    max-width: 103px;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 20px;
    line-height: 100%;
    letter-spacing: 0;
    color: #000000;
    text-align: center;
    word-wrap: break-word;
    overflow-wrap: break-word;
  }

  .global-momentum__country-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    align-items: center;
    text-align: center;
    margin-top: auto;
  }

  .global-momentum__country-block-label {
    margin-bottom: 8px;
    font-family: "Inter", system-ui, sans-serif;
    font-weight: 700;
    font-size: 13px;
    line-height: 100%;
    letter-spacing: 0;
    text-align: center;
    color: #6564DB;
  }

  .global-momentum__country-block {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 16px;
    line-height: 100%;
    letter-spacing: 0;
    text-align: center;
    color: #212121;
  }

  .global-momentum__country-description {
    margin: 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 13px;
    font-weight: 400;
    line-height: 1.7;
    letter-spacing: 0.02em;
    color: #5E6979;
  }

  .global-momentum__modal {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 1050;
  }

  .global-momentum__modal.is-open {
    display: flex;
  }

  .global-momentum__modal-backdrop {
    position: absolute;
    inset: 0;
    background-color: rgba(15, 23, 42, 0.45);
  }

  .global-momentum__modal-dialog {
    position: relative;
    background-color: #ffffff;
    border-radius: 1.8rem;
    border: 1.15px solid #9B9B9B;
    padding: 2.5rem 2.75rem;
    max-width: 691px;
    width: 691px;
    min-height: 545px;
    max-height: calc(100vh - 80px);
    overflow-y: auto;
    z-index: 1;
    box-shadow: 0px 22px 60px rgba(15, 29, 68, 0.16);
  }

  .global-momentum__modal-dialog.global-momentum__modal-dialog--no-partnerships {
    min-height: 0;
  }

  .global-momentum__modal-close {
    position: absolute;
    top: 0.75rem;
    right: 0.85rem;
    border: none;
    background: transparent;
    font-size: 1.5rem;
    line-height: 1;
    cursor: pointer;
    color: #6B7280;
  }

  .global-momentum__modal-layout {
    position: relative;
    width: 100%;
    display: flex;
    gap: 2.25rem;
    align-items: stretch;
  }

  .global-momentum__modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background-color: #ffffff;
    box-shadow: 0 8px 20px rgba(15, 29, 68, 0.12);
    width: 40px;
    height: 40px;
    border-radius: 999px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #4B4AEA;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
  }

  .global-momentum__modal-nav--prev {
    left: calc(50% - 691px / 2 - 90px);
  }

  .global-momentum__modal-nav--next {
    left: calc(50% + 691px / 2 + 50px);
  }

  .global-momentum__modal-nav:hover {
    background-color: #4B4AEA;
    color: #ffffff;
    box-shadow: 0 10px 24px rgba(15, 29, 68, 0.18);
  }

  .global-momentum__modal-body {
    display: flex;
    gap: 2.25rem;
    align-items: stretch;
    width: 100%;
  }

  .global-momentum__modal-left {
    flex: 0 0 260px;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
  }

  .global-momentum__modal-status-badge {
    position: absolute;
    top: -0.85rem;
    left: 50%;
    transform: translateX(-50%);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.15rem 0.85rem;
    background-color: #ffffff;
    color: #0F172A;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
  }

  .global-momentum__modal-status-badge--formal {
    background-color: #FAD6C4;
    color: #5C3100;
    box-shadow: none;
  }

  .global-momentum__modal-status-badge--engaged {
    background-color: #B7E8BD;
    color: #035A29;
    box-shadow: none;
  }

  .global-momentum__modal-status-badge--live {
    background-color: #FFFFFF;
    color: #313131;
    box-shadow: 0px 4px 11.4px 0px #00000040;
  }

  .global-momentum__modal-status-dot {
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background-color: #22C55E;
    animation: global-momentum-live-dot 1.2s ease-in-out infinite;
  }

  @keyframes global-momentum-live-dot {
    0% {
      opacity: 1;
      transform: scale(1);
    }
    50% {
      opacity: 0.25;
      transform: scale(0.75);
    }
    100% {
      opacity: 1;
      transform: scale(1);
    }
  }

  @media (prefers-reduced-motion: reduce) {
    .global-momentum__modal-status-dot {
      animation: none;
    }
  }

  .global-momentum__modal-flag-card {
    position: relative;
    margin-top: 1.25rem;
    width: 250.09px;
    height: 242.06px;
    border-radius: 27.53px;
    border: 1.15px solid #A1A1A1;
    padding: 2.25rem 2rem 2rem;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;  
  }

  .global-momentum__modal-flag img {
    width: 96px;
    height: 64px;
    border-radius: 0;
    object-fit: cover;
    margin-bottom: 1.5rem;
  }

  .global-momentum__modal-country {
    margin: 0;
    font-family: "Helvetica Neue", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 28.34px;
    line-height: 100%;
    letter-spacing: 0;
    color: #212121;
  }

  .global-momentum__modal-right {
    flex: 1 1 auto;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
  }

  .global-momentum__modal-meta {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
  }

  .global-momentum__modal-meta-row {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
  }

  .global-momentum__modal-label {
    font-size: 0.8125rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #6B7280;
    font-weight: 600;
  }

  .global-momentum__modal-meta-row:first-child .global-momentum__modal-label {
    margin-top: 25px;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 14.91px;
    line-height: 100%;
    letter-spacing: 0;
    text-transform: none;
    color: #6564DB;
  }

  .global-momentum__modal-meta-row:nth-child(2) .global-momentum__modal-label {
    margin-top: 12px;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 14.91px;
    line-height: 100%;
    letter-spacing: 0;
    text-transform: none;
    color: #6564DB;
  }

  .global-momentum__modal-meta-row:nth-child(3) .global-momentum__modal-label {
    margin-top: 12px;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 14.91px;
    line-height: 100%;
    letter-spacing: 0;
    text-transform: none;
    color: #535353;
  }

  .global-momentum__modal-partnerships .global-momentum__modal-label {
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 14.91px;
    line-height: 100%;
    letter-spacing: 0;
    text-transform: none;
    color: #6564DB;
  }

  .global-momentum__modal-block {
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 20.21px;
    line-height: 100%;
    letter-spacing: 0;
    color: #212121;
  }

  .global-momentum__modal-details {
    margin: 0;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-size: 20.21px;
    line-height: 100%;
    letter-spacing: 0;
    color: #212121;
  }

  .global-momentum__modal-impacts {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  .global-momentum__modal-population {
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
    font-family: "Outfit", system-ui, sans-serif;
  }

  .global-momentum__modal-population-icon {
    width: 32px;
    height: 32px;
    border-radius: 999px;
    background-color: #EEF2FF;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #545F71;
    flex-shrink: 0;
  }

  .global-momentum__modal-population-value {
    font-weight: 700;
    font-size: 0.9375rem;
    color: #000000;
  }

  .global-momentum__modal-population-label {
    font-size: 0.8125rem;
    color: #000000;
  }

  .global-momentum__popup-impact-chip {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 33px;
    padding: 0 0.9rem;
    border-radius: 999px;
    border: 1px solid #6564DB;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 12px;
    line-height: 100%;
    letter-spacing: 0;
    text-align: center;
    color: #6564DB;
    background: #FFFFFF;
  }

  .global-momentum__modal-partnerships {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 1.75rem;
    width: 100%;
    align-items: flex-start;
  }

  .global-momentum__modal-partners {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 120px));
    gap: 1rem;
    width: 100%;
    justify-content: start;
  }

  .global-momentum__modal-partner {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 120px;
  }

  .global-momentum__modal-partner-logo {
    width: 88.43px;
    height: 88.43px;
    margin: 0 auto 25px;
    padding: 16px;
    border-radius: 14.58px;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
  }

  .global-momentum__modal-partner-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }

  .global-momentum__modal-partner-name {
    margin: 0;
    font-family: "Helvetica Neue", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-size: 12px;
    font-weight: 700;
    line-height: 100%;
    letter-spacing: 0;
    color: #000000;
    text-align: center;
  }

  .global-momentum__modal-partner-name {
    margin: 0;
    font-family: "Helvetica Neue", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    font-weight: 700;
    font-style: normal;
    font-size: 16px;
    line-height: 100%;
    letter-spacing: 0;
    text-align: center;
    color: #000000;
  }

  @media (max-width: 991px) {
    .global-momentum__modal-dialog {
      width: calc(100% - 1.5rem);
      max-width: 691px;
      padding: 2rem 1.5rem;
    }

    .global-momentum__modal-nav {
      display: none;
    }

    .global-momentum__modal-body {
      flex-direction: column;
      gap: 1.25rem;
      align-items: center;
    }

    .global-momentum__modal-left {
      flex: 0 0 auto;
      width: 100%;
      align-items: center;
    }

    .global-momentum__modal-right {
      width: 100%;
    }

    .global-momentum__modal-population {
      justify-content: flex-start;
      flex-wrap: wrap;
    }
  }

  @media (max-width: 575px) {
    .global-momentum .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .global-momentum__header {
      margin-bottom: 10px;
    }

    .global-momentum__title {
      /* font-size: 2rem; */
    }

    .global-momentum__slider {
      gap: 1rem;
    }

    .global-momentum__country-card {
      flex: 0 0 200px;
      max-width: 200px;
    }

    .global-momentum__modal-partners {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }

    .global-momentum__modal-partner {
      width: 100%;
    }
  }

  .global-momentum__view {
    display: none;
  }

  .global-momentum__view.is-active {
    display: block;
  }

  .global-momentum__table-dialog {
    padding: 0;
    width: 100%;
    margin-top: 0;
  }

  .global-momentum__table-close {
    display: none;
  }

  .global-momentum__table-header {
    margin-bottom: 15px;
  }

  .global-momentum__table-wrapper {
    overflow: auto;
    height: 34rem;
    margin: 0 -0.5rem -0.5rem;
    padding: 0.5rem;
  }

  .global-momentum__table-wrapper::-webkit-scrollbar {
    width: 18px;
  }

  .global-momentum__table-wrapper::-webkit-scrollbar-track {
    background: #D1D1F7;
    border-radius: 99px;
  }

  .global-momentum__table-wrapper::-webkit-scrollbar-thumb {
    background: #6564DB;
    border-radius: 99px;
  }

  .global-momentum__table {
    border-collapse: separate;
    border-spacing: 0 19px;
  }

  .global-momentum__table thead th {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 16px;
    line-height: 100%;
    letter-spacing: 0;
    color: #253494;
    max-width: 119px;
    text-align: center;
    border-bottom: none !important;
  }

  .global-momentum__table tbody td {
    vertical-align: middle;
    font-size: 0.875rem;
    color: #111827;
    border-top: none;
    padding: 20px 24px;
  }

  .global-momentum__table tbody td:nth-child(2) {
    width: 139px;
    min-width: 139px;
    padding-left: 0;
    padding-right: 0;
  }

  .global-momentum__table thead th:nth-child(2) {
    width: 139px;
    min-width: 139px;
    max-width: 139px;
  }

  .global-momentum__table tbody tr {
    position: relative;
    border-radius: 25px;
    overflow: hidden;
  }

  .global-momentum__table tbody tr td:first-child {
    position: relative;
    overflow: visible;
    border-left: 1px solid #CACACA;
    border-top: 1px solid #CACACA;
    border-bottom: 1px solid #CACACA;
    border-top-left-radius: 25px;
    border-bottom-left-radius: 25px;
  }

  .global-momentum__table tbody tr td:last-child {
    border-right: 1px solid #CACACA;
    border-top: 1px solid #CACACA;
    border-bottom: 1px solid #CACACA;
    border-top-right-radius: 25px;
    border-bottom-right-radius: 25px;
  }

  .global-momentum__table tbody tr td:not(:first-child):not(:last-child) {
    border-top: 1px solid #CACACA;
    border-bottom: 1px solid #CACACA;
  }

  .global-momentum__table-row-status {
    position: absolute;
    top: 0;
    left: 24px;
    transform: translateY(-50%);
    padding: 0.25rem 0.8rem;
    border-radius: 999px;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 12px;
    line-height: 1;
    color: #313131;
    background-color: #FFFFFF;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .global-momentum__table-row-status--formal {
    background-color: #FAD6C4;
    color: #5C3100;
  }

  .global-momentum__table-row-status--engaged {
    background-color: #B7E8BD;
    color: #035A29;
  }

  .global-momentum__table-row-status--live {
    background-color: #FFFFFF;
    color: #313131;
    box-shadow: 0px 4px 11.4px 0px #00000040;
  }

  .global-momentum__table tbody tr.global-momentum__table-impact-row td {
    border-top: none;
    padding-top: 0;
    padding-bottom: 1.1rem;
  }

  .global-momentum__table-country {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
  }

  .global-momentum__table-flag img {
    width: 56px;
    height: 42px;
    border-radius: 0;
    object-fit: cover;
  }

  .global-momentum__table-country-name {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 700;
    font-size: 20px;
    /* line-height: 100%; */
    letter-spacing: 0;
    color: #000000;
    text-align: center;
    max-width: 139px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .global-momentum__table-block {
    display: block;
    max-width: 139px;
    margin: 0 auto;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 100%;
    letter-spacing: 0;
    text-align: center;
    color: #212121;
  }

  .global-momentum__table-text {
    display: block;
    max-width: 260px;
    margin: 0 auto;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 0.875rem;
    line-height: 100%;
    letter-spacing: 0;
    text-align: center;
    color: #212121;
  }

  .global-momentum__table-text--in-progress {
    color: #9ca3af;
  }

  .global-momentum__table-impacts,
  .global-momentum__table-partnerships {
    display: flex;
    flex-wrap: wrap;
    gap: 0.35rem;
  }

  .global-momentum__table-impact-chip,
  .global-momentum__table-partner-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.125rem 0.6rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
  }

  .global-momentum__table-impact-chip {
    background-color: #EEF2FF;
    border: 1px solid #6564DB;
    color: #4B4AEA;
  }

  .global-momentum__table-partner-chip {
    background-color: #F3F4FF;
    color: #4338CA;
  }

  .global-momentum__table-status-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.75rem;
    height: 1.75rem;
    border-radius: 999px;
    font-size: 0.9rem;
    background-color: transparent;
    border: 1.5px solid currentColor;
  }

  .global-momentum__table-status-icon--check {
    color: #22C55E;
  }

  .global-momentum__table-status-icon--cross {
    color: #EF4444;
  }

  .global-momentum__table-status-icon--tbd {
    color: #6B7280;
  }

  .global-momentum__table-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.15rem 0.75rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 500;
    white-space: nowrap;
  }

  .global-momentum__table-pill--muted {
    background-color: #EEF2FF;
    color: #4B5563;
  }

  .global-momentum__table-pill--primary {
    background-color: #4B4AEA;
    color: #ffffff;
  }

  .global-momentum__table-footer {
    margin-top: 1.5rem;
    display: flex;
    justify-content: center;
    gap: 0.75rem;
  }

  .global-momentum__table-download {
    border-radius: 7px;
    height: 58px;
    display: inline-flex;
    align-items: center;
    gap: 24px;
    background-color: #FFFFFF;
    border-color: #4948E1;
    color: #000000;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 16px;
    line-height: 160%;
    letter-spacing: 0.01em;
    text-align: center;
  }

  .global-momentum__table-footer-close {
    border-radius: 7px;
    padding: 0 1.5rem;
    height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #4948E1;
    border-color: #4948E1;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 14px;
    line-height: 160%;
    letter-spacing: 0.01em;
    color: #FFFFFF;
  }

  @media (max-width: 575px) {
    .global-momentum__table-dialog {
      width: calc(100% - 1.5rem);
    }

    .global-momentum__table-header .global-momentum__title {
      /* font-size: 1.625rem; */
    }
  }

  .global-momentum__table-entity-name {
    display: inline-block;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 0.875rem;
    color: #111827;
  }

  .global-momentum__table-status-text {
    display: inline-block;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 0.8125rem;
    color: #6B7280;
    min-width: 65px;
  }

  .global-momentum__modal-daas-details {
    margin-top: 1.75rem;
    width: 100%;
  }

  .global-momentum__modal-daas-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.75rem;
    width: 100%;
  }

  .global-momentum__modal-daas-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 0.85rem 0.75rem;
    border-radius: 14px;
    border: 1px solid #E5E7EB;
    background-color: #FAFAFA;
    text-align: center;
    min-height: 90px;
    justify-content: center;
  }

  .global-momentum__modal-daas-label {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 0.6875rem;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #6B7280;
  }

  .global-momentum__modal-daas-body {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.35rem;
  }

  .global-momentum__modal-daas-logo {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    background-color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    padding: 6px;
  }

  .global-momentum__modal-daas-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
  }

  .global-momentum__modal-daas-name {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 600;
    font-size: 0.8125rem;
    color: #111827;
  }

  .global-momentum__modal-daas-inprogress {
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 0.75rem;
    color: #6B7280;
  }

  @media (max-width: 575px) {
    .global-momentum__modal-daas-grid {
      grid-template-columns: repeat(2, 1fr);
    }
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.global-momentum__slider');
    const cards = slider ? Array.from(slider.querySelectorAll('.global-momentum__country-card')) : [];
    let currentIndex = 0;
    const modal = document.querySelector('.global-momentum__modal');
    const cardsView = document.querySelector('.global-momentum__cards-view');
    const tableView = document.querySelector('.global-momentum__table-view');
    const tableOpenBtn = document.querySelector('.global-momentum__table-btn');
    const sliderPrev = document.querySelector('.global-momentum__nav-prev');
    const sliderNext = document.querySelector('.global-momentum__nav-next');

    if (slider && cards.length > 1) {
      const shouldAutoplay = (slider.getAttribute('data-autoplay') || '').toLowerCase() === 'true';
      const autoplayInterval = Math.max(1500, parseInt(slider.getAttribute('data-autoplay-interval') || '5000', 10) || 5000);
      let autoplayTimer = null;
      const scrollToCard = (index) => {
        const safeIndex = (index + cards.length) % cards.length;
        const target = cards[safeIndex];
        if (!target) return;
        currentIndex = safeIndex;
        slider.scrollTo({
          left: target.offsetLeft - slider.offsetLeft,
          behavior: 'smooth'
        });
      };
      const stopAutoplay = () => {
        if (!autoplayTimer) return;
        clearInterval(autoplayTimer);
        autoplayTimer = null;
      };
      const startAutoplay = () => {
        if (!shouldAutoplay || autoplayTimer) return;
        autoplayTimer = setInterval(() => {
          scrollToCard(currentIndex + 1);
        }, autoplayInterval);
      };

      if (sliderPrev) {
        sliderPrev.addEventListener('click', function () {
          stopAutoplay();
          scrollToCard(currentIndex - 1);
          startAutoplay();
        });
      }

      if (sliderNext) {
        sliderNext.addEventListener('click', function () {
          stopAutoplay();
          scrollToCard(currentIndex + 1);
          startAutoplay();
        });
      }

      slider.addEventListener('mouseenter', stopAutoplay);
      slider.addEventListener('mouseleave', startAutoplay);
      startAutoplay();
    }

    const openTableView = () => {
      if (!cardsView || !tableView) return;
      cardsView.classList.remove('is-active');
      tableView.classList.add('is-active');
      tableView.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    const closeTableView = () => {
      if (!cardsView || !tableView) return;
      tableView.classList.remove('is-active');
      cardsView.classList.add('is-active');
      cardsView.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    if (tableView && tableOpenBtn) {
      const tableCloseBtn = tableView.querySelector('.global-momentum__table-close');
      const tableFooterCloseBtn = tableView.querySelector('.global-momentum__table-footer-close');
      const tableDownloadBtn = tableView.querySelector('.global-momentum__table-download');

      tableOpenBtn.addEventListener('click', function (event) {
        event.preventDefault();
        openTableView();
      });

      if (tableCloseBtn) {
        tableCloseBtn.addEventListener('click', closeTableView);
      }

      if (tableFooterCloseBtn) {
        tableFooterCloseBtn.addEventListener('click', closeTableView);
      }

      if (tableDownloadBtn) {
        tableDownloadBtn.addEventListener('click', function () {
          const url = tableDownloadBtn.getAttribute('data-xlsx-url') || '';
          if (!url) return;
          window.location.assign(url);
        });
      }
    }

    if (modal && cards.length) {
      const backdrop = modal.querySelector('.global-momentum__modal-backdrop');
      const closeBtn = modal.querySelector('.global-momentum__modal-close');
      const dialogEl = modal.querySelector('.global-momentum__modal-dialog');
      const flagImg = modal.querySelector('.global-momentum__modal-flag img');
      const countryEl = modal.querySelector('.global-momentum__modal-country');
      const statusDotEl = modal.querySelector('.global-momentum__modal-status-dot');
      const statusTextEl = modal.querySelector('.global-momentum__modal-status-text');
      const statusBadgeEl = modal.querySelector('.global-momentum__modal-status-badge');
      const populationValueEl = modal.querySelector('.global-momentum__modal-population-value');
      const populationRowEl = modal.querySelector('.global-momentum__modal-population');
      const blockEl = modal.querySelector('.global-momentum__modal-block');
      const detailsEl = modal.querySelector('.global-momentum__modal-details');
      const impactsEl = modal.querySelector('.global-momentum__modal-impacts');
      const daasGridEl = modal.querySelector('.global-momentum__modal-daas-grid');
      const partnersEl = modal.querySelector('.global-momentum__modal-partners');
      const partnersSectionEl = modal.querySelector('.global-momentum__modal-partnerships');
      const modalPrev = modal.querySelector('.global-momentum__modal-nav--prev');
      const modalNext = modal.querySelector('.global-momentum__modal-nav--next');

      const getCardData = (card) => {
        const raw = card.getAttribute('data-country') || '';
        try {
          return raw ? JSON.parse(raw) : null;
        } catch (e) {
          console.error('Failed to parse country data', e);
          return null;
        }
      };

      const setModalContent = (data) => {
        if (!data) return;
        if (flagImg) {
          flagImg.src = data.flag || '';
          flagImg.alt = data.name || '';
        }
        if (countryEl) countryEl.textContent = data.name || '';
        const statusValue = (data.status || '').toString().trim();
        const isLiveStatus = statusValue.toLowerCase() === 'live';
        const statusKey = statusValue.toLowerCase();
        const statusClass = statusKey === 'engaged'
          ? 'global-momentum__modal-status-badge--engaged'
          : (statusKey === 'live'
            ? 'global-momentum__modal-status-badge--live'
            : 'global-momentum__modal-status-badge--formal');
        if (statusBadgeEl) {
          statusBadgeEl.classList.remove(
            'global-momentum__modal-status-badge--formal',
            'global-momentum__modal-status-badge--engaged',
            'global-momentum__modal-status-badge--live'
          );
          statusBadgeEl.classList.add(statusClass);
        }
        if (statusTextEl) statusTextEl.textContent = statusValue === 'Live' ? statusValue + '!' : statusValue;
        if (statusDotEl) statusDotEl.style.display = isLiveStatus ? 'inline-block' : 'none';
        if (populationRowEl) {
          const hasPopulation = !!data.population;
          populationRowEl.style.display = hasPopulation ? 'flex' : 'none';
          if (populationValueEl) {
            populationValueEl.textContent = hasPopulation ? data.population : '';
          }
        }
        if (blockEl) blockEl.textContent = data.block || '';
        if (detailsEl) detailsEl.textContent = data.details || '';
        if (impactsEl) {
          impactsEl.innerHTML = '';
          if (Array.isArray(data.impacts)) {
            data.impacts.forEach((label) => {
              const chip = document.createElement('span');
              chip.className = 'global-momentum__popup-impact-chip';
              chip.textContent = label;
              impactsEl.appendChild(chip);
            });
          }
        }

        if (partnersEl) {
          partnersEl.innerHTML = '';
          const hasPartners = Array.isArray(data.partnerships) && data.partnerships.length > 0;
          if (partnersSectionEl) partnersSectionEl.style.display = hasPartners ? '' : 'none';
          if (dialogEl) dialogEl.classList.toggle('global-momentum__modal-dialog--no-partnerships', !hasPartners);
          if (hasPartners) {
            data.partnerships.forEach((partner) => {
              const wrapper = document.createElement('div');
              wrapper.className = 'global-momentum__modal-partner';

              const logoWrap = document.createElement('div');
              logoWrap.className = 'global-momentum__modal-partner-logo';
              if (partner.logo) {
                const img = document.createElement('img');
                img.src = partner.logo;
                img.alt = partner.name || '';
                logoWrap.appendChild(img);
              }

              const nameEl = document.createElement('p');
              nameEl.className = 'global-momentum__modal-partner-name';
              nameEl.textContent = partner.name || '';

              wrapper.appendChild(logoWrap);
              wrapper.appendChild(nameEl);
              partnersEl.appendChild(wrapper);
            });
          }
        }
      };

      const openModal = (data) => {
        if (!data) return;
        setModalContent(data);
        modal.classList.add('is-open');
        document.body.style.overflow = 'hidden';
      };

      const closeModal = () => {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
      };

      cards.forEach((card, index) => {
        card.setAttribute('data-index', String(index));
        card.addEventListener('click', () => {
          currentIndex = index;
          const data = getCardData(card);
          openModal(data);
        });
      });

      const showRelativeCountry = (delta) => {
        if (!cards.length) return;
        const nextIndex = (currentIndex + delta + cards.length) % cards.length;
        currentIndex = nextIndex;
        const card = cards[nextIndex];
        const data = getCardData(card);
        setModalContent(data);
      };

      if (modalPrev) {
        modalPrev.addEventListener('click', function () {
          showRelativeCountry(-1);
        });
      }

      if (modalNext) {
        modalNext.addEventListener('click', function () {
          showRelativeCountry(1);
        });
      }

      if (backdrop) {
        backdrop.addEventListener('click', closeModal);
      }
      if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
      }
      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
          if (modal && modal.classList.contains('is-open')) {
            closeModal();
          }
          if (tableView && tableView.classList.contains('is-active')) {
            closeTableView();
          }
        }
      });
    }
  });
</script>

