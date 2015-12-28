<?php

/**
 * Profile Model
 * 
 */

if ( ! class_exists( 'Profile_Model' )) :

	class Profile_Model {

		public $member_id;
		public $user_id;

		public function get_all_activities( $limit = true ) {

			global $wpdb;
			$LIMITBY = "";

			if ( $limit ) {
				$LIMITBY = " LIMIT 8";
			} 

	        $results = $wpdb->get_results(
	            $wpdb->prepare(
	                "SELECT * FROM wp_activities 
		            	WHERE status = 'publish' 
		            	AND user_id = '%d' 
		            	ORDER BY  activity_date DESC
		            	" . $LIMITBY,
	                array(
	                	$this->member_id,
	                )
	            )
	        );

	       return $results;

		}

		public function get_statistics() {

			global $wpdb;

	        $results = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT 
	                	  COUNT(*) AS 'activity_count',
						  SUM(`distance`) AS 'total_distance',
						  SEC_TO_TIME( SUM(TIME_TO_SEC(total_time))) AS 'total_time',
						  SUM(calories) AS 'total_calories',
						  SUM(elevation_gain) AS 'total_elev_gain'
	                	FROM wp_activities 
	                	WHERE status = 'publish' 
	                	AND user_id = '%d' 
	                	GROUP BY user_id
	                	ORDER BY  activity_date DESC",
	                array(
	                	$this->member_id,
	                )
	            )
	        );

	       return $results;

		}

		public function get_run_count( $activity_type ) {

			global $wpdb;

	        $results = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT
					  COUNT(*) as 'total'
					FROM wp_activities
					WHERE status = 'publish'
					AND user_id = '%d'
					AND activity_type = '%s'",
	                array(
	                	$this->member_id,
	                	$activity_type
	                )
	            )
	        );

	       return $results;

		}

		public function add_activity( $data) {

			global $wpdb;

			$sql = "INSERT INTO wp_activities ( user_id, activity_name, activity_type, activity_date, distance, total_time, average_pace, calories, elevation_gain, notes )
					VALUES ( %d, %s, %s, %s, %f, %s, %s, %s, %s, %s ) ";

			$result = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( !is_wp_error( $result ))
				return true;
			else
				return false;

		}

		public function add_request() {

			global $wpdb;
			
			$sql = "INSERT INTO wp_requests ( user_id, member_id, request_date )
					VALUES ( %d, %d, %s ) ";


			$data = array(
				$this->user_id,
				$this->member_id,
				CUR_DATE,
			);

			$result = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( !is_wp_error( $result ))
				return true;
			else
				return false;

		}

		public function get_request_status() {

			global $wpdb;

	        $result = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT *
	                	FROM wp_requests 
	                	WHERE user_id = '%d'
	                	AND member_id = '%d' 
	                	GROUP BY user_id",
	                array(
	                	$this->user_id,
	                	$this->member_id,
	                )
	            )
	        );

	        if( null !== $result)
	       		return true;
	       	else 
	       		return false;

		}

		public function check_connection_status() {

			global $wpdb;

	        $result = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT *
	                	FROM wp_connections 
	                	WHERE user_id = '%d'
	                	AND member_id = '%d'",
	                array(
	                	$this->user_id,
	                	$this->member_id,
	                )
	            )
	        );

	        if( null !== $result)
	       		return true;
	       	else 
	       		return false;
		}

		public function process_connection() {

			global $wpdb;
			
			$data = array(
				$this->user_id,
				$this->member_id,
				CUR_DATE,
				1,
				CUR_DATE
			);

			$sql = "INSERT INTO wp_connections ( user_id, member_id, date_added, is_acknowledged, date_acknowledged )
					VALUES ( %d, %d, %s, %d, %s ) ";		

			$sql2 = "INSERT INTO wp_connections ( member_id, user_id, date_added, is_acknowledged, date_acknowledged )
					VALUES ( %d, %d, %s, %d, %s ) ";

			$result = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( !is_wp_error( $result )) {

				//add member to own connections
				$wpdb->query( $wpdb->prepare( $sql2, $data) );

				//Update connection count
				$this->update_connection_stats();
				
				return true;

			 } else {

			 	return false;

			 }		
				
		}

		public function check_connection_privacy() {

			$meta_key = 'enable_connection_approval';

			if( metadata_exists( 'user', $this->member_id, $meta_key ) ) {	
				return get_user_meta( $this->member_id, $meta_key, true );
			} else {
				return false;
			}

		}

		private function update_connection_stats() {

			$meta_key = 'total_connections';

			if( metadata_exists( 'user', $this->user_id, $meta_key ) ) {						
				$old_value = get_user_meta( $this->user_id, $meta_key, true );
				update_user_meta( $this->user_id, $meta_key, $old_value + 1, $old_value );
			} else {					
				add_user_meta( $this->user_id, $meta_key, 1, true );
			}

			if( metadata_exists( 'user', $this->member_id, $meta_key ) )	{	
				$old_value = get_user_meta( $this->member_id, $meta_key, true );				
				update_user_meta( $this->member_id, $meta_key, $old_value + 1, $old_value );
			} else {					
				add_user_meta( $this->member_id, $meta_key, 1, true );
			}
		}
	}

endif;
