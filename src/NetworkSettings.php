<?php
/**
 * NetworkSettings Class
 *
 * The option-specific settings functionality for WordPress.
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
 * @copyright 2018-2019 Travis Smith
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License v2
 * @link      https://github.com/akamai/wp-akamai
 * @since     0.1.0
 */

namespace WPS\WP\DataStore;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\NetworkSettings' ) ) {
	/**
	 * Class NetworkSettings
	 * @package WPS\WP\DataStore
	 */
	class NetworkSettings extends AbstractNetworkOptions {

		/**
		 * Return option from the options table and cache result.
		 *
		 * Applies `wps_pre_get_option_$key` and `genesis_options` filters.
		 *
		 * Values pulled from the database are cached on each request, so a second request for the same value won't cause a
		 * second DB interaction.
		 *
		 * @param string $option Option name.
		 * @param string $setting Optional. Settings field name. Eventually defaults to `wps-settings` if not
		 *                          passed as an argument.
		 * @param bool   $use_cache Optional. Whether to use the Genesis cache value or not. Default is true.
		 *
		 * @return mixed The value of the `$key` in the database, or the return from
		 *               `wps_pre_get_option_{$key}` short circuit filter if not `null`.
		 */
		public function get( $option, $setting = null, $use_cache = true ) {
			$setting = $setting ? $setting : $this->get_name();

			// Allow child theme to short circuit this function.
			$pre = apply_filters( "{$setting}_pre_get_option_{$option}", null, $setting );
			if ( null !== $pre ) {
				return $pre;
			}

			// Bypass cache if viewing site in Customizer.
			if ( is_customize_preview() ) {
				$use_cache = false;
			}

			// If we need to bypass the cache.
			if ( ! $use_cache ) {
				$options = get_network_option( $this->network_id, $setting );

				if ( ! is_array( $options ) || ! array_key_exists( $option, $options ) ) {
					return '';
				}

				return is_array( $options[ $option ] ) ? $options[ $option ] : wp_kses_decode_entities( $options[ $option ] );
			}

			// Setup caches.
			static $settings_cache = [];
			static $options_cache = [];

			// Check options cache.
			if ( isset( $options_cache[ $setting ][ $option ] ) ) {
				// Option has been cached.
				return $options_cache[ $setting ][ $option ];
			}

			// Check settings cache.
			if ( isset( $settings_cache[ $setting ] ) ) {
				// Setting has been cached.
				$options = apply_filters( "{$setting}_options", $settings_cache[ $setting ], $setting );
			} else {
				// Set value and cache setting.
				$settings_cache[ $setting ] = apply_filters( "{$setting}_options", get_network_option( $this->network_id, $setting ), $setting );
				$options                    = $settings_cache[ $setting ];
			}

			// Check for non-existent option.
			if ( ! is_array( $options ) || ! array_key_exists( $option, (array) $options ) ) {
				// Cache non-existent option.
				$options_cache[ $setting ][ $option ] = '';
			} else {
				// Option has not previously been cached, so cache now.
				$options_cache[ $setting ][ $option ] = is_array( $options[ $option ] ) ? $options[ $option ] : wp_kses_decode_entities( $options[ $option ] );
			}

			return $options_cache[ $setting ][ $option ];
		}

		/**
		 * Sets or updates an option.
		 *
		 * @param string|array $new New settings. Can be a string, or an array.
		 * @param string       $setting Optional. Settings field name.
		 * @param bool         $autoload Optional. Not usable with network options.
		 *
		 * @return bool
		 */
		public function set( $new, $setting = null, $autoload = true ) {
			$setting = $setting ?: $this->get_name();
			$old     = get_option( $setting );

			$settings = wp_parse_args( $new, $old );

			// Allow settings to be deleted.
			foreach ( $settings as $key => $value ) {
				if ( 'unset' === $value ) {
					unset( $settings[ $key ] );
				}
			}

			return update_network_option( $this->network_id, $setting, $settings );
		}

		/**
		 * Deletes an entry from the cache.
		 *
		 * @param string $key The setting key.
		 *
		 * @return bool True on successful removal, false on failure.
		 */
		public function delete( $key = null ) {
			if ( null === $key ) {
				return delete_network_option( $this->network_id, $this->get_name() );
			}

			$settings = $this->get( $key );
			if ( isset( $settings[ $key ] ) ) {
				unset( $settings[ $key ] );
				return $this->set( $settings );
			}

			return false;
		}
	}
}

