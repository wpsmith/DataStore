<?php
/**
 * DataStore Interface
 *
 * The data store interface.
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
 * @since     0.3.0
 */

namespace WPS\WP\DataStore;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( __NAMESPACE__ . '\DataStore' ) ) {
	/**
	 * Interface DataStore
	 *
	 * @package WPS\WP\DataStore
	 */
	interface DataStore {

		/**
		 * Retrieves a cached value.
		 *
		 * @param string $key The key root.
		 *
		 * @return mixed
		 */
		public function get( $key );

		/**
		 * Sets an entry in the cache and stores the key.
		 *
		 * @param mixed $key The key root.
		 * @param mixed $value The value to store.
		 * @param int   $duration Optional. The duration in seconds. Default 0 (no expiration).
		 *
		 * @return bool True if the cache could be set successfully.
		 */
		public function set( $key, $value, $duration = 0 );

		/**
		 * Deletes an entry from the cache.
		 *
		 * @param string $key Optional. The key root.
		 *
		 * @return bool True on successful removal, false on failure.
		 */
		public function delete( $key = null );

	}
}

