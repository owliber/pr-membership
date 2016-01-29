<?php

/**
 * Member model
 *
 */


if ( ! class_exists( 'Members_Model ')) :

    class Members_Model {

    	public $user_id;
        public $profile;

    	public function get_all_connections() {
    		
    		global $wpdb;
    		
            $results = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT 
                    	user_id, member_id
                     FROM wp_connections 
    	            	WHERE user_id = '%d' 
    	            	AND is_acknowledged = 1",
                    array(
                    	$this->user_id,
                    )
                )
            );

           return $results;

    	}

        public function update_profile () {
    

            foreach( $this->profile as $key => $val ) {

                if ( isset( $this->profile[$key]) ) {

                    if( in_array( $key, array('user_url', 'display_name')) ) {

                        wp_update_user( array( 'ID' => $this->user_id, $key => $val ) );

                    } else {

                        if( metadata_exists( 'user', $this->user_id, $key ) ) {
                            
                            $result = update_user_meta( $this->user_id, $key, $val, get_user_meta( $this->user_id, $key, true ));

                        } else {
                            
                            $result = add_user_meta( $this->user_id, $key, $val, true );

                        }
                    }

                }           
                
            }

            if( ! is_wp_error( $result )) {
                return true;
            } else {
                return false;
            }
            
        }

        public function get_other_sports() {

            global $wpdb;

            $results = $wpdb->get_results("SELECT * FROM wp_sports WHERE sport_status = 1");

            return $results;
        }


        public function get_interest_lists() {

            global $wpdb;

            $results = $wpdb->get_results("SELECT * FROM wp_interests WHERE interest_status = 1");

            return $results;
        }

        public function get_privacy_meta() {

            $meta = array(
                'show_name',
                'show_gender',
                'show_location',
                'show_birthday',
                'show_birthyear',
                'show_age',
                'show_height',
                'show_weight',
                'show_year_started_running',
                'show_facebook',
                'show_twitter',
                'show_instagram',
                'show_website',
                'show_interests',
                'show_fastest_pace',
                'show_total_time',
                'show_activity_time',
                'show_activity_pace',
                'enable_connection_approval',
            );

            return $meta;
        }

        public function get_member_meta() {

            $meta = array(
                'first_name',
                'last_name',
                'gender',
                'location',
                'birth_day',
                'birth_year',
                'birth_month',
                'year_started_running',
                'height',
                'weight',
                'facebook',
                'twitter',
                'instagram',
                'interests',
            );

            return $meta;
        }

    }

endif;
