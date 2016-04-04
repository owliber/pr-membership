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
	private $view;
	private $active_class;

	function __construct() {

		add_shortcode('pr_connect', array( $this, 'render_connect_page' ));
		
	}

	function render_connect_page() {

		if( ! is_author() ) :

		$this->user_id = get_current_user_id();	  

		$query_view = get_query_var( 'view' );

		if ( isset( $query_view ) && ! empty( $query_view ) ) {

			$this->view = $query_view;

		}

		require_once( dirname( __DIR__ ) . '/views/connect.php' );
		
		endif;

	}

	public function list_members() {

		if ( $this->view == 'featured' ) :
			$args = array(
				'exclude' => array( get_current_user_id() ),
				//'meta_key' => 'is_featured',
		        'orderby' => 'registered',
		        'order' => 'DESC',
				'meta_query' => array(
					//'relation' => 'AND',
					array(
						'key'     => 'is_featured',
						'value'   =>  1,
						'compare' => '='
					),
				 ),
			);
		else:
			$key_val = 0;
			$args = array(
				'exclude' => array( get_current_user_id() ),
		        'orderby' => 'registered',
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
			);

		endif;

		return get_users( $args );
	}

}