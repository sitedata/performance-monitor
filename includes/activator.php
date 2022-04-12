<?php

namespace PerformanceMonitor;

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/OllieJones
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 * @author     Ollie Jones <oj@plumislandmedia.net>
 */
class Activator {

  /**
   * Short Description. Activate the plugin.
   *
   */
  public static function activate() {

    Activator::startIndexing();
  }


  /**
   * @return void
   */
  private static function startIndexing() {

  }

}
