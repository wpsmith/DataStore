<?php
/**
 * Abstract Options Class
 *
 * The shared functionality for Options.
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

use WPS\Core\Singleton;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( __NAMESPACE__ . '\AbstractOptions' ) ) {
	/**
	 * Class AbstractOptions
	 * @package WPS\WP\DataStore
	 */
	abstract class AbstractOptions implements DataStore {

		/**
		 * The prefix added to all keys.
		 *
		 * @var string
		 */
		protected $prefix = '';

		/**
		 * ID of something.
		 *
		 * For miscellaneous usage.
		 *
		 * @var mixed
		 */
		protected $id;

		/**
		 * Gets or sets the prefix.
		 *
		 * @param string|null $prefix The prefix to be set. No prefix means get prefix.
		 *
		 * @return string
		 */
		public function prefix( $prefix = null ) {
			if ( null !== $prefix ) {
				$this->prefix = $prefix;
			}

			return $this->prefix;
		}

		/**
		 * Gets or sets the ID.
		 *
		 * @param string|null $id The ID to be set. No ID means get ID.
		 *
		 * @return string
		 */
		public function id( $id = null ) {
			if ( null !== $id ) {
				$this->id = $id;
			}

			return $this->id;
		}

		/**
		 * Retrieves the complete option name to use.
		 *
		 * @param string $option The option name (without the plugin-specific prefix).
		 *
		 * @return string
		 */
		public function get_name( $option = null ) {
			if ( null === $option ) {
				return "{$this->prefix()}{$this->id}";
			}

			return "{$this->prefix()}{$option}";
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
		abstract public function get( $option, $default = null, $raw = false );

		/**
		 * Outputs an option value.
		 *
		 * @param string $option The option name (without the plugin-specific prefix).
		 * @param mixed  $default Optional. Default value to return if the option does not exist. Default null.
		 * @param bool   $raw Optional. Use the raw option name (i.e. don't call get_name). Default false.
		 *
		 * @return mixed Value set for the option.
		 */
		public function e( $option, $default = null, $raw = false ) {
			echo $this->get( $option, $default, $raw );
		}

	}
}

