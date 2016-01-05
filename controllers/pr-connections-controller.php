<?php

/**
 * Controller for connections
 * 
 */

class PR_Connections {

	public $connections;
	
	public function __construct() {

		add_shortcode( 'pr_connections', array( $this, 'render' ) );

	}

	public function render() {

		require_once( WPPR_PLUGIN_DIR . '/models/members-model.php' );
		$model = new Members_Model;

		if( is_user_logged_in() ) {

			$model->user_id = get_current_user_id();

			$this->connections = $model->get_all_connections();
			
			require_once( dirname( __DIR__ ) . '/views/connections.php' );

		} else {

			//redirect to login page
			PR_Membership::pr_redirect( home_url() );

		}
	}

}