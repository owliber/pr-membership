<?php

/**
 * Group Model
 */

if ( ! class_exists( 'Group_Model' )) : 

	class Group_Model {

		public $group_id;
		public $user_id;
		public $member_id;
		public $post_slug;

		public function is_group_exist() {

			global $wpdb;

			$result = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT 
	                	*
	                 FROM wp_posts 
		            	WHERE post_name = '%s'
		            	AND post_type = 'groups'",
	                array(
	                	$this->post_slug
	                )
	            )
	        );

	        if ( null !== $result ) 
	        	return true;
	        else 
	        	return false;
		}

		public function add_group_member( $group_id, $user_id, $approval, $is_admin = 0 ) {

			global $wpdb;		

			$data = array(
				$group_id,
				$user_id,
				CUR_DATE,
				$is_admin,
				$approval
			);

			$sql = "INSERT INTO wp_group_members ( group_id, user_id, date_joined, is_admin, is_approved )
					VALUES ( %d, %d, %s, %d, %d ) ";

			$result = $wpdb->query( $wpdb->prepare( $sql, $data ));

			if ( ! is_wp_error( $result )) {
				return true;
			} else {
				return false;
			}

		}

		public function get_member_groups( $user_id ) {

			global $wpdb;

			$sql = "SELECT * FROM wp_group_members WHERE user_id = '%d'";

			$result = $wpdb->get_results(
	            $wpdb->prepare( $sql,
	                array(
	                	$user_id
	                )
	            )
	        );

			return $result;
		}

		public function get_members( $group_id ) {

			global $wpdb;

			$sql = "SELECT * FROM wp_group_members 
					WHERE group_id = '%d' 
						AND is_approved = 1";

			$result = $wpdb->get_results(
	            $wpdb->prepare( $sql,
	                array(
	                	$group_id
	                )
	            )
	        );

			return $result;

		}

		public function get_membership_status( $group_id, $user_id ) {

			global $wpdb;

			$sql = "SELECT is_approved 
					FROM wp_group_members 
					WHERE group_id = '%d' 
						AND user_id = '%d'";

			$result = $wpdb->get_row(
	            $wpdb->prepare( $sql,
	                array(
	                	$group_id,
	                	$user_id
	                )
	            )
	        );

			if( $result->is_approved == 1)
				return true;
			else
				return false;

		}

		public function join_requests() {

			global $wpdb;

			$sql = "SELECT user_id, group_id FROM wp_group_members 
					WHERE group_id IN ( 
					    SELECT
					      group_id
					    FROM wp_group_members
					    WHERE user_id = '%d'
					    	AND is_admin = 1
					  ) AND is_approved = 0 ";

			$result = $wpdb->get_results(
	            $wpdb->prepare( $sql,
	                array(
	                	$this->user_id
	                )
	            )
	        );

			return $result;

		}

		public function check_group_joins( $group_id, $user_id ) {

			global $wpdb;

			$result = $wpdb->get_row(
	            $wpdb->prepare(
	                "SELECT 
	                	*
	                 FROM wp_group_members 
		            	WHERE group_id = '%d'
		            	AND user_id = '%d'
		            	AND is_approved = 0",
	                array(
	                	$group_id,
	                	$user_id
	                )
	            )
	        );

	        if ( null !== $result ) 
	        	return true;
	        else 
	        	return false;
		}

		public function approve_group_join() {

			global $wpdb;

			$sql = "UPDATE wp_group_members 
					SET is_approved = 1, date_approved = '%s', approved_by = '%d'
					WHERE user_id = '%d' AND group_id = '%d'";

			$result = $wpdb->query(
	            $wpdb->prepare( $sql,
	                array(
	                	CUR_DATE,
	                	$this->user_id,
	                	$this->member_id,
	                	$this->group_id
	                )
	            )
	        );

	        if ( ! is_wp_error( $result ))
	        	return true;
	        else
	        	return false;

		}

		public function disapprove_group_join() {

			global $wpdb;

			$sql = "DELETE FROM wp_group_members 
					WHERE user_id = '%d' AND group_id = '%d'";

			$result = $wpdb->query(
	            $wpdb->prepare( $sql,
	                array(
	                	$this->member_id,
	                	$this->group_id
	                )
	            )
	        );

	        if ( ! is_wp_error( $result ))
	        	return true;
	        else
	        	return false;

		}
		
	}

endif;
