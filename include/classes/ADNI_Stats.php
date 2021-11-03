<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Stats' ) ) :

class ADNI_Stats {

    public static $loaded_ads = array();
    public static $loaded_adzones = array();
    
    public function __construct() 
	{
        // Actions --------------------------------------------------------
        add_action( 'wp_footer', array( __CLASS__, 'loaded_ads_ids' ), PHP_INT_MAX );

        // Filters --------------------------------------------------------
        add_filter('adning_loaded_banners', array(__CLASS__, 'loaded_ads'), 10, 2);
        //add_filter('adning_save_stats', array(__CLASS__, 'save_stats'));
        add_action('adning_save_stats', array(__CLASS__, 'save_stats'));
    }



    /**
     * Google Analytics functions
     * https://gist.github.com/chrisblakley/e1f3d79b6cecb463dd8a
     */
    public static function gaParseCookie() 
    {
        if (isset($_COOKIE['_ga'])) 
        {
            list($version, $domainDepth, $cid1, $cid2) = explode('.', $_COOKIE["_ga"], 4);
            $contents = array('version' => $version, 'domainDepth' => $domainDepth, 'cid' => $cid1 . '.' . $cid2);
            $cid = $contents['cid'];
        } 
        else 
        {
            $cid = self::gaGenerateUUID();
        }
        return $cid;
    }
    
    //Generate UUID
    //Special thanks to stumiller.me for this formula.
    public static function gaGenerateUUID() 
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }




    public static function save_stats($args = array())
    {
        $defaults = array(
            'type' => 'impression',
            'banner_id' => 0,
            'adzone_id' => 0,
            'advertiser_id' => 0
        );

        $set = ADNI_Main::settings();
        $url = '';
        $stats_type = $args['type'] === 'click' ? 'Clicks' : 'Impressions';
        $post = ADNI_CPT::load_post( $args['banner_id'], array('filter' => 0) );
        
        if( !empty($set['settings']['ga_tracking_id']) )
        {
            // Temporarrily disabled:
            // http://tunasite.com/frm_topics/google-analytics-problem/
            /*
            $data = array(
                'v' => 1,
                'tid' => $set['settings']['ga_tracking_id'], // Google Analytics Tracking ID.
                'cid' => self::gaParseCookie(),
                't' => 'event',
                'ni' => 1,
                'ec' => 'Adning Advertising', //Category (Required)
                'ea' => '[banner] '.$stats_type, //Action (Required)
                'el' => '['.$args['banner_id'].'] '.$post['post']->post_title //Label,
            );

            $getString = 'https://ssl.google-analytics.com/collect';
            $getString .= '?payload_data&';
            $getString .= http_build_query($data);
            $result = wp_remote_get($getString);
            */
            
            
            /*$ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $getString );
            curl_setopt( $ch, CURLOPT_HEADER, false );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false); //@nczz Fixed HTTPS GET method
            curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
            curl_exec( $ch );
            curl_close( $ch );*/

            /*
            // https://github.com/dancameron/server-side-google-analytics
            require_once( ADNI_CLASSES_DIR.'/ssga.php' );
            $ssga = new ssga( $set['settings']['ga_tracking_id'], get_bloginfo('url') );
            $ssga->set_event( 'Adning-Advertising', '[banner]-'.$stats_type, '['.$args['banner_id'].']-'.$post['post']->post_title, 1 );
            $ssga->send();
            //$url.= 'v=1&tid='.$set['settings']['ga_tracking_id'].'';
            */
        }

        // v=1&tid=UA-4488103-41&cid=942621015.1586369152&t=event&ni=1&ec=Adning+Advertising&ea=%5Bbanner%5D+Clicks&dl=http%3A%2F%2Fadning.com%2Fwhat-is-a-banner-ad%2F&dp=%2Fwhat-is-a-banner-ad%2F&el=%5B91%5D+imgMCE
    }

    // end Google Analytics functions




    /**
     * Collect all loaded ad ids on page with active statistics.
     */
    public static function loaded_ads($b, $args = array())
    {
        self::$loaded_ads[] = array($b['post']->ID => array( 'name' => $b['post']->post_title ));
        if(!empty($args['in_adzone']))
        {
            self::$loaded_adzones[$args['in_adzone']['post']->ID] = $args['in_adzone']['post']->post_title;
        }
        
        return count(self::$loaded_ads)-1;
    }



    /**
     * Output loaded ad ids javascript variable
     */
    public static function loaded_ads_ids()
    {
        $set_arr = ADNI_Main::settings();
        $settings = $set_arr['settings'];

        $h = '';
        if( !empty($settings['ga_tracking_id']))
        {
            $h.= '<script type="text/javascript">';
                $h.= 'var ang_tracker = "'.$settings['ga_tracking_id'].'";';
                $h.= 'var loaded_ang = '.json_encode(self::$loaded_ads).';';
                $h.= 'var loaded_angzones = '.json_encode(self::$loaded_adzones).';';
            $h.= '</script>';
        }

        echo $h;
    }


}
endif;
?>
