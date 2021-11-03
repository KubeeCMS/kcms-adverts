<?php
/**
 * Usage:
 *
 * require_once(DIR. '/ADNI_Uploader.php');
 * new ADNI_Uploader();
 * ADNI_Uploader::enqueue_scripts(array('upload_folder' => 'path'));
 *
*/
if ( ! class_exists( 'ADNI_Uploader' ) ) :

class ADNI_Uploader {

	public static $version = '2.0.2';
	public static $upload_folder = '';

	public function __construct() {

		$_spr_upload_ajax_actions = array(
			'_ning_upload_image',
			//'_ning_remove_image'
		);

		foreach($_spr_upload_ajax_actions as $ajax_action)
        {
            add_action( 'wp_ajax_' . $ajax_action, array(__CLASS__, str_replace( '-', '_', $ajax_action )));
        }
	}

	
	
	/*
	 * enqueue_scripts
	 *
	 * @access public
	 * @return null
	*/
	public static function enqueue_scripts($args = array())
	{
		$defaults = array(
			'version' => '1',
			'inc_url' => '',
			'prefix' => '_ning',
			'upload_folder' => 'ANDI_Uploads/'
		);
		$args = array_merge( $defaults, $args );

		self::$upload_folder = $args['upload_folder'];
		
		// Scripts
		wp_register_script( $args['prefix'].'_uploader_js', ADNI_ASSETS_URL. '/dev/js/_ning_uploader.js', array( 'jquery' ), $args['version'], true );

		// Styles
		wp_register_script( $args['prefix'].'_uploader_css', ADNI_ASSETS_URL. '/dev/css/_ning_uploader.css', false, $args['version'], "all" );
	}






	public static function load_images($args = array())
	{
		$defaults = array(
			'user_id' => 0,
			'supported' => array('gif','jpg','jpeg','png','svg'),
			'upload_folder' => 'ADNI_Uploads/',
			'upload_path' => '',
			'upload_src' => ''
		);
		$args = array_merge( $defaults, $args );

		$html = '';
		$path = $args['upload_path'].$args['upload_folder'];

		if(is_dir($path)) 
		{
			// Find all files in folder
			$files = glob($path."*.*");
			
			for ($i=0; $i<count($files); $i++)
			{
				$image = $files[$i];

				$ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
				if(in_array($ext, $args['supported'])) 
				{
				    //echo basename($image)."<br />"; // show only image name if you want to show full path then use this code // echo $image."<br />";
				    //$src = 'http://'.$_SERVER['SERVER_NAME'].'/files/upload/'.$path.basename($image);
				    $src = $args['upload_src'].$args['upload_folder'].basename($image);
				    $html.= '<div class="grid-item is-loading" data-use="path" data-src="'.$src.'">';
				    	$html.= '<img src="'.$src.'" />';
				    	$html.= '<div class="info_btn" data-info="'.basename($image).'">';
				    		$html.= '<svg viewBox="0 0 448 512" style="height:18px;border-radius:2px;"><path fill="currentColor" d="M400 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm-176 86c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z" class=""></path></svg>';
				    	$html.= '</div>';
				    $html.= '</div>';
				}
			}
		}

		return $html;
	}







	/**
	 * AJAX FUNCTIONS
	*/
	// Upload Image
	public static function _ning_upload_image()
	{
		$_action = isset($_POST['action']) ? $_POST['action'] : '';
		$user_id = isset($_POST['uid']) ? $_POST['uid'] : 0;
		$banner_id = isset($_POST['bid']) ? $_POST['bid'] : 0;
		$upload = isset($_POST['upload']) ?  json_decode(stripslashes($_POST['upload']), true) : array();
		$wpnonce = isset( $_POST['wpnonce'] ) ? $_POST['wpnonce'] : 0;
		
		$set_arr = ADNI_Main::settings();
		$settings = $set_arr['settings'];
		
		// Wordfence update - Make sure uploads are legit
		if( !empty( $_POST['wpnonce'] ) && !wp_verify_nonce( $_POST['wpnonce'], '_ning_upload_'.$banner_id )){
		//if( !wp_verify_nonce( $_POST['wpnonce'], '_ning_upload_'.$banner_id )){
			echo json_encode(array('ERROR' => 'no nonce.'));
			exit;
		}
		if($upload['dir'] !== ADNI_UPLOAD_DIR){
			echo 'wrong dir.';
			exit;
		}
		
		$valid_formats = $settings['upload']['file_types'];
		$max_upload_size = apply_filters('angwp_max_upload_size',10); // Max. size 10 MB.
		
		$max_file_size = 1024000*$max_upload_size;
		$upload_path = $upload['dir'].$upload['folder'];
		$count = 0;

		// Create upload folder if not exists
		if(!is_dir($upload_path)) {
		    mkdir($upload_path, 0777, true);
		}

		if(!empty($_FILES['files'])) 
		{
			$upload_success = false;
			$upload_error = '';
			$uploaded_files = array();
			$unzip_error = array();

			// Loop $_FILES to execute all files
			foreach ($_FILES['files']['name'] as $f => $name) 
			{     
			    if ($_FILES['files']['error'][$f] == 4) 
			    {
			        continue; // Skip file if any error found
			    }	       
			    if ($_FILES['files']['error'][$f] == 0) 
			    {	           
			        if ($_FILES['files']['size'][$f] > $max_file_size) 
			        {
			            $upload_error = $name. " is too large!";
			            continue; // Skip large files
			        }
					elseif( !in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats) )
					{
						$upload_error = $name." is not a valid format";
						continue; // Skip invalid file formats
					}
			        else
			        { 
			        	// No error found! Move uploaded files 
			            if(move_uploaded_file($_FILES["files"]["tmp_name"][$f], $upload_path.$name)){
			            	$count++; // Number of successfully uploaded file
							$src = $upload['src'].$upload['folder'].$name;

			            	// Copy image to banner folder
			            	/*if(!empty($banner_id))
			        		{
			        			if(!is_dir($upload_dir.'/'.$banner_folder)) {
								    mkdir($upload_dir.'/'.$banner_folder, 0777, true);
								}
			        			copy($path.$name, $upload_dir.'/'.$banner_folder.$name);
			        		}*/

			        		$uploaded_files[] = array(
			        			'name' => $name, 
								'size' => $_FILES['files']['size'][$f],
								'upload' => $upload,
								'path' => $upload_path.$name,
			        			'src' => $src,
			        			'grid_item' => '<div class="grid-item" data-src="'.$src.'" data-use="path"><img src="'.$src.'" /><div class="info_btn" data-info="'.basename($src).'"><svg viewBox="0 0 448 512" style="height:18px;border-radius:2px;"><path fill="currentColor" d="M400 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zm-176 86c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z" class=""></path></svg></div></div>',
			        			'uid'  => $user_id,
			        			'action'  => $_action
							);
							
							
							if( pathinfo($name, PATHINFO_EXTENSION) == 'zip')
							{
								$zipfile = array(
									'name' => $_FILES['files']['name'][$f],
									'type' => $_FILES['files']['type'][$f],
									'tmp_name' => $_FILES['files']['tmp_name'][$f],
									'error' => $_FILES['files']['error'][$f],
									'size' => $_FILES['files']['size'][$f],
								);
								
								$unzip_error = self::upload_and_unzip($zipfile, array('folder' => $upload['folder'], 'path' => $upload_path, 'src' => $upload['src']));
							}
						}
						else
						{
							$upload_error = is_writable($upload_path) ? 'Could not move files.' : 'Folder is not writable.';
						}
			        }
			    }
			}

			if(count($uploaded_files) > 0){
				$upload_success = true;
			}

			echo json_encode(array("chk" => $_FILES['files'], "unzip" => $unzip_error, "upload" => $upload, "success" => $upload_success, "files" => json_encode($uploaded_files), "error" => $upload_error));
		}else{
			echo 'no files found.';
		}
		exit;
	}


	// Remove Image
	// Currently not used
	/*public static function _ning_remove_image()
	{
		$upload = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_url = $upload['baseurl'];

		// Wordfence update - Make sure uploads are legit
		if(!is_numeric($_POST['uid']))
			exit;

		$upload_folder = self::$upload_folder.$_POST['uid'].'/';	

		$path = $upload_dir.'/'.$upload_folder.basename($_POST['src']);
		$removed = 0;

		if(unlink($path)){
			$remove = 1;
		}
		echo $remove;

		exit;
	}*/





	/**
	 * UPLOAD AND UNZIP ZIP FILES
     * $upload_result = self::upload_and_unzip($zip, array('folder' => $folder));
	 * $upload_result = json_decode($upload_result,true);
	 *
	 */
	public static function upload_and_unzip($zip = array(), $args = array())
	{
        $defaults = array(
			'folder' => '', //IMC_UPLOAD_FOLDER.'resources/stickers/',
			'path' => '',
			'src' => ''
		);
        $args = array_merge( $defaults, $args );

		$unzip_folder = $args['path'];
		$target_path = $args['path'].$zip['name'];


		$msg = __('No files received.','imc'); 
		$response = array(
			'success' => false,
			'html' => $msg,
            'url' => '',
            'path' => '',
			'type' => 'zip'
		);
		$is_zip = 0;
		
		if( !empty($zip))
		{
			$name = explode('.', $zip['name']);
			$accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
			foreach($accepted_types as $mime_type) {
				if($mime_type == $zip['type']) {
					//$okay = true;
					$is_zip = 1;
					break;
				} 
			}
			
			//rebuild folder name - fix for dots in name.
			$folder_name = '/';
			foreach($name as $i => $nm)
			{
				$dot = $i > 0 ? '.' : '';
				$folder_name .= strtolower($nm) != 'zip' ? $dot.$nm : '';
				$is_zip = strtolower($nm) == 'zip' ? 1 : $is_zip;
			}
			
			// Check if file is zip file to continue.
			$continue = $is_zip ? true : false;
			if(!$continue) {
				$msg = __('The file you are trying to unzip is not a .zip file. Please try again.','imc');
				$response['html'] = $msg;
				
				return $response;
			}
		
			
			$zip = new ZipArchive();
			if ($zip->open($target_path) === true) {			
				for($i = 0; $i < $zip->numFiles; $i++) {				
					$zip->extractTo($unzip_folder, array($zip->getNameIndex($i)));				
					// here you can run a custom function for the particular extracted file					
				}				
				$zip->close();	
				//unlink($target_path);			
			}
			$msg = __('Your .zip file was uploaded and unpacked:', 'imc');
            $response['html'] = $msg;
            $response['path'] = $unzip_folder.$folder_name;
			$response['success'] = true;
			
			// Check if we can find a index.html file
			$package_files = scandir($unzip_folder.$folder_name);
			$has_index = in_array('index.html', $package_files) ? 1 : 0;

			if( $has_index )
			{
                $msg.= ' '.$unzip_folder.$folder_name.'/index.html';
                $response['url'] = ADNI_UPLOAD_SRC.$args['folder'].$folder_name.'/index.html';
				$response['html'] = $msg;
			}
			else
			{
				$msg.= ' but we were unable to detect an index.html file. You will need to add the start file manually to the folder name. <strong>'.$unzip_folder.$folder_name.'/</strong>';
				$response['url'] = ADNI_UPLOAD_SRC.$args['folder'].$folder_name;
				$response['html'] = $msg;
			}
		}
		
		return $response;
	}
	
}

endif;
?>