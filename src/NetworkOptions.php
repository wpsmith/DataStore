<?php
/**
 * Network Options Class
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

if ( ! class_exists( __NAMESPACE__ . '\NetworkOptions' ) ) {
	/**
	 * Class NetworkOptions
	 * @package WPS\WP\DataStore
	 */
	class NetworkOptions extends AbstractOptions {

		/**
		 * The network ID.
		 *
		 * @var int
		 */
		protected $id;

		/**
		 * Create new Network Options instance.
		 *
		 * @param string   $prefix The prefix automatically added to option names.
		 * @param int|null $network_id Optional. The network ID or null for the current network. Default null.
		 */
		public function __construct( $prefix = '_', $network_id = null ) {
			$this->prefix = $prefix;
			$this->id     = ! empty( $network_id ) ? absint( $network_id ) : \get_current_network_id();
		}

		/**
		 * Retrieves an option value.
		 *
		 * @param string $option The option name (without the plugin-specific prefix).
		 * @param mixed  $default Optional. Default value to return if the option does not exist. Default null.
		 * @param bool   $raw Optional. Use the raw option name (i.e. don't call get_name). Default false.
		 *
		 * @return mixed Value set for the option.
		 */
		public function get( $option, $default = null, $raw = false ) {
			$value = \get_network_option( $this->id, $raw ? $option : $this->get_name( $option ), $default );

			if ( is_array( $default ) && '' === $value ) {
				$value = [];
			}

			return $value;
		}

		/**
		 * Sets or updates an option.
		 *
		 * @param string $option The option name (without the plugin-specific prefix).
		 * @param mixed  $value The value to store.
		 * @param bool   $autoload Optional. This value is ignored for network options,
		 *                         which are always autoloaded. Default true.
		 * @param bool   $raw Optional. Use the raw option name (i.e. don't call get_name). Default false.
		 *
		 * @return bool False if value was not updated and true if value was updated.
		 */
		public function set( $option, $value, $autoload = true, $raw = false ) {
			return \update_network_option( $this->id, $raw ? $option : $this->get_name( $option ), $value );
		}

		/**
		 * Deletes an option.
		 *
		 * @param string $option The option name (without the plugin-specific prefix).
		 * @param bool   $raw Optional. Use the raw option name (i.e. don't call get_name). Default false.
		 *
		 * @return bool True, if option is successfully deleted. False on failure.
		 */
		public function delete( $option, $raw = false ) {
			return \delete_network_option( $this->id, $raw ? $option : $this->get_name( $option ) );
		}
	}
}

