<?php

/**
 * Class for verifying email changes
 * @author owliber <owliber@yahoo.com>
 *
 */


class PR_Verify_Email { 

	// public $key;
	// public $email;
	// public $user_id;

	function __construct() {

        add_shortcode('pr_verify_email', array($this, 'verify_email'));
    }


    public function verify_email() {

    	require_once( WPPR_PLUGIN_DIR . '/models/signup-model.php' );
		$model = new Signup_Model;
		$redirect_url = home_url();

		if(isset( $_GET['key'] ) &&  !empty( $_GET['key'] ) && isset( $_GET['email'] ) && !empty( $_GET['email'] ) ) {
			
			//Sanitize query
			$model->key = sanitize_key( $_GET['key'] );
			$model->email = sanitize_email( $_GET['email'] );

			$valid = $model->confirm_verification_key();

			if( $valid ) {
				
				// reset verification key
				update_user_meta( $model->user_id, 'is_email_verified', 1, 0 );
				update_user_meta( $model->user_id, 'email_verification_key', null, $this->key );

				$redirect_msg = 'Way to go runner, your email was successfully verified!';
					
			} else {

				$redirect_msg = 'Oops! the keys or email is either invalid or expired!';

			}
			
		} else {

			$redirect_msg = 'Oops! the parameters is invalid!';			

		}

		$this->redirect_to( $redirect_msg, $redirect_url );

	}


	public function redirect_to( $msg, $redirect_to ) {

		$js_script = '<script>alert("'.$msg.'")</script>';
		$js_script .= '<script>window.location.replace("'.$redirect_to.'")</script>';

		echo $js_script;
	}

}
