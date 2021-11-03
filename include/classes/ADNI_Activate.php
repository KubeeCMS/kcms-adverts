<?php
class ADNI_Activate {		
	
	
	public function __construct() 
	{
		
	}
	
	
	/*
	 * REMOTE POST handler
	 *
	 * @access public
	 * @return array
	*/
	public static function remote_post($args = array())
	{
		global $wp_version;
		
		$defaults = array(
			'body' => array(),
			'url' => 'https://tunasite.com/updates/?plu-plugin=ajax-handler'
		);
		$args = wp_parse_args( $args, $defaults );
		
		$request = array(
			'body' => $args['body'],
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);
		
		$response = wp_remote_post($args['url'], $request);
		$response_code = wp_remote_retrieve_response_code( $response );
		$response_body = wp_remote_retrieve_body( $response );
		//print_r($args['body']);
		// ERROR
		if ( $response_code != 200 || is_wp_error( $response ) ) 
		{
			return array('server_status' => 0, 'body' => $response);
		}
		// OK
		else
		{
			return array('server_status' => 1, 'body' => $response_body);
		}
	}




	/*
	 * CHECK License
	 *
	 * @access public
	 * @return array
	*/
	public static function check($args = array())
	{	
		$defaults = array(
			'action'       => 'check',
			'license-key'  => ''
		);
        $args = wp_parse_args( $args, $defaults );
        $set = ADNI_Main::settings();
		$debug = $set['settings']['debug'];
		
		$request_body = array(
			'body' => array(
				'action'      => $args['action'], 
				'envato_id'   => ADNI_ENVATO_ID,
				'item_slug'   => ADNI_BASENAME,
				'license-key' => $args['license-key'],
				'api-key'     => md5(get_bloginfo('url')),
				'url'         => get_bloginfo('url'),
				'email'       => get_bloginfo('admin_email')
			)
		);
		
		$response = self::remote_post($request_body);
		echo $debug ? '<pre>'.print_r($response,true).'</pre>' : '';
		
		if( $response['server_status'] )
		{
			$resp = json_decode($response['body']);

			if(empty($resp->data))
			{
				$notice = ADNI_Init::deactivate();
			}
			
			return $resp;
		}
		// In case of error.
		else
		{
			return array('error' => __('Could not connect with server. Please try again in a few minutes.','adn'), 'error' => $response['body']);
		}
	}


	
	
	
	/*
	 * REGISTER Plugin
	 *
	 * @access public
	 * @return array
	*/
	public static function register($args = array())
	{	
		$defaults = array(
			'action'       => 'register_v6', //'check',
			'license-key'  => ''
		);
        $args = wp_parse_args( $args, $defaults );
        $set = ADNI_Main::settings();
		$debug = $set['settings']['debug'];
		
		$request_body = array(
			'body' => array(
				'action'      => $args['action'], 
				'envato_id'   => ADNI_ENVATO_ID,
				'item_slug'   => ADNI_BASENAME,
				'license-key' => $args['license-key'],
				'api-key'     => md5(get_bloginfo('url')),
				'url'         => get_bloginfo('url'),
				'email'       => get_bloginfo('admin_email')
			)
		);
		
		$response = self::remote_post($request_body);
		echo $debug ? '<pre>'.print_r($response,true).'</pre>' : '';
		
		// If plugin registered succesfully	
		if( $response['server_status'] )
		{
			$resp = json_decode($response['body']);
			
			if($resp->registered)
			{
				ADNI_multi::update_option( 'adning_activation', array(
					'license-key' => $args['license-key'], 
					'verify' => $resp->verify, 
					'user_data' => $resp->user_data
				));
				//update_option( 'adning_activation', array('license-key' => $args['license-key'], 'verify' => $resp->verify, 'user_data' => $resp->user_data) );
			}
			else
			{
				ADNI_multi::update_option( 'adning_activation', array() );
				//update_option( 'adning_activation', array() );
			}

			// Reset plugin updates
			set_site_transient('update_plugins', null);
			
			return $resp;
		}
		// In case of error.
		else
		{
			return array('msg' => __('Could not connect with server. Please try again in a few minutes.','adn'), 'error' => $response['body']);
		}
	}
	
	
	
	
	
	
	/*
	 * DE-REGISTER Plugin
	 *
	 * @access public
	 * @return array
	*/
	public static function deregister($args = array())
	{	
		$defaults = array(
			'action'       => 'unregister', //'check',
			'license-key'  => '',
		);
        $args = wp_parse_args( $args, $defaults );
        $set = ADNI_Main::settings();
		//$set = get_option('_adning_settings', array());
		$debug = $set['settings']['debug'];
		
		$request_body = array(
			'body' => array(
				'action'      => $args['action'], 
				'envato_id'   => ADNI_ENVATO_ID,
				'item_slug'   => ADNI_BASENAME,
				'license-key' => $args['license-key']
			)
		);
		
		$response = self::remote_post($request_body);
		echo $debug ? '<pre>'.print_r($response,true).'</pre>' : '';
		
		if( $response['server_status'] )
		{
			ADNI_multi::update_option( 'adning_activation', '' );
			//update_option( 'adning_activation', '' );

			// Reset plugin updates
			set_site_transient('update_plugins', null);
				
			return array('msg' => 'License code deactivated successfully.', 'adn');
		}
		else
		{
			return array('msg' => __('Could not connect with server. Please try again in a few minutes.','adn'), 'error' => $response['body']);	
		}
	}
	
	
	
	
	
	
	
	
	/*
	 * CHECK SUPPORT STATUS
	 *
	 * statuses: 0 = inactive, 1 = active
	 * codes: inactive, active, expired
	 *
	 * @access public
	 * @return array
	*/
	public static function check_support()
	{
		$status = 0;
		$code = 'inactive';
		$days = 0;
		$timestamp = '';
		$data = ADNI_Multi::get_option( 'adning_activation', array());
		//$data = get_option( 'adning_activation', array());
		
		if( !empty($data) )
		{
			if( is_object($data['verify']) )
			{
				$date = new DateTime($data['verify']->supported_until);
				$timestamp = strtotime($date->format('Y-m-d H:i:s'));
				
				if($timestamp > time())
				{
					$status = 1;
					$code = 'active';
					$datediff = $timestamp - time();
					$days = floor($datediff/(60*60*24));
				}
				else
				{
					$status = 0;
					$code = 'expired';	
				}
			}
		}
		
		return array('status' => $status, 'code' => $code, 'time' => $timestamp, 'days' => $days);
	}
	
	
}
?>