<?php
/**
 * Scripts and Styles Class.
 *
 * @package Nirab\WV\Assets
 */

namespace Nirab\WV;

/**
 * Scripts and Styles Class
 */
class Assets {

	/**
	 * Assets constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'register' ), 5 );
		} else {
			add_action( 'wp_enqueue_scripts', array( $this, 'register' ), 5 );
		}
	}

	/**
	 * Register our app scripts and styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register() {
		$this->register_scripts( $this->get_scripts() );
		$this->register_styles( $this->get_styles() );
	}

	/**
	 * Register scripts
	 *
	 * @param array $scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register_scripts( $scripts ) {
		foreach ( $scripts as $handle => $script ) {
			$deps      = isset( $script['deps'] ) ? $script['deps'] : false;
			$in_footer = isset( $script['in_footer'] ) ? $script['in_footer'] : false;
			$version   = isset( $script['version'] ) ? $script['version'] : NIRAB_WV_VERSION;

			wp_register_script( $handle, $script['src'], $deps, $version, $in_footer );
		}
	}

	/**
	 * Register styles
	 *
	 * @param array $styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_styles( $styles ) {
		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, NIRAB_WV_VERSION );
		}
	}

	/**
	 * Get all registered scripts
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_scripts() {
		$plugin_js_assets_path = NIRAB_WV_ASSETS . '/js/';

		$scripts = array(
			'nirab_wv_admin' => array(
				'src'       => $plugin_js_assets_path . 'admin.js',
				'deps'      => array( 'jquery' ),
				'in_footer' => true,
			),
		);

		return $scripts;
	}

	/**
	 * Get registered styles
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_styles() {
		$plugin_css_assets_path = NIRAB_WV_ASSETS . '/css/';

		$styles = array();

		return $styles;
	}
}
