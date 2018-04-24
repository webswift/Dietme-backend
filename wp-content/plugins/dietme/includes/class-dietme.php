<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.dietme.it
 * @since      1.0.0
 *
 * @package    Dietme
 * @subpackage Dietme/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Dietme
 * @subpackage Dietme/includes
 * @author     Fabio Sirchia <fabio.sirchia@gmail.com>
 */
class Dietme {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dietme_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'dietme';
		$this->version = '1.0.0';

		$this->classesDir = array (
				plugin_dir_path( dirname( __FILE__ ) ).'classes/',
				plugin_dir_path( dirname( __FILE__ ) ).'post-types/',
		);
		$this->__autoload();
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}
	
	private function __autoload(){
		foreach ($this->classesDir as $directory) {
			foreach (glob("{$directory}/*.php") as $filename){
				include_once $filename;
			}
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dietme_Loader. Orchestrates the hooks of the plugin.
	 * - Dietme_i18n. Defines internationalization functionality.
	 * - Dietme_Admin. Defines all hooks for the admin area.
	 * - Dietme_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dietme-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dietme-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dietme-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dietme-public.php';
		
		
		$this->loader = new Dietme_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dietme_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dietme_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Dietme_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dietme_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'init_users_taxonomies' );
		
		
		/*Utenti*/
		$plugin_user = new Dietme_Post_Types_User();
		$this->loader->add_action( 'init', $plugin_user, 'init' );
		
		/*Pazienti*/
		$plugin_patient = new Dietme_Post_Types_Patient();
		$this->loader->add_action( 'init', $plugin_patient, 'init' );
		
		/*Visite*/
		$plugin_examination = new Dietme_Post_Types_Examination();
		$this->loader->add_action( 'init', $plugin_examination, 'init' );
		
		
		/*Api rest*/
		$plugin_rest_api = new Dietme_Rest_Api();
		$this->loader->add_filter( 'rest_request_after_callbacks', $plugin_rest_api, 'dm_rest_request_after_callbacks' );
		
		
		/*Api rest login*/
		$plugin_rest_api_login = new Dietme_Rest_Login();
		$this->loader->add_action( 'rest_api_init', $plugin_rest_api_login, 'init_register_routes' );
		
		/*Api rest patient*/
		$plugin_rest_api_patient = new Dietme_Rest_Patient();
		$this->loader->add_action( 'rest_api_init', $plugin_rest_api_patient, 'init_register_routes' );
		
		/*Api rest visite*/
		$plugin_rest_api_examination = new Dietme_Rest_Examination();
		$this->loader->add_action( 'rest_api_init', $plugin_rest_api_examination, 'init_register_routes' );
		
		//$plugin_rest_api_login->register_routes();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Dietme_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
