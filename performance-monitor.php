<?php

/**
 * AAAA Performance Monitor
 * @author   : Oliver Jones
 * @copyright: 2022 Oliver Jones
 * @license  GPL-2.0-or-later
 * @wordpress-plugin0
 * Plugin Name: AAA Performance Monitor
 * Version: 1.0.0
 * Plugin URI:  https://plumislandmedia.org/performance-monitor/
 * Description: Monitor the performance of your WordPress site and database.
 * Requires at least: 5.2
 * Tested up to:      5.9.2
 * Requires PHP:      5.6
 * Author:       OllieJones
 * Author URI:   https://github.com/OllieJones
 * License:      GPL v2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  performance-monitor
 * Domain Path:  /languages
 * Network:      true
 * Tags:         users, performance, monitor
 */

// TODO  change AAA Performance Monitor to back to Performance Monitor

use PerformanceMonitor\Activator;
use PerformanceMonitor\Deactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

const PERFORMANCE_MONITOR_NAME = 'performance-monitor';
define( 'PERFORMANCE_MONITOR_FILENAME', plugin_basename( __FILE__ ) );
const PERFORMANCE_MONITOR_VERSION        = '1.0.0';
const PERFORMANCE_MONITOR_PREFIX         = 'performance-monitor-';
const PERFORMANCE_MONITOR_DB_PREFIX     = 'perfmon';
const PERFORMANCE_MONITOR_SHORT_LIFETIME = HOUR_IN_SECONDS * 4;
const PERFORMANCE_MONITOR_LONG_LIFETIME  = DAY_IN_SECONDS * 2;
/**
 * The number of users we process at a time when creating index entries in wp_usermeta.
 * This number is limited to avoid swamping MariaDB / MySQL with vast
 * update transactions  when creating index metadata for large numbers of users.
 */
const PERFORMANCE_MONITOR_BATCHSIZE      = 2000;

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.php
 * @noinspection PhpIncludeInspection
 */
function activate_performance_monitor() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/activator.php';
  Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.php
 * @noinspection PhpIncludeInspection
 */
function deactivate_performance_monitor() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/deactivator.php';
  Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_performance_monitor' );
register_deactivation_hook( __FILE__, 'deactivate_performance_monitor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
/** @noinspection PhpIncludeInspection */
require plugin_dir_path( __FILE__ ) . 'includes/plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */

$plugin = new PerformanceMonitor\PerformanceMonitor();
$plugin->run();
