<?php
/**
 * Work Continents Navigation Block
 * Each tab's content is a single markdown string.
 */

$continents = [
    [
        'icon' => 'images/maps/latin-america-caribbean.png',
        'label' => 'Latin America & Caribbean',
        'slug' => 'latin-america-caribbean',
        'active' => true,
    ],
    [
        'icon' => 'images/maps/africa-map.svg',
        'label' => 'Africa',
        'slug' => 'africa',
        'active' => false,
    ],
    [
        'icon' => 'images/maps/asia-map.svg',
        'label' => 'Asia',
        'slug' => 'asia',
        'active' => false,
    ],
];

$workContinentsHandler = new CustomPost('work_impact', null);

$workContinentsRaw = $workContinentsHandler->getListOfPosts(["content"]);


$continent_content = [];

foreach ($continents as $continent) {
    $slug  = $continent['slug'];
    $label = $continent['label'];
    $content = '';

    // Find corresponding post in $workContinentsRaw using slug
    foreach ($workContinentsRaw as $post) {
        if (
            (isset($post['slug']) && $post['slug'] === $slug) ||
            (isset($post['post_name']) && $post['post_name'] === $slug) // fallback for WP field
        ) {
            // Use 'content' as the field as per usual CustomPost structure
            $content = $post['content'] ?? '';
            break;
        }
    }

    $continent_content[$slug] = $content;
}

?>

<section class="redlof-block work-continents py-4">
    <div class="work-impact work-continents__impact">
        <div class="work-impact__inner">
            <div class="work-impact__content">
                <div class="work-impact__icon">
                    <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-5.svg'); ?>" alt="" width="32" height="32" loading="lazy" />
                </div>
                <h2 class="work-impact__title">
                    Where we've made <br />
                    an Impact
                </h2>
                <p class="work-impact__body">
                    Showcasing the people we help, the places we've worked,
                    and the services that we provide.
                </p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="continents-nav d-flex justify-content-center align-items-stretch">
            <?php foreach ($continents as $continent) : ?>
                <a href="#<?php echo esc_attr($continent['slug']); ?>" class="continent-item d-flex align-items-center <?php echo $continent['active'] ? 'active' : ''; ?>">
                    <span class="continent-icon">
                        <img src="<?php echo Helper::getImagePath($continent['icon']); ?>" alt="<?php echo esc_attr($continent['label']); ?>">
                    </span>
                    <span class="continent-label"><?php echo esc_html($continent['label']); ?></span>
                    <span class="continent-arrow">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 18l6-6-6-6"/>
                        </svg>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="work-continents__content">
        <div class="container">
            <?php foreach ($continents as $continent) :
                $slug    = $continent['slug'];
                $markdown = $continent_content[$slug] ?? '';
                $html    = $markdown;
                if ($html === '') {
                    continue;
                }
            ?>
                <div class="continent-panel <?php echo $continent['active'] ? 'is-active' : ''; ?>" data-continent="<?php echo esc_attr($slug); ?>">
                    <div class="continent-panel__inner">
                        <div class="continent-panel__scroll">
                            <div class="continent-panel__content">
                                <?php echo wp_kses_post($html); ?>
                            </div>
                        </div>
                        <div class="continent-panel__read-footer">
                            <button type="button" class="continent-panel__read-more" aria-expanded="false" hidden>
                                ...Read more
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.work-continents {
    background-color: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 24px 24px;
}

/* Work impact (merged from work-impact block) */
.work-continents__impact.work-impact {
    background-color: #ffffff;
    padding: 5.5rem 1.5rem 3.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.work-impact__inner {
    max-width: 1016px;
    width: 100%;
    margin: 0 auto;
}

.work-impact__content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1.5rem;
    text-align: center;
}

.work-impact__icon {
    width: 2rem;
    height: 2rem;
    flex-shrink: 0;
}

.work-impact__icon img {
    width: 100%;
    height: 100%;
    display: block;
}

.work-impact__title {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 300;
    font-size: 32px;
    line-height: 140%;
    letter-spacing: 0;
    color: #101828;
    margin: 0;
}

.work-impact__body {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    text-align: center;
    color: #5E6979;
    margin: 0;
    max-width: 416px;
}

.continents-nav {
    gap: 0;
}

.continent-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    text-decoration: none;
    color: #6c757d;
    position: relative;
    transition: all 0.2s ease;
}

.continent-item::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 32px;
    right: 32px;
    height: 2px;
    background: transparent;
    transition: background 0.2s ease;
}

.continent-item:hover {
    color: #4f46e5;
}

.continent-item.active {
    color: #4f46e5;
}

.continent-item.active::after {
    background: #4f46e5;
}

.continent-icon {
    width: 88px;
    height: 104px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.continent-icon img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    opacity: 0.6;
}

.continent-item.active .continent-icon img,
.continent-item:hover .continent-icon img {
    opacity: 1;
}

.continent-label {
    font-size: 15px;
    font-weight: 500;
    white-space: nowrap;
}

.continent-arrow {
    display: flex;
    align-items: center;
    color: #9ca3af;
}

.continent-item:hover .continent-arrow,
.continent-item.active .continent-arrow {
    color: #4f46e5;
}

.work-continents__content {
    padding: 3rem 0 4rem;
}

.continent-panel {
    display: none;
}

.continent-panel.is-active {
    display: block;
}

.continent-panel__scroll {
    max-height: 400px;
    overflow: hidden;
}

.continent-panel.is-expanded .continent-panel__scroll,
.continent-panel.continent-panel--short .continent-panel__scroll {
    max-height: none;
    overflow: visible;
}

.continent-panel__inner {
    max-width: 840px;
    margin: 0 auto;
    width: 100%;
}

.continent-panel__read-footer {
    display: block;
    width: 100%;
    clear: both;
    text-align: right;
}

.continent-panel__read-footer:has(.continent-panel__read-more:not([hidden])) {
    margin-top: 0.75rem;
}

.continent-panel__read-more {
    display: inline;
    margin: 0;
    padding: 0;
    border: none;
    background: none;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: inherit;
    font-weight: 400;
    line-height: inherit;
    letter-spacing: 0.02em;
    color: #4f46e5;
    cursor: pointer;
    text-align: right;
    vertical-align: baseline;
    white-space: nowrap;
    text-decoration: none;
}

.continent-panel__read-more:hover,
.continent-panel__read-more:focus {
    text-decoration: none;
}

.continent-panel__content {
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 170%;
    letter-spacing: 0.02em;
    color: #4a5563;
}

.continent-panel__content p {
    margin: 0 0 1rem;
}

.continent-panel__content p:last-child {
    margin-bottom: 0;
}

.continent-panel__content img {
    display: block;
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    border-radius: 10px;
}

.continent-panel__content p img {
    display: inline-block;
    width: auto;
    height: 1.1em;
    max-width: none;
    margin: 0 0.35rem;
    border-radius: 0;
    vertical-align: -0.15em;
    object-fit: contain;
}

.continent-panel__content h1,
.continent-panel__content h2,
.continent-panel__content h3 {
    font-weight: 500;
    color: #101828;
    margin: 1.5rem 0 0.75rem;
}

.continent-panel__content h1:first-child,
.continent-panel__content h2:first-child,
.continent-panel__content h3:first-child {
    margin-top: 0;
}

/* Responsive */
@media (max-width: 991px) {
    .work-continents__impact.work-impact {
        padding: 4rem 1.5rem 3rem;
    }

    .work-impact__title {
        font-size: 24px;
    }

    .work-impact__body {
        font-size: 0.9375rem;
    }

    .continents-nav {
        flex-wrap: wrap;
        justify-content: center;
    }

    .continent-item {
        padding: 12px 20px;
    }
}

@media (max-width: 767px) {
    .work-continents__impact.work-impact {
        padding: 0;
    }
    .work-continents .container {
        padding-left: 0;
        padding-right: 0;
        max-width: 100%;
        overflow-x: hidden;
    }

    .continents-nav {
        flex-direction: column;
        align-items: stretch;
        width: 100%;
        max-width: 100%;
    }
    
    .continent-item {
        justify-content: flex-start;
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        min-width: 0;
        max-width: 100%;
        box-sizing: border-box;
    }
    
    .continent-icon {
        width: 56px;
        height: 56px;
        flex-shrink: 0;
    }
    
    .continent-label {
        white-space: normal;
        min-width: 0;
        flex: 1;
    }
    
    .continent-item::after {
        display: none;
    }
    
    .continent-item.active {
        background: #f8f9ff;
        border-left: 3px solid #4f46e5;
    }
}
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var section = document.querySelector('.work-continents');
    if (!section) {
      return;
    }

    var navItems = section.querySelectorAll('.continent-item');
    var panels = section.querySelectorAll('.continent-panel');
    var CONTENT_MAX_PX = 400;

    if (!navItems.length || !panels.length) {
      return;
    }

    function isFullyInsideScroll(el, scrollEl) {
      if (!el || !scrollEl) {
        return false;
      }
      var sr = scrollEl.getBoundingClientRect();
      var er = el.getBoundingClientRect();
      return er.top >= sr.top - 1 && er.bottom <= sr.bottom + 1;
    }

    function moveReadMoreToFooter(btn, footer) {
      if (!footer || !btn) {
        return;
      }
      if (btn.parentNode !== footer) {
        footer.appendChild(btn);
      }
    }

    function moveReadMoreInlineAfterLastParagraph(btn, inner, footer) {
      if (!inner || !btn || !footer) {
        return;
      }
      var lastP = inner.querySelector('p:last-of-type');
      if (!lastP) {
        moveReadMoreToFooter(btn, footer);
        return;
      }
      if (btn.parentNode === lastP) {
        return;
      }
      moveReadMoreToFooter(btn, footer);
      lastP.appendChild(document.createTextNode(' '));
      lastP.appendChild(btn);
    }

    function refreshPanelClamp(panel) {
        var inner = panel.querySelector('.continent-panel__content');
        var scrollEl = panel.querySelector('.continent-panel__scroll');
        var footer = panel.querySelector('.continent-panel__read-footer');
        var btn = panel.querySelector('.continent-panel__read-more');
        if (!inner || !scrollEl || !footer || !btn) {
            return;
        }

        function run() {
            if (!panel.classList.contains('is-active')) {
                return;
            }

            if (panel.classList.contains('is-expanded')) {
                panel.classList.remove('continent-panel--short');
                btn.hidden = false;
                btn.textContent = 'Show less';
                btn.setAttribute('aria-expanded', 'true');
                moveReadMoreInlineAfterLastParagraph(btn, inner, footer);
                return;
            }

            var fullHeight = inner.scrollHeight;
            if (fullHeight <= CONTENT_MAX_PX) {
                panel.classList.add('continent-panel--short');
                btn.hidden = true;
                btn.textContent = '...Read more';
                btn.setAttribute('aria-expanded', 'false');
                moveReadMoreToFooter(btn, footer);
                return;
            }

            panel.classList.remove('continent-panel--short');
            btn.hidden = false;
            btn.textContent = '...Read more';
            btn.setAttribute('aria-expanded', 'false');

            var lastP = inner.querySelector('p:last-of-type');
            if (lastP && isFullyInsideScroll(lastP, scrollEl)) {
                moveReadMoreInlineAfterLastParagraph(btn, inner, footer);
            } else {
                moveReadMoreToFooter(btn, footer);
            }
        }

        window.requestAnimationFrame(run);
    }

    panels.forEach(function (panel) {
        var btn = panel.querySelector('.continent-panel__read-more');
        var inner = panel.querySelector('.continent-panel__content');
        if (!btn || !inner) {
            return;
        }

        btn.addEventListener('click', function () {
            panel.classList.toggle('is-expanded');
            refreshPanelClamp(panel);
        });

        inner.querySelectorAll('img').forEach(function (img) {
            if (!img.complete) {
                img.addEventListener('load', function () {
                    refreshPanelClamp(panel);
                }, { once: true });
            }
        });
    });

    navItems.forEach(function (item) {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            var href = item.getAttribute('href') || '';
            var slug = href.replace('#', '');

            navItems.forEach(function (el) {
                el.classList.remove('active');
            });
            item.classList.add('active');

            panels.forEach(function (panel) {
                if (panel.getAttribute('data-continent') === slug) {
                    panel.classList.add('is-active');
                } else {
                    panel.classList.remove('is-active');
                }
            });

            var activePanel = section.querySelector('.continent-panel.is-active');
            if (activePanel) {
                refreshPanelClamp(activePanel);
            }
        });
    });

    window.addEventListener('resize', function () {
        var activePanel = section.querySelector('.continent-panel.is-active');
        if (activePanel) {
            refreshPanelClamp(activePanel);
        }
    });

    var initial = section.querySelector('.continent-panel.is-active');
    if (initial) {
        refreshPanelClamp(initial);
    }
  });
</script>
