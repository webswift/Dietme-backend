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
class Dietme_Post_Types_Patient {
	
	const FIELDTEXT_PREFIX = "field_patient_";
	const POST_TYPE = "dm-patient";
	
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
	        'name'                  => _x( 'Pazienti', 'Post type general name', 'dm' ),
	        'singular_name'         => _x( 'Paziente', 'Post type singular name', 'dm' ),
	        'menu_name'             => _x( 'Pazienti', 'Admin Menu text', 'dm' ),
	        'name_admin_bar'        => _x( 'Paziente', 'Add New on Toolbar', 'dm' ),
	        'add_new'               => __( 'Nuovo', 'dm' ),
	        'add_new_item'          => __( 'Nuovo Paziente', 'dm' ),
	        'new_item'              => __( 'Nuovo Paziente', 'dm' ),
	        'edit_item'             => __( 'Modifica Paziente', 'dm' ),
	        'view_item'             => __( 'Vedi Paziente', 'dm' ),
	        'all_items'             => __( 'Tutti gli Pazienti', 'dm' ),
	        'search_items'          => __( 'Cerca Pazienti', 'dm' ),
	        'parent_item_colon'     => __( 'Pazienti Padre:', 'dm' ),
	        'not_found'             => __( 'Nessun alimento trovato.', 'dm' ),
	        'not_found_in_trash'    => __( 'Nessun alimento trovato nel cestino.', 'dm' ),
	        'featured_image'        => _x( 'Cover Paziente', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'dm' ),
	        'set_featured_image'    => _x( 'Imposta immagine cover', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'dm' ),
	        'remove_featured_image' => _x( 'Remuovi cover', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'dm' ),
	        'use_featured_image'    => _x( 'Usa come immagine cover', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'dm' ),
	        'archives'              => _x( 'Archivio alimento', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'dm' ),
	        'insert_into_item'      => _x( 'Inerisci nell alimento', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'dm' ),
	        'uploaded_to_this_item' => _x( 'Caricato in questo alimento', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'dm' ),
	        'filter_items_list'     => _x( 'Filtra lista alimenti', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'dm' ),
	        'items_list_navigation' => _x( 'Lista navigazione alimenti', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'dm' ),
	    );
	 
	    $args = array(
	        'labels'             => $labels,
	        'public'             => true,
	        'publicly_queryable' => true,
	        'show_ui'            => true,
	        'show_in_menu'       => true,
	        'query_var'          => true,
	        'rewrite'            => array( 'slug' => 'pazienti' ),
	        'capability_type'    => 'post',
	        'has_archive'        => true,
	        'hierarchical'       => false,
	        'menu_position'      => null,
	        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'entry-views'),
	    	'taxonomies'          => array( 'category' ),
	    	'menu_icon'   => 'dashicons-admin-users',
    		'show_in_rest'       => true,
	    		'rest_base'          => self::POST_TYPE,
    		'rest_controller_class' => 'WP_REST_Posts_Controller',
	    );
	 
	    register_post_type( self::POST_TYPE, $args );
	    
	    if(function_exists("register_field_group"))
	    {
	    	register_field_group(array (
	    			'id' => 'acf_anagrafica-paziente',
	    			'title' => 'Anagrafica Paziente',
	    			'fields' => array (
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'name',
	    							'label' => 'Nome',
	    							'name' => 'name',
	    							'type' => 'text',
	    							'default_value' => '',
	    							'placeholder' => '',
	    							'prepend' => '',
	    							'append' => '',
	    							'formatting' => 'html',
	    							'maxlength' => '',
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'surname',
	    							'label' => 'Cognome',
	    							'name' => 'surname',
	    							'type' => 'text',
	    							'default_value' => '',
	    							'placeholder' => '',
	    							'prepend' => '',
	    							'append' => '',
	    							'formatting' => 'html',
	    							'maxlength' => '',
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'city',
	    							'label' => 'Citta',
	    							'name' => 'city',
	    							'type' => 'text',
	    							'default_value' => '',
	    							'placeholder' => '',
	    							'prepend' => '',
	    							'append' => '',
	    							'formatting' => 'html',
	    							'maxlength' => '',
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'address',
	    							'label' => 'Indirizzo',
	    							'name' => 'address',
	    							'type' => 'text',
	    							'default_value' => '',
	    							'placeholder' => '',
	    							'prepend' => '',
	    							'append' => '',
	    							'formatting' => 'html',
	    							'maxlength' => '',
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'telephone',
	    							'label' => 'Numero di telefono',
	    							'name' => 'telephone',
	    							'type' => 'text',
	    							'default_value' => '',
	    							'placeholder' => '',
	    							'prepend' => '',
	    							'append' => '',
	    							'formatting' => 'html',
	    							'maxlength' => '',
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'note',
	    							'label' => 'Note',
	    							'name' => 'note',
	    							'type' => 'wysiwyg',
	    							'default_value' => '',
	    							'toolbar' => 'full',
	    							'media_upload' => 'yes',
	    					),
	    					array (
	    							'key' => self::FIELDTEXT_PREFIX . 'published_at',
	    							'label' => 'Data primo incontro',
	    							'name' => 'published_at',
	    							'type' => 'date_picker',
	    							'date_format' => 'yymmdd',
	    							'display_format' => 'dd/mm/yy',
	    							'first_day' => 1,
	    					),
	    			),
	    			'location' => array (
	    					array (
	    							array (
	    									'param' => 'post_type',
	    									'operator' => '==',
	    									'value' => 'dm-patient',
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
	    }
	    
	     
	}
}


