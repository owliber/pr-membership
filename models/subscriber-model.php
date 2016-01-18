<?php

/**
 * Model for subscriptions
 */

if ( ! class_exists( 'Subscriber_Model')) :

	class Subscriber_Model {

		public $email;
		public $name;

		public function insert() {

			global $wpdb;

			$data = array(
				$this->name,
				$this->email,
				CUR_DATE,
				REMOTE_IP
			);
		
			$sql = "INSERT IGNORE INTO wp_subscribers ( subscriber_name, subscriber_email, date_subscribed, ip_address )
					VALUES ( %s, %s, %s, %s ) ";

			$signup = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( is_wp_error( $signup ))
				return false;
			else
				return true;

		}

	}

endif;