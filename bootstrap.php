<?php

/* Include the autoloader so we can dynamically include the rest of the classes. */
require_once trailingslashit(dirname(__FILE__)) . 'inc/autoloader.php';
require_once trailingslashit(dirname(__FILE__)) . 'helpers/generic-helper.php';

add_action('init', 'sblhInit');

if (!defined('SBLH_VERSION')) {
    define('SBLH_VERSION', '0.0.1');
}

if (!defined('SBLH_POST_TYPES')) {
    define('SBLH_POST_TYPES',  ['post']);
}

if (!defined('SBLH_VIEWS')) {
    define('SBLH_VIEWS', __DIR__ . '/views/');
}

/**
 * Starts the plugin by initializing the meta box, its display, and then
 * sets the plugin in motion.
 */
function sblhInit()
{
    return \SBLH\Controllers\AppController::init();
}

register_activation_hook(__FILE__, function () {
    update_option('sblh_version', SBLH_VERSION);
});
