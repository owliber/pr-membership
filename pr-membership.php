<?php

/**
Plugin Name: Pinoy Runners Membership
Plugin URI:  http://www.pinoyrunners.co
Description: A membership portal for all runners, walkers, marathoners, tri-athletes and all pinoy that loves running. A portal where members can keep and share their running activities, events they attended, record their personal trainings and so on and so forth.
Version:     0.1.0
Author:      Oliver Candelario
Author URI:  http://www.pinoyrunners.co
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: pr-membership

A free online membership for Pinoy runners.
*/

// Avoid direct execution
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
define( 'WPPR_PLUGIN_DIR', plugin_dir_path(__FILE__));

//Load the config file
require( WPPR_PLUGIN_DIR . '/config/pr-config.php' );

//Include all controllers
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-login-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-signup-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-confirm-email-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-profile-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-edit-profile-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-account-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-verify-email-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-privacy-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-notifications-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-upload-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-connect-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-homepage-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-mygroups-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-connections-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-groups-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-events-joined-controller.php' );

if ( ! class_exists( 'PR_Membership' )) :

	class PR_Membership {

		// Folder Name
		const PLUGIN_FOLDER = 'pr-membership';
		
		// DB Version
	    const DB_VERSION = '1.0';

	    function __construct() {

	    	//Instantiate controllers
	    	new PR_Login;
			new PR_Signup;
			new PR_Confirm_Email;
			new PR_Profile;
			new PR_Edit_Profile;
			new PR_Homepage;
			new PR_Account;
			new PR_Verify_Email;
			new PR_Privacy;
			new PR_Notifications;
			new PR_Connect;
			new PR_Upload_Profile_Bg;
			new PR_My_Groups;
			new PR_Connections;
			new PR_Groups;
			new PR_Events_Joined;	
	        
	        // Register admin menu
	        add_action('admin_menu', array($this, 'register_pr_membership_menu'));

	        // Create the custom pages at plugin activation
			//register_activation_hook( __FILE__, array( 'Pr_Login', 'plugin_activated' ) );
			//register_activation_hook( __FILE__, 'add_member_role' );
			//add_action('init', array($this, 'add_member_role'));
			
	    }

	 //    function add_member_role() {
	 //    	global $wp_roles;

		//    	$result = add_role( 'member', 'Member', array( 'read' => true, 'level_0' => true ) );

		//    	if ( null !== $result ) {
		// 	    echo 'Member role is now created!';
		// 	}
		// 	else {
		// 	    echo 'Member role already exists.';
		// 	}
		// }


	    function register_pr_membership_menu() {
			add_menu_page('PR Membership', 'PR Membership', '', 'pr-membership-menu');
			add_submenu_page( 'pr-membership-menu', 'Members', 'Members', 'manage_options', 'prm-members','prm_member_page_callback');
			add_submenu_page( 'pr-membership-menu', 'Events', 'Events', 'manage_options', 'prm-events','prm_events_page_callback');
			add_submenu_page( 'pr-membership-menu', 'Settings', 'Settings', 'manage_options', 'prm-settings','prm_settings_page_callback');
		}

		function prm_settings_page_callback() {
			
			echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
				echo '<h2>Settings</h2>';
			echo '</div>';

		}

		function prm_member_page_callback() {
			
			echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
				echo '<h2>Members</h2>';
			echo '</div>';

		}

		function prm_events_page_callback() {
			
			echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
				echo '<h2>Events</h2>';
			echo '</div>';

		}
		

		public function compute_age( $month, $day, $year ) 
		{ 
		        $curMonth = date("m");
		        $curDay = date("j");
		        $curYear = date("Y");
		        $age = $curYear - $year; 

		        if( $curMonth < $month || ( $curMonth == $month && $curDay < $day )) 
		                $age--; 

		        return $age; 
		}

		public static function is_member_page() {

			if( is_user_logged_in() ) {

				$URI = $_SERVER['REQUEST_URI'];		 
			 	$member_profile = str_replace('/', '', str_replace('member','', $URI));
			 	$current_user = get_userdata( get_current_user_id() );
			 	
			 	if( username_exists( $member_profile ) && $current_user->user_login == $member_profile ) 
			 		return true;
			 	else
			 		return false;
			 	
			} 
		}

		public function is_valid_user( $user_id ) {

			$user = get_userdata( $user_id );

			if ( $user )
				return true;
			else 
				return false;
		}

		public static function pr_redirect( $url ) {
			echo '<script>window.location.replace("'.$url.'")</script>';
		}


		public static function get_html_template( $template_name, $attributes = null ) {
		 
		    ob_start();
		 
		    do_action( 'pr_'.$template_name.'_before_' . $template_name );
		 
		    require( 'views/' . $template_name . '.php' );
		 
		    do_action( 'pr_'.$template_name.'_after_' . $template_name );
		 
		    $html = ob_get_contents();

		    ob_end_clean();
		 
		    return $html;
		}

	}

	new PR_Membership;

endif;
