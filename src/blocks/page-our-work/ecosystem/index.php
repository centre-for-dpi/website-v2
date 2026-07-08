<?php
/**
 * Ecosystem section – Partnership header + category blocks (Researchers, Technical, Development).
 * Partner images use the same logos/ path pattern as the About us page (e.g. logos/gates-foundation.png).
 */


$partnersHandler = new CustomPost('partner', null);

$partnersRaw = $partnersHandler->getListOfPosts([
    'meta_fields',
    "thumbnail"
]);

$partnersRaw = is_array($partnersRaw) ? array_values($partnersRaw) : [];
usort($partnersRaw, function ($a, $b) {
    $name_a = strtolower(trim((string) ($a['title'] ?? '')));
    $name_b = strtolower(trim((string) ($b['title'] ?? '')));
    return $name_a <=> $name_b;
});

// Build partners array grouped by category
$partners_by_category = [];

// Map meta category slugs to the legacy ones used below
$category_slug_map = [
    'tech-solution-builders' => 'technical',
    'researchers' => 'researchers',
    'development' => 'development',
];

// Loop through partnersRaw and group by category
if (!empty($partnersRaw) && is_array($partnersRaw)) {
    foreach ($partnersRaw as $partner) {
        // Make sure 'categories' exists and is an array
        if (!empty($partner['categories']) && is_array($partner['categories'])) {
            foreach ($partner['categories'] as $cat) {
                $mapped_cat = $category_slug_map[$cat['slug']] ?? $cat['slug'];
                $partners_by_category[$mapped_cat][] = [
                    'id'      => $partner['id'] ?? '',
                    'name'    => $partner['title'] ?? '',
                    'link'    => $partner['meta_fields']['partner_url'] ?? $partner['link'],
                    'slug'    => $partner['slug'] ?? '',
                    'excerpt' => $partner['excerpt'] ?? '',
                    'image'=> $partner['thumbnail'] ?? '',
                ];
            }
        }
    }
}


$ecosystem_categories = [
   
    'technical' => [
        'title' => 'TECHNICAL SOLUTION BUILDERS: DPGs, SPs, ISVs',
        'description' => 'We work with technology solution providers to strengthen offerings for countries.',
    ],
    'development' => [
        'title' => 'DEVELOPMENT PARTNERS',
        'description' => 'We work with funders and the development sector for global DPI advocacy and implementation.',
    ],
    'researchers' => [
      'title' => 'RESEARCHERS',
      'description' => 'We work with researchers to drive thought leadership and conduct impact evaluations of DPI',
  ],
];
?>
<div class="redlof-block ecosystem">
  <section class="ecosystem__section">
    <div class="ecosystem__inner">
      <div class="ecosystem__partnership">
        <div class="ecosystem__pattern">
          <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-3.svg'); ?>" alt="" loading="lazy" />
        </div>
        <p class="ecosystem__label">ORCHESTRATION</p>
        <h2 class="ecosystem__headline">How we work with<br />the ecosystem</h2>
      </div>
    </div>
  </section>

  <?php foreach ($ecosystem_categories as $cat_slug => $cat_config) :
      $partners_in_cat = $partners_by_category[$cat_slug] ?? [];
      if (empty($partners_in_cat)) {  // if no partners in this category, skip
          continue;
      }
  ?>
  <div class="ecosystem__category">
    <div class="ecosystem__category-separator"></div>
    <div class="ecosystem__inner ecosystem__category-inner">
      <div class="ecosystem__category-row">
        <div class="ecosystem__category-text">
          <h3 class="ecosystem__category-title"><?php echo esc_html($cat_config['title']); ?></h3>
          <p class="ecosystem__category-desc"><?php echo esc_html($cat_config['description']); ?></p>
        </div>
        <div class="ecosystem__logos">
          <?php
          foreach ($partners_in_cat as $partner) :
              $img_src = $partner['image'] ?? '';
              $link = $partner['link'] ?? '#';
          ?>
            <a href="<?php echo esc_url($link); ?>" class="ecosystem__logo-item" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr($partner['name']); ?>">
              <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($partner['name']); ?>" loading="lazy" />
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <?php endforeach; ?>

  <div class="ecosystem__separator ecosystem__separator--purple"></div>
</div>

<style>
  .ecosystem {
    padding: 0;
  }

  .ecosystem__section {
    background: #ffffff;
    padding: 80px 0 80px 0;
  }

  .ecosystem__inner {
    max-width: 1260px;
    margin-left: auto;
    margin-right: auto;
  }

  .ecosystem__partnership {
    display: flex;
    flex-direction: column;
    gap: 24px;
    align-items: center;
    text-align: center;
  }

  .ecosystem__pattern img {
    display: block;
    max-width: 100%;
    height: auto;
  }

  .ecosystem__label {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 12px;
    line-height: 170%;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    margin: 0;
    background: linear-gradient(90deg, #9810FA 0%, #6564DB 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .ecosystem__headline {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 0;
  }

  .ecosystem__separator {
    height: 1px;
    background: #e4e7ec;
  }

  .ecosystem__separator--purple {
    background: linear-gradient(90deg, #d6e1f1 0%, #6564DB 50%, #d6e1f1 100%);
  }

  .ecosystem__category {
    background: #fff;
  }

  .ecosystem__category-separator {
    height: 1px;
    background: linear-gradient(90deg, #d6e1f1 0%, #ffffff 100%);
    margin-left:90px
  }

  .ecosystem__category-inner {
    max-width: 1260px;
    margin-left: auto;
    margin-right: auto;
    padding: 80px 20px;
  }

  .ecosystem__category-row {
    display: flex;
    flex-wrap: wrap;
    gap: 40px 80px;
    align-items: flex-start;
  }

  .ecosystem__category-text {
    flex: 0 0 430px;
    max-width: 100%;
  }

  .ecosystem__category-title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 20px;
    line-height: 170%;
    letter-spacing: 0.08em;
    color: #0F0F0F;
    margin: 0 0 16px 0;
    max-width: 300px;
  }

  .ecosystem__category-desc {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #5E6979;
    margin: 0;
    max-width: 245px;
  }

  .ecosystem__logos {
    flex: 1 1 0;
    min-width: 280px;
    margin-left: auto;
    display: grid;
    width: 100%;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 18px;
    align-items: center;
  }

  .ecosystem__logo-item {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 180px;
    height: 100px;
    padding: 16px;
    background: transparent;
    border-radius: 8px;
    text-decoration: none;
    transition: box-shadow 0.2s ease, opacity 0.2s ease;
    justify-self: start;
  }

  .ecosystem__logo-item:hover {
    box-shadow: 0 4px 12px rgba(101, 100, 219, 0.12);
    opacity: 0.9;
  }

  .ecosystem__logo-item img {
    max-width: 100%;
    max-height: 100%;
    width: auto;
    height: auto;
    object-fit: contain;
  }

  @media (max-width: 991px) {
    .ecosystem__section {
      padding-top: 100px;
    }

    .ecosystem__inner {
      padding: 0 24px 80px 24px;
    }

    .ecosystem__headline {
      font-size: 28px;
    }

    .ecosystem__category-inner {
      padding: 60px 24px;
    }

    .ecosystem__category-row {
      flex-direction: column;
      gap: 32px;
    }

    .ecosystem__category-text {
      flex: 0 0 auto;
    }

    .ecosystem__logo-item {
      width: 160px;
      height: 88px;
    }
  }

  @media (max-width: 767px) {
    .ecosystem__category-title,
    .ecosystem__category-desc {
      max-width: none;
    }
  }

  @media (max-width: 575px) {
    .ecosystem__section {
      padding: 60px 0 0 0;
    }

    .ecosystem__inner {
      padding: 0 1.5rem 32px 1.5rem;
    }

    .ecosystem__partnership {
      margin-bottom: 32px;
    }

    .ecosystem__headline {
      font-size: 32px;
    }

    .ecosystem__category-inner {
      padding: 48px 1.5rem;
    }

    .ecosystem__logos {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .ecosystem__logo-item {
      width: 100%;
      height: 76px;
      padding: 12px;
    }
  }
</style>
