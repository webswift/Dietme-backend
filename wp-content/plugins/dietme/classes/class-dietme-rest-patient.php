<?php

class Dietme_Rest_Patient extends Dietme_Rest_Api_Base{
	
	public function init_register_routes(){
		$this->register_routes();
	}
	
	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version = '1';
		$namespace = 'dm/v' . $version;
		$base = 'dm-patient';
		
		register_rest_route( $namespace, '/' . $base, array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_patients' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => array(
								
						),
				),
				array(
						'methods'         => WP_REST_Server::CREATABLE,
						'callback'        => array( $this, 'create_patient' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				),
		) );
		
		register_rest_route( $namespace, '/' . $base . '/full', array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_patients_full' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => array(
								
						),
				)
		) );
		
		register_rest_route( $namespace, '/' . $base . '/full/(?P<id>[\d]+)', array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_patients_full' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => array(
								
						),
				)
		) );
		
		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_patient' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				),
				array(
						'methods'         => WP_REST_Server::EDITABLE,
						'callback'        => array( $this, 'update_patient' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				),
				array(
						'methods'         => WP_REST_Server::DELETABLE,
						'callback'        => array( $this, 'delete_patient' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				)
		) );
		
		register_rest_route( $namespace, '/' . $base . '/tags', array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_tags' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => array(
								
						),
				)
		) );
		
		
		
	}
	
	public function get_patient_args($request, $args){
		$params = $this->get_params($request);
// 		print_r($params);
// 		exit;
		if(array_key_exists("order", $params)){
			$requested_order = current($params["order"]);
			if(array_key_exists("column", $requested_order) && array_key_exists("dir", $requested_order)){
				$requested_order_column = $requested_order["column"];
				$args["meta_key"] = $params["columns"][$requested_order_column]["data"];
				$args["order"] = $requested_order['dir'];
				$args["orderby"] = 'meta_value';
			}
		}
		if(array_key_exists("search", $params)){
			$query_string = $params["search"]["value"];
// 			$args["s"] = $query_string;
			$args["meta_query"]= array(
					'relation' => 'OR', // Optional, defaults to "AND"
					array(
							'key'     => "name",
							'value'   => "".$query_string,
							'compare' => 'LIKE'
					),
					array(
							'key'     => "surname",
							'value'   => "".$query_string,
							'compare' => 'LIKE'
					),
					array(
							'key'     => "city",
							'value'   => "".$query_string,
							'compare' => 'LIKE'
					),
					array(
							'key'     => "address",
							'value'   => "".$query_string,
							'compare' => 'LIKE'
					),
					array(
							'key'     => "telephone",
							'value'   => "".$query_string,
							'compare' => 'LIKE'
					),
					array(
							'key'     => "note",
							'value'   => "".$query_string,
							'compare' => 'LIKE'
					),
					
			);
		}
// 		if(array_key_exists("_sort", $params) && array_key_exists("_order", $params)){
// 			if($params["_sort"] != "id"){
// 				$args["meta_key"] = $params["_sort"];
// 				$args["order"] = $params['_order'];
// 				$args["orderby"] = 'meta_value';
// 			}
// 		}
		return $args;
	}
	
	
	public function get_patient_list($request){
		$patient_list = get_field("field_user_patient_list", "user_" . $this->current_user->ID);
		if($patient_list){
			$patients_ids = array();
			foreach ($patient_list as $key => $thePatient){
				$patients_ids[] = $thePatient->ID;
			}
			$args = array(
					"post_type" => Dietme_Post_Types_Patient::POST_TYPE,
					'include'  => $patients_ids
					
			);
			$args = $this->get_patient_args($request, $args);
			$patient_list = get_posts($args);
			
			foreach ($patient_list as $key => $thePatient){
				$acf = get_fields($thePatient->ID);
				$thePatient->id = $thePatient->ID;
				$thePatient->acf = $acf;
			}
		}else{
			$patient_list = array();
		}
		$header["X-WP-Total"] = count($patient_list);
		return new WP_REST_Response($patient_list, 200, $header);
	}
	
	/*
	 * Direi che possiamo creare il paziente direttamente ed associarlo all'utente senza grossi controlli
	 * alla fine sono ammessi duplicati, forse potremmo inserire una qualche discriminante piu avanti
	 * ma per adesso creiamolo e basta.
	 * */
	public function create_patient_insert_post($item){
		// Create post object
		$my_patient = array(
				'post_title'    => wp_strip_all_tags( $item['post_title'] ),
				'post_content'  => $item['post_title'],
				'post_status'   => 'publish',
				'post_author'   => $this->current_user->ID,
				'post_date'     => $item["published_at"],
				'post_type'     => 'dm-patient'
		);
		
		// Insert the post into the database
		$thePatient = wp_insert_post( $my_patient );
		if(!is_wp_error($thePatient)){
			update_field('field_patient_name'          ,$item["name"]         , $thePatient );
			update_field('field_patient_surname'       ,$item["surname"]      , $thePatient );
			update_field('field_patient_city'          ,$item["city"]         , $thePatient );
			update_field('field_patient_address'       ,$item["address"]      , $thePatient );
			update_field('field_patient_telephone'     ,$item["telephone"]    , $thePatient );
			update_field('field_patient_note'          ,$item["note"]         , $thePatient );
			update_field('field_patient_published_at'  ,$item["published_at"] , $thePatient );
			/*Dopo aver completato l'inserimento del paziente lo colleghiamo alla nutrizionista*/
			$patient_list = get_field("field_user_patient_list", "user_" . $this->current_user->ID);
			$new_patient = get_post( $thePatient);
			array_push( $patient_list, $new_patient);
			update_field( 'field_user_patient_list', $patient_list, "user_" . $this->current_user->ID);
		}
		return $thePatient;
	}
	
	public function update_patient_insert_post($item){
		// Create post object
		$my_patient = array(
				'ID'            => $item["ID"],
				'post_title'    => wp_strip_all_tags( $item['post_title'] ),
				'post_content'  => $item['post_title'],
				'post_status'   => 'publish',
				'post_author'   => $this->current_user->ID,
				'post_date'     => $item["published_at"],
				'post_type'     => 'dm-patient'
		);
		
		// Insert the post into the database
		$thePatient = wp_update_post( $my_patient );
		if(!is_wp_error($thePatient)){
			update_field('field_patient_name'          ,$item["name"]         , $thePatient );
			update_field('field_patient_surname'       ,$item["surname"]      , $thePatient );
			update_field('field_patient_city'          ,$item["city"]         , $thePatient );
			update_field('field_patient_address'       ,$item["address"]      , $thePatient );
			update_field('field_patient_telephone'     ,$item["telephone"]    , $thePatient );
			update_field('field_patient_note'          ,$item["note"]         , $thePatient );
			update_field('field_patient_published_at'  ,$item["published_at"] , $thePatient );
			/*Dopo aver completato l'inserimento del paziente lo colleghiamo alla nutrizionista*/
			$patient_list = get_field("field_user_patient_list", "user_" . $this->current_user->ID);
			$new_patient = get_post( $thePatient);
			array_push( $patient_list, $new_patient);
			update_field( 'field_user_patient_list', $patient_list, "user_" . $this->current_user->ID);
		}
		return $thePatient;
	}
	
	public function get_patients_full( $request ) {
		$patient_list = get_field("field_user_patient_list", "user_" . $this->current_user->ID);
		foreach ($patient_list as $key => $thePatient){
			$thePatient->patient_id = $thePatient->ID;
			$thePatient->id = $thePatient->ID;
			$thePatient->name = $thePatient->post_title;
		}
		$header["X-WP-Total"] = count($patient_list);
		return new WP_REST_Response( $patient_list, 200, $header);
	}
	
	public function get_tags($request, $args){
		$data = array();
		
		$params = $request->get_params();
		if(array_key_exists("id", $params)){
			return $this->get_tags_by_ids($request, $args);
		}
		
		if(taxonomy_exists("tags_" . $this->current_user->ID)){
			$args['taxonomy'] = "tags_" . $this->current_user->ID;
			$args['hide_empty'] = false;
			$items = get_terms($args);
// 			$items = get_tags(array('hide_empty' => false));
			foreach( $items as $item ) {
				$itemdata = $item;
				$itemdata->id = $itemdata->term_id;
				$data[] = $this->prepare_response_for_collection( $itemdata );
			}
		}else{
			register_taxonomy(
					"tags_" . $this->current_user->ID,
					Dietme_Post_Types_Examination::POST_TYPE,
					array(
							'label' => __( 'Tags ' . $this->current_user->display_name ),
							'rewrite' => array( 'slug' => "tags_" . $this->current_user->ID ),
							'hierarchical' => false,
					)
				);
		}
		return new WP_REST_Response( $data, 200, $header);
	}
	
	public function get_tags_by_ids($request, $args){
		$data = array();
		$params = $request->get_params();
		if(array_key_exists("id", $params)){
			$params["id"] = explode(",", $params["id"]);
			foreach ($params["id"] as $tag_id){
				$data[] = get_tag($tag_id);
			}
		}
		return new WP_REST_Response( $data, 200, $header);
	}
	
	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_patients( $request ) {
		$patient_list = get_field("field_user_patient_list", "user_" . $this->current_user->ID);
		$items = array(); //do a query, call another class, etc
		if($patient_list){
			$patients_ids = array();
			foreach ($patient_list as $key => $thePatient){
				$patients_ids[] = $thePatient->ID;
			}
			$args = array(
					"post_type" => Dietme_Post_Types_Patient::POST_TYPE,
					'include'  => $patients_ids
					
			);
			$args = $this->get_patient_args($request, $args);
			$the_query = new WP_Query($args);
// 			"recordsTotal": 57,
// 			"recordsFiltered": 57,
// 			$items= get_posts($args);
			$items = $the_query->get_posts();
		}
		$data = array();
		foreach( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );
			$data[] = $this->prepare_response_for_collection( $itemdata );
		}
		$header["X-WP-Total"] = count($data);
// 		$data = array("data" =>$data);
		$data["recordsTotal"] = $the_query->post_count;
		$data["recordsFiltered"] = $the_query->post_count;
// 		$data["draw"] = 1;
		
		
		return new WP_REST_Response( $data, 200, $header);
	}
	
	/**
	 * Get one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_patient( $request ) {
		//get parameters from request
		$params = $request->get_params();
		$item = get_post($params["id"]);
		$data = $this->prepare_item_for_response( $item, $request );
		
		//return a response or error based on some conditional
		if ( 1 == 1 ) {
			return new WP_REST_Response( $data, 200 );
		}else{
			return new WP_Error( 'code', __( 'message', 'text-domain' ) );
		}
	}
	
	/**
	 * Create one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function create_patient( $request ) {
		
		$item = $this->prepare_item_for_database( $request );
		if($item){
			$data = $this->create_patient_insert_post( $item );
			if ( !is_wp_error($data) ) {
				return new WP_REST_Response( $data, 200 );
			}
		}
		
		return new WP_Error( 'cant-create', __( 'message', 'text-domain'), array( 'status' => 500 ) );
		
		
	}
	
	/**
	 * Update one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function update_patient( $request ) {
		$item = $this->prepare_item_for_database( $request );
		if($item){
			$data = $this->update_patient_insert_post( $item );
			if ( !is_wp_error($data) ) {
				return new WP_REST_Response( $data, 200 );
			}
		}
		
		return new WP_Error( 'cant-update', __( 'message', 'text-domain'), array( 'status' => 500 ) );
		
	}
	
	/**
	 * Delete one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function delete_patient( $request ) {
		$params=$request->get_params();
		
		if(array_key_exists("id", $params)){
			$deleted = wp_delete_post($params["id"]);
			if (  $deleted  ) {
				return new WP_REST_Response( true, 200 );
			}
		}
		return new WP_Error( 'cant-delete', __( 'Errore nella cancellazione del paziente. Contatta un amministratore!', 'text-domain'), array( 'status' => 500 ) );
	}
	
	/**
	 * Check if a given request has access to get items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_items_permissions_check( $request ) {
		//return true; <--use to make readable by all
		return current_user_can( 'edit_something' );
	}
	
	/**
	 * Check if a given request has access to get a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function get_item_permissions_check( $request ) {
		return $this->get_items_permissions_check( $request );
	}
	
	/**
	 * Check if a given request has access to update a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function update_item_permissions_check( $request ) {
		return $this->create_item_permissions_check( $request );
	}
	
	/**
	 * Check if a given request has access to delete a specific item
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|bool
	 */
	public function delete_item_permissions_check( $request ) {
		return $this->create_item_permissions_check( $request );
	}
	
	/**
	 * Prepare the item for create or update operation
	 *Array
		(
		    [published_at] => 2017-07-15T10:14:59.196Z
		    [name] => Fabio
		    [surname] => Sirchia
		    [city] => Nogarole Rocca
		    [address] => Via IX Maggio 76D
		    [telephone] => 3493073868
		    [note] => <p>saadssadsadadsda</p>
		)
	 * @param WP_REST_Request $request Request object
	 * @return WP_Error|object $prepared_item
	 */
	protected function prepare_item_for_database( $request ) {
		$item = false;
		$params = $request->get_params();
		if(array_key_exists("name", $params) && $params["name"]){
			$item = Array
			(
					["published_at"] => "",
					["name"]         => "",
					["surname"]      => "",
					["city"]         => "",
					["address"]      => "",
					["telephone"]    => "",
					["note"]         => ""
			);
			$item["name"] = $params["name"];
			$item["post_title"] = $params["name"];
			if(array_key_exists("surname", $params)){
				$item["surname"] = $params["surname"];
				$item["post_title"] .= " ".$params["surname"];
			}
			if(array_key_exists("city", $params)){
				$item["city"] = $params["city"];
			}
			if(array_key_exists("address", $params)){
				$item["address"] = $params["address"];
			}
			if(array_key_exists("telephone", $params)){
				$item["telephone"] = $params["telephone"];
			}
			if(array_key_exists("note", $params)){
				$item["note"] = $params["note"];
			}
			if(array_key_exists("published_at", $params)){
				$item["published_at"] = $params["published_at"];
			}
			if(array_key_exists("id", $params)){
				$item["ID"] = $params["id"];
			}
// 			$item["post_title"] .= " - " . $this->current_user->display_name. " - " . $this->current_user->ID; 
		}
		
		return $item;
	}
	
	/**
	 * Prepare the item for the REST response
	 *
	 * @param mixed $item WordPress representation of the item.
	 * @param WP_REST_Request $request Request object.
	 * @return mixed
	 */
	public function prepare_item_for_response( $item, $request ) {
		$itemRes = array();
		$acf = get_fields($item->ID);
		$itemRes["id"]= $item->ID;
		$item->acf = $acf;
		$itemRes["wp"] = $item;
		foreach ($item->acf as $key => $value){
			$itemRes[$key] = $value;
		}
		return $itemRes;
	}
	
	/**
	 * Get the query params for collections
	 *
	 * @return array
	 */
	public function get_collection_params() {
		return array(
				'page'                   => array(
						'description'        => 'Current page of the collection.',
						'type'               => 'integer',
						'default'            => 1,
						'sanitize_callback'  => 'absint',
				),
				'per_page'               => array(
						'description'        => 'Maximum number of items to be returned in result set.',
						'type'               => 'integer',
						'default'            => 10,
						'sanitize_callback'  => 'absint',
				),
				'search'                 => array(
						'description'        => 'Limit results to those matching a string.',
						'type'               => 'string',
						'sanitize_callback'  => 'sanitize_text_field',
				),
		);
	}
	
}