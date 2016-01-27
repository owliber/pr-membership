<?php

class PR_ForgotPassword {

	
    public function __construct() {

     	add_shortcode( 'pr_forgotpass_form', array( $this, 'render_password_lost_form' ) );
     	add_shortcode( 'pr_password_reset_form', array( $this, 'render_password_reset_form' ) );

     	add_action( 'login_form_lostpassword', array( $this, 'redirect_to_custom_lostpassword' ) );
     	add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
     	add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_message' ), 10, 4 );
     	add_action( 'login_form_rp', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_custom_password_reset' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );
    }


    /**
	 * A shortcode for rendering the form used to reset a user's password.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_reset_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	 
	    if ( is_user_logged_in() ) {
	        PR_Membership::pr_redirect( home_url() );
	    } else {
	        if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
	            $attributes['login'] = $_REQUEST['login'];
	            $attributes['key'] = $_REQUEST['key'];
	 
	            // Error messages
	            $errors = array();
	            if ( isset( $_REQUEST['error'] ) ) {
	                $error_codes = explode( ',', $_REQUEST['error'] );
	 
	                foreach ( $error_codes as $code ) {
	                    $errors []= $this->get_error_message( $code );
	                }
	            }
	            $attributes['errors'] = $errors;
	 
	            return PR_Membership::get_html_template( 'passwordreset', $attributes );
	        } else {
	            return __( 'Invalid password reset link.', 'pinoyrunners' );
	        }
	    }
	}

    public function render_password_lost_form( $attributes, $content = null ) {
	    
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    //$attributes = shortcode_atts( $default_attributes, $attributes );
	    
	    // Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['errors'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['errors'] );
		 
		    foreach ( $error_codes as $error_code ) {
		        $attributes['errors'] []= $this->get_error_message( $error_code );
		    }
		}
	 
	    if ( is_user_logged_in() ) {
	        PR_Membership::pr_redirect( home_url() );
	    } else {
	        return PR_Membership::get_html_template( 'forgotpassword', $attributes );
	    }

	}

    /**
	 * Redirects the user to the custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_custom_lostpassword() {

	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user();
	            exit;
	        }
	 
	        wp_redirect( home_url( 'forgot-password' ) );
	        exit;
	    }
	}

	/**
	 * Initiates password reset.
	 */
	public function do_password_lost() {
	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	        $errors = retrieve_password();
	        if ( is_wp_error( $errors ) ) {
	            // Errors found
	            $redirect_url = home_url( 'forgot-password' );
	            $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
	        } else {
	            // Email sent
	            $redirect_url = home_url( 'login' );
	            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
	        }
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}

	function get_error_message( $error_code ) {
		// Lost password
 
	 	switch( $error_code ) {

			case 'empty_username':
			    return __( 'You need to enter your email address to continue.', 'pinoyrunners' );
			 
			case 'invalid_email':
			case 'invalidcombo':
			    return __( 'There are no users registered with this email address.', 'pinoyrunners' );

			// Reset password
			case 'expiredkey':
			case 'invalidkey':
			    return __( 'The password reset link you used is not valid anymore.', 'pinoyrunners' );
			 
			case 'password_reset_mismatch':
			    return __( "The passwords you entered does not matched.", 'pinoyrunners' );
			     
			case 'password_reset_empty':
			    return __( "Sorry, we don't accept empty passwords.", 'pinoyrunners' );
		}
	}

	/**
	 * Returns the message body for the password reset mail.
	 * Called through the retrieve_password_message filter.
	 *
	 * @param string  $message    Default mail message.
	 * @param string  $key        The activation key.
	 * @param string  $user_login The username for the user.
	 * @param WP_User $user_data  WP_User object.
	 *
	 * @return string   The mail message to send.
	 */
	public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	    $msg  = sprintf( __( 'Hi %s!', 'pinoyrunners' ), $user_login ) . "<br /><br />";
	    $msg .= sprintf( __( 'You recently requested us to reset your password for your account using the email address %s.', 'pinoyrunners' ), $user_data->user_email ) . '<br /><br />';
	    $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'pinoyrunners' ) . '<br /><br />';
	    $msg .= __( 'To reset your password, click or visit the following address:', 'pinoyrunners' ) . '<br /><br />';
	    $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '<br /><br />';
	    $msg .= __( 'Thank you. See you on the road!', 'pinoyrunners' ) . '<br /><br />';
	 
	    return $msg;
	}

	/**
	 * Redirects to the custom password reset page, or the login page
	 * if there are errors.
	 */
	public function redirect_to_custom_password_reset() {
	    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
	        // Verify key / login combo
	        $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
	        if ( ! $user || is_wp_error( $user ) ) {
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
	                wp_redirect( home_url( 'login?login=expiredkey' ) );
	            } else {
	                wp_redirect( home_url( 'login?login=invalidkey' ) );
	            }
	            exit;
	        }
	 
	        $redirect_url = home_url( 'password-reset' );
	        $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
	        $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );
	 
	        wp_redirect( $redirect_url );
	        exit;
	    }
	}
	
	/**
	 * Resets the user's password if the password reset form was submitted.
	 */
	public function do_password_reset() {

	    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
	        $rp_key = $_REQUEST['rp_key'];
	        $rp_login = $_REQUEST['rp_login'];
	 
	        $user = check_password_reset_key( $rp_key, $rp_login );
	 
	        if ( ! $user || is_wp_error( $user ) ) {
	            if ( $user && $user->get_error_code() === 'expired_key' ) {
	                wp_redirect( home_url( 'login?login=expiredkey' ) );
	            } else {
	                wp_redirect( home_url( 'login?login=invalidkey' ) );
	            }
	            exit;
	        }
	 
	        if ( isset( $_POST['pass1'] ) ) {
	            if ( $_POST['pass1'] != $_POST['pass2'] ) {
	                // Passwords don't match
	                $redirect_url = home_url( 'password-reset' );
	 
	                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
	                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
	                $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );
	 
	                wp_redirect( $redirect_url );
	                exit;
	            }
	 
	            if ( empty( $_POST['pass1'] ) ) {
	                // Password is empty
	                $redirect_url = home_url( 'password-reset' );
	 
	                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
	                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
	                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );
	 
	                wp_redirect( $redirect_url );
	                exit;
	            }
	 
	            // Parameter checks OK, reset password
	            reset_password( $user, $_POST['pass1'] );
	            wp_redirect( home_url( 'login?password=changed' ) );
	        } else {
	            echo "Invalid request.";
	        }
	 
	        exit;
	    }
	}
}
