<?php

/**
 * Class for user groups
 *
 */

class PR_Search {
    
	public function __construct() {

     	add_shortcode( 'pr_search', array( $this, 'render_search_page' ) );
     	
    }

    public function render_search_page() {
        $attributes = array();

        if( isset( $_REQUEST['s'] )) {
            $attributes['search'] = $_REQUEST['s'];
        }


            global $query_string;

            $query_args = explode("&", $query_string);
            $search_query = array();

            foreach($query_args as $key => $string) {
                $query_split = explode("=", $string);
                $search_query[$query_split[0]] = urldecode($query_split[1]);
            } // foreach

            $search = new WP_Query($search_query);
            
            var_dump($search);

        return PR_Membership::get_html_template( 'search-page', $attributes );

    }

}
