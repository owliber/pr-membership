<?php

/**
 * Class for runner's homepage
 */

class PR_Homepage {

	public $user_id;
	public $privacy_status = 0;
	
	public function __construct() {

     	add_shortcode( 'pr_home_page', array( $this, 'render_homepage' ) );
     	add_action( 'wp_enqueue_scripts', array($this, 'enqueue_ajax_script' ));
     	add_action( 'wp_ajax_process_request', array( $this, 'process_request' ));
		add_action( 'wp_ajax_nopriv_process_request', 'process_request' );
		add_action( 'wp_ajax_process_group_request', array( $this, 'process_group_request' ));
		add_action( 'wp_ajax_nopriv_process_group_request', 'process_group_request' );
		
    }

    function enqueue_ajax_script() {
	  wp_enqueue_script( 'ajax-home-js', plugins_url(PR_Membership::PLUGIN_FOLDER  . '/js/ajax-home.js'), array('jquery'), '1.0.0', true );
	  wp_localize_script( 'ajax-home-js', 'AjaxHome', array(
	    'ajaxurl' => admin_url( 'admin-ajax.php' ),
	    'security' => wp_create_nonce( 'pr-home-actions' )
	  ));
	}

	function process_request() {

		require_once( WPPR_PLUGIN_DIR . '/models/connect-model.php' );
		$model = new Connect_Model;
		
		check_ajax_referer( 'pr-home-actions', 'security' );
		$ignore_request = false;

		if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
				&& ( isset( $_POST['user_id'] ) && !empty( $_POST['user_id'] ))
				&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
				&& ( isset( $_POST['status'] ) && !empty( $_POST['status'] ))
			) { 

			$model->member_id = intval( $_POST['user_id'] ); //requesting member
			$model->user_id = get_current_user_id();
			$status_id = intval( $_POST['status'] );

			if ( PR_Membership::is_valid_user( $model->member_id ) && $status_id ) {
				
				switch( $status_id ) {
					case 1:
					case 2: 
						$ignore_request = true;
						$result = $model->process_connection( $ignore_request ); 
						break;
					case 3: $result = $model->acknowledge_connection(); break;
				}


				if( $result ) {
					$result_code = 0;
					$status_msg = 'success';

				} else {
					$result_code = 1;
					$result_msg = 'error';
				}
			}

		} else {
			$result_code = 2;
			$result_msg = 'invalid connection request';
		}

		wp_send_json( array( 'result_code'=>$result_code, 'result_msg'=>$result_msg, 'result_id'=>$model->member_id ) );

		wp_die();
	}

	function process_group_request() {

		require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
		$model = new Group_Model;
		
		check_ajax_referer( 'pr-home-actions', 'security' );

		if ( (isset( $_POST['action'] ) && !empty( $_POST['action'] )) 
				&& ( isset( $_POST['user_id'] ) && !empty( $_POST['user_id'] ))
				&& ( isset( $_POST['group_id'] ) && !empty( $_POST['group_id'] ))
				&& ( isset( $_POST['security'] ) && !empty( $_POST['security'] ))
				&& ( isset( $_POST['status'] ) && !empty( $_POST['status'] ))
			) { 

			$model->member_id = intval( $_POST['user_id'] ); //requesting member
			$model->user_id = get_current_user_id();
			$model->group_id = intval( $_POST['group_id']);
			$status_id = intval( $_POST['status'] );

			if ( PR_Membership::is_valid_user( $model->member_id ) && $status_id ) {
				
				if ( $status_id == 1 ) { //Approve
					$result = $model->approve_group_join();
				} else {
					$result = $model->disapprove_group_join();
				}

				if( $result ) {
					$result_code = 0;
					$result_msg = 'success';

				} else {
					$result_code = 1;
					$result_msg = 'error';
				}
			}

		} else {
			$result_code = 2;
			$result_msg = 'invalid connection request';
		}

		wp_send_json( array( 'result_code'=>$result_code, 'result_msg'=>$result_msg, 'result_id'=>$model->member_id ) );

		wp_die();
	}

    public function render_homepage() {

    	require_once( WPPR_PLUGIN_DIR . '/models/connect-model.php' );    	
		$model = new Connect_Model;		

    	if ( is_user_logged_in() ) {

    		$user_id = get_current_user_id();
    		$model->user_id = $user_id;

    		$privacy = $model->check_connection_privacy();

    		if( $privacy == 1 )
    			$this->privacy_status = $privacy;

    		require_once( dirname( __DIR__ ) . '/views/homepage.php' );

    	} else {
    		PR_Membership::pr_redirect( home_url() );
    	}
    	
    }

    public function group_requests() {

    	require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
    	$model = new Group_Model;

    	$model->user_id = get_current_user_id();

    	return $model->join_requests();
    }

    function connection_requests() {

    	require_once( WPPR_PLUGIN_DIR . '/models/connect-model.php' );    	
		$model = new Connect_Model;

		$model->user_id = get_current_user_id();

		$conn_requests = $model->get_connection_requests();
    	$new_requests = $model->get_new_connections();

    	return array_merge($conn_requests, $new_requests);

    }

    

}
