<?php
/**
 * Team global map with member markers and profile card.
 */
?>
<?php
$global_members = [];

$global_default_member_image = Helper::getImagePath('team/default_user.jpeg');
// Fetch dynamic data from Team Members CPT.
$global_team_handler = new CustomPost('team_members', null);
$global_team_posts = $global_team_handler->getListOfPosts([
    'meta_fields',
    'featured_image',
    'thumbnail',
]);

$global_parse_languages = static function ($raw_languages): array {
    if (is_array($raw_languages)) {
        return array_values(array_filter(array_map('trim', $raw_languages), static fn($value) => $value !== ''));
    }
    $raw_languages = is_string($raw_languages) ? trim($raw_languages) : '';
    if ($raw_languages === '') {
        return [];
    }
    $json = json_decode($raw_languages, true);
    if (is_array($json)) {
        return array_values(array_filter(array_map('trim', $json), static fn($value) => is_string($value) && $value !== ''));
    }
    $maybe_serialized = @unserialize($raw_languages);
    if (is_array($maybe_serialized)) {
        return array_values(array_filter(array_map('trim', $maybe_serialized), static fn($value) => is_string($value) && $value !== ''));
    }
    $parts = preg_split('/\s*,\s*/', $raw_languages) ?: [];
    return array_values(array_filter(array_map('trim', $parts), static fn($value) => $value !== ''));
};

foreach ($global_team_posts as $member_post) {

    $name = trim((string)($member_post['title'] ?? ''));
    if ($name === '') {
        continue;
    }

    // Get country from ACF relationship in any return format.
    $country_object = $member_post['meta_fields']['country'] ?? null;
    if (is_string($country_object)) {
        $decoded = json_decode($country_object, true);
        if (is_array($decoded)) {
            $country_object = $decoded;
        } else {
            $maybe_serialized = @unserialize($country_object);
            if (is_array($maybe_serialized)) {
                $country_object = $maybe_serialized;
            }
        }
    }

    $country = '';
    if (is_array($country_object) && isset($country_object[0])) {
        $country_object = $country_object[0];
    }

    if (is_array($country_object)) {
        if (!empty($country_object['post_title'])) {
            $country = trim((string) $country_object['post_title']);
        } elseif (!empty($country_object['title'])) {
            $country = trim((string) $country_object['title']);
        } elseif (!empty($country_object['name'])) {
            $country = trim((string) $country_object['name']);
        } elseif (!empty($country_object['ID']) && is_numeric($country_object['ID'])) {
            $country = trim((string) get_the_title((int) $country_object['ID']));
        } elseif (!empty($country_object['id']) && is_numeric($country_object['id'])) {
            $country = trim((string) get_the_title((int) $country_object['id']));
        }
    } elseif (is_object($country_object)) {
        if (!empty($country_object->post_title)) {
            $country = trim((string) $country_object->post_title);
        } elseif (!empty($country_object->title)) {
            $country = trim((string) $country_object->title);
        } elseif (!empty($country_object->name)) {
            $country = trim((string) $country_object->name);
        } elseif (!empty($country_object->ID) && is_numeric($country_object->ID)) {
            $country = trim((string) get_the_title((int) $country_object->ID));
        }
    } elseif (is_numeric($country_object)) {
        $country = trim((string) get_the_title((int) $country_object));
    } elseif (is_string($country_object)) {
        $country = trim($country_object);
    }


    $role = trim((string)($member_post['meta_fields']['role'] ?? $member_post['meta_fields']['designation'] ?? $member_post['meta_fields']['position'] ?? ''));
    $languages = $global_parse_languages($member_post['meta_fields']['languages'] ?? $member_post['meta_fields']['language'] ?? '');
    $bio = trim((string)($member_post["meta_fields"]['bio'] ??''));
    $lat = $member_post['meta_fields']['latitude'] ?? '';
    $lng = $member_post['meta_fields']['longitude'] ?? '';
    $lat = is_numeric($lat) ? (float)$lat : null;
    $lng = is_numeric($lng) ? (float)$lng : null;

    if ($lat === null || $lng === null) {
        continue;
    }

    $featured_image = CustomPost::getImageUrl($member_post['meta_fields']['photo'] ?? null);
    $thumbnail = $member_post['meta_fields']['photo'] ?? '';
    $image = $featured_image !== '' ? $featured_image : $thumbnail;

    $global_members[] = [
        'name' => $name,
        'role' => $role,
        'country' => $country,
        'languages' => $languages,
        'bio' => $bio,
        'image' => $image,
        'lat' => $lat,
        'lng' => $lng,
    ];

}



foreach ($global_members as &$global_member) {
    $existing_image = trim((string)($global_member['image'] ?? ''));
    $global_member['image'] = $existing_image !== ''
        ? $existing_image
        : $global_default_member_image;
}
unset($global_member);
?>

<section class="redlof-block global-team-map">
  <div class="container">
    <div class="global-team-map__header">
      <div class="global-team-map__pattern">
        <img src="<?php echo Helper::getImagePath('patterns/cube-pattern-2.svg'); ?>" alt="" loading="lazy" />
      </div>
      <h2 class="global-team-map__title">Global team footprint</h2>
      <p class="global-team-map__subtitle">Click a marker to view member details</p>
    </div>
    <div class="global-team-map__canvas-wrap">
      <div id="global-team-map-canvas" class="global-team-map__canvas" aria-label="Global team map"></div>
    </div>
    <div id="global-team-map-overlay" class="global-team-map__overlay-card" hidden></div>
  </div>
</section>

<style>
  .global-team-map {
    background: #fff;
    padding: 80px 0 100px;
  }

  .global-team-map__header {
    margin-bottom: 20px;
  }

  .global-team-map__pattern {
    margin-bottom: 14px;
  }

  .global-team-map__pattern img {
    display: block;
    max-width: 100%;
    height: auto;
  }

  .global-team-map__title {
    margin: 0 0 8px;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 500;
    font-size: 32px;
    line-height: 1.3;
    color: #0f0f0f;
  }

  .global-team-map__subtitle {
    margin: 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-weight: 400;
    font-size: 15px;
    line-height: 1.7;
    color: #5e6979;
  }

  .global-team-map__canvas-wrap {
    position: relative;
    overflow: hidden;
    z-index: 0;
    border-radius: 12px;
  }

  .global-team-map .container {
    position: relative;
    overflow: visible;
  }

  .global-team-map__canvas {
    width: 100%;
    height: 620px;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #d6e1f1;
  }

  .global-team-map__popup-top {
    display: flex;
    gap: 16px;
    align-items: flex-start;
  }

  .global-team-map__popup-header {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 8px;
  }

  .global-team-map__popup-close {
    width: 28px;
    height: 28px;
    border: 0;
    border-radius: 999px;
    background: #f2f4f7;
    color: #0f0f0f;
    font-size: 18px;
    line-height: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    padding: 0;
  }

  .global-team-map__popup-close:hover {
    background: #e4e7ec;
  }

  .global-team-map__member-image {
    width: 128px;
    height: 128px;
    border-radius: 10px;
    object-fit: cover;
    object-position: center center;
    flex-shrink: 0;
  }

  .global-team-map__member-name {
    margin: 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 20px;
    line-height: 1.2;
    font-weight: 700;
    color: #0f0f0f;
  }

  .global-team-map__member-role,
  .global-team-map__member-country {
    margin: 4px 0 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 28px;
    line-height: 1.4;
    color: #0f0f0f;
  }

  .global-team-map__member-languages {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-top: 10px;
  }

  .global-team-map__lang-pill {
    border: 1px solid #101828;
    border-radius: 999px;
    padding: 4px 10px;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 14px;
    line-height: 1.3;
    color: #0f0f0f;
  }

  .global-team-map__member-bio {
    margin: 14px 0 0;
    font-family: "Outfit", system-ui, sans-serif;
    font-size: 16px;
    line-height: 1.55;
    color: #0f0f0f;
  }

  .global-team-map .leaflet-container {
    font-family: "Outfit", system-ui, sans-serif;
  }

  .global-team-map .leaflet-popup-content-wrapper {
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.14);
  }

  .global-team-map .leaflet-popup-pane {
    z-index: 3000 !important;
  }

  .global-team-map .leaflet-popup {
    z-index: 3001 !important;
  }

  .global-team-map .leaflet-popup-content {
    margin: 16px;
    width: 360px !important;
    max-width: calc(100vw - 72px);
    max-height: min(420px, calc(100vh - 140px));
    overflow-y: auto;
    overscroll-behavior: contain;
  }

  .global-team-map__popup-content {
    font-family: "Outfit", system-ui, sans-serif;
    color: #0f0f0f;
  }

  .global-team-map__overlay-card {
    position: absolute;
    z-index: 4000;
    width: 360px;
    max-width: calc(100vw - 72px);
    max-height: min(420px, calc(100vh - 140px));
    overflow-y: auto;
    overscroll-behavior: contain;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.14);
    padding: 16px;
    pointer-events: auto;
  }

  .global-team-map .leaflet-marker-icon {
    filter: drop-shadow(0 3px 5px rgba(0, 0, 0, 0.25));
  }

  .global-team-map .leaflet-div-icon.global-team-map__marker {
    background: transparent;
    border: 0;
  }

  .global-team-map__marker-photo {
    width: 91px;
    height: 91px;
    border-radius: 999px;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 10px rgba(15, 15, 15, 0.2);
    overflow: hidden;
    background: #f2f4f7;
    box-sizing: border-box;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
  }

  @media (max-width: 991px) {
    .global-team-map {
      padding: 60px 0 80px;
    }

    .global-team-map__canvas {
      height: 520px;
    }

    .global-team-map__member-name {
      font-size: 28px;
    }

    .global-team-map__member-role,
    .global-team-map__member-country {
      font-size: 22px;
    }
  }

  @media (max-width: 767px) {
    .global-team-map {
      padding: 48px 0 56px;
    }

    .global-team-map .container {
      padding-left: 1.5rem;
      padding-right: 1.5rem;
    }

    .global-team-map__title {
      font-size: 26px;
    }

    .global-team-map__canvas {
      height: 420px;
    }

    .global-team-map .leaflet-popup-content {
      width: 280px !important;
      margin: 12px;
    }

    .global-team-map__overlay-card {
      width: 280px;
      max-width: calc(100vw - 32px);
      padding: 12px;
    }

    .global-team-map__member-image {
      width: 92px;
      height: 92px;
    }

    .global-team-map__member-name {
      font-size: 24px;
    }

    .global-team-map__member-role,
    .global-team-map__member-country {
      font-size: 18px;
    }

    .global-team-map__member-bio {
      font-size: 14px;
    }

    .global-team-map__marker-photo {
      width: 44px;
      height: 44px;
      border-width: 2px;
    }
  }
</style>

<script>
  (function () {
    var members = <?php echo wp_json_encode($global_members); ?>;
    var root = document.querySelector('.global-team-map');
    if (!root || !Array.isArray(members) || !members.length) {
      return;
    }

    function loadLeaflet(cb) {
      if (window.L) {
        cb();
        return;
      }

      var cssId = 'leaflet-css-cdpi';
      if (!document.getElementById(cssId)) {
        var css = document.createElement('link');
        css.id = cssId;
        css.rel = 'stylesheet';
        css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
        document.head.appendChild(css);
      }

      var scriptId = 'leaflet-js-cdpi';
      var existing = document.getElementById(scriptId);
      if (existing) {
        existing.addEventListener('load', cb, { once: true });
        return;
      }

      var script = document.createElement('script');
      script.id = scriptId;
      script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
      script.async = true;
      script.onload = cb;
      document.body.appendChild(script);
    }

    function init() {
      var canvas = document.getElementById('global-team-map-canvas');
      if (!canvas || !window.L) {
        return;
      }
      var baseZoom = 2.45;

      var map = L.map(canvas, {
        zoomControl: false,
        attributionControl: false,
        scrollWheelZoom: true,
        dragging: true,
        touchZoom: true,
        doubleClickZoom: true,
        boxZoom: false,
        keyboard: false,
        tap: true,
        zoomSnap: 0.05,
        zoomDelta: 0.05,
        minZoom: baseZoom,
        worldCopyJump: false,
        maxBoundsViscosity: 1
      }).setView([16, 10], baseZoom);

      var worldBounds = L.latLngBounds(L.latLng(-85, -180), L.latLng(85, 180));
      map.setMaxBounds(worldBounds);

      L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}{r}.png', {
        subdomains: 'abcd',
        maxZoom: 6,
        minZoom: 2,
        noWrap: true,
        bounds: worldBounds
      }).addTo(map);

      function createMarkerIcon(member) {
        var safeName = member && member.name ? member.name.replace(/"/g, '&quot;') : '';
        var safeImage = member && member.image ? String(member.image).replace(/"/g, '&quot;') : '';
        var markerSize = window.matchMedia('(max-width: 767px)').matches ? 44 : 91;
        return L.divIcon({
          className: 'global-team-map__marker',
          html: '<div class="global-team-map__marker-photo" role="img" aria-label="' + safeName + '" style="background-image:url(\'' + safeImage + '\')"></div>',
          iconSize: [markerSize, markerSize],
          iconAnchor: [markerSize / 2, markerSize / 2],
          popupAnchor: [0, -10]
        });
      }

      function buildMemberPopup(member) {
        var langs = (member.languages || []).map(function (lang) {
          return '<span class="global-team-map__lang-pill">' + lang + '</span>';
        }).join('');
        return '' +
          '<div class="global-team-map__popup-content">' +
            '<div class="global-team-map__popup-header">' +
              '<button type="button" class="global-team-map__popup-close" aria-label="Close member details">&times;</button>' +
            '</div>' +
            '<div class="global-team-map__popup-top">' +
              '<img class="global-team-map__member-image" src="' + (member.image || '') + '" alt="' + (member.name || '') + '" loading="lazy" />' +
              '<div class="global-team-map__member-meta">' +
                '<h3 class="global-team-map__member-name">' + (member.name || '') + '</h3>' +
                '<div class="global-team-map__member-languages">' + langs + '</div>' +
              '</div>' +
            '</div>' +
            '<p class="global-team-map__member-bio">' + (member.bio || '') + '</p>' +
          '</div>';
      }

      var containerEl = root.querySelector('.container');
      var overlayEl = document.getElementById('global-team-map-overlay');
      var activeMarker = null;
      var overlayGap = 12;

      function closeOverlay() {
        if (!overlayEl) {
          return;
        }
        overlayEl.hidden = true;
        overlayEl.innerHTML = '';
        activeMarker = null;
      }

      function positionOverlay(marker) {
        if (!overlayEl || !containerEl || !marker) {
          return;
        }
        var markerPoint = map.latLngToContainerPoint(marker.getLatLng());
        var canvasRect = canvas.getBoundingClientRect();
        var containerRect = containerEl.getBoundingClientRect();
        var overlayWidth = overlayEl.offsetWidth || 360;
        var overlayHeight = overlayEl.offsetHeight || 260;
        var left = canvasRect.left - containerRect.left + markerPoint.x - (overlayWidth / 2);
        var top = canvasRect.top - containerRect.top + markerPoint.y - overlayHeight - overlayGap;

        var minLeft = 8;
        var maxLeft = Math.max(minLeft, containerRect.width - overlayWidth - 8);
        left = Math.max(minLeft, Math.min(left, maxLeft));

        var minTop = 8;
        if (top < minTop) {
          top = canvasRect.top - containerRect.top + markerPoint.y + overlayGap;
        }
        overlayEl.style.left = left + 'px';
        overlayEl.style.top = Math.max(minTop, top) + 'px';
      }

      function openOverlay(member, marker) {
        if (!overlayEl) {
          return;
        }
        activeMarker = marker;
        overlayEl.innerHTML = buildMemberPopup(member);
        overlayEl.hidden = false;
        positionOverlay(marker);
      }

      // Highlight member countries with actual country polygons.
      var memberCountries = {};
      members.forEach(function (member) {
        if (member && member.country) {
          memberCountries[String(member.country).trim().toLowerCase()] = true;
        }
      });

      function addCountryHighlights() {
        var geoJsonUrl = 'https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json';
        fetch(geoJsonUrl)
          .then(function (res) { return res.json(); })
          .then(function (worldGeoJson) {
            L.geoJSON(worldGeoJson, {
              interactive: false,
              style: function (feature) {
                var name = feature && feature.properties && feature.properties.name
                  ? String(feature.properties.name).trim().toLowerCase()
                  : '';
                if (memberCountries[name]) {
                  return {
                    fillColor: '#6E4AFD',
                    fillOpacity: 1,
                    color: '#6E4AFD',
                    weight: 0,
                    opacity: 1
                  };
                }
                return {
                  fillOpacity: 0,
                  opacity: 0,
                  weight: 0
                };
              }
            }).addTo(map);
          })
          .catch(function () {
            // Fallback: visible circular highlights if GeoJSON cannot load.
            members.forEach(function (member) {
              if (typeof member.lat !== 'number' || typeof member.lng !== 'number') {
                return;
              }
              L.circle([member.lat, member.lng], {
                radius: 700000,
                stroke: false,
                fillColor: '#6E4AFD',
                fillOpacity: 1,
                interactive: false
              }).addTo(map);
            });
          });
      }

      addCountryHighlights();

      // Spread members that share identical coordinates (common for same-country entries).
      var coordGroups = {};
      members.forEach(function (member, idx) {
        if (typeof member.lat !== 'number' || typeof member.lng !== 'number') {
          return;
        }
        var key = member.lat.toFixed(4) + ',' + member.lng.toFixed(4);
        if (!coordGroups[key]) {
          coordGroups[key] = [];
        }
        coordGroups[key].push(idx);
      });

      Object.keys(coordGroups).forEach(function (key) {
        var group = coordGroups[key];
        if (!group || group.length < 2) {
          return;
        }
        var radiusDeg = 1.6; // ~150-180km visual spread, still within country context
        var step = (2 * Math.PI) / group.length;
        group.forEach(function (memberIndex, i) {
          var angle = i * step;
          var originalLat = members[memberIndex].lat;
          var latOffset = Math.sin(angle) * radiusDeg;
          var lngOffset = Math.cos(angle) * (radiusDeg / Math.max(0.2, Math.cos(originalLat * Math.PI / 180)));
          members[memberIndex].lat = originalLat + latOffset;
          members[memberIndex].lng = members[memberIndex].lng + lngOffset;
        });
      });

      members.forEach(function (member) {
        if (typeof member.lat !== 'number' || typeof member.lng !== 'number') {
          return;
        }
        var marker = L.marker([member.lat, member.lng], { icon: createMarkerIcon(member) }).addTo(map);
        marker.on('click', function () {
          openOverlay(member, marker);
        });
      });

      map.on('click', function () {
        closeOverlay();
      });

      map.on('move zoom resize', function () {
        if (activeMarker) {
          positionOverlay(activeMarker);
        }
      });

      if (overlayEl && window.L && L.DomEvent) {
        L.DomEvent.disableClickPropagation(overlayEl);
        L.DomEvent.disableScrollPropagation(overlayEl);
      }

      if (overlayEl) {
        overlayEl.addEventListener('click', function (event) {
          var target = event.target;
          if (target && target.classList && target.classList.contains('global-team-map__popup-close')) {
            closeOverlay();
          }
        });
      }
    }

    loadLeaflet(init);
  })();
</script>
