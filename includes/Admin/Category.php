<?php

namespace Nirab\WV\Admin;

/**
 * Handle Category taxonomy
 */
class Category {

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'product_cat_add_form_fields', array( $this, 'add_form_fields_create' ) );
		add_action( 'product_cat_edit_form_fields', array( $this, 'add_form_fields_edit' ) );

		add_action( 'created_product_cat', array( $this, 'save_form' ) );
		add_action( 'edited_product_cat', array( $this, 'save_form' ) );
	}

	/**
	 * Add form fields to Category creation.
	 *
	 * @return void
	 */
	public function add_form_fields_create() {
		require_once 'views/category-form-create.php';
	}

	/**
	 * Add form fields to Category Edit.
	 *
	 * @param \WP_Term $term Category Term Object.
	 *
	 * @return void
	 */
	public function add_form_fields_edit( $term ) {
		$selected_role = get_term_meta( $term->term_id, 'nirab_wv_vip_role', true );
		require_once 'views/category-form-edit.php';
	}

	/**
	 * Save forms.
	 *
	 * @param int $term_id Category Id.
	 *
	 * @return void
	 */
	public function save_form( $term_id ) {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( ! isset( $_POST['nirab_vip_role'] ) ) {
			return;
		}

		// We don't need nonce verification on this case.

        // phpcs:ignore WordPress.Security.NonceVerification.Missing
		update_term_meta( $term_id, 'nirab_wv_vip_role', sanitize_text_field( wp_unslash( $_POST['nirab_vip_role'] ) ) );
	}
}
