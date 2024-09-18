<?php

namespace Nirab\WV\Frontend;

/**
 * Handle Products.
 */
class Product {

	/**
	 * Current User roles.
	 *
	 * @var array
	 */
	private $user_roles = array();

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'set_current_user_roles' ) );
		add_filter( 'woocommerce_product_query_meta_query', array( $this, 'hide_vip_products_based_vip_meta' ) );
		add_filter( 'woocommerce_product_query_tax_query', array( $this, 'hide_vip_products_based_category' ) );
		add_action( 'woocommerce_before_main_content', array( $this, 'check_category_access' ) );
		add_action( 'woocommerce_before_single_product', array( $this, 'check_single_product_access' ) );
	}

	/**
	 * Set current user roles.
	 *
	 * @return void
	 */
	public function set_current_user_roles() {
		$current_user = wp_get_current_user();
		$roles        = $current_user->roles;

		if ( count( $roles ) === 0 ) {
			$roles = array( 'none' );
		}

		$this->user_roles = $roles;
	}

	/**
	 * Filter out the query based on VIP role set.
	 *
	 * @param array $meta_query Meta Query.
	 *
	 * @return [type]
	 */
	public function hide_vip_products_based_vip_meta( $meta_query ) {
		$meta_query[] = array(
			'relation' => 'OR',
			array(
				'key'     => '_nirab_wv_vip_role',
				'compare' => 'NOT EXISTS',
			),
			array(
				'key'     => '_nirab_wv_vip_role',
				'value'   => 'none',
				'compare' => '=',
			),
			array(
				'key'     => '_nirab_wv_vip_role',
				'value'   => $this->user_roles,
				'compare' => 'IN',
			),
		);

		return $meta_query;
	}

	/**
	 * Filter the query based on VIP Category.
	 *
	 * @param array $tax_query Taxonomy Query.
	 *
	 * @return array
	 */
	public function hide_vip_products_based_category( $tax_query ) {
		$terms = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => 'nirab_wv_vip_role',
						'compare' => 'NOT EXISTS',
					),
					array(
						'key'     => 'nirab_wv_vip_role',
						'value'   => 'none',
						'compare' => '=',
					),
					array(
						'key'     => 'nirab_wv_vip_role',
						'value'   => $this->user_roles,
						'compare' => 'IN',
					),
				),
				'fields'     => 'ids',
				'hide_empty' => false,
			)
		);

		$tax_query[] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $terms,
			'operator' => 'IN',
		);

		return $tax_query;
	}

	/**
	 * Check Category access.
	 *
	 * @return void
	 */
	public function check_category_access() {
		if ( ! is_product_category() ) {
			return;
		}

		$current_category = get_queried_object();

		if ( ! $this->check_category( $current_category->term_id ) ) {
			$this->redirect_404();
		}
	}

	/**
	 * Check single product access.
	 *
	 * @return void
	 */
	public function check_single_product_access() {
		if ( ! is_product() || ! get_the_ID() ) {
			return;
		}

		$product    = wc_get_product( get_the_ID() );
		$categories = $product->get_category_ids();
		$redirect   = false;

		foreach ( $categories as $category ) {
			if ( ! $this->check_category( $category ) ) {
				$redirect = true;
			}
		}

		if ( $redirect ) {
			$this->redirect_404();
		}
	}

	/**
	 * Check category Id.
	 *
	 * @param int $category_id Category Id.
	 *
	 * @return bool
	 */
	private function check_category( $category_id ) {
		$vip_role = get_term_meta( $category_id, 'nirab_wv_vip_role', true );

		if ( empty( $vip_role ) || 'none' === $vip_role ) {
			return true;
		}

		return in_array( $vip_role, $this->user_roles, true );
	}

	/**
	 * Redirect to 404
	 *
	 * @return void
	 */
	private function redirect_404() {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		nocache_headers();
		include get_404_template();
		exit;
	}
}
