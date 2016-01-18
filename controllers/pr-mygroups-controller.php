<?php

/**
 * Class for user groups
 *
 */

class PR_My_Groups {

    private $group_id;
    private $user_id;
    private $groups;
    private $other_groups;
    
	function __construct() {

     	add_shortcode( 'pr_mygroups', array( $this, 'render_member_groups' ) );
        add_action( 'wp_ajax_get_group_details', array( $this, 'get_group_details' ));
        add_action( 'wp_ajax_nopriv_get_group_details', 'get_group_details' );
        add_action( 'wp_enqueue_scripts', array($this, 'enqueue_ajax_script' ));
     	
    }

    function enqueue_ajax_script() {      

      wp_enqueue_script( 'ajax-groups-js', plugins_url(PR_Membership::PLUGIN_FOLDER  . '/js/ajax-group.js'), array('jquery'), '1.0.0', true );
      //wp_enqueue_script( 'semantic_min_js', get_stylesheet_directory_uri() . '/js/semantic.min.js', array('jquery'), '', true );
      wp_localize_script( 'ajax-groups-js', 'AjaxGroup', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'pr-get-group-details' )
      ));

      
    }

    function get_group_details() {

        $data = array();

        if ( isset( $_POST['group_id'] ) && intval( $_POST['group_id'] ) ) {

            check_ajax_referer( 'pr-get-group-details', 'security' );

            require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
            $model = new Group_Model;

            $group_id = $_POST['group_id'];
            $group = get_post( $group_id );

            if ( null !== $group ) {

                $data = array(
                    'group_id'=> $group->ID,
                    'group_name'=> $group->post_title,
                    'group_description'=> $group->post_content,
                    'group_location'=> get_post_meta( $group_id, '_group_location', true ),
                    'group_is_private' => get_post_meta( $group_id, '_is_private', true ),
                );

                $result_code = 0;
                $result_msg = 'success';

            } else {
                $result_code = 1;
                $result_msg = 'failed';
            }

        } else {

            $result_code = 2;
            $result_msg = 'invalid request';

        }

        wp_send_json( array( 
            'result_code'=>$result_code, 
            'result_msg'=>$result_msg,
            'result_data'=> $data,
        ) );

        wp_die(); 
    }

    function render_member_groups() {

    	if( is_user_logged_in() ) {

            require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
            $model = new Group_Model;
            
            $user_id = get_current_user_id();
            $this->user_id = $user_id;
            $model->user_id = $user_id;
            $userdata = get_userdata( $user_id );
            $result = array();


            if( isset( $_POST['group'] ) )  {
            
                $result = $this->save( $_POST['group'], $_POST['manage_form_group'], $_FILES['group_logo'] );

            }

            //Get joined groups
            $groups = $model->get_member_groups( $user_id );
            $group_ids = array();

            require_once( dirname( __DIR__ ) . '/views/mygroups.php' );

    	} else {

    		//redirect to login page
    		$url = home_url();
    		PR_Membership::pr_redirect( $url );

    	}

    }

    function save( $post, $nonce, $attachment = null ) {

        require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
        $model = new Group_Model;
        $has_attachment = false;

        if( isset( $post ) )  {

                if ( isset ( $nonce ) && wp_verify_nonce( $nonce, 'form_group') ) {

                    ( isset( $post['group_id'] ) && !empty( $post['group_id'] ) ) ? $is_update = true : $is_update = false;

                    $model->post_slug = sanitize_title( $post['group_name'] );

                    $postdata = array(
                        'post_title' => $post['group_name'],
                        'post_content' => $post['description'],
                        'post_status' => 'publish',
                        'post_author' => $this->user_id,
                        'post_type' => 'groups',
                        'post_name' => $model->post_slug,
                    );

                    $meta_location = $post['location'];
                    $meta_is_private =  ! isset( $post['is_private'] )  ? 0 : $post['is_private'];    

                    if ( $is_update ) {

                        $post_id = $post['group_id'];

                        $ID = array(
                            'ID' => $post_id,
                        );

                        $postdata = array_merge( $postdata, $ID );

                        $details = get_post( $post_id );
                        $post_name = $details->post_name;

                        if( $post_name != $model->post_slug ) {

                            //verify new group name is not exists
                            
                            if( ! $model->is_group_exist() ) {

                                $result = wp_update_post( $postdata );
                                update_post_meta( $post_id, '_group_location', $meta_location );
                                update_post_meta( $post_id, '_is_private', $meta_is_private );
                                
                                $status_code = 0;
                                $status_msg = 'Your group was successfully updated.';

                            } else {

                                $status_code = 1;
                                $status_msg = 'The group name is no longer available. Please try a new one.';

                            }
                            
                        
                        } else {

                            $result = wp_update_post( $postdata );
                            update_post_meta( $post_id, '_group_location', $meta_location );
                            update_post_meta( $post_id, '_is_private', $meta_is_private );
                            
                            $status_code = 0;
                            $status_msg = 'Your group was successfully updated.';
                        }


                    } else {

                        if ( ! $model->is_group_exist() ) {

                            $post_id = wp_insert_post( $postdata );  
                            $result = $model->add_group_member( $post_id, $this->user_id, 1, 1 ); //group_id, user_id, is_approved, is_admin

                            add_post_meta( $post_id, '_group_location', $meta_location, true );
                            add_post_meta( $post_id, '_group_total', 1, true );
                            add_post_meta( $post_id, '_is_private', $meta_is_private, true );

                            
                            $status_code = 0;
                            $status_msg = 'Your new group was successfully created.';

                        } else {

                            $status_code = 1;
                            $status_msg = 'The group name is no longer available. Please try a new one.';

                        }
                        
                    }

                    //Upload and attach image
                    
                    if( isset( $attachment ) && @$attachment['name'] ){

                        $has_attachment = true;

                        $upload_folder = get_option( 'pr_group_logo_path' );

                        $valid_image = $this->verify_image( $attachment );

                        if( $valid_image ) {

                            $uploaded_file = $this->process_image( $attachment );

                            if( $uploaded_file !== false ) {
                                $this->attach_image( $post_id, $uploaded_file, $is_update ); 
                            }

                        }

                    }
                    
                        
                    if( $has_attachment && ( ! $valid_image || $uploaded_file === false )) {
                        $status_msg .= 'However, the image file is invalid or not uploaded successfully.';
                    }

                }

                $result = array( 
                    'status_code' => $status_code,
                    'status_msg' => $status_msg
                ); 
               
            }

            return $result;

    }

    function is_membership_approved( $group_id ) {

        require_once( WPPR_PLUGIN_DIR . '/models/group-model.php' );
        $model = new Group_Model;

        $user_id = get_current_user_id();
        
        if ( $model->get_membership_status( $group_id, $user_id ))
            return true;
        else 
            return false;
    }

    function attach_image( $post_id, $uploaded_file, $is_update ) {
        
        // Get the path to the upload directory.
        $wp_upload_dir = wp_upload_dir();
        $filename = $wp_upload_dir['path'] . '/' . $uploaded_file;
        
        // Check the type of file. We'll use this as the 'post_mime_type'.
        $filetype = wp_check_filetype( basename( $filename ), null );
        $guid = $wp_upload_dir['url'] . '/' . basename( $filename );

        // Prepare an array of post data for the attachment.
        $attachment = array(
            'guid'           => $guid,
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            //'post_title'     => $uploaded_file,
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        // Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );

        if( $is_update )
            update_attached_file( $attach_id, $filename );

        // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        // Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
        wp_update_attachment_metadata( $attach_id, $attach_data );

        set_post_thumbnail( $post_id, $attach_id );
    }

    function verify_image( $file ) {

        $tmppath = $file['tmp_name'];
        
        if ( empty( $tmppath )) {
            return "Image filename is too long or invalid";
        } else {
            $imageinfo = getimagesize( $tmppath );
        }        

        if( ! $imageinfo || ! $imageinfo[0] || ! $imageinfo[1] ) {

            return "Unable to get image dimensions";

        } 

        $info = @getimagesize( $file['name'] );

        if ( //From WordPress image.php line 22
            !function_exists( 'imagegif' ) && $imageinfo[2] == IMAGETYPE_GIF
            ||
            !function_exists( 'imagejpeg' ) && $imageinfo[2] == IMAGETYPE_JPEG
            ||
            !function_exists( 'imagepng' ) && $imageinfo[2] == IMAGETYPE_PNG
        ) {
            return  "Filetype not supported";
        }

        if( $file['error'] ){
           
            switch( $file['error'] ) {

                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        return "The uploaded file exceeds the max upload size.";
                    case UPLOAD_ERR_PARTIAL:
                        return "The uploaded file was only partially uploaded.";
                    case UPLOAD_ERR_NO_FILE:
                        return "No file was uploaded.";
                    case UPLOAD_ERR_NO_TMP_DIR:
                        return "Missing a temporary folder.";
                    case UPLOAD_ERR_CANT_WRITE:
                        return "Failed to write file to disk.";
                    case UPLOAD_ERR_EXTENSION:
                        return "File upload stopped by extension.";
                    default:
                        return "File upload failed due to unknown error.";
                }
                       
          }
           
          if( ! in_array( $imageinfo['mime'], unserialize( TYPE_WHITELIST ) ) ){
           
            return 'The group logo must be a jpeg, png or gif  only!';
             
          } elseif ( ($file['size'] > MAX_UPLOAD_SIZE ) ){
           
            return 'Your group logo is ' . $file['size'] . ' bytes! It must not exceed ' . MAX_UPLOAD_SIZE . ' bytes.';
             
          }

          return true;

    }

    function process_image( $file ){  
        
        $logo_max_dimension = get_option( 'pr_group_logo_maximum_dimension' );
        
        $tmppath = $file['tmp_name'];

        if ( empty( $tmppath )) {
            return false;
        } else {


            $imagefile = date('YmdHis') . '.' . preg_replace('{^.+?\.(?=\w+$)}', '', strtolower($file['name']));

            $imagepath = UPLOAD_PATH . '/' . $imagefile;

            $imageinfo = getimagesize( $tmppath );
        }
        

        if( $imageinfo[0] >= $logo_max_dimension || $imageinfo[1] >= $logo_max_dimension ){

            if( $this->resize_logo( $tmppath, null, $logo_max_dimension, $error ) )
                $imageinfo = getimagesize($tmppath);
        }
      
        if( ! move_uploaded_file( $tmppath, $imagepath )) {

            return false; //Unable to upload the logo

        } else {

            chmod( $imagepath, 0666 );
            
            // #Resize Image
            $resized_dimension = get_option( 'pr_group_logo_maximum_dimension' );
            if( ! ($resized_dimension >= $imageinfo[0] && $resized_dimension >= $imageinfo[1] )) {
                $this->resize_logo( $imagepath, $thumbpath, $resized_dimension, $error, true );
            }
                        
            return $imagefile;
        }
    }

    function resize_logo($filename, $newFilename, $maxdimension, &$error){

        if( !$newFilename )
            $newFilename = $filename;

        $jpeg_compression = (int)get_option( 'pr_group_logo_jpeg_compression' );
        
        $info = @getimagesize( $filename );
        
        // create the initial copy from the original file
        if ( $info[2] == IMAGETYPE_GIF ) {
            $image = imagecreatefromgif( $filename );
        }
        elseif ( $info[2] == IMAGETYPE_JPEG ) {
            $image = imagecreatefromjpeg( $filename );
        }
        elseif ( $info[2] == IMAGETYPE_PNG ) {
            $image = imagecreatefrompng( $filename );
        }
        
        if( ! isset( $image ) ){
            $error = "Unrecognized image format.";
            return false;
        }
        
        if ( function_exists( 'imageantialias' ))
            imageantialias( $image, TRUE );

        // figure out the longest side

        if ( $info[0] > $info[1] ) {
            $image_width = $info[0];
            $image_height = $info[1];
            $image_new_width = $maxdimension;

            $image_ratio = $image_width / $image_new_width;
            $image_new_height = $image_height / $image_ratio;
            //width is > height
        } else {
            $image_width = $info[0];
            $image_height = $info[1];
            $image_new_height = $maxdimension;

            $image_ratio = $image_height / $image_new_height;
            $image_new_width = $image_width / $image_ratio;
            //height > width
        }

        $imageresized = imagecreatetruecolor( $image_new_width, $image_new_height);
        @ imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );

        // move the thumbnail to its final destination
        if ( $info[2] == IMAGETYPE_GIF ) {
            if ( ! imagegif( $imageresized, $newFilename ) ) {
                $error = "Thumbnail path invalid";
            }
        }
        elseif ( $info[2] == IMAGETYPE_JPEG ) {
            if ( ! imagejpeg( $imageresized, $newFilename, $jpeg_compression ) ) {
                $error = "Thumbnail path invalid";
            }
        }
        elseif ( $info[2] == IMAGETYPE_PNG ) {
            if ( !imagepng ( $imageresized, $newFilename ) ) {
                $error = "Thumbnail path invalid";
            }
        }

        if( !empty( $error ))
            return false;
        
        return true;
    }
	
}
