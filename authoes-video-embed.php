<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://faisal.com.bd
 * @since             1.0.0
 * @package           Authoes_Video_Embed
 *
 * @wordpress-plugin
 * Plugin Name:       Autheos Video Embed
 * Plugin URI:        https://github.com/fftfaisal/authoes-video-embed/
 * Description:       Embed authoes videos into wordpress,woocommerce site.Also Generate thumbnail from autheos from Video,EAN ID then save into wordpress and use as post thumbnail automatically.
 * Version:           1.0.0
 * Author:            Faisal Ahmed
 * Author URI:        https://faisal.com.bd
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       authoes-video-embed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}

/*
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'AUTHOES_VIDEO_EMBED_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-authoes-video-embed-activator.php
 */
function activate_authoes_video_embed() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-authoes-video-embed-activator.php';
    Authoes_Video_Embed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-authoes-video-embed-deactivator.php
 */
function deactivate_authoes_video_embed() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-authoes-video-embed-deactivator.php';
    Authoes_Video_Embed_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_authoes_video_embed' );
register_deactivation_hook( __FILE__, 'deactivate_authoes_video_embed' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-authoes-video-embed.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_authoes_video_embed() {

    $plugin = new Authoes_Video_Embed();
    $plugin->run();

}

run_authoes_video_embed();
