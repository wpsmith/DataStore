<?php
/**
 * Settings Class
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

if ( ! class_exists( __NAMESPACE__ . '\Settings' ) ) {
	/**
	 * Class Settings
	 * @package WPS\WP\DataStore
	 */
	class Settings extends AbstractOptions {

		/**
		 * Array of defaults.
		 *
		 * @var array {
		 *     Optional. An array of arguments.
		 *
		 * @type string $prefix The prefix. Default '_'.
		 * @type string $id ID of the settings. Default null.
		 * }
		 */
		protected $defaults = array( 'prefix' => '_', 'id' => null );

		/**
		 * Settings cache.
		 *
		 * @var Cache
		 */
		protected $cache;

		/**
		 * Settings constructor.
		 *
		 * @param string $prefix The prefix. Default '_'.
		 * @param null   $id ID of the settings. Default null.
		 */
		public function __construct( $prefix = '_', $id = null ) {
			$this->prefix = $prefix;
			$this->id     = $id;
			$this->cache  = Cache::get_instance();
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
			// Allow child theme to short circuit this function.
			$pre = apply_filters( "{$this->get_name()}_pre_get_option_{$option}", null, $this->get_name() );
			if ( null !== $pre ) {
				return $pre;
			}

			// Bypass cache if viewing site in Customizer.
			if ( is_customize_preview() ) {
				$raw = true;
			}

			// If we need to bypass the cache.
			if ( $raw ) {
				$options = \get_option( $this->get_name(), $default );

				if ( ! is_array( $options ) || ! array_key_exists( $option, $options ) ) {
					return '';
				}

				return is_array( $options[ $option ] ) ? $options[ $option ] : wp_kses_decode_entities( $options[ $option ] );
			}

			// Setup caches.
			static $settings_cache = [];
			static $options_cache = [];

			// Check options cache.
			if ( isset( $options_cache[ $this->get_name() ][ $option ] ) ) {
				// Option has been cached.
				return $options_cache[ $this->get_name() ][ $option ];
			}

			// Check settings cache.
			if ( isset( $settings_cache[ $this->get_name() ] ) ) {
				// Setting has been cached.
				$options = apply_filters( "{$this->get_name()}_options", $settings_cache[ $this->get_name() ], $this->get_name() );
			} else {
				// Set value and cache setting.
				$settings_cache[ $this->get_name() ] = apply_filters( "{$this->get_name()}_options", \get_option( $this->get_name() ), $this->get_name() );
				$options                             = $settings_cache[ $this->get_name() ];
			}

			// Check for non-existent option.
			if ( ! is_array( $options ) || ! array_key_exists( $option, (array) $options ) ) {
				// Cache non-existent option.
				$options_cache[ $this->get_name() ][ $option ] = '';
			} else {
				// Option has not previously been cached, so cache now.
				$options_cache[ $this->get_name() ][ $option ] = is_array( $options[ $option ] ) ? $options[ $option ] : wp_kses_decode_entities( $options[ $option ] );
			}

			return $options_cache[ $this->get_name() ][ $option ];
		}

		/**
		 * Sets or updates an option.
		 *
		 * @param mixed $value The value to store.
		 * @param bool  $autoload Optional. Whether to load the option when WordPress
		 *                         starts up. For existing options, $autoload can only
		 *                         be updated using update_option() if $value is also
		 *                         changed. Default true.
		 * @param bool  $raw Deprecated. Do not use. Does nothing.
		 *
		 * @return bool
		 */
		public function set( $value, $autoload = true, $raw = false ) {
			$old = parent::get( $this->get_name() );

			$value = wp_parse_args( $value, $old );

			// Allow settings to be deleted.
			foreach ( $value as $key => $val ) {
				if ( 'unset' === $val ) {
					unset( $value[ $key ] );
				}
			}

			return update_option( $this->get_name(), $value, $autoload );
		}

		/**
		 * Deletes an entry from the cache.
		 *
		 * @param string $key The cache key root.
		 *
		 * @return bool True on successful removal, false on failure.
		 */
		public function delete( $key ) {
			return \delete_option( $this->get_name() );
		}
	}
}

