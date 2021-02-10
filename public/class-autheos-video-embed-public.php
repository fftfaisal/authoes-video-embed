<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://faisal.com.bd
 * @since      1.0.0
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/public
 * @author     Faisal Ahmed <fftfaisal@gmail.com>
 */
class Autheos_Video_Embed_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Autheos_Video_Embed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Autheos_Video_Embed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		
		wp_enqueue_style( $this->plugin_name.'-core', "https://cdn.autheos.com/JrgiuBxHm4/embedcode/latest/embedcode.min.css", false, 'all' );
		if (is_single()) :
			wp_enqueue_style( $this->plugin_name.'-slick', "https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css", false, 'all' );
			wp_enqueue_style( $this->plugin_name.'-slick-theme', "https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.min.css", false, 'all' );
		endif;
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/autheos-video-embed-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Autheos_Video_Embed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Autheos_Video_Embed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'-core', 'https://cdn.autheos.com/JrgiuBxHm4/embedcode/latest/embedcode.min.js', array() , '1.0.1', false );
		if (is_single()) :
			wp_enqueue_script( $this->plugin_name.'-slick', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), '3.0.1', true );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/autheos-video-embed-public.js', array( 'jquery' ), $this->version, true );
		endif;

	}

}
