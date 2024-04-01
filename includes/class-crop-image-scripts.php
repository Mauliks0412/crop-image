<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package Crop Image
 * @since 1.0.0
 */

class Crop_Image_Scripts {

	//class constructor
	function __construct()
	{
		
	}
	
	/**
	 * Enqueue Scripts
	 * 
	 * @package Crop Image
	 * @since 1.0.0
	 */
	public function crop_image_scripts( $hooks ){
		
		if( $hooks == 'toplevel_page_wpd-ws-settings' ) {
		
			wp_register_style( 'crop-image-cropper-style', CROP_IMAGE_URL . 'includes/css/crop-image-cropper-style.css' );
			wp_enqueue_style( 'crop-image-cropper-style' );
			
			wp_register_style( 'crop-image-main-style', CROP_IMAGE_URL . 'includes/css/crop-image-main-style.css' );
			wp_enqueue_style( 'crop-image-main-style' );
				
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui');
			wp_enqueue_script('jquery-ui-dialog');
		
			wp_register_script( 'crop-image-cropper-script', CROP_IMAGE_URL.'includes/js/crop-image-cropper-script.js', array(), null, true);
			wp_enqueue_script( 'crop-image-cropper-script' );
			
			wp_register_script( 'crop-image-main-script', CROP_IMAGE_URL.'includes/js/crop-image-main-script.js', array(), null, true);
			wp_enqueue_script( 'crop-image-main-script' );
			
			// localize script
			wp_localize_script(
				'crop-image-main-script', 
				'CropImage', 
				array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'processing' => CROP_IMAGE_INC_URL
				) 
			);
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package Crop Image
	 * @since 1.0.0
	 */
	function add_hooks(){
		
		//add admin scripts
		add_action('admin_enqueue_scripts', array($this, 'crop_image_scripts'));
	}
}
