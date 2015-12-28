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

			//Check email status if already verified
			$email_status = get_user_meta( $this->user_id, 'is_email_verified', true );
			$email_status == 1 ? $this->is_verified = true : $this->is_verified = false;

			$attributes = array('errors'=>array(),'success'=>false);

			if( isset( $_POST['submit'] ) ) {

				$account = $_POST['account'];

				if( $_POST['submit'] == 1 ) {
					
					$this->current_password = $account['current_password'];
					$this->new_password = $account['new_password'];
					$this->confirm_password = $account['confirm_password'];
					$this->old_password = $current_user->user_pass;

					$valid = $this->validate_password();
					
					if( is_wp_error( $valid ) ) {
						
						$errors[] = $valid->get_error_message();
						$attributes['errors'] = $errors;

					} else {
						
						$new_password = wp_hash_password($account['new_password']);
						$success = wp_set_password( $this->new_password, $this->user_id );
						
						if( !is_wp_error( $success )) {
							$attributes['success'] = 1; 
						} else {
							$attributes['errors'] = $success->get_error_message();
						}
					}
				}

				if( $_POST['submit'] == 2 ) {
					
					$this->email = sanitize_email( $account['email'] );

					$valid = $this->validate_email();

					if( is_wp_error( $valid )) {

						$errors[] = $valid->get_error_message();
						$attributes['errors'] = $errors;

					} else {

						$userdata = array(
							'ID' => $this->user_id,
							'user_email' =>  $this->email
						);

						$result = wp_update_user( $userdata );

						if( !is_wp_error( $result )) {

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
							$attributes['success'] = 2;

						} else {

							$attributes['errors'] = $result->get_error_message();

						}

					}
				}
					
				wp_reset_postdata();
			}

			return PR_Membership::get_html_template( 'account', $attributes );

		} else {

			redirect_to_home();

		}

	}

	private function validate_password() {

		// Check current password if correct 
		$check_password = wp_check_password( $this->current_password, $this->old_password, $this->user_id );
		
		if( !$check_password ) {
			return new WP_Error('incorrect_password', __( 'Your current password is incorrect. ', 'pr-account'));	
		}

		if( strlen( $this->new_password ) < 5 ) {
			return new WP_Error('short_password', __( 'Password is too short, please use at least more than 5 alphanumeric characters. ', 'pr-account'));		
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
		$home_url = home_url( 'verify-email' );
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

	private function get_template_html( $template_name, $attributes ) {
	 
	    ob_start();
	 
	    do_action( 'pr_account_before_' . $template_name );
	 
	    require( 'templates/' . $template_name . '.php');
	 
	    do_action( 'pr_account_after_' . $template_name );
	 
	    $html = ob_get_contents();
	    ob_end_clean();
	 
	    return $html;
	}

}
