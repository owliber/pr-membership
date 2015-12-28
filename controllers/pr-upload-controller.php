<?php

/**
 * Class to upload background image for members
 */

class PR_Upload_Profile_Bg {

	const DEFAULT_HEADLINE_POSITION = 'left';

	public $user_id;
	//public $headline_position;

	function __construct() {

		add_shortcode('pr_upload', array($this, 'render_upload_form'));

	}

	function render_upload_form() {

		global $current_user;
		
		if ( is_user_logged_in() ) {

			$this->user_id = $current_user->ID;
			$userdata = get_userdata( $this->user_id );

			if ( isset ( $_POST['upload_profile_form_submitted'] ) && wp_verify_nonce($_POST['upload_profile_form_submitted'], 'upload_profile_form') ) {

				if( isset($_FILES['profile_image'] ) && @$_FILES['profile_image']['name'] ){

						$uploaded_image = $_FILES['profile_image'];						
						$result = $this->parse_file_errors( $uploaded_image );

						if( $result['error'] ) {

							$this->alert_message( $result['error'] );
					   
					  	} else {
					 		
					   		$this->process_image( $uploaded_image, $userdata );
					   		
					   	};

				}

			}

			if ( isset ( $_POST['colorpicker'] ) ) {

				$color = $_POST['colorpicker'];
				$this->set_headline_color( $color );

			}

			if ( isset ( $_POST['position'] ) ) {

				$position = $_POST['position'];
				$this->set_headline_position( $position );

			}

			//Get current headline position
			if( get_user_meta( $this->user_id, 'pr_member_headline_position', true ) != "" ) {
				$headline_position = get_user_meta( $this->user_id, 'pr_member_headline_position', true );
 			} else {
 				$headline_position = self::DEFAULT_HEADLINE_POSITION;
 			}

 			$attributes['headline_position'] = $headline_position;

			return PR_Membership::get_html_template( 'upload-form', $attributes );

		} else {

			echo '<div class="ui large red icon message">
							<i class="bug icon"></i>
							<div class="content">
							  <h3>You need to be logged-in to upload image!</h3>
							</div>
						  </div>';

		}

		
	}

	function parse_file_errors( $file ) {
 
		  $result = array();
		  $result['error'] = 0;
		   
		  if( $file['error'] ){
		   
		    switch( $file['error'] ) {

					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						$result['error'] = "The uploaded file exceeds the max upload size.";
						break;
					case UPLOAD_ERR_PARTIAL:
						$result['error'] = "The uploaded file was only partially uploaded.";
						break;
					case UPLOAD_ERR_NO_FILE:
						$result['error'] = "No file was uploaded.";
						break;
					case UPLOAD_ERR_NO_TMP_DIR:
						$result['error'] = "Missing a temporary folder.";
						break;
					case UPLOAD_ERR_CANT_WRITE:
						$result['error'] = "Failed to write file to disk.";
						break;
					case UPLOAD_ERR_EXTENSION:
						$result['error'] = "File upload stopped by extension.";
						break;
					default:
						$result['error'] = "File upload failed due to unknown error.";
				}
		     		   
		  }
		 
		  $image_data = getimagesize( $file['tmp_name'] );
		   
		  if( !in_array( $image_data['mime'], unserialize( TYPE_WHITELIST ) ) ){
		   
		    $result['error'] = 'Your image must be a jpeg, png or gif!';
		     
		  } elseif ( ($file['size'] > MAX_UPLOAD_SIZE ) ){
		   
		    $result['error'] = 'Your image was ' . $file['size'] . ' bytes! It must not exceed ' . MAX_UPLOAD_SIZE . ' bytes.';
		     
		  }
		     
		  return $result;
	 
	}

	function process_image( $file, $userdata ){
  
  		$document_root = $_SERVER['DOCUMENT_ROOT'];
	  	$upload_dir = wp_upload_dir();
	  	$upload_folder = get_option( 'pr_member_profile_background_path' );
	  	$upload_thumbanil_folder = get_option( 'pr_member_profile_thumbnail_path' );
	  	$profile_background_max_dimension = get_option( 'pr_member_profile_maximum_dimension' );

	  	$profile_dir = $document_root . trailingslashit( '/wp-content/uploads' )  . $upload_folder;
	  	$thumb_dir = $document_root . trailingslashit( '/wp-content/uploads' )  . $upload_thumbanil_folder;
	  	$tmppath = $file['tmp_name'];

	  	$oldimagefile = basename($userdata->pr_member_background_image);
		$oldthumbfile = basename($userdata->pr_member_thumbnail_image);
		
		$imagefile = $this->user_id . "_" . date('YmdHis') . '.' . preg_replace('{^.+?\.(?=\w+$)}', '', strtolower($file['name']));

		$imagepath = $profile_dir . '/' . $imagefile;
		$thumbfile = preg_replace("/(?=\.\w+$)/", '.thumbnail', $imagefile);
		$thumbpath = $thumb_dir . '/' . $thumbfile;

		$imageinfo = getimagesize( $tmppath );
		if( ! $imageinfo || ! $imageinfo[0] || ! $imageinfo[1] ) {

			$error = "Unable to get image dimensions.";				

		} else if( $imageinfo[0] >= $profile_background_max_dimension || $imageinfo[1] >= $profile_background_max_dimension ){

			if( $this->resize_background_image( $tmppath, null, $profile_background_max_dimension, $error ) )
				$imageinfo = getimagesize($tmppath);
		}
	  
	 	if( ! move_uploaded_file( $tmppath, $imagepath )) {

			return 'Unable to upload the user photo';

		} else {

			chmod( $imagepath, 0666 );
			
			// #Generate thumbnail
			$thumbnail_dimension = get_option( 'pr_member_profile_thumbnail_dimension' );
			if(!($thumbnail_dimension >= $imageinfo[0] && $thumbnail_dimension >= $imageinfo[1])){
				$this->resize_background_image( $imagepath, $thumbpath, $thumbnail_dimension, $error, true );
			}
			else {
				copy($imagepath, $thumbpath);
				chmod($thumbpath, 0666);
			}
			$thumbinfo = getimagesize($thumbpath);
						
			if( metadata_exists( 'user', $this->user_id, 'pr_member_background_image' ) ) {
				update_user_meta($this->user_id, 'pr_member_background_image', $imagefile, get_user_meta( $this->user_id, 'pr_member_background_image', true )); 
			} else {
				add_user_meta( $this->user_id, 'pr_member_background_image', $imagefile, true );
			}

			if( metadata_exists( 'user', $this->user_id, 'pr_member_thumbnail_image' ) ) {
				update_user_meta($this->user_id, 'pr_member_thumbnail_image', $thumbfile, get_user_meta( $this->user_id, 'pr_member_thumbnail_image', true )); 
			} else {
				add_user_meta( $this->user_id, 'pr_member_thumbnail_image', $thumbfile, true );
			}

			// update_usermeta($this->user_id, "pr_member_background_image_width", $imageinfo[0]); 
			// update_usermeta($this->user_id, "pr_member_background_image_height", $imageinfo[1]);
			// update_usermeta($this->user_id, "pr_member_thumbnail_image", $thumbfile);
			// update_usermeta($this->user_id, "pr_member_thumbnail_image_width", $thumbinfo[0]);
			// update_usermeta($this->user_id, "pr_member_thumbnail_image_height", $thumbinfo[1]);
			
			//Delete old thumbnail if it has a different filename (extension)
			if($oldimagefile != $imagefile)
				@unlink($profile_dir . '/' . $oldimagefile);

			if($oldthumbfile != $thumbfile)
			 	@unlink($thumb_dir . '/' . $oldthumbfile);
			
			return true; 	
		}
	}

	function set_headline_color( $color ) {

		if( metadata_exists( 'user', $this->user_id, 'pr_member_headline_color' ) ) {
			update_user_meta($this->user_id, 'pr_member_headline_color', $color, get_user_meta( $this->user_id, 'pr_member_headline_color', true )); 
		} else {
			add_user_meta( $this->user_id, 'pr_member_headline_color', $color, true );
		}

	}

	function set_headline_position( $position ) {

		if( metadata_exists( 'user', $this->user_id, 'pr_member_headline_position' ) ) {
			update_user_meta($this->user_id, 'pr_member_headline_position', $position, get_user_meta( $this->user_id, 'pr_member_headline_position', true )); 
		} else {
			add_user_meta( $this->user_id, 'pr_member_headline_position', $position, true );
		}
		
	}

	function resize_background_image($filename, $newFilename, $maxdimension, &$error, $is_thumb = false){

		if(!$newFilename)
			$newFilename = $filename;
		$jpeg_compression = (int)get_option( 'pr_member_photo_jpeg_compression' );
		
		$info = @getimagesize($filename);
		if(!$info || !$info[0] || !$info[1]){
			$error = "Unable to get image dimensions.";
		} else if ( //From WordPress image.php line 22
			!function_exists( 'imagegif' ) && $info[2] == IMAGETYPE_GIF
			||
			!function_exists( 'imagejpeg' ) && $info[2] == IMAGETYPE_JPEG
			||
			!function_exists( 'imagepng' ) && $info[2] == IMAGETYPE_PNG
		) {
			$error = 'Filetype not supported.';
		} 
		else {
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
			if(!isset($image)){
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

			if ( $is_thumb ) {
				$image_new_width = 800;
				$image_new_height = $image_new_width;
			}

			//$imageresized = imagecreatetruecolor( 600, 600);
			$imageresized = imagecreatetruecolor( $image_new_width, $image_new_height);
			@ imagecopyresampled( $imageresized, $image, 0, 0, 0, 0, $image_new_width, $image_new_height, $info[0], $info[1] );

			// move the thumbnail to its final destination
			if ( $info[2] == IMAGETYPE_GIF ) {
				if (!imagegif( $imageresized, $newFilename ) ) {
					$error = "Thumbnail path invalid";
				}
			}
			elseif ( $info[2] == IMAGETYPE_JPEG ) {
				if (!imagejpeg( $imageresized, $newFilename, $jpeg_compression ) ) {
					$error = "Thumbnail path invalid";
				}
			}
			elseif ( $info[2] == IMAGETYPE_PNG ) {
				if (!imagepng( $imageresized, $newFilename ) ) {
					$error = "Thumbnail path invalid";
				}
			}
		}
		if(!empty($error))
			return false;
		return true;
	}

	function alert_message( $result_msg ) {

		echo '<script>'.$result_msg.'</script>';

	}

}
