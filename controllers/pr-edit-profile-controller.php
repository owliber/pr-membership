<?php

/**
 * Class for editing profile
 */

class PR_Edit_Profile {

	private $user_id;
	private $first_name;
	private $last_name;
	private $display_name;
	private $description;
	private $user_url;
	private $ref_sports;
	private $ref_interests;
	private $interests;
	private $other_sports;
	private $location;
	private $gender;
	private $birth_day;
	private $birth_month;
	private $birth_year;
	private $year_started_running;
	private $facebook;
	private $twitter;
	private $instagram;
	private $months;
	private $height;
	private $weight;

	private $cur_year;

	
	function __construct() {

		add_shortcode('pr_edit_profile', array($this, 'render_edit_profile'));

	}

	function render_edit_profile() {

		require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
		$model = new Members_Model;

		if( is_user_logged_in() ) {

			$this->user_id = get_current_user_id();
			
			if( isset ( $_POST['profile'] ))
				$this->update( $_POST['profile'] );
			
			$meta = get_user_meta( $this->user_id );

			//Filter out empty meta data
			$meta = array_filter( array_map( function( $a ) {
				return $a[0];
			}, $meta ) );

			if ( isset( $meta['first_name'] )) {
				$this->first_name = $meta['first_name'];
			}

			if ( isset( $meta['last_name'] )) {
				$this->last_name = $meta['last_name'];
			}
			
			if ( isset( $meta['description'] )) {
				$this->description = $meta['description'];
			}

			$userdata = get_userdata( $this->user_id );
			$this->user_url = $userdata->user_url;
			$this->display_name = $userdata->display_name;

			$this->ref_sports = $model->get_other_sports();

			if( isset( $meta['other_sports'] )) {
				$this->other_sports = $meta['other_sports'];
				$this->other_sports = unserialize($this->other_sports);
			}

			$this->ref_interests = $model->get_interest_lists();
			
			if( isset( $meta['interests'] )) {
				$this->interests = $meta['interests'];
				$this->interests = unserialize($this->interests);
			}

			if( isset( $meta['location'] )) {
				$this->location = $meta['location'];
			}

			if( isset( $meta['gender'] )) {
				$this->gender = $meta['gender'];
			}

			if( isset( $meta['birth_day'] )) {
				$this->birth_day = $meta['birth_day'];
			}

			if( isset( $meta['birth_month'] )) {
				$this->birth_month = $meta['birth_month'];
			}

			$this->months = array(
				'1' => 'January',
				'2' => 'February',
				'3' => 'March',
				'4' => 'April',
				'5' => 'May',
				'6' => 'June',
				'7' => 'July',
				'8' => 'August',
				'9' => 'September',
				'10' => 'October',
				'11' => 'November',
				'12' => 'December'
			);

			if( isset( $meta['birth_year'] )) {
				$this->birth_year = $meta['birth_year'];
			}

			if( isset( $meta['height'] )) {
				$this->height = $meta['height'];
			}

			if( isset( $meta['weight'] )) {
				$this->weight = $meta['weight'];
			}

			if( isset( $meta['year_started_running'] )) {
				$this->year_started_running = $meta['year_started_running'];
			}

			if( isset( $meta['facebook'] )) {
				$this->facebook = $meta['facebook'];
			}

			if( isset( $meta['twitter'] )) {
				$this->twitter = $meta['twitter'];
			}

			if( isset( $meta['instagram'] )) {
				$this->instagram = $meta['instagram'];
			}	

			require_once( dirname( __DIR__ ) . '/views/edit-profile.php' );
			//return PR_Membership::get_html_template( 'edit-profile', $attributes ); 

		} else {

			redirect_to_home();

		}

	}

	private function update( $post ) {

		require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
		$model = new Members_Model;

		if( isset ( $post )) {

			$valid = $this->validate_profile( $_POST['profile'], $this->user_id );

			if( is_wp_error( $valid )) {

				$errors[] = $valid->get_error_message();
				$result['errors'] = $errors;

			} else {

				$model->user_id = $this->user_id;
				$model->profile = $post;
				$success = $model->update_profile();	

				if( !is_wp_error( $success ) ) {
					$result['success'] = true; 
				} else {
					$result['errors'] = $success->get_error_message();
				}
			}

			wp_reset_postdata();

			return $result;

		}
	}

	private function validate_profile( $profile, $user_id ) {


		if( empty( $profile['first_name'] )) {
			return new WP_Error('empty_firstname', __( ' Please enter your first name! ', 'pr-profile'));	
		}

		if( empty( $profile['last_name'] )) {
			return new WP_Error('empty_lastname', __( ' Please enter your last name! ', 'pr-profile'));	
		}

		if( empty( $profile['description'] )) {
			return new WP_Error('empty_description', __( ' Please say something interesting and wonderful about you. ', 'pr-profile'));	
		}

		if( empty( $profile['gender'] )) { 
			return new WP_Error('empty_gender', __( ' Please let us know if you are a girl, woman, boy or man.) ', 'pr-profile'));	
		}

		if( empty( $profile['location'] )) { 
			return new WP_Error('empty_location', __( ' Please let us know where you from.) ', 'pr-profile'));	
		}

		if( !intval( $profile['year_started_running'] )) {
			return new WP_Error('invalid_year', __( ' Please enter the year when you started running! ', 'pr-profile'));
		}

		return true;

	}

}
