<?php
/**
 * AbstractCache Class
 *
 * The abstract cache to be extended.
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

use WPS\Core\Singleton;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\AbstractCache' ) ) {
	/**
	 * Class AbstractCache
	 *
	 * Implements a generic caching mechanism for WordPress.
	 *
	 * @package WPS\WP\DataStore
	 */
	abstract class AbstractCache extends Singleton implements DataStore {
		/**
		 * Incrementor for cache invalidation.
		 *
		 * @var int
		 */
		public $incrementor;

		/**
		 * The prefix added to all keys.
		 *
		 * @var string
		 */
		protected $prefix;

		/**
		 * Create new cache instance.
		 *
		 * @param string $prefix The prefix automatically added to cache keys.
		 */
		protected function __construct( $prefix ) {
			$this->prefix = $prefix;

			if ( empty( $this->incrementor ) ) {
				$this->invalidate();
			}
		}

		/**
		 * Invalidate all cached elements by reseting the incrementor.
		 *
		 * @return void
		 */
		abstract public function invalidate();

		/**
		 * Retrieves a cached value.
		 *
		 * @param string $key The cache key root.
		 *
		 * @return mixed
		 */
		abstract public function get( $key );

		/**
		 * Sets an entry in the cache and stores the key.
		 *
		 * @param string $key       The cache key root.
		 * @param mixed  $value     The value to store.
		 * @param int    $duration  Optional. The duration in seconds. Default 0 (no expiration).
		 *
		 * @return bool True if the cache could be set successfully.
		 */
		abstract public function set( $key, $value, $duration = 0 );

		/**
		 * Deletes an entry from the cache.
		 *
		 * @param string $key The cache key root.
		 *
		 * @return bool True on successful removal, false on failure.
		 */
		abstract public function delete( $key );

		/**
		 * Retrieves the complete key to use.
		 *
		 * @param  string $key The cache key root.
		 *
		 * @return string
		 */
		protected function get_key( $key ) {
			return "{$this->prefix}{$this->incrementor}_{$key}";
		}

		/**
		 * Retrieves the set prefix.
		 *
		 * @return string
		 */
		protected function get_prefix() {
			return $this->prefix;
		}
	}
}

