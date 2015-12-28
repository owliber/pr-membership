<?php

/**
 * Class for user groups
 *
 */

class PR_Events_Joined {
    
    public $user_id;
    public $events_joined;

	public function __construct() {

     	add_shortcode( 'pr_events_joined', array( $this, 'render_events_joined' ) );
             	
    }

    public function render_events_joined() {

    	if( is_user_logged_in() ) {

            require_once( WPPR_PLUGIN_DIR . '/models/event-model.php' );
            $model = new Event_Model;
            
            $user_id = get_current_user_id();

            $events_joined =  $model->get_events_joined( $user_id );

            foreach( $events_joined as $event ) {
                $events[] = $event->event_id;
            }

            $this->events_joined = $events;
                      
    		return PR_Membership::get_html_template( 'events-joined' );

    	} else {

    		//redirect to login page
    		$url = home_url();
    		PR_Membership::pr_redirect( $url );

    	}

    }

}
