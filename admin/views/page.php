<?php

/**
 * Provide an admin area view for the plugin
 *
 * This file is used to present the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/OllieJones
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/admin/views
 */

use PerformanceMonitor\Indexer;

settings_errors( $this->options_name );
?>

<div class="wrap index-users">
    <h2 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h2>
    <p>
        <span><?php esc_html_e( 'Approximate number of users on this entire site', 'performance-monitor' ) ?>: </span>
        <span><?php echo esc_html(number_format_i18n( Indexer::getNetworkUserCount(), 0 )) ?></span>
    </p>
    <!--suppress HtmlUnknownTarget -->
    <form id="index-users-form" method="post" action="options.php">
      <?php
      settings_fields( $this->options_name );
      do_settings_sections( $this->plugin_name );
      submit_button( );
      ?>
    </form>
</div>


