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

    wp_unschedule_hook( 'performance_monitor_repeating_task' );
    wp_unschedule_hook( 'performance_monitor_task' );

    Deactivator::depopulateIndexMetadata ();
    Deactivator::deleteTransients();
  }

  private static function deleteTransients() {
    global $wpdb;
    $transients = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT option_name FROM $wpdb->options WHERE option_name LIKE CONCAT(%s, '%%')",
        $wpdb->esc_like( '_transient_' . PERFORMANCE_MONITOR_PREFIX ) )
    );
    foreach ( $transients as $transient ) {
      $name = str_replace( '_transient_', '', $transient->option_name );
      delete_transient( $name );
    }
  }

  private static function depopulateIndexMetadata () {
    $depop = new DepopulateMetaIndexes();
    $depop->init();
    while (!$depop->doChunk()) {
      /* empty */
    }
  }

}
