<?php
/*
Plugin Name: Crop Image
Plugin URI: https://github.com/Mauliks0412
Description: Crop Image Uploaded by Wordpress Media.
Version: 1.0.0
Author: Maulik
Author URI: https://github.com/Mauliks0412
*/

/**
 * Basic plugin definitions 
 * 
 * @package Crop Image
 * @since 1.0.0
 */
if( !defined( 'CROP_IMAGE_DIR' ) ) {
  define( 'CROP_IMAGE_DIR', dirname( __FILE__ ) );      // Plugin dir
}
if( !defined( 'CROP_IMAGE_URL' ) ) {
  define( 'CROP_IMAGE_URL', plugin_dir_url( __FILE__ ) );   // Plugin url
}
if( !defined( 'CROP_IMAGE_INC_DIR' ) ) {
  define( 'CROP_IMAGE_INC_DIR', CROP_IMAGE_DIR.'/includes' );   // Plugin include dir
}
if( !defined( 'CROP_IMAGE_INC_URL' ) ) {
  define( 'CROP_IMAGE_INC_URL', CROP_IMAGE_URL.'includes' );    // Plugin include url
}
if( !defined( 'CROP_IMAGE_ADMIN_DIR' ) ) {
  define( 'CROP_IMAGE_ADMIN_DIR', CROP_IMAGE_INC_DIR.'/admin' );  // Plugin admin dir
}
if(!defined('CROP_IMAGE_PREFIX')) {
  define('CROP_IMAGE_PREFIX', 'crop_image'); // Plugin Prefix
}
if(!defined('CROP_IMAGE_VAR_PREFIX')) {
  define('CROP_IMAGE_VAR_PREFIX', '_crop_image_'); // Variable Prefix
}

/**
 * Load Text Domain
 *
 * This gets the plugin ready for translation.
 *
 * @package Crop Image
 * @since 1.0.0
 */
load_plugin_textdomain( 'cropimage', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/**
 * Activation Hook
 *
 * Register plugin activation hook.
 *
 * @package Crop Image
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'crop_image_install' );

function crop_image_install(){
	
}

/**
 * Deactivation Hook
 *
 * Register plugin deactivation hook.
 *
 * @package Crop Image
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'crop_image_uninstall');

function crop_image_uninstall(){
  
}

// Script class handles most of script functionalities of plugin
include_once( CROP_IMAGE_INC_DIR.'/class-crop-image-scripts.php' );
$crop_image_scripts = new Crop_Image_Scripts();
$crop_image_scripts->add_hooks();

// Admin class
include_once( CROP_IMAGE_ADMIN_DIR.'/class-crop-image-admin.php' );
$crop_image_admin = new Crop_Image_Admin();
$crop_image_admin->add_hooks();
