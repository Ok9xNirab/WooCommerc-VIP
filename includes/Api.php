<?php
/**
 * API Class
 * 
 * @package Nirab\WV\API
 */

namespace Nirab\WV;

/**
 * API Class
 */
class API {

    /**
     * Initialize the class.
     * 
     * @since 1.0.0
     * 
     * @return void
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_api' ] );
    }

    /**
     * Register the API.
     * 
     * @since 1.0.0
     *
     * @return void
     */
    public function register_api() {
        
    }
}