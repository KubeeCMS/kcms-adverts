<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;

if ( ! class_exists( 'ADNI_Shortcodes' ) ) :

class ADNI_Shortcodes {	
	
	
	public function __construct() 
	{
		add_shortcode('ADNI_banner', array(__CLASS__, 'sc_ADNI_banner'));
		add_shortcode('ADNI_adzone', array(__CLASS__, 'sc_ADNI_adzone'));
		add_shortcode('adning', array(__CLASS__, 'sc_adning'));
		add_shortcode('adning_popup', array(__CLASS__, 'sc_adning_popup'));
	}
	
	
	
	/**
	 * shortcode description
	 */
	public static function sc_adning($args = array(), $content = null) 
	{	
		$defaults = array(
			'id' => 0,
			'animation' => '',
			'no_iframe' => 1,
			'stats' => 1
		);
		$args = wp_parse_args( $args, $defaults );
		$html = '';
		$animation = !empty($args['animation']) ? ',"animation":"'.$args['animation'].'"' : '';
		//$post = ADNI_CPT::load_post($args['id']);
		
		//if( !empty($post))
		if( !empty($args['id']))
		{
			$post_type = ADNI_Multi::get_post_type( $args['id'] );
			//$post_type = $post['post']->post_type;
			
			ADNI_Init::enqueue(
				array(
					'files' => array(
						array('file' => '_ning_css', 'type' => 'style'),
						array('file' => '_ning_global', 'type' => 'script')
					)
				)
			);
			
			if( strtolower($post_type) == strtolower(ADNI_CPT::$banner_cpt))
			{
				if(!$args['no_iframe'])
				{
					$html.= '<script type="text/javascript">var _ning_embed = {"id":'.$args['id'].',"width":'.$post['args']['size_w'].',"height":'.$post['args']['size_h'].$animation.'};</script>';
					$html.= '<script type="text/javascript" src="'.get_bloginfo('url').'?_dnembed=true"></script>';
				}
				else
				{
					$html.= self::sc_ADNI_banner($args);
				}
			}
			else
			{
				if(!$args['no_iframe'])
				{
					$html.= '<script type="text/javascript">var _ning_embed = {"id":'.$args['id'].',"width":'.$post['args']['size_w'].',"height":'.$post['args']['size_h'].$animation.'};</script>';
					$html.= '<script type="text/javascript" src="'.get_bloginfo('url').'?_dnembed=true"></script>';
				}
				else
				{
					//$html.= '<pre>'.print_r($args, true).'</pre>';
					$html.= self::sc_ADNI_adzone($args);
				}
			}
		}
		
		return $html;
	}
	
	
	
	public static function sc_ADNI_banner($args = array(), $content = null) 
	{	
		$defaults = array(
			'id' => 0,
			'load_script' => 1,
			'stats' => 1
		);
		$args = wp_parse_args( $args, $defaults );
		
		return ADNI_Templates::banner_tpl($args['id'], $args);
	}
	
	
	
	
	public static function sc_ADNI_adzone($args = array(), $content = null) 
	{	
		$defaults = array(
			'id' => 0,
			'stats' => 1
		);
		$args = wp_parse_args( $args, $defaults );
		
		//return '<pre>'.print_r($args, true).'</pre>';
		return ADNI_Templates::adzone_tpl($args['id'], $args);
	}




	public static function sc_adning_popup($args = array(), $content = null) 
	{	
		$defaults = array(
			'id' => '', // banner id
			'style' => '', // string, style="sidebar-bg-color:#18c4d0, btn-bg-color:yellow"
			'hidden_fields' => '',
			'input' => ''
		);
		$args = wp_parse_args( $args, $defaults );

		if(!empty($args['style']))
		{
			$st = explode(',', $args['style']);
			$args['style'] = array();
			foreach($st as $s)
			{
				$item = explode(':', trim($s));
				$args['style'][trim($item[0])] = trim($item[1]);
			}
		}
		else
		{
			unset($args['style']);
		}

		if(!empty($args['hidden_fields']))
		{
			$st = explode(',', $args['hidden_fields']);
			$args['hidden_fields'] = array();
			foreach($st as $s)
			{
				$item = explode(':', trim($s));
				$args['hidden_fields'][] = array('name' => trim($item[0]), 'value' => trim($item[1]));
			}
		}
		else
		{
			unset($args['hidden_fields']);
		}

		if(!empty($args['input']))
		{
			$st = explode(',', $args['input']);
			$args['input'] = array();
			foreach($st as $s)
			{
				$item = explode(':', trim($s));
				$args['input'][trim($item[0])] = trim($item[1]);
			}
		}
		else
		{
			unset($args['input']);
		}
		//print_r($args['style']);
		
		return ADNI_Templates::popup_tpl($args);
	}
}

//new ADNI_Shortcodes();

endif;
?>