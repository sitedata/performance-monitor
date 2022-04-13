<?php

namespace PerformanceMonitor;

use Exception;

/**
 * Fired during plugin activation
 * @link       https://github.com/OllieJones
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 */


global $performance_monitor_db_version;
$performance_monitor_db_version = '1.0';

/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 * @package    Performance_Monitor
 * @subpackage Performance_Monitor/includes
 * @author     Ollie Jones <oj@plumislandmedia.net>
 */
class Activator {

	/**
	 * Short Description. Activate the plugin.
	 */
	public static function activate() {

		Activator::createTables();
		Activator::populateTables();
	}


	/**
	 * @return void
	 */
	private static function createTables() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;
		global $performance_monitor_db_version;


		$longColLength = 1024;
		$noteLength    = 127;
		$nameLength    = 63;
		$dpIdDataType  = 'SMALLINT';
		$dataTable     = $wpdb->prefix . PERFORMANCE_MONITOR_DB_PREFIX;
		$refTable      = $wpdb->prefix . PERFORMANCE_MONITOR_DB_PREFIX . 'dp';

		$charset_collate = $wpdb->get_charset_collate();

		$refDDL = "CREATE TABLE $refTable (
        dp_id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
        dp_class TINYINT UNSIGNED DEFAULT 0 COMMENT '0: systemwide, 1: global status, 2: variable, 3: INNODB_METRICS',
		dp_type TINYINT UNSIGNED DEFAULT 0 COMMENT '0: number  1: string',
        name VARCHAR($nameLength) COLLATE latin1_bin NOT NULL COMMENT 'case-sensitive naming',
        description VARCHAR($longColLength) DEFAULT NULL,
        reference VARCHAR($longColLength) DEFAULT NULL,
        note VARCHAR($noteLength) DEFAULT NULL,
        PRIMARY KEY (name, dp_id, dp_class),
        UNIQUE KEY(dp_id)          
	) $charset_collate;";

		$dataDDL = "CREATE TABLE $dataTable (
        dp_id $dpIdDataType UNSIGNED NOT NULL,
        ts TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        value FLOAT DEFAULT NULL COMMENT 'numeric value of datapoint',
        note VARCHAR($noteLength) DEFAULT NULL COMMENT 'text value of datapoint',
        PRIMARY KEY (dp_id, ts),
        KEY ts_datapoint(ts, dp_id) /* TODO should we cover value, note ? */ 
	) $charset_collate;";

		dbdelta( $refDDL );
		dbDelta( $dataDDL );

		add_option( PERFORMANCE_MONITOR_PREFIX . 'db-version', $performance_monitor_db_version );

	}

	private static function populate( $command, $class ) {
		global $wpdb;

		$status = $wpdb->get_results( $command, ARRAY_N );
		foreach ( $status as $row ) {
			$name = $row[0];
			$val  = $row[1];
			$type = self::getType( $name, $val );
			self::insertDatapointName( $name, $class, $type );
			self::insertDatapoint( $name, $val, $type );
		}
	}

	private static function populateTables() {

		//TODO we may want to put descriptive information (meaning of datapoint) in the dp table

		global $wpdb;

		try {
			$wpdb->query( "START TRANSACTION" );
			self::populate( "SHOW GLOBAL STATUS", 1 );
			self::populate( "SHOW GLOBAL VARIABLES", 2 );
			$wpdb->query( "COMMIT" );
		} catch ( Exception $ex ) {
			$wpdb->query( "ROLLBACK" );
			error_log( 'performance_monitor: table populating exception: ' . $ex->getMessage() . ' ' . $ex->getTraceAsString() );
		}

	}

	/** insert the name of a datapoint into the reference table
	 *
	 * @param string $name  the name of the variable or status
	 * @param int    $class 0: system-wide  1: global status 2: global variables
	 * @param int    $type  0: number 1: string  2: ON/OFF/YES/NO boolean.
	 *
	 * @return void
	 */
	private static function insertDatapointName( $name, $class, $type ) {
		global $wpdb;
		$refTable = $wpdb->prefix . PERFORMANCE_MONITOR_DB_PREFIX . 'dp';
		$q1       = $wpdb->prepare(
			"INSERT IGNORE INTO $refTable (dp_class, dp_type, name) VALUES (%d, %d, %s)",
			$class, $type, $name );
		$wpdb->query( $q1 );
	}

	/** get the data type from the name and sample value
	 *
	 * @param string $name
	 * @param mixed $val
	 *
	 * @return int  0: number, 1: string, 2: boolean.
	 * @noinspection PhpUnusedParameterInspection*/
	private static function getType( $name, $val ) {
		if ( is_numeric( $val ) ) {
			return 0;
		}
		if ( is_string( $val ) ) {
			$vu = strtoupper( $val );
			if ( $vu === 'ON' || $vu === 'YES' || $vu === 'OFF' || $vu === 'NO' ) {
				return 2;
			}
		}

		return 1;
	}

	private static function convertValue( $name, $val, $type ) {
		switch ( $type ) {
			case 0:
				return 0 + $val;
			case 2:
				$vu = strtoupper( $val );
				if ( $vu === 'YES' || $vu === 'ON' ) {
					return 1;
				}
				if ( $vu === 'NO' || $vu === 'OFF' ) {
					return 0;
				}
		}

		return $val;
	}

	/** insert the name of a datapoint into the reference table
	 *
	 * @param string $name
	 * @param mixed  $val the value of the datapoint
	 *
	 * @return void
	 */
	private static function insertDatapoint( $name, $val, $type ) {
		global $wpdb;
		$dataTable = $wpdb->prefix . PERFORMANCE_MONITOR_DB_PREFIX;
		$refTable  = $wpdb->prefix . PERFORMANCE_MONITOR_DB_PREFIX . 'dp';
		$v = self::convertValue($name, $val, $type);
		switch ( $type ) {
			case 0:
			default:
				$q2 = $wpdb->prepare(
					"INSERT IGNORE INTO $dataTable (dp_id, value) SELECT dp_id, %f FROM $refTable WHERE name = %s",
					$v, $name );
				break;
			case 1:
				$q2 = $wpdb->prepare(
					"INSERT IGNORE INTO $dataTable (dp_id, note) SELECT dp_id, %s FROM $refTable WHERE name = %s",
					$val, $name );

				break;
			case 2:
				$q2 = $wpdb->prepare(
					"INSERT IGNORE INTO $dataTable (dp_id, value, note) SELECT dp_id, %f, %s FROM $refTable WHERE name = %s",
					$v, $val, $name );

				break;
		}
		$wpdb->query( $q2 );
	}

}
