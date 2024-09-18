<?php

namespace Nirab\WV\Frontend;

/**
 * Handle Prices.
 */
class Price {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_filter( 'woocommerce_product_get_price', array( $this, 'update_vip_price' ), 10, 2 );
		add_filter( 'woocommerce_product_get_sale_price', array( $this, 'update_vip_price' ), 10, 2 );
	}

	/**
	 * Update price for VIP.
	 *
	 * @param string      $price Price.
	 * @param \WC_Product $product Product Object.
	 *
	 * @return string
	 */
	public function update_vip_price( $price, \WC_Product $product ) {
		$current_user = wp_get_current_user();
		$roles        = $current_user->roles;
		$vip_role     = $product->get_meta( '_nirab_wv_vip_role' );
		$vip_price    = $product->get_meta( '_nirab_wv_vip_price' );

		if ( empty( $vip_role ) || 'none' === $vip_role || empty( $vip_price ) || ! in_array( $vip_role, $roles, true ) ) {
			return $price;
		}

		return $vip_price;
	}
}
