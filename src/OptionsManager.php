<?php
/**
 * OptionsManager Class
 *
 * The class gets the options-specific functionality for type of WordPress installation.
 *
 * You may copy, distribute and modify the software as long as you track
 * changes/dates in source files. Any modifications to or software including
 * (via compiler) GPL-licensed code must also be made available under the GPL
 * along with build & install instructions.
 *
 * PHP Version 7.2
 *
 * @category  WPS\WP\DataStore
 * @package   WPS\WP\DataStore
 * @author    Travis Smith <t@wpsmith.net>
 * @copyright 2018-2020 Travis Smith
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link      https://github.com/akamai/wp-akamai
 * @since     0.1.0
 */

namespace WPS\WP\DataStore;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\OptionsManager' ) ) {
	/**
	 * Class OptionsManager
	 * @package WPS\WP\DataStore
	 */
	class OptionsManager {
		/**
		 * Returns the Manager to use.
		 *
		 * @param int|null $network_id Optional. The network ID or null for the current network. Default null.
		 *
		 * @return NetworkOptions|Options Data Store to use.
		 */
		public static function get( $network_id = null ) {
			static $manager = null;

			if ( $manager === null ) {
				if ( is_multisite() ) {
					$manager = new NetworkOptions( $network_id );
				} else {
					$manager = new Options();
				}
			}

			return $manager;
		}

		/**
		 * Returns the Manager to use.
		 *
		 * @param int|null $network_id Optional. The network ID or null for the current network. Default null.
		 *
		 * @return NetworkSettings|Settings Data Store to use.
		 */
		public static function get_settings( $network_id = null ) {
			static $manager = null;

			if ( $manager === null ) {
				if ( is_multisite() ) {
					$manager = new NetworkSettings( $network_id );
				} else {
					$manager = new Settings();
				}
			}

			return $manager;
		}
	}
}

