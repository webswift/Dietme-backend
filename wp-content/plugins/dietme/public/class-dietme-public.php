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
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Dietme
 * @subpackage Dietme/public
 * @author     Fabio Sirchia <fabio.sirchia@gmail.com>
 */
class Dietme_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dietme_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dietme_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dietme-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Dietme_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Dietme_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dietme-public.js', array( 'jquery' ), $this->version, false );

	}
	
	public function init_users_taxonomies(){
		$theUsers = get_users();
		foreach ($theUsers as $theUser){
			register_taxonomy(
					"tags_" . $theUser->ID,
					Dietme_Post_Types_Examination::POST_TYPE,
					array(
							'label' => __( 'Tags ' . $theUser->display_name ),
							'rewrite' => array( 'slug' => "tags_" . $theUser->ID ),
							'hierarchical' => false,
					)
					);
		}
	}

}
