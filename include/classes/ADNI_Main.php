<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Main' ) ) :

class ADNI_Main {

	public static $IP = false;


	/*
	 * parse_args - Array Merge
	 * ADNI_Main::parse_args( $args, $default );
	 *
	 * @access public
	 * @return array
	*/
	public static function parse_args($args, $default)
	{
		if(!is_array($args)) return $default;
		if(!is_array($default)) return $args;

		$is_multi = self::array_is_multi($default);
		$is_multi = !$is_multi ? self::array_is_multi($args) : $is_multi;

		return $is_multi ? self::array_merge_recursive_distinct($default, $args) : array_merge( $default, $args );
		//return array_merge( $default, $args );
	}

	// Check if array is multidimentional
	public static function array_is_multi($a) 
	{
		if(empty($a)) return false;
		foreach ($a as $v) 
		{
			if (is_array($v)) return true;
		}
		return false;
	}
	
	
	/*
	 * Load Settings
	 *
	 * @access public
	 * @return array
	*/
	public static function settings()
	{
		$default_settings = array(
			'plugin_version' => 0, // The current plugin version to detect updates.
			'debug' => 0,
			'disable' => array(
				'all_ads' => 0,
				'non_singular_ads' => 0,
				'user_role_ads' => ''
			),
			'gdpr' => array(
				'disable_till_approved' => 0,
				'cookie_name' => '',
				'cookie_value' => '',
				'show_cookie_message' => 0,
				'cookie_message_page_url' => '',
				'cookie_message_text' => __('We use cookies to offer you a better browsing experience. If you continue to use this site, you consent to our use of cookies.','adn'),
				'cookie_message_approve_btn' => __('I Accept Cookies','adn')
			),
			'upload' => array(
				'html_ads' => 0,
				'sanitize' => 0,
				'file_types' => array('jpg','jpeg','png','gif','svg','mp4')
			),
			'positioning' => array(
				'post_types' => array('post', 'page') // double arrays do not work with custom parse_args so we added a fix for it below...
			),
			'filters' => array(
				'hide_ads_when_no_author_filter' => 0,
				'advertiser_role' => ''
			),
			'custom_css' => '',
			'placement_area_head' => '',
			'placement_area_body' => '',
			'adsense_pubid' => '',
			'adsense_auto_ads' => 0,
			'ga_tracking_id' => '',
			'uninstall_remove_data' => 0,
			'adblock_detect' => 0,
			'adblock_message' => __('You are using AD Blocker!','adn')
		);
		
		$_adning_settings = ADNI_Multi::get_option('_adning_settings', array());
		$settings = self::parse_args( $_adning_settings, $default_settings );
		$settings['plugin_version'] = empty($settings['plugin_version']) ? ADNI_VERSION : $settings['plugin_version'];
		

		// Fix for positioning post_types when post or page is not checked but parse args adds it back anyway.
		if( array_key_exists('positioning', $_adning_settings) )
		{
			if( array_key_exists('post_types', $_adning_settings['positioning']) )
			{
				$settings['positioning']['post_types'] = $_adning_settings['positioning']['post_types'];
			}
		}
		
		
		$default_roles = array(
			'access_role' => 'subscriber',
			'admin_role' => 'administrator',
			'create_banner_role' => 'editor',
			'manage_all_banners_role' => 'administrator',
            'create_adzone_role' => 'editor',
            'manage_all_adzones_role' => 'administrator',
            'create_campaign_role' => 'editor',
            'manage_all_campaigns_role' => 'administrator'
		);
		//$roles = wp_parse_args(get_option('_adning_roles', array()), $default_roles);
		$roles = wp_parse_args(ADNI_Multi::get_option('_adning_roles', array()), $default_roles);
		$admin_roles = wp_parse_args(ADNI_Multi::get_option('_adning_admin_roles', array()), self::default_role_options());
		
		return array(
			'settings' => apply_filters('ADNI_main_settings', $settings),
			'roles' => apply_filters('ADNI_default_roles', $roles),
			'admin_roles' => apply_filters('ADNI_default_admion_roles', $admin_roles)
		);	
	}



	/** 
	 * Merge multidimentional arrays.
	 * https://gist.github.com/ptz0n/1646171
	 * 
	 * ADN_Main::array_merge_recursive_distinct( $defaults, $args );
	*/
	/*public static function array_merge_recursive_distinct()
	{
		$arrays = func_get_args();
		$base = array_shift($arrays);
		foreach($arrays as $array) {
			reset($base);
			while(list($key, $value) = @each($array)) {
				// @since v1.1.7.2 added array_key_exists to prevent PHP notices for missing key
				if(is_array($value) && array_key_exists($key, $base) && @is_array($base[$key])) {
				//if(is_array($value) && @is_array($base[$key])) {
					$base[$key] = self::array_merge_recursive_distinct($base[$key], $value);
				}
				else {
					$base[$key] = $value;
				}
			}
		}
		return $base;
	}*/

	/** 
	 * Merge multidimentional arrays.
	 * http://php.net/manual/en/function.array-merge-recursive.php#102379
	 * 
	 * ADN_Main::array_merge_recursive_distinct( $defaults, $args );
	*/
	public static function array_merge_recursive_distinct( $defaults, $args )
	{
		foreach($args as $key => $Value)
		{
			if(array_key_exists($key, $defaults) && is_array($Value))
			{
				$defaults[$key] = self::array_merge_recursive_distinct($defaults[$key], $args[$key]);
			}
			else
			{
				$defaults[$key] = $Value;
			}
		}

		return $defaults;
	}



	// https://gist.github.com/RadGH/84edff0cc81e6326029c
	public static function number_format_short( $n, $precision = 1 ) 
	{
		if ($n < 900) {
			// 0 - 900
			$n_format = number_format($n, $precision);
			$suffix = '';
		} else if ($n < 900000) {
			// 0.9k-850k
			$n_format = number_format($n / 1000, $precision);
			$suffix = 'K';
		} else if ($n < 900000000) {
			// 0.9m-850m
			$n_format = number_format($n / 1000000, $precision);
			$suffix = 'M';
		} else if ($n < 900000000000) {
			// 0.9b-850b
			$n_format = number_format($n / 1000000000, $precision);
			$suffix = 'B';
		} else {
			// 0.9t+
			$n_format = number_format($n / 1000000000000, $precision);
			$suffix = 'T';
		}
	    // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
	    // Intentionally does not affect partials, eg "1.50" -> "1.50"
		if ( $precision > 0 ) {
			$dotzero = '.' . str_repeat( '0', $precision );
			$n_format = str_replace( $dotzero, '', $n_format );
		}
		return $n_format . $suffix;
	}



	/**
	 * random_weight()
	 * Utility function for getting random values with weighting.
	 * Pass in an associative array, such as array('A'=>5, 'B'=>45, 'C'=>50)
	 * An array like this means that "A" has a 5% chance of being selected, "B" 45%, and "C" 50%.
	 * The return value is the array key, A, B, or C in this case.  Note that the values assigned
	 * do not have to be percentages.  The values are simply relative to each other.  If one value
	 * weight was 2, and the other weight of 1, the value with the weight of 2 has about a 66%
	 * chance of being selected.  Also note that weights should be integers.
	 * 
	 * https://stackoverflow.com/a/11872928/3481803
	 * 
	 * @param array $weightedValues
	*/
	public static function random_weight(array $weightedValues) 
	{
		$rand = mt_rand(1, (int) array_sum($weightedValues));
		foreach ($weightedValues as $key => $value) 
		{
			if( is_int($rand) && is_int($value) )
			{ 
				$rand -= $value;
				if ($rand <= 0) 
				{
					return $key;
				}
			}
		}
	}



	/**
	 * Setup banner probability variable
	 * This function makes sure the probability variable contains all linked banners.
	 */
	public static function get_banner_probability($a)
	{
		$linked_banners = $a['linked_banners'];
		$probability = $a['probability'];

		if(!empty($linked_banners))
		{
			foreach($linked_banners as $banner_id)
			{
				if(!array_key_exists($banner_id, $probability))
				{
					$probability[$banner_id] = 50;
				}
				else
				{
					// Add value to empty values
					if(empty($probability[$banner_id]))
					{
						$probability[$banner_id] = 50;
					}
				}
			}

			// https://tunasite.com/frm_topics/huge-performance-problems-with-current-version/
			foreach(array_keys($probability) as $banner_id)
			{
				if (!in_array($banner_id, $linked_banners))
				{
					unset($probability[$banner_id]);
				}
			}

		}

		return $probability;
	}


	/**
	 * Shuffle array by probability value
	 * https://gist.github.com/LaffinToo/1543242
	 */
	public static function shuffle_probability($items)
	{
		//print_r($items);
		// How many loops should we do
		$loops = count($items)*100;

		// Find the total weight of all items
		$total_weight = 0;
		$new_items = array();
		foreach($items as $key => $val)
		{
			// Set default 50% if $val is empty 
			$val = $val === '' ? 50 : $val;
			$val = !is_numeric($val) ? 50 : $val;
			
			$new_items[$key] = array(
				$key,
				$val,
				$total_weight,
				$val + $total_weight
			);
			$total_weight = $val + $total_weight;
		}
	
		// initialize our stat counter
		foreach(array_keys($new_items) as $val)
		{
			$stat[$val] = 0;
		}
	
		for($loop = 0; $loop < $loops; $loop++)
		{
			$chance = rand(0, $total_weight);
			// Check Against Item Percent Markers
			$marker = 0;
			foreach($new_items as $key => $val)
			{
				if($chance <= $val[3]) break;
			}
			// Statistics
			$stat[$key]++;
		}
	
		//echo '<pre>'.print_r($new_items, true).'</pre>';
		//echo '<pre>'.print_r($stat, true).'</pre>';
		arsort($stat);
	
		//print_r($stat).'<br>';
		$shuffled = array();
		foreach($stat as $st => $val)
		{
			$shuffled[] = $new_items[$st][0];
		}
		//echo '<pre>'.print_r($shuffled,true).'</pre>';
		return $shuffled;
		
		/*
		$k = self::random_weight($items);
		unset($items[$k]);

		$shuffled = array($k);
		
		if(!empty($items))
		{
			arsort($items);
			foreach($items as $key => $prob)
			{
				$shuffled[] = $key;
			}
		}
		echo '<pre>'.print_r($shuffled,true).'</pre>';

		return $shuffled;
		*/
	}




	/*
	 * Handle Form Fields and save them to array
	 *
	 * @access public
	 * @return array
	*/
	public static function handle_form_fields($_post, $settings)
	{
		foreach($_post as $key => $post){
			
			if( is_array($post) ){
				$settings[$key] = self::handle_form_fields($post, $settings[$key]);
			}else{
				$settings[$key] = $post;
			}
		}
		return $settings;
	}
		
	
	
	/*
	 * Link Masking
	 *
	 * @access public
	 * @return url
	*/
	public static function link_masking($args = array()) // $id, $adzone_id = 0, 
	{
		$def = array(
			'id' => 0,
			'adzone_id' => 0,
			'bg_ad' => '' // left | top | right
		);
		$args = ADNI_Main::parse_args($args, $def);

		$bg_ad = !empty($args['bg_ad']) ? '&bgskin='.$args['bg_ad'] : '';
		$adzone_str = !empty($args['adzone_id']) ? '&aid='.$args['adzone_id'] : '';
		// Time string to prevent caching issues
		$time_str = '&t='.current_time('timestamp');
		return get_bloginfo('url').'?_dnlink='.$args['id'].$adzone_str.$bg_ad.$time_str;
	}


	
	/*
	 * Auto Positioning array
	 * Array containing banner / Adzone ids that are linked to an auto positioning spot.
	 *
	 * @access public
	 * @return array
	*/
	public static function auto_positioning($args = array())
	{
		//return get_option('_adning_auto_positioning', $args);
		return ADNI_Multi::get_option('_adning_auto_positioning', $args);
	}





	public static function remove_smart_quotes($content) 
	{
		$content= str_replace(
		array("\xe2\x80\x98", "\xe2\x80\x99", "\xe2\x80\x9c", "\xe2\x80\x9d", "\xe2\x80\x93", "\xe2\x80\x94", "\xe2\x80\xa6"),
		array("'", "'", '"', '"', '-', '--', '...'), $content);
		
		$content= str_replace(
		array(chr(145), chr(146), chr(147), chr(148), chr(150), chr(151), chr(133)),
		array("'", "'", '"', '"', '-', '--', '...'), $content);

		$content = str_replace('&#8220;', '&quot;', $content);
		$content = str_replace('&#8221;', '&quot;', $content);
		$content = str_replace('&#8243;', '&quot;', $content);
     	$content = str_replace('&#8216;', '&#39;', $content);
		$content = str_replace('&#8217;', '&#39;', $content);
		
		
		return $content;
	}

	
	
	
	/*
	 * Common Banner Sizes
	 *
	 * @access public
	 * @return array
	*/
	public static function banner_sizes()
	{
		return apply_filters('adning_banner_sizes', array(
			array('size' => '125x125', 'name' => __('square button','adn')),
			array('size' => '300x250', 'name' => __('medium rectangle','adn')),
			array('size' => '300x100', 'name' => __('mobile medium rectangle','adn')),
			array('size' => '728x90', 'name' => __('leaderboard','adn')),
			array('size' => '728x300', 'name' => __('pop-under','adn')),
			array('size' => '234x60', 'name' => __('half banner','adn')),
			array('size' => '468x60', 'name' => __('full banner','adn')),
			array('size' => '120x600', 'name' => __('skyscraper','adn')),
			array('size' => '160x600', 'name' => __('wide skyscraper','adn')),
			array('size' => '240x600', 'name' => __('extra wide skyscraper','adn')),
			array('size' => '300x600', 'name' => __('half page ad','adn')),
			array('size' => '120x240', 'name' => __('vertical banner','adn')),
			array('size' => '240x400', 'name' => __('vertical rectangle','adn'))
		));
	}
	



	public static function default_role_options($role = '')
	{
		//global $wp_roles;

		$options = array(
			'administrator' => array(
				'manage_all_banners' => 1,
				'manage_banners' => 1,
				'manage_all_adzones' => 1,
				'manage_adzones' => 1,
				'manage_all_campaigns' => 1,
				'manage_campaigns' => 1,
			),
			'editor' => array(
				'manage_all_banners' => 0,
				'manage_banners' => 1,
				'manage_all_adzones' => 0,
				'manage_adzones' => 1,
				'manage_all_campaigns' => 0,
				'manage_campaigns' => 1,
			),
			'author' => array(
				'manage_all_banners' => 0,
				'manage_banners' => 0,
				'manage_all_adzones' => 0,
				'manage_adzones' => 0,
				'manage_all_campaigns' => 0,
				'manage_campaigns' => 0,
			),
			'contributor' => array(
				'manage_all_banners' => 0,
				'manage_banners' => 0,
				'manage_all_adzones' => 0,
				'manage_adzones' => 0,
				'manage_all_campaigns' => 0,
				'manage_campaigns' => 0,
			)
		);


		// BETA - add option for custom roles
		foreach(wp_roles()->roles as $key => $r)
		{
			if($key !== 'subscriber' && !array_key_exists($key, $options))
			{
				$options[$key] = array(
					'manage_all_banners' => 0,
					'manage_banners' => 0,
					'manage_all_adzones' => 0,
					'manage_adzones' => 0,
					'manage_all_campaigns' => 0,
					'manage_campaigns' => 0,
				);
			}
		}
		//$def_roles = wp_roles();
		//print_r($def_roles);
		/*unset($def_roles->roles['subscriber']);
		foreach($options as $key => $option)
		{
			unset($def_roles->roles[$key]);
		}
		
		if(!empty($def_roles->roles))
		{
			foreach($def_roles->roles as $key => $r)
			{
				$options[$key] = array(
					'manage_all_banners' => 0,
					'manage_banners' => 0,
					'manage_all_adzones' => 0,
					'manage_adzones' => 0,
					'manage_all_campaigns' => 0,
					'manage_campaigns' => 0,
				);
			}
		}*/
		
		return !empty($role) ? $options[$role] : $options;
	}
	
	
	/*
	 * List All Capabilities for specific User or Role in WordPress
	 *
	 * @access public
	 * @return array
	*/
	public static function capabilities($args = array())
	{
		$defaults = array(
		 'id' => get_current_user_id(),
		 'role' => '', 
		);
		$args = wp_parse_args($args, $defaults);
		
		// Capabilities for specific role
		if( !empty($args['role']) )
		{
			//echo 'k'.$args['role'].print_r(get_role($args['role']),true);
			return get_role( $args['role'] )->capabilities;
		}
		// Capabilities for specific user id
		else
		{
			$data = get_userdata( $args['id'] );
			if( is_object( $data)) 
			{
				return $data->allcaps;
				//echo '<pre>' . print_r( $data->allcaps, true ) . '</pre>';
			}	
		}
	}
	
	
	
	
	/*
	 * ADNING Capabilities for specific Role
	 *
	 * @access public
	 * @return string
	*/
	public static function ADNI_capability( $role = 'administrator' )
	{
		$caps = self::capabilities(array('role' => $role));
		//echo '<pre>'.print_r($caps,true).'</pre>';
		$check_for = array('activate_plugins','edit_others_posts','publish_posts','edit_posts','read');
		
		if(!empty($caps))
		{
			foreach($check_for as $cap)
			{
				if(array_key_exists($cap, $caps))
				{
					return $cap;
				}
			}
		}
		
		return 'activate_plugins';
	}




	/**
	 * Get all roles with specific capability
	 */
	public static function get_roles_with_cap($cap = '')
	{
		//$cap = ADNI_Main::ADNI_capability($set_arr['roles']['create_campaign_role']);
		$roles_with_cap = array();

		if( !empty($cap))
		{
			$editable_roles = array_reverse( get_editable_roles() );

			foreach($editable_roles as $key => $role)
			{
				// echo '<pre>'.print_r($role['capabilities'],true).'</pre>';
				if( array_key_exists($cap, $role['capabilities']) )
				{
					$roles_with_cap[] = $key;
				}
			}
		}

		return $roles_with_cap;
	}
	
	
	/**
	 * Create data-* attributes
	 * data-key="value"
	*/
	public static function create_data_attributes($args = array())
	{
		$h = '';
		if(!empty($args))
		{
			foreach ($args as $key => $value) 
			{
				$h.= ' data-'.$key.'="'.$value.'"';
			}
		}

		return $h;
	}


	public static function dropdown_roles( $selected = '' ) 
	{
		$r = '';
		$editable_roles = array_reverse( get_editable_roles() );
		
		foreach ( $editable_roles as $role => $details ) 
		{
			$name = translate_user_role($details['name'] );
			// preselect specified role
			if ( $selected == $role ){
				$r .= "\n\t<option selected='selected' value='" . esc_attr( $role ) . "'>$name</option>";
			} else {
				$r .= "\n\t<option value='" . esc_attr( $role ) . "'>$name</option>";
			}
		}

		return $r;
	}
	



	/*
	 * Get Visitor IP
	 *
	 * @access public
	 * @return IP
	*/
	public static function get_visitor_ip() 
	{
		// Check to see if we've already retrieved the IP address and if so return the last result.
		if( self::$IP !== false ) { return self::$IP; }
		
		// Check if cronjob is running
		$sapi_type = php_sapi_name();
		if(substr($sapi_type, 0, 3) == 'cli') { return self::$IP; }
	
		// By default we use the remote address the server has.
		$temp_ip = $_SERVER['REMOTE_ADDR'];
	
		// Check to see if any of the HTTP headers are set to identify the remote user.
		// These often give better results as they can identify the remote user even through firewalls etc, 
		// but are sometimes used in SQL injection attacks.
		if (getenv('HTTP_CLIENT_IP')) {
			$temp_ip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$temp_ip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_X_FORWARDED')) {
			$temp_ip = getenv('HTTP_X_FORWARDED');
		} elseif (getenv('HTTP_FORWARDED_FOR')) {
			$temp_ip = getenv('HTTP_FORWARDED_FOR');
		} elseif (getenv('HTTP_FORWARDED')) {
			$temp_ip = getenv('HTTP_FORWARDED');
		} 

		// Trim off any port values that exist.
		if( strstr( $temp_ip, ':' ) !== FALSE ) {
			$temp_a = explode(':', $temp_ip);
			$temp_ip = $temp_a[0];
		}
		
		// Check to make sure the http header is actually an IP address and not some kind of SQL injection attack.
		$long = ip2long($temp_ip);
	
		// ip2long returns either -1 or FALSE if it is not a valid IP address depending on the PHP version, so check for both.
		if($long == -1 || $long === FALSE) {
			// If the headers are invalid, use the server variable which should be good always.
			$temp_ip = $_SERVER['REMOTE_ADDR'];
		}

		// If the ip address is blank, use 127.0.0.1 (aka localhost).
		if( $temp_ip == '' ) { $temp_ip = '127.0.0.1'; }
		
		self::$IP = $temp_ip;
		
		return self::$IP;
	}




	/*
	 * Get all countries
	 *
	 * @access public
	 * @return null
	*/
	public static function get_countries() 
	{	
		$countries = array(
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic Of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Viet Nam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		);
		
		return $countries;
	}

	




	/**
	 * Has stats 
	 * (Check if a stats plugin is installed)
	 * $args[type] (string) "int" | "ext" (internal stats or external stats (like Google Analytics))
	 * $args[name] (string) (name of the stats option/plugin)
	 * 
	 * return array
	 */
	public static function has_stats($args = array(), $settings = array())
	{
		$defaults = array(
			'type' => '',
			'name' => ''
		);
		$args = self::parse_args($args, $defaults);

		$has_stats = array('int' => array(), 'ext' => array());
		if(empty($settings))
		{
			$set_arr = self::settings();
        	$settings = $set_arr['settings'];
		}

		// Check if smarTrack is active.
		if( class_exists('sTrack_DB') )
		{
			$has_stats['int'][] = 'smartrack';
		}

		// Google Analytics
		if( !empty($settings['ga_tracking_id']) )
		{
			$has_stats['ext'][] = 'google-analytics';
		}

		$has_stats = apply_filters('ADNI_has_stats',$has_stats);

		if(!empty($args['type']))
		{
			if(!empty($args['name']))
			{
				return array_key_exists($args['name'], $has_stats[$args['type']]) ? $has_stats[$args['type']][$args['name']] : '';
			}
			return array_key_exists($args['type'], $has_stats) ? $has_stats[$args['type']] : '';
		}

		return $has_stats;
	}



	/**
	 * Count Stats (from SmarTrack)
	 */
	public static function count_stats($args = array())
	{
		$defaults = array(
			'type' => 'impression',
			'unique' => 0,
			'group' => 'id_1', 
			'id' => 0,
			'time_range' => '' //custom_TIMESTAMP::TIMESTAMP
		);
		$args = wp_parse_args($args, $defaults);
		$has_stats = self::has_stats(array('type' => 'int'));
		$has_stats_ext = self::has_stats(array('type' => 'ext'));
		//print_r( $has_stats_ext );
		if( empty($has_stats) && empty($has_stats_ext) )
			return '';

		if( in_array('smartrack', $has_stats) )
		{
			$group_by = $args['group'] === 'id_1' ? 'ev.event_id' : 'ev.id_2,ev.id';
			$between = !empty($args['time_range']) ? array('key' => 'ev.tm', 'val' => sTrack_Core::time_range(array('condition' => $args['time_range']))) : array();
			
			$value = sTrack_DB::count_stats(array(
				'event_type' => $args['type'],
				//'group' => $group_by,
				'group' => $args['unique'] ? 'ev.id_1' : $group_by,
				//'group' => $args['unique'] ? 'ev.id_2' : $group_by,
				//'group' => $args['unique'] ? 'st.ip' : $group_by, //ev.event_id',
				//'group' => 'ev.id',
				'where' => array( array($args['group'], $args['id'])),
				'between' => $between,
				'unique' => $args['unique']
			));
			//echo $value.' '.$args['unique'].' - ';
		}

		if( in_array('google-analytics', $has_stats_ext) )
		{
			if( is_plugin_active( 'adning-google-analytics/adning-google-analytics.php' ) )
			{
				$value = ADNIGA_Init::load_stats(array(
					'start_date' => '2012-01-12',
					'end_date' => 'today', // 1daysAgo
					'id' => $args['id'], // banner/adzone id
					'post_type' => get_post_type($args['id']),
					'stats_type' => $args['type']
				));
				
			}
			//echo 'EXT google';
		}

		return $value;
	}




	/**
	 * Load advertisers
	 */
	public static function load_advertisers()
	{
		$banners = ADNI_CPT::get_posts(array(
			'post_type'  => ADNI_CPT::$banner_cpt,
		));

		$advertisers = array();
		if(!empty($banners))
		{
			foreach($banners as $banner)
			{
				if(!in_array($banner->post_author, $advertisers))
				{
					$advertisers[] = $banner->post_author;
				}
			}
		}

		return $advertisers;
	}



	/**
	 * Reset Stats
	 */
	public static function reset_stats($id, $group = '')
	{
		$has_stats = self::has_stats(array('type' => 'int'));

		if( $has_stats )
		{
			if( in_array('smartrack', $has_stats) )
			{
				$GLOBALS[ 'wpdb' ]->query("UPDATE ".$GLOBALS[ 'wpdb' ]->prefix."strack_ev SET ".$group." = '0' WHERE ".$group." = '".$id."'");

				//sTrack_DB::delete_stats(array('delete' => 'ev.*', 'id' => $id, 'group' => 'id_2'));
				sTrack_DB::delete_stats(array('delete' => 'ev.*', 'where' => array(
						array('ev.id_1','0'),
						array('ev.id_2','0'),
						array('ev.id_3','0')
					) 
				));
			}
		}
	}



	/**
	 * Remove folder
	 */
	public static function delete_dir($dir) 
	{
		$res = array('removed' => 0, 'msg' => __('Folder could not be removed. Please try again.','adn'));
		$dirPath = '';

		if (! is_dir($dir)) {
			//throw new InvalidArgumentException("$dir must be a directory");
			return array('removed' => 0, 'msg' => sprintf(__('Error: %s is no directory.','adn'), $dir));
		}
		if(! is_writable($dir)) {
			return array('removed' => 0, 'msg' => sprintf(__('Error: permission denied. %s','adn'), $dir));
		}
		if (substr($dir, strlen($dir) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dir . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::delete_dir($file);
			} else {
				unlink($file);
			}
		}
		if( is_dir($dir) )
			rmdir($dir);

		return array('removed' => 1, 'msg' => __('Folder removed successfully.','adn'));
	}



	
	public static function sanitize_ad_content($content = '')
	{
		$set_arr = ADNI_Main::settings();
		$settings = $set_arr['settings'];

		return $settings['upload']['sanitize'] ? wp_kses_post($content) : $content;

	}
	




	/**
	 * ADD-ONS install functions
	 */
	public static function silent_permission_check() 
	{
		// Silent permission check
		ob_start();
		$creds = request_filesystem_credentials( '', '', false, false, null );
		ob_get_clean();
	
		// Abort if permissions were not available.
		if ( ! WP_Filesystem( $creds ) )
		  	return false;
	
		return true;
	}

	public static function plugin_installed( $plugin ) 
	{
		return file_exists( WP_PLUGIN_DIR . '/' . $plugin );
	}


	public static function install_plugin( $args ) 
	{
		$args = wp_parse_args( $args, array(
			'plugin'   => '', // the plugin folder name
			'package'  => '', //The full local path or URI of the package.
			'activate' => false
		));

		// Nothing to do if already installed
		if ( self::plugin_installed( $args['plugin'] ) ) 
		{
			ADNI_Init::error_log( sprintf(__( 'Plugin %s already installed.', 'adn' ), $args['plugin']) );
			return new WP_Error( 'adning-addons-extensions', sprintf(__( 'Plugin %s already installed.', 'adn' ), $args['plugin']) );
		}

		// Run an early permissions check silently to avoid output from the native one
		if ( !self::silent_permission_check() ) 
		{
			ADNI_Init::error_log(__( 'Your WordPress file permissions do not allow plugins to be installed.', 'adn' ));
			return new WP_Error( 'adning-addons-extensions', sprintf(__( 'Your WordPress file permissions do not allow plugins to be installed. You can %s and install it manually.', 'adn' ), '<a href="'.$args['package'].'" target="_blank">'.__('download the plugin','adn').'</a>') );
		}

		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ADNI_CLASSES_DIR . '/ADNING_PLU_Upgrader_Skin.php';

		$skin = new ADNING_PLU_Upgrader_Skin( array( 'plugin' => $args['plugin'] ) );
		$upgrader = new Plugin_Upgrader( $skin );
		$upgrader->install( $args['package'] );

		if ( $args['activate'] ) 
		{
			$activate = activate_plugin( $upgrader->plugin_info(), '', false, true );
			if ( is_wp_error( $activate ) ) 
			{
				return $activate;
			}
		}

		return $skin->result;
	}





	/*
	 * BANNER RSS FEED
	 *
	 * @access public
	 * @return rss
	*/
	public static function rss_feed( $ID = 0 )
	{	
		if( !empty( $ID ) || isset( $_GET['adning-rss'] ) && !empty( $_GET['adning-rss'] ) )
		{
			$html = '';
			$ID = !empty( $ID ) ? $ID : $_GET['adning-rss'];
			
			// http://kb.mailchimp.com/merge-tags/rss-blog/rss-item-tags
			// Mailchimp RSS code
			// *|RSSITEMS:|* *|RSSITEM:CONTENT_FULL|* *|END:RSSITEMS|*
			
			header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
			
			$html.= '<?xml version="1.0" encoding="UTF-8"?>';
			$html.= '<rss xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/" version="2.0">';	
				$html.= '<channel>';
					$html.= '<title>'.get_bloginfo('name').'</title>';
					$html.= '<atom:link href="'.get_bloginfo('url').'/?wppas-rss='.$ID.'" rel="self" type="application/rss+xml" />';
					$html.= '<link>'.get_bloginfo('url').'</link>';
					$html.= '<description><![CDATA['.get_bloginfo('description').']]></description>';
					$html.= '<lastBuildDate>'.date('r', current_time('timestamp')).'</lastBuildDate>';
					$html.= '<language>'.get_bloginfo('language').'</language>';
					$html.= '<generator>http://adning.com?v='.ADNI_VERSION.'</generator>';
					
					$data = do_shortcode('[ADNI_banner id="'.$ID.'" filter="0"]'); // rss=1
					
					$html.= '<item>';
						$html.= '<title>Banner</title>';
						$html.= '<link>'.get_bloginfo('url').'</link>';
						$html.= '<guid isPermaLink="false">'.get_bloginfo('url').'/?adning-rss='.$ID.'</guid>';
						$html.= '<description><![CDATA[ '.get_the_title( $ID ).' ]]></description>';
						$html.= '<content:encoded><![CDATA['.$data.']]></content:encoded>';
						$html.= '<pubDate>'.date('r', current_time('timestamp')).'</pubDate>';
					$html.= '</item>';
	
				$html.= '</channel>';
			$html.= '</rss>';
			
			echo $html;
			
			exit();
		}
	}




	/**
	 * ADNI_Main::arr_key_exists('key', $array);
	 * Function to check if a specific key exists in an array or object.
	 * Custom function since array_key_exists is deprecated for objects since PHP 7.4 
	 */
	public static function arr_key_exists($key = '', $array = array())
    {
        if(is_array($array))
        {
            return array_key_exists($key, $array);
        }
        elseif(is_object($array))
        {
            return property_exists($array, $key);
        }
        else
        {
            return false;
        }
    }

}

endif;
?>