<?php

/**
 * Group Model
 */

if ( ! class_exists( 'Event_Model' )) : 

	class Event_Model {

		public function join_member( $event_id, $user_id ) {

			global $wpdb;

			$data = array(
				$event_id,
				$user_id,
				CUR_DATE
			);

			$sql = "INSERT INTO wp_event_joins ( event_id, user_id, date_joined )
					VALUES ( %d, %d, %s ) ";

			$result = $wpdb->query( $wpdb->prepare( $sql, $data ));

			if ( ! is_wp_error( $result )) {
				return true;
			} else {
				return false;
			}

		}

		public function check_event_joins( $event_id, $user_id ) {

			global $wpdb;

			$result = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT 
	                	*
	                 FROM wp_event_joins 
		            	WHERE event_id = '%d'
		            	AND user_id = '%d'",
	                array(
	                	$event_id,
	                	$user_id
	                )
	            )
	        );

	        if ( null !== $result ) 
	        	return true;
	        else 
	        	return false;
		}

		public function get_events_joined( $user_id ) {

			global $wpdb;

			$result = $wpdb->get_results(
	            $wpdb->prepare(
	                "SELECT 
	                	event_id
	                 FROM wp_event_joins 
		            	WHERE user_id = '%d'",
	                array(
	                	$user_id
	                )
	            )
	        );

	        return $result;
		}
		
	}

endif;
