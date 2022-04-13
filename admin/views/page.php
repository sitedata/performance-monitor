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
    <!--suppress HtmlUnknownTarget -->
    <form id="performance-monitor-form" method="post" action="options.php">
      <?php
      settings_fields( $this->options_name );
      do_settings_sections( $this->plugin_name );
      submit_button( );
      ?>
    </form>
</div>


