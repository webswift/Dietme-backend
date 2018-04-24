<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.dietme.it
 * @since      1.0.0
 *
 * @package    Dietme
 * @subpackage Dietme/public
 */

/**
 * Override per wordpress api e compatibilt&agrave; con react admin on rest
 *
 * @package    Dietme
 * @subpackage Dietme/public
 * @author     Fabio Sirchia <fabio.sirchia@gmail.com>
 */
class Dietme_Rest_Api {

	
	public function dm_rest_request_after_callbacks( $response) {
		$response->headers["X-Total-Count"] = $response->headers["X-WP-Total"];
		$response->headers["Access-Control-Expose-Headers"] .= ", X-Total-Count, Authorization";
// 		$response->headers["Access-Control-Allow-Headers"] .= ", user-id";
		return $response;
	}
	

}
