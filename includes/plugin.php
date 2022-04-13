<?php

namespace PerformanceMonitor;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/OllieJones
 *
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @package    PerformanceMonitor
 * @author     Ollie Jones <oj@plumislandmedia.net>
 */
class PerformanceMonitor {

  /**
   * The unique identifier of this plugin.
   *
   * @access   protected
   * @var      string $plugin_name The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @access   protected
   * @var      string $version The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   */
  public function __construct() {
    $this->version     = PERFORMANCE_MONITOR_VERSION;
    $this->plugin_name = PERFORMANCE_MONITOR_NAME;

    /* stuff required for all back-end. cron, REST api operations. */
    if ( wp_doing_cron() || wp_doing_ajax() || is_admin() || wp_is_json_request() || wp_is_xml_request() ) {
      require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/wordpress-hooks.php';
      require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/i18n.php';
      //require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/user-handler.php';
    }

    /* stuff required for admin page but not for cron, REST */
    if ( is_admin() ) {
      require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/admin.php';
    } else {
      /* front-facing: empty for now */
    }

  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name() {
    return $this->plugin_name;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @return    string    The version number of the plugin.
   */
  public function get_version() {
    return $this->version;
  }

  public function run() {

  }

}
