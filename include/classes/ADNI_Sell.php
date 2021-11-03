<?php
// Exit if accessed directly
if ( ! defined( "ABSPATH" ) ) exit;
if ( ! class_exists( 'ADNI_Sell' ) ) :

class ADNI_Sell {


    public function __construct() 
	{
        // Actions --------------------------------------------------------
        add_action( 'admin_menu', array(__CLASS__, 'register_admin_menu'), 20);
        add_action( 'adning_single_adzone_settings', array( __CLASS__, 'adzone_sell_box' ), 10, 1 );
        add_action( 'parse_request', array(__CLASS__, 'handle_api_requests'), 0);
        add_action( 'before_delete_post', array(__CLASS__, 'delete_post'));
        add_action( 'ADNI_install', array(__CLASS__, 'create_tables'));

        // Filters --------------------------------------------------------
        add_filter( 'submenu_file', array( __CLASS__,'admin_submenu_filter') );
        add_filter( 'ADNI_admin_pages', array( __CLASS__,'admin_pages_filter') );
        add_filter( 'ADNI_main_settings', array( __CLASS__,'sell_main_settings') );
        add_filter( 'ADNI_default_adzone_args', array(__CLASS__,'adzone_set'), 10, 1 );
        add_filter( 'ADNI_save_post', array(__CLASS__,'save_post'), 10, 1 );
        add_filter( 'ADNI_save_banner', array( __CLASS__, 'add_banner_to_adzone' ), 10, 1 );
        //add_filter( 'ADNI_single_banner_args', array( __CLASS__, 'single_banner_args' ), 10, 3 );
        add_filter( 'ADNI_new_banner_args', array( __CLASS__, 'new_banner_args' ) );
        add_filter( 'ADNI_settings_tabs', array( __CLASS__, 'sell_settings_tab' ), 10 );
        add_filter( 'ADNI_load_post', array( __CLASS__, 'sell_load_post' ), 10, 2 );
        add_filter( 'adning_single_banner_notice', array(__CLASS__, 'approve_banner_box'), 10, 2);
        add_filter( 'ADNI_noti_balloon', array(__CLASS__, 'count_order_notifications'), 10, 1);

        // Shortcodes -----------------------------------------------------
        add_shortcode('adning_available_adzones', array(__CLASS__, 'sc_available_adzones'));
        add_shortcode('adning_user_dashboard', array(__CLASS__, 'sc_user_dashboard'));
        add_shortcode('adning_edit_banner', array(__CLASS__, 'sc_edit_banner'));


        // AJAX --------------------------------------------------------
        $_sell_ajax_actions = array(
            'send_payment',
            'paypal' 
		);
		foreach($_sell_ajax_actions as $ajax_action)
		{
			add_action( 'wp_ajax_' . $ajax_action, array(__CLASS__, str_replace( '-', '_', $ajax_action )));
			add_action( 'wp_ajax_nopriv_' . $ajax_action, array(__CLASS__, str_replace( '-', '_', $ajax_action )));
		}
    }



    /**
	 * Remove - Delete banner
	 */
	public static function delete_post( $post_id )
	{
		$post_type = get_post_type( $post_id );
		if($post_type === strtolower(ADNI_CPT::$banner_cpt))
		{
			$post = ADNI_CPT::load_post($post_id, array('post_type' => $post_type, 'filter' => 0));
			if(!empty($post['args']['sell']))
			{
                global $wpdb;
			    $wpdb->query( "DELETE FROM " . $wpdb->prefix . "adning_sell WHERE adzone_id = ".$post['args']['sell']['adzone_id']." AND banner_id = ".$post_id.";" );
			}
		}
	}


    /**
     * Add sell page to plugin Admin pages
     */
    public static function admin_pages_filter($pages)
    {
        $pages[] = 'adning-sell';
        return $pages;
    }


    /**
     * Register admin page for sell settings
     * will not be visible in the menu.
     */
    public static function register_admin_menu()
    {
        add_submenu_page(
            'adning'
            , __( 'Sell', 'adn' )
            , ''
            , ADNI_ADMIN_ROLE
            , 'adning-sell'
            , array( __CLASS__, "adning_sell_template")
        );
    }
    public static function adning_sell_template()
	{
		include( ADNI_TPL_DIR .'/sell_settings.php');
    }
    // Filter to remove the adning-sell page from the admin menu.
    // Instead we highlite the General Settings tab.
    public static function admin_submenu_filter( $submenu_file )
	{
		global $plugin_page, $parent_file;

		$hidden_submenus = array(
			'adning-sell' => true,
		);

		// Hide the submenu.
		foreach ( $hidden_submenus as $submenu => $unused ) {
			remove_submenu_page( 'adning', $submenu );
		}

		// Select another submenu item to highlight (optional).
        if ( $plugin_page && isset( $hidden_submenus[ $plugin_page ] ) ) 
        {
			$submenu_file = 'adning-settings';
			$parent_file  = 'adning';
		}
		
		return $submenu_file;
	}



    /**
     * Add Approve banner box to top of single banner settings page
     */
    public static function approve_banner_box($args = array(), $is_frontend = 0)
    {
        $h = '';
        //echo '<pre>'.print_r($args,true).'</pre>';
        if( current_user_can(ADNI_BANNERS_ROLE) && !$is_frontend && !empty($args['args']['sell']) ) // && $args['args']['status'] === 'review'
        {
            $order = self::load_order(array('query' => "WHERE id = '".$args['args']['sell']['order_id']."' LIMIT 1"));
            //echo '<pre>'.print_r($order,true).'</pre>';
            if(!empty($order) && $order[0]->status === 'draft')
            {   
                $sell_settings = self::sell_main_settings();
                $sell_settings = $sell_settings['sell'];
                $user_info = get_userdata($order[0]->user_id);
                $payment_status = $order[0]->am_paid >= $order[0]->price ? __('Completed','adn') : __('Awaiting Paiment','adn');
                $h.= '<div class="approve_banner_box">';
                    $h.= '<h3>'.__('Banner needs review.','adn').'</h3>';
                    $h.= '<ul>';
                        $h.= '<li><strong>'.__('Advertiser','adn').'</strong>: '.$user_info->display_name.' (#'.$order[0]->user_id.')</li>';
                        $h.= '<li><strong>'.__('Linked Adzone','adn').'</strong>: '.get_the_title($order[0]->adzone_id).' <small>(#'.$order[0]->adzone_id.')</small></li>';
                        $h.= '<li><strong>'.__('Order ID','adn').'</strong>: #'.$order[0]->id.'</li>';
                        $h.= '<li><strong>'.__('Payment Status','adn').'</strong>: '.$payment_status.' <small>('.$order[0]->am_paid.' '.$sell_settings['cur'].')</small></li>';
                    $h.= '</ul>';
                    $h.= '<div><a class="button-secondary" style="background-color:#dffc6f;" href="'.esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning&view=banner&id='.$args['post']->ID.'&approve_banner='.$order[0]->id))).'">'.__('Approve','adn').'</a></div>';
                $h.= '</div>';
            }
        }

        return $h;
    }

    


    

    public static function new_banner_args($post = array())
    {
        $aid = isset($_GET['slid']) ? $_GET['slid'] : 0;
        $oid = isset($_GET['oid']) ? $_GET['oid'] : 0;

        if( !empty($aid) && !empty($oid) )
        {
            $adzone_args = ADNI_multi::get_post_meta($aid, '_adning_args', array());
            $post['size'] = $adzone_args['size'];
            $post['size_w'] = $adzone_args['size_w'];
            $post['size_h'] = $adzone_args['size_h'];
            $post['responsive'] = 1;
            $post['status'] = 'review';
            $post['banner_link_masking'] = 1;
            $post['enable_stats'] = 1;
            $post['banner_content'] = '';
            $post['sell'] = array(
                'order_id' => $oid,
                'adzone_id' => $aid,
                'contract' => $adzone_args['sell']['contract'],
                'contract_duration' => $adzone_args['sell']['contract_duration']
            );
            $post = ADNI_Main::parse_args($post, ADNI_CPT::default_banner_args(array('empty_checkbox_values' => 1)));
           
            if( !empty($post['sell']))
            {
                $order = self::load_order(array('query' => "WHERE id = '".$post['sell']['order_id']."' LIMIT 1"));
                //echo '<pre>'.print_r($order,true).'</pre>';
                if( $order[0]->status === 'active' )
                {
                    $post['status'] = 'active';
                }
            }
        }

        return $post;
    }



    /**
     * Add Sell tab to settings page
     */
    public static function sell_settings_tab($tabs)
    {
        $pending_order_count = self::pending_order_count();
        $info_balloon = !empty($pending_order_count) ? ' <span class="info_balloon ttip" title="'.sprintf(__('You have %s orders pending for review.'), $pending_order_count).'">'.$pending_order_count.'</span>' : '';
        $tabs['sell'] = array('text' => __('Sell','adn').$info_balloon, 'page' => 'adning-sell', 'data-tab' => 'about-sell');

        return $tabs;
    }



    /** 
	 * SELL MAIN DATA
	 *
	 */
	public static function default_sell_settings($settings = array())
	{	
		return array(
            'cur' => 'USD',
            'payment' => self::sell_payment_options($settings),
            'urls' => array(
                'available_adzones' => trailingslashit( home_url( 'index.php' ) ).'?_ning_front=1&view=available_adzones',
                'user_dashboard' => trailingslashit( home_url( 'index.php' ) ).'?_ning_front=1&view=user_dashboard',
                'edit_banner' => trailingslashit( home_url( 'index.php' ) ).'?_ning_front=1&view=banner',
            ),
            'template' => array(
                'logo_title' => 'Adning',
                'side_title' => __('Frontend AD Manager','adn'),
                'footer_info' => __('Adning, Lightning fast Advertising - Modern "All In One" Wordpress advertising plugin.','adn'),
                'footer_copy' => 'Adning',
                'footer_copy_url' => 'http://adning.com'
            )
		);
    }


    /** 
	 * SELL PAYMENT OPTIONS
     * values only. Form fields get created using the function self::sell_payment_option_forms()
     * 
	 *
	 */
	public static function sell_payment_options($settings = array())
	{	
		return apply_filters('ADNI_sell_payment_options_settings',array(
            'bank-transfer' => array(
                'desc' => '',
                'active' => 1
            ),
            'paypal' => array(
                'active' => 0,
                'email' => '',
                'debug' => 0,
                'sandbox' => 0
            )
        ), $settings);
    }


    /** 
	 * SELL PAYMENT OPTIONS FORM TEMPLATES
	 *
	 */
	public static function sell_payment_option_forms($settings = array(), $key = '')
	{
        // Only send sell settings after this point
        $settings = array_key_exists('sell', $settings) ? $settings['sell'] : $settings;

		$options = apply_filters('ADNI_sell_payment_option_form_settings',array(
            'bank-transfer' => array(
                'title' => __('Bank Transfer','adn'),
                'logo' => '<svg viewBox="0 0 640 512"><path fill="currentColor" d="M608 32H32C14.33 32 0 46.33 0 64v384c0 17.67 14.33 32 32 32h576c17.67 0 32-14.33 32-32V64c0-17.67-14.33-32-32-32zM176 327.88V344c0 4.42-3.58 8-8 8h-16c-4.42 0-8-3.58-8-8v-16.29c-11.29-.58-22.27-4.52-31.37-11.35-3.9-2.93-4.1-8.77-.57-12.14l11.75-11.21c2.77-2.64 6.89-2.76 10.13-.73 3.87 2.42 8.26 3.72 12.82 3.72h28.11c6.5 0 11.8-5.92 11.8-13.19 0-5.95-3.61-11.19-8.77-12.73l-45-13.5c-18.59-5.58-31.58-23.42-31.58-43.39 0-24.52 19.05-44.44 42.67-45.07V152c0-4.42 3.58-8 8-8h16c4.42 0 8 3.58 8 8v16.29c11.29.58 22.27 4.51 31.37 11.35 3.9 2.93 4.1 8.77.57 12.14l-11.75 11.21c-2.77 2.64-6.89 2.76-10.13.73-3.87-2.43-8.26-3.72-12.82-3.72h-28.11c-6.5 0-11.8 5.92-11.8 13.19 0 5.95 3.61 11.19 8.77 12.73l45 13.5c18.59 5.58 31.58 23.42 31.58 43.39 0 24.53-19.05 44.44-42.67 45.07zM416 312c0 4.42-3.58 8-8 8H296c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h112c4.42 0 8 3.58 8 8v16zm160 0c0 4.42-3.58 8-8 8h-80c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h80c4.42 0 8 3.58 8 8v16zm0-96c0 4.42-3.58 8-8 8H296c-4.42 0-8-3.58-8-8v-16c0-4.42 3.58-8 8-8h272c4.42 0 8 3.58 8 8v16z"></path></svg>',
                'form' => array(
                    'desc' => array(
                        'html' => ADNI_Templates::spr_column(array(
                            'col' => 'spr_col-8',
                            'title' => __('Description','adn'),
                            'desc' => sprintf(__('%s information.','adn'), __('Bank Transfer','adn')),
                            'content' => ADNI_Templates::textarea_cont(array(
                                    'type' => 'text',
                                    'width' => '100%',
                                    'name' => 'payment[bank-transfer][desc]',
                                    'value' => $settings['payment']['bank-transfer']['desc'],
                                    'placeholder' => '',
                                ))
                        ))
                    )
                )
            ),
            'paypal' => array(
                'title' => __('Paypal','adn'),
                'logo' => '<svg viewBox="0 0 576 512"><path fill="currentColor" d="M186.3 258.2c0 12.2-9.7 21.5-22 21.5-9.2 0-16-5.2-16-15 0-12.2 9.5-22 21.7-22 9.3 0 16.3 5.7 16.3 15.5zM80.5 209.7h-4.7c-1.5 0-3 1-3.2 2.7l-4.3 26.7 8.2-.3c11 0 19.5-1.5 21.5-14.2 2.3-13.4-6.2-14.9-17.5-14.9zm284 0H360c-1.8 0-3 1-3.2 2.7l-4.2 26.7 8-.3c13 0 22-3 22-18-.1-10.6-9.6-11.1-18.1-11.1zM576 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48zM128.3 215.4c0-21-16.2-28-34.7-28h-40c-2.5 0-5 2-5.2 4.7L32 294.2c-.3 2 1.2 4 3.2 4h19c2.7 0 5.2-2.9 5.5-5.7l4.5-26.6c1-7.2 13.2-4.7 18-4.7 28.6 0 46.1-17 46.1-45.8zm84.2 8.8h-19c-3.8 0-4 5.5-4.2 8.2-5.8-8.5-14.2-10-23.7-10-24.5 0-43.2 21.5-43.2 45.2 0 19.5 12.2 32.2 31.7 32.2 9 0 20.2-4.9 26.5-11.9-.5 1.5-1 4.7-1 6.2 0 2.3 1 4 3.2 4H200c2.7 0 5-2.9 5.5-5.7l10.2-64.3c.3-1.9-1.2-3.9-3.2-3.9zm40.5 97.9l63.7-92.6c.5-.5.5-1 .5-1.7 0-1.7-1.5-3.5-3.2-3.5h-19.2c-1.7 0-3.5 1-4.5 2.5l-26.5 39-11-37.5c-.8-2.2-3-4-5.5-4h-18.7c-1.7 0-3.2 1.8-3.2 3.5 0 1.2 19.5 56.8 21.2 62.1-2.7 3.8-20.5 28.6-20.5 31.6 0 1.8 1.5 3.2 3.2 3.2h19.2c1.8-.1 3.5-1.1 4.5-2.6zm159.3-106.7c0-21-16.2-28-34.7-28h-39.7c-2.7 0-5.2 2-5.5 4.7l-16.2 102c-.2 2 1.3 4 3.2 4h20.5c2 0 3.5-1.5 4-3.2l4.5-29c1-7.2 13.2-4.7 18-4.7 28.4 0 45.9-17 45.9-45.8zm84.2 8.8h-19c-3.8 0-4 5.5-4.3 8.2-5.5-8.5-14-10-23.7-10-24.5 0-43.2 21.5-43.2 45.2 0 19.5 12.2 32.2 31.7 32.2 9.3 0 20.5-4.9 26.5-11.9-.3 1.5-1 4.7-1 6.2 0 2.3 1 4 3.2 4H484c2.7 0 5-2.9 5.5-5.7l10.2-64.3c.3-1.9-1.2-3.9-3.2-3.9zm47.5-33.3c0-2-1.5-3.5-3.2-3.5h-18.5c-1.5 0-3 1.2-3.2 2.7l-16.2 104-.3.5c0 1.8 1.5 3.5 3.5 3.5h16.5c2.5 0 5-2.9 5.2-5.7L544 191.2v-.3zm-90 51.8c-12.2 0-21.7 9.7-21.7 22 0 9.7 7 15 16.2 15 12 0 21.7-9.2 21.7-21.5.1-9.8-6.9-15.5-16.2-15.5z" class=""></path></svg>',
                'form' => array(
                    'email' => array(
                        'html' => ADNI_Templates::spr_column(array(
                            'col' => 'spr_col-6',
                            'title' => __('Email adress','adn'),
                            'desc' => sprintf(__('%s email adress to receive payments.','adn'), __('Paypal','adn')),
                            'content' => ADNI_Templates::inpt_cont(array(
                                'type' => 'text',
                                'width' => '100%',
                                'name' => 'payment[paypal][email]',
                                'value' => $settings['payment']['paypal']['email'],
                                'placeholder' => '',
                                'icon' => 'at',
                                'show_icon' => 1
                            ))
                        ))
                    ),
                    'sandbox' => array(
                        'html' => ADNI_Templates::spr_column(array(
                            'col' => 'spr_col-2',
                            'title' => __('Sandbox','adn'),
                            'desc' => sprintf(__('Run %s in sandbox mode (for testing).'), __('Paypal','adn')),
                            'content' => ADNI_Templates::switch_btn(array(
                                'name' => 'payment[paypal][sandbox]',
                                'checked' => $settings['payment']['paypal']['sandbox'],
                                'value' => 1,
                                'hidden_input' => 1,
                                'chk-on' => __('Yes','adn'),
                                'chk-off' => __('No','adn')
                            ))
                        ))
                    ),
                    'debug' => array(
                        'html' => ADNI_Templates::spr_column(array(
                            'col' => 'spr_col-2',
                            'title' => __('Debug','adn'),
                            'desc' => sprintf(__('Enable %s debug mode (for testing).'), __('Paypal','adn')),
                            'content' => ADNI_Templates::switch_btn(array(
                                'name' => 'payment[paypal][debug]',
                                'checked' => $settings['payment']['paypal']['debug'],
                                'value' => 1,
                                'hidden_input' => 1,
                                'chk-on' => __('Yes','adn'),
                                'chk-off' => __('No','adn')
                            ))
                        ))
                    )
                )
            )
        ), $settings, $key);

        return !empty($key) ? $options[$key] : $options;
    }
    

    /** 
     * Return Sell main settings
	 * Update the main settings ( ADNI_Main::settings() )
	 *
	 */
    public static function sell_main_settings($settings = array())
    {
        if( empty($settings))
        {
            $set_arr = ADNI_Main::settings();
            $settings = $set_arr['settings'];
        }
        return ADNI_Main::parse_args( $settings, array('sell' => self::default_sell_settings($settings)) );
    }
	


    /*
	 * API Requeste - Paypal, Stripe
	 *
	 * @access public
	 * @return null
	*/
    public static function handle_api_requests()
    {
        // Receive Paypal data
		if ( isset( $_GET['_ning-pp-ipn'] ) && !empty( $_GET['_ning-pp-ipn'] )) 
		{
            add_filter('strack_track_page_view', 0);
            $adning_paypal_ipn = new ADNI_Paypal_IPN();
        
			if($adning_paypal_ipn->check_ipn_request())
			{
				$ipn_response = $adning_paypal_ipn->successful_request($IPN_status = true);
				if( $_GET['_ning-pp-ipn'] == 'IPN' )
				{
					self::receive_payment( $ipn_response, 'paypal' );
				}
			}
			else
			{
				$adning_paypal_ipn->log_add('ERROR : Invalid ipn_request');	
			}
		}
    }




    /**
     * When a post gets loaded,
     * Check if the banner should be active.
     */
    public static function sell_load_post($post = array(), $filter = 1)
    {
        if(!empty($post))
        {
            // Only run when banner needs to get filtered
            if($filter)
            {
                // Make sure post is a banner
                if(strtolower($post['post']->post_type) === strtolower(ADNI_CPT::$banner_cpt))
                {
                    if(!empty($post['args']['sell']) && $post['post']->post_status === 'publish')
                    {
                        //echo 'Is sell banner.';
                        //echo $post['args']['sell']['contract'];
                        //echo '<pre>'.print_r($post,true).'</pre>';
                        $status = self::check_order_status($post);
                        //echo $status;
                        if( empty($status) || $status === 'expired')
                            return array();
                    }
                }
            }

            // Check for banner approval
            if(isset($_GET['approve_banner']) && !empty($_GET['approve_banner']))
            {
                if(!empty($post['args']['sell']))
                {
                    $order = self::load_order(array('query' => "WHERE id = '".$_GET['approve_banner']."' LIMIT 1"));
                    //echo '<pre>'.print_r($order,true).'</pre>';
                    if( !empty($order) )
                    {
                        global $wpdb;

                        $banner_id = $post['post']->ID;
                        $adzone_id = $order[0]->adzone_id;

                        //echo 'BANNER GOT APPROVED '.$banner_id;
                        ADNI_CPT::add_banner_to_adzone($adzone_id, $banner_id);
                        ADNI_CPT::add_adzone_to_banner($adzone_id, $banner_id);

                        //$b_args = ADNI_Multi::get_post_meta($banner_id, '_adning_args', array());
                        $post['args']['status'] = 'active';
                        ADNI_Multi::update_post_meta($banner_id, '_adning_args', $post['args']);
            
                        // Update order
                        $wpdb->query("UPDATE " . $wpdb->prefix . "adning_sell  
                            SET 
                                status = 'active'
                            WHERE id = '".$order[0]->id."' 
                        ");
                    }
                }
            }
        }
        return $post;
    }




    /**
     * Check / Update order Status
     * expire banner
     */
    public static function check_order_status($post, $order = array())
    {
        $status = '';
        /*if( empty($post['post']))
        {
            return $status;
        }*/
        //echo '<pre>'.print_r($post,true).'</pre>';
        //echo $post['post']->ID.' '.$post['args']['sell']['adzone_id'];
        $order = empty($order) ? self::load_order(array('query' => "WHERE banner_id = '".$post['post']->ID."' AND adzone_id = '".$post['args']['sell']['adzone_id']."' ORDER BY id DESC")) : self::load_order(array('query' => "WHERE id = '".$order->id."' LIMIT 1"));
        //echo '<pre>'.print_r($post,true).'</pre>';
        if(!empty($order))
        {
            //echo '<pre>'.print_r($order,true).'</pre>';
            // @since v1.4.5 Load adzone args
            $adzone_id = !empty($post['args']) ? $post['args']['sell']['adzone_id'] : $order[0]->adzone_id;
            /*if(empty($post['args']))
            {
                $Aargs = get_post_meta($adzone_id, '_adning_args', array());
                $post['args'] = !empty($Aargs) ? $Aargs[0] : array();
            }*/
            $sell_adzone_args = get_post_meta($adzone_id,'_adning_args', true);
            //echo '<pre>'.print_r($sell_adzone_args['sell'],true).'</pre>';
            $sell_unique = array_key_exists('unique_stats',$sell_adzone_args['sell']) ? $sell_adzone_args['sell']['unique_stats'] : 0;
            $unique_stats = $sell_unique === '' ? 0 : $sell_unique;
            //
            
            $order = $order[0];
            $status = $order->status;
            //$order_time = !empty($order->trans_date) ? $order->trans_date : current_time('timestamp');
            $order_time = !empty($order->trans_date) ? $order->trans_date : $order->time;
            $time_range = !empty($order->trans_date) ? 'custom_'.$order_time.'::'.current_time('timestamp') : '';

            if( $sell_adzone_args['sell']['contract'] === 'ppv')
            {
                $count = ADNI_Main::count_stats(array('type' => 'impression', 'group' => 'id_1', 'id' => $post['post']->ID, 'time_range' => $time_range, 'unique' => $unique_stats));
            }
            if( $sell_adzone_args['sell']['contract'] === 'ppc')
            {
                $count = ADNI_Main::count_stats(array('type' => 'click', 'group' => 'id_1', 'id' => $post['post']->ID, 'time_range' => $time_range, 'unique' => $unique_stats));
            }
            if( $sell_adzone_args['sell']['contract'] === 'ppd')
            {
                //echo date('d m Y', current_time('timestamp')).' :: '.date('d m Y', $order_time).'<br>';
                $datediff = current_time('timestamp') - $order_time;
                $count = round($datediff / (60 * 60 * 24));
                $togo = $sell_adzone_args['sell']['contract_duration'] - $count;
                //echo $order->id.' -- '.$togo.' - '.date('d m Y, H:i:s', $order_time).' :: '.date('d m Y, H:i:s', current_time('timestamp')).'<br>';
                  
                // Calculate hour difference
                // Expires today but maybe not yet at this hour.
                if($togo == 0)
                {
                    // create hour with fake date (day) to make sure order time issent in the past. as this was causing trouble calculating the wrong hour.
                    $ct = current_time('timestamp');
                    $sd = mktime(date('H',$ct), date('i',$ct), date('s',$ct), date('m',$ct), '01', date('Y',$ct));
                    $od = mktime(date('H',$order_time), date('i',$order_time), date('s',$order_time), date('m',$order_time), '02', date('Y',$order_time));
                    $datetime1 = new DateTime('@'.$sd);
                    $datetime2 = new DateTime('@'.$od);
                    /*$datetime1 = new DateTime(date('H:i:s',current_time('timestamp')));
                    $datetime2 = new DateTime(date('H:i:s',$order_time));*/
                    $interval = $datetime1->diff($datetime2);
                    //$timediff = ($interval->days * 24) + $interval->h;
                    $timediff = $interval->h;
                    if( $timediff > 0)
                    {
                        $togo = 1;
                    }
                    
                }
            }
            
            // Time to expire? Update status
            if( $sell_adzone_args['sell']['contract'] !== 'ppd' && $count >= $sell_adzone_args['sell']['contract_duration'] || $sell_adzone_args['sell']['contract'] === 'ppd' && $togo <= 0 )
            {
                //echo $order->id.' -- '.$togo.'<br>';
                $status = self::expire_order($order, $post);
                /*global $wpdb;
                $status = 'expired';

                // Remove banner from adzone
                ADNI_CPT::remove_banner_from_adzone($post['args']['sell']['adzone_id'], $post['post']->ID);
                ADNI_CPT::remove_adzone_from_banner($post['args']['sell']['adzone_id'], $post['post']->ID);
                // Update banner status
                $post['args']['status'] = $status;
                ADNI_multi::update_post_meta($post['post']->ID, '_adning_args', $post['args']);

                // Update order
                $wpdb->query("UPDATE " . $wpdb->prefix . "adning_sell  
                    SET 
                        status = '".$status."',
                        am_paid = '0',
                        trans_date = '',
                        transaction = ''
                    WHERE id = '".$order->id."' 
                ");

                // Allow 3rd parties to run a function when order expires.
                do_action( 'adning_order_expired', $order, $post );
                */
            }
        }

        return $status;
    }



    /**
     * expire order
     */
    public static function expire_order($order = array(), $post = array())
    {
        global $wpdb;
        $status = 'expired';

        if( !empty($order->banner_id))
        {
            $post = empty($post) ? ADNI_CPT::load_post($order->banner_id) : $post;
            
            // Remove banner from adzone
            ADNI_CPT::remove_banner_from_adzone($post['args']['sell']['adzone_id'], $post['post']->ID);
            ADNI_CPT::remove_adzone_from_banner($post['args']['sell']['adzone_id'], $post['post']->ID);
            // Update banner status
            $post['args']['status'] = $status;
            ADNI_Multi::update_post_meta($post['post']->ID, '_adning_args', $post['args']);
        }

        // Update order
        $wpdb->query("UPDATE " . $wpdb->prefix . "adning_sell  
            SET 
                status = '".$status."',
                am_paid = '0',
                trans_date = '',
                transaction = ''
            WHERE id = '".$order->id."' 
        ");

        // Allow 3rd parties to run a function when order expires.
        do_action( 'adning_order_expired', $order, $post );

        return $status;
    }



    /**
     * default settings
     */
    public static function defaults()
    {
        return array(
            'enable' => 0,
            'approve_manually' => 0,
            'contract' => 'ppc',
            'contract_duration' => 10,
            'price' => 10,
            'max_banners' => ''
        );
    }



    /**
     * Adjust adzone default settings
     * wp_parse_args( args, defaults );
     */
    public static function adzone_set($args = array())
    {
        return wp_parse_args(array('sell' => self::defaults()), $args);
    }





    /*
	 * Adzones for sale array
	 * Array containing Adzone ids that are available for sale.
	 *
	 * @access public
	 * @return array
	*/
	public static function adzones_for_sale($args = array())
	{
		return ADNI_Multi::get_option('_adning_adzones_for_sale', $args);
	}



    /**
     * Adjust post settings when saving settings
     */
    public static function save_post($post = array())
    {
        // Only run this on adzones
        if( strtolower($post['post_type']) === strtolower(ADNI_CPT::$adzone_cpt))
        {
            $sell_arr = isset($post['sell']) ? $post['sell'] : array();
            //echo '<pre>'.print_r($post, true).'</pre>';
            $set = wp_parse_args( $sell_arr, self::defaults() );
            $a_id = $post['post_id'];
            $adzones_for_sale = self::adzones_for_sale();

            if( $set['enable'] )
            {
                //echo '<pre>'.print_r($set, true).'</pre>';
                $adzones_for_sale[$a_id] = array(
                    'contract' => $set['contract'],
                    'duration' => $set['contract_duration'],
                    'price' => $set['price']
                );
                ADNI_Multi::update_option('_adning_adzones_for_sale', $adzones_for_sale);
            }
            else
            {
                if( array_key_exists($a_id, $adzones_for_sale)) 
                {
                    unset($adzones_for_sale[$a_id]);
                    ADNI_Multi::update_option('_adning_adzones_for_sale', $adzones_for_sale);
                }
            }
        }


        // Only run this on Banners
        /*if( strtolower($post['post_type']) === strtolower(ADNI_CPT::$banner_cpt))
        {
            
        }*/
       
        return $post;
    }






    /**
     * Adds a Sell settings meta_box to the adzone settings page.
     */
    public static function adzone_sell_box($adzone = array())
    {
        //print_r($adzone);
        $id = !empty($adzone['post']) ? $adzone['post']->ID : 0;
        $args = $adzone['args'];
        
        $h = '';
        //$h.= '<pre class="clearFix">'.print_r($args, true).'</pre>';
        $h.= '<div id="sell_adzone_settings" class="spr_column-inner left_column clearFix">
			<div class="spr_wrapper">
				<div class="option_box closed">
					<div class="info_header">
						<span class="nr"><svg viewBox="0 0 288 512" style="height:20px;"><path fill="currentColor" d="M209.2 233.4l-108-31.6C88.7 198.2 80 186.5 80 173.5c0-16.3 13.2-29.5 29.5-29.5h66.3c12.2 0 24.2 3.7 34.2 10.5 6.1 4.1 14.3 3.1 19.5-2l34.8-34c7.1-6.9 6.1-18.4-1.8-24.5C238 74.8 207.4 64.1 176 64V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48h-2.5C45.8 64-5.4 118.7.5 183.6c4.2 46.1 39.4 83.6 83.8 96.6l102.5 30c12.5 3.7 21.2 15.3 21.2 28.3 0 16.3-13.2 29.5-29.5 29.5h-66.3C100 368 88 364.3 78 357.5c-6.1-4.1-14.3-3.1-19.5 2l-34.8 34c-7.1 6.9-6.1 18.4 1.8 24.5 24.5 19.2 55.1 29.9 86.5 30v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-48.2c46.6-.9 90.3-28.6 105.7-72.7 21.5-61.6-14.6-124.8-72.5-141.7z"></path></svg></span>
                        <span class="text">'.__('Sell Ad spots','adn').'</span>
                        <span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>
					</div>';
					// <!-- end .info_header -->
                    
                    $h.= '<div class="settings_box_content hidden">';
                        if( !empty($id) )
                        {
                            $h.= '<div class="spr_column">
                                <div class="spr_column-inner">
                                    <div class="spr_wrapper">
                                        <div class="input_container">
                                            <p>
                                                '.__('','adn').'
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                            $sell_ad_spots = array_key_exists('enable',$args['sell']) ? $args['sell']['enable'] : 0;
                            $sell_ad_spots = $sell_ad_spots === '' ? 0 : $sell_ad_spots;
                            $h.= ADNI_Templates::switch_btn(array(
                                'title' => __('Sell AD Spots','adn'),
                                'id' => 'sellAdSpots',
                                'name' => 'sell[enable]',
                                'checked' => $sell_ad_spots,
                                'value' => 1,
                                'chk-on' => __('YES','adn'),
                                'chk-off' => __('NO','adn'),
                                'chk-high' => 1,
                                'column' => array(
                                    'size' => 'col-3',
                                    'desc' => __('Sell available ad spots in this adzone.','adn'),
                                )
                            ));


                            //$h.= '<div class="clearFix"></div>';
                            $contract = $args['sell']['contract'];
                            $contracts = self::contract_options();
                            $ctr_line = '';
                            foreach($contracts as $contr)
                            {
                                $name = self::contract(array('contract' => $contr));
                                $ctr_line.= '<option value="'.$contr.'" '.selected( $contract, $contr, false ).'>'.sprintf(__('Pay per %s','adn'),$name['single']).'</option>';
                            }
                            
                            $h.= ADNI_Templates::spr_column(array(
                                'col' => 'spr_col-3',
                                'title' => __('Contract Type','adn'),
                                'desc' => __('Select a contract type.','adn'),
                                'content' => '<select name="sell[contract]" class="">'.$ctr_line.'</select>'
                            ));

                            $h.= ADNI_Templates::spr_column(array(
                                'col' => 'spr_col-3',
                                'title' => __('Contract Duration','adn'),
                                'desc' => __('Select the duration amount (int value) for the contract.','adn'),
                                'content' => ADNI_Templates::inpt_cont(array(
                                        'type' => 'text',
                                        'show_icon' => 1,
                                        'icon' => 'clock-o',
                                        'width' => '100%',
                                        'name' => 'sell[contract_duration]',
                                        'value' => $args['sell']['contract_duration'],
                                        'placeholder' => ''
                                    ))
                            ));

                            $sell_unique = array_key_exists('unique_stats',$args['sell']) ? $args['sell']['unique_stats'] : 0;
                            $sell_unique = $sell_unique === '' ? 0 : $sell_unique;
                            $h.= ADNI_Templates::switch_btn(array(
                                'title' => __('Unique stats','adn'),
                                'id' => 'uniqueStats',
                                'name' => 'sell[unique_stats]',
                                'checked' => $sell_unique,
                                'value' => 1,
                                'chk-on' => __('YES','adn'),
                                'chk-off' => __('NO','adn'),
                                'chk-high' => 1,
                                'column' => array(
                                    'size' => 'col-3',
                                    'desc' => __('Unique statistics','adn'),
                                )
                            ));

                            $h.= '<div class="clearFix"></div>';

                            $h.= ADNI_Templates::spr_column(array(
                                'col' => 'spr_col-3',
                                'title' => __('Price','adn'),
                                'desc' => __('Price for the selected contract duration.','adn'),
                                'content' => ADNI_Templates::inpt_cont(array(
                                        'type' => 'text',
                                        'show_icon' => 1,
                                        'icon' => 'money',
                                        'width' => '100%',
                                        'name' => 'sell[price]',
                                        'value' => $args['sell']['price'],
                                        'placeholder' => ''
                                    ))
                            ));

                            $h.= ADNI_Templates::spr_column(array(
                                'col' => 'spr_col-3',
                                'title' => __('Max. amount of banners','adn'),
                                'desc' => __('The maximum amount of banners allowed in this adzone (int value). Leave empty for unlimited.','adn'),
                                'content' => ADNI_Templates::inpt_cont(array(
                                        'type' => 'text',
                                        'show_icon' => 1,
                                        'icon' => 'pencil',
                                        'width' => '100%',
                                        'name' => 'sell[max_banners]',
                                        'value' => $args['sell']['max_banners'],
                                        'placeholder' => ''
                                    ))
                            ));

                            $sell_approve_manually = array_key_exists('approve_manually',$args['sell']) ? $args['sell']['approve_manually'] : 0;
                            $sell_approve_manually = $sell_approve_manually === '' ? 0 : $sell_approve_manually;
                            $h.= ADNI_Templates::switch_btn(array(
                                'title' => __('Approve Manually','adn'),
                                'id' => 'sellApproveManually',
                                'name' => 'sell[approve_manually]',
                                'checked' => $sell_approve_manually,
                                'value' => 1,
                                'chk-on' => __('YES','adn'),
                                'chk-off' => __('NO','adn'),
                                'chk-high' => 1,
                                'column' => array(
                                    'size' => 'col-3',
                                    'desc' => __('Do you want each banner to be approved manually before going live?','adn'),
                                )
                            ));
                        }
                        else
                        {
                            $h.= '<div class="spr_column">
                                <div class="spr_column-inner">
                                    <div class="spr_wrapper">
                                        <div class="input_container">
                                            <p>
                                                '.__('Please save the adzone inorder to activate these settings.','adn').'
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                        }

                        $h.= ADNI_Templates::spr_column(array(
                            'col' => 'spr_col',
                            'title' => '',
                            'desc' => '',
                            'content' => '<input type="submit" value="'.__('Save Adzone','adn').'" class="button-primary" name="save_adzone">'
                        ));
                        

                    $h.= '</div>';
                    // end .settings_box_content

                    
                $h.= '</div>
            </div>
        </div>';
        // end .spr_column-inner

        echo $h;
    }




    /**
	 * Return available contract types
	 */
    public static function contract_options()
    {
        return ADNI_Main::has_stats(array('type' => 'int')) ? array('ppv','ppc','ppd') : array('ppd');
    }


    /**
	 * Returns a readable contract line with type + duration
	 */
	public static function contract($args = array())
	{
		if($args['contract'] == 'ppv')
		{
			$contract = array(
				'multi'  => __('Views'),
				'single' => __('View'),
			);
		}
		else if($args['contract'] == 'ppd')
		{
			$contract = array(
				'multi'  => __('Days'),
				'single' => __('Day'),
			);
		}
		else
		{
			$contract = array(
				'multi'  => __('Clicks'),
				'single' => __('Click'),
			);
		}
		
		return $contract;
	}
	
	
	
	/**
	 * Returns a readable contract line with type + duration
	 *
	 */
	public static function contract_line($args = array())
	{
        $defaults = array(
			'contract' => 'ppv',
            'duration' => 10,
            'line_format' => '%s %s'
		);
        $args = wp_parse_args( $args, $defaults );

		$html = '';
		$contract = self::contract(array('contract' => $args['contract']));
		
		if($args['duration'] > 1)
		{
            $html.= sprintf($args['line_format'], $args['duration'], $contract['multi']);
			//$html.= $args['duration'].' '.$contract['multi'];
		}	
		else
		{
            $html.= sprintf($args['line_format'], $args['duration'], $contract['single']);
			//$html.= $args['duration'].' '.$contract['single'];
		}
		
		return $html;
    }
    

    /**
	 * Returns a readable contract line whith info about expiring time
	 *
	 */
	public static function contract_expire_line($adzone = array(), $order = array())
	{
        $h = '';
        //echo '<pre>'.print_r($order, true).'</pre>';
        $contract = $adzone['sell']['contract'];
        $duration = $adzone['sell']['contract_duration'];
        $sell_unique = array_key_exists('unique_stats',$adzone['sell']) ? $adzone['sell']['unique_stats'] : 0;
        $unique_stats = $sell_unique === '' ? 0 : $sell_unique;
        
        $time = !empty($order->trans_date) ? $order->trans_date : $order->time;
        $time_range = 'custom_'.$time.'::'.current_time('timestamp');
        $togo = $duration;
        $contract_line = '';

        if( $contract === 'ppv')
        {
            if( ADNI_Main::has_stats(array('type' => 'int')) )
		    {
                $impressions = !empty($order->banner_id) ? ADNI_Main::count_stats(array('type' => 'impression', 'group' => 'id_1', 'id' => $order->banner_id, 'time_range' => $time_range, 'unique' => $unique_stats)) : 0;
                $togo = $duration - $impressions;
            }
        }
        if( $contract === 'ppc')
        {
            if( ADNI_Main::has_stats(array('type' => 'int')) )
		    {
                $clicks = !empty($order->banner_id) ? ADNI_Main::count_stats(array('type' => 'click', 'group' => 'id_1', 'id' => $order->banner_id, 'time_range' => $time_range, 'unique' => $unique_stats)) : 0;
                $togo = $duration - $clicks;
            }
        }
        if( $contract === 'ppd')
        {
            $datediff = current_time('timestamp') - $time;
            $count = round($datediff / (60 * 60 * 24));
            $togo = $duration - $count;
            //echo '<pre>'.print_r($order,true).'</pre>';
            // echo $order->id.' -- '.$togo.' :: '.$duration.', '.$count.'<br>';

            // Calculate hour difference
            // Expires today but maybe not yet at this hour.
            if($togo == 0)
            {
                //echo date('d m Y, H:i:s',current_time('timestamp')).' -- '.date('d m Y, H:i:s',$time);
                /*$datetime1 = new DateTime(date('H:i:s',current_time('timestamp')));
                $datetime2 = new DateTime(date('H:i:s',$time));*/
                //print_r( $datetime1).print_r($datetime2);

                // create hour with fake date (day) to make sure order time issent in the past. as this was causing trouble calculating the wrong hour.
                $ct = current_time('timestamp');
                $sd = mktime(date('H',$ct), date('i',$ct), date('s',$ct), date('m',$ct), '01', date('Y',$ct));
                $od = mktime(date('H',$time), date('i',$time), date('s',$time), date('m',$time), '02', date('Y',$time));
                $datetime1 = new DateTime('@'.$sd);
                $datetime2 = new DateTime('@'.$od);
                
                
                $interval = $datetime1->diff($datetime2);
                //$timediff = ($interval->days * 24) + $interval->h;
                $timediff = $interval->h;
                //echo ($interval->days * 24);
                if( $timediff > 0)
                {
                    $togo = 1;
                    $contract_line = sprintf(__('Contract will expire in %s houres','adn'), $timediff);
                }
                
            }
        }
        

        if( $togo > 0 )
        {
            $h.= !empty($contract_line) ? $contract_line : self::contract_line(array('contract' => $contract, 'duration' => $togo, 'line_format' => __('Contract will expire after %s more %s','adn') ));
        }
        else
        {
            $h.= __('Contract is expired.','adn');

            // Expire order if it didn't happen yet.
            //print_r($order);
            $post = get_post($order->banner_id);
            $args = get_post_meta($order->banner_id, '_adning_args', array());
            $args = !empty($post) ? $args[0] : $args;
            //echo $order->id.' '.$togo.'<br>';
            $status = self::check_order_status(array('post' => $post, 'args' => $args), $order);
            //echo $order->id.' - '.$status.' '.$togo.'<br>';
            
        }
		
		return $h;
	}




    public static function adzone_details($adzone)
    {
        $adzone_info = array();

        if( is_numeric($adzone))
        {
            $adzone = ADNI_CPT::load_post($adzone, array('post_type' => ADNI_CPT::$adzone_cpt, 'filter' => 0));
        }
    
        if( !empty($adzone))
        {
            $adzone_info['enable'] = $adzone['args']['sell']['enable'];
            $adzone_info['id'] = $adzone['post']->ID;  
            $adzone_info['name'] = $adzone['post']->post_title;
            $adzone_info['size'] = $adzone['args']['size'];
            $adzone_info['price'] = $adzone['args']['sell']['price'];
            $adzone_info['review'] = $adzone['args']['sell']['approve_manually'];
            $adzone_info['contract'] = $adzone['args']['sell']['contract'];
            $adzone_info['duration'] = $adzone['args']['sell']['contract_duration'];
            $adzone_info['linked_banners'] = $adzone['args']['linked_banners'];
            $adzone_info['linked_banners_num'] = !empty($adzone_info['linked_banners']) ? count($adzone_info['linked_banners']) : 0;
            $adzone_info['max_banners'] = $adzone['args']['sell']['max_banners'];
            $adzone_info['rotation'] = empty($adzone_info['max_banners']) || $adzone_info['max_banners'] > 1 ? 1 : 0;
            $adzone_info['rotation_icon'] = $adzone_info['rotation'] ? '<svg viewBox="0 0 512 512" style="width:11px;color:#cbcbcb;"><path fill="currentColor" d="M370.72 133.28C339.458 104.008 298.888 87.962 255.848 88c-77.458.068-144.328 53.178-162.791 126.85-1.344 5.363-6.122 9.15-11.651 9.15H24.103c-7.498 0-13.194-6.807-11.807-14.176C33.933 94.924 134.813 8 256 8c66.448 0 126.791 26.136 171.315 68.685L463.03 40.97C478.149 25.851 504 36.559 504 57.941V192c0 13.255-10.745 24-24 24H345.941c-21.382 0-32.09-25.851-16.971-40.971l41.75-41.749zM32 296h134.059c21.382 0 32.09 25.851 16.971 40.971l-41.75 41.75c31.262 29.273 71.835 45.319 114.876 45.28 77.418-.07 144.315-53.144 162.787-126.849 1.344-5.363 6.122-9.15 11.651-9.15h57.304c7.498 0 13.194 6.807 11.807 14.176C478.067 417.076 377.187 504 256 504c-66.448 0-126.791-26.136-171.315-68.685L48.97 471.03C33.851 486.149 8 475.441 8 454.059V320c0-13.255 10.745-24 24-24z"></path></svg>' : '';
            $adzone_info['rotate_info'] = $adzone_info['rotation'] && $adzone_info['max_banners'] > 1 ? sprintf(__('Ad will rotate with max. %s other ads','adn'), ($adzone_info['max_banners']-1)) : __('Ad will rotate with other ads','adn');
            $adzone_info['spots'] = !empty($adzone_info['max_banners']) ? $adzone_info['max_banners']-$adzone_info['linked_banners_num'] : 1;
            $adzone_info['spots_available'] = !empty($adzone_info['max_banners']) ? $adzone_info['max_banners']-$adzone_info['linked_banners_num'] : __('Unlimited','adn');
            $adzone_info['contract_line'] = self::contract_line(array('contract' => $adzone_info['contract'], 'duration' => $adzone_info['duration']));
        }

        return $adzone_info;
    }




    /**
	 * Shortcode function to show all available adzones in a page
	 */
    public static function sc_available_adzones($args = array())
    {
        $h = '';
        /*$defaults = array(
            'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"
        );
        $args = ADNI_Main::parse_args($args, $defaults);*/
        
        ADNI_Init::enqueue(
            array(
                'files' => array(
                    array('file' => '_ning_css', 'type' => 'style'),
                    array('file' => '_ning_admin_css', 'type' => 'style'),
                    array('file' => '_ning_frontend_manager_css', 'type' => 'style'),
                    array('file' => '_ning_global', 'type' => 'script'),
                    array('file' => '_ning_uploader', 'type' => 'script'),
                    array('file' => '_ning_admin_global', 'type' => 'script')
                )
            )
        );

        require(ADNI_TPL_DIR.'/frontend_manager/sell/available_adzones.php'); 
        return $h;
    }

    /**
	 * Shortcode function to show the user dashboard in a page
	 */
    public static function sc_user_dashboard($args = array())
    {  
        $h = '';
        ADNI_Init::enqueue(
            array(
                'files' => array(
                    array('file' => '_ning_css', 'type' => 'style'),
                    array('file' => '_ning_admin_css', 'type' => 'style'),
                    array('file' => '_ning_frontend_manager_css', 'type' => 'style'),
                    array('file' => '_ning_global', 'type' => 'script'),
                    array('file' => '_ning_uploader', 'type' => 'script'),
                    array('file' => '_ning_admin_global', 'type' => 'script')
                )
            )
        );

        require(ADNI_TPL_DIR.'/frontend_manager/sell/user_dashboard.php'); 
        return $h;
    }


    /**
	 * Shortcode function to show the banner editor in a page
	 */
    public static function sc_edit_banner($args = array())
    {  
        $h = '';
        $is_frontend = 1;

        ADNI_Init::enqueue(
            array(
                'files' => array(
                    array('file' => '_ning_css', 'type' => 'style'),
                    array('file' => '_ning_admin_css', 'type' => 'style'),
                    array('file' => '_ning_frontend_manager_css', 'type' => 'style'),
                    array('file' => '_ning_global', 'type' => 'script'),
                    array('file' => '_ning_uploader', 'type' => 'script'),
                    array('file' => '_ning_admin_global', 'type' => 'script')
                )
            )
        );

        require(ADNI_TPL_DIR.'/single_banner.php'); 
        return $h;
    }



    /**
	 * All available adzones
	 */
	public static function all_available_adzones($args = array())
	{	
		$defaults = array(
			'title' => __('Advertising Spots','adn'),
            'exclude' => array()
		);
        $args = wp_parse_args( $args, $defaults );
        $available_adzones = self::adzones_for_sale();
        $sell_settings = self::sell_main_settings();
        $sell_settings = $sell_settings['sell'];
        $html = '';
        
        $html.= '<div class="available_zones">';
            $html.= '<h1 class="title">'.$args['title'].'</h1>';
            $html.= '<ul class="bs">';
                
                if(!empty($available_adzones))
                {
                    foreach($available_adzones as $key => $zone)
                    {
                        $adzone = ADNI_CPT::load_post($key, array('post_type' => ADNI_CPT::$adzone_cpt, 'filter' => 0));
                        if(!empty($adzone))
                        {
                            $adzone_info = self::adzone_details($adzone);

                            if( $adzone_info['enable'] )
                            {
                                $urlf = strpos($sell_settings['urls']['available_adzones'], '?') !== false ? '&' : '?';
                                $url = $sell_settings['urls']['available_adzones'].$urlf.'adzone='.$adzone['post']->ID;

                                $html.= '<li class="order">';
                                    //$html.= '<a href="'.$args['url'].$url_args.'" class="box">';
                                    $html.= '<a href="'.$url.'" class="box">';
                                    $html.= '<div class="one_second v_middle">';
                                        $html.= '<span style="color:#6b6b6b;"><strong>'.$adzone['post']->post_title.'</strong> '.__('adzone','adn').' '.$adzone_info['rotation_icon'].'</span>';
                                        if( $adzone_info['spots'] <= 0 )
                                        {
                                            // soldout
                                            $html.= '<div class="status soldout" style="text-transform:uppercase;margin: 0 0 0 10px;">'.__('Currently sold out','adn').'</div>';
                                        }
                                        else
                                        {
                                            // available
                                            $html.= '<div class="status available" style="text-transform:uppercase;margin: 0 0 0 10px;">'.sprintf(__('%s Spots Available','adn'), $adzone_info['spots_available']).'</div>';
                                        }
                                        // adzone rotation info
                                        $html.= $adzone_info['rotation'] ? '<div style="font-size:12px;color:#c3c3c3;"><strong>'.__('Rotation','adn').'</strong> - '.$adzone_info['rotate_info'].'</div>' : '';
                                        // adzone size info
                                        $html.= '<div style="font-size:12px;color:#c3c3c3;"><strong>'.__('Size','adn').'</strong> - '.$adzone_info['size'].'</div>';
                                    $html.= '</div>';
                                    $html.= '<div class="one_second v_middle" style="color: #c3c3c3; text-align:center;">';
                                        $html.= '<span>'.$adzone_info['price'].' '.$sell_settings['cur'].'</span>';
                                        $html.= '<div><small>'.sprintf(__('Per %s','adn'),$adzone_info['contract_line']).'</small></div>';
                                    $html.= '</div>';
                                    
                                    $html.= '</a>';
                                $html.= '</li>';
                            }
                        }
                    }
                }
                else
                {
                    $html.= '<li class="order" style="padding:10px;">';
                        $html.= __('Sorry, we currently have no advertising spots available.','adn');
                    $html.= '</li>';
                }
            $html.= '</ul>';
        $html.= '</div>';
        // end .bs_available_zones
        
		
		return $html;
    }





    /**
	 * Load Order
	 */
	public static function load_order($args = array())
	{
        ADNI_Multi::wpmu_load_from_main_start();
            global $wpdb;
            
            $defaults = array(
                'query' => ''
            );
            $args = wp_parse_args( $args, $defaults );

            self::check_if_table_exists( 'adning_sell' );
            //$result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "adning_sell ".$args['query']);
            $result = $wpdb->get_results("SELECT id, time, user_id, email, ip_address, adzone_id, banner_id, status, price, am_paid, pos, trans_date, transaction, provider FROM " . $wpdb->prefix . "adning_sell ".$args['query']);
        ADNI_Multi::wpmu_load_from_main_stop();

		return $result;	
	}






    /**
	 * Order Status
	 * 
	 *
	 */
	public static function order_status($order = array())
	{
        $status = $order->status === 1 ? 'active' : $order->status;
		$status = empty($status) ? 'abandoned' : $status;
		if( $status === 'abandoned' )
		{
			$status = current_time('timestamp') - $order->time < 3600 ? 'in-progress' : $status;
		}
        $status = self::status(array('key' => $status));
		
		return $status;
	}

    /**
	 * Status Array
	 * 
	 *
	 */
	public static function status($args = array())
	{
		$defaults = array(
			'key' => ''
		);
		$args = wp_parse_args( $args, $defaults );
		
		$array = array(
			'draft' => array('value' => 'draft', 'name' => __('Pending Review','adn')),
			'abandoned' => array('value' => 'abandoned', 'name' => __('Not Finished','adn')),
			'in-progress' => array('value' => 'in-progress', 'name' => __('In Progress','adn')),
			'active' => array('value' => 'active', 'name' => __('Active','adn')),
			'expired' => array('value' => 'expired', 'name' => __('Expired','adn')),
			'trash' => array('value' => 'trash', 'name' => __('Trashed','adn')),
			'renewed' => array('value' => 'renewed', 'name' => __('Renewed','adn')),
		);
		
		return !empty($args['key']) ? $array[$args['key']] : $array;
    }
    


    /**
     * DO something with the banner post values while saving.
     * Adds banner to adzone used in single_banner.php
     * Only for new banners.
     */
    public static function add_banner_to_adzone($_post = array())
    {
        global $wpdb;

        $defaults = array(
            'post_id' => 0
		);
        $_post = wp_parse_args( $_post, $defaults );

        
        //$adzone_id = $args['sell_aid'];
        //echo '<pre> '.print_r($args,true).'</pre>';
        //unset($args['sell_aid']);
        $b_args = ADNI_Multi::get_post_meta($_post['post_id'], '_adning_args', array());

        //if( !empty($adzone_id))
        if( !empty($b_args) && !empty($b_args['sell']))
        {
            $banner_id = $_post['post_id'];
            $order_id = $b_args['sell']['order_id'];
            $adzone_id = $b_args['sell']['adzone_id'];
            //$user = wp_get_current_user();
            //$email = $user->user_email;
            
            // Only run this code when no banner ID is assigned to the order yet.
            $orders = self::load_order(array('query' => "WHERE id = '".$order_id."' AND banner_id = '0' LIMIT 1"));
            if( !empty($orders))
            {
                if( $orders[0]->status === 'active' )
                {
                    $args['args']['status'] = 'active';
                    ADNI_CPT::add_banner_to_adzone($adzone_id, $banner_id);
                }
                else
                {
                    $args['args']['status'] = 'review';
                }

                // Update order
                $wpdb->query("UPDATE " . $wpdb->prefix . "adning_sell  
                    SET 
                        banner_id = '".$banner_id."'
                    WHERE id = '".$orders[0]->id."' 
                ");
            }
        }

        return $_post;
    }





    /**
	 * ADMIN DASHBOARD
	 */
	public static function admin_dashboard($args = array())
	{
        $h = '';
        $sell_settings = self::sell_main_settings();
        $sell_settings = $sell_settings['sell'];

        self::check_if_table_exists( 'adning_sell' );
		$orders = self::load_order(array('query' => "ORDER BY id DESC"));
            
        $h.= '<div class="available_zones">';
			$h.= '<h1 class="title">'.__('All Orders','adn').'</h1>';
            $h.= '<ul class="bs">';
                if( !empty($orders))
                {
                    foreach( $orders as $order)
                    {
                        $status = self::order_status($order);
                        $adzone = ADNI_multi::get_post_meta($order->adzone_id, '_adning_args', array());
                        $post_status = !empty($order->adzone_id) ? get_post_status($order->adzone_id) : '';
                        $time = !empty($order->trans_date) ? $order->trans_date : $order->time;
                        
                        $h.= '<li class="order" style="padding:10px;" data-order-id="'.$order->id.'">';
                            // Banner info
                            $h.= '<div class="one_third v_middle">';
                                $h.= !empty($order->adzone_id) ? '<span><small>#'.$order->id.'</small> '.get_the_title($order->adzone_id).'</span>' : '';
                                $h.= '<span style="color:#c3c3c3;font-size:11px;margin-left:5px;">'.sprintf(__('Purchased: %s ago','adn'), self::time_ago($time)).'</span>';
                                $h.= '<div>';
                                    $h.= '<span class="status '.$status['value'].'">'.$status['name'].'</span>';
                                    $h.= $post_status == 'trash' ? '<span class="status trash">'.__('Trashed','adn').'</span>' : '';
                                    $h.= '<span style="font-size:12px;color:#c3c3c3;"><strong>'.__('Size','adn').'</strong> - '.$adzone['size'].'</span>';
                                $h.= '</div>';
                            $h.= '</div>';

                            $h.= '<div class="one_fifth v_middle">';
                                $h.= '<span style="color:#c3c3c3;font-size:11px;">'.__('Advertiser:','adn').'</span>';
                                $h.= '<div style="font-size:12px;color:#c3c3c3;">';
                                    $h.= '<small>#'.$order->user_id.'</small> <strong>'.$order->email.'</strong>';
                                $h.= '</div>';
                            $h.= '</div>';
                            
                            $h.= '<div class="one_fifth v_middle">';
                                $h.= '<span style="color:#c3c3c3;font-size:11px;">'.sprintf(__('Contract: %s','adn'), $adzone['sell']['contract']).'</span>';
                                $h.= '<div style="font-size:12px;color:#c3c3c3;">';
                                    $h.= '<strong>'.self::contract_expire_line($adzone, $order).'</strong>';
                                $h.= '</div>';
                            $h.= '</div>';


                            if( !empty($order->banner_id) )
                            {
                                $banner = ADNI_CPT::load_post($order->banner_id, array('post_type' => ADNI_CPT::$banner_cpt, 'filter' => 0));
                                //echo '<pre>'.print_r($banner,true).'</pre>';
                                $h.= '<div class="one_forth v_middle">';
                                    $h.= '<span style="color:#c3c3c3;font-size:11px;">'.__('Linked Banner:','adn').'</span>';
                                    $h.= !empty($banner) ? ' <strong>'.$banner['post']->post_title.'</strong>' : '';
                                    $h.= '<div style="font-size:12px;color:#c3c3c3;"><strong>'.__('Size','adn').'</strong> - '.$banner['args']['size'].'</div>';
                                $h.= '</div>';

                                // Edit banner
                                if( is_admin() )
                                {
                                    $edit_url = esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning&view=banner&id='.$order->banner_id)));
                                }
                                else
                                {
                                    $urlf = strpos($sell_settings['urls']['edit_banner'], '?') !== false ? '&' : '?';
                                    $edit_url = $sell_settings['urls']['edit_banner'].$urlf.'id='.$order->banner_id;
                                }
                                $btn_title = $order->status === 'draft' ? __('Review Banner','adn') : __('Edit Banner','adn');
                                $h.= '<a class="button-secondary" href="'.$edit_url.'">'.$btn_title.'</a>';
                            }


                            // ACTIVATE ORDER
                            if( empty($order->status) ) // || $order->status === 'draft'
                            {
                                if( is_admin() )
                                {
                                    $activate_url = esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning-sell&activate_order='.$order->id)));
                                }
                                else
                                {
                                    $urlf = strpos($sell_settings['urls']['user_dashboard'], '?') !== false ? '&' : '?';
                                    $activate_url = $sell_settings['urls']['user_dashboard'].$urlf.'activate_order='.$order->id;
                                }
                                $h.= '<a class="button-secondary" href="'.$activate_url.'">'.__('Activate Order','adn').'</a>';
                            }


                            // REMOVE ORDER
                            if( is_admin() )
                            {
                                $remove_url = esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning-sell&remove_order='.$order->id)));
                            }
                            else
                            {
                                $urlf = strpos($sell_settings['urls']['user_dashboard'], '?') !== false ? '&' : '?';
                                $remove_url = $sell_settings['urls']['user_dashboard'].$urlf.'remove_order='.$order->id;
                            }
                            $h.= '<a title="'.__('Remove Order','adn').'" class="_ning_remove_sell_order remove_order button-secondary" data-href="'.$remove_url.'" data-msg="'.sprintf(__('Are you sure you want to remove this order (#: %s)? This cannot be undone.','adn'),$order->id).'" style="margin-left: 40px;">';
                                $h.= '<svg viewBox="0 0 448 512" style="height:12px;"><path fill="currentColor" d="M0 84V56c0-13.3 10.7-24 24-24h112l9.4-18.7c4-8.2 12.3-13.3 21.4-13.3h114.3c9.1 0 17.4 5.1 21.5 13.3L312 32h112c13.3 0 24 10.7 24 24v28c0 6.6-5.4 12-12 12H12C5.4 96 0 90.6 0 84zm416 56v324c0 26.5-21.5 48-48 48H80c-26.5 0-48-21.5-48-48V140c0-6.6 5.4-12 12-12h360c6.6 0 12 5.4 12 12zm-272 68c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208z"></path></svg>';
                            $h.= '</a>';

                        $h.= '</li>';
                    }
                }
            $h.= '</ul>';
        $h.= '</div>';

        return $h;
    }


    /**
	 * USER DASHBOARD - Frontent
	 */
	public static function user_dashboard($args = array())
	{	
		$defaults = array(
			'title' => __('Your Ad Spots','adn')
		);
		$args = wp_parse_args( $args, $defaults );
		$html = '';
        //$bs_data = self::get_buysell_data();
        
        $sell_settings = self::sell_main_settings();
        $sell_settings = $sell_settings['sell'];
		
		if( is_user_logged_in() )
		{
			$user = wp_get_current_user();
			$email = $user->user_email;
            
            self::check_if_table_exists( 'adning_sell' );
			$orders = self::load_order(array('query' => "WHERE email = '".$email."' ORDER BY id DESC"));
            
			$html.= '<div class="available_zones">';
				$html.= '<h1 class="title">'.$args['title'].'</h1>';
				$html.= '<ul class="bs">';
					if( !empty($orders))
					{
						foreach( $orders as $order)
						{
							$status = self::order_status($order);
							//if( $status['value'] != 'renewed'){
                            $adzone = ADNI_multi::get_post_meta($order->adzone_id, '_adning_args', array());
                            $post_status = !empty($order->adzone_id) ? get_post_status($order->adzone_id) : '';
                            $time = !empty($order->trans_date) ? $order->trans_date : $order->time;
                            
                            $html.= '<li class="order" style="padding:10px;" data-order-id="'.$order->id.'">';
                                // Banner info
                                $html.= '<div class="one_third v_middle">';
                                    $html.= !empty($order->adzone_id) ? '<span><small>#'.$order->id.'</small> '.get_the_title($order->adzone_id).'</span>' : '';
                                    $html.= '<span style="color:#c3c3c3;font-size:11px;margin-left:5px;">'.sprintf(__('Purchased: %s ago','adn'), self::time_ago($time)).'</span>';
                                    $html.= '<div>';
                                        $html.= '<span class="status '.$status['value'].'">'.$status['name'].'</span>';
                                        $html.= $post_status == 'trash' ? '<span class="status trash">'.__('Trashed','adn').'</span>' : '';
                                        $html.= '<span style="font-size:12px;color:#c3c3c3;"><strong>'.__('Size','adn').'</strong> - '.$adzone['size'].'</span>';
                                    $html.= '</div>';
                                $html.= '</div>';
                                

                                $html.= '<div class="one_forth v_middle">';
                                    $html.= '<span style="color:#c3c3c3;font-size:11px;">'.sprintf(__('Contract: %s','adn'), $adzone['sell']['contract']).'</span>';
                                    $html.= '<div style="font-size:12px;color:#c3c3c3;">';
                                        $html.= '<strong>'.self::contract_expire_line($adzone, $order).'</strong>';
                                    $html.= '</div>';
                                $html.= '</div>';
                                

                                if($order->status === 'active' || $order->status === 'draft')
                                {
                                    if( empty($order->banner_id) )
                                    {
                                        $urlf = strpos($sell_settings['urls']['edit_banner'], '?') !== false ? '&' : '?';
                                        $html.= '<div class="one_forth v_middle">';
                                            $html.= '<a class="button-secondary add_banner" href="'.$sell_settings['urls']['edit_banner'].$urlf.'oid='.$order->id.'&slid='.$order->adzone_id.'">'.__('Add Banner','adn').'</a>';
                                        $html.= '</div>';
                                    }
                                    else
                                    {
                                        // Banner info
                                        $banner = ADNI_CPT::load_post($order->banner_id, array('post_type' => ADNI_CPT::$banner_cpt, 'filter' => 0));
                                        //echo '<pre>'.print_r($banner,true).'</pre>';
                                        $html.= '<div class="one_forth v_middle">';
                                            $html.= '<span style="color:#c3c3c3;font-size:11px;">'.__('Linked Banner:','adn').'</span>';
                                            $html.= !empty($banner) ? ' <strong>'.$banner['post']->post_title.'</strong>' : '';
                                            //$html.= !empty($order->adzone_id) ? ' <span>'.__('adzone','adn').'</span>' : '';
                                            //$html.= !empty($order->adzone_id) ? ' '.$adzone_info['rotation_icon'] : '';
                                            //$html.= $adzone_info['rotation'] ? '<div style="font-size:12px;color:#c3c3c3;"><strong>'.__('Rotation','adn').'</strong> - '.$adzone_info['rotate_info'].'</div>' : '';
                                            $html.= '<div style="font-size:12px;color:#c3c3c3;"><strong>'.__('Size','adn').'</strong> - '.$banner['args']['size'].'</div>';
                                        $html.= '</div>';
                                    }
                                }

                                

                                $html.= '<div class="one_forth v_middle" style="text-align:right;">';
                                    // Edit banner
                                    if( !empty($order->banner_id))
                                    {
                                        // Renew Contract
                                        if( $status['value'] === 'expired' )
                                        {
                                            if($order->provider === 'paypal')
                                            {
                                                $html.= self::paypal(
                                                    array(
                                                        'aid' => $order->adzone_id,
                                                        'bid' => $order->banner_id,
                                                        'price' => $order->price,
                                                        'email' => $order->email,
                                                        'type' => 'renew',
                                                        'order_id' => $order->id
                                                    ), // ipn_data
                                                    array(
                                                        'form_id'      => 'renewContract',
                                                        'form_style'   => 'display:inline-block',
                                                        'show_btn'     => 1,
                                                        'submit_btn'   => __('Renew Contract','adn'),
                                                    ) // args
                                                ); 
                                            }
                                        }
                                        else
                                        {
                                            $urlf = strpos($sell_settings['urls']['edit_banner'], '?') !== false ? '&' : '?';
                                            $html.= '<a class="button-secondary" href="'.$sell_settings['urls']['edit_banner'].$urlf.'id='.$order->banner_id.'">'.__('Edit Banner','adn').'</a>';
                                            //$html.= '<a class="button-secondary" href="?_ning_front=1&view=banner&id='.$order->banner_id.'">'.__('Edit Banner','adn').'</a>';
                                        }
                                    }

                                    if( $status['value'] === 'abandoned' )
                                    {
                                        if($order->provider === 'paypal')
                                        {
                                            $html.= self::paypal(
                                                array(
                                                    'aid' => $order->adzone_id,
                                                    'bid' => $order->banner_id,
                                                    'price' => $order->price,
                                                    'email' => $order->email,
                                                    'type' => 'renew',
                                                    'order_id' => $order->id
                                                ), // ipn_data
                                                array(
                                                    'form_id'      => 'renewContract',
                                                    'form_style'   => 'display:inline-block',
                                                    'show_btn'     => 1,
                                                    'submit_btn'   => __('Complete Purchase','adn'),
                                                ) // args
                                            );
                                        }
                                    }

                                    $urlf = strpos($sell_settings['urls']['user_dashboard'], '?') !== false ? '&' : '?';
                                    $html.= '<a title="'.__('Remove Order','adn').'" class="_ning_remove_sell_order remove_order button-secondary" data-href="'.$sell_settings['urls']['user_dashboard'].$urlf.'remove_order='.$order->id.'" data-msg="'.sprintf(__('Are you sure you want to remove this order (#: %s)? This cannot be undone.','adn'),$order->id).'" style="margin-left: 40px;">';
                                        $html.= '<svg viewBox="0 0 448 512" style="height:12px;"><path fill="currentColor" d="M0 84V56c0-13.3 10.7-24 24-24h112l9.4-18.7c4-8.2 12.3-13.3 21.4-13.3h114.3c9.1 0 17.4 5.1 21.5 13.3L312 32h112c13.3 0 24 10.7 24 24v28c0 6.6-5.4 12-12 12H12C5.4 96 0 90.6 0 84zm416 56v324c0 26.5-21.5 48-48 48H80c-26.5 0-48-21.5-48-48V140c0-6.6 5.4-12 12-12h360c6.6 0 12 5.4 12 12zm-272 68c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208zm96 0c0-8.8-7.2-16-16-16s-16 7.2-16 16v224c0 8.8 7.2 16 16 16s16-7.2 16-16V208z"></path></svg>';
                                    $html.= '</a>';
                                $html.= '</div>';

                            $html.= '</li>';
							//}
						}
                    }
                    else
                    {
                        $html.= '<li class="order" style="padding:10px;">'.sprintf(__('You don\'t own any active ad spots. Have a look at the %s.'), '<a href="'.$sell_settings['urls']['available_adzones'].'">'.__('available options','adn').'</a>').'</li>';
                    }
				$html.= '</ul>';
			$html.= '</div>';
		}
		else
		{
            $html.= '<div class="spr_row" style="margin-top: 40px;">';
                $html.= '<div class="spr_column spr_col">';
                    $html.= '<div class="spr_column-inner left_column">';
                        $html.= '<div class="spr_wrapper">';
                            $html.= '<div class="input_container">';
                                $html.= '<div class="ning_login_box">';
                                    $html.= '<a href="'.wp_login_url( $sell_settings['urls']['user_dashboard'] ).'" title="'.__('Login','adn').'">'.__('Please login to access this page.','adn').'</a>'; // $_SERVER['REQUEST_URI']
                                $html.= '</div>';
                                $html.= '<span class="description bottom"></span>';
                            $html.= '</div>';
                        $html.= '</div>';
                    $html.= '</div>';
                $html.= '</div>';
            $html.= '</div>';
		}
			
		
		return $html;
	}
    





    /**
	 * BUY SELL - ORDER FORM
	 *
	 */
	public static function order_form($args = array())
	{
        $defaults = array(
            'id' => 0
        );
        $args = wp_parse_args($args, $defaults);
        
        $adzone_id = $args['id'];
		$html = '';
        
		if( $adzone_id )
		{
            $adzone = ADNI_CPT::load_post($adzone_id, array('post_type' => ADNI_CPT::$adzone_cpt, 'filter' => 0));
            $adzone_info = self::adzone_details($adzone);
            $sell_settings = self::sell_main_settings();
            $sell_settings = $sell_settings['sell'];
            
            if( $adzone_info['spots'] > 0 )
            {
                $html.= '<div class="_ning_order_form">';
                    
                    $html.= '<div class="spr_row">  
                        <div class="spr_column spr_col-8">
                            <div class="spr_column-inner left_column">
                                <div class="spr_wrapper">';

                                    $html.= '<div class="option_box" style="position:relative;">';

                                        $html.= '<div class="info_header">
                                            <span class="nr">1</span>
                                            <span class="text">';
                                                $html.= sprintf(__('Advertise in the %s adzone %s','adn'), '<strong>'.$adzone_info['name'].'</strong>', $adzone_info['rotation_icon']);
                                            $html.= '</span>
                                        </div>';
                                        // end .info_header

                                        $html.= '<div class="loading_overlay">
                                            <div class="inner_loader"><svg aria-hidden="true" data-prefix="fas" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-spinner fa-w-16 fa-spin fa-lg"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z" class=""></path></svg></div>
                                        </div>';

                                        $html.= '<div class="spr_row adzone_order_form" style="position:relative;">';

                                            //$html.= '<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Rotation Info','adn').'</strong></span></div>';
                                            $html.= '<div class="spr_column spr_col-4">
                                                <div class="spr_column-inner left_column">
                                                    <div class="spr_wrapper">
                                                        <div class="input_container">';
                                                            if( $adzone_info['spots'] <= 0 )
                                                            {
                                                                // soldout
                                                                $html.= '<div class="status soldout" style="text-transform:uppercase;margin:0;">'.__('Currently sold out','adn').'</div>';
                                                            }
                                                            else
                                                            {
                                                                // available
                                                                $html.= '<div class="status available" style="text-transform:uppercase;margin:0;">'.sprintf(__('%s Spots Available','adn'), $adzone_info['spots_available']).'</div>';
                                                            }

                                                            // adzone rotation info
                                                            $html.= $adzone_info['rotation'] ? '<div style="font-size:12px;color: #c3c3c3;"><strong>'.__('Rotation','adn').'</strong> - '.$adzone_info['rotate_info'].'</div>' : '';
                                                            // adzone size info
                                                            $html.= '<div style="font-size:12px;color: #c3c3c3;"><strong>'.__('Size','adn').'</strong> - '.$adzone_info['size'].'</div>';
                                                            
                                                            $html.= '<span class="description bottom">'.__('','adn').'</span>';
                                                        $html.= '</div>
                                                    </div>  
                                                </div>
                                            </div>'; 
                                            // end .spr_column

                                            $html.= ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col-8',
                                                'title' => '',
                                                'desc' => '',
                                                'content' => $adzone['args']['description']
                                            ));
                                            
                                            

                                            // Check if user is loggedin
                                            $email = '';
                                            $name = '';
                                            
                                            $html.= '<div class="clearFix"></div>';
                                            $html.= '<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Your Details','adn').'</strong></span></div>';
                                            
                                            if( is_user_logged_in() )
                                            {
                                                $user = wp_get_current_user();
                                                $email = $user->user_email; 
                                                $name = !empty($user->user_firstname) && !empty($user->user_lastname) ? $user->user_firstname.' '.$user->user_lastname : '';
                                            
                                                $html.= '<div class="spr_column spr_col-6">';
                                                    $html.= '<div class="spr_column-inner left_column">
                                                        <div class="spr_wrapper">
                                                            <div class="input_container">';
                                                                $html.= ADNI_Templates::inpt_cont(array(
                                                                    'title' => __('Full Name','adn'),
                                                                    'desc' => __('Provide your full name to create your advertiser account on our website.','adn'),
                                                                    'id' => 'bs_name', 
                                                                    'width' => '100%',
                                                                    'placeholder' => __('Firstname Lastname','adn'),
                                                                    'value' => $name,
                                                                    'icon' => 'pencil',
                                                                    'show_icon' => 1
                                                                ));  
                                                            $html.= '</div>
                                                        </div>
                                                    </div>';
                                                $html.= '</div>'; 
                                                // end .spr_column

                                                $html.= '<div class="spr_column spr_col-6">';
                                                    $html.= '<div class="spr_column-inner left_column">
                                                        <div class="spr_wrapper">
                                                            <div class="input_container">';
                                                                $html.= ADNI_Templates::inpt_cont(array(
                                                                    'title' => __('Email Address','adn'),
                                                                    'desc' => __('Provide a valid email address to activate your account and receive status updates about your banners.','adn'),
                                                                    'id' => 'bs_email',  
                                                                    'width' => '100%',
                                                                    'placeholder' => __('email','adn'),
                                                                    'value' => $email,
                                                                    'icon' => 'pencil',
                                                                    'show_icon' => 1
                                                                ));
                                                            $html.= '</div>
                                                        </div>
                                                    </div>';
                                                $html.= '</div>'; 
                                                // end .spr_column

                                            
                                                // PAYMENT OPTIONS
                                                $html.= '<div class="clearFix"></div>';

                                                $payment_options_html = '';
                                                $payment_options_html.= '<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Payment Option','adn').'</strong></span></div>';
                                            
                                                $active_payment = 0;
                                                if( !empty($sell_settings['payment']))
                                                {
                                                    $i = 0;
                                                    foreach( $sell_settings['payment'] as $key => $payment)
                                                    {
                                                        $form_item = ADNI_Sell::sell_payment_option_forms($sell_settings, $key);

                                                        if( $payment['active'] && $key !== 'woocommerce' )
                                                        {
                                                            $active_payment = 1;
                                                            $selected = !$i ? ' selected' : '';
                                                            $payment_options_html.= '<div class="spr_column spr_col-1">';
                                                                $payment_options_html.= '<div class="spr_column-inner left_column">
                                                                    <div class="spr_wrapper">
                                                                        <div class="input_container">';

                                                                            $payment_options_html.= '<a class="payment_btn ttip'.$selected.'" title="'.$form_item['title'].'" data-opt="'.$key.'">';
                                                                                $payment_options_html.= $form_item['logo'];
                                                                            $payment_options_html.= '</a>';

                                                                            $payment_options_html.= '<span class="description bottom">'.__('','adn').'</span>';
                                                                        $payment_options_html.= '</div>
                                                                    </div>
                                                                </div>';
                                                            $payment_options_html.= '</div>'; 
                                                            // end .spr_column 

                                                            $i++;
                                                        }
                                                    }
                                                }

                                                $html.= apply_filters('ADNI_sell_payment_options', $payment_options_html, $sell_settings);
                                                $active_payment = apply_filters('ADNI_sell_active_payment', $active_payment, $key);

                                                $html.= !$active_payment ? '<div class="clearFix"></div><div class="input_container"><p>'.__('Sorry, no payment options are currently available. Please contact the website administration.','adn').'</p></div>' : '';
                                                

                                                // CONFIRMATION
                                                if( $active_payment )
                                                {
                                                    $html.= '<div class="clearFix"></div>';
                                                    $html.= '<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.__('Confirmation','adn').'</strong></span></div>';
                                                    $html.= '<div class="spr_column spr_col">';
                                                        $html.= '<div class="spr_column-inner left_column">
                                                            <div class="spr_wrapper">
                                                                <div class="input_container">';
                                                                    $html.= '<p>'.sprintf(__('Purchase ad spot in the %s adzone <small>(ID: %s)</small>','adn'), '<strong>'.$adzone_info['name'].'</strong>', $adzone_id).'</p>';
                                                                    $html.= '<ul style="margin-left: 20px;">';
                                                                        $html.= '<li>'.__('Price:','adn').' <strong><span id="bs_conf_price">'.$adzone_info['price'].'</span> '.$sell_settings['cur'].'</strong></li>';
                                                                        $html.= '<li>'.__('Contract:','adn').' <strong>'.self::contract_line(array('contract' => $adzone_info['contract'], 'duration' => $adzone_info['duration'])).'</strong></li>';
                                                                            
                                                                    $html.= '</ul>';
                                                                    $html.= '<div class="bs_confirmation_notice" style="margin-top: 20px;"></div>';
                                                                    $html.= '<a id="_ning_confirm_order" class="button-primary">'.__('Confirm & proceed to payment','adn').'</a>';
                                                                    
                                                                    $html.= '<span class="description bottom">'.__('','adn').'</span>';
                                                                $html.= '</div>
                                                            </div>
                                                        </div>';
                                                    $html.= '</div>'; 
                                                    // end .spr_column   
                                                }
                                            }
                                            else
                                            {
                                                $html.= '<div class="spr_column spr_col">';
                                                        $html.= '<div class="spr_column-inner left_column">
                                                            <div class="spr_wrapper">
                                                                <div class="input_container">';
                                                                    $html.= '<div class="ning_login_box">';
                                                                        $html.= '<a href="'.wp_login_url( $_SERVER['REQUEST_URI'] ).'" title="'.__('Login','adn').'">'.__('Please login to continue.','adn').'</a>';
                                                                    $html.= '</div>';
                                                                    $html.= '<span class="description bottom"></span>';
                                                                $html.= '</div>
                                                            </div>
                                                        </div>
                                                    </div>';
                                                $html.= '</div>';
                                            }
                                    
                                        $html.= '</div>';
                                        // end .spr_row

                                    $html.= '</div>';
                                    // end .option_box

                                $html.= '</div>
                            </div>
                        </div>
                    </div>';
                    // end .spr_row

                    
                    // More available adzones
                    $html.= '<div style="margin:50px 0 0 0;">';
                        $html.= self::all_available_adzones(array('title' => __('More Advertising Options','adn'), 'exclude' => array($adzone_id)));
                    $html.= '</div>';
                
                $html.= '</div>';
                // end ._ning_order_form
                
                
                
                
                
                
                // JS
                // 
                unset($adzone_info['rotation_icon']);

                $html.= '<script type="text/javascript">';
                    $html.= 'jQuery(document).ready(function($){
                        $(".payment_btn").on("click", function(){
                            $(".payment_btn").removeClass("selected");
                            $(this).addClass("selected");
                        });

                        $("#_ning_confirm_order").on("click", function(){
                            $(".loading_overlay").show();

                            var data = {
                                payment: $(".payment_btn.selected").data("opt"),
                                ip_adress: "'.ADNI_Main::get_visitor_ip().'", 
                                name: $("#bs_name").val(),
						        email: $("#bs_email").val()
                            };

                            // Check if all data is valid to proceed.
                            var proceed = 1;
                            proceed = !Adning_global.is_valid_email(data["email"]) ? 0 : proceed;
                            proceed = data["name"] == "" ? 0 : proceed;

                            if( proceed ){
                                //console.log("PROCEED", data.payment, encodeURIComponent(JSON.stringify(data)));
                                $.ajax({
                                    type: "POST",
                                    url: "'.ADNI_AJAXURL.'",
                                    data: "action=send_payment&adzone_info='.addslashes(json_encode($adzone_info)).'&data="+encodeURIComponent(JSON.stringify(data))
                                }).done(function( obj ) {
                                    //console.log("DONE", obj);
                                    //msg = JSON.parse( obj );
                                    $(".bs_confirmation_notice").html(obj);
                                    $( "#_ning_pmt_send" ).submit();      

                                    if( data.payment === "bank-transfer" ){
                                        $(".loading_overlay").hide();
                                        $(".adzone_order_form").html(obj);
                                    }
                                });
                            }else{
                                $(".loading_overlay").hide();
                                $(".bs_confirmation_notice").html("Please provide your full name and valid email address.");
                            }
                            
                        });
                    });';
                $html.= '</script>';
            }
		}
		else
		{
            //$html.= __('Sorry, the adzone you are looking for cannot be found.','adn');	
			$html.= self::all_available_adzones();
		}
		
		return $html;
	}






    /**
	 * FOOTER FAQ INFO
	 */
	public static function faq_info_footer()
	{
		$html = '';
		$html.= '<div class="fq_inf rotation one_third">';
			$html.= '<span class="title">'.__('Ad will rotate with other ads','adn').'</span>';
			$html.= '<span class="desc">'.__('When you see this icon, it means your ad will rotate with other ads in the adzone.','adn').'</span>';
		$html.= '</div>';
		
		$html.= '<div class="fq_inf adblockers one_third">';
			$html.= '<span class="title">'.__('What about ad blockers?','adn').'</span>';
			$html.= '<span class="desc">'.__('No worries! We have them under control, If however your ad would be detected by an adblocker impressions will not be counted.','adn').'</span>';
		$html.= '</div>';	
		
		return $html;
    }
    



    /**
     * Return paypal form to Send Payment 
     * form can be send automatically using .submit() or using the button
     */
    public static function paypal($ipn_data, $args = array())
	{
		$defaults = array(
            'id'           => 0, //adzone_id
            'form_id'      => '_ning_pmt_send',
			'price'        => 10,
			'form_style'   => '',
            'ipn_data'     => '',
            'show_btn'     => 0,
			'submit_btn'   => __('Buy Now','adn'),
			'button_class' => 'button-secondary',
			'notify'       => 'IPN',
			'return'       => '_ning_purchase'
		);
		$args = wp_parse_args( $args, $defaults );
		
        $data = self::sell_main_settings();
        $data = $data['sell'];
        
        $adning_paypal_ipn = new ADNI_Paypal_IPN();
		$paypal_url = $adning_paypal_ipn->wp_tuna_get_paypal_redirect( $data['payment']['paypal']['sandbox'] );
        $rand = substr(uniqid('', true), -5);
        $form_id = !empty($args['form_id']) ? ' id="'.$args['form_id'].'"' : '';
		$form_style = !empty($args['form_style']) ? ' style="'.$args['form_style'].'"' : '';
		$html = '';
		
		$html.= '<form'.$form_id.' action="'.$paypal_url.'" method="post"'.$form_style.'>'; // '.$rand.'_'.$args['id'].'
			$html.= '<input type="hidden" name="cmd" value="_xclick">';
			$html.= '<input type="hidden" name="business" value="'.$data['payment']['paypal']['email'].'">';
			$html.= '<input type="hidden" name="item_name" value="'.sprintf(__('Payment for Adzone on %s'), get_bloginfo('name')).'">';
			$html.= '<input type="hidden" name="currency_code" value="'.$data['cur'].'">';
			$html.= '<input type="hidden" name="amount" class="amount" value="'.$ipn_data['price'].'">';
			$html.= '<input type="hidden" name="notify_url" value="'.trailingslashit( home_url( 'index.php' ) ).'?_ning-pp-ipn='.$args['notify'].'" />';
            //$html.= '<input type="hidden" name="return" value="'.trailingslashit( home_url( 'index.php' ) ).'?_ning_front=1&view=user_dashboard&'.$args['return'].'='.$ipn_data['aid'].'">';
            $urlf = strpos($data['urls']['user_dashboard'], '?') !== false ? '&' : '?';
            $html.= '<input type="hidden" name="return" value="'.$data['urls']['user_dashboard'].$urlf.$args['return'].'='.$ipn_data['aid'].'">';
			$html.= '<input type="hidden" name="custom" value="'.urlencode(addslashes(json_encode($ipn_data))).'">';
			$html.= $args['show_btn'] ? '<input type="submit" class="'.$args['button_class'].'" value="'.$args['submit_btn'].'">' : '';
		$html.= '</form>';	
		
        return $html;
    }
    


    /**
     * Send Payment AJAX function
     */
    public static function send_payment()
    {
        global $wpdb;

        $h = '';
        $data = json_decode(json_encode(json_decode(stripslashes($_POST['data']))), true);
        $adzone_info = json_decode(json_encode(json_decode(stripslashes($_POST['adzone_info']))), true);
        $payment = $data['payment'];

        //echo stripslashes($_POST['adzone_info']);
        //print_r( json_decode(stripslashes($_POST['adzone_info']) ));

        $ipn_data = array(
            'aid' => $adzone_info['id'],
            'bid' => 0,
            'price' => $adzone_info['price'],
            'email' => $data['email'],
            'type' => 'new',
            'order_id' => 0
        );

        // Check if user has an account
        $user = get_user_by( 'email', $data['email'] );
        $wpuser_id = $user ? $user->ID : 0;

        // Add new order to database.
        self::check_if_table_exists( 'adning_sell' );
        $wpdb->query("INSERT INTO " . $wpdb->prefix . "adning_sell 
            SET 
                time = '".current_time('timestamp')."',
                user_id = '".$wpuser_id."',
                email = '".$data['email']."',
                ip_address = '".$data['ip_adress']."',
                adzone_id = '".$adzone_info['id']."',
                status = 0,
                price = '".$adzone_info['price']."',
                provider = '".$payment."'
        ");
        $ipn_data['order_id'] = $wpdb->insert_id;
        
        
        if( $payment === 'paypal')
        {
            $h.= self::paypal($ipn_data);
        }
        if( $payment === 'bank-transfer')
        {
            $sell_settings = self::sell_main_settings();
            $sell_settings = $sell_settings['sell']['payment'][$payment];

            $h.= '<div class="spr_row" style="position:relative;">';
                $h.= '<div class="spr_column spr_col-6">';
                    $h.= '<div class="spr_column-inner left_column">
                        <div class="spr_wrapper">
                            <div class="input_container">';
                                $h.= '<h2>'.sprintf(__('Your order (#%s) has been received.','adn'), $ipn_data['order_id']).'</h2>';
                                $h.= '<p>'.$sell_settings['desc'].'</p>';
                            $h.= '</div>
                        </div>
                    </div>';
            $h.= '</div>';
        }

        //echo print_r($adzone_info,true);
        echo apply_filters('ADNI_sell_send_payment', $h, $ipn_data, $payment);
        exit;
    }





    /**
	 * RECEIVE PAYMENT - PAYPAL, Stripe
	 *
	 * @args: $data (array), $provider (string)
	 * @access public
	 * @return array
	 */
	public static function receive_payment( $data = array(), $provider = 'paypal' )
	{
        global $wpdb;

        if( !empty($data))
		{
            $all_ok = 0;
            $pay_arr = array();

            if($provider === 'paypal')
            {
                $adning_paypal_ipn = new ADNI_Paypal_IPN();
                $adning_paypal_ipn->log_add('received : ' . print_r($data, true));

                $args = json_decode(json_encode(json_decode(stripslashes(urldecode($data['custom'])))), true);
                $adning_paypal_ipn->log_add('custom : ' . print_r($args, true));

                // Fully paid
			    if( $data['payment_amount'] >= $args['price'] )
			    {
                    $pay_arr = array(
                        'type' => $args['type'],
                        'adzone_id' => $args['aid'],
                        'banner_id' => $args['bid'],
                        'order_id' => $args['order_id'],
                        'price' => $args['price'],
                        'email' => $args['email'],
                        'transaction' => $data['txn_id'],
                        'am_paid' => $data['payment_amount'],
                        'trans_date' => current_time('timestamp')
                    );
                    $all_ok = 1;
                }
            }
            else
            {
                $pay_arr = apply_filters('ADNI_sell_receive_payment', $data, $pay_arr, $provider);
                $all_ok = array_key_exists('all_ok', $pay_arr) ? $pay_arr['all_ok'] : 0;
            }


            // Payment successfull, create data
            if( $all_ok && !empty($pay_arr) )
            {
                $adzone = ADNI_CPT::load_post($pay_arr['adzone_id'], array('post_type' => ADNI_CPT::$adzone_cpt, 'filter' => 0));
                $adzone_info = self::adzone_details($adzone);
                $order_status = $adzone_info['review'] ? 'draft' : 'active';
            
                // Update order
                $wpdb->query("UPDATE " . $wpdb->prefix . "adning_sell  
                    SET 
                        status = '".$order_status."',
                        transaction = '".$pay_arr['transaction']."',
                        trans_date = '".$pay_arr['trans_date']."',
                        am_paid = '".$pay_arr['am_paid']."'
                    WHERE id = '".$pay_arr['order_id']."' 
                ");

                // if renewing
                if( $pay_arr['type'] === 'renew' )
                {
                    self::renew_contract_activation($adzone, $pay_arr);
                }
            }
        }
    }





    /**
	 * Renew Contract Activation - after succesfull payment.
	 *
	 */
    public static function renew_contract_activation($adzone = array(), $args = array())
    {
        $banner = ADNI_CPT::load_post($args['banner_id'], array('post_type' => ADNI_CPT::$banner_cpt, 'filter' => 0));
        //$b_args = ADNI_Main::parse_args(array('status' => 'active'), $banner['args']);
        $banner['args']['status'] = 'active';
        $b_args = ADNI_Multi::update_post_meta($args['banner_id'], '_adning_args', $banner['args']);

        ADNI_CPT::add_banner_to_adzone($args['adzone_id'], $args['banner_id']);
        ADNI_CPT::add_adzone_to_banner($args['adzone_id'], $args['banner_id']);
    }



    /**
     * Pending order count
     */
    public static function pending_order_count()
    {
        $orders = self::load_order(array('query' => "WHERE status = 'draft'"));

        return count($orders);
    }

    /**
     * Pending order notifications count balloon, 
     * for Filter ADNI_noti_balloon
     */
    public static function count_order_notifications($count)
    {
        return $count + self::pending_order_count();
    }



    /**
     * Remove Order
     */
    public static function remove_order($id)
    {
        $order = self::load_order(array('query' => "WHERE id = '".$id."' LIMIT 1"));
        if(!empty($order))
        {
            global $wpdb;

            ADNI_CPT::remove_banner_from_adzone($order[0]->adzone_id, $order[0]->banner_id);
            ADNI_CPT::remove_adzone_from_banner($order[0]->adzone_id, $order[0]->banner_id);

            wp_trash_post($order[0]->banner_id); 
            wp_delete_post($order[0]->banner_id, true); 

            $wpdb->query("DELETE FROM ".$wpdb->prefix . "adning_sell WHERE id= ".$id.";");
        }
    }



    /**
     * Manually Activate Order
     */
    public static function activate_order($id)
    {
        $order = self::load_order(array('query' => "WHERE id = '".$id."' LIMIT 1"));
        if(!empty($order))
        {
            global $wpdb;

            $adzone = ADNI_CPT::load_post($order[0]->adzone_id, array('post_type' => ADNI_CPT::$adzone_cpt, 'filter' => 0));
            $adzone_info = self::adzone_details($adzone);
            $order_status = $adzone_info['review'] ? 'draft' : 'active';

            $wpdb->query("UPDATE ".$wpdb->prefix . "adning_sell SET status = '".$order_status."' WHERE id= ".$id.";");
        }
    }



    /**
	 * PAYPAL ACCEPTED CURRENCIES
	 * https://developer.paypal.com/docs/classic/api/currency_codes/#paypal
	 */
	public static function currencies()
	{
		$cur = array(
			'EUR' => array('value' => 'EUR', 'text' => 'Euro - EUR'),
			'USD' => array('value' => 'USD', 'text' => 'United States Dollars - USD'),
			'GBP' => array('value' => 'GBP', 'text' => 'United Kingdom Pounds - GBP'),
			'CAD' => array('value' => 'CAD', 'text' => 'Canada Dollars - CAD'),
			'AUD' => array('value' => 'AUD', 'text' => 'Australia Dollars - AUD'),
			'NZD' => array('value' => 'NZD', 'text' => 'New Zealand Dollars - NZD'),
			'JPY' => array('value' => 'JPY', 'text' => 'Japan Yen - JPY'),
			'INR' => array('value' => 'INR', 'text' => 'India Rupees - INR'),
			'CHF' => array('value' => 'CHF', 'text' => 'Switzerland Francs - CHF'),
			'ZAR' => array('value' => 'ZAR', 'text' => 'South Africa Rand - ZAR'),
			'DKK' => array('value' => 'DKK', 'text' => 'Denmark Kroner - DKK'),
			'CZK' => array('value' => 'CZK', 'text' => 'Czech Republic Koruny - CZK'),
			'HKD' => array('value' => 'HKD', 'text' => 'Hong Kong Dollars - HKD'),
			'HUF' => array('value' => 'HUF', 'text' => 'Hungary Forint - HUF'),
			'ILS' => array('value' => 'ILS', 'text' => 'Israel New Shekels - ILS'),
			'MXN' => array('value' => 'MXN', 'text' => 'Mexico Pesos - MXN'),
			'NOK' => array('value' => 'NOK', 'text' => 'Norway Kroner - NOK'),
			'PLN' => array('value' => 'PLN', 'text' => 'Poland Zlotych - PLN'),
			'SEK' => array('value' => 'SEK', 'text' => 'Swedish Krona - SEK'),
			'SGD' => array('value' => 'SGD', 'text' => 'Singapore Dollars - SGD'),
			'BRL' => array('value' => 'BRL', 'text' => 'Brazilian Real - BRL'),
			'PHP' => array('value' => 'PHP', 'text' => 'Philippine Peso - PHP'),
			'TWD' => array('value' => 'TWD', 'text' => 'Taiwan New Dollar - TWD'),
			'THB' => array('value' => 'THB', 'text' => 'Thai Baht - THB')
		);
		
        return apply_filters('ADNI_currencies', $cur);
        
        /*
        add_filter('ADNI_currencies', 'your_function');
        function your_function($cur){
            // adjust $cur;
            return $cur;
        }
        */
	}



    /*
	 * Check if the table exists and if not Create the database table.
	 *
	 * @access public
	 * @return void
	*/
    public static function check_if_table_exists($table = '')
    {
        if( !empty($table) )
        {
            global $wpdb;

            $table_name = $wpdb->prefix . $table;
            if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                self::create_tables();
            }
        }
    }


    /*
	 * Create the database tables the plugin needs to function.
	 *
	 * @access public
	 * @return void
	*/
	public static function create_tables() 
	{
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$wpdb->hide_errors();
		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) 
		{
			if ( ! empty($wpdb->charset ) ) 
			{
				$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
			}
			if ( ! empty($wpdb->collate ) )
			{
				$collate .= " COLLATE $wpdb->collate";
			}
		}
		
		
		// Sell database
		$sql_adning_sell = "CREATE TABLE " . $wpdb->prefix . "adning_sell (
			id int(11) NOT NULL AUTO_INCREMENT,
			time int(11) NOT NULL,
			user_id int(11) NOT NULL,
			email VARCHAR( 80 ) NOT NULL,
			ip_address VARCHAR( 50 ) NOT NULL,
            adzone_id mediumint(9) NOT NULL,
            banner_id mediumint(9) NOT NULL,
			status VARCHAR( 50 ) NOT NULL,
			price VARCHAR(50) NOT NULL,
			am_paid VARCHAR(50) NOT NULL,
			pos VARCHAR(50) NOT NULL,
			trans_date VARCHAR(100) NOT NULL,
			transaction VARCHAR(200) NOT NULL,
			provider VARCHAR( 50 ) NOT NULL,
			UNIQUE KEY id (id),
			KEY time (time)
		) ".$collate.";";
		dbDelta( $sql_adning_sell );
    }
    



    /*
	 * Time Ago - Relative timing
	 *
	 * @access public
	 * @param time $ptime
	 * @return string
	*/
	public static function time_ago( $ptime )
	{
        $etime = current_time( 'timestamp' ) - $ptime;
		
		if ($etime < 1) {
			return '0 '.__('seconds','adn');
		}
		
		$a = array( 12 * 30 * 24 * 60 * 60  =>  array(__('year','adn'), __('years','adn')),
					30 * 24 * 60 * 60       =>  array(__('month','adn'), __('months','adn')),
					//7 * 24 * 60 * 60        =>  'week',
					24 * 60 * 60            => array(__('day','adn'), __('days','adn')),
					60 * 60                 =>  array(__('hour','adn'), __('hours','adn')),
					60                      =>  array(__('minute','adn'), __('minutes','adn')),
					1                       =>  array(__('second','adn'), __('seconds','adn'))
					);
		
		foreach ($a as $secs => $str) {
			$d = $etime / $secs;
			if ($d >= 1) {
				$r = round($d);
				return $r . ' ' . ($r > 1 ? $str[1] : $str[0]);
			}
		}
    }
    




    

}
endif;
?>