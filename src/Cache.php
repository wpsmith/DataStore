<?php
/**
 * Cache Class
 *
 * The cache class.
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

if ( ! class_exists( __NAMESPACE__ . '\Cache' ) ) {
	/**
	 * Class Cache
	 * @package WPS\WP\DataStore
	 */
	final class Cache extends AbstractCache implements DataStore {
		/**
		 * The incrementor cache key.
		 *
		 * @var string
		 */
		private $incrementor_key;

		/**
		 * The cache group.
		 *
		 * @var string
		 */
		private $group;

		/**
		 * Cache constructor.
		 *
		 * @param string      $prefix The prefix automatically added to cache keys.
		 * @param string|null $group  Optional. The cache group. Defaults to $prefix.
		 */
		public function __construct( $prefix, $group = null ) {

			$this->incrementor     = (int) \wp_cache_get( $this->incrementor_key, $this->group );
			parent::__construct( $prefix );

			$this->group           = ! isset( $group ) ? $prefix : $group;
			$this->incrementor_key = "{$this->prefix}cache_incrementor";

		}

		/**
		 * Invalidate all cached elements by reseting the incrementor.
		 */
		public function invalidate() {
			$this->incrementor = time();
			\wp_cache_set( $this->incrementor_key, $this->incrementor, $this->group, 0 );
		}

		/**
		 * Retrieves a cached value.
		 *
		 * @param string    $key   The cache key.
		 * @param bool|null $found Optional. Whether the key was found in the cache. Disambiguates a return of false as a storable value. Passed by reference. Default null.
		 *
		 * @return mixed
		 */
		public function get( $key, &$found = null ) {
			return \wp_cache_get( $this->get_key( $key ), $this->group, false, $found );
		}

		/**
		 * Sets an entry in the cache and stores the key.
		 *
		 * @param string $key       The cache key.
		 * @param mixed  $value     The value to store.
		 * @param int    $duration  Optional. The duration in seconds. Default 0 (no expiration).
		 *
		 * @return bool True if the cache could be set successfully.
		 */
		public function set( $key, $value, $duration = 0 ) {
			return \wp_cache_set( $this->get_key( $key ), $value, $this->group, $duration );
		}

		/**
		 * Deletes an entry from the cache.
		 *
		 * @param string $key The cache key root.
		 *
		 * @return bool True on successful removal, false on failure.
		 */
		public function delete( $key = null ) {
			if ( null === $key ) {
				return false;
			}

			return \wp_cache_delete( $this->get_key( $key ), $this->group );
		}
	}
}

