<?php
/*
Plugin Name: Coming Soon Page
Plugin URI: #
Description: Redirects site for non logged in users to a coming soon template. Requires a cf7 form by the name of "Launch" e.g [contact-form-7 title="Launch"]. This can be changed via translations
Version: 1.0.0
Author: robmccormack89
Author URI: #
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: LICENSE
Text Domain: coming-soon
Domain Path: /languages/
*/

// don't run if someone access this file directly
defined('ABSPATH') || exit;

// define some constants
if (!defined('COMING_SOON_PATH')) define('COMING_SOON_PATH', plugin_dir_path( __FILE__ ));
if (!defined('COMING_SOON_URL')) define('COMING_SOON_URL', plugin_dir_url( __FILE__ ));

// require action functions 
// require_once('inc/functions.php');

// require the composer autoloader
if (file_exists($composer_autoload = __DIR__.'/vendor/autoload.php')) require_once $composer_autoload;

// then require the main plugin class. this class extends Timber/Timber which is required via composer
new Rmcc\ComingSoon;