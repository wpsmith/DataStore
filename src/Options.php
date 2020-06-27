<?php
/**
 * Options Class
 *
 * The options-specific functionality for WordPress.
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
 * @copyright 2018-202 Travis Smith
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link      https://github.com/akamai/wp-akamai
 * @since     0.1.0
 */

namespace WPS\WP\DataStore;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\Options' ) ) {
	/**
	 * Class Options
	 * @package WPS\WP\DataStore
	 */
	class Options extends AbstractOptions {

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
			// Bypass cache if viewing site in Customizer.
			if ( is_customize_preview() ) {
				$raw = false;
			}

			$value = \get_option( $raw ? $option : $this->get_name( $option ), $default );

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
		 * @param bool   $autoload Optional. Whether to load the option when WordPress
		 *                         starts up. For existing options, $autoload can only
		 *                         be updated using update_option() if $value is also
		 *                         changed. Default true.
		 * @param bool   $raw Optional. Use the raw option name (i.e. don't call get_name). Default false.
		 *
		 * @return bool False if value was not updated and true if value was updated.
		 */
		public function set( $option, $value, $autoload = true, $raw = false ) {
			return \update_option( $raw ? $option : $this->get_name( $option ), $value, $autoload );
		}

		/**
		 * Deletes an option.
		 *
		 * @param string $option The option name (without the plugin-specific prefix).
		 * @param bool   $raw Optional. Use the raw option name (i.e. don't call get_name). Default false.
		 *
		 * @return bool True, if option is successfully deleted. False on failure.
		 */
		public function delete( $option = null, $raw = false ) {
			if ( null === $option ) {
				return false;
			}

			return \delete_option( $raw ? $option : $this->get_name( $option ) );
		}

	}
}

