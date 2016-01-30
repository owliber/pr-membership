<?php

/**
 * Class controller for listing member cards
 * @author  owliber@yahoo.com
 * @version 1.0
 */

class PR_Connect {

	public $members;
	public $user_id;
	public $member_id;

	function __construct() {

		add_shortcode('pr_connect', array( $this, 'render_connect_page' ));
		
	}

	function render_connect_page() {

		if( ! is_author() ) :

		$this->user_id = get_current_user_id();	  

		// if ( isset( $_GET['r'] ) ) {

		// 	echo '<script>alert("test");</script>';
		// }

		require_once( dirname( __DIR__ ) . '/views/connect.php' );
		
		endif;

	}

	public function list_members() {

		$args = array(
			'exclude' => array( get_current_user_id() ),
			'meta_key' => 'is_featured',
	        'orderby' => 'meta_value_num',
	        'order' => 'DESC',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => 'has_profile_background',
					'value'   => 1,
					'compare' => '='
				),
				array(
					'key'     => 'is_profile_update',
					'value'   => 1,
					'compare' => '='
				),
			 ),
			//'role' => 'member',
		);

		return get_users( $args );
	}

}