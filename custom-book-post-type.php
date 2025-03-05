<?php
/**
 * Plugin Name: Custom Book Post Type
 * Description: Custom Post Type for Books with custom meta fields and taxonomies.
 * Version: 1.0
 * Author: Saikat
 */

if (!defined('ABSPATH')) {
    exit; // Direct access block
}

// CPT, Taxonomy & Meta Fields include
require_once plugin_dir_path(__FILE__) . 'includes/cpt.php';
require_once plugin_dir_path(__FILE__) . 'includes/meta-fields.php';
require_once plugin_dir_path(__FILE__) . 'includes/taxonomies.php';
require_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';

// Plugin Activation Hook
function cpt_books_activate() {
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'cpt_books_activate');

// Plugin Deactivation Hook
function cpt_books_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cpt_books_deactivate');
