<?php
/**
 * The admin class
 *
 * @package Nirab\WV\Admin
 */

namespace Nirab\WV;

use Nirab\WV\Admin\Category;
use Nirab\WV\Admin\Product;

/**
 * The admin class
 */
class Admin {

	/**
	 * Initialize the class.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->dispatch_actions();
		new Category();
		new Product();
	}

	/**
	 * Dispatch and bind actions.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function dispatch_actions() {
	}
}
