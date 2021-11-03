<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_AMP' ) ) :

class ADNI_AMP {

    public static $amp_components = array();


    public function __construct() 
	{
        // Actions --------------------------------------------------------
        /*if ( ! is_admin() ) 
        {
			add_action( 'wp', array( __CLASS__, 'wp_load' ), 10 );
		}*/

        // Filters --------------------------------------------------------
        add_filter( 'adning_banner_content', array( __CLASS__, 'amp_banner_content'), 10, 2 );
        add_filter( 'adning_adzone_content', array( __CLASS__, 'amp_adzone_content'), 10, 2 );
    }



    
    /**
	 * Check if page is AMP
	 * Note: always returns false and PHP Notice when called before the parse_query hook.
	 *
	 * return bool (true if AMP URL)
	*/
	public static function is_amp() 
	{
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ) 
			return false;
        
        if( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() || function_exists( 'is_wp_amp' ) && is_wp_amp() || isset( $_GET [ 'wpamp' ] ) )
            return true;
        
        return apply_filters('adning_is_amp', false);
    }



    /**
     * Send data (scripts and CSS) to AMP plugin.
     * Current supported AMP plugins:
     * 
     * - https://nl.wordpress.org/plugins/amp/
     */
    public static function wp_load() 
	{
        if( !self::is_amp() )
            return;

        if( function_exists( 'is_amp_endpoint' ) ) 
        {
            add_action( 'amp_post_template_data', array( __CLASS__, 'add_amp_scripts' ) );
            add_action( 'amp_post_template_css', array( __CLASS__, 'add_amp_css' ) );
        }
    }
    



    public static function amp_banner_content( $content, $b = array() )
    {
        if( !self::is_amp() )
            return $content;
            
        $h = '';

        /**
         * GOOGLE ADSense
         */
        //if (strpos($content, 'adsbygoogle') !== false) {
        if( !empty($b['args']['adsense_settings']))
        {
            if( !empty($b['args']['adsense_settings']['pub_id']))
            {
                self::$amp_components['amp-ad'] = 'https://cdn.ampproject.org/v0/amp-ad-0.1.js';

                $pub_id = $b['args']['adsense_settings']['pub_id'];
                $slot_id = $b['args']['adsense_settings']['slot_id'];
                $type = $b['args']['adsense_settings']['type'];
                $w = $b['args']['size_w'];
                $sh = $b['args']['size_h'];
                $w = $w === 'full' ? '100vw' : $w;
                $fullw = $b['args']['size_w'] === 'full' ? ' data-full-width' : '';

                $h.= '<amp-ad width="'.$w.'" height="'.$sh.'"
                    type="adsense"
                    data-ad-client="ca-'.$pub_id.'"
                    data-ad-slot="'.$slot_id.'"
                    layout="responsive"
                    '.$fullw.'>
                </amp-ad>'; // data-auto-format="rspv" // <div overflow></div>

                $content = $h;
            }
        }

        self::wp_load();

        return $content;
    }


    public static function amp_adzone_content( $content, $b = array() )
    {
        if( !self::is_amp() )
            return $content;
            
        $h = '';

        /*
        self::$amp_components['amp-iframe'] = 'https://cdn.ampproject.org/v0/amp-iframe-0.1.js';
        //$h.= get_bloginfo('url').'?_dnid='.$b['post']->ID;
        $h.= '<amp-iframe width="'.$b['args']['size_w'].'" height="'.$b['args']['size_h'].'"
            sandbox="allow-scripts"
            layout="responsive"
            src="https://localhost/wordpress?_dnid='.$b['post']->ID.'">
            <amp-img placeholder layout="fill"
                src="'.ADNI_ASSETS_URL.'/images/logo.png"></amp-img>
        </amp-iframe>'; // allow-same-origin 
        $content = $h;
        */

        return $content;
    }



    public static function add_amp_scripts( $data )
    {
        if(!empty(self::$amp_components))
        {
            foreach(self::$amp_components as $key => $component)
            {
                // https://nl.wordpress.org/plugins/amp/
                if( function_exists( 'is_amp_endpoint' ) ) 
                {
                    $data['amp_component_scripts'][$key] = $component;
                }
            }
        }

        return apply_filters('adning_add_amp_scripts', $data, self::$amp_components);
    }

    // Return CSS string
    public static function add_amp_css()
    {
        $c = '';
        $c.= '._ning_link {
            width: 100%;
            height: 100%;
            position: absolute;
            z-index: 1001;
            text-decoration: none;
        }';
        // Ad Grid CSS
        $c.= '.justify-content-center {
            -ms-flex-pack: center;
            justify-content: center;
        }';
        $c.= '.mjs_row {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            margin-right: -15px;
            margin-left: -15px;
        }';
        $c.= '._ningzone_grid.mjs_column {
            padding: 15px;
            box-sizing: inherit;
        }';
        echo $c;
        echo do_action('adning_add_amp_css');
    }

}
endif;
?>