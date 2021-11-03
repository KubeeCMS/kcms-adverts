<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Updates' ) ) :

class ADNI_Updates {


    /**
     * Check if the plugin needs to run the updater.
     */
    public static function needs_update()
    {
        $set = ADNI_Main::settings();
        $settings = $set['settings'];
        
        if( $settings['plugin_version'] !== ADNI_VERSION)
        {
            return self::run_update($settings);
        }
        
        return false;
    }



    public static function run_update($settings = array())
    {
        $set = ADNI_Main::settings();
        
        if( empty($settings))
        {
            $settings = $set['settings'];
        }

        $last_update = ADNI_Multi::get_option( '_adning_latest_update', 1 );

        if( ADNI_VERSION >= '1.2.3' && $last_update < '1.2.3' )
        {
            
        }
        if( ADNI_VERSION >= '1.1.7' && $last_update < '1.1.7' )
        {
            $auto_pos = ADNI_Main::auto_positioning();
            if(!empty($auto_pos))
            {
                $is_old = 0;
                $new_pos = array();
                foreach($auto_pos as $key => $pos)
                {
                    // Only run if _adning_auto_positioning is still the old one.
                    if( array_key_exists('pos', $pos))
                    {
                        $is_old = 1;
                        $new_pos[$key][$pos['pos']] = array();
                        if( array_key_exists('custom', $pos))
                        {
                            foreach($pos['custom'] as $c => $val)
                            {
                                $split = explode('_', $c,2);
                                $new_pos[$key][$pos['pos']][$split[1]] = $val;
                            }
                        }
                    }
                }

                if($is_old)
                {
                    ADNI_Multi::update_option('_adning_auto_positioning', $new_pos);
                }
            }
        }


        // Updates for v1.0.8
        if( ADNI_VERSION >= '1.0.8' && $last_update < '1.0.8')
        {
            ADNI_CPT::add_custom_caps(array('role' => $set['roles']['create_campaign_role'], 'cpt' => ADNI_CPT::$campaign_cpt));
        }


        // Updates for v1.0.7
        if( ADNI_VERSION >= '1.0.7' && $last_update < '1.0.7')
        {
            $args = array(
                'post_type' => array(ADNI_CPT::$banner_cpt, ADNI_CPT::$adzone_cpt)
            );
            $posts_array = get_posts( $args );

            if(!empty($posts_array))
            {
                foreach($posts_array as $key => $post)
                {
                    $p_args = ADNI_multi::get_post_meta($post->ID, '_adning_args', array());
                    $type = $p_args['type'];
                    $p_args['size'] = $p_args[$type.'_size'];
                    $p_args['size_w'] = $p_args[$type.'_size_w'];
                    $p_args['size_h'] = $p_args[$type.'_size_h'];
                    $p_args['responsive'] = $p_args[$type.'_responsive'];
                    $p_args = ADNI_multi::update_post_meta($post->ID, '_adning_args', $p_args);
                    //echo '<pre>'.print_r($p_args, true).'</pre>';
                }
            }
            //echo '<pre>'.print_r($posts_array, true).'</pre>';
        }

        

        // Update plugin version
        $settings['plugin_version'] = ADNI_VERSION;
        $settings = ADNI_Multi::update_option('_adning_settings', $settings);


        // Check license
        $activation = ADNI_Multi::get_option('adning_activation', array());
        if( !empty($activation))
        {
            $resp = ADNI_Activate::check(array(
                'license-key' => $activation['license-key']
            ));
        }
        
        //echo '<pre>'.print_r($resp, true).'</pre>';
        // Last update
        ADNI_Multi::update_option( '_adning_latest_update', ADNI_VERSION );

        return ADNI_VERSION;
    }
}
endif;
?>