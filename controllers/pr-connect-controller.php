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

		$this->user_id = get_current_user_id();	  	
		return PR_Membership::get_html_template( 'connect' );

	}

	public function list_members() {

		$args = array(
			'exclude' => array( get_current_user_id() ),
			//'role' => 'member',
		);

		return get_users( $args );
	}

}