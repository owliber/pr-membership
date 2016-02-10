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
        add_shortcode('pr_confirm_thankyou', array($this, 'confirm_thankyou'));
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_subscribe_ajax_script' ));
        add_action( 'wp_ajax_nopriv_subscribe_to_newsletter', 'subscribe_to_newsletter' );
		add_action( 'wp_ajax_subscribe_to_newsletter', 'subscribe_to_newsletter' );

    }

    function enqueue_subscribe_ajax_script() {   
	  wp_enqueue_script( 'ajax-subscribe-js', plugins_url(PR_Membership::PLUGIN_FOLDER  . '/js/ajax-subscribe.js'), array('jquery'), '1.0.0', true );
      wp_localize_script( 'ajax-subscribe-js', 'AjaxSubscribe', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'pr-subscribe-to-newsletter' )
      ));
	  
	  
	}

    function subscribe_to_newsletter() {

	    if ( isset( $_POST['name'] ) && isset( $_POST['email'] ) && is_email( $_POST['email'] ) ) {

	        require_once( WP_PLUGIN_DIR . '/pr-membership/models/subscriber-model.php' );
	        $model = new Subscriber_Model;

	        $model->name = sanitize_text_field( $_POST['name'] );
	        $model->email = sanitize_email( $_POST['email'] );
	        $result = $model->insert();

	        if( $result ) {
	            $result_code = 0;
	            $result_msg ="Thank you for subscribing to us! See you on the road!";
	        } else {
	            $result_code = 0;
	            $result_msg ="Something went wrong, please try again later!";
	        }        

	        wp_send_json( array( 
	                'result_code'=>$result_code, 
	                'result_msg'=>$result_msg,
	            ) );

	        wp_die();

	    }
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
					'user_nicename' => $model->user,
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
										
					echo $this->redirect_on_success( $result['signup_email'] );
					
					
				} else {
					echo $this->redirect_on_error();
				}

			} else {
			
				echo $this->redirect_on_error();
			}
		}

	}

	function confirm_thankyou(){

		if( isset( $_REQUEST['email'] )) {
			$this->email = sanitize_email( $_REQUEST['email'] );
		}

		 require_once( dirname( __DIR__ ) . '/views/thankyou.php' );

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

	function redirect_on_success( $email ) {

		$redirect_url = 'thank-you?email='. $email;
		$js_script = '<script>window.location.replace("'.home_url( $redirect_url ).'")</script>';

		return $js_script;
	}

}
