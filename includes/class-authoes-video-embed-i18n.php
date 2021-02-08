<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://faisal.com.bd
 * @since      1.0.0
 *
 * @package    Authoes_Video_Embed
 * @subpackage Authoes_Video_Embed/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Authoes_Video_Embed
 * @subpackage Authoes_Video_Embed/includes
 * @author     Faisal Ahmed <fftfaisal@gmail.com>
 */
class Authoes_Video_Embed_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'authoes-video-embed',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
