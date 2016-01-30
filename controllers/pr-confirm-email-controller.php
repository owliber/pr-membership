<?php

/**
 * Confirm Email
 * @author owliber <owliber@yahoo.com>
 *
 */


class PR_Confirm_Email {

	private $key;
	private $user;
	private $email;
	private $password;
	private $date_registered;

	function __construct() {

        add_shortcode('pr_confirm_email', array($this, 'confirm_email'));

    }
		
	function confirm_email( $user, $key ) {

		require_once( WPPR_PLUGIN_DIR . '/models/signup-model.php' );
		$model = new Signup_Model;

		if(isset( $_GET['key'] ) &&  !empty( $_GET['key'] ) && isset( $_GET['user'] ) && !empty( $_GET['user'] ) ) {
			
			//Sanitize keys
			$model->key = sanitize_key( $_GET['key'] );
			$model->user = sanitize_user( $_GET['user'], true );

			$result = $model->validate_key();

			if( $result !== false  && ! username_exists( $model->user ) ) {
				
				$userdata = array(
					'user_login' => $model->user,
					//$model->user,
					'user_pass' => $result['signup_plain_password'],
					'user_email' => $result['signup_email'],
					'user_registerd' => $result['signup_date'],
					'display_name' => $model->user,
				);

				//Transfer record from wp_signup table to wp_users
				$confirmed = $model->register_user( $userdata );

				if( $confirmed ) {
					
					// Notify admin of new registration
					//wp_new_user_notification( $result );
					
					$this->send_notification_msg( $model->user, $result['signup_email'], $result['signup_plain_password'] );
										
					echo $this->redirect_on_success();
					
					
				} else {
					echo $this->redirect_on_error();
				}

			} else {
			
				echo $this->redirect_on_error();
			}
		}

	}

	function send_notification_msg( $user, $email, $password ) {    

		require_once( WPPR_PLUGIN_DIR . '/models/signup-model.php' );
		$model = new Signup_Model;
		$template = $model->get_message_template(3);    	 

		$subject = $template->message_subject; 
		$message = $template->message_body;
		
		$placeholders = array(
            'USERNAME' => $user,
            'PASSWORD' => $password,
        );

        foreach($placeholders as $key => $value){
            $message = str_replace('{'.$key.'}', $value, $message);
        }

		$headers = 'From: noreply@pinoyrunners.co' . "\r\n";           
		wp_mail($email, $subject, $message, $headers);

	}

	function redirect_on_error() {

		$js_script = '<script>alert("Oops! Sorry runner, Please try to signup again or use the Forgot Password. Thank you!")</script>';
		$js_script .= '<script>window.location.replace("'.home_url().'")</script>';

		return $js_script;
	}

	function redirect_on_success() {

		$js_script = '<script>alert("Congratulation! Please login now to customize your page.")</script>';
		$js_script .= '<script>window.location.replace("'.home_url().'")</script>';

		return $js_script;
	}

}
