<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://faisal.com.bd
 * @since      1.0.0
 *
 * @package    Autheos_Video_Embed
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
$autheos_post_query = array('post_type' => 'post', 'posts_per_page' => -1);
$plugin_data = get_option('autheos_setting_options');

$autheos_posts = get_posts($autheos_post_query);
foreach ($autheos_posts as $post) {
	$get_fake_thumbnail_id = get_post_meta($post->ID,'_thumbnail_id',true);
	if(isset($plugin_data['delete_data']) && $plugin_data['delete_data'] == 'yes') {
		if($get_fake_thumbnail_id == -1) {
			update_post_meta($post->ID,'_thumbnail_id','');
		}
		delete_post_meta($post->ID, '_authoes_thumbnail_id');
		delete_option('autheos_setting_options');
	}
}