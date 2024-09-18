<?php

namespace Nirab\WV\Admin;

/**
 * Handle Product forms.
 */
class Product {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'woocommerce_product_options_pricing', array( $this, 'add_forms' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_form_data' ) );
	}

	/**
	 * Display forms.
	 *
	 * @return void
	 */
	public function add_forms() {
		wp_enqueue_script( 'nirab_wv_admin' );

		$product = wc_get_product( get_the_ID() );

		$role_options = array_merge(
			array(
				'none' => __( 'None', 'nirab_wv' ),
			),
			wp_roles()->role_names
		);

		woocommerce_wp_select(
			array(
				'id'          => 'nirab_wv_vip_role',
				'label'       => __( 'VIP Role', 'nirab_wv' ),
				'value'       => $product->get_meta( '_nirab_wv_vip_role' ),
				'options'     => $role_options,
				'description' => __( 'Set the role to restrict access the product.', 'nirab_wv' ),
				'desc_tip'    => true,
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'          => 'nirab_wv_vip_price',
				'label'       => __( 'VIP Price', 'nirab_wv' ),
				'class'       => 'short wc_input_price',
				'value'       => $product->get_meta( '_nirab_wv_vip_price' ),
				'description' => __( 'Set price for only VIP role users.', 'nirab_wv' ),
				'desc_tip'    => true,
			)
		);
	}

	/**
	 * Save form data.
	 *
	 * @param int $product_id Product Id.
	 *
	 * @return void
	 */
	public function save_form_data( $product_id ) {
		// we don't need verify nonce here.

        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['nirab_wv_vip_role'], $_POST['nirab_wv_vip_price'] ) ) {
			return;
		}

		$product = wc_get_product( $product_id );
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product->update_meta_data( '_nirab_wv_vip_role', sanitize_text_field( wp_unslash( $_POST['nirab_wv_vip_role'] ) ) );
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$product->update_meta_data( '_nirab_wv_vip_price', sanitize_text_field( wp_unslash( $_POST['nirab_wv_vip_price'] ) ) );
		$product->save();
	}
}
