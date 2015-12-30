<?php

/**
 * Class for notifications
 */

class PR_Notifications {
	
	public function __construct() {

     	add_shortcode( 'pr_notifications', array( $this, 'render_notifications' ) );
    }

    public function render_notifications() {

    	$attributes = array('errors'=>array(),'success'=>false);

    	require_once( dirname( __DIR__ ) . '/views/notifications.php' );
    }

}