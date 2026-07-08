<?php

function redlof_dynamic_block_shortcode($atts) {

    $atts = shortcode_atts([
        'name' => '',
    ], $atts);

    $block_name = trim($atts['name']);
    
    // Prevent path traversal like ../../etc/passwd
    $block_path = str_replace(['..', './', '\\'], '', $block_name);
    
    // Convert to full file path
    $block_file = get_template_directory() . '/src/blocks/' . $block_path . '.php';

    if (file_exists($block_file)) {
        $block_atts = $atts;

        ob_start();
        include $block_file;
        return ob_get_clean();
    }

    return '<b>Redlof Block not found: ' . esc_html($block_name) . '</b>';
}

add_shortcode('redlof_block', 'redlof_dynamic_block_shortcode');

?>