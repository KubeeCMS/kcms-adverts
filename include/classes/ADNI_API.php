<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_API' ) ) :

class ADNI_API {
	
	public function __construct() 
	{
		// API Iframe url ---------------------------------------------------
		add_action( 'wp', array( __CLASS__, 'iframe_url' ), 4);		
		add_action( 'wp', array( __CLASS__, 'iframe_embed' ), 4);		
	}
	
	
	public static function iframe_url()
	{	
		// API Iframe URL
		if( isset( $_GET['_dnid'] ) && !empty( $_GET['_dnid'] ) )
		{
			if ( class_exists( 'sTrack_Core' ) )
			{
				sTrack_Core::_tracking();
			}

			$html = '';
			$custom_css = '';
			$post_type = get_post_type( $_GET['_dnid'] );
			$type = strtolower($post_type) === strtolower(ADNI_CPT::$banner_cpt) ? 'banner' : 'adzone';
			
			$html.= '<!doctype html>';
			$html.= '<html>';
				$html.= '<head>';
					$html.= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
					
					$html.= '<script type="text/javascript" src="'.get_site_url().'/wp-includes/js/jquery/jquery.js"></script>';
					$html.= '<script type="text/javascript" src="'.ADNI_ASSETS_URL . '/dev/js/advertising.js"></script>';
					$html.= '<script type="text/javascript">
					/* <![CDATA[ */
					var _adn_ = {"ajaxurl":"'.ADNI_AJAXURL.'"};
					/* ]]> */
					</script>';
					$html.= '<script type="text/javascript" src="'.ADNI_ASSETS_URL. '/dist/angwp.bundle.js"></script>';
					$html.= '<script type="text/javascript" src="'.ADNI_ASSETS_URL. '/js/embed/iframeResizer.contentWindow.min.js"></script>';
					$html.= '<link rel="stylesheet" href="'.ADNI_ASSETS_URL. '/dist/angwp.bundle.js.css" media="all" />';
					
					// IMGMCE
					if( class_exists('ADN_main') )
					{
						$html.= '<script type="text/javascript">
						/* <![CDATA[ */
						var _dn_ = {"debug":"'.IMC_DEBUG.'","ajaxurl":"'.IMC_AJAXURL.'","siteurl":"'.IMC_SITEURL.'","assets_url":"'.IMC_ASSETS_URL.'","assets_dir":"'.IMC_ASSETS_DIR.'","inc_url":"'.IMC_INC_URL.'","inc_dir":"'.IMC_INC_DIR.'","inc_dir_base":"'.ADN_Main::base_url(IMC_INC_DIR).'","upload_folder":"'.IMC_UPLOAD_FOLDER.'","upload_path":"'.IMC_UPLOAD_DIR.'","upload_src":"'.IMC_UPLOAD_SRC.'"};
						/* ]]> */
						</script>';
						$html.= '<script type="text/javascript" src="'.IMC_ASSETS_URL.'dist/_imc.bundle.js"></script>';
						//$html.= '<script type="text/javascript" src="'.IMC_ASSETS_URL.'js/'.ADN_main::file_loc(array('name' => 'imgmce_image')).'.js"></script>';
					}
					
					$html.= '<style type="text/css">body{ margin:0; padding:0; '.$custom_css.' }</style>';
					$html.= '<title>ADNING - Revolutionary Ad Manager for Wordpress</title>';
				$html.= '</head>';
				$html.= '<body>';
					$html.= ADNI_Multi::do_shortcode('[ADNI_'.$type.' id="'.$_GET['_dnid'].'" filter="0"]');
					$html.= apply_filters( 'adning_api_footer', '', false );
					$html.= apply_filters( 'imgmce_api_footer', '', false );
				$html.= '</body>';
			$html.= '</html>';
			
			echo $html;
			exit;
		}
	}
	
	
	
	/**
	 * EMBED BANNERS
	 *
	 * Usage:
	 *
	 * <script type="text/javascript">
	   		var _ning_embed = {"id":"46","width":728,"height":90};
       </script>
       <script type="text/javascript" src="http://adning.com?_dnembed=true"></script>
	 *
	*/
	public static function iframe_embed()
	{	
		if( isset( $_GET['_dnembed'] ) && !empty( $_GET['_dnembed'] ) )
		{
			if ( class_exists( 'sTrack_Core' ) )
			{
				sTrack_Core::_tracking();
			}
			
			header("Content-Type: application/javascript");
			
			/*
			 * $foo = "http://www.example.com/foo/bar?hat=bowler&accessory=cane";
			 * parse_url($foo) returns:
			 *Array
				(
					[scheme] => http
					[host] => www.example.com
					[path] => /foo/bar
					[query] => hat=bowler&accessory=cane
				)
			*/
			$site_url = parse_url(get_site_url());
			$inc_url = parse_url(ADNI_INC_URL);
			$assets_url = parse_url(ADNI_ASSETS_URL);
			
			$html = '';
			
			// Minified. - https://closure-compiler.appspot.com
			
			
			/**
			 * RAW SCRIPT
			 *
			*/
			
			$html.= 'if (typeof _ning_embed !== "undefined") {';
				//$html.= 'console.log(_ning_embed);';

				$html.= 'if( typeof _ning_objects === "undefined"){';
					$html.= 'var _ning_objects = {};';
				$html.= '}';

				$html.= '_ning_objects[_ning_embed.id] = {"id": _ning_embed.id};';
				$html.= 'console.log(_ning_objects);';
				
				// Main Variables
				$html.= 'var site_url = "'.get_site_url().'";';
				$html.= 'var inc_path = "'.$inc_url['path'].'";';
				$html.= 'var assets_path = "'.$assets_url['path'].'";';
				$html.= 'var assets_url = "'.ADNI_ASSETS_URL.'";';
				$html.= 'var opacity = "undefined"!==typeof _ning_embed.animation ? " opacity:0;" : "";';
				$html.= 'var bWidth = _ning_embed.width != "full" ? _ning_embed.width+"px" : "100%";';
				//$html.= 'var maxWidth = "max-width:"+_ning_embed.width+"px;";';
				$html.= 'var maxWidth = "max-width:"+bWidth+";";';
				
				// Load iframeSizer
				$html.= 'var firstScript = document.getElementsByTagName("script")[0],';
      			$html.= 'js = document.createElement("script");';
  				$html.= 'js.src = assets_url+"/js/embed/_dnEmbedSizer.min.js";';
  				$html.= 'js.onload = function(){';
    				// do stuff with your dynamically loaded script
    				$html.= 'iFrameResize({log:false});';
  				$html.= '};';
  				$html.= 'firstScript.parentNode.insertBefore(js, firstScript);';
				
				// Create iframe
				// Time str to prevent caching
				$time_str = '&t='.current_time('timestamp');
				$html.= 'document.write("<div class=\'_ning_holder _ning_holder_"+_ning_embed.id+"\' style=\'width:100%;max-width:"+_ning_embed.width+"px;max-height:"+_ning_embed.height+"px;\'><iframe id=\'_dn"+_ning_embed.id+"\' src=\'"+site_url+"?_dnid="+_ning_embed.id+"'.$time_str.'\' width=\'100%\' height=\'"+_ning_embed.height+"px\' frameborder=\'0\' allowtransparency=\'true\' scrolling=\'no\' style=\'"+maxWidth+opacity+"\' allowfullscreen></iframe></div>");';
				
				// Talk to iframe content - https://gist.github.com/pbojinov/8965299
				$html.= 'function bindEvent(element, eventName, eventHandler) {
					if (element.addEventListener){
						element.addEventListener(eventName, eventHandler, false);
					} else if (element.attachEvent) {
						element.attachEvent(\'on\' + eventName, eventHandler);
					}
				}';
				
				/*// Listen to message from child window
				bindEvent(window, \'message\', function (e) {
					//console.log(\'parent received message from child: \', e.data);
				});*/

				$html.= 'jQuery.each( _ning_objects, function( key, value ) {
					var iframeEl = document.getElementById(\'_dn\'+key);
				
					// Send a message to the child iframe
					var sendMessage = function(msg) {
						iframeEl.contentWindow.postMessage(msg, \'*\');
					};

					iframeEl.onload=function(){
						sendMessage({\'id\': \'_dn\'+key});
					};
			  	});';
				// End talk to iframe content

			$html.= '}else{';
				$html.= 'console.log("adning ERROR: wrong embed data.");';
			$html.= '}';
			
			
			
			
			/**
			 * OPTIONAL CSS and JS For animation and viewport detection
			*/
			// Viewport detection script
			$viewport_function = '(function(c,h){c.fn.ningInViewport=function(k,a){var e=c.extend({padding:0},a);return this.each(function(a,f){function g(){var d=c(this).height(),b=f.getBoundingClientRect(),a=b.top+e.padding;b=b.bottom+e.padding;return k.call(f,Math.max(0,0<a?d-a:b<d?b:d))}g();c(h).on("resize scroll",g)})}})(jQuery,window);';
			// Fire when banner enters viewport
			$banner_enters_viewport = 'jQuery("#_dn"+_ning_embed.id).ningInViewport(function(px){ if(px) jQuery(this).css({"opacity":1}).addClass(_ning_embed.animation+" animated"); }, {padding:200});';
			
			
			$html.= 'if("undefined"!==typeof _ning_embed.animation){';
			
				// load animation css style (if not loaded already)
				$html.= 'var ss = document.styleSheets;var cssLoaded=0;';
				$html.= 'for (var i = 0, max = ss.length; i < max; i++) {';
					$html.= 'if (ss[i].href == site_url+inc_path+"/extensions/spr_columns/assets/css/animate.min.css")';
						$html.= 'cssLoaded = 1;';
				$html.= '}';
				$html.= 'if(!cssLoaded){';
					//$html.= 'console.log("not loaded");';
					$html.= 'document.write("<link rel=\'stylesheet\' href=\'"+site_url+inc_path+"/extensions/spr_columns/assets/css/animate.min.css\' type=\'text/css\' media=\'all\' />");';
				$html.= '}';
				
				
				// Check if jQuery has been loaded.
				$html.= 'if(!window.jQuery){';
				   $html.= 'var script = document.createElement("script");';
				   $html.= 'script.type = "text/javascript";';
				   $html.= 'script.src = site_url+assets_path+"/js/embed/jquery.js";';
				   $html.= 'document.getElementsByTagName("head")[0].appendChild(script);';
				   $html.= 'script.onload = function(){';
    					// do stuff with your dynamically loaded jQuery script
						$html.= $viewport_function;
						$html.= $banner_enters_viewport;
  					$html.= '};';
				$html.= '}else{';
					// Jquery has already been loaded on the page.
					$html.= $viewport_function;
					$html.= $banner_enters_viewport;
				
				$html.= '}';
			$html.= '}';
			
			
			echo $html;
			exit;
		}
	}
	
}
endif;
?>