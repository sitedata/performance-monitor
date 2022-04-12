<?php

namespace PerformanceMonitor;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/OllieJones
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 * @author     Ollie Jones <oj@plumislandmedia.net>
 */
class i18n extends WordPressHooks {


  public function __construct() {
    parent::__construct();
  }

  /**
   * Load the plugin text domain for translation.
   *
   */
  public function action__plugins_loaded() {

    load_plugin_textdomain(
      'performance-monitor',
      false,
      dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
    );

  }

}

new i18n();
