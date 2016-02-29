<?php

/**
 * Signup Controller
 * @author owliber <owliber@yahoo.com>
 *
 */
 
 class PR_Signup {
 
	private $username;
	private $email;
	private $password;
	
	function __construct() {
        add_shortcode('pr_signup_form', array($this, 'render_signup_form'));
        add_shortcode('pr_register', array( $this, 'render_registration') );
        //add_action( 'user_register', 'send_activation_link');      
    }
	
	function render_signup_form( $attributes )
    {

        if ( isset( $_POST['register'] ) ) {
            $this->username = $_POST['username'];
            $this->email = $_POST['email'];
            $this->password = $_POST['password'];
            $attributes = $this->signup();
        }

		if( ! is_user_logged_in() ) {
 
			// check to make sure user registration is enabled
			$registration_enabled = get_option('users_can_register');
	 
			// only show the registration form if allowed
			if( $registration_enabled ) {
				return PR_Membership::get_html_template( 'signup-form', $attributes );
				
			} else {
				echo 'Registration is disabled at the moment';
			}
			
		 }

    }

    function render_registration( $attributes ) {


        if ( isset( $_POST['register'] ) ) {

            $this->username = $_POST['username'];
            $this->email = $_POST['email'];
            $this->password = $_POST['password'];
            $attributes = $this->signup();
            
        }

		if( ! is_user_logged_in() ) {
 
			// check to make sure user registration is enabled
			$registration_enabled = get_option('users_can_register');
	 
			// only show the registration form if allowed
			if( $registration_enabled ) {
				return Pr_Membership::get_html_template( 'register', $attributes );
				
			} else {
				echo 'Registration is disabled at the moment';
			}
			
		 } else {
		 	PR_Membership::pr_redirect( home_url( 'home' ));
		 }

		

	}

	function sanitize_username( $username ) {

		$username = sanitize_user( $username, true );

		if( is_email( $username )) {
			$parts = explode("@", $username);
			$username = $parts[0];
		} else {
			$username = strtolower( $username );
			$username = str_replace(" ", "", $username);
			$username = str_replace("-", "", $username);
		}

		return $username;
	}

    function signup() {

    	require_once( WPPR_PLUGIN_DIR . '/models/signup-model.php' );
		$model = new Signup_Model;

		$username = $this->sanitize_username( $this->username );
		$email = sanitize_email( $this->email );
		$password = $this->password;
		$activation_key = generate_key( $email );

		$userdata = array(
			$username,
			$email,
			$password,
			$activation_key,
			CUR_DATE,
			REMOTE_IP,			
		);

		if ( is_wp_error( $this->validate_signup() ) ) {

			$attributes['errors'] = $this->validate_signup()->get_error_message();

        } else {

            $result = $model->insert_signup( $userdata );
            
            if ( !is_wp_error( $result ) ) {

            	$attributes['success'] = 'Please check your email for confirmation';

				//send email confirmation to user
				$this->send_activation_link( $username, $email, $activation_key );

 
				
            } else {

                $attributes['errors'] = 'Something went wrong. Please try again later';
            }
        }

        return $attributes;

	}

    function validate_signup() {

		require_once( WPPR_PLUGIN_DIR . '/models/signup-model.php' );
		$model = new Signup_Model;
		
		if ( username_exists( $this->username ) ) {
			return new WP_Error('username_unavailable', 'Username already taken');
		}
		
		if ( ! validate_username( $this->username ) ) {
			// invalid username
			return new WP_Error('username_invalid', 'Username is invalid');
		}
		
        if ( strlen( $this->username ) < 4 ) {
			return new WP_Error('username_length', 'Username too short. At least 4 characters is required');
        }

        if ( ! is_email( $this->email ) ) {
			return new WP_Error('email_invalid', 'Email is not valid');
        }

        if ( email_exists( $this->email ) ) {
			return new WP_Error('email', 'Email is already in used.');
        }

        if ( $model->validate_email( $this->email ) ) {
        	return new WP_Error('email', 'You already used this email to signup. Please check your email for confirmation.');
        }

        if ( strlen( $this->password ) <= 5 ) {
        	return new WP_Error('password_too_short', 'Password is too short.');
        }
				
	}

    function send_activation_link( $user, $email, $key ) {    

		require_once( WPPR_PLUGIN_DIR . '/models/signup-model.php' );
		$model = new Signup_Model;
		$template = $model->get_message_template(1);    	 

		$subject = $template->message_subject; 
		$message = $template->message_body;
		$home_url = home_url( 'confirm' );
		$confirmation_link = add_query_arg( array('key'=>$key, 'user'=>$user), $home_url );

		$placeholders = array(
            'USERNAME' => $user,
            'CONFIRM_LINK' => $confirmation_link,
            //'PASSWORD' => $password,
        );

        foreach($placeholders as $key => $value){
            $message = str_replace('{'.$key.'}', $value, $message);
        }

		$headers = 'From: noreply@pinoyrunners.co' . "\r\n";           
		wp_mail($email, $subject, $message, $headers);

	}
	
 }
