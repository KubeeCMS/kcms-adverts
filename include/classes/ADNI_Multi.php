<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Multi' ) ) :

class ADNI_Multi {

    /*
	 * Check if the plugin is network activated
	 *
	 * @access public
	 * @return bool
	*/
	public static function is_network_activated()
	{
		$active = 0;
		
		if( is_multisite() )
		{
			if( !function_exists( 'is_plugin_active_for_network' ) )
			{
				require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
				// Makes sure the plugin is defined before trying to use it
			}
			
			if( is_plugin_active_for_network( plugin_basename(ADNI_FILE) ) ) 
			{
				$active = 1;
			}
		}
		
		return $active;
    }
    


    /*
	 * Load site option - get_option() - for multisite installations.
	 * ADNI_multi::get_option();
	 *
	 * @access public
	 * @return array/string
	*/
	public static function get_option( $name, $value = '' )
	{
		if( $name == 'adning_activation' ) return true;
		global $wpdb;
		
		if( self::is_network_activated() )
		{
			$option = get_site_option($name, $value);
		}
		else
		{
			$option = get_option($name, $value);
		}
		
		return $option;
    }
    


    /*
	 * Update option - update_option() - for multisite installations.
	 * ADNI_multi::update_option();
	 *
	 * @access public
	 * @return null
	*/
	public static function update_option( $name, $value = '' )
	{
		global $wpdb;
		
		update_option($name, $value);
		
		if( self::is_network_activated() && is_main_site() )
		{
			update_site_option($name, $value);
		}
	}
	

	/*
	 * Get Post Meta - get_post_meta() - for multisite installations.
	 * ADNI_multi::get_post_meta();
	 *
	 * @access public
	 * @return null
	*/
	public static function get_post_meta( $id, $name, $value = '' )
	{
		self::wpmu_load_from_main_start();
			$value = get_post_meta($id, $name, $value);
		self::wpmu_load_from_main_stop();

		$value = !empty($value) && array_key_exists(0, $value) ? $value[0] : $value;
		
		$post_type = get_post_type($id);
		if(strtolower($post_type) === strtolower(ADNI_CPT::$banner_cpt))
		{
			$value = ADNI_Main::parse_args($value, ADNI_CPT::default_banner_args());
		}
		if(strtolower($post_type) === strtolower(ADNI_CPT::$adzone_cpt))
		{
			$value = ADNI_Main::parse_args($value, ADNI_CPT::default_adzone_args());
		}

		return $value;
	}



	/*
	 * Update Post Meta - update_post_meta() - for multisite installations.
	 * ADNI_multi::update_post_meta();
	 *
	 * @access public
	 * @return null
	*/
	public static function update_post_meta( $id, $name, $value = '' )
	{
		self::wpmu_load_from_main_start();
			update_post_meta($id, $name, $value);
		self::wpmu_load_from_main_stop();

		return $value;
	}



	
	/*
	 * get_post_type() - for multisite installations.
	 *
	 * @access public
	 * @return string
	*/
	public static function get_post_type( $id )
	{	
		self::wpmu_load_from_main_start();
			$post_type = get_post_type( $id );
		self::wpmu_load_from_main_stop();
		return $post_type;
	}



    /*
	 * Run shortcodes - do_shortcode() - for multisite installations.
	 *
	 * @access public
	 * @return string
	*/
	public static function do_shortcode( $shortcode )
	{	
		self::wpmu_load_from_main_start();
		    $value = do_shortcode( $shortcode );
		self::wpmu_load_from_main_stop();
		
		return $value;
	}
    


    /*
	 * MULTISITE get data from main site using set_blog_id() or switch_to_blog()
	 *
	 * @access public
	 * @return null
	*/
	public static function wpmu_load_from_main_start()
	{	
		if( self::is_network_activated() && !is_main_site() )
		{
			switch_to_blog( BLOG_ID_CURRENT_SITE );
		}
	}
	
	
	/*
	 * MULTISITE get data from main site using set_blog_id() or switch_to_blog()
	 *
	 * @access public
	 * @return null
	*/
	public static function wpmu_load_from_main_stop()
	{	
		if( self::is_network_activated() && is_main_site() )
		{
			restore_current_blog();	
		}
	}




	/*
	 * Check if specific admin data has to be loaded.
	 *
	 * @access public
	 * @return bool
	*/
	public static function load_admin_data()
	{
		$visible = 0;
		
		if( is_multisite() && self::is_network_activated() && is_main_site() || is_multisite() && !self::is_network_activated() || !is_multisite() )
		{
			$visible = 1;
		}
		
		return $visible;
	}
}
endif;
?>