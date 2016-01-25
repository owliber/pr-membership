<?php

/**
 * Signup Model
 * @Controller pr-signup-controller
 */

if ( ! class_exists( 'Signup_Model')) :

	class Signup_Model {

		public $key;
		public $user;
		public $email;
		public $user_id;

		public function validate_email( $email ) {

			global $wpdb;

	        $results = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT * FROM wp_signups WHERE signup_email = '%s'",
	                array(
	                	$email
	                )
	            )
	        );

	        // No key found
	        if($results === null)
	            return false;

			return true;

		}

		public function insert_signup( $data ) {

			global $wpdb;
		
			$sql = "INSERT INTO wp_signups ( signup_username, signup_email, signup_password, signup_activation_key, signup_date, signup_ip_address )
					VALUES ( %s, %s, %s, %s, %s, %s ) ";

			$signup = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( is_wp_error( $signup ))
				return false;
			else
				return $wpdb->insert_id;

		}

		public function get_message_template( $id ) {

			global $wpdb;

			return $wpdb->get_row( "SELECT * FROM wp_message_templates WHERE message_template_id = $id" ); 

		}

		public function validate_key() {

			global $wpdb;

	        $results = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT * FROM wp_signups WHERE signup_activation_key = '%s' AND signup_username = '%s'",
	                array(
	                	$this->key,
	                	$this->user,
	                )
	            )
	        );

	        if( is_wp_error( $results ) || $wpdb->num_rows <= 0) {
	            return false;
	        } else {

				$signup_data = array(
					'signup_username' => $results->signup_username,
					'signup_email' => $results->signup_email,
					'signup_date' => $results->signup_date,
					'signup_password' => $results->signup_password,
				);

				return $signup_data;
			}

		}

		public function confirm_verification_key() {

			global $wpdb;

			// Retrieve data
	        $results = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT * FROM wp_usermeta a 
	                	INNER JOIN wp_users b ON a.user_id = b.ID
	                	WHERE ( a.meta_key= '%s' AND a.meta_value = '%s' )
	                		AND b.user_email = '%s' ",
	                array(
	                	'email_verification_key',
	                	$this->key,
	                	$this->email,
	                )
	            )
	        );

	        // No key found
	        if( is_wp_error( $results ) || $wpdb->num_rows <= 0) {
	            return false;
	        } else {
	        	$this->user_id = $results->user_id;
	        	return true;
	        }

		}

		public function register_user( $data ) {
		
			global $wpdb;

			$sql = "INSERT INTO wp_users ( user_login, user_nicename, user_pass, user_email, user_registered, display_name )
					VALUES ( %s, %s, %s, %s, %s, %s ) ";

			$result = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( !is_wp_error( $result ) )
			{
				$user_id = $wpdb->insert_id;

				$userdata = array(
					'ID' => $user_id,
					'role' => 'member',
					'nickname' => $this->user,
				);

				$user_meta = wp_update_user( $userdata );
				add_user_meta( $user_id, 'is_email_verified', 1, true );
				add_user_meta( $user_id, 'email_verification_key', $this->key, true );
				add_user_meta( $user_id, 'has_profile_background', 0, true );
				add_user_meta( $user_id, 'is_profile_update', 0, true );

				if( !is_wp_error( $user_meta ))
				{
					return true;
				}
				else
				{
					return false;
				}
			}

		}

	}

endif;