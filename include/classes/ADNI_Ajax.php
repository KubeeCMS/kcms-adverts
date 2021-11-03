<?php
/*
 * AJAX REQUEST FUNCTIONS
 *
 * http://codex.wordpress.org/AJAX_in_Plugins
 * For not logged-in users use: add_action('wp_ajax_nopriv_my_action', 'my_action_callback');
*/
// Exit if accessed directly
if( !defined("ADNI_VERSION")) exit;
if ( ! class_exists( 'ADNI_Ajax' ) ) :

class ADNI_Ajax {

	public function __construct() {
		
		$_dn_ajax_actions = array(
			'adblocker_detected',
			'display_filter_load_posts',
			'ajax_install_plugin',
			'ppp_pup'
		);


		foreach($_dn_ajax_actions as $ajax_action)
		{
			add_action( 'wp_ajax_' . $ajax_action, array(__CLASS__, str_replace( '-', '_', $ajax_action )));
			add_action( 'wp_ajax_nopriv_' . $ajax_action, array(__CLASS__, str_replace( '-', '_', $ajax_action )));
		}
    }
    


    public static function adblocker_detected()
    {
		$set_arr = ADNI_Main::settings();
		$settings = $set_arr['settings'];

		if( $settings['adblock_detect'] )
		{
			echo json_encode(array('alert' => $settings['adblock_message']));
		}
        exit;
	}
	



	public static function display_filter_load_posts()
	{
		global $wpdb, $current_user;

		$key = $_POST['key'];
		$search = $_POST['search'];
		$type = $_POST['type'];
		$h = '';
		$response = array();

		// Check if user can add banners to all posts or only his/here own posts.
		$limit_user_posts = !current_user_can(ADNI_ALL_BANNERS_ROLE) ? ' post_author = '.$current_user->ID : '';

		if( $key === 'post_type' )
		{
			$all_posts = $wpdb->get_results( "SELECT ID, post_title FROM ".$wpdb->prefix."posts WHERE".$limit_user_posts." post_type = '".$type."' AND post_status = 'publish' AND post_title LIKE '%".$search."%' LIMIT 20" );
			
			//$h.= '<li class="active-result" data-option-array-index="0"></li>';
			foreach($all_posts as $i => $post)
			{
				$response[] = array("id" => $post->ID, "name" => $post->post_title);
				//$selected = !empty($posts) && is_array($posts) ? in_array($post->ID, $posts) ? 'selected' : '' : '';
				//$h.= '<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.' - (ID:'.$post->ID.')</option>';
				//$h1.= '<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.' - (ID:'.$post->ID.')</option>';
				//$h.= '<li class="active-result" data-option-array-index="'.$post->ID.'">'.$post->post_title.' - (ID:'.$post->ID.')</li>';
			}
			//echo $h.$post_type;
		}
		elseif( $key === 'taxonomy' )
		{
			$all_terms = get_terms( array(
				'taxonomy' => $type,
				'name__like' => $search,
				'number' => 20
			));
			foreach($all_terms as $i => $term)
			{
				$response[] = array("id" => $term->term_id, "name" => $term->name);
			}
		}
		elseif( $key === 'author' )
		{
			if( current_user_can(ADNI_ALL_BANNERS_ROLE) )
			{
				$all_users = get_users( array(
					'search' => '*'.$search.'*',
					'fields' => array( 'display_name', 'user_login', 'user_email', 'ID' ),
					'number' => 20
				));
				foreach($all_users as $i => $user)
				{
					$response[] = array("id" => $user->ID, "name" => $user->display_name);
				}
			}
			else
			{
				$response[] = array("id" => $current_user->ID, "name" => $current_user->display_name);
			}
			
		}
		
		echo json_encode($response);
		exit;
	}




	public static function ajax_install_plugin() 
	{
		if ( ! current_user_can( 'install_plugins' ) || ! isset( $_POST['plugin'] ) || ! $_POST['plugin'] ) 
		{
		  	wp_send_json_error( array( 'message' => 'No plugin specified' ) );
		}
	
		if ( ! isset( $_POST['package'] ) || ! $_POST['package'] ) {
		  	wp_send_json_error( array( 'message' => 'No package provided' ) );
		}
	
		$install = ADNI_Main::install_plugin( array(
		  	'plugin'   => $_POST['plugin'],
			'package'  => $_POST['package'],
			'activate' => true
		) );
	
		if ( is_wp_error( $install ) ) 
		{
		  	wp_send_json_error( array( 'message' => $install->get_error_message() ) );
		}
	
		wp_send_json_success( array( 'plugin' => $_POST['plugin'] ) );
	}





	public static function ppp_pup()
	{
		$banner_id = $_POST['bnr_id'];
		$inputs =  json_decode(json_encode(json_decode(stripslashes($_POST['inputs']))));
		if(!empty($inputs))
		{
			foreach($inputs as $key => $input)
			{

			}
		}

		$result_arr = array(
			'id' => $banner_id,
			'inputs' => $inputs,
			'response' => __('Thanks!','adn')
		);

		$result_arr = apply_filters('angwp_popup_result', $result_arr);

		echo json_encode($result_arr);
		exit;
	}
}
endif;