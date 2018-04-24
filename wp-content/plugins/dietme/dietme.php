<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              www.dietme.it
 * @since             1.0.0
 * @package           Dietme
 *
 * @wordpress-plugin
 * Plugin Name:       DietMe
 * Plugin URI:        www.dietme.it
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Fabio Sirchia
 * Author URI:        www.dietme.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dietme
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dietme-activator.php
 */
function activate_dietme() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dietme-activator.php';
	Dietme_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dietme-deactivator.php
 */
function deactivate_dietme() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dietme-deactivator.php';
	Dietme_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dietme' );
register_deactivation_hook( __FILE__, 'deactivate_dietme' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dietme.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dietme() {

	$plugin = new Dietme();
	$plugin->run();

}
run_dietme();
