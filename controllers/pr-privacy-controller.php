<?php

/**
 * Class for editing privacy
 */

class PR_Privacy {

	public $user_id;
	public $first_name;
	public $last_name;
	public $display_name;
	public $description;
	public $user_url;
	public $ref_sports;
	public $interests;
	public $other_sports;
	public $location;
	public $gender;
	public $birth_month;
	public $birth_year;
	public $birth_day;
	public $height;
	public $weight;
	public $year_started_running;
	public $facebook;
	public $twitter;
	public $instagram;
	public $age;

	public $show_name;
	public $show_gender;
	public $show_location;
	public $show_birthday;
	public $show_birthyear;
	public $show_year_started_running;
	public $show_age;
	public $show_interests;
	public $show_activities;
	public $show_personal_records;
	public $show_groups;
	public $show_total_distance;
	public $show_total_time;
	public $show_total_calories;
	public $enable_connection_approval;
	
	public $show_facebook;
	public $show_twitter;
	public $show_website;

	
	public function __construct() {

     	add_shortcode( 'pr_privacy', array( $this, 'render_privacy_page' ) );
    }

    public function render_privacy_page() {

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

    	return PR_Membership::get_html_template( 'privacy', $attributes );
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
