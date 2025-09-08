
<?php

/**
 * Plugin Name: Content expiry
 * Description: Expires the content after a specified time.
 * Version: 0.0.4
 * Author: Daniel Lúcia
 * Author URI: http://www.daniellucia.es
 * textdomain: dl-content-expiry
 */

use DL\ContentExpiry\Plugin;

defined('ABSPATH') || exit;

require_once __DIR__ . '/vendor/autoload.php';

define('DL_CONTENT_EXPIRY_VERSION', '0.0.4');
define('DL_CONTENT_EXPIRY_FILE', __FILE__);

add_action('plugins_loaded', function () {

    load_plugin_textdomain('dl-content-expiry', false, dirname(plugin_basename(__FILE__)) . '/languages');

    $plugin = new Plugin();
    $plugin->init();
});
