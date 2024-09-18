<?php
/**
 * Plugin Name: WooCommerce VIP
 * Plugin URI: https://nirab.me
 * Description: Technical task for PrimeTechBD
 * Version: 1.0.0
 * Author: Istiaq Ahmmed Nirab
 * Author URI: https://nirab.me
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nirab_wv
 * Domain Path: /languages
 *
 * @package WooCommerce VIP
 */

/**
 * Copyright (c) 2024 Istiaq Ahmmed Nirab (email: ok9xnirab@gmail.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

// Don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Nirab_Wv class
 *
 * @class Nirab_Wv The class that holds the entire Nirab_Wv plugin
 */
final class Nirab_Wv {
	/**
	 * Plugin version
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * Constructor for the Nirab_Wv class.
	 *
	 * Sets up all the appropriate hooks and actions
	 * within our plugin.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Initializes the Nirab_Wv() class.
	 *
	 * Checks for an existing Nirab_Wv() instance
	 * and if it doesn't find one, creates it.
	 *
	 * @since 1.0.0
	 *
	 * @return Nirab_Wv|bool
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new Nirab_Wv();
		}

		return $instance;
	}

	/**
	 * Define the constants.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'NIRAB_WV_VERSION', self::VERSION );
		define( 'NIRAB_WV_FILE', __FILE__ );
		define( 'NIRAB_WV_PATH', dirname( NIRAB_WV_FILE ) );
		define( 'NIRAB_WV_INCLUDES', NIRAB_WV_PATH . '/includes' );
		define( 'NIRAB_WV_URL', plugins_url( '', NIRAB_WV_FILE ) );
		define( 'NIRAB_WV_ASSETS', NIRAB_WV_URL . '/assets' );
	}

	/**
	 * Load the plugin after all plugis are loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Placeholder for activation function.
	 *
	 * Nothing being called here yet.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function activate() {
		$installer = new Nirab\WV\Installer();
		$installer->run();
	}

	/**
	 * Placeholder for deactivation function.
	 *
	 * Nothing being called here yet.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
	}

	/**
	 * Include the required files.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function includes() {
		if ( $this->is_request( 'admin' ) ) {
			new Nirab\WV\Admin();
		}

		if ( $this->is_request( 'frontend' ) ) {
			new Nirab\WV\Frontend();
		}

		if ( $this->is_request( 'ajax' ) ) {
			// ajax class.
		}
	}

	/**
	 * Initialize the hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'init', array( $this, 'init_classes' ) );

		// Localize our plugin
		add_action( 'init', array( $this, 'localization_setup' ) );
	}

	/**
	 * Instantiate the required classes.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function init_classes() {
		new Nirab\WV\Api();
		new Nirab\WV\Assets();
	}

	/**
	 * Initialize plugin for localization.
	 *
	 * @uses load_plugin_textdomain()
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function localization_setup() {
		load_plugin_textdomain( 'nirab_wv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * What type of request is this?
	 *
	 * @param string $type admin, ajax, cron or frontend.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();

			case 'ajax':
				return defined( 'DOING_AJAX' );

			case 'rest':
				return defined( 'REST_REQUEST' );

			case 'cron':
				return defined( 'DOING_CRON' );

			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}
} // Nirab_Wv

/**
 * Initialize the main plugin.
 *
 * @since 1.0.0
 *
 * @return \Nirab_Wv|bool
 */
function nirab_wv() {
	return Nirab_Wv::init();
}

/**
 * Kick-off the plugin.
 *
 * @since 1.0.0
 */
nirab_wv();
