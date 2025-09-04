<?php

/**
 * Plugin Name: Content expiry
 * Description: Expira el contenido después de un tiempo determinado.
 * Version: 0.0.2
 * Author: Daniel Lúcia
 * Author URI: http://www.daniellucia.es
 * textdomain: dl-woo-content-expiry
 */

defined('ABSPATH') || exit;

require_once __DIR__ . '/src/Plugin.php';

define('DL_CONTENT_EXPIRY_VERSION', '0.0.2');
define('DL_CONTENT_EXPIRY_FILE', __FILE__);

add_action('plugins_loaded', function () {

    load_plugin_textdomain('dl-woo-content-expiry', false, dirname(plugin_basename(__FILE__)) . '/languages');

    $plugin = new DLContentExpiryPlugin();
    $plugin->init();
});
