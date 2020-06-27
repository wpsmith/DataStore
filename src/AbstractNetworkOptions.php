<?php
/**
 * Abstract Network Options Class
 *
 * The options-specific functionality for WordPress Multisite.
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

if ( ! class_exists( __NAMESPACE__ . '\AbstractNetworkOptions' ) ) {
	/**
	 * Class AbstractNetworkOptions
	 * @package WPS\WP\DataStore
	 */
	abstract class AbstractNetworkOptions extends AbstractOptions {

		/**
		 * The network ID.
		 *
		 * @var int
		 */
		protected $network_id;

		/**
		 * Settings cache.
		 *
		 * @var Cache
		 */
		protected $cache;

		/**
		 * Create new Network Options instance.
		 *
		 * @param string   $prefix The prefix automatically added to option names.
		 * @param int|null $network_id Optional. The network ID or null for the current network. Default null.
		 */
		public function __construct( $network_id = null, $id = null ) {
			$this->id         = $id;
			$this->cache  = Cache::get_instance();
			$this->network_id = ! empty( $network_id ) ? absint( $network_id ) : \get_current_network_id();
		}

	}
}

