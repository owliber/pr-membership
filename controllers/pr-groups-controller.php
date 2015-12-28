<?php

/**
 * Class for user groups
 *
 */

class PR_Groups {

    public $group_id;
    public $user_id;
    public $groups;

	public function __construct() {

     	add_shortcode( 'pr_groups', array( $this, 'render_groups' ) );     	
    }

    function enqueue_ajax_script() {      

      wp_enqueue_script( 'ajax-join-js', plugins_url(PR_Membership::PLUGIN_FOLDER  . '/js/ajax-join.js'), array('jquery'), '1.0.0', true );
      wp_localize_script( 'ajax-join-js', 'AjaxJoin', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'pr-join-a-group' )
      ));

      
    }

    
    public function render_groups() {

       // require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
       // $model = new Group_Model;
    	
        //$this->groups = $model->get_all_groups();
        return PR_Membership::get_html_template( 'groups' );
    	

    }

}
