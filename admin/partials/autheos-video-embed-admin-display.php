<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://faisal.com.bd
 * @since      1.0.0
 *
 * @package    Autheos_Video_Embed
 * @subpackage Autheos_Video_Embed/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <form action="options.php" method="post">
        <?php
        // output security fields for the registered setting "wporg"
        settings_fields( 'autheos-options' );
        // output setting sections and their fields
        // (sections are registered for "wporg", each field is registered to a specific section)
        do_settings_sections( 'autheos-options' );
        // output save settings button
        submit_button( 'Save Settings' );
        ?>
    </form>
</div>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
