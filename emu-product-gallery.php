<?php
/*
Plugin Name: Emu Product Gallery
Plugin URI: https://example.com/emu-product-gallery
Description: A plugin to display image and YouTube video gallery sliders.
Version: 1.1.7
Author: Emu Plugins
Author URI: https://aganrdagency.com
*/

if (!defined('ABSPATH')) exit;

// Impedir qualquer tentativa de carregar traduções aqui

add_action('init', function () use ($plugin_slug) {
    remove_action('init', 'load_plugin_textdomain', 10);
}, 9999);

add_filter('load_textdomain_mofile', function ($mofile, $domain) use ($plugin_slug) {
    // Impede o carregamento da tradução para o seu plugin, verificando o slug
    if ($domain === $plugin_slug) {
        return false;  // Retorna falso para não carregar o arquivo de tradução
    }
    return $mofile;
}, 10, 2);


require_once plugin_dir_path(__FILE__) . 'includes/metaboxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/option_page.php';

// Sistema de atualização do plugin

$plugin_slug = basename(__DIR__);
if (substr($plugin_slug, -5) === '-main') {
    $plugin_slug = substr($plugin_slug, 0, -5);
}
$self_plugin_dir = basename(__DIR__);

require_once plugin_dir_path(__FILE__) . 'update-handler.php';

new Emu_Updater($plugin_slug, $self_plugin_dir);

// Enqueueing plugin CSS and JS
function emu_product_gallery_enqueue_assets() {
    
    if (is_admin()) {
        return;
    }
    
    // Enqueue Swiper CSS
    wp_enqueue_style('swiper-style', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), null);

    // Enqueue your plugin's CSS
    wp_enqueue_style('emu-product-gallery-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');

    // Enqueue Swiper JS
    wp_enqueue_script('swiper-script', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), null, true);

    // Enqueue your plugin's JS
    wp_enqueue_script('emu-product-gallery-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('swiper-script', 'jquery'), null, true);
    
}

// Including the slider shortcode
function emu_product_gallery_include_slider_shortcode() {
    
    if (is_admin()) {
        return;
    }
    // Includes the file containing the shortcode
    if (file_exists(plugin_dir_path(__FILE__) . 'includes/slider_template.php')) {
        require_once plugin_dir_path(__FILE__) . 'includes/slider_template.php';
    }
}

add_action('wp_enqueue_scripts', 'emu_product_gallery_enqueue_assets');
add_action('init', 'emu_product_gallery_include_slider_shortcode');
