<?php

/**
 * Model for connection
 */


if ( ! class_exists( 'Connect_Model' ) ) :

	class Connect_Model {

		public $user_id;
		public $member_id;

		public function get_connection_requests() {
    	
	    	global $wpdb;
			
	        $results = $wpdb->get_results(
	            $wpdb->prepare(
	                "SELECT 
	                	user_id
	                 FROM wp_requests 
		            	WHERE member_id = '%d'",
	                array(
	                	$this->user_id,
	                )
	            )
	        );

	       return $results;

		}

		public function get_new_connections() {
	    	
	    	global $wpdb;
			
	        $results = $wpdb->get_results(
	            $wpdb->prepare(
	                "SELECT 
	                	user_id, member_id, is_acknowledged
	                 FROM wp_connections 
		            	WHERE user_id = '%d' 
		            	AND is_acknowledged = 0",
	                array(
	                	$this->user_id,
	                )
	            )
	        );

	       return $results;

		}

		public function process_connection( $is_ignored = false ) {

			global $wpdb;

			$data = array(
				$this->user_id,
				$this->member_id,
				CUR_DATE,
				0,
				CUR_DATE
			);

			$sql = "INSERT INTO wp_connections ( user_id, member_id, date_added, is_acknowledged, date_acknowledged )
					VALUES ( %d, %d, %s, %d, %s ) ";		

			$sql2 = "INSERT INTO wp_connections ( member_id, user_id, date_added, is_acknowledged, date_acknowledged )
					VALUES ( %d, %d, %s, %d, %s ) ";

			if ( ! $is_ignored ) {

				$result = $wpdb->query( $wpdb->prepare( $sql, $data) );

				if( !is_wp_error( $result )) {

					$data2 = array(
						$this->user_id,
						$this->member_id,
						CUR_DATE,
						1,
						CUR_DATE
					);

					//add member to own connections
					$wpdb->query( $wpdb->prepare( $sql2, $data2) );

					//Update connection count
					$this->update_connection_stats( $this->user_id, $this->member_id );
					
					//If successful, delete from request
					$wpdb->delete( 'wp_requests', array( 'member_id' => $this->user_id, 'user_id' => $this->member_id ), array( '%d', '%d' ) );

					return true;

				 } else {

				 	return false;

				 }

			} else {

				//Delete from request if ignored
				$result = $wpdb->delete( 'wp_requests', array( 'member_id' => $this->user_id, 'user_id' => $this->member_id ), array( '%d', '%d' ) );

				if ( !is_wp_error( $result ))
					return true;
				else
					return false;
			}

			
				
		}

		public function acknowledge_connection() {

			global $wpdb;
			$cur_date = date('Y-m-d H:i:s');

			$data = array(
				$cur_date,
				$this->user_id,
				$this->member_id
			);

			$sql = "UPDATE wp_connections SET is_acknowledged = 1, date_acknowledged = '%s'
					WHERE user_id = '%d' AND member_id = '%d' AND is_acknowledged = 0";	

			$result = $wpdb->query( $wpdb->prepare( $sql, $data) );

			if( !is_wp_error( $result ))
				return true;
			else
			 	return false;
		}

		public function update_connection_stats() {

			global $wpdb;
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

		public function check_connection_privacy() {

			global $wpdb;
			$meta_key = 'enable_connection_approval';

			if( metadata_exists( 'user', $this->user_id, $meta_key ) ) {	
				return get_user_meta( $this->user_id, $meta_key, true );
			} else {
				return false;
			}

		}
	}

endif; 