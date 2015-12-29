<?php

/**
 * Class for editing member account
 */

class PR_Account {

	public $user_id;
	public $user_login;
	public $old_password;
	public $current_password;
	public $new_password;
	public $confirm_password;
	public $email;
	public $key;
	public $is_verified;

	function __construct() {

		add_shortcode('pr_account', array($this, 'render_account_page'));

	}

	function render_account_page() {

		if( is_user_logged_in() ) {

			$this->user_id = get_current_user_id();
			$current_user = get_userdata( $this->user_id );
			$this->user_login = $current_user->user_login;
			$this->email = $current_user->user_email;
			$this->old_password = $current_user->user_pass;

			//Check email status if already verified
			$email_status = get_user_meta( $this->user_id, 'is_email_verified', true );
			$email_status == 1 ? $this->is_verified = true : $this->is_verified = false;

			if ( isset( $_POST['submit'] ))
				$result = $this->update( $_POST['submit'] );

			require_once( WPPR_PLUGIN_DIR . '/views/account.php' );
			

		} else {

			redirect_to_home();

		}

	}

	private function update( $post ) {

		if( isset( $post ) ) {

				$account = $_POST['account'];

				//Update account password
				if( $_POST['submit'] == 1 ) {
					
					$this->current_password = $account['current_password'];
					$this->new_password = $account['new_password'];
					$this->confirm_password = $account['confirm_password'];

					$valid = $this->validate_password();
					
					if( is_wp_error( $valid ) ) {
						
						$errors[] = $valid->get_error_message();
						$result['errors'] = $errors;

					} else {
						
						$new_password = wp_hash_password($account['new_password']);
						$success = wp_set_password( $this->new_password, $this->user_id );
						
						if( !is_wp_error( $success )) {
							$result['success'] = 1; 
						} else {
							$result['errors'] = $success->get_error_message();
						}
					}
				}

				//Update email address
				if( $_POST['submit'] == 2 ) {
					
					$this->email = sanitize_email( $account['email'] );

					$valid = $this->validate_email();

					if( is_wp_error( $valid )) {

						$errors[] = $valid->get_error_message();
						$result['errors'] = $errors;

					} else {

						$userdata = array(
							'ID' => $this->user_id,
							'user_email' =>  $this->email
						);

						$update = wp_update_user( $userdata );

						if( ! is_wp_error( $update )) {

							//generate key for validation
							$this->key = generate_key( $this->email );

							if( metadata_exists( 'user', $this->user_id, 'is_email_verified' ) ) {
							
								update_user_meta( $this->user_id, 'is_email_verified', 0, get_user_meta( $this->user_id, 'is_email_verified', true ));

							} else {
								
								add_user_meta( $this->user_id, 'is_email_verified', 0, true );

							}

							if( metadata_exists( 'user', $this->user_id, 'email_verification_key' ) ) {
							
								update_user_meta( $this->user_id, 'email_verification_key', $this->key, get_user_meta( $this->user_id, 'email_verification_key', true ));

							} else {
								
								add_user_meta( $this->user_id, 'email_verification_key', $this->key, true );

							}

							//send verification link to email
							$this->send_verification_link();
							$result['success'] = 2;

						} else {

							$result['errors'] = $result->get_error_message();

						}

					}
				}

				if( $_POST['submit'] == 3 ) {
					//generate key for validation
					$this->key = generate_key( $this->email );
					update_user_meta( $this->user_id, 'is_email_verified', 0, get_user_meta( $this->user_id, 'is_email_verified', true ));
					update_user_meta( $this->user_id, 'email_verification_key', $this->key, get_user_meta( $this->user_id, 'email_verification_key', true ));
					$this->send_verification_link();
					$result['success'] = 2;
				}

				wp_reset_postdata();

				return $result;
			}

	}

	private function validate_password() {

		// Check current password if correct 
		$check_password = wp_check_password( $this->current_password, $this->old_password, $this->user_id );
		
		if( !$check_password ) {
			return new WP_Error('incorrect_password', __( 'Your current password is incorrect. ', 'pr-account'));	
		}

		if( strlen( $this->new_password ) < 5 ) {
			return new WP_Error('short_password', __( 'Password is too short, please use more than 5 alphanumeric characters. ', 'pr-account'));		
		}

		if( $this->confirm_password != $this->new_password ) {
			return new WP_Error('not_matched_password', __( 'Your new password does not matched. ', 'pr-account'));	
		}

		return true;

	}

	private function validate_email() {

		if( !is_email( $this->email ) ) {
			return new WP_Error('invalid_email', __( 'Invalid email address', 'pr-account'));		
		}

		if( email_exists( $this->email ) ) {
			return new WP_Error('email_used', __( 'The email address is already in used. ', 'pr-account'));	
		}

		return true;
	}

	function send_verification_link() {    

    	global $wpdb;

    	$template = $wpdb->get_row( "SELECT * FROM wp_message_templates WHERE message_template_id = 2" );  

		$subject = $template->message_subject; 
		$message = $template->message_body;
		$home_url = home_url( 'verify' );
		$verification_link = add_query_arg( array('key'=>$this->key, 'email'=>$this->email), $home_url );

		$placeholders = array(
            'USERNAME' => $this->user_login,
            'VERIFY_LINK' => $verification_link,
        );

        foreach($placeholders as $key => $value){
            $message = str_replace('{'.$key.'}', $value, $message);
        }

		$headers = 'From: noreply@pinoyrunners.co' . "\r\n";           
		wp_mail($this->email, $subject, $message, $headers);

	}

}
