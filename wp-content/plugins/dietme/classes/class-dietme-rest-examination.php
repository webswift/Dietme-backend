<?php

class Dietme_Rest_Examination extends Dietme_Rest_Api_Base{
	
	public function init_register_routes(){
		$this->register_routes();
	}
	
	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version = '1';
		$namespace = 'dm/v' . $version;
		$base = 'examination';
		
		register_rest_route( $namespace, '/' . $base, array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_examinations' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => array(
								
						),
				),
				array(
						'methods'         => WP_REST_Server::CREATABLE,
						'callback'        => array( $this, 'create_examination' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				),
		) );
		
		register_rest_route( $namespace, '/' . $base . '/(?P<id>[\d]+)', array(
				array(
						'methods'         => WP_REST_Server::READABLE,
						'callback'        => array( $this, 'get_examination' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				),
				array(
						'methods'         => WP_REST_Server::EDITABLE,
						'callback'        => array( $this, 'update_examination' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				),
				array(
						'methods'         => WP_REST_Server::DELETABLE,
						'callback'        => array( $this, 'delete_examination' ),
						'permission_callback' => array( $this, 'create_item_permissions_check' ),
						'args'            => $this->get_endpoint_args_for_item_schema( true ),
				)
		) );
	}
	
	public function get_examination_args($request, $args){
		$params = $this->get_params($request);
		if(array_key_exists("_sort", $params) && array_key_exists("_order", $params)){
			if($params["_sort"] != "id"){
				$args["meta_key"] = $params["_sort"];
				$args["order"] = $params['_order'];
				$args["orderby"] = 'meta_value';
			}
		}
		return $args;
	}
	
	
	public function create_examination_insert_post($item){
		// Create post object
		$my_examination = array(
				'post_title'    => wp_strip_all_tags( $item['post_title'] ),
				'post_content'  => $item['post_title'],
				'post_status'   => 'publish',
				'post_author'   => $this->current_user->ID,
				'post_date'     => $item["published_at"],
				'post_type'     => Dietme_Post_Types_Examination::POST_TYPE
		);
		
		// Insert the post into the database
		$theExamination = wp_insert_post( $my_examination );
		if(!is_wp_error($theExamination)){
// 			$examination_patient = get_post($item["patient_id"]);
			update_field('field_examination_patient'          ,array($item["examination_patient"]), $theExamination);
			update_field('field_examination_published_at'          ,$item["published_at_yymmdd"], $theExamination);
		}
		return get_post($theExamination);
	}
	
	public function update_examination_insert_post($item){
		// Create post object
		$my_examination = array(
				'ID'            => $item['ID'],
				'post_title'    => wp_strip_all_tags( $item['post_title'] ),
				'post_content'  => $item['post_title'],
				'post_status'   => 'publish',
				'post_author'   => $this->current_user->ID,
				'post_date'     => $item["published_at"],
				'post_type'     => Dietme_Post_Types_Examination::POST_TYPE
		);
		
		// Insert the post into the database
		$theExamination= wp_update_post( $my_examination );
		if(!is_wp_error($theExamination)){
			// 			$examination_patient = get_post($item["patient_id"]);
			update_field('field_examination_patient'          ,array($item["examination_patient"]), $theExamination);
			update_field('field_examination_published_at'          ,$item["published_at_yymmdd"], $theExamination);
			wp_set_post_terms($item['ID'], $item["tags"], "tags_" . $this->current_user->ID);
		}
		return $theExamination;
	}
	
	/**
	 * Get a collection of items
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_examinations( $request ) {
		$args = array(
				'author'        =>  $this->current_user->ID,
				'orderby'       =>  'post_date',
				'order'         =>  'ASC',
				"post_type" => Dietme_Post_Types_Examination::POST_TYPE,
		);
		$items = array(); //do a query, call another class, etc
		$args = $this->get_examination_args($request, $args);
		$items= get_posts($args);
		$data = array();
		foreach( $items as $item ) {
			$itemdata = $this->prepare_item_for_response( $item, $request );
			$data[] = $this->prepare_response_for_collection( $itemdata );
		}
		$header["X-WP-Total"] = count($data);
		return new WP_REST_Response( $data, 200, $header);
	}
	
	/**
	 * Get one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_examination( $request ) {
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
	public function create_examination( $request ) {
		
		$item = $this->prepare_item_for_database( $request );
		if($item){
			$data = $this->create_examination_insert_post( $item );
			if ( !is_wp_error($data) ) {
				$data = $this->prepare_item_for_response($data, $request);
				return new WP_REST_Response( $data, 200 );
// 				return new WP_REST_Response( array("message" => __( 'Visita creata con successo', 'text-domain')), 200 );
			}
		}
		
		return new WP_Error( 'cant-create', __( 'Attenzione: per creare una nuova visita devi selezionare un paziente.', 'text-domain'), array( 'status' => 500 ) );
		
		
	}
	
	/**
	 * Update one item from the collection
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_Error|WP_REST_Request
	 */
	public function update_examination( $request ) {
		$item = $this->prepare_item_for_database( $request );
		if($item){
			$data = $this->update_examination_insert_post( $item );
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
	public function delete_examination( $request ) {
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
		if(array_key_exists("examination_patient", $params) && $params["examination_patient"]){
			$item = Array
			(
					["published_at"] => "",
					["examination_patient"]         => "",
			);
			$patient = get_post($params["examination_patient"]);
			$item["post_title"] = $this->current_user->display_name . " - " . $patient->post_title;
			$item["examination_patient"] = $params["examination_patient"];
			if(array_key_exists("published_at", $params)){
				$published_at = new DateTime($params["published_at"]);
				$item["published_at"] = $params["published_at"];
				$item["published_at_yymmdd"] = $published_at->format("Ymd");
				$item["post_title"] .= " - " . $params["published_at"];
			}
			if(array_key_exists("tags", $params)){
				foreach ($params["tags"] as $tag){
					if(!term_exists($tag, "tags_" . $this->current_user->ID)){
						$thetag = wp_insert_term($tag, "tags_" . $this->current_user->ID);
					}else{
						if(is_integer($tag)){
							$termBy = "term_id";
						}else{
							$termBy = "slug";
						}
						$thetag = get_term_by($termBy, $tag, "tags_" . $this->current_user->ID);
					}
					if(!is_wp_error($thetag)){
						if(!is_object($thetag)){
							$thetag = get_term($thetag["term_id"]);
						}
						$item["tags"][] = $thetag->term_id;
					}
				}
			}
			if(array_key_exists("id", $params)){
				$item["ID"] = $params["id"];
			}
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
			if($key == "examination_patient"){
				$itemRes[$key] = current($value);
				$examination_patient = get_post(current($value));
				$itemRes["name"] = $examination_patient->post_title;
			}
			if($key == "published_at"){
				$publised_at = new DateTime($value);
				$itemRes[$key] = $publised_at->format("Y-m-d");
			}
		}
		
		if(taxonomy_exists("tags_" . $this->current_user->ID)){
			$args['taxonomy'] = "tags_" . $this->current_user->ID;
			$items = wp_get_post_terms($item->ID, $args['taxonomy']);
// 			$items = get_terms($args);
			foreach( $items as $item ) {
// 				$itemdata = $item;
// 				$itemdata->id = $itemdata->term_id;
				$itemRes["tag_ids"][] = $item->term_id;
// 				$itemRes["tags_names"][] = array("id"=>$item->term_id,"data"=> array("name" => $item->name));
			}
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