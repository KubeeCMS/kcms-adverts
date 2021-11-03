<?php
/**
 * Usage:
 *
 * require_once(DIR. '/spr_columns/SPR_Columns.php');
 * new SPR_Columns();
 * SPR_Columns::enqueue_scripts(array('inc_url' => '', 'version' => 0));
 *
*/
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'SPR_Columns' ) ) :

class SPR_Columns {
	
	
	/*
	 * enqueue_scripts (but actually we only register most of them here)
	 *
	 * @access public
	 * @return null
	*/
	public static function enqueue_scripts($args = array())
	{
		$defaults = array(
			'version' => 1,
			'inc_url' => '',
			'inc_dir' => '',
		);
		$args = wp_parse_args($args, $defaults);
		
		// Scripts
		wp_enqueue_script( 'spr_inViewport_js', $args['inc_url']. '/extensions/spr_columns/assets/js/spr_inViewport.js', array( 'jquery' ), $args['version'], true );
		wp_enqueue_script( 'spr_parallax_js', $args['inc_url']. '/extensions/spr_columns/assets/js/parallax.min.js', array( 'jquery' ), $args['version'], true );
		
		wp_enqueue_style( 'spr_col_animate_css', $args['inc_url']. '/extensions/spr_columns/assets/css/animate.min.css', false, $args['version'], "all" );
		wp_enqueue_style( 'spr_col_css', $args['inc_url']. '/extensions/spr_columns/assets/css/spr_columns.css', false, $args['version'], "all" );
	}
}

endif;
?>