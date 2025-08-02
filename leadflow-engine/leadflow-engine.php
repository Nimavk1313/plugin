<?php
/**
 * Plugin Name:       LeadFlow Engine
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       A comprehensive WordPress plugin designed to empower site owners to create, manage, and analyze high-converting landing pages, while controlling user registration through an admin approval system.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Jules
 * Author URI:        https://example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       leadflow-engine
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-leadflow-engine.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_leadflow_engine() {

    $plugin = new Leadflow_Engine();
    $plugin->run();

}
run_leadflow_engine();
