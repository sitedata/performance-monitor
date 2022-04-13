<?php

namespace PerformanceMonitor;

/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/OllieJones
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 * @author     Ollie Jones <oj@plumislandmedia.net>
 */
class Deactivator {

  /**
   * We wipe out stashed indexes on deactivation, not deletion.
   *
   * It doesn't make sense to keep the index metadata when the plugin isn't active
   * because they don't get maintained. Therefore we delete them on deactivation,
   * not plugin deletion.
   *
   */
  public static function deactivate() {

    //wp_unschedule_hook( 'performance_monitor_repeating_task' );
    //wp_unschedule_hook( 'performance_monitor_task' );

  }

}
