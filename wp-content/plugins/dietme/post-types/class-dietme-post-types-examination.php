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
class Dietme_Post_Types_Examination {
	
	const FIELDTEXT_PREFIX = "field_examination_";
	const POST_TYPE = "examination";
	
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
	    $labels = array(
	        'name'                  => _x( 'Visite', 'Post type general name', 'dm' ),
	        'singular_name'         => _x( 'Visita', 'Post type singular name', 'dm' ),
	        'menu_name'             => _x( 'Visite', 'Admin Menu text', 'dm' ),
	        'name_admin_bar'        => _x( 'Visita', 'Add New on Toolbar', 'dm' ),
	        'add_new'               => __( 'Nuova', 'dm' ),
	        'add_new_item'          => __( 'Nuova Visita', 'dm' ),
	        'new_item'              => __( 'Nuova Visita', 'dm' ),
	        'edit_item'             => __( 'Modifica Visita', 'dm' ),
	        'view_item'             => __( 'Vedi Visita', 'dm' ),
	        'all_items'             => __( 'Tutte le Visite', 'dm' ),
	        'search_items'          => __( 'Cerca Visite', 'dm' ),
	        'parent_item_colon'     => __( 'Visite Padre:', 'dm' ),
	        'not_found'             => __( 'Nessuna visita trovata.', 'dm' ),
	        'not_found_in_trash'    => __( 'Nessuna visita trovata nel cestino.', 'dm' ),
	        'featured_image'        => _x( 'Cover Visita', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dm' ),
	        'set_featured_image'    => _x( 'Imposta immagine cover', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dm' ),
	        'remove_featured_image' => _x( 'Remuovi cover', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dm' ),
	        'use_featured_image'    => _x( 'Usa come immagine cover', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dm' ),
	        'archives'              => _x( 'Archivio visite', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dm' ),
	        'insert_into_item'      => _x( 'Inerisci nella visita', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dm' ),
	        'uploaded_to_this_item' => _x( 'Caricato in questa visita', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dm' ),
	        'filter_items_list'     => _x( 'Filtra lista visite', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dm' ),
	        'items_list_navigation' => _x( 'Lista navigazione visite', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dm' ),
	    );
	 
	    $args = array(
	        'labels'             => $labels,
	        'public'             => true,
	        'publicly_queryable' => true,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
	        'query_var'          => true,
	        'rewrite'            => array( 'slug' => 'archivio-visite' ),
	        'capability_type'    => 'post',
	        'has_archive'        => true,
	        'hierarchical'       => false,
	        'menu_position'      => null,
	        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'entry-views'),
	    	'taxonomies'          => array( 'category', 'post_tag' ),
	    	'menu_icon'   => 'dashicons-universal-access-alt',
    		'show_in_rest'       => true,
	    		'rest_base'          => self::POST_TYPE,
    		'rest_controller_class' => 'WP_REST_Posts_Controller',
	    );
	 
	    register_post_type( self::POST_TYPE, $args );
	    
	    if(function_exists("register_field_group"))
	    {
	    	register_field_group(array (
	    			'id' => 'acf_info-visita',
	    			'title' => 'Info visita',
	    			'fields' => array (
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'patient',
	    							'label' => 'Paziente visita',
	    							'name' => 'examination_patient',
	    							'type' => 'relationship',
	    							'required' => 1,
	    							'return_format' => 'id',
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
	    							'max' => 1,
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'published_at',
	    							'label' => 'Data della visita',
	    							'name' => 'published_at',
	    							'type' => 'date_picker',
	    							'required' => 1,
	    							'date_format' => 'yymmdd',
	    							'display_format' => 'dd/mm/yy',
	    							'first_day' => 1,
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'tag',
	    							'label' => 'Tag visita',
	    							'name' => 'examination_tag',
	    							'type' => 'taxonomy',
	    							'taxonomy' => 'post_tag',
	    							'field_type' => 'checkbox',
	    							'allow_null' => 0,
	    							'load_save_terms' => 0,
	    							'return_format' => 'id',
	    							'multiple' => 0,
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'note',
	    							'label' => 'Note visita',
	    							'name' => 'examination_note',
	    							'type' => 'wysiwyg',
	    							'default_value' => '',
	    							'toolbar' => 'full',
	    							'media_upload' => 'yes',
	    					),
	    			),
	    			'location' => array (
	    					array (
	    							array (
	    									'param' => 'post_type',
	    									'operator' => '==',
	    									'value' => self::POST_TYPE,
	    									'order_no' => 0,
	    									'group_no' => 0,
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
	    	register_field_group(array (
	    			'id' => 'acf_anamnesi',
	    			'title' => 'Anamnesi',
	    			'fields' => array (
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'history',
	    							'label' => 'Anamnesi',
	    							'name' => 'examination_history',
	    							'type' => 'wysiwyg',
	    							'default_value' => '',
	    							'toolbar' => 'full',
	    							'media_upload' => 'yes',
	    					),
	    			),
	    			'location' => array (
	    					array (
	    							array (
	    									'param' => 'post_type',
	    									'operator' => '==',
	    									'value' => self::POST_TYPE,
	    									'order_no' => 0,
	    									'group_no' => 0,
	    							),
	    					),
	    			),
	    			'options' => array (
	    					'position' => 'normal',
	    					'layout' => 'default',
	    					'hide_on_screen' => array (
	    					),
	    			),
	    			'menu_order' => 1,
	    	));
	    	register_field_group(array (
	    			'id' => 'acf_misurazioni',
	    			'title' => 'Misurazioni',
	    			'fields' => array (
	    			),
	    			'location' => array (
	    					array (
	    							array (
	    									'param' => 'post_type',
	    									'operator' => '==',
	    									'value' => self::POST_TYPE,
	    									'order_no' => 0,
	    									'group_no' => 0,
	    							),
	    					),
	    			),
	    			'options' => array (
	    					'position' => 'normal',
	    					'layout' => 'default',
	    					'hide_on_screen' => array (
	    					),
	    			),
	    			'menu_order' => 2,
	    	));
	    }
	    
	     
	}
}


