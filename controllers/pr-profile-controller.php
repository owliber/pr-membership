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
	private $profile;
	private $activities;
	private $statistics;
	
	function __construct() {

		add_shortcode('pr_profile', array( $this, 'render_profile') );
		add_action( 'wp_ajax_connect_request', array( $this, 'connect_request' ));
		add_action( 'wp_ajax_nopriv_connect_request', array( $this, 'connect_request' ));
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_ajax_script' ));
		add_action(	'wp_head', array( $this, 'load_profile_background' ));

	}

	function enqueue_ajax_script() {
	  wp_enqueue_script( 'ajax-profile-js', plugins_url(PR_Membership::PLUGIN_FOLDER  . '/js/ajax-profile.js'), array('jquery'), '1.0.0', true );
	  wp_localize_script( 'ajax-profile-js', 'AjaxConnect', array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'security' => wp_create_nonce( 'pr-connect-request' )
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

	function render_profile() {

		$this->user_id = get_current_user_id();
		$this->member_id = $this->get_MID();
		$this->load_profile_background();
		$profile = get_userdata( $this->member_id );
		
		require_once( dirname( __DIR__ ) . '/views/profile.php' );
		
	}


	function get_MID() {

		$URI = $_SERVER['REQUEST_URI'];		 		
		$member = str_replace('/', '', str_replace('member','', $URI));
		
		$profile = null;

		if ( username_exists( $member ) ) {
			$profile = get_user_by( 'login', $member );
		}

		return $profile->ID;

	}

	function has_pending_request() {

		require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
		$model = new Profile_Model;
		$model->member_id = $this->member_id;
		$model->user_id = $this->user_id;

		if( $model->get_request_status() )
			return true;
		else
			return false;

	}

	function is_connected() {

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

	function add_activity( $data ) {

		if ( isset( $postdata ) ) {

			$post = $_POST['activity'];

			$post_data = array(
				$this->member_id,
				sanitize_text_field( $post['activity_name'] ),
				sanitize_text_field( $post['activity_type'] ),
				sanitize_text_field( $post['activity_date'] ),
				(int) sanitize_text_field( $post['distance'] ),
				sanitize_text_field( $post['total_time'] ),
				sanitize_text_field( $post['average_pace'] ),
				(int) sanitize_text_field( $post['calories'] ),
				(int) sanitize_text_field( $post['elev_gain'] ),
				sanitize_text_field( $post['notes'] ),
			);

			$success = $model->add_activity( $post_data );

			if ( $success ) {
				$result = '<div class="ui large green icon message fade">
							 <i class="checkmark icon"></i>
								 <div class="content">
								  <h3>Your new activity was successfully added!</h3>
								</div>
							</div>';
			} else {
				$result = '<div class="ui large red icon message">
							<i class="bug icon"></i>
							<div class="content">
							  <h3>Sorry, your new activity is not added!</h3>
							</div>
						  </div>';
			}

			return $result;
			
		}
	}

	function load_profile_background() {

	  	$image_file = get_user_meta( $this->member_id, 'pr_member_background_image', true );
		$profile_background = PROFILE_URL . $image_file;

		$background = '<style type="text/css" id="custom-background-css-override">
	        	body { 
	        		background: url(' . $profile_background . ') no-repeat center center fixed; 
	  				-webkit-background-size: cover;
	  				-moz-background-size: cover;
	  				-o-background-size: cover;
	  				background-size: cover; 
	        	}
	    	</style>';

	   	echo $background;
	}

}
