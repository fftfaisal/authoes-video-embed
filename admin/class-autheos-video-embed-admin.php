<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://faisal.com.bd
 * @since      1.0.0
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/admin
 * @author     Faisal Ahmed <fftfaisal@gmail.com>
 */
class Autheos_Video_Embed_Admin {

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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    /**
     * save options data into a variable for internal use
     *  @since    1.0.1
     * @access   private
     * @var string|array
     */
    private $autheos_options;

    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
		$this->version     = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /*
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

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/autheos-video-embed-admin.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /*
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

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/autheos-video-embed-admin.js', array( 'jquery' ), $this->version, false );
        wp_localize_script($this->plugin_name,'autheos_data', array());
    }
    /**
     * Add plugin setting link into plugins page 
     *
     * @param array $links
     * @return array $links
     */
    public function autheos_plugin_settings_link($links) {
        $setting_link = '<a href="options-general.php?page=autheos-options">Settings</a>'; 
        array_unshift($links, $setting_link); 
        return $links; 
    }

    /**
     * create form field 
     *
     * @return void
     */
    public function autheos_register_setting_fields() {
        add_settings_section( 
            'autheos_setting_options',
            '',
            '',
            //[$this,'autheos_setting_options_callback'],
            'autheos-options'
        );
        add_settings_field(
            'default_thumbnail',
            __('Default Thumbnail', 'autheos-video-embed'),
            [$this,'autheos_default_thumbnail_markup'],
            'autheos-options',
            'autheos_setting_options'
        );

        add_settings_field(
            'delete_data',
            __('Uninstall Plugin', 'autheos-video-embed'),
            [$this,'autheos_plugin_data_markup'],
            'autheos-options',
            'autheos_setting_options'
        );

        register_setting('autheos-options', 'autheos_setting_options');
    }

    /**
     * default_thumbnail field markup for displaying form
     *
     * @return void
     */
    public function autheos_default_thumbnail_markup() {
        printf(
            '<input type="hidden" id="default_thumbnail" name="autheos_setting_options[default_thumbnail]" value="%s"/>',
            $this->autheos_options['default_thumbnail']
        );
        printf(
            '<a id="select_default_thumbnail" class="button button-primary" title="%s" href="#">
			    <span style="margin-top: 3px;" class="dashicons dashicons-format-image"></span>
			    %s
		    </a>',
            esc_attr( 'Change default thumbnail', 'autheos-video-embed' ),
            esc_html( 'Change default thumbnail', 'autheos-video-embed' )
        );
        if( isset($this->autheos_options['default_thumbnail']) && $this->autheos_options['default_thumbnail'] ) {
            $thumbnail_html = $this->autheos_get_preview_image($this->autheos_options['default_thumbnail']);
        } else {
            $thumbnail = plugin_dir_url( dirname( __FILE__ ) ) . 'public/images/no-video-thumbnail.jpg';
            $thumbnail_html = sprintf('<img src="%s" alt="Default thumbnail">',$thumbnail);
        }
        printf('<div id="preview_thumbnail" style="margin-top:20px;">%s</div>', $thumbnail_html);
        printf(
            '<a id="remove_thumbnail" class="button" title="%s" href="#">
                <span style="margin-top: 3px;" class="dashicons dashicons-format-image"></span>
                %s
            </a>',
            esc_attr( 'Remove selected image', 'autheos-video-embed' ),
            esc_html( 'Remove selected image', 'autheos-video-embed' )
        );
    }

    public function autheos_get_preview_image($image_id){
        return wp_get_attachment_image($image_id);
    }

    /*
     * get ajax request from js when thumbail is selected
     *
     * @return void
     */
    public function autheos_ajax_get_thumbnail() {
        if ( ! empty( $_POST['image_id'] ) && absint( $_POST['image_id'] ) ) {
			$img_id = absint( $_POST['image_id'] );
			echo $this->autheos_get_preview_image( $img_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		die(); // ajax call..
    }
    /**
     * delete plugin field markup for displaying form
     *
     * @return void
     */
    public function autheos_plugin_data_markup() {
        printf(
            '<input type="checkbox" name="autheos_setting_options[delete_data]" id="delete_data" value="yes" %s> 
            <label for="delete_data">Delete plugin data and generated thumbnails when plugin is uninstalled</label>',
			isset( $this->autheos_options['delete_data'] )  && $this->autheos_options['delete_data'] == 'yes' ? 'checked' : ''
        );
    }
    /**
     * add link into setting menu page
     * @return void
     */
    public function autheos_settiings_page() {
        add_submenu_page(
            'options-general.php',
            'Autheos Setting',
            'Autheos Setting',
            'manage_options',
            'autheos-options',
            [$this,'autheos_setting_callback']
        );
    }
    /**
     * render setting page form 
     * 
    */
    public function autheos_setting_callback () {
        $this->autheos_options = get_option( 'autheos_setting_options' );
        require_once plugin_dir_path( dirname(__FILE__) ). '/admin/partials/autheos-video-embed-admin-display.php';
    }
}
