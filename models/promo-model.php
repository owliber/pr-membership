<?php

/**
 * Member model
 *
 */


if ( ! class_exists( 'Promo_Model ')) :

    class Promo_Model {

        public $start_date;
        public $end_date;

        public function get_complete_profiles() {

            global $wpdb;
            
            $results = $wpdb->get_results(

                $wpdb->prepare(

                        "SELECT
                              wu.ID,
                              wu.user_login,
                              wu.user_email,
                              DATE_ADD(wu.user_registered, INTERVAL 8 HOUR) AS user_registered,
                              IFNULL(b.total_activities,0) AS total_activities
                        FROM wp_users wu
                          LEFT JOIN wp_usermeta wu1
                            ON wu.id = wu1.user_id
                          LEFT OUTER JOIN ( SELECT
                          user_id,
                          COUNT(*) AS total_activities
                        FROM wp_activities
                        GROUP BY user_id) b ON wu.ID = b.user_id
                        WHERE ((wu1.meta_key = 'has_profile_background'
                            AND wu1.meta_value = 1)
                            OR (wu1.meta_key = 'is_profile_updated'
                            AND wu1.meta_value = 1))
                            AND (wu.user_registered >= '%s'
                            AND wu.user_registered <= '%s')
                        ORDER BY wu.user_registered DESC",

                    array(
                        $this->start_date,
                        $this->end_date
                    )
                )
                
            );

           return $results;

        }

        public function get_incomplete_profiles() {

            global $wpdb;
            
            $results = $wpdb->get_results(

                $wpdb->prepare(

                        "SELECT
                              wu.ID,
                              wu.user_login,
                              wu.user_email,
                              DATE_ADD(wu.user_registered, INTERVAL 8 HOUR) AS user_registered,
                              IFNULL(b.total_activities,0) AS total_activities
                        FROM wp_users wu
                          LEFT JOIN wp_usermeta wu1
                            ON wu.id = wu1.user_id
                          LEFT OUTER JOIN ( SELECT
                          user_id,
                          COUNT(*) AS total_activities
                        FROM wp_activities
                        GROUP BY user_id) b ON wu.ID = b.user_id
                        WHERE ((wu1.meta_key = 'has_profile_background'
                            AND wu1.meta_value = 0)
                            OR (wu1.meta_key = 'is_profile_updated'
                            AND wu1.meta_value = 0))
                            AND (wu.user_registered >= '%s'
                            AND wu.user_registered <= '%s')
                        ORDER BY wu.user_registered DESC",

                    array(
                        $this->start_date,
                        $this->end_date
                    )
                )
                
            );

           return $results;

        }

        public function get_total_entries() {

            global $wpdb;
            
            $results = $wpdb->get_row(

                $wpdb->prepare(

                    "SELECT
                          count(*) as total_entries
                        FROM wp_users wu
                        WHERE ( wu.user_registered > '%s'
                        AND wu.user_registered < '%s' )",

                    array(
                        $this->start_date,
                        $this->end_date
                    )
                )
                
            );

           return $results;

        }

        public function get_valid_entries() {

            global $wpdb;
            
            $results = $wpdb->get_row(

                $wpdb->prepare(

                    "SELECT
                          count(*) as valid_entries
                        FROM wp_users wu
                          LEFT JOIN wp_usermeta wu1
                            ON wu.ID = wu1.user_id
                        WHERE (( wu1.meta_key = 'has_profile_background'
                        AND wu1.meta_value = 1 )
                        OR ( wu1.meta_key = 'is_profile_updated'
                        AND wu1.meta_value = 1 ))
                        AND ( wu.user_registered > '%s'
                        AND wu.user_registered < '%s' )",

                    array(
                        $this->start_date,
                        $this->end_date
                    )
                )
                
            );

           return $results;

        }

    }

endif;
