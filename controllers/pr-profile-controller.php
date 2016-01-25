<?php

/**
 * Class controller for viewing member details in site frontpage
 * @author  owliber@yahoo.com
 * @version 1.0
 */

class PR_Profile {

	const DEFAULT_HEADLINE_COLOR = 'green';
	const DEFAULT_HEADLINE_POSITION = 'left';

	private $user_id;
	private $member_id;
	private $username;
	private $activities;
	private $statistics;
	private $headline_position;
	private $headline_color;
	private $age;
	
	function __construct() {

		add_shortcode('pr_profile', array( $this, 'render_profile') );

		
		add_action( 'wp_head', array( $this, 'set_profile_bg'), 1, 3 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_ajax_script' ));
		add_action( 'wp_ajax_connect_request', array( $this, 'connect_request' ));
		add_action( 'wp_ajax_nopriv_connect_request', array( $this, 'connect_request' ));
		add_action( 'wp_ajax_get_record_details', array( $this, 'get_record_details' ));
		add_action( 'wp_ajax_nopriv_get_record_details', array( $this, 'get_record_details' ));
		add_action( 'wp_ajax_delete_record', array( $this, 'delete_record' ));
		add_action( 'wp_ajax_nopriv_delete_record', array( $this, 'delete_record' ));
		add_action( 'wp_ajax_update_record', array( $this, 'update_record' ));
		add_action( 'wp_ajax_nopriv_update_record', array( $this, 'update_record' ));

	}

	function set_profile_bg() {

		if ( is_author() ) :
			$this->member_id = $this->get_MID();
			$this->load_profile_background();
		endif;

	}

	function enqueue_ajax_script() {
	  wp_enqueue_script( 'ajax-profile-js', plugins_url(PR_Membership::PLUGIN_FOLDER  . '/js/ajax-profile.js'), array('jquery'), '1.0.0', true );
	  wp_localize_script( 'ajax-profile-js', 'AjaxConnect', array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'security' => wp_create_nonce( 'pr-connect-request' ),
	  ));
	  wp_localize_script( 'ajax-profile-js', 'AjaxGetDetails', array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'security' => wp_create_nonce( 'pr-get-record-details' )
	  ));
	  wp_localize_script( 'ajax-profile-js', 'AjaxDelete', array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'security' => wp_create_nonce( 'pr-delete-record' )
	  ));
	  wp_localize_script( 'ajax-profile-js', 'AjaxUpdate', array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'security' => wp_create_nonce( 'pr-update-record' )
	  ));
	}

	function connect_request() {

	  $total_connections = 0;
	  $privacy = 0;

	  if( is_user_logged_in() ) {

	  	if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
				&& ( isset( $_POST['member_id'] ) && !empty( $_POST['member_id'] ))
				&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
			) {

			check_ajax_referer( 'pr-connect-request', 'security' );
			$this->member_id = intval( $_POST['member_id'] ); //whom the request is for
			$this->user_id = get_current_user_id();

			if ( ! $this->member_id ) {
				
				$result_code = 1;
				$result_msg = 'invalid user or parameters';

			} else {
				
				//validate member if valid
				if ( PR_Membership::is_valid_user( $this->member_id )) {		

					require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
					$model = new Profile_Model;			
					
					$privacy = $model->check_connection_privacy();

					$model->user_id = $this->user_id;
					$model->member_id = $this->member_id;

					if( $privacy == 1 ) {
    					$result = $model->add_request();
    				} else {
    					$result = $model->process_connection();
    				}

    				$total_connections = get_user_meta( $this->member_id, 'total_connections', true );    					

					if( !is_wp_error( $result )) {
						$result_code = 0;
						$result_msg = 'success';
					}

				} else {

					$result_code = 2;
					$result_msg = 'Oops, the user is invalid';

				} 
				
			}

		} else {

			$result_code = 3;
			$result_msg = 'The request is invalid';
			
		}

	  } else {

	  	$result_code = 4;
		$result_msg = 'You are not logged in.';
	  
	  }
	  
		wp_send_json( array( 
			'result_code'=>$result_code, 
			'result_msg'=>$result_msg, 
			'total_connections'=>$total_connections, 
			'privacy_setting'=>$privacy 
		) );

		wp_die(); 
	
	}

	function get_record_details() {

		if( is_user_logged_in() ) {

			if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
				&& ( isset( $_POST['activity_id'] ) && !empty( $_POST['activity_id'] ))
				&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
			) {

				check_ajax_referer( 'pr-get-record-details', 'security' );
				$activity_id = intval( $_POST['activity_id'] ); //whom the request is for
				$activity_name = "";

				require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
				$model = new Profile_Model;
				$model->activity_id = $activity_id;
				$model->user_id = get_current_user_id();
				$result = $model->get_activity_details();
				
				if ( $result !== false ) {
					$activity_name = $result->activity_name;
					$activity_type = $result->activity_type;
					$activity_date = date('Y-m-d',strtotime( $result->activity_date ));
					$distance = $result->distance;
					$bibnumber = $result->bibnumber;
					$total_time = $result->total_time;
					$time = explode(':', $total_time);
					$hour_part = $time[0];
					$min_part = $time[1];
					$pace = $result->average_pace;
					$notes = $result->notes;
				}

				wp_send_json( array( 
					'activity_id' => $activity_id, 
					'activity_name' => $activity_name, 
					'activity_type' => $activity_type,
					'activity_date' => $activity_date,
					'distance' => $distance,
					'bibnumber' => $bibnumber,
					'total_hour' => $hour_part,
					'total_min' => $min_part,
					'pace' => $pace,
					'notes' => $notes
				) );

				wp_die();
			}
		}

	}

	function delete_record() {

		if( is_user_logged_in() ) {

			if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
				&& ( isset( $_POST['activity_id'] ) && !empty( $_POST['activity_id'] ))
				&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
			) {

				check_ajax_referer( 'pr-delete-record', 'security' );
				$activity_id = intval( $_POST['activity_id'] ); //whom the request is for

				require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
				$model = new Profile_Model;
				$model->activity_id = $activity_id;
				$model->user_id = get_current_user_id();
				$result = $model->delete_activity();
				
				if ( $result ) {
					$result_code = 0;
					$result_msg = 'The record was successfully deleted.';
				} else {
					$result_code = 1;
					$result_msg = 'Something went wrong, please try again later.';
				}

				wp_send_json( array( 
					'activity_id' => $activity_id,
					'result_code'=> $result_code, 
					'result_msg'=> $result_msg, 
				) );

				wp_die();
			}
		}

	}

	function render_profile() {

		if( is_author() ) :

			$this->user_id = get_current_user_id();
			$this->member_id = $this->get_MID();
			//$this->load_profile_background();
			$headline_position = get_user_meta( $this->member_id, 'pr_member_headline_position', true );
			$headline_color = get_user_meta( $this->member_id, 'pr_member_headline_color', true );

			$month = get_user_meta( $this->member_id, 'birth_month', true );
			$day = get_user_meta( $this->member_id, 'birth_day', true );
			$year = get_user_meta( $this->member_id, 'birth_year', true );

			$this->age = PR_Membership::compute_age($month, $day, $year);
			
			if( ! isset( $headline_position  ) && empty( $headline_position ) )
				$this->headline_position = self::DEFAULT_HEADLINE_POSITION;
			else
				$this->headline_position = $headline_position;

			if( ! isset( $headline_color ) && empty( $headline_color ))
				$this->headline_color = self::DEFAULT_HEADLINE_COLOR;
			else
				$this->headline_color = $headline_color;

			if ( isset( $_POST['activity'] ) ) :

				$result = $this->add_activity( $_POST['activity'] );

			endif;
			
			require_once( dirname( __DIR__ ) . '/views/profile.php' );
			
		endif;
		
	}

	function get_MID() {

		$URI = $_SERVER['REQUEST_URI'];		 		
		$member = str_replace('/', '', str_replace('member','', $URI));
		
		$profile = null;

		if ( username_exists( $member ) ) {
			$profile = get_user_by( 'login', $member );

			return $profile->ID;
		}

		return false;

	}

	public function has_pending_request() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;

		$model->member_id = $this->get_MID();
		$model->user_id = $this->user_id;

		if( $model->get_request_status() )
			return true;
		else
			return false;

	}

	public function is_connected() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;
		$model->member_id = $this->member_id;
		$model->user_id = $this->user_id;

		if( $model->check_connection_status() )
			return true;
		else
			return false;

	}

	function is_public( $key ) {

		if( metadata_exists( 'user', $this->member_id, $key ) ) {
			$meta = get_user_meta( $this->member_id, $key, true );

			if( $meta == 1 )
				return true;
		}

		return false;

	}


	function statistic() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;
		$model->member_id = $this->member_id;

		$statistics = $model->get_statistics();

		return $statistics;
	}

	function total_races() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;
		$model->member_id = $this->member_id;

		// Get run count
		$races_joined = $model->get_run_count( 'race' );
		return $races_joined;

	}

	function activities( $limit = false ) {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;		
		$model->member_id = $this->member_id;

		return $model->get_all_activities( $limit );
		
	}

	function farthest_distance() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;
		$model->member_id = $this->member_id;
		$result = $model->get_farthest_distance();
		return $result->max_distance;
	}

	function fastest_pace() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;
		$model->member_id = $this->member_id;
		$result = $model->get_fastest_pace();
		return $result->best_pace;
	}

	function add_activity( $post, $is_new = true ) {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;

		if ( isset( $post ) ) {

			//concat total hour and minutes
			$hour_part = sanitize_text_field( $post['total_hour'] );
			$minute_part = sanitize_text_field( $post['total_minute'] );
			$total_time = date('H:i', mktime($hour_part, $minute_part, 0, 0, 0 ));
			$pace = explode(':', sanitize_text_field( $post['average_pace'] ));
			$pace_min = $pace[0];
			$pace_secs = $pace[1];
			$pace_per_km = date('H:i:s', mktime(0, $pace_min, $pace_secs, 0, 0, 0 ));

			if ( $is_new ) :

				$post_data = array(
					$this->member_id,
					sanitize_text_field( $post['activity_name'] ),
					sanitize_text_field( $post['activity_type'] ),
					sanitize_text_field( $post['activity_date'] ),
					floatval( sanitize_text_field( $post['distance'] ) ),
					$total_time,
					$pace_per_km,
					sanitize_text_field( $post['bibnumber'] ),
					sanitize_text_field( $post['notes'] ),
				);

				$success = $model->insert( $post_data );

				if ( $success ) {
					$result = '<div class="ui medium success icon message fade">
								 <i class="checkmark icon"></i>
									 <div class="content">
									  <h3>Your new activity was successfully added!</h3>
									</div>
								</div>';
				} else {
					$result = '<div class="ui medium error icon message">
								<i class="bug icon"></i>
								<div class="content">
								  <h3>Something went wrong, please try again later.</h3>
								</div>
							  </div>';
				}

			else :

				$post_data = array(
					sanitize_text_field( $post['activity_name'] ),
					sanitize_text_field( $post['activity_type'] ),
					sanitize_text_field( $post['activity_date'] ),
					floatval( sanitize_text_field( $post['distance'] ) ),
					$total_time,
					$pace_per_km,
					sanitize_text_field( $post['bibnumber'] ),
					sanitize_text_field( $post['notes'] ),
					$this->member_id,
					intval( sanitize_text_field( $post['activity_id'] )),
				);

				$success = $model->update( $post_data );

				if ( $success ) {
					$result = '<div class="ui medium success icon message fade">
								 <i class="checkmark icon"></i>
									 <div class="content">
									  <h3>Your activity was successfully updated!</h3>
									</div>
								</div>';
				} else {
					$result = '<div class="ui medium error icon message">
								<i class="bug icon"></i>
								<div class="content">
								  <h3>Something went wrong, please try again later.</h3>
								</div>
							  </div>';
				}

			endif;

			
			return $result;
			
		}
	}

	function load_profile_background() {

	  	$image_file = get_user_meta( $this->member_id, 'pr_member_background_image', true );
		
		if( empty( $image_file ))
			$image_file = 'default.jpg';

		$profile_background = PROFILE_URL . $image_file;

		$background = '<style type="text/css" id="prbg">
	        	body { 
	        		background: url(' . $profile_background . ') no-repeat center center fixed  !important;
	        		-webkit-background-size: cover !important;
	  				-moz-background-size: cover !important;
	  				-o-background-size: cover !important;
	  				background-size: cover !important; 
	  				
	        	}
	    	</style>';

	   	echo $background;
	}

}
