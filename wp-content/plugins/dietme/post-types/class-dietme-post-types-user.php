<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.lifeisfood.it
 * @since      1.0.0
 *
 * @package    Lif
 * @subpackage Lif/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lif
 * @subpackage Lif/public
 * @author     Fabio Sirchia <fabio.sirchia@gmail.com>
 */
class Dietme_Post_Types_User {
	
	const FIELDTEXT_PREFIX = "field_user_";
	const POST_TYPE = "user";
	
	public function __construct() {
		
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function init() {
	    
	    
	    if(function_exists("register_field_group"))
	    {
	    	register_field_group(array (
	    			'id' => 'acf_area-pazienti',
	    			'title' => 'Area Pazienti',
	    			'fields' => array (
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'patient_list',
	    							'label' => 'Lista pazienti',
	    							'name' => 'patient_list',
	    							'type' => 'relationship',
	    							'return_format' => 'object',
	    							'post_type' => array (
	    									0 => 'dm-patient',
	    							),
	    							'taxonomy' => array (
	    									0 => 'all',
	    							),
	    							'filters' => array (
	    									0 => 'search',
	    							),
	    							'result_elements' => array (
	    									0 => 'post_type',
	    									1 => 'post_title',
	    							),
	    							'max' => '',
	    					),
	    			),
	    			'location' => array (
	    					array (
	    							array (
	    									'param' => 'ef_user',
	    									'operator' => '==',
	    									'value' => 'nutritionist',
	    									'order_no' => 0,
	    									'group_no' => 0,
	    							),
	    					),
	    					array (
	    							array (
	    									'param' => 'ef_user',
	    									'operator' => '==',
	    									'value' => 'administrator',
	    									'order_no' => 0,
	    									'group_no' => 1,
	    							),
	    					),
	    			),
	    			'options' => array (
	    					'position' => 'normal',
	    					'layout' => 'default',
	    					'hide_on_screen' => array (
	    					),
	    			),
	    			'menu_order' => 0,
	    	));
	    }
	    
	     
	}
}


