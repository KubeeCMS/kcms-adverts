<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Frontend' ) ) :

class ADNI_Frontend {
	
	public function __construct() 
	{
        // Fronten AD Manager
		add_action( 'wp', array( __CLASS__, 'frontend_ad_manager' ), 4);
	}
	


	/*
	 * Frontend Header
	 *
	 * @access public
	 * @return html
	*/
	public static function header($args = array(), $settings = array())
	{
		$defaults = array(
			'menu' => array()
		);
		$args = wp_parse_args($args, $defaults);

		$h = '';
		$h.= '<header></header>';

		$h.= '<nav class="top_bar">'; // <!-- css_sticky -->
			$h.= '<div class="inner_content clear">';
				$h.= '<a href="">';
					$h.= '<span class="logo">';
						$h.= '<svg class="_dn_ani" title="Adning Advertising" viewBox="0 0 562 577.471" style="opacity: 1;"> <g> <path class="colorful c1" fill="none" stroke="#000000" stroke-width="6.3853" stroke-linecap="round" d=" M426.056,110.615c-4.884-17.071-12.84-32.996-18.556-49.722l7-4.857c27.306,14.704,52.93,36.27,71.752,54.109 c74.347,70.466,82.461,149.52,61.6,240.495c-0.918,4.004-2.994,11.451-4.861,18.039c-22.888,80.746-68.216,147.818-144.187,178.374 c-18.54,7.457-41.594,14.001-67.079,18.355C182.835,590.849,30.643,542.229,9.321,374.183c-2.956-23.294-5.007-58.902-2.305-88.062 C21.267,132.373,143.822-22.6,313.363,14.711" style="stroke-dasharray: 1717.16, 1717.16; stroke-dashoffset: 0;"></path> <path class="colorful c2" fill="none" stroke="#000000" stroke-width="5.5888" stroke-linecap="round" d=" M214.5,471.893c17.435-47.895,33.938-95.657,46.333-145.104c-16.147-1.152-32.94-1.004-49.236-1.965 c13.073-56.969,23.37-120.021,49.154-173.074c38.938-0.785,80.387-5.217,118.124,1.99c-29.695,37.611-61.574,76.14-81.897,119.612 c28.546,3.878,59.042-5.488,86.875-1.204c-56.624,57.441-88.036,107.949-128.104,158.994 c-12.692,16.169-25.556,35.718-38.998,47.001L214.5,471.893z" style="stroke-dasharray: 1005.03, 1005.03; stroke-dashoffset: 0;"></path> </g> </svg>';
					$h.= '</span>';
					$h.= '<span class="title">'.$settings['sell']['template']['logo_title'].'</span>';
					$h.= '<span class="side-title">';
						$h.= $settings['sell']['template']['side_title'];
					$h.= '</span>';
				$h.= '</a>';
		
				$h.= '<div class="header_menu">';
					
					if(!empty($args['menu']))
					{
						$h.= '<button type="button" class="navbar-toggle">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>';
						$h.= '<div id="main-menu" class="main_menu">';
							$h.= '<div class="button-close">';
								$h.= '<svg viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"></path></svg>';
							$h.= '</div>';
							$h.= '<ul id="menu-main" class="nav navbar-nav">';
								$h.= self::create_menu($args['menu']);
							$h.= '</ul>';
						$h.= '</div>'; // <!--#main-menu-->
					}
					
				$h.= '</div>';
		
			$h.= '</div>';
		$h.= '</nav>';

		return $h;
	}




	public static function content()
	{
		$is_frontend = 1;
		$view = isset($_GET['view']) && !empty($_GET['view']) ? $_GET['view'] : 'available_adzones';
		$set_arr = ADNI_Main::settings();
		$settings = $set_arr['settings'];
		$h = '';

		echo self::header(
			array(
				'menu' => self::menu($view)
			),
			$settings
		);

		echo '<div class="main">';
			echo '<div class="page_content">';
				echo '<div class="page_container">';
					if( $view === 'banner')
					{
						require(ADNI_TPL_DIR.'/single_banner.php'); 
						echo $h;
					}
					if( $view === 'user_dashboard')
					{
						require(ADNI_TPL_DIR.'/frontend_manager/sell/user_dashboard.php'); 
						echo $h;
					}
					if( $view === 'available_adzones')
					{
						require(ADNI_TPL_DIR.'/frontend_manager/sell/available_adzones.php'); 
						echo $h;
					}
				echo '</div>';
			echo '</div>';
		echo '</div>';

		echo self::footer($settings);
	}



	public static function menu($view = '')
	{
		$menu = array();
		$sell_settings = ADNI_Sell::sell_main_settings();
        $sell_settings = $sell_settings['sell'];

		if( $view === 'banner')
		{
			$menu = array(
				array(
					'name' => __('User Dashboard','adn'),
					'url' => $sell_settings['urls']['user_dashboard'],
					'selected' => 0
				),
				array(
					'name' => __('Available Adzones','adn'),
					'url' => $sell_settings['urls']['available_adzones'],
					'selected' => 0
				)
			);
		}
		if( $view === 'user_dashboard')
		{
			$menu = array(
				array(
					'name' => __('User Dashboard','adn'),
					'url' => $sell_settings['urls']['user_dashboard'],
					'selected' => 1
				),
				array(
					'name' => __('Available Adzones','adn'),
					'url' => $sell_settings['urls']['available_adzones'],
					'selected' => 0
				)
			);
			/*$menu = array(
				array(
					'name' => __('User Dasshboard','adn'),
					'url' => '#',
					'sub' => array(
						array(
							'name' => 'Post Ads',
							'url' => 'http://adning.com/what-is-a-banner-ad/',
						),
						array(
							'name' => 'Pre-Post Ads',
							'url' => 'http://adning.com/pre-post-ads/',
						)
					)
				)
			);*/
		}
		if( $view === 'available_adzones')
		{
			$menu = array(
				array(
					'name' => __('User Dashboard','adn'),
					'url' => $sell_settings['urls']['user_dashboard'],
					'selected' => 0
				),
				array(
					'name' => __('Available Adzones','adn'),
					'url' => $sell_settings['urls']['available_adzones'],
					'selected' => 1
				)
			);
		}

		return $menu;
	}



	public static function footer($settings = array())
	{
		$h = '';
		$h.= '<footer>';
			$h.= '<div class="footer_content">';
				$h.= '<section class="footer_description"></section>';
				
				$h.= '<nav>
					<!-- footer menu area -->
				</nav>';
				
				$h.= '<section class="footer_bottom">
					<div class="info_line">'.$settings['sell']['template']['footer_info'].'</div>

					<div class="legal">
						<div class="copyright">
							Copyright &copy; '.date('Y').' <a href="'.$settings['sell']['template']['footer_copy_url'].'" target="_blank">'.$settings['sell']['template']['footer_copy'].'</a>
							
							<div class="legal-menu"></div>
						</div>
					</div>
					
				</section>';

			$h.= '</div>';
		$h.= '</footer>';
		
		return $h;
	}





	public static function create_menu($menu_arr)
	{
		$h = '';
		foreach($menu_arr as $menu)
		{
			$has_sub = array_key_exists('sub', $menu) && !empty($menu['sub']) ? ' menu-item-has-children' : '';
			$h.= '<li class="menu-item menu-item-type-custom menu-item-object-custom'.$has_sub.'">';
				$selected = $menu['selected'] ? ' selected' : '';
				$h.= '<a href="'.$menu['url'].'" class="'.$selected.'">'.$menu['name'].'</a>';
				if( !empty($has_sub))
				{
					$h.= '<ul class="sub-menu">';
						$h.= self::create_menu($menu['sub']);
					$h.= '</ul>';
				}
			$h.= '</li>';
		}

		return $h;
	}




    /*
	 * Frontend AD Manager
	 *
	 * @access public
	 * @return null
	*/
	public static function frontend_ad_manager()
	{
		if( isset( $_GET['_ning_front'] ) && !empty( $_GET['_ning_front'] ) )
		{
			require(ADNI_TPL_DIR.'/frontend_manager/index.php');
			
			exit;
		}
	}
}
endif;
?>