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
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-edit-page-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-connect-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-homepage-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-mygroups-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-connections-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-events-joined-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-activities-controller.php' );
require_once( WPPR_PLUGIN_DIR . '/controllers/pr-forgot-password-controller.php' );

if ( ! class_exists( 'PR_Membership' )) :

	class PR_Membership {

		// Folder Name
		const PLUGIN_FOLDER = 'pr-membership';
		public $members;

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
			new PR_Edit_Page;
			new PR_My_Groups;
			new PR_Connections;
			new PR_Events_Joined;	
			new PR_Activities;
			new PR_ForgotPassword;
	        
	        // Register admin menu
	        add_action('admin_menu', array($this, 'register_pr_membership_menu'));
			
	    }

	
	    function register_pr_membership_menu() {
			add_menu_page('PR Membership', 'PR Membership', '', 'pr-membership-menu');
			add_submenu_page( 'pr-membership-menu', 'Incomplete Profiles', 'Incomplete Profiles', 'manage_options', 'pr-incomplete-profile', array( $this, 'pr_incomplete_profiles'));
			add_submenu_page( 'pr-membership-menu', 'Unconfirmed Signups', 'Unconfirmed Signups', 'manage_options', 'pr-unconfirmed-signups', array( $this, 'pr_unconfirmed_signups'));
			add_submenu_page( 'pr-membership-menu', 'Settings', 'Settings', 'manage_options', 'pr-settings', array( $this, 'pr_membership_settings'));
		}

		function pr_member_page() {
			echo "<h2>" . __( 'Test Members', 'pr-membership') . "</h2>";
		}

		function pr_membership_settings() {
			
			echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
				echo '<h2>Settings</h2>';
			echo '</div>';

		}

		function pr_incomplete_profiles() {
						
			require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
			$model = new Members_Model;

			echo "<h2>" . __( 'Members with Incomplete Information', 'pr-membership') . "</h2>";

			$args = array(
				''
			);

			$users = $model->get_incomplete_profiles();
			$ctr = 1;
			?>
			<form name="send-email" method="post" action="">
				<table class="ui stackable table">
	              <thead>
	                <tr>
	                  <th>#</th>
	                  <th>Login</th>
	                  <th>Email</th>
	                </tr>
	              </thead>
	              <tbody>
	                <?php foreach ( $users as $row ) : ?>
	                <tr>
	                  <td><?php echo $ctr; ?></td>
	                  <td><?php echo $row->user_login; ?></td>
	                  <td><?php echo $row->user_email; ?></td>
	                </tr>
	              <?php 
	                $ctr++;
	              endforeach; ?>        
	              </tbody>
	            </table>
	            <input type="submit" name="SendEmail" value="Send Email" />
	        </form>
			<?php

			if ( isset( $_POST['SendEmail'])) :

				foreach( $users as $user ) {

					$placeholders = array(
			            'USERNAME' => $user->user_login,
			        );
					$msg_template_id = 4;
					$this->send_notification_msg( $user->user_login, $user->user_email, $placeholders, $msg_template_id );	
				}

				echo 'Notification sent!';

			endif;

		}

		function pr_unconfirmed_signups() {
						
			require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
			$model = new Members_Model;

			echo "<h2>" . __( 'Lists of unconfirmed signups', 'pr-membership') . "</h2>";

			$args = array(
				''
			);

			$signups = $model->get_unconfirmed_signups();
			$ctr = 1;
			?>
			<form name="send-email" method="post" action="">
				<table class="ui stackable table">
	              <thead>
	                <tr>
	                  <th>#</th>
	                  <th>Signup Date</th>
	                  <th>Signup Username</th>
	                  <th>Signup Email</th>
	                  <th>Activation Key</th>
	                </tr>
	              </thead>
	              <tbody>
	                <?php foreach ( $signups as $signup ) : ?>
	                <tr>
	                  <td><?php echo $ctr; ?></td>
	                  <td><?php echo date('M d, Y',strtotime( $signup->signup_date ) ); ?></td>
	                  <td><?php echo $signup->signup_username; ?></td>
	                  <td><?php echo $signup->signup_email; ?></td>
	                  <td><?php echo $signup->signup_activation_key; ?></td>
	                </tr>
	              <?php 
	                $ctr++;
	              endforeach; ?>        
	              </tbody>
	            </table>
	            <input type="submit" name="SendEmail" value="Send Email" />
	        </form>
			<?php

			if ( isset( $_POST['SendEmail'])) :

				foreach( $signups as $signup ) {

					$home_url = home_url( 'confirm' );
					$verification_link = add_query_arg( array('key'=>$signup->signup_activation_key, 'user'=>$signup->signup_username), $home_url );

					$placeholders = array(
			            'USERNAME' => $signup->signup_username,
			            'VERIFY_LINK' => $verification_link,
			        );
					$msg_template_id = 5;
					$this->send_notification_msg( $signup->signup_username, $signup->signup_email, $placeholders, $msg_template_id );	
				}

				echo 'Notification sent!';

			endif;

		}


		function send_notification_msg( $user, $email, $placeholders, $template_id ) {    

			global $wpdb;
			$template = $wpdb->get_row( "SELECT * FROM wp_message_templates WHERE message_template_id = $template_id" );    

			$subject = $template->message_subject; 
			$message = $template->message_body;
			
			// $placeholders = array(
	  //           'USERNAME' => $user,
	  //       );

	        foreach($placeholders as $key => $value){
	            $message = str_replace('{'.$key.'}', $value, $message);
	        }

			$headers = 'From: noreply@pinoyrunners.co' . "\r\n";           
			wp_mail($email, $subject, $message, $headers);

		}
		

		public static function compute_age( $month, $day, $year ) 
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
