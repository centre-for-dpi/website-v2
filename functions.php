<?php
/*
Author: Think201
URL: https://think201.com
*/


require_once( 'src/redlof/index.php' );


function theme_add_styles_and_scripts() {
  $manifest = json_decode(
    file_get_contents(__DIR__ . "/public/webpack.manifest.json"),
    true
  );

	wp_enqueue_style(
    'style',
    get_template_directory_uri() . $manifest['main.css'],
    array(),
    null,
    'all'
  );

	wp_enqueue_script(
    'script',
    get_template_directory_uri() . $manifest['main.js'],
    array(),
    null,
    true
  );

  if (is_home()) {
    wp_dequeue_style('wp-block-library');
    wp_deregister_script('wp-embed');
  }
}

add_action('wp_enqueue_scripts', 'theme_add_styles_and_scripts');





function restrict_search_to_title($query) {
  if ($query->is_search() && !is_admin() && $query->get('post_type') === 'learn') {
      add_filter('posts_where', 'search_only_titles', 10, 2);
  }
}
add_action('pre_get_posts', 'restrict_search_to_title');

function search_only_titles($where, $query) {
  global $wpdb;

  if ($query->is_search() && !is_admin() && $query->get('post_type') === 'learn') {
      $search_query = $query->get('s');
      if (!empty($search_query)) {
          $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . $wpdb->esc_like($search_query) . '%');
      }
  }

  return $where;
}



// Add custom rewrite rules
function generate_rewrite_rules($wp_rewrite)
{
  $new_rules = array(
    'blog/([^/]+)/?$' => 'index.php?post_type=post&name=$matches[1]',
    'blog/([^/]+)/page/?([0-9]{1,})/?$' => 'index.php?post_type=post&name=$matches[1]&paged=$matches[2]',
    'blog/([^/]+)/comment-page-([0-9]{1,})/?$' => 'index.php?post_type=post&name=$matches[1]&cpage=$matches[2]',
    'blog/([^/]+)(/[0-9]+)?/?$' => 'index.php?post_type=post&name=$matches[1]&page=$matches[2]',
  );
  $wp_rewrite->rules = $new_rules + $wp_rewrite->rules;
}
add_action('generate_rewrite_rules', 'generate_rewrite_rules');

// Flush rewrite rules upon activation
function custom_rewrite_flush()
{
  generate_rewrite_rules();
  flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'custom_rewrite_flush');

// Ensure slugs for drafts
function ensure_slug_for_drafts($post_id)
{
  $post = get_post($post_id);
  if ($post->post_status == 'draft' && empty($post->post_name)) {
    // Generate a unique slug
    $slug = wp_unique_post_slug($post->post_title, $post_id, $post->post_status, $post->post_type, $post->post_parent);
    // Update the post with the generated slug
    wp_update_post(array('ID' => $post_id, 'post_name' => $slug));
  }
}
add_action('save_post', 'ensure_slug_for_drafts');

// Update post link to include the 'blog' slug
function update_post_link($post_link, $post)
{
  if (is_object($post) && $post->post_type == 'post') {
    return home_url('/blog/' . $post->post_name . '/');
  }
  return $post_link;
}
add_filter('post_link', 'update_post_link', 10, 2);

// Ensure WordPress flushes permalinks when activating the theme
function flush_learn_rewrite_rules() {
  flush_rewrite_rules();
}

add_action('after_switch_theme', 'flush_learn_rewrite_rules');

/**
 * Download Global Momentum table as XLSX.
 */
function cdpiGlobalMomentumDownloadXlsx(): void {
    try {
        check_ajax_referer('cdpi_global_momentum_xlsx', 'nonce');

        $countryHandler = class_exists('CustomPost') ? new CustomPost('daas_countries', null) : null;
        if (!$countryHandler) {
            throw new UnexpectedValueException('Country handler not available.');
        }

        $countries = $countryHandler->getListOfPosts(['meta_fields']);
        if (!is_array($countries)) {
            $countries = [];
        }

        $headers = [
            'Status',
            'Country',
            'Use case',
            'DPI Block',
            'Legal Artefact',
            'Technical Scope',
            'Service Provider',
            'Hosting choice',
            'DaaS Funding',
            'Program Management',
        ];

        $rows = cdpiGlobalMomentumXlsxRows($countries);
        if (!class_exists('ZipArchive')) {
            cdpiGlobalMomentumDownloadCsv($headers, $rows);
        }

        $bin = CdpiXlsxWriter::build($headers, $rows);

        $filename = 'global-momentum-' . gmdate('Y-m-d') . '.xlsx';
        nocache_headers();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($bin));
        echo $bin;
    } catch (Throwable $e) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Global Momentum XLSX export failed: ' . $e->getMessage());
        }
        try {
            $countryHandler = class_exists('CustomPost') ? new CustomPost('daas_countries', null) : null;
            $countries = $countryHandler ? $countryHandler->getListOfPosts(['meta_fields']) : [];
            if (!is_array($countries)) {
                $countries = [];
            }

            $headers = [
                'Status',
                'Country',
                'Use case',
                'DPI Block',
                'Legal Artefact',
                'Technical Scope',
                'Service Provider',
                'Hosting choice',
                'DaaS Funding',
                'Program Management',
            ];
            $rows = cdpiGlobalMomentumXlsxRows($countries);
            cdpiGlobalMomentumDownloadCsv($headers, $rows);
        } catch (Throwable $fallbackError) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('Global Momentum CSV fallback failed: ' . $fallbackError->getMessage());
            }
            wp_die('Export failed.', 500);
        }
    }

    wp_die();
}

add_action('wp_ajax_cdpi_global_momentum_xlsx', 'cdpiGlobalMomentumDownloadXlsx');
add_action('wp_ajax_nopriv_cdpi_global_momentum_xlsx', 'cdpiGlobalMomentumDownloadXlsx');

/**
 * @param string[] $headers
 * @param array<int, array<int, string>> $rows
 */
function cdpiGlobalMomentumDownloadCsv(array $headers, array $rows): void {
    $filename = 'global-momentum-' . gmdate('Y-m-d') . '.csv';
    nocache_headers();
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'wb');
    if (!$output) {
        throw new RuntimeException('Failed to open CSV output stream.');
    }

    fputcsv($output, $headers);
    foreach ($rows as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}

/**
 * @param array<int, array<string, mixed>> $countries
 * @return array<int, array<int, string>>
 */
function cdpiGlobalMomentumXlsxRows(array $countries): array {
    return array_values(array_map(static function ($country): array {
        $meta = $country['meta_fields'] ?? [];
        $dpiRaw = $meta['dpi_blocks'] ?? [];
        $dpiBlocks = is_array($dpiRaw) ? $dpiRaw : maybe_unserialize($dpiRaw);
        if (!is_array($dpiBlocks)) {
            $dpiBlocks = $dpiRaw !== '' ? [$dpiRaw] : [];
        }
        $dpiBlocks = array_values(array_filter(array_map(static fn($v) => trim((string) $v), $dpiBlocks)));

        $statusToCell = static function ($status): string {
            $value = strtolower(trim((string) $status));
            if ($value === 'using' || $value === 'selected' || $value === 'with_fund') {
                return 'Yes';
            }
            if ($value === 'in_progress') {
                return 'In progress';
            }
            return 'No';
        };

        $entityToCell = static function ($status, $name = '', $selectedStatus = 'selected', $selectedFallback = 'Yes'): string {
            $statusValue = strtolower(trim((string) $status));
            if ($statusValue === $selectedStatus) {
                $nameValue = trim((string) $name);
                return $nameValue !== '' ? $nameValue : $selectedFallback;
            }
            if ($statusValue === 'in_progress') {
                return 'In progress';
            }
            return 'No';
        };

        return [
            ucfirst(strtolower(trim((string) ($meta['status'] ?? '')))),
            (string) ($country['title'] ?? ''),
            trim((string) ($country['excerpt'] ?? '')),
            implode(', ', $dpiBlocks),
            $statusToCell($meta['legal_artefacts'] ?? ''),
            $statusToCell($meta['technical_scope'] ?? ''),
            $entityToCell($meta['service_provider_status'] ?? '', $meta['service_provider_name'] ?? ''),
            $entityToCell($meta['hosting_choice_status'] ?? '', $meta['hosting_choice_name'] ?? ''),
            $entityToCell($meta['funding_status'] ?? '', $meta['funder_name'] ?? '', 'with_fund', 'Funded'),
            $entityToCell($meta['program_management_status'] ?? '', $meta['program_management_name'] ?? ''),
        ];
    }, $countries));
}

