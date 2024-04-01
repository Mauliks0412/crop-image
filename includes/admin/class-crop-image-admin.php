<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Manage Admin Class
 *
 * @package Crop Image
 * @since 1.0.0
 */

class Crop_Image_Admin {

	//class constructor
	function __construct() {
		
	}
	
	/**
	 * Crop image dialog
	 * 
	 * @package Crop Image
	 * @since 1.0.0
	 */
	public function crop_image_dialog() {
?>		
		<div id="crop_image_dialog" title="Crop Image"></div>
<?php
	}
	
	/**
	 * Rename cropped image if already exist
	 * 
	 * @package Crop Image
	 * @since 1.0.0
	 */
	public function crop_image_file_newname($path, $filename){
	    if ($pos = strrpos($filename, '.')) {
	           $name = substr($filename, 0, $pos);
	           $ext = substr($filename, $pos);
	    } else {
	           $name = $filename;
	    }
	
	    $newpath = $path.'/'.$filename;
	    $newname = $filename;
	    $counter = 0;
	    while (file_exists($newpath)) {
	           $newname = $name .'-'. $counter . $ext;
	           $newpath = $path.'/'.$newname;
	           $counter++;
	     }
	
	    return $newname;
	}
	
	/**
	 * Save cropped image
	 * 
	 * @package Crop Image
	 * @since 1.0.0
	 */
	public function crop_image_save_cropped_image() {
		
		$file  = $_POST['file'];
		$image = $_POST['image'];
		
		$upload_dir = wp_upload_dir();
		
		$dir  = $upload_dir['path'];
		$url  = $upload_dir['url'];
		$file = 'cropped-'.$file;
		
		if( file_exists($dir.'/'.$file) ){
			$file = $this->crop_image_file_newname( $dir.'/', $file );
		}
		
		$data  = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
		
		$check = file_put_contents($dir.'/'.$file, $data);
		
		$filetype = wp_check_filetype( basename( $file ), null );
		
		$attachment = array(
			'guid'           => $url . '/' .$file, 
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $file ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);
		
		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $dir.'/'.$file );
		
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $dir.'/'.$file );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		
		if( $check && !empty($attach_id) ){
			
			$response = array(
				'img' => $url.'/'.$file,
				'id'  => $attach_id
			);
			
			echo json_encode($response);
		}
		die;
	}
	
	/**
	 * Adding Hooks
	 *
	 * @package Crop Image
	 * @since 1.0.0
	 */
	function add_hooks(){
		
		// Crop image dialog
		add_action( 'admin_footer', array($this, 'crop_image_dialog'), 10, 6 );
		
		// Save cropped image
		add_action( 'wp_ajax_crop_image_save_cropped_image', array($this, 'crop_image_save_cropped_image') );
		add_action( 'wp_ajax_nopriv_crop_image_save_cropped_image', array($this, 'crop_image_save_cropped_image') );
	}
}
