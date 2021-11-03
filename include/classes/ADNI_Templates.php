<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Templates' ) ) :

class ADNI_Templates {

	public static function logo_svg($args = array())
	{
		$defaults = array(
			'width' => '100%',
			'height' => '100%'
		);
		$args = wp_parse_args($args, $defaults);

		return '<svg width="'.$args['width'].'" height="'.$args['height'].'" viewBox="0 0 310 426"><g> <g id="bottom_xA0_Image_3_"> <g id="XMLID_2_"> <g> <path fill="#DEFF00" d="M237,225c-0.33,0-0.67,0-1,0C182.27,284.93,127.15,343.49,73,403c-0.851,0.18-0.94-0.39-1-1 c26.08-58.92,51.7-118.3,77-178C178,224.67,209.67,222.67,237,225z"/> </g> </g> </g> <g id="top_xA0_Image_3_"> <g id="XMLID_3_"> <g> <path fill="#DEFF00" d="M289,165c0,1.33,0,2.67,0,4c-17.86,18.48-34.641,38.03-52,57c-56,0-112,0-168,0c0-1,0-2,0-3 c21.5-64.83,42.62-130.05,63-196c46.33,0,92.67,0,139,0c-27.25,45.75-54.78,91.22-81,138C223,165,256,165,289,165z"/> </g> </g> </g> </g> <g id="BACK_1_"> <g id="bottom_xA0_Image_1_"> <g id="XMLID_5_"> <g> <path fill="#84BA12" d="M149,225c0,1.33,0,2.67,0,4c-26.73,57.27-50.59,117.41-77,175c-16.021-1.31-37.3,2.63-50-2 c25.93-59.4,52.979-117.689,79-177C117,225,133,225,149,225z"/> </g> </g> </g> <g id="top_xA0_Image_1_"> <g id="XMLID_4_"> <g> <path fill="#84BA12" d="M133,27c0,1,0,2,0,3c-21.17,64.5-41.92,129.41-62,195c-16.33,0-32.67,0-49,0c0-1,0-2,0-3 C43.17,157.5,63.92,92.59,84,27C100.33,27,116.67,27,133,27z"/> </g> </g> </g> </g> </svg>';
	}

	
	public static function admin_header()
	{
		$html = '';
		$html.= '<div class="spr_row">';
       
			$html.= '<div class="spr_column">';
           	$html.= '<div class="spr_column-inner">';
            		$html.= '<div class="spr_wrapper">';
        				$html.= '<div style="margin: 0 0 10px; text-align:left; padding:10px; background-color:#FFF;">';
                     		$html.= '<div style="float:left;"><span class="adning_logo"></span></div>';
                        	$html.= '<div style="float:left; margin: 25px 0 0 15px;">';
                         		$html.= '<h3 style="margin:0;font-size:2.9em;display:inline-block;color:#32373c;">ADNING</h3>';
                            	$html.= '<h4 style="margin:0px 0 0 5px;font-size:1.5em;display:inline-block;color:#32373c;">- '.__('Advertising Ready To Strike','adn').'</h4>';
                         $html.= '</div>';
                        	$html.= '<div style="float:right;">';
                        		$html.= '<small>'.ADNI_VERSION.'</small> ';
                        	$html.= '</div>';
                        	$html.= '<div class="clearFix"></div>';
                    	$html.= '</div>';
                  $html.= '</div>';
                  //end .spr_wrapper
              $html.= '</div>';
              //end .spr_column-inner 
          $html.= '</div>';
          //end .spr_column
      $html.= '</div>';
      //end .spr_row -->';
		
		return $html;	
	}




	public static function main_admin_header($args = array())
	{
		$defaults = array(
			'page' => 'dashboard',
			'title' => 'Adning Premium Advertising',
			'desc' => '⚡ ' . __('Welcome! You are using the most powerful Advertising plugin for Wordpress. Let\'s get started!','adn'),
			'tabs' => 1,
			'errors' => array()
		);
		$args = wp_parse_args($args, $defaults);

		//$activation = get_option('adning_activation', array());
		$activation = ADNI_Multi::get_option('adning_activation', array());

		$html = '';
		$html.= '<div class="imc-heading-section adning-header">';
			$html.= '<h1>'.$args['title'].'</h1>';
			$html.= '<h3>'.$args['desc'].'</h3>';
			$html.= '<div class="adn-head-logo">';
				$html.= '<div class="logo" style="height: 80px;">'.self::logo_svg().'</div>';
				$html.= '<div class="adn-product-ver">';
					$html.= '<div>'.__('Version','adn').' '.ADNI_VERSION.'</div>';
				$html.= '</div>';
			$html.= '</div>';
		$html.= '</div>';

		// Error messages
		if(!empty($args['errors']))
        {
			$html.= '<div class="_ning_notices">';
                foreach($args['errors'] as $err)
                {
					$type = array_key_exists('type', $err) ? '<strong>'.ucfirst($err['type']).':</strong> ' : '';
                    $html.= '<div class="error_msg">'.$type.$err['msg'].'</div>';
                }
			$html.= '</div>';
		}

		// Menu options
		if( $args['tabs'])
		{
			$settings_tabs = apply_filters('ADNI_settings_tabs', array(
				'dashboard' => array('text' => __('About','adn'), 'page' => 'adning', 'data-tab' => 'about-adning'),
				'settings' => array('text' => __('General Settings','adn'), 'page' => 'adning-settings', 'data-tab' => 'adning-settings'),
				'role-manager' => array('text' => __('Role Manager','adn'), 'page' => 'adning-role-manager', 'data-tab' => 'adning-role-manager'),
				'updates' => array('text' => __('Product License','adn'), 'page' => 'adning-updates', 'data-tab' => 'adning-updates'),
			));

			$html.= '<div class="adning-settings-wrapper">';
				$html.= '<h2 class="nav-tab-wrapper">';
					if(!empty($settings_tabs))
					{
						foreach($settings_tabs as $key => $tab)
						{
							$active = $args['page'] === $key ? ' nav-tab-active' : '';
							if( $key === 'updates' )
							{
								$activated = !empty($activation) ? '' : ' style="background-color:#d4ff00;"';
								$activation_title = !empty($activation) ? '' : ' title="'.__('Your license has not yet been activated.','adn').'"';
							}
							$html.= '<a href="?page='.$tab['page'].'" data-tab="'.$tab['data-tab'].'" class="nav-tab'.$active.'"> '.$tab['text'].' </a>';
						}
					}
				$html.= '</h2>';
			$html.= '</div>';
		}
		
		return $html;	
	}


	// ABOUT TABS
	public static function about_tabs($args = array())
	{
		$defaults = array(
			'tab' => 'faq'
		);
		$args = wp_parse_args($args, $defaults);

		$h = '';
		$tab = $args['tab'];
		//$activation = get_option('adning_activation', array());
		$activation = ADNI_Multi::get_option('adning_activation', array());

		// Menu options
		$m_new = $tab == 'new' ? ' nav-tab-active' : '';
		$m_faq = $tab == 'faq' ? ' nav-tab-active' : '';
		$m_addons = $tab == 'addons' ? ' nav-tab-active' : '';
		$m_resources = $tab == 'resources' ? ' nav-tab-active' : '';

		$h.= '<div class="adning-about-tabs">';
			$h.= '<h2 class="nav-tab-wrapper">';
				//$h.= '<a href="?page=adning&tab=new" data-tab="whats-new" class="nav-tab'.$m_new.'"> '.__('What\'s New','adn').' </a>';
				$h.= '<a href="?page=adning&tab=faq" data-tab="faq" class="nav-tab'.$m_faq.'"> '.__('FAQ','adn').' </a>';
				$h.= current_user_can(ADNI_ADMIN_ROLE) ? '<a href="?page=adning&tab=addons" data-tab="addons" class="nav-tab'.$m_addons.'"> '.__('Add-Ons','adn').' </a>' : '';
				$h.= '<a href="?page=adning&tab=resources" data-tab="resources" class="nav-tab'.$m_resources.'"> '.__('Resources','adn').' </a>';
				$h.= empty($activation) && current_user_can(ADNI_ADMIN_ROLE) ? '<a href="?page=adning-updates" data-tab="updates" class="nav-tab" style="background-color:#d4ff00;"> '.__('Product License','adn').' </a>' : '';
			$h.= '</h2>';
		$h.= '</div>';
		
		return $h;	
	}


	
	
	/**
	 * Main banner container
	*/
	public static function banner_tpl($id, $args = array())
	{
		$defaults = array(
			'add_url' => 1,
			'load_script' => 1,
			'animation' => '',
			'in_adzone' => array(),
			'stats' => 1,
			'filter' => 1, // run show/hide function
			'return' => 'string' // string (just the default banner content) | array (array containing banner object and content)
		);
		$args = wp_parse_args($args, $defaults);

		$html = '';
		
		$banner = ADNI_CPT::load_post( $id, array('filter' => $args['filter']) );
		if( empty($banner) )
			return '';

		$adzone_id = !empty($args['in_adzone']) ? $args['in_adzone']['post']->ID : 0;
		// if args stats is empty it has to overwrite the single banner stats.
		$save_stats = empty($args['stats']) ? 0 : $banner['args']['enable_stats'];
		$loaded_id = '';
		
		//echo '<pre>'.print_r($banner,true).'</pre>';
		
		// Filter -------------------------------------------------------
		if( $save_stats )
		{
			if(!is_admin())
			{
				// Add to loaded banners and return the loaded id
				$loaded_id = apply_filters('adning_loaded_banners', $banner, $args);
				
				do_action('adning_save_stats', array(
				//apply_filters('adning_save_stats', array(
					'type' => 'impression',
					'banner_id' => $id,
					'adzone_id' => $adzone_id,
					'advertiser_id' => $banner['post']->post_author
				));
			}
		}

		$b = $banner['args'];
		
		$url = !empty($b['banner_url']) ? $b['banner_url'] : '';
		//$url = preg_replace( "/\r|\n/", "", $url );
		//$html.= '<pre>'.print_r($b,true).'</pre>';
		$content = apply_filters('adning_banner_content', $b['banner_content'], $banner);
		
		// Sizes
		$banner_w = is_numeric($b['size_w']) ? $b['size_w'].'px' : '100%';
		$banner_h = is_numeric($b['size_h']) ? $b['size_h'].'px' : 'inherit';
		$banner_h = !$b['responsive'] ? $banner_h : 'inherit';
		$bg_color = !empty($b['bg_color']) ? ' background:'.$b['bg_color'].';' : '';
		
		$url = $b['banner_link_masking'] && !empty($url) ? ADNI_Main::link_masking(array('id' => $id, 'adzone_id' => $adzone_id)) : $url;
		$nofollow = $b['banner_no_follow'] ? ' rel="nofollow"' : '';
		$responsive_class = $b['responsive'] && !$adzone_id ? ' responsive' : '';
		$scale_class = $b['banner_scale'] ? ' scale' : '';
		$inner_size = $b['banner_scale'] ? 'width:'.$banner_w.';height:'.$b['size_h'].'px;' : '';
		$container = $b['banner_container'] ? 1 : 0;
		$animation = !empty($args['animation']) ? ' data-animation="'.$args['animation'].'"' : '';
		$label = !$adzone_id ? $b['cont_label'] : '';
		$label_color = !empty($b['cont_label_color']) ? 'color:'.$b['cont_label_color'].';' : '';
		$label_pos = ' _'.$b['cont_label_pos'];
		$has_label = !empty($label) ? ' has_label' : '';
		$has_border = !$adzone_id && !empty($b['cont_border']) ? ' has_border' : '';
		$border_color = !empty($b['cont_border_color']) ? ' background:'.$b['cont_border_color'].';' : '';
		
		$ning_outer_class = !$adzone_id ? ' _ning_outer' : '';
		$align_class = ' _align_'.$b['align'];
		$clearfix_div = !$b['wrap_text'] ? '<div class="clear"></div>' : '';

		$strack_btn = !empty($url) && $save_stats ? ' strack_bnr' : '';
		$loaded_id_data = $loaded_id !== '' ? ' data-lid="'.$loaded_id.'"' : '';
		
		// Banner content
		$b_html = '';

		
		if(!$container)
		{
			$b_html.= '<div class="angwp_'.$id.' _ning_cont'.$strack_btn.' _ning_hidden'.$ning_outer_class.$align_class.$responsive_class.$scale_class.$has_label.$has_border.'" data-size="'.$b['size'].'"'.$animation.' data-bid="'.$id.'" data-aid="'.$adzone_id.'"'.$loaded_id_data.' style="max-width:'.$banner_w.'; width:100%;height:'.$banner_h.';'.$border_color.'">'; // height:inherit
				$b_html.= !$adzone_id ? '<div class="_ning_label'.$label_pos.'" style="'.$label_color.'">'.$label.'</div>' : '';
				$b_html.= '<div class="_ning_inner" style="'.$inner_size.$bg_color.'">';
					// Banner_url
					$b_html.= !empty($url) && $args['add_url'] ? '<a href="'.$url.'" class="strack_cli _ning_link" target="'.$b['banner_target'].'"'.$nofollow.'>&nbsp;</a>' : '';
					// Banner content
					$b_html.= ADNI_Multi::do_shortcode($content);
				$b_html.= '</div>';
			$b_html.= '</div>';
		}
		else
		{
			$b_html.= '<div class="angwp-outer-cont _ning_outer'.$strack_btn.$ning_outer_class.$align_class.$has_label.$has_border.'" style="--width:'.$b['size_w'].';--height:'.$b['size_h'].';'.$border_color.'">';
				$b_html.= !$adzone_id ? '<div class="_ning_label'.$label_pos.'" style="'.$label_color.'">'.$label.'</div>' : '';
				$b_html.= '<div class="angwp-cont" style="--width:'.$b['size_w'].';--height:'.$b['size_h'].';'.$bg_color.'">';
					$b_html.= '<div class="angwp-in">';
						// Banner_url
						$b_html.= !empty($url) && $args['add_url'] ? '<a href="'.$url.'" class="strack_cli _ning_link" target="'.$b['banner_target'].'"'.$nofollow.'>&nbsp;</a>' : '';
						// Banner content
						$b_html.= ADNI_Multi::do_shortcode($content);
					$b_html.= '</div>';
				$b_html.= '</div>';
			$b_html.= '</div>';
		}

		$b_html.= $clearfix_div;

		// Wrapper
		$html.= self::display_wrapper($banner, $b_html);
			
		
		// JS
		if($args['load_script'])
		{
			$js = '';
			$js.= '<script>';
				$js.= 'jQuery(document).ready(function($){';
					$js.= '$("._ning_cont").ningResponsive();';
				$js.= '});';
			$js.= '</script>';

			ADNI_Filters::$collect_js[$id] = $js;
		}
		
		return $args['return'] === 'array' ? array('content' => $html, 'banner' => $banner) : $html;
	}
	

	/**
	 * Load Random Single Banner - for ADzones
	 * 
	 * Loads a random banner from the adzone linked_banners array.
	 * And checks to make sure no empty banner (due to filters) gets returned.
	 * Unless all banners get filtered out this will always return content.
	 */
	public static function load_random_single_banner($adzone = array())
	{
		$a = $adzone['args'];
		$linked_banners = $a['linked_banners'];
		if( !empty($linked_banners))
		{
			$probability = ADNI_Main::get_banner_probability($a);
			$linked_banners = ADNI_Main::shuffle_probability($probability);
			
			/*$kID = ADNI_Main::random_weight($probability);
			$k = array_search ($kID, $linked_banners);*/
			$banner_filter = $a['no_banner_filter'] ? 0 : 1;
			$cont = self::banner_tpl($linked_banners[0], array( 'in_adzone' => $adzone, 'load_script' => 0, 'filter' => $banner_filter));
			//$cont = self::banner_tpl($linked_banners[$k], array( 'in_adzone' => $adzone, 'load_script' => 0, 'filter' => $banner_filter));
			
			if( empty($cont))
			{
				unset($linked_banners[0]);
				$adzone['args']['linked_banners'] = array_values($linked_banners);
				//$adzone['args']['linked_banners'] = $linked_banners;
				return self::load_random_single_banner($adzone);
			}
			else
			{
				return $cont;
			}
		}
	}
	
	
	public static function adzone_tpl($id = 0, $args = array())
	{
		$defaults = array(
			'stats' => 1,
			'filter' => 1 // run show/hide function
		);
		$args = wp_parse_args($args, $defaults);
		
		$html = '';
		
		if( !empty($id))
		{
			$adzone = ADNI_CPT::load_post( $id, array('filter' => $args['filter']) );
			if( empty($adzone) )
				return '';
			
			$a = $adzone['args'];
			$transition_time = !empty($a['adzone_transition_time']) ? $a['adzone_transition_time'] : 5;
			
			$adzone_content = self::adzone_content($adzone, $args, $transition_time);
			
			if( empty($adzone_content))
				return '';
			
			//echo '<pre>'.print_r($a, true).'</pre>';
			$rand_id = $id.'_'.rand(); // To fix conflicts with same adzones on one page.
			$label = $a['cont_label'];
			$label_color = !empty($a['cont_label_color']) ? 'color:'.$a['cont_label_color'].';' : '';
			$label_pos = ' _'.$a['cont_label_pos'];
			$has_label = !empty($label) ? ' has_label' : '';
			$has_border = !empty($a['cont_border']) ? ' has_border' : '';
			$border_color = !empty($a['cont_border_color']) ? ' background:'.$a['cont_border_color'].';' : '';
			//$responsive_class = $a['responsive'] ? ' responsive' : '';
			$css = $a['custom_css'];

			$align_class = ' _align_'.$a['align'];
			$clearfix_div = !$a['wrap_text'] ? '<div class="clear"></div>' : '';
			$is_grid_class = $a['load_grid'] ? ' _ning_grid' : '';

			$grid_align = array('left' => 'justify-content-start', 'center' => 'justify-content-center', 'right' => 'justify-content-end');

			$_ning_outer_style = !$a['load_grid'] ? 'max-width:'.$a['size_w'].'px;width:100%;overflow:hidden;' : 'width:inherit;display: table;';
			$_ning_zone_inner_style = !$a['load_grid'] ? 'width:'.$a['size_w'].'px; height:'.$a['size_h'].'px;' : '';
			$u_slides_style = !$a['load_grid'] ? 'position:absolute; overflow:hidden; left:0px; top:0px;width:'.$a['size_w'].'px; height:'.$a['size_h'].'px;' : '';

			$a_html = '';
			
			$a_html.= '<div class="angwp_'.$id.' _ning_outer ang_zone_'.$id.' _ning_jss_zone'.$has_label.$has_border.$align_class.$is_grid_class.'" style="'.$_ning_outer_style.'height:inherit;'.$border_color.'">';
				$a_html.= '<div class="_ning_label'.$label_pos.'" style="'.$label_color.'">'.$label.'</div>';
				$a_html.= '<div id="_ning_zone_'.$rand_id.'" class="_ning_zone_inner" style="'.$_ning_zone_inner_style.'position:relative;">';
					$a_html.= '<div u="slides" style="'.$u_slides_style.'">';
						
						$a_html.= $adzone_content;

					$a_html.= '</div>';
				$a_html.= '</div>';
			$a_html.= '</div>';

			$a_html.= $clearfix_div;

			// Wrapper
			$html.= self::display_wrapper($adzone, $a_html);
			//$html.= apply_filters('adning_adzone_content', self::display_wrapper($adzone, $a_html), $adzone);
			
			// @since v1.2.6 JS gets collected and added to footer
			$js = '';
			$js.= '<script>';
				$js.= 'jQuery(document).ready(function($){';

					if( !$a['load_grid'] )
					{
						// Create options object
						$js.= 'var options_'.$rand_id.' = {';
							$js.= '$ArrowKeyNavigation:false,';
							$js.= '$DragOrientation:0,';
						$js.= '};';


						if( !empty( $a['linked_banners'] ) && !$a['load_single'] )
						{
							// https://www.jssor.com/development/api-methods-properties-events.html
							$js.= 'var _SlideshowTransitions_'.$rand_id.' = ['.$a['adzone_transition'].'];';
							
							// Extend options object
							$js.= 'options_'.$rand_id.'.$AutoPlay = 1;';
							$js.= 'options_'.$rand_id.'.$ArrowKeyNavigation = false;';
							$js.= 'options_'.$rand_id.'.$DragOrientation = '.$a['touch_scroll'].';';
							$js.= 'options_'.$rand_id.'.$SlideshowOptions = {';
								$js.= '$Class:$JssorSlideshowRunner$,';
								$js.= '$Transitions:_SlideshowTransitions_'.$rand_id.',';
								$js.= '$TransitionsOrder:1,';
								$js.= '$ShowLink:true';
							$js.= '};';

							/**
							 * fires when carousel stopped sliding and slides park in correct position
						     * slideIndex: the index of current slide
							 * fromIndex: the index of previous slide
							 **/
							$js.= 'function Slider_'.$rand_id.'_ParkEventHandler(slideIndex, fromIndex)
							{
								var slide_num = slideIndex+1;
								$("#_ning_zone_'.$rand_id.'").find(".slide").parent().removeClass("activeSlide");
								$("#_ning_zone_'.$rand_id.'").find(".slide_"+slide_num).parent().addClass("activeSlide");
								$("#_ning_zone_'.$rand_id.'").find(".slide").removeClass("active");
								$("#_ning_zone_'.$rand_id.'").find(".slide_"+slide_num).addClass("active");
							}';
							

							/*$js.= 'function Slider_'.$rand_id.'_PositionChangeEventHandler(position, fromPosition, virtualPosition, virtualFromPosition)
							{
								console.log("changing position "+position);
								var slide_num = _ning_slider_'.$rand_id.'.$CurrentIndex()+1;
								//console.log(slide_num, "'.$rand_id.'", "_ning_slider_'.$rand_id.'");
								
								$("#_ning_zone_'.$rand_id.'").find(".slide").removeClass("active");
								$("#_ning_zone_'.$rand_id.'").find(".slide_"+slide_num).addClass("active");
								
								//continuously fires while carousel sliding
								//position: current position of the carousel
								//fromPosition: previous position of the carousel
								//virtualPosition: current virtual position of the carousel
								//virtualFromPosition: previous virtual position of the carousel
							}';*/
							//_ning_slider.$On($JssorSlider$.$EVT_POSITION_CHANGE, SliderPositionChangeEventHandler);';
						}
						
						$js.= 'if( $("#_ning_zone_'.$rand_id.'").length ){';
							$js.= 'var _ning_slider_'.$rand_id.' = new $JssorSlider$(\'_ning_zone_'.$rand_id.'\', options_'.$rand_id.');';
							
							//$js.= '_ning_slider_'.$rand_id.'.$On($JssorSlider$.$EVT_POSITION_CHANGE, Slider_'.$rand_id.'_PositionChangeEventHandler);';
							if( !empty( $a['linked_banners'] ) && !$a['load_single'] )
							{
								$js.= '_ning_slider_'.$rand_id.'.$On($JssorSlider$.$EVT_PARK, Slider_'.$rand_id.'_ParkEventHandler);';
							}
							//$js.= 'var slideIndex = _ning_slider_'.$rand_id.'.$CurrentIndex();';
							//$js.= 'console.log("changing position "+slideIndex);';
						$js.= '}';
					}


					//Scale slider after document ready
					$js.= 'ScaleSlider();';
					$js.= 'function ScaleSlider() {';
						$js.= 'var parentWidth = $(\'#_ning_zone_'.$rand_id.'\').parent().width();';
						$js.= 'if(parentWidth){';
							//$js.= 'console.log("'.$rand_id.': "+ parentWidth);';
							$js.= 'if( typeof _ning_slider_'.$rand_id.' !== "undefined" ){';
								$js.= '_ning_slider_'.$rand_id.'.$ScaleWidth(parentWidth);';
							$js.= '}';
						$js.= '}else{';
							$js.= 'window.setTimeout(ScaleSlider, 30);';
						$js.= '}';
						 
						// Run ningResponsive() to make sure banners are visible in admin area.
						$js.= '$("._ning_cont").ningResponsive();';
					$js.= '}';
												
					//Scale slider while window load/resize/orientationchange.
					$js.= '$(window).bind("load", ScaleSlider);';
					$js.= '$(window).bind("resize", ScaleSlider);';
					$js.= '$(window).bind("orientationchange", ScaleSlider);';
					
				$js.= '});';
			$js.= '</script>';
			
			ADNI_Filters::$collect_js[$rand_id] = $js;
			ADNI_Filters::$collect_css[$rand_id] = $css;
		}
		
		return $html;
	}
	


	/**
	 * Load adzone content
	 */
	public static function adzone_content($adzone, $args = array(), $transition_time = 0)
	{
		$a = $adzone['args'];
		$html = '';
		$h = '';
		$grid_align = array('left' => 'justify-content-start', 'center' => 'justify-content-center', 'right' => 'justify-content-end');

		// Load banners		
		if(!empty($a['linked_banners']))
		{
			// Check if only a single banner has to be loaded.
			if( $a['load_single'] )
			{
				if( $a['random_order'] )
				{
					return self::load_random_single_banner($adzone);
				}
				else
				{
					$banner_filter = $a['no_banner_filter'] ? 0 : 1;
					$banner_filter = empty($args['filter']) ? 0 : $banner_filter;
					$bnr_cont = self::banner_tpl($a['linked_banners'][0], array( 'return' => 'array', 'in_adzone' => $adzone, 'load_script' => 0, 'filter' => $banner_filter));
					return !empty($bnr_cont['content']) ? $bnr_cont['content'] : '';
				}
				//$a['linked_banners'] = array();
				//echo '<pre>'.print_r($a['linked_banners'],true).'</pre>';
			}
			else
			{
				// Check if random order is selected. If so, shuffle array.
				if( $a['random_order'] )
				{
					//shuffle($a['linked_banners']);
					$probability = ADNI_Main::get_banner_probability($adzone['args']);
					$a['linked_banners'] = ADNI_Main::shuffle_probability($probability);
					//echo '<pre>'.print_r($a['linked_banners'],true).'</pre>';
				}
			}

			if( !empty( $a['linked_banners'] ))
			{
				$html.= $a['load_grid'] ? '<div class="mjs_row '.$grid_align[$a['align']].'">' : '';
				$c = 1;
				$banner_count = 1;
				foreach($a['linked_banners'] as $i => $banner_id)
				{
					$banner_filter = $a['no_banner_filter'] ? 0 : 1;
					$banner_filter = empty($args['filter']) ? 0 : $banner_filter;

					$bnr_cont = self::banner_tpl($banner_id, array( 'return' => 'array', 'in_adzone' => $adzone, 'load_script' => 0, 'filter' => $banner_filter));
					
					if( !empty($bnr_cont['content']))
					{
						$transition_time = !empty($bnr_cont['banner']['args']['duration']) ? $bnr_cont['banner']['args']['duration'] : $transition_time;
						//$grid_resp = !$a['responsive'] ? 'max-width:'.$a['size_w'].'px;' : '';
						$h.= $a['load_grid'] ? '<div class="_ningzone_grid mjs_column mjs_col" style="max-width:'.$a['size_w'].'px;">' : '';

							$h.= '<div class="slide_'.$banner_count.' slide" idle="'.($transition_time*1000).'">';
								$h.= $bnr_cont['content'];
							$h.= '</div>';

						$h.= $a['load_grid'] ? '</div>' : '';

						// Handle grid items
						if( $a['load_grid'] )
						{
							if( $banner_count == ($a['grid_columns'] * $a['grid_rows']) )
								break;
							if( $c == $a['grid_columns'] ) // rows are horizontal
							{
								$h.= '<div class="w-100"></div>';
								$c = 0;
							}
							$c++;
						}

						$banner_count++;
					}
				}
				$html.= $h;
				$html.= $a['load_grid'] ? '</div>' : '';
			}
		}

		return !empty($h) ? $html : '';
	}





	/**
     * Display Wrapper
     */
    public static function display_wrapper( $post = array(), $content = '' ) 
    {
		if( empty($post['post']) )
			return $content;
			
		$b = $post['args'];
		$id = $post['post']->ID;
		$type = $b['type'];

        if(!empty($b['display']))
        {
            if(array_key_exists('parallax', $b['display']))
            {
				$para_active = $b['display']['parallax']['active'];

				if( $para_active )
				{
					ADNI_Init::enqueue(
						array(
							'files' => array(
								array('file' => '_ning_parallax_css', 'type' => 'style'),
								array('file' => '_ning_parallax', 'type' => 'script')
							)
						)
					);

					$url = '';
					if($b['type'] === 'banner')
					{
						$url = !empty($b['banner_url']) ? $b['banner_url'] : '';
						$url = $b['banner_link_masking'] && !empty($url) ? ADNI_Main::link_masking(array('id' => $id)) : $url;
					}

					$href = !empty($url) ? ' href="'.$url.'"' : '';

					$para_overflow = !$b['display']['parallax']['overflow'] ? ' overflow_hidden' : '';
					$para_y = $b['display']['parallax']['y'] !== '' ? $b['display']['parallax']['y'] : -100;
					$para_x = $b['display']['parallax']['x'] !== '' ? $b['display']['parallax']['x'] : 0;
					$para_bg = $b['display']['parallax']['bg'];
					$para_fb_bg = array_key_exists('fb_bg', $b['display']['parallax']) ? $b['display']['parallax']['fb_bg'] : '';
					$para_bg_color = !empty($b['display']['parallax']['bg_color']) ? 'background:'.$b['display']['parallax']['bg_color'].';' : '';
					$para_bg_speed = $b['display']['parallax']['bg_speed'] !== '' ? $b['display']['parallax']['bg_speed'] : 0.5;
					$para_bg_only = $b['display']['parallax']['bg_only'];

					// Check for video bg
					$video_data = self::parallax_video_data(array($para_bg, $para_fb_bg));
					$para_bg = !empty($video_data) ? '' : $para_bg;
					$para_fb_bg = self::parallax_is_video($para_fb_bg) ? '' : $para_fb_bg;

					$c = '';
					$c.= '<div data-jarallax data-speed="'.$para_bg_speed.'"'.$video_data.' class="_ning_parallax_container _ning_parallax_'.$id.$para_overflow.'" style="min-height:'.$b['size_h'].'px;'.$para_bg_color.'">';
						$c.= !empty($href) ? '<a'.$href.' class="parallax_link"></a>' : '';
						$c.= !empty($para_bg) ? '<img class="jarallax-img" src="'.$para_bg.'" alt="">' : '';
						$c.= !empty($para_fb_bg) ? '<img class="jarallax-img" src="'.$para_fb_bg.'" alt="">' : '';
						if( !$para_bg_only )
						{
							$c.= '<div data-jarallax-element="'.$para_y.' '.$para_x.'">';
								$c.= $content;
							$c.= '</div>';
						}
					$c.= '</div>';
					return $c;
				}
            }
        }

        return $content;
	}
	


	public static function parallax_video_data($para_bg = array())
	{
		$video_data_val = '';
		$video_data = '';
		if(!empty($para_bg))
		{
			foreach($para_bg as $i => $bg)
			{
				if(!empty($bg))
				{
					if(\strpos($bg, 'https://www.youtube.com/watch') !== false)
					{
						$video_data_val = $bg;
					}
					elseif(\strpos($bg, 'https://vimeo.com') !== false)
					{
						$video_data_val = bg;
					}
					else
					{
						$ext = pathinfo($bg, PATHINFO_EXTENSION);
						$vide_ext = array('mp4','webm','ogv','mov');
						$cma = !$i ? '' : ',';
						if( in_array($ext, $vide_ext))
						{
							$video_data_val.= $cma.$ext.':'.$bg;
						}
					}
					
				}
			}
		}

		return !empty($video_data_val) ? ' data-jarallax-video="'.$video_data_val.'"' : '';
	}


	public static function parallax_is_video($bg = '', $ext = '')
	{
		$vide_ext = array('mp4','webm','ogv','mov');

		if(!empty($bg))
		{
			$ext = empty($ext) ? pathinfo($bg, PATHINFO_EXTENSION) : $ext;
			if( in_array($ext, $vide_ext))
			{
				return true;
			}
			elseif(\strpos($bg, 'https://www.youtube.com/watch') !== false)
			{
				return true;
			}
			elseif(\strpos($bg, 'https://vimeo.com') !== false)
			{
				return true;
			}
		}

		return false;
	}
	
	
	
	public static function linked_banners_box($args = array())
	{
		$defaults = array(
			'banners' => array()
		);
		$args = wp_parse_args($args, $defaults);
		//print_r($args);
		
		$html = '';
		$html.= '<ul>';
		if(!empty($args['banners']))
		{
			foreach($args['banners'] as $banner_id)
			{
				$html.= '<li>'.get_the_title($banner_id).'</li>';
			}
		}
		else
		{
			$html.= '<li>'.__('No banners linked to this adzone yet.','adn').'</li>';
		}
		$html.= '</ul>';
		
		return $html;
	}




	public static function spr_column($args = array())
	{
		$defaults = array(
			'col' => '', // spr_col-6
			'class' => '',
			'style' => '',
			'title' => '',
			'content' => '',
			'desc' => ''
		);
		$args = wp_parse_args($args, $defaults);

		$col = !empty($args['col']) ? ' '.$args['col'] : '';
		$class = !empty($args['class']) ? ' '.$args['class'] : '';
		$style = !empty($args['style']) ? ' style="'.$args['style'].'"' : '';

		$h = '';
		$h.= '<div class="spr_column'.$col.$class.'"'.$style.'>';
			$h.= '<div class="input_container">';
				$h.= '<h3 class="title">'.$args['title'].'</h3>';
				$h.= '<div class="input_container_inner">';
					$h.= $args['content'];
				$h.= '</div>';
				$h.= '<span class="description bottom">'.$args['desc'].'</span>';
			$h.= '</div>';
		$h.= '</div>';

		return $h;
	}



	public static function itm_defaults()
	{
		$defaults = array(
			'title'       => '',
			'name'        => '', 
			'slug'        => '',
			'type'        => 'text',
			'id'          => '',
			'data'        => '',
			'placeholder' => '',
			'value'       => '',
			'class'       => '',
			'style'       => '',
			'script'      => '',
			'text-align'  => 'left',
			'height'      => '100px', // for textareas
			'width'       => 'auto',
			'size'        => '', // one_third
			'desc_pos'    => 'bottom', // bottom, top
			'desc'        => '',
			'content'     => '',
			'show_icon'   => 0,
			'icon'        => '',
			'icon_type'   => 'fa',
			'select_opts' => array(),
			'chk-on'      => __('ON','adn'),
			'chk-off'     => __('OFF','adn'),
			'chk_width'   => 50,
			'chk_height'  => 30,
			'chk_btn_width' => 30,
			'chk_labels_placement' => 'right',
			'chk_data-values' => '',
			'color_change' => '',
			'trigger_switch_button' => 0
		);
		
		return $defaults;
	}


	/**
	 * Tooltip
	*/
	public static function tooltip($args = array())
	{
		$defaults = array(
			'class' => '',
			'title' => '',
			'icon' => '<svg viewBox="0 0 512 512"><path fill="currentColor" d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-36 344h12V232h-12c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12h48c6.627 0 12 5.373 12 12v140h12c6.627 0 12 5.373 12 12v8c0 6.627-5.373 12-12 12h-72c-6.627 0-12-5.373-12-12v-8c0-6.627 5.373-12 12-12zm36-240c-17.673 0-32 14.327-32 32s14.327 32 32 32 32-14.327 32-32-14.327-32-32-32z" class=""></path></svg>'
		);
		$args = wp_parse_args($args, $defaults);

		$t = '';
		$class = !empty($args['class']) ? ' '.$args['class'] : '';
		$t.= '<span class="ttip'.$class.'" title="'.$args['title'].'">'.$args['icon'].'</span>';

		return $t;
	}



	/**
	 * Checkbox
	 * 
	*/
	public static function checkbox($args = array())
	{
		$defaults = array(
			'id' => '',
			'title' => '',
			'tooltip' => '',
			'class' => '',
			'name' => '',
			'data' => array(),
			'value' => '',
			'checked' => '',
			'disabled' => 0,
			'hidden_input' => 0 // to return 0 when check box is unchecked. will only work when a value is provided.
		);
		$args = wp_parse_args($args, $defaults);

		$id = !empty($args['id']) ? ' id="'.$args['id'].'"' : '';
		$check = !empty($args['checked']) ? ' checked="checked"' : '';
		$disabled = !empty($args['disabled']) ? ' disabled="disabled"' : '';
		$disabled_class = !empty($args['disabled']) ? ' disabled' : '';
		$data_string = ADNI_Main::create_data_attributes($args['data']);
		$name = !empty($args['name']) ? ' name="'.$args['name'].'"' : '';
		$value = $args['value'] !== '' ? ' value="'.$args['value'].'"' : '';

		$h = '';
		$h.= '<label class="spr_check_container'.$disabled_class.'">';
			$h.= $args['title'];
			$h.= !empty($args['tooltip']) ? self::tooltip(array('class' => '_dn_quest_tooltip', 'title' => $args['tooltip'])) : '';
			$h.= $args['hidden_input'] ? '<input type="hidden" value="0"'.$name.' />' : '';  
			$h.= '<input'.$id.' class="'.$args['class'].'"'.$data_string.$name.$value.' type="checkbox"'.$check.$disabled.'>';
	  		$h.= '<span class="checkmark"></span>';
		$h.= '</label>';

		return $h;
	}




	public static function file_upload($args = array())
	{
		$defaults = array(
			'id' => '',
			'title' => '',
			'tooltip' => '',
			'class' => '',
			'data' => array(),
			'info_txt' => __('Click here or Drag file to upload','adn'),
			'icon' => '<svg viewBox="0 0 640 512"><path fill="currentColor" d="M272 64c60.28 0 111.899 37.044 133.36 89.604C419.97 137.862 440.829 128 464 128c44.183 0 80 35.817 80 80 0 18.55-6.331 35.612-16.927 49.181C572.931 264.413 608 304.109 608 352c0 53.019-42.981 96-96 96H144c-61.856 0-112-50.144-112-112 0-56.77 42.24-103.669 97.004-110.998A145.47 145.47 0 0 1 128 208c0-79.529 64.471-144 144-144m0-32c-94.444 0-171.749 74.49-175.83 168.157C39.171 220.236 0 274.272 0 336c0 79.583 64.404 144 144 144h368c70.74 0 128-57.249 128-128 0-46.976-25.815-90.781-68.262-113.208C574.558 228.898 576 218.571 576 208c0-61.898-50.092-112-112-112-16.734 0-32.898 3.631-47.981 10.785C384.386 61.786 331.688 32 272 32zm48 340V221.255l68.201 68.2c4.686 4.686 12.284 4.686 16.97 0l5.657-5.657c4.687-4.686 4.687-12.284 0-16.971l-98.343-98.343c-4.686-4.686-12.284-4.686-16.971 0l-98.343 98.343c-4.686 4.686-4.686 12.285 0 16.971l5.657 5.657c4.686 4.686 12.284 4.686 16.97 0l68.201-68.2V372c0 6.627 5.373 12 12 12h8c6.628 0 12.001-5.373 12.001-12z"></path></svg>',
			'max_filesize' => 50,
			'extensions' => 'jpg'
		);
		$args = ADNI_Main::parse_args($args, $defaults);

		$h = '';
		$class = !empty($args['class']) ? ' '.$args['class'] : '';
		$data_string = ADNI_Main::create_data_attributes($args['data']);

		$h.= '<div class="ddrop-upload'.$class.'"'.$data_string.'>
			<div class="dz-message ddrop-message-container">
				<div class="dz-default ddrop-message">
					<span class="ddrop-message-inner">'.$args['icon'].'</span>
				</div>
				<div class="info_text">'.$args['info_txt'].'</div>
				<div class="info_text info_text_sub">'.sprintf(__('Max. size %s MB.'),'<span class="max_filesize"></span>').' '.__('Allowed files:','adn').' <span class="allowed_extentions"></span></div>
			</div>
		</div>';

		return $h;
	}



	/**
	 * SWITCH Button
	 * 
	*/
	public static function switch_btn($args = array())
	{
		$defaults = array(
			'id' => '',
			'title' => '',
			'tooltip' => '',
			'class' => '',
			'container_class' => '',
			'name' => '',
			'data' => array(),
			'value' => '',
			'checked' => '',
			'disabled' => 0,
			'chk-on' => __('ON','adn'),
			'chk-off' => __('OFF','adn'),
			'chk-high' => 0, // input_h, class to make the switch btn high
			'column' => array(),
			'hidden_input' => 0 // to return 0 when check box is unchecked. will only work when a value is provided.
		);
		$args = wp_parse_args($args, $defaults);

		$id = !empty($args['id']) ? ' id="'.$args['id'].'"' : '';
		$high = !empty($args['chk-high']) ? ' input_h' : '';
		//$checked = !empty($args['checked']) ? $args['checked'] : $args['value'];
		//$check = !empty($checked) ? ' checked="checked"' : '';
		$check = !empty($args['checked']) ? ' checked="checked"' : '';
		$disabled = !empty($args['disabled']) ? ' disabled="disabled"' : '';
		$disabled_class = !empty($args['disabled']) ? ' disabled' : '';
		$data_string = ADNI_Main::create_data_attributes($args['data']);
		$name = !empty($args['name']) ? ' name="'.$args['name'].'"' : '';
		$value = $args['value'] !== '' ? ' value="'.$args['value'].'"' : '';
		$class = !empty($args['class']) ? ' '.$args['class'] : '';
		$cont_class = !empty($args['container_class']) ? ' '.$args['container_class'] : '';
		$ttip = !empty($args['tooltip']) ? ' ttip' : '';

		$h = '';
		$h.= '<label class="switch switch-slide small'.$high.$ttip.$cont_class.'" title="'.$args['tooltip'].'">';
			$h.= $args['hidden_input'] ? '<input type="hidden" value="0"'.$name.' />' : '';  
			$h.= '<input class="switch-input'.$class.'" type="checkbox" '.$id.$name.$value.$check.$disabled.$data_string.' />
			<span class="switch-label" data-on="'.$args['chk-on'].'" data-off="'.$args['chk-off'].'"></span> 
			<span class="switch-handle"></span>
		</label>';

		if( !empty($args['column']))
		{
			$col_def = array(
				'size' => '',
				'desc' => ''
			);
			$col_args = wp_parse_args($args['column'], $col_def);

			return self::spr_column(array(
				'col' => !empty($col_args['size']) ? 'spr_'.$col_args['size'] : '',
				'title' => $args['title'],
				'desc' => $col_args['desc'],
				'content' => $h
			));
		}

		return $h;
	}
	


	/** 
	 * INPUT CONTAINER
	 *
	 */
	public static function inpt_cont($args = array())
	{
		$defaults = self::itm_defaults();
		$args = wp_parse_args( $args, $defaults );
		
		$class = !empty($args['class']) ? ' class="'.$args['class'].'"' : '';
		$id = !empty($args['id']) ? ' id="'.$args['id'].'"' : '';
		$name = !empty($args['name']) ? ' name="'.$args['name'].'"' : '';
		$placeholder = $args['placeholder'] !== '' ? ' placeholder="'.$args['placeholder'].'"' : '';
		$input_style = !empty($args['width']) ? ' style="width:'.$args['width'].';"' : '';
		
		$html = '';
		$html.= '<span class="input_container_box '.$args['size'].'">';
			$html.= !empty($args['title']) ? '<h3 class="title">'.$args['title'].'</h3>' : '';
			$html.= $args['desc_pos'] == 'top' && !empty($args['desc']) ? '<span class="description top">'.$args['desc'].'</span>' : '';
			$html.= '<div style="position: relative;">';
				$html.= '<input type="'.$args['type'].'"'.$class.$id.$input_style.$name.$placeholder.' value="'.$args['value'].'" />';
				$html.= $args['show_icon'] ? '<i class="input_icon fa fa-'.$args['icon'].'" aria-hidden="true"></i>' : '';
			$html.= '</div>';
			$html.= $args['desc_pos'] == 'bottom' && !empty($args['desc']) ? '<span class="description bottom">'.$args['desc'].'</span>' : '';
		$html.= '</span>';
		
		return $html;
	} 
	
	
	
	
	/** 
	 * TEXTAREA
	 *
	 */
	public static function textarea_cont($args = array())
	{
		$defaults = self::itm_defaults();
		$args = wp_parse_args( $args, $defaults );

		$data = !empty($args['data']) ? ' '.$args['data'] : '';
		
		$html = '';
		$html.= '<span class="input_container_box '.$args['size'].'">';
			$html.= !empty($args['title']) ? '<h3 class="title">'.$args['title'].'</h3>' : '';
			$html.= $args['desc_pos'] == 'top' && !empty($args['desc']) ? '<span class="description top">'.$args['desc'].'</span>' : '';
			$html.= '<div style="position: relative;">';
				$html.= '<textarea type="text" class="'.$args['class'].'" id="'.$args['id'].'" name="'.$args['name'].'"'.$data.' placeholder="'.$args['placeholder'].'" style="width:100%; height:'.$args['height'].';">'.$args['value'].'</textarea>';
			$html.= '</div>';
			$html.= $args['desc_pos'] == 'bottom' && !empty($args['desc']) ? '<span class="description bottom">'.$args['desc'].'</span>' : '';
		$html.= '</span>';
		
		return $html;
	} 



	/** 
	 * SELECT CONTAINER
	 *
	 */
	public static function select_cont($args = array())
	{
		$defaults = self::itm_defaults();
		$args = wp_parse_args( $args, $defaults );
		
		// Example aerray
		/*$args['select_opts'] = array(
			'left' => array(
				'value' => 'left',
				'text'  => 'Left'
			),
			'right' => array(
				'value' => 'left',
				'text'  => 'Left'
			),
		);*/
		
		$html = '';
		$html.= '<span class="input_container_box '.$args['size'].'">';
			$html.= !empty($args['title']) ? '<h3 class="title">'.$args['title'].'</h3>' : '';
			$html.= $args['desc_pos'] == 'top' && !empty($args['desc']) ? '<span class="description top">'.$args['desc'].'</span>' : '';
			$html.= '<select id="'.$args['id'].'" name="'.$args['name'].'" class="'.$args['class'].'">';
				if( !empty($args['select_opts']))
				{
					foreach($args['select_opts'] as $key => $opt)
					{
						$val = array_key_exists('value', $opt) && $key != $opt['value'] ? $opt['value'] : $key;
						$txt = array_key_exists('text', $opt) ? $opt['text'] : $val;
						
						$html.= '<option value="'.$val.'" '.selected($args['value'], $val, false).'>'.$txt.'</option>';
					}
				}
				else
				{
					$html.= '<option value="">'.__('No options available','adn').'</option>';
				}
			$html.= '</select>';
			$html.= $args['desc_pos'] == 'bottom' && !empty($args['desc']) ? '<span class="description bottom">'.$args['desc'].'</span>' : '';
		$html.= '</span>';
		
		return $html;
	}







	public static function auto_positioning_template($id, $adzone)
	{
		$auto_pos = ADNI_Main::auto_positioning();
		//echo '<pre>'.print_r($auto_pos, true).'</pre>';
		$type = $adzone['args']['type'];
		$save_name = 'save_'.$type;
		$h = '';
		//$h.= '<pre>'.print_r($adzone,true).'</pre>';
		$h.= '<div class="spr_column-inner left_column">
			<div class="spr_wrapper">
				<div class="option_box">
					<div class="info_header">
						<span class="nr"><svg viewBox="0 0 512 512" style="width:22px;"><path fill="currentColor" d="M500 224h-30.364C455.724 130.325 381.675 56.276 288 42.364V12c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v30.364C130.325 56.276 56.276 130.325 42.364 224H12c-6.627 0-12 5.373-12 12v40c0 6.627 5.373 12 12 12h30.364C56.276 381.675 130.325 455.724 224 469.636V500c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-30.364C381.675 455.724 455.724 381.675 469.636 288H500c6.627 0 12-5.373 12-12v-40c0-6.627-5.373-12-12-12zM288 404.634V364c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v40.634C165.826 392.232 119.783 346.243 107.366 288H148c6.627 0 12-5.373 12-12v-40c0-6.627-5.373-12-12-12h-40.634C119.768 165.826 165.757 119.783 224 107.366V148c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-40.634C346.174 119.768 392.217 165.757 404.634 224H364c-6.627 0-12 5.373-12 12v40c0 6.627 5.373 12 12 12h40.634C392.232 346.174 346.243 392.217 288 404.634zM288 256c0 17.673-14.327 32-32 32s-32-14.327-32-32c0-17.673 14.327-32 32-32s32 14.327 32 32z"></path></svg></span>
						<span class="text">'.__('Auto Positioning','adn').'</span>
						<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>';
						//<input type="submit" value="'.sprintf(__('Save %s','adn'), ucfirst($type)).'" class="button-primary" name="'.$save_name.'" style="width:auto;float:right;margin:8px;">
					$h.= '</div>';
					//<!-- end .info_header -->
					
					$h.= '<div class="settings_box_content">';
						$h.= '<div class="spr_column">
							<div class="spr_column-inner">
								<div class="spr_wrapper">
									<div class="input_container">
										<p>
											'.__('Placements are physically places on your website. Banners and AD Zones can be added to these places automatically.','adn').'
										</p>
									</div>
								</div>
							</div>
						</div>

						
						
						<div class="spr_column">
							<div class="spr_column-inner">
								<div class="spr_wrapper">
								<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Default AD placements','adn').'</strong></span></div>
								<div class="input_container">';

									$val = !empty($adzone['args']['positioning']) ? $adzone['args']['positioning'] : '';
									$h.= '<input class="adning_auto_position" type="hidden" value="'.$val.'" name="positioning" />
									
									<h3 class="title">'.__('','adn').'</h3>
									<div class="input_container_inner">
										
										<div class="clear">
											<!-- Manuall -->
											<div class="spot_box ttip" data-pos="" data-custom="0" title="'.__('Manually','adn').'">
												<div class="a_cont" style="width:100%;position:relative;">
													<div class="a_box" style="background:transparent;text-align: center;margin: 26px 0;font-size: 10px;">[adning]</div>
												</div>
											</div>';

											$selected = $adzone['args']['positioning'] === 'above_content' ? ' selected' : '';
											$h.= '<div class="spot_box ttip'.$selected.'" data-pos="above_content" data-custom="0" title="'.__('Above Content','adn').'">
												<div class="a_cont" style="width:100%;height:17px;">
													<div class="a_box" style="width:95%;height:15px;margin: 17px auto;"></div>
												</div>
												<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
											</div>';

											$selected = $adzone['args']['positioning'] === 'inside_content' ? ' selected' : '';
											$h.= '<div class="spot_box ttip'.$selected.'" data-pos="inside_content" data-custom="1" title="'.__('Inside Content','adn').'">
												<div class="a_cont" style="width:100%;height:17px;background:transparent;">
													<div class="a_box" style="width:95%;height:15px;margin: 18px auto;"></div>
												</div>
												<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
											</div>';

											$selected = $adzone['args']['positioning'] === 'below_content' ? ' selected' : '';
											$h.= '<div class="spot_box ttip'.$selected.'" data-pos="below_content" data-custom="0" title="'.__('Below Content','adn').'">
												<div class="a_cont" style="width:100%;height:30px;bottom:0;">
													<div class="a_box" style="width:95%;height:15px;margin:0 auto;"></div>
												</div>
												<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
											</div>';

											$selected = $adzone['args']['positioning'] === 'js_inject' ? ' selected' : '';
											$h.= '<div class="spot_box ttip'.$selected.'" data-pos="js_inject" data-custom="1" title="'.__('Inject before/after class','adn').'">
												<div class="a_cont" style="width: 60%;height: 15px;bottom:42px;left: 2px;">
													<div class="a_box" style="width:95%;height:15px;margin:0 auto;"></div>
												</div>
												<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
											</div>';

											$selected = $adzone['args']['positioning'] === 'popup' ? ' selected' : '';
											$h.= '<div class="spot_box ttip'.$selected.'" data-pos="popup" data-custom="1" title="'.__('Popup/Sticky','adn').'">
												<div class="a_cont" style="width: 100%;height: 80px;background: rgba(0, 0, 0, 0.25);">
													<div class="a_box" style="width: 50%;position: absolute;top: 20px;left: 20px;height: 25px;"></div>
												</div>
												<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
											</div>';

											$selected = $adzone['args']['positioning'] === 'cornerpeel' ? ' selected' : '';
											$h.= '<div class="spot_box ttip'.$selected.'" data-pos="cornerpeel" data-custom="0" title="'.__('Corner Peel','adn').'">
												<div class="a_cont" style="width: 100%;height: 80px;background:transparent;">
													<div class="a_box" style="width: 25px;position: absolute;top: -8px;right: -8px;height: 25px;background: #FFF;"></div>
													<div class="peel" style="width: 25px;height: 25px;background: #c7ff00;position: absolute;right: -12px;top: -12px;-ms-transform: rotate(20deg);-webkit-transform: rotate(20deg);transform: rotate(45deg);"></div>
												</div>
												<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
											</div>';

											if( $adzone['args']['type'] === 'banner' ){
												$selected = $adzone['args']['positioning'] === 'bg_takeover' ? ' selected' : '';
												$h.= '<div class="spot_box ttip'.$selected.'" data-pos="bg_takeover" data-custom="1" title="'.__('Background Takeover AD','adn').'">
													<div class="a_cont" style="width: 100%;height: 80px;background: rgba(0, 0, 0, 0);">
														<div class="a_box" style="width: 12px;position: absolute;top: 0;left: 0;height: 100%;background: #c7ff00;border-right: solid #f9f9f9;"></div>
														<div class="a_box" style="width: 12px;height: 100%;background: #c7ff00;position: absolute;right: 0;top: 0;border-left: solid #f9f9f9;"></div>
													</div>
													<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
												</div>';
											}
										$h.= '</div>
										
										<span class="description bottom">'.__('','adn').'</span>
									</div>
								</div>
							</div>
						</div>
						<!-- end .spr_column -->

						<div class="spr_column">
							<div class="spr_column-inner">
								<div class="spr_wrapper custom_placement_settings_cont">
									<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Custom Placement Settings','adn').'</strong></span></div>';
										
										// Inside Content - Settings
										$h.= '<div class="clear custom_box option_inside_content">';
											$h.= '<div class="input_container">
												<h2 class="title">'.__('Inside Content, Settings','adn').'</h2>
											</div>';

											$h.= '<div class="spr_column spr_col-6">';
												$h.= '<div class="input_container">';
													$h.= '<div class="input_container_inner">';

														$h.= '<div class="adn_settings_cont">';
															$h.= '<h4>'.__('In Post ADS','adn').'</h4>';
															$h.= '<div class="adn_settings_cont_inner clear">';
																$h.= '<p>'.__('','adn').'</p>';

																$after_x_p = '';
																$after_x_p_repeat ='';
																if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																{
																	$inside_content = array_key_exists('inside_content', $auto_pos[$id]) ? $auto_pos[$id]['inside_content'] : array();
																	$inside_content = ADNI_Main::parse_args($inside_content, array('after_x_p' => '', 'after_x_p_repeat' => 0, 'after_x_post' => '', 'after_x_post_repeat' => 0));
																	$after_x_p = $inside_content['after_x_p'];
																	$after_x_p_repeat = $inside_content['after_x_p_repeat'];
																}
																$h.= self::spr_column(array(
																	'col' => 'spr_col-6',
																	'title' => __('After x Paragraphs','adn'),
																	'desc' => __('Select after how many paragraphs the ad should show.','adn'),
																	'content' => self::inpt_cont(array(
																		'type' => 'text',
																		'width' => '100%',
																		//'name' => 'position_after_x_p',
																		'name' => 'pos[inside_content][after_x_p]',
																		'value' => $after_x_p,
																		'placeholder' => '2',
																		'icon' => 'pencil',
																		'show_icon' => 1
																	))
																));
																$h.= self::spr_column(array(
																	'col' => 'spr_col-6',
																	'title' => __('Repeat','adn'),
																	'desc' => __('Repeat AD after every x paragraphs.','adn'),
																	'content' => self::switch_btn(array(
																		'name' => 'pos[inside_content][after_x_p_repeat]',
																		'checked' => $after_x_p_repeat,
																		'value' => 1,
																		'hidden_input' => 1,
																		'chk-on' => __('Yes','adn'),
																		'chk-off' => __('No','adn'),
																		'chk-high' => 1
																	))
																));

															$h.= '</div>';
														$h.= '</div>';
														// end .adn_settings_cont

													$h.= '</div>';
												$h.= '</div>';
											$h.= '</div>';
											// end .spr_column

											$h.= '<div class="spr_column spr_col-6">';
												$h.= '<div class="input_container">';
													$h.= '<div class="input_container_inner">';

														$h.= '<div class="adn_settings_cont">';
															$h.= '<h4>'.__('In Loop ADS','adn').'</h4>';
															$h.= '<div class="adn_settings_cont_inner clear">';
																$h.= '<p>'.__('','adn').'</p>';

																$after_x_post = '';
																$after_x_post_repeat = '';
																if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																{
																	$after_x_post = $inside_content['after_x_post'];
																	$after_x_post_repeat = $inside_content['after_x_post_repeat'];
																}
																$h.= ADNI_Templates::spr_column(array(
																	'col' => 'spr_col-6',
																	'title' => __('In Loop','adn'),
																	'desc' => __('Select after how many posts the ad should show.','adn'),
																	'content' => ADNI_Templates::inpt_cont(array(
																		'type' => 'text',
																		'width' => '100%',
																		'name' => 'pos[inside_content][after_x_post]',
																		'value' => $after_x_post,
																		'placeholder' => '',
																		'icon' => 'pencil',
																		'show_icon' => 1
																	))
																));
																$h.= self::spr_column(array(
																	'col' => 'spr_col-6',
																	'title' => __('Repeat','adn'),
																	'desc' => __('Repeat AD after every x posts.','adn'),
																	'content' => self::switch_btn(array(
																		'name' => 'pos[inside_content][after_x_post_repeat]',
																		'checked' => $after_x_post_repeat,
																		'value' => 1,
																		'hidden_input' => 1,
																		'chk-on' => __('Yes','adn'),
																		'chk-off' => __('No','adn'),
																		'chk-high' => 1
																	))
																));

															$h.= '</div>';
														$h.= '</div>';
														// end .adn_settings_cont

													$h.= '</div>';
												$h.= '</div>';
											$h.= '</div>';
											// end .spr_column

										$h.= '</div>';



										// Inject - Settings
										$h.= '<div class="clear custom_box option_js_inject">';
											$h.= '<div class="input_container">
												<h2 class="title">'.__('Inject, Settings','adn').'</h2>
											</div>';
											
											$h.= '<div class="spr_column spr_col-3 left_column">';
												$h.= '<div class="input_container">
													<h3 class="title">'.__('Where','adn').'</h3>
													<div class="input_container_inner">';
														$inject_where = '';
														if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
														{
															//$inject_where = array_key_exists('inject_where', $auto_pos[$id]['custom']) ? $auto_pos[$id]['custom']['inject_where'] : '';
															$inject_where = array_key_exists('js_inject', $auto_pos[$id]) ? $auto_pos[$id]['js_inject']['where'] : '';
														}
														
														$h.= '<select name="pos[js_inject][where]">';
															$h.= '<option value="before"'.selected( $inject_where, 'before', false ).'>'.__('Before','adn').'</option>';
															$h.= '<option value="after"'.selected( $inject_where, 'after', false ).'>'.__('After','adn').'</option>';
														$h.= '</select>';
														
													$h.= '</div>
													<span class="description bottom">'.__('Insert before or after the element.','adn').'</span>
												</div>';
											$h.= '</div>';
											// end .spr_column 

											$h.= '<div class="spr_column spr_col-8">';
												$h.= '<div class="input_container">
													<h3 class="title">'.__('Element','adn').'</h3>
													<div class="input_container_inner">';
														$inject_element = '';
														if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
														{
															//$inject_element = array_key_exists('inject_element', $auto_pos[$id]['custom']) ? $auto_pos[$id]['custom']['inject_element'] : '';
															$inject_element = array_key_exists('js_inject', $auto_pos[$id]) ? $auto_pos[$id]['js_inject']['element'] : '';
														}
														
														$h.= '<input 
															type="text" 
															class="" 
															name="pos[js_inject][element]" 
															value="'.$inject_element.'" 
															placeholder=".classname" />';
														$h.= '<i class="input_icon fa fa-pencil" aria-hidden="true"></i>';
														
													$h.= '</div>
													<span class="description bottom">'.__('Insert the banner next to this element (classname/id).','adn').'</span>
												</div>';
											$h.= '</div>';
											// end .spr_column 
										$h.= '</div>';


										// Popup - Settings
										$h.= '<div class="clear custom_box option_popup">';
											$h.= '<div class="input_container">
												<h2 class="title">'.__('Popup, Settings','adn').'</h2>
											</div>';
											
											$h.= '<div class="spr_column spr_col">';
												$h.= '<div class="input_container popup_display_options">';

													$popup_display = 'mc_popup';
													if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
													{
														if( array_key_exists('popup', $auto_pos[$id]) )
														{
															$popup_display = array_key_exists('display', $auto_pos[$id]['popup']) ? $auto_pos[$id]['popup']['display'] : $popup_display;
														}
													}
													$h.= '<input class="popup_display_type" type="hidden" value="'.$popup_display.'" name="pos[popup][display]">';
													$h.= '<h3 class="title">'.__('Positioning','adn').'</h3>';

													// TOP
													$selected = $popup_display === 'tl_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="tl_popup" data-custom="1" title="'.__('Top Left','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;top:0px;left:0px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													$selected = $popup_display === 'tc_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="tc_popup" data-custom="1" title="'.__('Top Center','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;top:0px;left:11px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													$selected = $popup_display === 'tr_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="tr_popup" data-custom="1" title="'.__('Top Right','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;top:0px;right:0px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';
													
													// MIDDLE
													$selected = $popup_display === 'ml_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="ml_popup" data-custom="1" title="'.__('Middle Left','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;top:10px;left:0px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													$selected = $popup_display === 'mc_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="mc_popup" data-custom="1" title="'.__('Middle Center','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;top:10px;left:11px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													$selected = $popup_display === 'mr_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="mr_popup" data-custom="1" title="'.__('Middle Right','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;top:10px;right:0px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													// BOTTOM
													$selected = $popup_display === 'bl_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="bl_popup" data-custom="1" title="'.__('Bottom Left','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;bottom:3px;left:0px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													$selected = $popup_display === 'bc_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="bc_popup" data-custom="1" title="'.__('Bottom Center','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;bottom:3px;left:11px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

													$selected = $popup_display === 'br_popup' ? ' selected' : '';
													$h.= '<div class="pop_box ttip'.$selected.'" data-pos="br_popup" data-custom="1" title="'.__('Bottom Right','adn').'">
														<div class="a_cont" style="width: 100%;height: 40px;background: rgba(0, 0, 0, 0.15);">
															<div class="a_box" style="width:18px;position: absolute;bottom:3px;right:0px;height:13px;"></div>
														</div>
														<svg viewBox="0 0 402.532 334.177"> <path fill="#D6D6D6" d="M393.671,17.72c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,11.391,393.671,14.225,393.671,17.72L393.671,17.72z"></path> <path fill="#D6D6D6" d="M393.671,44.732c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,38.403,393.671,41.237,393.671,44.732L393.671,44.732z"></path> <path fill="#D6D6D6" d="M393.671,71.885c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,65.556,393.671,68.389,393.671,71.885L393.671,71.885z"></path> <path fill="#D6D6D6" d="M393.671,99.999c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,93.67,393.671,96.503,393.671,99.999L393.671,99.999z"></path> <path fill="#D6D6D6" d="M393.671,127.011c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,120.682,393.671,123.516,393.671,127.011L393.671,127.011z"></path> <path fill="#D6D6D6" d="M393.671,154.163c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.495,2.833-6.328,6.329-6.328h372.152C390.837,147.835,393.671,150.668,393.671,154.163L393.671,154.163z"></path> <path fill="#D6D6D6" d="M393.671,182.288c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,175.959,393.671,178.792,393.671,182.288L393.671,182.288z"></path> <path fill="#D6D6D6" d="M393.671,209.3c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,202.971,393.671,205.805,393.671,209.3L393.671,209.3z"></path> <path fill="#D6D6D6" d="M393.671,236.453c0,3.496-2.834,6.329-6.329,6.329H15.19c-3.496,0-6.329-2.833-6.329-6.329l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,230.124,393.671,232.957,393.671,236.453L393.671,236.453z"></path> <path fill="#D6D6D6" d="M393.671,264.567c0,3.495-2.834,6.328-6.329,6.328H15.19c-3.496,0-6.329-2.833-6.329-6.328l0,0 c0-3.496,2.833-6.33,6.329-6.33h372.152C390.837,258.237,393.671,261.071,393.671,264.567L393.671,264.567z"></path> <path fill="#D6D6D6" d="M393.671,291.579c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.329,6.329-6.329h372.152C390.837,285.25,393.671,288.083,393.671,291.579L393.671,291.579z"></path> <path fill="#D6D6D6" d="M393.671,318.731c0,3.496-2.834,6.33-6.329,6.33H15.19c-3.496,0-6.329-2.834-6.329-6.33l0,0 c0-3.496,2.833-6.328,6.329-6.328h372.152C390.837,312.403,393.671,315.235,393.671,318.731L393.671,318.731z"></path> <path display="none" opacity="0.4" d="M412.595,329.455c0,6.627-5.373,12-12,12h-403c-6.627,0-12-5.373-12-12v-329 c0-6.627,5.373-12,12-12h403c6.627,0,12,5.373,12,12V329.455z"></path></svg>
													</div>';

												$h.= '</div>';
											$h.= '</div>';

											$h.= '<div class="clearFix"></div>';

											$popup_width = '';
											if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
											{
												$popup_width = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['width'] : '';
											}
											$h.= ADNI_Templates::spr_column(array(
												'col' => 'spr_col-3',
												'title' => esc_attr__('Width','adn'),
												'desc' => esc_attr__('Width of the popup.','adn'),
												'content' => ADNI_Templates::inpt_cont(array(
													'type' => 'text',
													'width' => '100%',
													'name' => 'pos[popup][width]',
													'value' => $popup_width,
													'placeholder' => '',
													'icon' => 'arrows-h',
													'show_icon' => 1
												))
											));

											$popup_height = '';
											if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
											{
												$popup_height = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['height'] : '';
											}
											$h.= ADNI_Templates::spr_column(array(
												'col' => 'spr_col-3',
												'title' => esc_attr__('Height','adn'),
												'desc' => esc_attr__('Height of the popup.','adn'),
												'content' => ADNI_Templates::inpt_cont(array(
													'type' => 'text',
													'width' => '100%',
													'name' => 'pos[popup][height]',
													'value' => $popup_height,
													'placeholder' => '',
													'icon' => 'arrows-v',
													'show_icon' => 1
												))
											));

											$popup_bg_color = '';
											if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
											{
												$popup_bg_color = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['bg_color'] : '';
											}
											$h.= ADNI_Templates::spr_column(array(
												'col' => 'spr_col-2',
												'title' => esc_attr__('Bg Color','adn'),
												'desc' => esc_attr__('Popup background color.','adn'),
												'content' => ADNI_Templates::inpt_cont(array(
													'type' => 'text',
													'width' => '100%',
													'id' => 'popup_bg_color',
													'name' => 'pos[popup][bg_color]',
													'value' => $popup_bg_color,
													'placeholder' => '',
													'show_icon' => 0
												)).
												'<script>jQuery(document).ready(function($){ $("#popup_bg_color").coloringPick(); });</script>'
											));

											$popup_shadow_color = '';
											if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
											{
												$popup_shadow_color = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['shadow_color'] : '';
											}
											$h.= ADNI_Templates::spr_column(array(
												'col' => 'spr_col-2',
												'title' => esc_attr__('Shadow Color','adn'),
												'desc' => esc_attr__('Popup shadow color.','adn'),
												'content' => ADNI_Templates::inpt_cont(array(
													'type' => 'text',
													'width' => '100%',
													'id' => 'popup_shadow_color',
													'name' => 'pos[popup][shadow_color]',
													'value' => $popup_shadow_color,
													'placeholder' => '',
													'show_icon' => 0
												)).
												'<script>jQuery(document).ready(function($){ $("#popup_shadow_color").coloringPick({"picker":"solid","picker_changeable":false}); });</script>'
											));

											$popup_overlay_color = '';
											if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
											{
												$popup_overlay_color = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['overlay_color'] : '';
											}
											$h.= ADNI_Templates::spr_column(array(
												'col' => 'spr_col-2',
												'title' => esc_attr__('Overlay Color','adn'),
												'desc' => esc_attr__('Background overlay color for the popup.','adn'),
												'content' => ADNI_Templates::inpt_cont(array(
													'type' => 'text',
													'width' => '100%',
													'id' => 'popup_overlay_color',
													'name' => 'pos[popup][overlay_color]',
													'value' => $popup_overlay_color,
													'placeholder' => '',
													'show_icon' => 0
												)).
												'<script>jQuery(document).ready(function($){ $("#popup_overlay_color").coloringPick(); });</script>'
											));



											$h.= '<div class="clearFix"></div>';
											$h.= '<div class="spr_column">';
												$h.= '<div class="spr_column-inner">';
													$h.= '<div class="spr_wrapper">';
														$h.= '<div class="input_container">';

															$h.= '<div class="adn_settings_cont closed">';
																$h.= '<h4>'.__('Trigger Settings','adn').' <span class="fa togg"></span></h4>';
																$h.= '<div class="set_box_content hidden" style="margin-top: 15px;">';
																	
																	$is_exit_popup = 0;
																	$is_scroll_popup = 0;
																	$is_inactive_popup = 0;
																	$is_delay_popup = 0;
																	$delay_args = array('target' => 5);
																	$scroll_args = array('target' => 'percent', 'value' => '20');
																	$inactive_args = array('target' => 5);
																	
																	if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																	{
																		if( array_key_exists('popup', $auto_pos[$id]) )
																		{
																			if( array_key_exists('trigger', $auto_pos[$id]['popup']) )
																			{
																				$is_exit_popup = $auto_pos[$id]['popup']['trigger']['exit'];
																				$is_scroll_popup = $auto_pos[$id]['popup']['trigger']['scroll'];
																				$scroll_args = $auto_pos[$id]['popup']['trigger']['args']['scroll'];
																				$is_delay_popup = $auto_pos[$id]['popup']['trigger']['delay'];
																				$delay_args = $auto_pos[$id]['popup']['trigger']['args']['delay'];
																				//$is_inactive_popup = $auto_pos[$id]['popup']['trigger']['inactive'];
																				//$inactive_args = $auto_pos[$id]['popup']['trigger']['args']['inactive'];
																			}
																		}
																		
																	}
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => esc_attr__('Exit Popup','adn'),
																		'desc' => '',
																		'content' => ADNI_Templates::switch_btn(array(
																			'name' => 'pos[popup][trigger][exit]',
																			'tooltip' => esc_attr__('Trigger popup when user exits page.','adn'),
																			'checked' => $is_exit_popup,
																			'value' => 1,
																			'hidden_input' => 1,
																			'chk-on' => esc_attr__('Yes','adn'),
																			'chk-off' => esc_attr__('No','adn'),
																			'chk-high' => 1
																		))
																	));
																	$h.= '<div class="clearFix"></div>';

																	// Scroll Popup
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => esc_attr__('Scroll Popup','adn'),
																		'desc' => '',
																		'content' => ADNI_Templates::switch_btn(array(
																			'name' => 'pos[popup][trigger][scroll]',
																			'tooltip' => esc_attr__('Trigger popup when the user reaches a certain point.','adn'),
																			'checked' => $is_scroll_popup,
																			'value' => 1,
																			'hidden_input' => 1,
																			'chk-on' => esc_attr__('Yes','adn'),
																			'chk-off' => esc_attr__('No','adn'),
																			'chk-high' => 1
																		))
																	));
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-4',
																		'title' => esc_attr__('Target','adn'),
																		'desc' => esc_attr__('','adn'),
																		'content' => '<select name="pos[popup][trigger][args][scroll][target]">
																			<option value="percent"'.selected( $scroll_args['target'], 'percent', false ).'>'.__('Percent (x% of the page)','adn').'</option>
																			<option value="scroll"'.selected( $scroll_args['target'], 'scroll', false ).'>'.__('Scroll (specific class/id)','adn').'</option>
																		</select>'
																	));
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-4',
																		'title' => esc_attr__('Value','adn'),
																		'desc' => esc_attr__('','adn'),
																		'content' => ADNI_Templates::inpt_cont(array(
																			'type' => 'text',
																			'width' => '100%',
																			'name' => 'pos[popup][trigger][args][scroll][value]',
																			'value' => $scroll_args['value'],
																			'placeholder' => '',
																			'show_icon' => 1,
																			'icon' => 'pencil'
																		))
																	));
																	$h.= '<div class="clearFix"></div>';

																	// Delay Popup
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => esc_attr__('Delay Popup','adn'),
																		'desc' => '',
																		'content' => ADNI_Templates::switch_btn(array(
																			'name' => 'pos[popup][trigger][delay]',
																			'tooltip' => esc_attr__('Trigger popup after x amount of time.','adn'),
																			'checked' => $is_delay_popup,
																			'value' => 1,
																			'hidden_input' => 1,
																			'chk-on' => esc_attr__('Yes','adn'),
																			'chk-off' => esc_attr__('No','adn'),
																			'chk-high' => 1
																		))
																	));
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-4',
																		'title' => esc_attr__('Seconds','adn'),
																		'desc' => esc_attr__('','adn'),
																		'content' => ADNI_Templates::inpt_cont(array(
																			'type' => 'text',
																			'width' => '100%',
																			'name' => 'pos[popup][trigger][args][delay][target]',
																			'value' => $delay_args['target'],
																			'placeholder' => '',
																			'show_icon' => 1,
																			'icon' => 'clock-o'
																		))
																	));
																	$h.= '<div class="clearFix"></div>';

																	// Inactive Popup
																	/*$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => esc_attr__('Inactive Popup','adn'),
																		'desc' => '',
																		'content' => ADNI_Templates::switch_btn(array(
																			'name' => 'pos[popup][trigger][inactive]',
																			'tooltip' => esc_attr__('Trigger popup when the user is inactive for x amount of time.','adn'),
																			'checked' => $is_inactive_popup,
																			'value' => 1,
																			'hidden_input' => 1,
																			'chk-on' => esc_attr__('Yes','adn'),
																			'chk-off' => esc_attr__('No','adn'),
																			'chk-high' => 1
																		))
																	));
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-4',
																		'title' => esc_attr__('Seconds','adn'),
																		'desc' => esc_attr__('','adn'),
																		'content' => ADNI_Templates::inpt_cont(array(
																			'type' => 'text',
																			'width' => '100%',
																			'name' => 'pos[popup][trigger][args][inactive][target]',
																			'value' => $inactive_args['target'],
																			'placeholder' => '',
																			'show_icon' => 1,
																			'icon' => 'clock-o'
																		))
																	));*/
																	
																	$h.= '<div class="clearFix"></div>';
																$h.= '</div>';
															$h.= '</div>';

														$h.= '</div>';
													$h.= '</div>';	
												$h.= '</div>';	
											$h.= '</div>';		

											
											$h.= '<div class="clearFix"></div>';
											$h.= '<div class="spr_column">';
												$h.= '<div class="spr_column-inner">';
													$h.= '<div class="spr_wrapper">';
														$h.= '<div class="input_container">';

															$h.= '<div class="adn_settings_cont closed">';
																$h.= '<h4>'.__('Animation Settings','adn').' <span class="fa togg"></span></h4>';
																$h.= '<div class="set_box_content hidden" style="margin-top: 15px;">';
																	$popup_animateIn = 'tada';
																	$popup_animateOut = 'tada';
																	if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																	{
																		if( array_key_exists('popup', $auto_pos[$id]) )
																		{
																			$popup_animateIn = array_key_exists('animate_in', $auto_pos[$id]['popup']) ? $auto_pos[$id]['popup']['animate_in'] : $popup_animateIn;
																			$popup_animateOut = array_key_exists('animate_out', $auto_pos[$id]['popup']) ? $auto_pos[$id]['popup']['animate_out'] : $popup_animateOut;
																		}
																	}
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-6',
																		'title' => esc_attr__('Animate In','adn'),
																		'desc' => esc_attr__('Animation when popup gets shown.','adn'),
																		'content' => self::popup_animations(array('id' => 'animate_in', 'name' => 'pos[popup][animate_in]', 'value' => $popup_animateIn))
																	));
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-6',
																		'title' => esc_attr__('Animate Out','adn'),
																		'desc' => esc_attr__('Animation when popup gets closed.','adn'),
																		'content' => self::popup_animations(array('id' => 'animate_out', 'name' => 'pos[popup][animate_out]', 'value' => $popup_animateOut))
																	));
																	$h.= '<div class="clearFix"></div>';
																$h.= '</div>';
															$h.= '</div>';

														$h.= '</div>';
													$h.= '</div>';	
												$h.= '</div>';	
											$h.= '</div>';		


											$h.= '<div class="clearFix"></div>';
											$h.= '<div class="spr_column">';
												$h.= '<div class="spr_column-inner">';
													$h.= '<div class="spr_wrapper">';
														$h.= '<div class="input_container">';

															$h.= '<div class="adn_settings_cont closed">';
																$h.= '<h4>'.__('Advanced Settings','adn').' <span class="fa togg"></span></h4>';
																$h.= '<div class="set_box_content hidden" style="margin-top: 15px;">';
																	
																	$popup_disable_window_scroll = 0;
																	if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																	{
																		if( array_key_exists('popup', $auto_pos[$id]) )
																		{
																			$popup_disable_window_scroll = array_key_exists('disable_ws', $auto_pos[$id]) ? $auto_pos[$id]['popup']['disable_ws'] : $popup_disable_window_scroll;
																		}
																	}
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => esc_attr__('Disable Window Scroll','adn'),
																		'desc' => '',
																		'content' => ADNI_Templates::switch_btn(array(
																			'name' => 'pos[popup][disable_ws]',
																			'tooltip' => esc_attr__('Turn Off window scrolling when popup is open.','adn'),
																			'checked' => $popup_disable_window_scroll,
																			'value' => 1,
																			'hidden_input' => 1,
																			'chk-on' => esc_attr__('Yes','adn'),
																			'chk-off' => esc_attr__('No','adn'),
																			'chk-high' => 0
																		))
																	));

																	$h.= '<div class="clearFix"></div>';

																	$popup_custom_json = '';
																	if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																	{
																		$popup_custom_json = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['custom_json'] : '';
																	}
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col',
																		'title' => 'Custom Attributes',
																		'desc' => sprintf(__('Add custom %s attributes.','adn'), '<a href="http://modaljs.com/installation/#attributes" target="_blank">ModalJS</a>'),
																		'content' => ADNI_Templates::inpt_cont(array(
																			'type' => 'text',
																			'width' => '100%',
																			'name' => 'pos[popup][custom_json]',
																			'value' => str_replace('"',"'", stripslashes($popup_custom_json)),
																			'placeholder' => "animatedIn:\'tada\'",
																			'show_icon' => 1,
																			'icon' => 'pencil'
																		))
																	));

																	$h.= '<div class="clearFix"></div>';
																$h.= '</div>';
															$h.= '</div>';

														$h.= '</div>';
													$h.= '</div>';	
												$h.= '</div>';	
											$h.= '</div>';		
											
											$h.= '<div class="clearFix"></div>';
											$h.= '<div class="spr_column">';
												$h.= '<div class="spr_column-inner">';
													$h.= '<div class="spr_wrapper">';
														$h.= '<div class="input_container">';

															$h.= '<div class="adn_settings_cont closed">';
																$h.= '<h4>'.__('Cookie Settings','adn').' <span class="fa togg"></span></h4>';
																$h.= '<div class="set_box_content hidden" style="margin-top: 15px;">';
																	$popup_cookie_value = '';
																	if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																	{
																		$popup_cookie_value = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['cookie_value'] : '';
																	}
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => '',
																		'desc' => esc_attr__('Numeric value in how long the cookie should expire.','adn'),
																		'content' => ADNI_Templates::inpt_cont(array(
																			'type' => 'text',
																			'width' => '100%',
																			'name' => 'pos[popup][cookie_value]',
																			'value' => $popup_cookie_value,
																			'placeholder' => '0',
																			'show_icon' => 0
																		))
																	));
					
																	$popup_cookie_type = '';
																	if( !empty($auto_pos) && array_key_exists($id, $auto_pos) )
																	{
																		$popup_cookie_type = array_key_exists('popup', $auto_pos[$id]) ? $auto_pos[$id]['popup']['cookie_type'] : '';
																	}
																	$h.= ADNI_Templates::spr_column(array(
																		'col' => 'spr_col-3',
																		'title' => '',
																		'desc' => esc_attr__('Set a cookie to only show the popup every x amount of time.','adn'),
																		'content' => '<select name="pos[popup][cookie_type]">
																			<option value="minutes"'.selected( $popup_cookie_type, 'minutes', false ).'>'.__('Minutes','adn').'</option>
																			<option value="days"'.selected( $popup_cookie_type, 'days', false ).'>'.__('Days','adn').'</option>
																		</select>'
																	));
																	$h.= '<div class="clearFix"></div>';
																$h.= '</div>';
															$h.= '</div>';
																	
														$h.= '</div>';
													$h.= '</div>';	
												$h.= '</div>';	
											$h.= '</div>';		
											
											
											/*$h.= '<div class="spr_column spr_col-6">';
												$h.= '<div class="input_container">
													<h3 class="title">'.__('Custom Attributes','adn').'</h3>
													<div class="input_container_inner">';
														
														
													$h.= '</div>
													<span class="description bottom">'.sprintf(__('Add custom %s attributes.','adn'), '<a href="http://modaljs.com/installation/#attributes" target="_blank">ModalJS</a>').'</span>
												</div>';
											$h.= '</div>';
											// end .spr_column */

											// Popup Cookie settings
											/*$h.= '<div class="clearFix">';
												$h.= '<div class="input_container">';
													$h.= '<h3 class="title">'.__('Popup Cookie Settings','adn').'</h3>';
												$h.= '</div>';
											$h.= '</div>';*/

										$h.= '</div>';
										// end popup settings




										// Background Takeover AD - Settings
										if( $adzone['args']['type'] === 'banner' ){
											$h.= '<div class="clear custom_box option_bg_takeover">';
												$h.= '<div class="input_container">
													<h2 class="title">'.__('Background Takeover, Settings','adn').'</h2>
												</div>';
												
												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-4',
													'title' => __('Background Takover Image','adn'),
													'desc' => __('Upload or Insert the background takeover image URL.','adn'),
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'id' => 'bg_takeover_src',
														'class' => 'bg_takeover_src',
														'width' => '100%',
														'name' => 'bg_takeover_src',
														'value' => $adzone['args']['bg_takeover_src'],
														'placeholder' => '',
														'icon' => 'link',
														'show_icon' => 1
													)).
													ADNI_Templates::file_upload(array(
														'class' => 'BGADUploader',
														'data' => array('id' => $id)
													))
												));



												$h.= '<div class="spr_column spr_col-4">';
													$h.= '<div class="input_container">
														<h3 class="title">'.__('Background Container','adn').'</h3>
														<div class="input_container_inner">';
															
															$h.= '<input 
																type="text" 
																class="" 
																id="bg_takeover_bg_container"
																name="bg_takeover_bg_container" 
																value="'.$adzone['args']['bg_takeover_bg_container'].'" 
																placeholder="'.__('body','adn').'" />
															<i class="input_icon fa fa-pencil" aria-hidden="true"></i>
														</div>
														<span class="description bottom">'.__('The object/class/ID of the background container. (default body).','adn').'</span>
													</div>';
												$h.= '</div>';
												// end .spr_column  
												$h.= '<div class="spr_column spr_col-4">';
													$h.= '<div class="input_container">
														<h3 class="title">'.__('Content Container','adn').'</h3>
														<div class="input_container_inner">';
															
															$h.= '<input 
																type="text" 
																class="" 
																id="bg_takeover_content_container"
																name="bg_takeover_content_container" 
																value="'.$adzone['args']['bg_takeover_content_container'].'" 
																placeholder="'.__('.content','adn').'" />
															<i class="input_icon fa fa-pencil" aria-hidden="true"></i>
														</div>
														<span class="description bottom">'.__('The class/ID of the main content container for the background AD to wrap around.','adn').'</span>
													</div>';
												$h.= '</div>';
												// end .spr_column  
												$h.= '<div class="spr_column spr_col-4">';
													$h.= '<div class="input_container">
														<h3 class="title">'.__('Top Skin (optional)','adn').'</h3>
														<div class="input_container_inner">';
															$h.= '<input 
																type="text" 
																class="bg_takeover_prev_obj"
																id="bg_takeover_top_skin"
																name="bg_takeover_top_skin" 
																value="'.$adzone['args']['bg_takeover_top_skin'].'" 
																placeholder="'.__('100px','adn').'" />
															<i class="input_icon fa fa-pencil" aria-hidden="true"></i>
														</div>
														<span class="description bottom">'.__('Show top of the background takeover ad. This will add a top margin to the container.','adn').'</span>
													</div>';
												$h.= '</div>';
												// end .spr_column 
												$h.= '<div class="spr_column spr_col-4">';
													$h.= '<div class="input_container">
														<h3 class="title">'.__('Background Position','adn').'</h3>
														<div class="input_container_inner">';
															$h.= '<select id="ADNI_label_pos" name="bg_takeover_position">
																<option value="absolute"'.selected( $adzone['args']['bg_takeover_position'], 'absolute', false ).'>'.__('Scroll with page','adn').'</option>
																<option value="fixed"'.selected( $adzone['args']['bg_takeover_position'], 'fixed', false ).'>'.__('Fixed','adn').'</option>
															</select>
														</div>
														<span class="description bottom">'.__('Background takeover position (fixed or scrolling with the page).','adn').'</span>
													</div>';
												$h.= '</div>';
												// end .spr_column 
												$h.= '<div class="spr_column spr_col-4">';
													$h.= '<div class="input_container">
														<h3 class="title">'.__('Background Color','adn').'</h3>
														<div class="input_container_inner">';
															
															$h.= '<input class="bg_takeover_prev_obj" id="bg_takeover_bg_color" name="bg_takeover_bg_color" type="text" value="'.$adzone['args']['bg_takeover_bg_color'].'">';
															$h.= "<script>jQuery(document).ready(function($){ $('#bg_takeover_bg_color').coloringPick({ on_select:function(){ $('.bg_takeover_prev_obj').trigger('change') } }); });</script>";
												
														$h.= '</div>
														<span class="description bottom">'.__('(optional) Background color.','adn').'</span>
													</div>';
												$h.= '</div>';
												// end .spr_column 
												$h.= '<div class="spr_column spr_col-4">';
													$h.= '<div class="input_container">
														<h3 class="title">'.__('Content Background Color','adn').'</h3>
														<div class="input_container_inner">';
															
															$h.= '<input class="bg_takeover_prev_obj" id="bg_takeover_content_bg_color" name="bg_takeover_content_bg_color" type="text" value="'.$adzone['args']['bg_takeover_content_bg_color'].'">';
															$h.= "<script>jQuery(document).ready(function($){ $('#bg_takeover_content_bg_color').coloringPick({ on_select:function(){ $('.bg_takeover_prev_obj').trigger('change') } }); });</script>";
												
														$h.= '</div>
														<span class="description bottom">'.__('(optional) Just in case the content background has no background color.','adn').'</span>
													</div>';
												$h.= '</div>';
												// end .spr_column 

												$h.= '<div class="clear">';
													$h.= '<div class="spr_column spr_col-4">';
														$h.= '<div class="input_container">
															<h3 class="title">'.__('Top Skin URL','adn').'</h3>
															<div class="input_container_inner">';
																$h.= '<input 
																	type="text" 
																	class="bg_takeover_prev_obj" 
																	id="bg_takeover_top_skin_url"
																	name="bg_takeover_top_skin_url" 
																	value="'.$adzone['args']['bg_takeover_top_skin_url'].'" 
																	placeholder="'.__('','adn').'" />
																<i class="input_icon fa fa-link" aria-hidden="true"></i>
															</div>
															<span class="description bottom">'.__('Link URL for the Top Skin.','adn').'</span>
														</div>';
													$h.= '</div>';
													// end .spr_column 
													$h.= '<div class="spr_column spr_col-4">';
														$h.= '<div class="input_container">
															<h3 class="title">'.__('Left Skin URL','adn').'</h3>
															<div class="input_container_inner">';
																$h.= '<input 
																	type="text" 
																	class="bg_takeover_prev_obj" 
																	id="bg_takeover_left_skin_url"
																	name="bg_takeover_left_skin_url" 
																	value="'.$adzone['args']['bg_takeover_left_skin_url'].'" 
																	placeholder="'.__('','adn').'" />
																<i class="input_icon fa fa-link" aria-hidden="true"></i>
															</div>
															<span class="description bottom">'.__('Link URL for the Left Skin.','adn').'</span>
														</div>';
													$h.= '</div>';
													// end .spr_column 
													$h.= '<div class="spr_column spr_col-4">';
														$h.= '<div class="input_container">
															<h3 class="title">'.__('Right Skin URL','adn').'</h3>
															<div class="input_container_inner">';
																$h.= '<input 
																	type="text" 
																	class="bg_takeover_prev_obj" 
																	id="bg_takeover_right_skin_url"
																	name="bg_takeover_right_skin_url" 
																	value="'.$adzone['args']['bg_takeover_right_skin_url'].'" 
																	placeholder="'.__('','adn').'" />
																<i class="input_icon fa fa-link" aria-hidden="true"></i>
															</div>
															<span class="description bottom">'.__('Link URL for the Right Skin.','adn').'</span>
														</div>';
													$h.= '</div>';
													// end .spr_column 
												$h.= '</div>';
												
												$h.= '<div class="clear">';
													$h.= '<div class="spr_column left_column">';
														$show = !empty($adzone['args']['bg_takeover_src']) || !empty($adzone['args']['bg_takeover_bg_color']) ? '' : ' style="display:none;"';
														$h.= '<div class="input_container bgad_preview_container"'.$show.'>
															<h3 class="title">'.__('Preview','adn').'</h3>
															<p>
																'.__('Note: This is just a quick preview to give you an idea. The actual result on your website may look different depending on the sizing.','adn').'
															</p>
															<div class="input_container_inner">';
																$h.= '<div class="bgad_preview" style="position:relative;">
																	<div class="bgad_prev_content" style="position: relative;width: 50%;margin: 0 auto;background: #FFF;padding: 10px;font-size: 10px;">
																		<h5>Demo Content</h5>We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin. We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.We’ve packed everything you need for managing your advertisements in one easy to use, professional WordPress plugin.
																	</div>
																</div>';
															$h.= '</div>
														</div>
													</div>';
													// end .spr_column 
												$h.= '</div>';

											$h.= '</div>';
										}
										// end background takeover settings

										$h.= '<span class="description bottom">'.__('','adn').'</span>
									</div>
								</div>
							</div>
						</div>
						<!-- end .spr_column -->';

						$h.= ADNI_Templates::spr_column(array(
							'col' => 'spr_col',
							'title' => '',
							'desc' => '',
							'content' => '<input type="submit" value="'.sprintf(__('Save %s','adn'), ucfirst($type)).'" class="button-primary" name="'.$save_name.'">'
						));

					$h.= '</div>';
					// end .settings_box_content

				$h.= '</div>
				<!-- end .option_box -->
			</div>
		</div>';
		return $h;
	}




	public static function popup_animations($args = array())
	{
		$defaults = array(
			'id' => 'animate_in',
			'class' => 'animation_edit',
			'name' => '',
			'value' => 'tada'
		);
		$args = ADNI_Main::parse_args($args, $defaults);
		$h = '';
		$h.= '<select id="'.$args['id'].'" name="'.$args['name'].'" class="'.$args['class'].'">
			<optgroup label="Attention Seekers">
				<option value="bounce" '.selected( $args['value'], 'bounce', false ).'>bounce</option>
				<option value="flash" '.selected( $args['value'], 'flash', false ).'>flash</option>
				<option value="pulse" '.selected( $args['value'], 'pulse', false ).'>pulse</option>
				<option value="rubberBand" '.selected( $args['value'], 'rubberBand', false ).'>rubberBand</option>
				<option value="shake" '.selected( $args['value'], 'shake', false ).'>shake</option>
				<option value="swing" '.selected( $args['value'], 'swing', false ).'>swing</option>
				<option value="tada" '.selected( $args['value'], 'tada', false ).'>tada</option>
				<option value="wobble" '.selected( $args['value'], 'wobble', false ).'>wobble</option>
				<option value="jello" '.selected( $args['value'], 'jello', false ).'>jello</option>
			</optgroup>

			<optgroup label="Bouncing Entrances">
				<option value="bounceIn" '.selected( $args['value'], 'bounceIn', false ).'>bounceIn</option>
				<option value="bounceInDown" '.selected( $args['value'], 'bounceInDown', false ).'>bounceInDown</option>
				<option value="bounceInLeft" '.selected( $args['value'], 'bounceInLeft', false ).'>bounceInLeft</option>
				<option value="bounceInRight" '.selected( $args['value'], 'bounceInRight', false ).'>bounceInRight</option>
				<option value="bounceInUp" '.selected( $args['value'], 'bounceInUp', false ).'>bounceInUp</option>
			</optgroup>

			<optgroup label="Bouncing Exits">
				<option value="bounceOut" '.selected( $args['value'], 'bounceOut', false ).'>bounceOut</option>
				<option value="bounceOutDown" '.selected( $args['value'], 'bounceOutDown', false ).'>bounceOutDown</option>
				<option value="bounceOutLeft" '.selected( $args['value'], 'bounceOutLeft', false ).'>bounceOutLeft</option>
				<option value="bounceOutRight" '.selected( $args['value'], 'bounceOutRight', false ).'>bounceOutRight</option>
				<option value="bounceOutUp" '.selected( $args['value'], 'bounceOutUp', false ).'>bounceOutUp</option>
			</optgroup>

			<optgroup label="Fading Entrances">
				<option value="fadeIn" '.selected( $args['value'], 'fadeIn', false ).'>fadeIn</option>
				<option value="fadeInDown" '.selected( $args['value'], 'fadeInDown', false ).'>fadeInDown</option>
				<option value="fadeInDownBig" '.selected( $args['value'], 'fadeInDownBig', false ).'>fadeInDownBig</option>
				<option value="fadeInLeft" '.selected( $args['value'], 'fadeInLeft', false ).'>fadeInLeft</option>
				<option value="fadeInLeftBig" '.selected( $args['value'], 'fadeInLeftBig', false ).'>fadeInLeftBig</option>
				<option value="fadeInRight" '.selected( $args['value'], 'fadeRight', false ).'>fadeInRight</option>
				<option value="fadeInRightBig" '.selected( $args['value'], 'fadeInRightBig', false ).'>fadeInRightBig</option>
				<option value="fadeInUp" '.selected( $args['value'], 'fadeInUp', false ).'>fadeInUp</option>
				<option value="fadeInUpBig" '.selected( $args['value'], 'fadeInUpBig', false ).'>fadeInUpBig</option>
			</optgroup>

			<optgroup label="Fading Exits">
				<option value="fadeOut" '.selected( $args['value'], 'fadeOut', false ).'>fadeOut</option>
				<option value="fadeOutDown" '.selected( $args['value'], 'fadeOutDown', false ).'>fadeOutDown</option>
				<option value="fadeOutDownBig" '.selected( $args['value'], 'fadeOutDownBig', false ).'>fadeOutDownBig</option>
				<option value="fadeOutLeft" '.selected( $args['value'], 'fadeOutLeft', false ).'>fadeOutLeft</option>
				<option value="fadeOutLeftBig" '.selected( $args['value'], 'fadeOutLeftBig', false ).'>fadeOutLeftBig</option>
				<option value="fadeOutRight" '.selected( $args['value'], 'fadeOutRight', false ).'>fadeOutRight</option>
				<option value="fadeOutRightBig" '.selected( $args['value'], 'fadeOutRightBig', false ).'>fadeOutRightBig</option>
				<option value="fadeOutUp" '.selected( $args['value'], 'fadeOutUp', false ).'>fadeOutUp</option>
				<option value="fadeOutUpBig" '.selected( $args['value'], 'fadeOutUpBig', false ).'>fadeOutUpBig</option>
			</optgroup>

			<optgroup label="Flippers">
				<option value="flip" '.selected( $args['value'], 'flip', false ).'>flip</option>
				<option value="flipInX" '.selected( $args['value'], 'flipInX', false ).'>flipInX</option>
				<option value="flipInY" '.selected( $args['value'], 'flipInY', false ).'>flipInY</option>
				<option value="flipOutX" '.selected( $args['value'], 'flipOutX', false ).'>flipOutX</option>
				<option value="flipOutY" '.selected( $args['value'], 'flipOutY', false ).'>flipOutY</option>
			</optgroup>

			<optgroup label="Lightspeed">
				<option value="lightSpeedIn" '.selected( $args['value'], 'lightSpeedIn', false ).'>lightSpeedIn</option>
				<option value="lightSpeedOut" '.selected( $args['value'], 'lightSpeedOut', false ).'>lightSpeedOut</option>
			</optgroup>

			<optgroup label="Rotating Entrances">
				<option value="rotateIn" '.selected( $args['value'], 'rotateIn', false ).'>rotateIn</option>
				<option value="rotateInDownLeft" '.selected( $args['value'], 'rotateInDownLeft', false ).'>rotateInDownLeft</option>
				<option value="rotateInDownRight" '.selected( $args['value'], 'rotateInDownRight', false ).'>rotateInDownRight</option>
				<option value="rotateInUpLeft" '.selected( $args['value'], 'rotateInUpLeft', false ).'>rotateInUpLeft</option>
				<option value="rotateInUpRight" '.selected( $args['value'], 'rotateInUpRight', false ).'>rotateInUpRight</option>
			</optgroup>

			<optgroup label="Rotating Exits">
				<option value="rotateOut" '.selected( $args['value'], 'rotateOut', false ).'>rotateOut</option>
				<option value="rotateOutDownLeft" '.selected( $args['value'], 'rotateOutDownLeft', false ).'>rotateOutDownLeft</option>
				<option value="rotateOutDownRight" '.selected( $args['value'], 'rotateOutDownRight', false ).'>rotateOutDownRight</option>
				<option value="rotateOutUpLeft" '.selected( $args['value'], 'rotateOutUpLeft', false ).'>rotateOutUpLeft</option>
				<option value="rotateOutUpRight" '.selected( $args['value'], 'rotateOutUpRight', false ).'>rotateOutUpRight</option>
			</optgroup>

			<optgroup label="Sliding Entrances">
				<option value="slideInUp" '.selected( $args['value'], 'slideInUp', false ).'>slideInUp</option>
				<option value="slideInDown" '.selected( $args['value'], 'slideInDown', false ).'>slideInDown</option>
				<option value="slideInLeft" '.selected( $args['value'], 'slideInLeft', false ).'>slideInLeft</option>
				<option value="slideInRight" '.selected( $args['value'], 'slideInRight', false ).'>slideInRight</option>

			</optgroup>
			<optgroup label="Sliding Exits">
				<option value="slideOutUp" '.selected( $args['value'], 'slideOutUp', false ).'>slideOutUp</option>
				<option value="slideOutDown" '.selected( $args['value'], 'slideOutDown', false ).'>slideOutDown</option>
				<option value="slideOutLeft" '.selected( $args['value'], 'slideOutLeft', false ).'>slideOutLeft</option>
				<option value="slideOutRight" '.selected( $args['value'], 'slideOutRight', false ).'>slideOutRight</option>
				
			</optgroup>
			
			<optgroup label="Zoom Entrances">
				<option value="zoomIn" '.selected( $args['value'], 'zoomIn', false ).'>zoomIn</option>
				<option value="zoomInDown" '.selected( $args['value'], 'zoomInDown', false ).'>zoomInDown</option>
				<option value="zoomInLeft" '.selected( $args['value'], 'zoomInLeft', false ).'>zoomInLeft</option>
				<option value="zoomInRight" '.selected( $args['value'], 'zoomInRight', false ).'>zoomInRight</option>
				<option value="zoomInUp" '.selected( $args['value'], 'zoomInUp', false ).'>zoomInUp</option>
			</optgroup>
			
			<optgroup label="Zoom Exits">
				<option value="zoomOut" '.selected( $args['value'], 'zoomOut', false ).'>zoomOut</option>
				<option value="zoomOutDown" '.selected( $args['value'], 'zoomOutDown', false ).'>zoomOutDown</option>
				<option value="zoomOutLeft" '.selected( $args['value'], 'zoomOutLeft', false ).'>zoomOutLeft</option>
				<option value="zoomOutRight" '.selected( $args['value'], 'zoomOutRight', false ).'>zoomOutRight</option>
				<option value="zoomOutUp" '.selected( $args['value'], 'zoomOutUp', false ).'>zoomOutUp</option>
			</optgroup>

			<optgroup label="Specials">
				<option value="hinge" '.selected( $args['value'], 'hinge', false ).'>hinge</option>
				<option value="jackInTheBox" '.selected( $args['value'], 'jackInTheBox', false ).'>jackInTheBox</option>
				<option value="rollIn" '.selected( $args['value'], 'rollIn', false ).'>rollIn</option>
				<option value="rollOut" '.selected( $args['value'], 'rollOut', false ).'>rollOut</option>
			</optgroup>
		</select>';

		return $h;
	}



	public static function parallax_tpl($post = array(), $settings = array())
	{
		if( empty($post['post']))
			return;

		$b = $post['args'];
		$id = $post['post']->ID;

		$h = '';
		$h.= '<div class="spr_column">
			<div class="spr_column-inner left_column">
				<div class="spr_wrapper">
					<div class="option_box closed">
						<div class="info_header">
							<span class="nr"><svg viewBox="0 0 512 512" style="width:20px;"><path fill="currentColor" d="M12.41 148.02l232.94 105.67c6.8 3.09 14.49 3.09 21.29 0l232.94-105.67c16.55-7.51 16.55-32.52 0-40.03L266.65 2.31a25.607 25.607 0 0 0-21.29 0L12.41 107.98c-16.55 7.51-16.55 32.53 0 40.04zm487.18 88.28l-58.09-26.33-161.64 73.27c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.51 209.97l-58.1 26.33c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 276.3c16.55-7.5 16.55-32.5 0-40zm0 127.8l-57.87-26.23-161.86 73.37c-7.56 3.43-15.59 5.17-23.86 5.17s-16.29-1.74-23.86-5.17L70.29 337.87 12.41 364.1c-16.55 7.5-16.55 32.5 0 40l232.94 105.59c6.8 3.08 14.49 3.08 21.29 0L499.59 404.1c16.55-7.5 16.55-32.5 0-40z"></path></svg></span>
							<span class="text">'.__('Parallax Effect','adn').'</span>
							<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
						</div>';

						$h.= '<div class="settings_box_content hidden">';

							$para_active = array_key_exists('parallax', $b['display']) ? $b['display']['parallax']['active'] : 0;
							$h.= self::spr_column(array(
								'col' => 'spr_col-3',
								'title' => __('Activate','adn'),
								'desc' => __('Activate parallax effect.','adn'),
								'content' => self::switch_btn(array(
									'name' => 'display[parallax][active]',
									'id' => 'parallax_activate_btn',
									'checked' => $para_active,
									'value' => 1,
									'hidden_input' => 1,
									'chk-on' => __('Yes','adn'),
									'chk-off' => __('No','adn'),
									'chk-high' => 1
								))
							));
							//$h.= '<div class="clearFix"></div>';

							$h.= '<div class="spr_column spr_col">';
								$h.= '<div class="input_container">';
									$h.= '<div class="input_container_inner">';

										$is_hidden = !$para_active ? ' hidden' : '';

										$h.= '<div class="adn_settings_cont parallax_settings_container'.$is_hidden.'">';
											$h.= '<h4>'.__('Parallax Settings','adn').'</h4>';
											$h.= '<div class="adn_settings_cont_inner clear">';
												$h.= '<p>'.__('','adn').'</p>';

												$para_overflow = 0; // 0 = hidden, 1 = visible
												$para_y = 100; // vertical distance
												$para_x = 0; // horizontal distance
												$para_bg = '';
												$para_fb_bg = '';
												$para_bg_color = '';
												$para_bg_speed = 0.5;
												$para_bg_only = 0;
												if( !empty($b['display']) && array_key_exists('parallax', $b['display']) )
												{
													$para_overflow = $b['display']['parallax']['overflow'];
													$para_y = $b['display']['parallax']['y'];
													$para_x = $b['display']['parallax']['x'];
													$para_bg = $b['display']['parallax']['bg'];
													$para_fb_bg = array_key_exists('fb_bg', $b['display']['parallax']) ? $b['display']['parallax']['fb_bg'] : '';
													$para_bg_color = $b['display']['parallax']['bg_color'];
													$para_bg_speed = $b['display']['parallax']['bg_speed'];
													$para_bg_only = $b['display']['parallax']['bg_only'];
												}
												
												$h.= self::spr_column(array(
													'col' => 'spr_col-3',
													'title' => __('Overflow','adn'),
													'desc' => __('Hide parallax element behind the content or show it on top.','adn'),
													'content' => self::switch_btn(array(
														'name' => 'display[parallax][overflow]',
														'checked' => $para_overflow,
														'value' => 1,
														'hidden_input' => 1,
														'chk-on' => __('Visible','adn'),
														'chk-off' => __('Hidden','adn'),
														'chk-high' => 1
													))
												));
												$h.= self::spr_column(array(
													'col' => 'spr_col-3',
													'title' => __('Bg Only','adn'),
													'desc' => __('Hide banner and only show the parallax background.','adn'),
													'content' => self::switch_btn(array(
														'name' => 'display[parallax][bg_only]',
														'checked' => $para_bg_only,
														'value' => 1,
														'hidden_input' => 1,
														'chk-on' => __('Yes','adn'),
														'chk-off' => __('No','adn'),
														'chk-high' => 1
													))
												));
												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-3',
													'title' => __('Distance Y','adn'),
													'desc' => __('Vertical travel.','adn'),
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'width' => '100%',
														'name' => 'display[parallax][y]',
														'value' => $para_y,
														'placeholder' => '-100',
														'icon' => 'arrows-v',
														'show_icon' => 1
													))
												));
												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-3',
													'title' => __('Distance X','adn'),
													'desc' => __('Horizontal travel.','adn'),
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'width' => '100%',
														'name' => 'display[parallax][x]',
														'value' => $para_x,
														'placeholder' => '0',
														'icon' => 'arrows-h',
														'show_icon' => 1
													))
												));

												$h.= '<div class="clearFix"></div>';

												/*$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-6',
													'title' => __('Background','adn'),
													'desc' => __('Background image or video(mp4) for the parallax item.','adn'),
													'class' => 'parallax_settings',
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'class' => 'parallax_bg_src',
														'width' => '100%',
														'name' => 'display[parallax][bg]',
														'value' => $para_bg,
														'placeholder' => '',
														'icon' => 'link',
														'show_icon' => 1
													)).
													'<div data-id="'.$id.'" class="ParallaxUploader box" style="border:dashed 1px #d7d7d7;border-radius:3px;padding:15px 5px;background: #FFF;" method="post" action="'.ADNI_AJAXURL.'" enctype="multipart/form-data"></div>'
												));*/
												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-6',
													'title' => __('Background','adn'),
													'desc' => __('Background image or video(mp4) for the parallax item.','adn'),
													'class' => 'parallax_settings',
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'class' => 'parallax_bg_src',
														'width' => '100%',
														'name' => 'display[parallax][bg]',
														'value' => $para_bg,
														'placeholder' => '',
														'icon' => 'link',
														'show_icon' => 1
													)).
													ADNI_Templates::file_upload(array(
														'class' => 'ParallaxUploader',
														'data' => array('id' => $id, 'wpnonce' => wp_nonce_field( '_ning_upload_nonce', '_ning_upload_'.$id ))
													))
													//'<div data-id="'.$id.'" class="ParallaxUploader box" style="border:dashed 1px #d7d7d7;border-radius:3px;padding:15px 5px;background: #FFF;" method="post" action="'.ADNI_AJAXURL.'" enctype="multipart/form-data"></div>'
												));
												
												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-6',
													'title' => __('Background Fallback','adn'),
													'desc' => __('Fallback background image for maximum compatibility with all browsers.','adn'),
													'class' => 'parallax_settings',
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'class' => 'parallax_bg_src',
														'width' => '100%',
														'name' => 'display[parallax][fb_bg]',
														'value' => $para_fb_bg,
														'placeholder' => '',
														'icon' => 'link',
														'show_icon' => 1
													)).
													ADNI_Templates::file_upload(array(
														'class' => 'ParallaxUploader',
														'data' => array('id' => $id)
													))
													//'<div data-id="'.$id.'" class="ParallaxUploader box" style="border:dashed 1px #d7d7d7;border-radius:3px;padding:15px 5px;background: #FFF;" method="post" action="'.ADNI_AJAXURL.'" enctype="multipart/form-data"></div>'
												));

												$h.= '<div class="clearFix"></div>';

												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-2',
													'title' => __('Bg Color','adn'),
													'desc' => __('Background color for the parallax item.','adn'),
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'class' => 'parallax_bg_color',
														'name' => 'display[parallax][bg_color]',
														'value' => $para_bg_color
													)).
													"<script>jQuery(document).ready(function($){ $('.parallax_bg_color').coloringPick(); });</script>"
													//'content' => "<input class=\"parallax_bg_color\" name=\"pos[inside_content][parallax][bg_color]\" type=\"text\" value=\"".$popup_bg_color."\"><script>jQuery(document).ready(function($){ $('.parallax_bg_color').coloringPick(); });</script>"
												));


												$h.= ADNI_Templates::spr_column(array(
													'col' => 'spr_col-3',
													'title' => __('Bg Speed','adn'),
													'desc' => __('Parallax background speed. Number between -1.0 and 2.0.','adn'),
													'content' => ADNI_Templates::inpt_cont(array(
														'type' => 'text',
														'width' => '100%',
														'name' => 'display[parallax][bg_speed]',
														'value' => $para_bg_speed,
														'placeholder' => '0.5',
														'icon' => 'pencil',
														'show_icon' => 1
													))
												));
							
											
											$h.= '</div>';
										$h.= '</div>';
											
									$h.= '</div>';
								$h.= '</div>';
							$h.= '</div>';
							// end .spr_column
							$h.= '<div class="clearFix"></div>';

							$h.= ADNI_Templates::spr_column(array(
								'col' => 'spr_col',
								'title' => '',
								'desc' => '',
								'content' => '<input type="submit" value="'.sprintf(__('Save %s','adn'), ucfirst($b['type'])).'" class="button-primary" name="save_'.$b['type'].'">'
							));

						$h.= '</div>';
						// end .settings_box_content

					$h.= '</div>
				</div>
			</div>
		</div>';

		return $h;
	}





	public static function export_tpl($banner_post = array())
	{
		$h = '';

		$id = !empty($banner_post['post']) ? $banner_post['post']->ID : 0;
		$b = !empty($banner_post['post']) ? $banner_post['args'] : array();
		
		if($id)
		{
			$h.= '<div class="option_box">';
				$h.= '<div class="info_header">';
					$h.= '<span class="icon"><i class="fa fa-code" aria-hidden="true"></i></span>';
					$h.= '<span class="text">'.__('Export','adn').'</span>';
					$h.= '<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>';
				$h.= '</div>';

				$h.= '<div class="settings_box_content">';

					$h.= '<div class="input_container">';
						$h.= '<h3 class="title"></h3>';
						$h.= '<div class="input_container_inner">';
							$h.= '<input id="sc_code" style="font-size:11px;" type="text" value=\'[adning id="'.$id.'"]\' />';
						$h.= '</div>';
						$h.= '<span class="description bottom">'.__('Shortcode.','adn').'</span>';
					$h.= '</div>';
					//<!-- end .input_container -->
				

					if( !empty($b['display']) && $b['display']['parallax']['active'] )
					{
						$h.= self::spr_column(array(
							'col' => 'spr_col',
							'title' => '',
							'desc' => '',
							'content' => __('Parallax banners cannot be exported using embed code or iframe.','adn')
						));
					}
					else
					{
						$h.= '<div class="clearFix"></div>';

						$h.= self::spr_column(array(
							'col' => 'spr_col-6',
							'title' => '',
							'desc' => '',
							'content' => self::switch_btn(array(
								'id' => 'embed_export_switcher',
								'class' => 'export_switcher',
								'container_class' => 'export_switcher_cont embed_export_switcher',
								'data' => array('opos' => 'iframe_export_switcher', 'type' => 'embed'),
								'checked' => 1,
								'value' => 1,
								'chk-on' => __('Embed Code','adn'),
								'chk-off' => __('Embed Code','adn'),
								'chk-high' => 1
							)).
							self::switch_btn(array(
								'id' => 'iframe_export_switcher',
								'class' => 'export_switcher',
								'container_class' => 'export_switcher_cont iframe_export_switcher',
								'data' => array('opos' => 'embed_export_switcher', 'type' => 'iframe'),
								'checked' => 0,
								'value' => 1,
								'chk-on' => __('Iframe','adn'),
								'chk-off' => __('Iframe','adn'),
								'chk-high' => 1
							))
						));
						
						$h.= '<div class="clearFix"></div>';

						$h.= '<div class="input_container">';
							$h.= '<h3 class="title"></h3>';
								$h.= '<div class="input_container_inner">';
									
									$h.= '<div class="export_switch_box visible embed_code_container">';
										$code = '<script type="text/javascript">var _ning_embed = {"id":"'.$id.'","width":'.$b['size_w'].',"height":'.$b['size_h'].'};</script><script type="text/javascript" src="'.get_bloginfo('url').'?_dnembed=true"></script>';
										$h.= '<textarea class="export_embed_code" style="min-height:120px;font-size:11px;">'.$code.'</textarea>';
										$h.= '<span class="description bottom">'.__('Embed code.','adn').'</span>';
									$h.= '</div>';
									$h.= '<div class="export_switch_box iframe_code_container">';
										$code = '<div style="max-width:'.$b['size_w'].'px; width:100%; height:'.$b['size_h'].'px;"><iframe src="'.get_bloginfo('url').'?_dnid='.$id.'&t='.current_time('timestamp').'" border="0" scrolling="no" allowtransparency="true" style="width:1px;min-width:100%;*width:100%;height:100%;border:0;"></iframe></div>';
										$h.= '<textarea class="export_embed_code" style="min-height:120px;font-size:11px;">'.$code.'</textarea>';
										$h.= '<span class="description bottom">'.__('Iframe code.','adn').'</span>';
									$h.= '</div>';

									$h.= '<p>'.__('<strong>Note:</strong> "Display Filters" will not work with Embed Code and Iframe export options.','adn').'</p>';
								$h.= '</div>';
							
						$h.= '</div>';
						//<!-- end .input_container -->
					}

					$h.= '<div class="input_container">';
						$h.= '<h3 class="title">'.__('RSS Feed.','adn').'</h3>';
						$h.= '<div class="input_container_inner">';
							$h.= '<input id="ning_rss_feed" style="font-size:11px;" type="text" value="'.get_bloginfo('url').'?adning-rss='.$id.'" />';
						$h.= '</div>';
						$h.= '<span class="description bottom">'.__('Banner RSS Feed.','adn').'</span>';
					$h.= '</div>';
					//<!-- end .input_container -->

				$h.= '</div>';
				// end .settings_box_content

			$h.= '</div>';
			//<!-- end .option_box -->
		}

		return $h;
	}



	public static function display_filters_tpl($adzone, $settings = array())
	{
		if( empty($settings))
		{
			$set_arr = ADNI_Main::settings();
			$settings = $set_arr['settings'];
		}
		$type = $adzone['args']['type'];
		$save_name = 'save_'.$type;
		$h = '';

		$h.= '<div class="spr_column">
			<div class="spr_column-inner left_column">
				<div class="spr_wrapper">
					<div class="option_box">
						<div class="info_header">
							<span class="nr"><svg viewBox="0 0 576 512" style="width:23px;"><path fill="currentColor" d="M569.354 231.631C512.97 135.949 407.81 72 288 72 168.14 72 63.004 135.994 6.646 231.631a47.999 47.999 0 0 0 0 48.739C63.031 376.051 168.19 440 288 440c119.86 0 224.996-63.994 281.354-159.631a47.997 47.997 0 0 0 0-48.738zM288 392c-102.556 0-192.091-54.701-240-136 44.157-74.933 123.677-127.27 216.162-135.007C273.958 131.078 280 144.83 280 160c0 30.928-25.072 56-56 56s-56-25.072-56-56l.001-.042C157.794 179.043 152 200.844 152 224c0 75.111 60.889 136 136 136s136-60.889 136-136c0-31.031-10.4-59.629-27.895-82.515C451.704 164.638 498.009 205.106 528 256c-47.908 81.299-137.444 136-240 136z"></path></svg></span>
							<span class="text">'.__('Display Filters','adn').'</span>
							<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>';
							//<input type="submit" value="'.sprintf(__('Save %s','adn'), ucfirst($type)).'" class="button-primary" name="'.$save_name.'" style="width:auto;float:right;margin:8px;">
						$h.= '</div>';
						
						$h.= '<div class="settings_box_content">';

							$h.= ADNI_Templates::spr_column(array(
								'col' => 'spr_col',
								'title' => '',
								'desc' => '',
								'content' => '<p>'.__('Display filters allow you to show/hide ads on posts automatically.','adn').'</p>'
							));
							$h.= '<div class="clearFix"></div>';

							if( $type === 'adzone' )
							{
								$h.= ADNI_Templates::spr_column(array(
									'col' => 'spr_col',
									'title' => __('Disable Banner Filters','adn'),
									'desc' => __('By default banners loaded into adzones will still use their own "display filters" This option allows you to turn off the banner display filters and make them use the once for this adzone.','adn'),
									'content' => ADNI_Templates::switch_btn(array(
										'name' => 'no_banner_filter',
										'checked' => $adzone['args']['no_banner_filter'],
										'value' => 1,
										'hidden_input' => 1,
										'chk-on' => __('Yes','adn'),
										'chk-off' => __('No','adn'),
										'chk-high' => 1
									))
								));
							}
								
							$h.= '<div class="clear">
								<div class="input_container">
									<h3 class="title">'.__('Home Page','adn').'</h3>
								</div>';
								$h.= ADNI_Templates::spr_column(array(
									'col' => 'spr_col-6',
									'title' => '',
									'desc' => __('Show or Hide the banner on the home page.','adn'),
									'content' => ADNI_Templates::switch_btn(array(
										'name' => 'display_filter[homepage]',
										'checked' => $adzone['args']['display_filter']['homepage'],
										'value' => 1,
										'hidden_input' => 1,
										'chk-on' => __('Show','adn'),
										'chk-off' => __('Hide','adn'),
										'chk-high' => 1
									))
								));
								
							$h.= '</div>';


							$h.= '<div class="clear">
								<div class="input_container">
									<h3 class="title">'.__('404 Page','adn').'</h3>
								</div>';
								$h.= ADNI_Templates::spr_column(array(
									'col' => 'spr_col-6',
									'title' => '',
									'desc' => __('Show or Hide the banner on the 404 page.','adn'),
									'content' => ADNI_Templates::switch_btn(array(
										'name' => 'display_filter[404]',
										'checked' => $adzone['args']['display_filter']['404'],
										'value' => 1,
										'hidden_input' => 0,
										'chk-on' => __('Show','adn'),
										'chk-off' => __('Hide','adn'),
										'chk-high' => 1
									))
								));
								
							$h.= '</div>';

							$h.= '<div class="clear device_filter_container" style="margin-top: 40px;">
								<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Device Filters','adn').'</strong></span></div>
								<div class="spr_column">';
									$h.= self::devices_options($adzone['args']);		
								$h.= '</div>
								<!-- end .spr_column -->
							</div>';


							$h.= '<div class="clear device_filter_container" style="margin-top: 40px;">
								<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Country Filters','adn').'</strong></span></div>
								<div class="clear">';
									$h.= self::country_options($adzone['args']);		
								$h.= '</div>
							</div>';

							$h.= '<div class="clearFix"></div>';

							$h.= '<div class="clear device_filter_container" style="margin-top: 40px;">
								<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Author Filters','adn').'</strong></span></div>
								<div class="clear">';
									$author_display_filters = array_key_exists('authors', $adzone['args']['display_filter']) ? $adzone['args']['display_filter']['authors'] : array();
									$show_hide = !empty($author_display_filters) && array_key_exists('show_hide', $author_display_filters) ? $author_display_filters['show_hide'] : 1;
									$h.= ADNI_Templates::spr_column(array(
										'col' => 'spr_col-6',
										'title' => '',
										'desc' => __('Show or Hide the banner if post is created by the selected authors. Will only work when a post ID is available (like inside post/page content).','adn'),
										'content' => ADNI_Templates::switch_btn(array(
											'name' => 'display_filter[authors][show_hide]',
											'checked' => $show_hide,
											'value' => 1,
											'hidden_input' => 1,
											'chk-on' => __('Show','adn'),
											'chk-off' => __('Hide','adn'),
											'chk-high' => 1
										))
									));
									

									$h.= '<div class="spr_column spr_col-6">
										<div class="input_container">
											<div class="custom_box option_inside_content">
												<h3 class="title"></h3>
												<div class="input_container_inner ning_chosen_select">';
													
													$h.= '<select name="display_filter[authors][ids][]" data-placeholder="'.__('Start typing to select an author', 'adn').'" data-type="author" style="width:100%;" class="chosen-select ning_chosen_author_select" multiple>';
														$h.= '<option value=""></option>';
														
														$authors = '';
														if( array_key_exists('authors', $adzone['args']['display_filter']) )
														{
															$authors = array_key_exists('ids', $adzone['args']['display_filter']['authors']) ? $adzone['args']['display_filter']['authors']['ids'] : array();
														}
														// Load selected users
														if(!empty($authors))
														{
															foreach($authors as $author_id)
															{
																$user = get_userdata($author_id);
																$h.= '<option class="opt_'.$author_id.'" value="'.$author_id.'" selected>'.$user->display_name.' - (ID:'.$author_id.')</option>';
															}
														}
														
													$h.= '</select>';

												$h.= '</div>
											</div>
										</div>
									</div>';
								$h.= '</div>
							</div>';

							$h.= '<div class="clearFix"></div>';

							// CONTENT FILTERS
 							$h.= '<div class="clear" style="margin-top: 40px;">
								<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Content Filters','adn').'</strong></span></div>';
								$h.= '<div class="input_container">
									<p>
										'.__('Content filters only apply on ads where a post ID is available (like inside post/page content).','adn').'
									</p>
								</div>';

								if( !empty( $settings['positioning']['post_types'] ))
								{
									// Loop true all available post types
									foreach( $settings['positioning']['post_types'] as $post_type )
									{
										// Show / Hide for Post type
										$h.= '<div class="clear" style="border-bottom: solid 1px #efefef;margin-bottom: 20px;">
											<div class="input_container">
												<h3 class="title">'.sprintf(__('For %s','adn'), $post_type).'</h3>
											</div>';

											$show_hide = array_key_exists($post_type, $adzone['args']['display_filter']['post_types']) ? $adzone['args']['display_filter']['post_types'][$post_type]['show_hide'] : 1;
											$h.= ADNI_Templates::spr_column(array(
												'col' => 'spr_col-6',
												'title' => '',
												'desc' => sprintf(__('Show or Hide the banner for the selected %s.','adn'), $post_type),
												'content' => ADNI_Templates::switch_btn(array(
													'name' => 'display_filter[post_types]['.$post_type.'][show_hide]',
													'checked' => $show_hide,
													'value' => 1,
													'hidden_input' => 1,
													'chk-on' => __('Show','adn'),
													'chk-off' => __('Hide','adn'),
													'chk-high' => 1
												))
											));
											

											$h.= '<div class="spr_column spr_col-6">
												<div class="input_container">
													<div class="custom_box option_inside_content">
														<h3 class="title"></h3>
														<div class="input_container_inner ning_chosen_select">';
															
															$h.= '<select name="display_filter[post_types]['.$post_type.'][ids][]" data-placeholder="'.sprintf(__('Start typing to select a %s', 'adn'), $post_type).'" data-type="'.$post_type.'" style="width:100%;" class="chosen-select ning_chosen_posttype_select" multiple>';
																$h.= '<option value=""></option>';
																
																//$posts = $adzone['args']['display_filter']['posts'];
																$posts = '';
																if( array_key_exists('post_types', $adzone['args']['display_filter']) )
																{
																	$posts = array_key_exists($post_type, $adzone['args']['display_filter']['post_types']) ? $adzone['args']['display_filter']['post_types'][$post_type]['ids'] : '';
																}
																// Load selected posts
																if(!empty($posts))
																{
																	foreach($posts as $post_id)
																	{
																		$h.= '<option class="opt_'.$post_id.'" value="'.$post_id.'" selected>'.get_the_title($post_id).' - (ID:'.$post_id.')</option>';
																	}
																}
																/*$all_posts = get_posts(array(
																	'posts_per_page'   => -1,
																	'post_status'      => 'publish',
																	'post_type'        => $post_type
																));*/
																/*$all_posts = $GLOBALS[ 'wpdb' ]->get_results( "SELECT ID, post_title FROM ".$GLOBALS[ 'wpdb' ]->prefix."posts WHERE post_status = 'publish' AND post_type='".$post_type."'" );
												
																foreach($all_posts as $i => $post)
																{
																	$selected = !empty($posts) && is_array($posts) ? in_array($post->ID, $posts) ? 'selected' : '' : '';
																	$h.= '<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.' - (ID:'.$post->ID.')</option>';
																}*/
															$h.= '</select>';

														$h.= '</div>
													</div>
												</div>
											</div>
											<!-- end .spr_column -->';


											$h.= '<div class="taxonomies clearFix" style="width:100%;box-sizing: border-box;padding-left: 30px;">';

											$taxonomies = get_object_taxonomies( $post_type );
											//$h.= '<pre>'.print_r($taxonomies, true).'</pre>';
											if(!empty($taxonomies))
											{
												foreach($taxonomies as $taxonomy)
												{
													$terms = get_terms( $taxonomy );
													if( !empty($terms))
													{
														$tax_arr = array_key_exists($post_type, $adzone['args']['display_filter']['post_types']) ? $adzone['args']['display_filter']['post_types'][$post_type] : array();
														$tax_arr = array_key_exists('taxonomies', $tax_arr) ? $adzone['args']['display_filter']['post_types'][$post_type]['taxonomies'] : array();
														$tax_arr = array_key_exists($taxonomy, $tax_arr) ? $tax_arr[$taxonomy] : array();

														//$h.= '<pre>'.print_r($terms, true).'</pre>';
														$h.= '<div class="clear">
															<div class="input_container">
																<h3 class="title">'.sprintf(__('For %s','adn'), $taxonomy).'</h3>
															</div>';

															$show_hide = array_key_exists('show_hide', $tax_arr) ? $tax_arr['show_hide'] : 0;
															$h.= ADNI_Templates::spr_column(array(
																'col' => 'spr_col-6',
																'title' => '',
																'desc' => sprintf(__('Show or Hide the banner for the selected %s.','adn'),$taxonomy),
																'content' => ADNI_Templates::switch_btn(array(
																	'name' => 'display_filter[post_types]['.$post_type.'][taxonomies]['.$taxonomy.'][show_hide]',
																	'checked' => $show_hide,
																	'value' => 1,
																	'hidden_input' => 1,
																	'chk-on' => __('Show','adn'),
																	'chk-off' => __('Hide','adn'),
																	'chk-high' => 1
																))
															));
																	//$show_hid
															/*<div class="spr_column spr_col-6">
																<div class="input_container">';
																	
																	e = array_key_exists('tags', $adzone['args']['display_filter']) ? $adzone['args']['display_filter']['tags']['show_hide'] : 0;
																	$h.= '<label class="switch switch-slide small input_h ttip" title="'.__('Show/Hide.','adn').'">
																		<input class="switch-input" type="checkbox" name="display_filter[post_types]['.$post_type.'][taxonomies]['.$taxonomy.'][show_hide]" value="1" '.checked($show_hide,1,false).' />
																		<span class="switch-label" data-on="'.__('Show','adn').'" data-off="'.__('Hide','adn').'"></span> 
																		<span class="switch-handle"></span>
																	</label>';

																	$h.= '<span class="description bottom">'.sprintf(__('Show or Hide the banner for the selected %s.','adn'),$taxonomy).'</span>
																</div>
															</div>
															<!-- end .spr_column -->*/

															$h.= '<div class="spr_column spr_col-6">
																<div class="input_container">
																	<div class="custom_box option_inside_content">
																		<h3 class="title"></h3>
																		<div class="input_container_inner ning_chosen_select">';
																			
																			$h.= '<select name="display_filter[post_types]['.$post_type.'][taxonomies]['.$taxonomy.'][ids][]" data-placeholder="'.sprintf(__('Start typing to select a %s', 'adn'),$taxonomy).'" data-type="'.$taxonomy.'" style="width:100%;" class="chosen-select ning_chosen_taxonomy_select" multiple>';
																				$h.= '<option value=""></option>';
																				
																				$tags = array_key_exists('ids', $tax_arr) ? $tax_arr['ids'] : '';
																				//$allowed_terms = apply_filters( 'adning_hide_terms', $terms);
																				if( !empty( $tags ))
																				{
																					foreach($tags as $tag)
																					{
																						$term = get_term( $tag, $taxonomy );
																						$h.= '<option class="opt_'.$term->term_id.'" value="'.$term->term_id.'" selected>'.$term->name.' - (ID:'.$term->term_id.')</option>';
																					}
																				}
																				
																				/*foreach($terms as $term)
																				{
																					//if(in_array($term->taxonomy, $allowed_terms))
																					//{
																						$selected = !empty($tags) && is_array($tags) ? in_array($term->term_id, $tags) ? 'selected' : '' : '';
																						$h.= '<option value="'.$term->term_id.'" '.$selected.'>'.$term->name.' - (ID:'.$term->term_id.')</option>';
																					//}
																				}*/
																				
																			$h.= '</select>';
																			
																		$h.= '</div>
																	</div>
																</div>
															</div>
															<!-- end .spr_column -->
														</div>';
													}
													
												}
											}
											$h.= '</div>';
											// end .taxonomies
											
										$h.= '</div>';
									}
								}
								else
								{
									$h.= ADNI_Templates::spr_column(array(
										'col' => 'spr_col',
										'title' => '',
										'desc' => '',
										'content' => sprintf(__('<strong>Note:</strong> No Post Types have been selected under <em>General Settings</em> -> <em>Placement Settings</em> -> <em>Post Types for ADS</em>. As a result ADS will not be visible on most of the pages. %s','adn'), '<div><a class="button-secondary" style="margin-top:5px;" href="'.esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning-settings#posttypes_for_ads'))).'">'.__('Select post types here','adn').'</a></div>')
									));
								}

							$h.= '</div>';

							$h.= ADNI_Templates::spr_column(array(
								'col' => 'spr_col',
								'title' => '',
								'desc' => '',
								'content' => '<input type="submit" value="'.sprintf(__('Save %s','adn'), ucfirst($type)).'" class="button-primary" name="'.$save_name.'">'
							));

						$h.= '</div>';
						// end .settings_box_content

					$h.= '</div>
				</div>
			</div>
		</div>';

		return $h;
	}






	public static function border_settings_tpl($b = array())
	{
		$h = '';
		$h.= '<div class="option_box closed">
			<div class="info_header">
				<span class="nr">
				<svg viewBox="0 0 576 512"><path fill="currentColor" d="M564 224c6.627 0 12-5.373 12-12v-72c0-6.627-5.373-12-12-12h-72c-6.627 0-12 5.373-12 12v12h-88v-24h12c6.627 0 12-5.373 12-12V44c0-6.627-5.373-12-12-12h-72c-6.627 0-12 5.373-12 12v12H96V44c0-6.627-5.373-12-12-12H12C5.373 32 0 37.373 0 44v72c0 6.627 5.373 12 12 12h12v160H12c-6.627 0-12 5.373-12 12v72c0 6.627 5.373 12 12 12h72c6.627 0 12-5.373 12-12v-12h88v24h-12c-6.627 0-12 5.373-12 12v72c0 6.627 5.373 12 12 12h72c6.627 0 12-5.373 12-12v-12h224v12c0 6.627 5.373 12 12 12h72c6.627 0 12-5.373 12-12v-72c0-6.627-5.373-12-12-12h-12V224h12zM352 64h32v32h-32V64zm0 256h32v32h-32v-32zM64 352H32v-32h32v32zm0-256H32V64h32v32zm32 216v-12c0-6.627-5.373-12-12-12H72V128h12c6.627 0 12-5.373 12-12v-12h224v12c0 6.627 5.373 12 12 12h12v160h-12c-6.627 0-12 5.373-12 12v12H96zm128 136h-32v-32h32v32zm280-64h-12c-6.627 0-12 5.373-12 12v12H256v-12c0-6.627-5.373-12-12-12h-12v-24h88v12c0 6.627 5.373 12 12 12h72c6.627 0 12-5.373 12-12v-72c0-6.627-5.373-12-12-12h-12v-88h88v12c0 6.627 5.373 12 12 12h12v160zm40 64h-32v-32h32v32zm0-256h-32v-32h32v32z"></path></svg>
				</span>
				<span class="text">'.__('Border Settings','adn').'</span>
				<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
			</div>

			<div class="settings_box_content hidden">
				<div class="spr_row">';
					$h.= ADNI_Templates::spr_column(array(
						'col' => 'spr_col-6',
						'title' => __('Add Border','adn'),
						'desc' => '',
						'content' => ADNI_Templates::switch_btn(array(
							'name' => 'cont_border',
							'tooltip' => __('Add a border arround the banner','adn'),
							'id' => 'ADNI_has_border',
							'checked' => $b['cont_border'],
							'value' => 1,
							'hidden_input' => 1,
							'chk-on' => __('On','adn'),
							'chk-off' => __('Off','adn'),
							'chk-high' => 0
						))
					));
					
					$h.= '<div class="spr_column spr_col-6">
						<div class="spr_column-inner">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('Border Color','adn').'</h3>
									<div class="input_container_inner small_coloringPick">';
										//$border_color = !empty($b['cont_border_color']) ? $b['cont_border_color'] : '';
										
										$h.= '<input id="cont_border_color" name="cont_border_color" type="text" value="'.$b['cont_border_color'].'">';
										$h.= "<script>jQuery(document).ready(function($){ $('#cont_border_color').coloringPick({'on_select': function(color){ $('.banner_holder').find('._ning_outer').css({'background': color}); } }); });</script>";
									$h.= '</div>
									<span class="description bottom">'.__('','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>
					<!-- end .spr_column -->
				</div>
				<!-- end .spr_row -->


				<div class="spr_row">  
					<div class="spr_column spr_col-6">
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('Label Text','adn').'</h3>';

									$cont_label = !empty($b['cont_label']) ? $b['cont_label'] : '';
									$h.= '<div class="input_container_inner">
											<input 
											type="text" 
											class="" 
											id="ADNI_label"
											name="cont_label" 
											value="'.addslashes($cont_label).'" 
											placeholder="'.__('Advertisement','adn').'">
										<i class="input_icon fa fa-pencil" aria-hidden="true"></i>
									</div>
									<span class="description bottom">'.__('Label to show in the banner container.','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>
					<!-- end .spr_column -->
					
					<div class="spr_column spr_col-6">
						<div class="spr_column-inner">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('Label Position','adn').'</h3>
									<div class="input_container_inner">';
										$h.= '<select id="ADNI_label_pos" name="cont_label_pos" class="">
											<option value="left"'.selected( $b['cont_label_pos'], 'left', false ).'>'.__('Left','adn').'</option>
											<option value="center"'.selected( $b['cont_label_pos'], 'center', false ).'>'.__('Center','adn').'</option>
											<option value="right"'.selected( $b['cont_label_pos'], 'right', false ).'>'.__('Right','adn').'</option>
										</select>';
									$h.= '</div>
									<span class="description bottom">'.__('','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>
					<!-- end .spr_column -->
				</div>
				<!-- end .spr_row -->


				<div class="spr_row">  
					<div class="spr_column spr_col-6">
						<div class="spr_column-inner">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('Label Font Color','adn').'</h3>
									<div class="input_container_inner small_coloringPick">';
										//$border_color = !empty($b['cont_border_color']) ? $b['cont_border_color'] : '';
										
										$h.= '<input id="cont_label_color" name="cont_label_color" type="text" value="'.$b['cont_label_color'].'">';
										$h.= "<script>jQuery(document).ready(function($){ $('#cont_label_color').coloringPick({'picker':'solid','picker_changeable':false, 'on_select': function(color){ $('.banner_holder').find('._ning_label').css({'color': color}); } }); });</script>";
									$h.= '</div>
									<span class="description bottom">'.__('','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>
					<!-- end .spr_column -->
				</div>
				<!-- end .spr_row -->';

			$h.= '</div>';
			// end .settings_box_content

		$h.= '</div>
		<!-- end .option_box -->';

		return $h;
	}




	public static function stats_settings_tpl($args = array(), $b = array())
	{
		$defaults = array(
			'id' => 0,
			'unique' => 0,
			'frontend' => 0, 
			'time_range' => '' //custom_TIMESTAMP::TIMESTAMP
		);
		$args = wp_parse_args($args, $defaults);

		$h = '';
		$id = $args['id'];
		$type = $b['type'];
		$group = $type === 'banner' ? 'id_1' : 'id_2';
		$args['time_range'] = 'custom_'.get_the_time('U', $id).'::'.current_time('timestamp');

		if( ADNI_Main::has_stats(array('type' => 'int')) || ADNI_Main::has_stats(array('type' => 'ext')) )
		{
			// show stats for adzones alaways as they are based on banner stats anyway.
			$b['enable_stats'] = $type === 'banner' ? $b['enable_stats'] : 1; 
			
			$h.= '<div class="option_box">
				<div class="info_header">
					<span class="icon"><svg viewBox="0 0 512 512" style="width:20px;"><path fill="currentColor" d="M496 384H64V80c0-8.84-7.16-16-16-16H16C7.16 64 0 71.16 0 80v336c0 17.67 14.33 32 32 32h464c8.84 0 16-7.16 16-16v-32c0-8.84-7.16-16-16-16zM464 96H345.94c-21.38 0-32.09 25.85-16.97 40.97l32.4 32.4L288 242.75l-73.37-73.37c-12.5-12.5-32.76-12.5-45.25 0l-68.69 68.69c-6.25 6.25-6.25 16.38 0 22.63l22.62 22.62c6.25 6.25 16.38 6.25 22.63 0L192 237.25l73.37 73.37c12.5 12.5 32.76 12.5 45.25 0l96-96 32.4 32.4c15.12 15.12 40.97 4.41 40.97-16.97V112c.01-8.84-7.15-16-15.99-16z" class=""></path></svg></span>
					<span class="text">'.sprintf(__('%s Stats','adn'), ucfirst($type)).'</span>
					<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
				</div>';

				$h.= '<div class="settings_box_content">';
					if( $b['enable_stats'] && ADNI_Main::has_stats(array('type' => 'int')) )
					{
						$impressions = !empty($id) ? ADNI_Main::count_stats(array('type' => 'impression', 'group' => $group, 'id' => $id, 'time_range' => $args['time_range'])) : 0;
						$clicks = !empty($id) ? ADNI_Main::count_stats(array('type' => 'click', 'group' => $group, 'id' => $id, 'time_range' => $args['time_range'])) : 0;
						
						$h.= self::spr_column(array(
							'col' => 'spr_col-6',
							'class' => 'stats_box',
							'title' => __('Impressions','adn'),
							'desc' => sprintf(__('All %s impressions.','adn'),$type),
							'content' => '<center>'.ADNI_Main::number_format_short($impressions).'</center>'
						));
						$h.= self::spr_column(array(
							'col' => 'spr_col-6',
							'class' => 'stats_box',
							'title' => __('Clicks','adn'),
							'desc' => sprintf(__('All %s clicks.','adn'),$type),
							'content' => '<center>'.ADNI_Main::number_format_short($clicks).'</center>'
						));

						if($type === 'adzone')
						{
							$h.= '<div class="clearFix"></div>';
							$h.= self::spr_column(array(
								'col' => 'spr_col',
								'class' => 'stats_info',
								'content' => __('Adzone stats are based on the banners showing inside the adzone. Empty adzones will not collect stats.','adn')
							));
						}
					}

					// External stats notice
					if( $b['enable_stats'] && ADNI_Main::has_stats(array('type' => 'ext')) )
					{
						if( is_plugin_active( 'adning-google-analytics/adning-google-analytics.php' ) )
						{
							$impressions = ADNIGA_Init::load_stats(array(
								'start_date' => '2012-01-12',
								'end_date' => 'today', // 1daysAgo
								'id' => $id, // banner/adzone id
								'post_type' => $type
							));
							$clicks = ADNIGA_Init::load_stats(array(
								'start_date' => '2012-01-12',
								'end_date' => 'today', // 1daysAgo
								'id' => $id, // banner/adzone id
								'post_type' => $type,
								'stats_type' => 'click'
							));


							$h.= self::spr_column(array(
								'col' => 'spr_col-6',
								'class' => 'stats_box',
								'title' => __('Impressions','adn'),
								'desc' => sprintf(__('All %s impressions.','adn'), $type),
								'content' => '<center>'.ADNI_Main::number_format_short($impressions).'</center>'
							));
							$h.= self::spr_column(array(
								'col' => 'spr_col-6',
								'class' => 'stats_box',
								'title' => __('Clicks','adn'),
								'desc' => sprintf(__('All %s clicks.','adn'),$type),
								'content' => '<center>'.ADNI_Main::number_format_short($clicks).'</center>'
							));
						}

						$h.= self::spr_column(array(
							'col' => 'spr_col',
							'class' => 'stats_info',
							'content' => sprintf(__('Stats are getting tracked by %s.','adn'), '<a href="https://analytics.google.com/analytics/" target="_blank">'.__('Google Analytics','adn').'</a>')
						));
					}
					

					if( !$args['frontend'] )
					{
						$h.= '<div class="clearFix sep_line" style="margin:0 0 15px 0;"><span></span></div>';
						$h.= $type === 'banner' ? self::spr_column(array(
							'col' => 'spr_col-4',
							'title' => __('Enable stats','adn'),
							//'desc' => __('All adzone clicks.','adn'),
							'content' => self::switch_btn(array(
								'title' => __('Desktop','adn'),
								'name' => 'enable_stats',
								'checked' => $b['enable_stats'],
								'value' => 1,
								'hidden_input' => 1,
								'chk-on' => __('Yes','adn'),
								'chk-off' => __('No','adn')
							))
						)) : '';

						if( $b['enable_stats'] && ADNI_Main::has_stats(array('type' => 'int')) )
						{
							$stats_url = esc_url( wp_nonce_url( self_admin_url( 'admin.php?page=strack-statistics&group='.$group.'&group_id='.$id ) ) );
							//$stats_url = 'admin.php?page=strack-statistics&group='.$group.'&group_id='.$id; // .'&range='.$args['time_range']
							$remove_stats_url = esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning&view='.$type.'&id='.$id.'&reset_stats=1')));
							$h.= self::spr_column(array(
								'col' => 'spr_col-4',
								'title' => __('View all stats','adn'),
								//'desc' => __('All adzone clicks.','adn'),
								'content' => '<a class="button-secondary" href="'.$stats_url.'">'.__('All Stats','adn').'</a>'
							));
							$h.= self::spr_column(array(
								'col' => 'spr_col-4',
								'title' => __('Reset stats','adn'),
								//'desc' => __('All adzone clicks.','adn'),
								'content' => '<a class="button-secondary" id="reset_stats" style="background:#ffe8f0;" data-href="'.$remove_stats_url.'" data-msg="'.sprintf(__("Are you sure you want to reset all statistics for this %s? This will remove all available %s stats and cannot be undone.","adn"), $type, $type).'">'.__('Reset Stats','adn').'</a>'
							));
						}
					}

				$h.= '</div>';
				// end .settings_box_content
				
			$h.= '</div>';
			// end .option_box
		}

		return $h;
	}





	public static function link_campaign_tpl($b = array())
	{
		global $current_user;

		$h = '';
		$h.= '<div class="option_box">
			<div class="info_header">
				<span class="nr">3</span>
				<span class="text">'.__('Campaigns','adn').'</span>
				<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
			</div>';

			$h.= '<div class="settings_box_content">';
				$h.= '<div class="spr_row">  
					<div class="spr_column">
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('','adn').'</h3>
									<p>
										'.__('Select the campaigns you want to link this banner too.','adn').'
									</p>
									
									<div class="input_container_inner">';
										$h.= '<select name="campaigns[]" data-placeholder="'.__('Select campaigns', 'adn').'" style="width:100%;" class="chosen-select" multiple>';
											$h.= '<option value=""></option>';
											
											$posts = $b['campaigns'];
											// Check if user can load all campaigns or only his/here own.
											$limit_user_posts = !current_user_can(ADNI_ALL_BANNERS_ROLE) ? array('author' => $current_user->ID) : array();
											
											$all_posts = get_posts(
												ADNI_Main::parse_args(array(
													'posts_per_page'   => -1,
													'post_status'      => 'publish',
													'post_type'        => ADNI_CPT::$campaign_cpt
												),$limit_user_posts)
											);
							
											foreach($all_posts as $i => $post)
											{
												$selected = !empty($posts) && is_array($posts) ? in_array($post->ID, $posts) ? 'selected' : '' : '';
												$h.= '<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.' - (ID:'.$post->ID.')</option>';
											}
										$h.= '</select>';
										
									$h.= '</div>
									<span class="description bottom">'.__('','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>
					<!-- end .spr_column -->
				</div>';

			$h.= '</div>';
			// end .settings_box_content

		$h.= '</div>';

		return $h;
	}



	public static function link_adzone_tpl($b = array())
	{
		global $current_user;

		$h = '';
		$h.= '<div class="option_box">
			<div class="info_header">
				<span class="nr">4</span>
				<span class="text">'.__('Adzones','adn').'</span>
				<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
			</div>';

			$h.= '<div class="settings_box_content">';
				$h.= '<div class="spr_row">  
					<div class="spr_column">
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('','adn').'</h3>
									<p>
										'.__('Select the adzones you want to link this banner too.','adn').'
									</p>
									
									<div class="input_container_inner">';
										$h.= '<select name="adzones[]" data-placeholder="'.__('Select adzones', 'adn').'" style="width:100%;" class="chosen-select" multiple>';
											$h.= '<option value=""></option>';
											
											$posts = $b['adzones'];
											// Check if user can load all adzones or only his/here own.
											$limit_user_posts = !current_user_can(ADNI_ALL_BANNERS_ROLE) ? array('author' => $current_user->ID) : array();
											
											$all_posts = get_posts(
												ADNI_Main::parse_args(array(
													'posts_per_page'   => -1,
													'post_status'      => 'publish',
													'post_type'        => ADNI_CPT::$adzone_cpt
												),$limit_user_posts)
											);
							
											foreach($all_posts as $i => $post)
											{
												$selected = !empty($posts) && is_array($posts) ? in_array($post->ID, $posts) ? 'selected' : '' : '';
												$h.= '<option value="'.$post->ID.'" '.$selected.'>'.$post->post_title.' - (ID:'.$post->ID.')</option>';
											}
										$h.= '</select>';
										
									$h.= '</div>
									<span class="description bottom">'.__('','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>';
					//<!-- end .spr_column -->

					$h.= ADNI_Templates::spr_column(array(
						'col' => 'spr_col',
						'title' => __('Banner Duration','adn'),
						'desc' => __('Duration time for this banner in seconds.','adn'),
						'content' => ADNI_Templates::inpt_cont(array(
							'type' => 'text',
							'width' => '100%',
							'name' => 'duration',
							'value' => $b['duration'],
							'placeholder' => '5',
							'icon' => 'clock',
							'show_icon' => 1
						))
					));

				$h.= '</div>';
			$h.= '</div>';
			// end .settings_box_content

		$h.= '</div>';

		return $h;
	}




	public static function alignment_settings_tpl($b = array())
	{
		$h = '';
		$h.= '<div class="option_box">
			<div class="info_header">
				<span class="nr">
				<svg viewBox="0 0 448 512"><path fill="currentColor" d="M352 44v40c0 8.837-7.163 16-16 16H112c-8.837 0-16-7.163-16-16V44c0-8.837 7.163-16 16-16h224c8.837 0 16 7.163 16 16zM16 228h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 256h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm320-200H112c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16h224c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16z"></path></svg>
				</span>
				<span class="text">'.__('Alignment Settings','adn').'</span>
				<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
			</div>

			<div class="settings_box_content">
				<div class="spr_row">  
					<div class="spr_column spr_col-6">
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div class="input_container">
									<h3 class="title">'.__('Banner Alignment','adn').'</h3>
									
									<div class="input_container_inner">';
										$h.= '<select id="ADNI_align" name="align" class="">
											<option value="left"'.selected( $b['align'], 'left', false ).'>'.__('Left','adn').'</option>
											<option value="center"'.selected( $b['align'], 'center', false ).'>'.__('Center','adn').'</option>
											<option value="right"'.selected( $b['align'], 'right', false ).'>'.__('Right','adn').'</option>
										</select>';
									$h.= '</div>
									<span class="description bottom">'.__('','adn').'</span>
								</div>
								<!-- end .input_container -->
							</div>
						</div>
					</div>';
					// <!-- end .spr_column -->

					$h.= ADNI_Templates::spr_column(array(
						'col' => 'spr_col-6',
						'title' => __('Wrap Text','adn'),
						'desc' => '',
						'content' => ADNI_Templates::switch_btn(array(
							'name' => 'wrap_text',
							'tooltip' => __('Wrap text around the banner.','adn'),
							'id' => 'ADNI_wrap_text',
							'checked' => $b['wrap_text'],
							'value' => 1,
							'hidden_input' => 1,
							'chk-on' => __('Yes','adn'),
							'chk-off' => __('No','adn'),
							'chk-high' => 1
						))
					));
				$h.= '</div>';
				// <!-- end .spr_row -->

				$h.= '<div class="clearFix"></div>';

			$h.= '</div>';
			// end .settings_box_content

		$h.= '</div>';
		// <!-- end .option_box -->

		return $h;
	}






	public static function adsense_tpl($args = array())
	{
		$defaults = array(
			'pub_id' => '',
			'type' => 'normal',
			'slot_id' => '',
			'width' => 300,
			'height' => 250,
			'layout_key' => '',
			'layout' => '',
			'google_src' => '//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'
		);
		$args = wp_parse_args($args, $defaults);

		$code = '';

		switch ( $args['type'] ) 
		{
			case 'in-feed':
				$code = '<script async src="'.$args['google_src'].'"></script>'.
						'<ins class="adsbygoogle" ' .
							 'style="display:block;" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '" ' .
							 'data-ad-layout-key="' . $args['layout_key'] . '" ';
				if ( $args['layout'] !== '' ) {
					$code .= 'data-ad-layout="' . $args['layout'] . '" ';
				}
				$code .= 'data-ad-format="fluid"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			case 'in-article':
				$code = '<script async src="'.$args['google_src'].'"></script>' .
						'<ins class="adsbygoogle" ' .
							 'style="display:block;text-align:center;" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '" ' .
							 'data-ad-layout="in-article" ' .
							 'data-ad-format="fluid"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			case 'matched-content':
				$code = '<script async src="'.$args['google_src'].'"></script>' .
						'<ins class="adsbygoogle" ' .
							 'style="display:block;" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '" ' .
							 'data-ad-format="autorelaxed"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			case 'link-responsive':
				$code = '<script async src="'.$args['google_src'].'"></script>' .
						'<ins class="adsbygoogle" ' .
							 'style="display:block;" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '" ' .
							 'data-ad-format="link"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			case 'link':
				$code = '<script async src="'.$args['google_src'].'"></script>' .
						'<ins class="adsbygoogle" ' .
							 'style="display:block;width:' . $args['width'] . 'px;height:' . $args['height'] . 'px" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '" ' .
							 'data-ad-format="link"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			case 'responsive':
				$code = '<script async src="'.$args['google_src'].'"></script>' .
						'<ins class="adsbygoogle" ' .
							 'style="display:block;" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '" ' .
							 'data-ad-format="auto"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			case 'normal':
				$code = '<script async src="'.$args['google_src'].'"></script>' .
						'<ins class="adsbygoogle" ' .
							 'style="display:inline-block;width:' . $args['width'] . 'px;height:' . $args['height'] . 'px" ' .
							 'data-ad-client="ca-' . $args['pub_id'] . '" ' .
							 'data-ad-slot="' . $args['slot_id'] . '"></ins>' .
						'<script>' .
						'(adsbygoogle = window.adsbygoogle || []).push({});' .
						'</script>';
				break;
			default:
		}

		return $code;
	}




	public static function devices_options($args = array())
	{
		$html = '';

		// DESKTOP
		$show_desktop = array_key_exists('show_desktop',$args['display_filter']) ? $args['display_filter']['show_desktop'] : 1;
		$show_desktop = $show_desktop === '' ? 1 : $show_desktop;
		
		$html.= self::switch_btn(array(
			'title' => __('Desktop','adn'),
			'id' => 'dopt_show_desktop',
			'name' => 'df_show_desktop',
			'checked' => $show_desktop,
			'value' => 1,
			'hidden_input' => 1,
			'chk-on' => __('SHOW','adn'),
			'chk-off' => __('HIDE','adn'),
			'chk-high' => 1,
			'column' => array(
				'size' => 'col-3',
				'desc' => __('Show banner on desktop.','adn'),
			)
		));
		// TABLET
		$show_tablet = array_key_exists('show_tablet',$args['display_filter']) ? $args['display_filter']['show_tablet'] : 1;
		$show_tablet = $show_tablet === '' ? 1 : $show_tablet;
		$html.= self::switch_btn(array(
			'title' => __('Tablet','adn'),
			'id' => 'dopt_show_tablet',
			'name' => 'df_show_tablet',
			'checked' => $show_tablet,
			'value' => 1,
			'hidden_input' => 1,
			'chk-on' => __('SHOW','adn'),
			'chk-off' => __('HIDE','adn'),
			'chk-high' => 1,
			'column' => array(
				'size' => 'col-3',
				'desc' => __('Show banner on tablet devices.','adn'),
			)
		));
		// MOBILE
		$show_mobile = array_key_exists('show_mobile',$args['display_filter']) ? $args['display_filter']['show_mobile'] : 1;
		$show_mobile = $show_mobile === '' ? 1 : $show_mobile;
		$html.= self::switch_btn(array(
			'title' => __('Mobile','adn'),
			'desc' => __('Show banner on mobile devices.','adn'),
			'id' => 'dopt_show_mobile',
			'name' => 'df_show_mobile',
			'checked' => $show_mobile,
			'value' => 1,
			'hidden_input' => 1,
			'chk-on' => __('SHOW','adn'),
			'chk-off' => __('HIDE','adn'),
			'chk-high' => 1,
			'column' => array(
				'size' => 'col-3',
				'desc' => __('Show banner on mobile devices.','adn'),
			)
		));
		
		/*$html.= '<hr>';
		
		// IOS
		$html.= self::checkbox(array(
			'title' => __('IOS','adn'),
			'desc' => __('Show banner on IOS devices.','adn'),
			'id' => 'dopt_show_ios',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// Android
		$html.= self::checkbox(array(
			'title' => __('Android','adn'),
			'desc' => __('Show banner on Android devices.','adn'),
			'id' => 'dopt_show_android',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// Windows Mobile
		$html.= self::checkbox(array(
			'title' => __('Windows Mobile','adn'),
			'desc' => __('Show banner on Windows Mobile devices.','adn'),
			'id' => 'dopt_show_windows_mobile',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// IPhone
		$html.= self::checkbox(array(
			'title' => __('Iphone','adn'),
			'desc' => __('Show banner on Iphones.','adn'),
			'id' => 'dopt_show_iphone',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// IPad
		$html.= self::checkbox(array(
			'title' => __('Ipad','adn'),
			'desc' => __('Show banner on Ipads.','adn'),
			'id' => 'dopt_show_ipad',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// IPod
		$html.= self::checkbox(array(
			'title' => __('Ipod','adn'),
			'desc' => __('Show banner on Ipods.','adn'),
			'id' => 'dopt_show_ipod',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// Samsung
		$html.= self::checkbox(array(
			'title' => __('Samsung','adn'),
			'desc' => __('Show banner on Samsung devices.','adn'),
			'id' => 'dopt_show_samsung',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// blackberry
		$html.= self::checkbox(array(
			'title' => __('Blackberry','adn'),
			'desc' => __('Show banner on Blackberry devices.','adn'),
			'id' => 'dopt_show_blackberry',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// sony_ericsson
		$html.= self::checkbox(array(
			'title' => __('Sony Ericsson','adn'),
			'desc' => __('Show banner on Sony Ericsson devices.','adn'),
			'id' => 'dopt_show_sony_ericsson',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		// Motorola
		$html.= self::checkbox(array(
			'title' => __('Motorola','adn'),
			'desc' => __('Show banner on Motorola devices.','adn'),
			'id' => 'dopt_show_motorola',
			'value' => 1,
			'chk-on' => __('YES','adn'),
			'chk-off' => __('NO','adn'),
			'chk_width' => 50,
			'chk_height' => 20,
			'chk_btn_width' => 30,
			'size' => 'one_third'
		));
		*/
		
		return $html;
	}




	public static function country_options($args = array(), $info = array())
	{
		$defaults = array(
			'desc' => __('Show or Hide the banner for selected countries.','adn'),
		);
		$info = wp_parse_args($info, $defaults);

		$h = '';
		$show_hide = array_key_exists('show_hide', $args['display_filter']['countries']) ? $args['display_filter']['countries']['show_hide'] : 0;
		$h.= ADNI_Templates::spr_column(array(
			'col' => 'spr_col-6',
			'title' => '',
			'desc' => $info['desc'],
			'content' => ADNI_Templates::switch_btn(array(
				'name' => 'display_filter[countries][show_hide]',
				'checked' => $show_hide,
				'value' => 1,
				'hidden_input' => 1,
				'chk-on' => __('Show','adn'),
				'chk-off' => __('Hide','adn'),
				'chk-high' => 1
			))
		));
		/*$h.= '<div class="spr_column spr_col-6">
			<div class="input_container">';
				
				$show_hide = array_key_exists('show_hide', $args['display_filter']['countries']) ? $args['display_filter']['countries']['show_hide'] : 0;
				$h.= '<label class="switch switch-slide small input_h ttip" title="'.__('Show/Hide.','adn').'">
					<input class="switch-input" type="checkbox" name="display_filter[countries][show_hide]" value="1" '.checked($show_hide,1,false).' />
					<span class="switch-label" data-on="'.__('Show','adn').'" data-off="'.__('Hide','adn').'"></span> 
					<span class="switch-handle"></span>
				</label>';

				$h.= '<span class="description bottom">'.$info['desc'].'</span>
			</div>
		</div>
		<!-- end .spr_column -->';
		*/

		$h.= '<div class="spr_column spr_col-6">
			<div class="input_container">
				<div class="custom_box option_inside_content">
					<h3 class="title"></h3>
					<div class="input_container_inner">';
						
						$h.= '<select name="display_filter[countries][ids][]" data-placeholder="'.__('Select Countries', 'adn').'" style="width:100%;" class="chosen-select" multiple>';
							$h.= '<option value=""></option>';
							
							$country_arr = ADNI_Main::get_countries();
							$countries = array_key_exists('ids', $args['display_filter']['countries']) ? $args['display_filter']['countries']['ids'] : array();
	
							foreach($country_arr as $key => $country)
							{
								$selected = !empty($countries) && is_array($countries) ? in_array($key, $countries) ? 'selected' : '' : '';
								$h.= '<option value="'.$key.'" '.$selected.'>'.$country.' - ('.$key.')</option>';
							}
							
						$h.= '</select>';
						
					$h.= '</div>
				</div>
			</div>
		</div>
		<!-- end .spr_column -->';


		return $h;
	}




	public static function gdpr_approve_modal_tpl($args = array())
	{
		$defaults = array(
			'text' => '',
			'btn_text' => __('I Accept Cookies','adn'),
			'page_url' => ''
		);
		$args = wp_parse_args($args, $defaults);

		$text = empty($args['text']) ? __('We use cookies to offer you a better browsing experience. If you continue to use this site, you consent to our use of cookies.','adn') : $args['text'];
		$btn_text = empty($args['btn_text']) ? __('I Accept Cookies','adn') : $args['btn_text'];

		$h = '';
		$h.= '<div id="_ning_gdpr_approve" class="gdpr_approve">
			<div class="mdl_content">
				<div class="mjs_row is-hidden-tablet-and-below">
					<div class="mjs_column mjs_col-7 mjs_v_align">
						<div class="col_in desc">
							'.$text.'
						</div>
					</div>';

					if( !empty($args['page_url']) )
					{
						$h.= '<div class="mjs_column mjs_col-2 mjs_v_align">
							<div class="col_in">
								<a href="'.$args['page_url'].'" class="cookie_settings">'.__('Privacy Policy','adn').'</a>
							</div>
						</div>';
					}

					$h.= '<div class="mjs_column mjs_col-2 mjs_v_align">
						<div class="col_in">
							<a class="mdl_close_btn my_close_button">
								<svg viewBox="0 0 512 512" style="width:10px;"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
								<span style="margin-left: 5px;">'.$btn_text.'</span>
							</a>
						</div>
					</div>
				</div>
		
				<div class="mjs_row is-hidden-desktop">
					<div class="mjs_column mjs_col-8 mjs_v_align">
						<div class="col_in desc">
							'.$text.'
						</div>
					</div>
					<div class="mjs_column mjs_col-4 mjs_v_align">
						<div class="col_in">
							<a class="mdl_close_btn my_close_button">
								<svg viewBox="0 0 512 512" style="width:10px;"><path fill="currentColor" d="M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z"></path></svg>
								<span style="margin-left: 5px;">'.$btn_text.'</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>';


		/*
		var five_minutes = new Date(new Date().getTime() + 5 * 60 * 1000);
		args.mjs_cookies.set(cookie_name, 1, { expires: five_minutes });
		*/
		$h.= '<script>
		jQuery(document).ready(function($){
			$("#_ning_gdpr_approve").modalJS({
				width:"100%",
				position: ["","bottom"],
				close_btn:{},
				animatedIn:"fadeInUp",
				trigger: {"event": "delay", "target":1},
				disable_window_scroll: 0,
				afterClose:function(args){
					if( typeof args.modal.attr("id") !== "undefined"){
						var cookie_name = "_mjs_"+args.modal.attr("id");
						args.mjs_cookies.set(cookie_name, 1, { expires: 365 });
					}
					location.reload();
				}
			});
		});
		</script>';

		return $h;
	}
	




	public static function weekdays($slug = '')
	{
		$weekdays = array(
			'mon' => __('Monday','adn'),
			'tue' => __('Tuesday','adn'),
			'wed' => __('Wednesday','adn'),
			'thu' => __('Thursday','adn'),
			'fri' => __('Friday','adn'),
			'sat' => __('Saturday','adn'),
			'sun' => __('Sunday','adn'),
		);

		return !empty($slug) ? $weekdays[$slug] : $weekdays;
	}


	public static function months($slug = '')
	{
		if( is_numeric($slug) )
		{
			$m = array('jan','feb','mar','apr','may','jun','jul','aug','sep','okt','nov','dec');
			$slug = $m[($slug-1)];
		}

		$months = array(
			'jan' => __('January','adn'),
			'feb' => __('February','adn'),
			'mar' => __('March','adn'),
			'apr' => __('April','adn'),
			'may' => __('May','adn'),
			'jun' => __('June','adn'),
			'jul' => __('July','adn'),
			'aug' => __('August','adn'),
			'sep' => __('September','adn'),
			'okt' => __('Oktober','adn'),
			'nov' => __('November','adn'),
			'dec' => __('December','adn'),
		);

		return !empty($slug) ? $months[$slug] : $months;
	}

	public static function time($slug = '')
	{
		$time = array(
			'0' => __('Midnight','adn'),
			'1' => __('1 AM (1:00)','adn'),
			'2' => __('2 AM (2:00)','adn'),
			'3' => __('3 AM (3:00)','adn'),
			'4' => __('4 AM (4:00)','adn'),
			'5' => __('5 AM (5:00)','adn'),
			'6' => __('6 AM (6:00)','adn'),
			'7' => __('7 AM (7:00)','adn'),
			'8' => __('8 AM (8:00)','adn'),
			'9' => __('9 AM (9:00)','adn'),
			'10' => __('10 AM (10:00)','adn'),
			'11' => __('11 AM (11:00)','adn'),
			'12' => __('12 PM (12:00)','adn'),
			'13' => __('1 PM (13:00)','adn'),
			'14' => __('2 PM (14:00)','adn'),
			'15' => __('3 PM (15:00)','adn'),
			'16' => __('4 PM (16:00)','adn'),
			'17' => __('5 PM (17:00)','adn'),
			'18' => __('6 PM (18:00)','adn'),
			'19' => __('7 PM (19:00)','adn'),
			'20' => __('8 PM (20:00)','adn'),
			'21' => __('9 PM (21:00)','adn'),
			'22' => __('10 PM (22:00)','adn'),
			'23' => __('11 PM (23:00)','adn')
		);

		return $slug !== '' ? $time[$slug] : $time;
	}


	/**
	 * Martketing dates
	 * 
	 * date_id is for jQuery to detect marketing dates. day-month-year (num-string-num) (26-jan-2019)
	 * For multiple values add _ (day_day-month_month-year_year) (25_26-jan_feb-2019_2020)
	 * 
	 * 23 dates
	 */
	public static function marketing_dates($slug = '')
	{
		$ecom = array(
			'australia-day' => array(
				'name' => 'Australia Day',
				'date' => array('month' => 1, 'day' => 26),
				'date_id' => '26-jan-x'
			),
			'valentine' => array(
				'name' => 'Valentine’s Day',
				'date' => array('month' => 2, 'day' => 14),
				'date_id' => '14-feb-x'
			),
			'president' => array(
				'name' => 'President’s Day',
				'date' => array('month' => 2, 'day' => 19),
				'date_id' => '19-feb-x'
			),
			'world-book-day' => array(
				'name' => 'World Book Day',
				'date' => array('month' => 3, 'day' => 7),
				'date_id' => '7-mar-x'
			),
			'st-patrick' => array(
				'name' => 'St Patrick’s Day',
				'date' => array('month' => 3, 'day' => 17),
				'date_id' => '17-mar-x'
			),
			'april-fools' => array(
				'name' => 'April Fool’s Day',
				'date' => array('month' => 4, 'day' => 1),
				'date_id' => '1-apr-x'
			),
			'health-day' => array(
				'name' => 'World Health Day',
				'date' => array('month' => 4, 'day' => 7),
				'date_id' => '7-apr-x'
			),
			'easter-weekend' => array(
				'name' => 'Easter Weekend',
				'date' => array('month' => 4, 'day' => 19),
				'date_id' => '19_20_21-apr-x'
			),
			'earth-day' => array(
				'name' => 'Earth Day',
				'date' => array('month' => 4, 'day' => 22),
				'date_id' => '22-apr-x'
			),
			'cinco-de-mayo' => array(
				'name' => 'Cinco de Mayo',
				'date' => array('month' => 5, 'day' => 5),
				'date_id' => '5-may-x'
			),
			'Memorial-day' => array(
				'name' => 'Memorial’s Day',
				'date' => array('month' => 5, 'day' => 28),
				'date_id' => '28-may-x'
			),
			'world-environment-day' => array(
				'name' => 'World Environment Day',
				'date' => array('month' => 6, 'day' => 5),
				'date_id' => '5-jun-x'
			),
			'independence-day' => array(
				'name' => 'Independence Day',
				'date' => array('month' => 7, 'day' => 4),
				'date_id' => '4-jul-x'
			),
			'labor-day' => array(
				'name' => 'Labor Day',
				'date' => array('month' => 9, 'day' => 2),
				'date_id' => '2-sep-x'
			),
			'halloween' => array(
				'name' => 'Halloween',
				'date' => array('month' => 10, 'day' => 31),
				'date_id' => '31-okt-x'
			),
			'movember' => array(
				'name' => 'Movember',
				'date' => array('month' => 11, 'day' => 1),
				'date_id' => '1-nov-x'
			),
			'thanksgiving-day' => array(
				'name' => 'Thanksgiving Day',
				'date' => array('month' => 11, 'day' => 28),
				'date_id' => '28-nov-x'
			),
			'black-friday' => array(
				'name' => 'Black Friday',
				'date' => array('month' => 11, 'day' => 29),
				'date_id' => '29-nov-x'
			),
			'cyber-monday' => array(
				'name' => 'Cyber Monday',
				'date' => array('month' => 12, 'day' => 2),
				'date_id' => '2-dec-x'
			),
			'green-monday' => array(
				'name' => 'Green Monday',
				'date' => array('month' => 12, 'day' => 9),
				'date_id' => '9-dec-x'
			),
			'super-saturday' => array(
				'name' => 'Super Saturday',
				'date' => array('month' => 12, 'day' => 21),
				'date_id' => '21-dec-x'
			),
			'christmas' => array(
				'name' => 'Christmas',
				'date' => array('month' => 12, 'day' => 25),
				'date_id' => '25-dec-x'
			),
			'new-years-eve' => array(
				'name' => 'New Year’s Eve',
				'date' => array('month' => 12, 'day' => 31),
				'date_id' => '31-dec-x'
			),
		);
		
		return $slug !== '' ? $ecom[$slug] : $ecom;
	}




	public static function popup_tpl($args = array())
	{
		$defaults = array(
			'id' => 0,
			'bg_image' => ADNI_ASSETS_URL.'/images/ppp-newsletter.jpg',
			'title' => __('Subscribe To Our Newsletter', 'adn'),
			'btn_text' => __('Subscribe','adn'),
			'description' => __('Subscribe to our Newsletter to be the first to know about it.', 'adn'),
			'input' => array(
				'type' => 'email',
				'name' => 'your-email',
				'class' => 'ppp7-validates-as-email',
				'placeholder' => __('Enter Your E-mail...', 'adn')
			),
			'hidden_fields' => array(), // array('name' => '', 'value' => '') 
			'style' => array(
				'font-family' => 'sans-serif',
				'content-height' => '400px',
				'content-padding' => '0 40px',
				'sidebar-bg-color' => '#211612',
				'title-font-color' => '#FFF',
				'subtitle-font-color' => '#999',
				'input-bg-color' => '#FFF',
				'input-font-color' => '#999',
				'btn-bg-color' => '#da3863',
				'btn-font-color' => '#FFF',
				'btn-hover-bg-color' => '#b81842',
				'btn-hover-font-color' => '#FFF',
				'response-font-color' => '#FFF'
			)
		);
		$args = ADNI_Main::parse_args($args, $defaults);

		$h = '';
		$h.= '<style>';
			$h.= ':root {';
				$h.= '--ppp-font-family:'.$args['style']['font-family'].';';
				$h.= '--ppp-content-height:'.$args['style']['content-height'].';';
				$h.= '--ppp-content-padding:'.$args['style']['content-padding'].';';
				$h.= '--ppp-sidebar-bg-color:'.$args['style']['sidebar-bg-color'].';';
				$h.= '--ppp-title-font-color:'.$args['style']['title-font-color'].';';
				$h.= '--ppp-subtitle-font-color:'.$args['style']['subtitle-font-color'].';';
				$h.= '--ppp-input-bg-color:'.$args['style']['input-bg-color'].';';
				$h.= '--ppp-input-font-color:'.$args['style']['input-font-color'].';';
				$h.= '--ppp-btn-bg-color:'.$args['style']['btn-bg-color'].';';
				$h.= '--ppp-btn-font-color:'.$args['style']['btn-font-color'].';';
				$h.= '--ppp-btn-hover-bg-color:'.$args['style']['btn-hover-bg-color'].';';
				$h.= '--ppp-btn-hover-font-color:'.$args['style']['btn-hover-font-color'].';';
				$h.= '--ppp-response-font-color:'.$args['style']['response-font-color'].';';
			$h.= '}';
		$h.= '</style>';
		$h.= '<div class="ppp-subscribe-pup-holder ppp-pup-'.$args['id'].'">';
			$h.= '<div class="ppp-sp-background" style="background-image: url('.$args['bg_image'].')"></div>';
			$h.= '<div class="ppp-sp-content-container">';
				$h.= '<h5 class="ppp-sp-title">'.$args['title'].'</h5>';
				$h.= '<div class="ppp-sp-subtitle">';
					$h.= $args['description'];
				$h.= '</div>';
				$h.= '<div role="form" class="ppp7">';
					$h.= '<div class="screen-reader-response"></div>';
					$h.= '<form action="" method="post" class="ppp7-form ppp-form-'.$args['id'].'" novalidate="novalidate" data-bnr-id="'.$args['id'].'">';
						if(!empty($args['hidden_fields']))
						{
							$h.= '<div style="display: none;">';
								foreach($args['hidden_fields'] as $field)
								{
									$h.= '<input type="hidden" name="'.$field['name'].'" value="'.$field['value'].'">';
								}
							$h.= '</div>';
						}
						$h.= '<div class="info-sidebar no-bg">';
							$h.= '<div class="info-input-holder">';
								$h.= '<span class="ppp7-form-control-wrap your-email">';
									$h.= '<input type="'.$args['input']['type'].'" name="'.$args['input']['name'].'" value="" size="40" class="ppp7-form-control ppp7-text ppp7-email ppp7-validates-as-required '.$args['input']['class'].'" aria-required="true" aria-invalid="false" placeholder="'.$args['input']['placeholder'].'">';
								$h.= '</span>';
								$h.= '<button type="submit" class="ppp-btn-'.$args['id'].' ppp7-form-control ppp7-submit ppp-btn ppp-btn-medium ppp-btn-solid ppp-btn-direction-animation" data-bnr-id="'.$args['id'].'">';
									$h.= '<span class="ppp-btn-text">'.$args['btn_text'].'</span>';
									$h.= '<span class="ppp-btn-background-holder">';
										$h.= '<span class="ppp-btn-background"></span>';
									$h.= '</span>';
								$h.= '</button>';
								$h.= '<span class="ppp-ajax-loader"></span>';
							$h.= '</div>';
						$h.= '</div>';
						$h.= '<div class="ppp7-response-output ppp7-display-none"></div>';
					$h.= '</form>';
				$h.= '</div>';
					
			$h.= '</div>';
		$h.= '</div>';

		return $h;
	}

}

endif;
?>