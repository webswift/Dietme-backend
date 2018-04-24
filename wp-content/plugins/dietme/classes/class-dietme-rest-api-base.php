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
class Dietme_Rest_Api_Base extends WP_REST_Controller {
	
	protected $current_user;
	
	/**
	 * Check if a given request has access to create items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function create_item_permissions_check( $request ) {
		$is_authenticated = wp_get_current_user();
		if(!is_wp_error($is_authenticated)){
			$this->current_user = $is_authenticated;
			return true;
		}
		return false;
// 		$usr = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : '';
// 		$pwd = isset($_SERVER['PHP_AUTH_PW'])   ? $_SERVER['PHP_AUTH_PW']   : '';
// 		if (empty($usr) && empty($pwd) && isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION']) {
// 			list($type, $auth) = explode(' ', $_SERVER['HTTP_AUTHORIZATION']);
// 			if (strtolower($type) === 'basic') {
// 				list($usr, $pwd) = explode(':', base64_decode($auth));
// 			}
// 		}
		
// 		$is_authenticated = wp_authenticate($usr, $pwd);
// 		if(!is_wp_error($is_authenticated)){
// 			$this->current_user = $is_authenticated;
// 			return true;
// 		}
// 		return false;
	}
	
	public function  get_params($request){
		$params = $request->get_params();
		$params["_sort"]=str_replace("acf.", "", $params["_sort"]);
		return $params;
	}
	

}
