<?php

/**
 * Class for editing privacy
 */

class PR_Privacy {

	private $user_id;
	private $first_name;
	private $last_name;
	private $display_name;
	private $description;
	private $user_url;
	private $ref_sports;
	private $interests;
	private $other_sports;
	private $location;
	private $gender;
	private $birth_month;
	private $birth_year;
	private $birth_day;
	private $height;
	private $weight;
	private $year_started_running;
	private $facebook;
	private $twitter;
	private $instagram;
	private $age;

	private $show_name;
	private $show_gender;
	private $show_location;
	private $show_birthday;
	private $show_birthyear;
	private $show_year_started_running;
	private $show_height;
	private $show_weight;
	private $show_age;
	private $show_interests;
	private $show_activities;
	private $show_personal_records;
	private $show_groups;
	private $show_total_distance;
	private $show_total_time;
	private $show_total_calories;
	private $enable_connection_approval;
	
	private $show_facebook;
	private $show_twitter;
	private $show_website;
	private $show_instagram;

	
	function __construct() {

     	add_shortcode( 'pr_privacy', array( $this, 'render_privacy_page' ) );
    }

    function render_privacy_page() {

    	require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
		$model = new Members_Model;

    	$attributes = array('errors'=>array(),'success'=>false);

    	if ( is_user_logged_in() ) {

    		// get current user id
    		$this->user_id = get_current_user_id();

    		if( isset( $_POST['submit'] ) && $_POST['submit'] == 'save' ) {
    			
    			if( isset( $_POST['privacy']) ) {
    				$this->update_privacy( $_POST['privacy'] );
    			}
    			
    		}

    		// get current user's metada
    		$meta = get_user_meta( $this->user_id );
			
			$userdata = get_userdata( $this->user_id );
			$this->user_url = $userdata->user_url;
			$this->display_name = $userdata->display_name;
			$this->ref_sports = $model->get_other_sports();

    		//get only meta keys with value
			$meta = array_filter( array_map( function( $a ) {
				return $a[0];
			}, $meta ) );

			$member_meta = $model->get_member_meta();

			foreach( $member_meta as $key ) {

				if( isset( $meta[$key]) ) {

					$this->$key = $meta[$key];

					if( $key == 'interests')
						$this->interests = unserialize( $this->interests );

					if( $key == 'gender' )
						$this->gender == 1 ? $this->gender = 'Male' : $this->gender = 'Female';

					if( $key == 'birth_month') 
						$this->birth_month = date('F', mktime(0, 0, 0, $this->birth_month, $this->birth_day));

					$this->age = PR_Membership::compute_age( $this->birth_month, $this->birth_day, $this->birth_year );
				}

			}


			/* Privacy Check */

			$meta_keys = $model->get_privacy_meta();

			foreach( $meta_keys as $key ) {
				
				if( isset( $meta[$key] ))
					$this->$key = $meta[$key];
				
			}

    	}

    	require_once( dirname( __DIR__ ) . '/views/privacy.php' );
    }

    
	private function update_privacy( $post ) {
	
		require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
		$model = new Members_Model;

		foreach( $post as $key => $val ) {

			if ( isset( $post[$key]) ) {

				if( metadata_exists( 'user', $this->user_id, $key ) ) {
						
					$result = update_user_meta( $this->user_id, $key, $val, get_user_meta( $this->user_id, $key, true ));

				} else {
					
					$result = add_user_meta( $this->user_id, $key, $val, true );

				}

			}	

			$updated_meta[] = $key;		
			
		}

		$meta = $model->get_privacy_meta();

		foreach( $meta as $key ) {
			if( !in_array($key, $updated_meta) ) {
				$result = update_user_meta( $this->user_id, $key, 0, get_user_meta( $this->user_id, $key, true ));
			}
		}

		if( !is_wp_error( $result )) {
			return true;
		} else {
			return false;
		}
		
	}

}
