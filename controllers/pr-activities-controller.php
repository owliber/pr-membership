<?php

/**
 * Controller for connections
 * 
 */

class PR_Activities {

	public $connections;
	
	public function __construct() {

		add_shortcode( 'pr_activities', array( $this, 'render_activities' ) );
		add_action( 'init', array( $this, 'enqueue_ajax_actions') );
		
	}

	function enqueue_ajax_actions() {
		if( is_user_logged_in() ) :
			add_action( 'wp_ajax_get_record_details', array( $this, 'get_record_details' ));
			add_action( 'wp_ajax_nopriv_get_record_details', array( $this, 'get_record_details' ));
			add_action( 'wp_ajax_delete_record', array( $this, 'delete_record' ));
			add_action( 'wp_ajax_nopriv_delete_record', array( $this, 'delete_record' ));
			add_action( 'wp_ajax_update_record', array( $this, 'update_record' ));
			add_action( 'wp_ajax_nopriv_update_record', array( $this, 'update_record' ));
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

	// function get_record_details() {

	// 	if( is_user_logged_in() ) {

	// 		if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
	// 			&& ( isset( $_POST['activity_id'] ) && !empty( $_POST['activity_id'] ))
	// 			&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
	// 		) {

	// 			check_ajax_referer( 'pr-get-record-details', 'security' );
	// 			$activity_id = intval( $_POST['activity_id'] ); //whom the request is for
	// 			$activity_name = "";

	// 			require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
	// 			$model = new Profile_Model;
	// 			$model->activity_id = $activity_id;
	// 			$model->user_id = get_current_user_id();
	// 			$result = $model->get_activity_details();
				
	// 			if ( $result !== false ) {
	// 				$activity_name = $result->activity_name;
	// 				$activity_location = $result->location;
	// 				$activity_type = $result->activity_type;
	// 				$activity_date = date('Y-m-d',strtotime( $result->activity_date ));
	// 				$distance = $result->distance;
	// 				$bibnumber = $result->bibnumber;
	// 				$total_time = $result->total_time;
	// 				$time = explode(':', $total_time);
	// 				$hour_part = $time[0];
	// 				$min_part = $time[1];
	// 				//$pace = $result->average_pace;
	// 				$pace_part = explode(':', $result->average_pace);
	// 				$pace_hour_part = $pace_part[0];
	// 				$pace_min_part = $pace_part[1];
	// 				$pace_secs_part = $pace_part[2];
	// 				$pace = implode(':', array( $pace_min_part, $pace_secs_part ) );
	// 				$notes = $result->notes;
	// 			}

	// 			wp_send_json( array( 
	// 				'activity_id' => $activity_id, 
	// 				'activity_name' => $activity_name,
	// 				'activity_location' => $activity_location, 
	// 				'activity_type' => $activity_type,
	// 				'activity_date' => $activity_date,
	// 				'distance' => $distance,
	// 				'bibnumber' => $bibnumber,
	// 				'total_hour' => $hour_part,
	// 				'total_min' => $min_part,
	// 				'pace' => $pace,
	// 				'notes' => $notes
	// 			) );

	// 			wp_die();
	// 		}
	// 	}

	// }

	// function delete_record() {

	// 	if( is_user_logged_in() ) {

	// 		if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
	// 			&& ( isset( $_POST['activity_id'] ) && !empty( $_POST['activity_id'] ))
	// 			&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
	// 		) {

	// 			check_ajax_referer( 'pr-delete-record', 'security' );
	// 			$activity_id = intval( $_POST['activity_id'] ); //whom the request is for

	// 			require_once( WPPR_PLUGIN_DIR . '/models/profile-model.php' );
	// 			$model = new Profile_Model;
	// 			$model->activity_id = $activity_id;
	// 			$model->user_id = get_current_user_id();
	// 			$result = $model->delete_activity();
				
	// 			if ( $result ) {
	// 				$result_code = 0;
	// 				$result_msg = 'The record was successfully deleted.';
	// 			} else {
	// 				$result_code = 1;
	// 				$result_msg = 'Something went wrong, please try again later.';
	// 			}

	// 			wp_send_json( array( 
	// 				'activity_id' => $activity_id,
	// 				'result_code'=> $result_code, 
	// 				'result_msg'=> $result_msg, 
	// 			) );

	// 			wp_die();
	// 		}
	// 	}

	// }

	function render_activities() {

    	if( is_user_logged_in() ) {

            $this->member_id = get_current_user_id();

            if ( isset( $_POST['activity'] ) ) :            	

            	if ( isset( $_POST['activity']['activity_id'] ) && !empty( $_POST['activity']['activity_id'] ) )
            		$result = $this->add_activity( $_POST['activity'], false );
            	else
                	$result = $this->add_activity( $_POST['activity'] );

            endif;

            require_once( dirname( __DIR__ ) . '/views/activities.php' );

    	} else {

    		//redirect to login page
    		$url = home_url();
    		PR_Membership::pr_redirect( $url );

    	}

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
					sanitize_text_field( $post['location'] ),					
					sanitize_text_field( $post['activity_date'] ),
					floatval( sanitize_text_field( $post['distance'] ) ),
					$total_time,
					$pace_per_km,
					sanitize_text_field( $post['bibnumber'] ),
					sanitize_text_field( $post['notes'] ),
				);

				$result = $model->insert( $post_data );

				if ( $result ) {
					$result_msg = '<div class="ui medium success icon message fade">
								 <i class="checkmark icon"></i>
									 <div class="content">
									  <h3>Your new activity was successfully added!</h3>
									</div>
								</div>';
				} else {
					$result_msg = '<div class="ui medium error icon message">
								<i class="bug icon"></i>
								<div class="content">
								  <h3>Something went wrong, please try again later.</h3>
								</div>
							  </div>';
				}

			else :

				$post_data = array(
					sanitize_text_field( $post['activity_name'] ),
					sanitize_text_field( $post['location'] ),
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

				$result = $model->update( $post_data );

				if ( $result ) {
					$result_msg = '<div class="ui medium success icon message fade">
								 <i class="checkmark icon"></i>
									 <div class="content">
									  <h3>Your activity was successfully updated!</h3>
									</div>
								</div>';
				} else {
					$result_msg = '<div class="ui medium error icon message">
								<i class="bug icon"></i>
								<div class="content">
								  <h3>Something went wrong, please try again later.</h3>
								</div>
							  </div>';
				}

			endif;

			
			return $result_msg;
			
		}
	}


}