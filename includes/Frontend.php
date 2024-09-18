<?php
/**
 * Frontend handler class
 *
 * @package Nirab\WV\Frontend
 */

namespace Nirab\WV;

use Nirab\WV\Frontend\Button;
use Nirab\WV\Frontend\BuyNow;
use Nirab\WV\Frontend\Price;
use Nirab\WV\Frontend\Product;

/**
 * Frontend handler class
 */
class Frontend {

	/**
	 * Frontend constructor.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		new Price();
		new Product();
		new BuyNow();
	}
}
