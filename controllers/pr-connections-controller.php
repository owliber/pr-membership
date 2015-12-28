<?php

/**
 * Controller for connections
 * 
 */

class PR_Connections {

	public $connections;
	
	public function __construct() {

		add_shortcode( 'pr_connections2', array( $this, 'render' ) );

	}

	public function render() {

		require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
		$model = new Members_Model;

		if( is_user_logged_in() ) {

			$model->user_id = get_current_user_id();

			$this->connections = $model->get_all_connections();
			
			return PR_Membership::get_html_template( 'connections' );

		} else {

			//redirect to login page
			$url = home_url();
			PR_Membership::pr_redirect( $url );

		}
	}

}