<?php

namespace Nirab\WV\Frontend;

/**
 * Handle Buy now Button.
 */
class BuyNow {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'woocommerce_single_product_summary', array( $this, 'add_buy_now_button' ), 35 );
		add_action( 'wp', array( $this, 'quick_checkout' ) );
		add_action( 'woocommerce_load_cart_from_session', array( $this, 'restore_cart_items' ) );
	}

	/**
	 * Ready Quick Checkout.
	 *
	 * @return void
	 */
	public function quick_checkout() {
		if ( ! is_checkout() || ! isset( $_GET['quick_checkout'], $_GET['add-to-cart'] ) || 'true' !== $_GET['quick_checkout'] ) {
			return;
		}

		$product_id        = sanitize_text_field( wp_unslash( $_GET['add-to-cart'] ) );
		$other_product_ids = array();

		$cart_items = WC()->cart->get_cart();
		foreach ( $cart_items as $cart_item_key => $cart_item ) {
			if ( $cart_item['product_id'] != $product_id ) {
				WC()->cart->remove_cart_item( $cart_item_key );
				array_push(
					$other_product_ids,
					array(
						'product_id' => $cart_item['product_id'],
						'quantity'   => $cart_item['quantity'],
					)
				);
			} else {
				WC()->cart->set_quantity( $cart_item_key, 1 );
			}
		}

		WC()->session->set( 'undo_cart_items', $other_product_ids );
	}

	/**
	 * Restore old cart items.
	 *
	 * @return void
	 */
	public function restore_cart_items() {
		$old_cart_items = WC()->session->get( 'undo_cart_items' );
		foreach ( $old_cart_items as $cart_item ) {
			WC()->cart->add_to_cart( $cart_item['product_id'], $cart_item['quantity'] );
		}

		WC()->session->set( 'undo_cart_items', array() );
	}

	/**
	 * Display Buy now Button.
	 *
	 * @return void
	 */
	public function add_buy_now_button() {
		global $product;

		if ( is_product() ) {
			$product_id = $product->get_id();

			$checkout_url = add_query_arg(
				array(
					'add-to-cart'    => $product_id,
					'quick_checkout' => 'true',
				),
				wc_get_checkout_url()
			);

			// Output the button HTML.
			echo '<br/><a href="' . esc_url( $checkout_url ) . '" class="button wp-element-button">Buy Now</a>';
		}
	}
}
