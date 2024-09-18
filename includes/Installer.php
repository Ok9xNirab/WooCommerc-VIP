<?php
/**
 * The Installer class.
 * Install all dependency from here while activating the plugin.
 *
 * @package Nirab\WV\Installer
 */

namespace Nirab\WV;

/**
 * Class Installer
 * @package Nirab\WV
 */
class Installer {

    /**
     * Run the installer.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->create_tables();
    }

    /**
     * Add time and version on DB.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function add_version() {
        $installed = get_option( 'woocommerce_vip_installed' );

        if ( ! $installed ) {
            update_option( 'woocommerce_vip_installed', time() );
        }

        update_option( 'woocommerce_vip_version', NIRAB_WV_VERSION );
    }

    /**
     * Create necessary database tables.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function create_tables() {
        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        
    }

    
}
