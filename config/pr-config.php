<?php

/**
 * Config File
 */


//Get the upload directory
$upload_dir = wp_upload_dir();

/** [path] => C:\path\to\wordpress\wp-content\uploads\2010\05
    [url] => http://example.com/wp-content/uploads/2010/05
    [subdir] => /2010/05
    [basedir] => C:\path\to\wordpress\wp-content\uploads
    [baseurl] => http://example.com/wp-content/uploads
    [error] =>

 */

//Get the thumbnail path in the wp_options
$thumb_folder = get_option( 'pr_member_profile_thumbnail_path' );
$thumb_dir = trailingslashit( $upload_dir['baseurl']) . $thumb_folder;

//Get the profile upload folder
$background_folder = get_option( 'pr_member_profile_background_path' );
//$profile_dir = trailingslashit( $upload_dir['baseurl']) . $background_folder;

//Group Logo Path
$group_logo_folder = get_option( 'pr_group_logo_path' );

$current_date = date('Y-m-d H:i:s');
$current_year = date('Y');
$remote_ip = $_SERVER['REMOTE_ADDR'];

define( 'UPLOAD_PATH', $upload_dir['path'] );
define( 'UPLOAD_BASEURL', $upload_dir['baseurl'] );
define( 'UPLOAD_BASEDIR', $_SERVER['DOCUMENT_ROOT'] . trailingslashit( '/wp-content/uploads/' ));
define( 'CUR_DATE', $current_date );
define( 'CUR_YEAR', $current_year);		
define( 'THUMB_DIR', $thumb_dir );
define( 'PROFILE_DIR', UPLOAD_BASEDIR . $background_folder );
define( 'PROFILE_URL', UPLOAD_BASEURL . '/' . $background_folder . '/');
define( 'REMOTE_IP', $remote_ip );
define( 'MAX_AGE', 100 );
define( 'MIN_AGE', 6);
define( 'CURRENT_URI', home_url( $_SERVER['REQUEST_URI'] ) );

/* Background Upload */
define('MAX_UPLOAD_SIZE', 5242880); //Limit to 5MB
define('TYPE_WHITELIST', serialize(array(
		  'image/jpeg',
		  'image/png',
		  'image/gif'
		  )));


