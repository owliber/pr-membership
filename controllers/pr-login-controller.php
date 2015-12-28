<?php

class PR_Login {

	/**
     * Initializes the plugin.
     *
     * To keep the initialization fast, only add filter and action
     * hooks in the constructor.
     */
    public function __construct() {

     	add_shortcode( 'pr_login_form', array( $this, 'render_login_form' ) );
     	add_shortcode( 'pr_login_page', array( $this, 'render_login_page') );
     	add_action( 'login_form_login', array( $this, 'redirect_to_custom_login' ) );
     	add_filter( 'authenticate', 'wp_authenticate_username_password',  20, 3 );
		add_filter( 'authenticate', 'wp_authenticate_spam_check',         99    );
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
		add_action( 'wp_logout', array( $this, 'redirect_after_logout' ) );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
    }

	/**
	 * A shortcode for rendering the login form.
	 *
	 * @param  array   $attributes  Shortcode attributes.
	 * @param  string  $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_login_form( $attributes, $content = null ) {
	    // Parse shortcode attributes
	    $default_attributes = array( 'show_title' => false );
	    $attributes = shortcode_atts( $default_attributes, $attributes );
	    $show_title = $attributes['show_title'];
	 
	    if ( is_user_logged_in() ) {
	        $url = home_url( 'home' );
	        PR_Membership::pr_redirect( $url );
	    }
	     
	    // Pass the redirect parameter to the WordPress login functionality: by default,
	    // don't specify a redirect, but if a valid redirect URL has been passed as
	    // request parameter, use it.
	    $attributes['redirect'] = '';
	    if ( isset( $_REQUEST['redirect_to'] ) ) {
	        $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
	    }
	     
	     // Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['login'] );
		 
		    foreach ( $error_codes as $code ) {
		        $errors []= $this->get_error_message( $code );
		    }
		}
		$attributes['errors'] = $errors;

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

	    // Render the login form using an external template
	    return PR_Membership::get_html_template( 'login-form', $attributes );
	}

	function render_login_page( $attributes, $content = null ) {
	    
	 
	    if ( is_user_logged_in() ) {
	    	
	    	$url = home_url( 'home' );
	        PR_Membership::pr_redirect( $url );
	    }
	     
	    // Pass the redirect parameter to the WordPress login functionality: by default,
	    // don't specify a redirect, but if a valid redirect URL has been passed as
	    // request parameter, use it.
	    $attributes['redirect'] = '';
	    if ( isset( $_REQUEST['redirect_to'] ) ) {
	        $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
	    }
	     
	     // Error messages
		$errors = array();
		if ( isset( $_REQUEST['login'] ) ) {
		    $error_codes = explode( ',', $_REQUEST['login'] );
		 
		    foreach ( $error_codes as $code ) {
		        $errors []= $this->get_error_message( $code );
		    }
		}
		$attributes['errors'] = $errors;

		// Check if user just logged out
		$attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

		return Pr_Membership::get_html_template( 'login', $attributes );

	}


	/**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	function redirect_to_custom_login() {
	    if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
	        $redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : null;
	     
	        if ( is_user_logged_in() ) {
	            $this->redirect_logged_in_user( $redirect_to );
	            exit;
	        }
	 
	        // The rest are redirected to the login page
	        $login_url = home_url();
	        if ( ! empty( $redirect_to ) ) {
	            $login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );
	        }
	 
	        wp_redirect( $login_url );
	        exit;
	    }
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she
	 * is an admin or not.
	 *
	 * @param string $redirect_to   An optional redirect_to URL for admin users
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
	    $user = wp_get_current_user();
	    if ( user_can( $user, 'manage_options' ) ) {
	        if ( $redirect_to ) {
	            wp_safe_redirect( $redirect_to );
	        } else {
	            wp_redirect( admin_url() );
	        }
	    } else {
	        wp_redirect( home_url( 'profile' ) );
	    }
	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 *
	 * @param Wp_User|Wp_Error  $user       The signed in user, or the errors that have occurred during login.
	 * @param string            $username   The user name used to log in.
	 * @param string            $password   The password used to log in.
	 *
	 * @return Wp_User|Wp_Error The logged in user, or error information if there were errors.
	 */
	function maybe_redirect_at_authenticate( $user, $username, $password ) {
	    // Check if the earlier authenticate filter (most likely, 
	    // the default WordPress authentication) functions have found errors
	    if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	        if ( is_wp_error( $user ) ) {
	            $error_codes = join( ',', $user->get_error_codes() );
	 
	            $login_url = home_url( 'login' );
	            $login_url = add_query_arg( 'login', $error_codes, $login_url );
	 
	            wp_redirect( $login_url );
	            exit;
	        }
	    }
	 
	    return $user;
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 *
	 * @param string $error_code    The error code to look up.
	 *
	 * @return string               An error message.
	 */
	private function get_error_message( $error_code ) {
	    switch ( $error_code ) {
	        case 'empty_username':
	            return __( 'Please enter your username.', 'pr-login' );
	 
	        case 'empty_password':
	            return __( 'Please enter your password.', 'pr-login' );
	 
	        case 'invalid_username':
	            return __( 'Invalid username!', 'pr-login' );
	 
	        case 'incorrect_password':
	            // $err = __(
	            //     "Invalid password. <a href='%s'> Forgot Password</a>?", 'pr-login' );
	            // return sprintf( $err, wp_lostpassword_url() );
	            return __( 'Invalid password!', 'pr-login' );
	 
	        default:
	            break;
	    }
	     
	    return __( 'An unknown error occurred. Please try again later.', 'pr-login' );
	}

	/**
	 * Redirect to custom login page after the user has been logged out.
	 */
	public function redirect_after_logout() {
	    // $redirect_url = home_url( 'member-login?logged_out=true' );
	    $redirect_url = home_url( '?logged_out=true' );
	    wp_safe_redirect( $redirect_url );
	    exit;
	}

	/**
	 * Returns the URL to which the user should be redirected after the (successful) login.
	 *
	 * @param string           $redirect_to           The redirect destination URL.
	 * @param string           $requested_redirect_to The requested redirect destination URL passed as a parameter.
	 * @param WP_User|WP_Error $user                  WP_User object if login was successful, WP_Error object otherwise.
	 *
	 * @return string Redirect URL
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
	    $redirect_url = home_url();
	 
	    if ( ! isset( $user->ID ) ) {
	        return $redirect_url;
	    }
	 
	    if ( user_can( $user, 'manage_options' ) ) {
	        // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
	        if ( $requested_redirect_to == '' ) {
	            $redirect_url = admin_url();
	        } else {
	            $redirect_url = $requested_redirect_to;
	        }
	    } else {
	        // Non-admin users always go to their account page after login
	        //$redirect_url = home_url( 'profile' );
	        $redirect_url = home_url( $user->user_login );
	    }
	 
	    return wp_validate_redirect( $redirect_url, home_url() );
	}
}
