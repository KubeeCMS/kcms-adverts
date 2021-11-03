<?php
$h = '';

if( !is_user_logged_in() )
{
    $h.= '<div style="margin-top:50px;text-align:center;">'.esc_attr__('Please login to access this area.','adn').'</div>';
    return;
}


//echo 'oi'.function_exists( 'is_amp_endpoint' );

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = !$id && isset($_POST['post_id']) ? $_POST['post_id'] : $id;
$is_frontend = isset($is_frontend) ? $is_frontend : 0;
$user_id = get_current_user_id();
$notice = array();

if( !current_user_can(ADNI_BANNERS_ROLE) && empty($id) )
{
    // If user is purchasing a banner it's ok
    // If not EXIT!
    if( !isset($_GET['oid']) && !isset($_GET['slid']) )
    {
        $h.= '<div style="margin-top:50px;text-align:center;">'.esc_attr__('Sorry you cannot access this area.','adn').'</div>';
        return;
    }
}


if( isset($_GET['reset_stats']) && !empty($_GET['reset_stats']))
{
    ADNI_Main::reset_stats($id, 'id_1');
}

//echo ADNI_Filters::get_country( ADNI_Main::get_visitor_ip() );

// Create draft post ( just to get a banner ID )
if( !$id )
{
    $id = ADNI_CPT::add_update_post(
        apply_filters('ADNI_new_banner_args', array(
            'post_type' => ADNI_CPT::$banner_cpt,
            'post_id' => 0,
            'post_title' => '',
            'responsive' => 1,
            'df_show_desktop' => 1,
            'df_show_tablet' => 1,
            'df_show_mobile' => 1
        )),
        'draft'
    );

    // Add new ID to url
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $urlf = strpos($current_url, '?') !== false ? '&' : '?';
    $h.= '<script>var url = "'.$current_url.$urlf.'id='.$id.'";document.location = url;</script>';
    //$h.= '<script>var url = document.location.href+"&id='.$id.'";document.location = url;</script>';
}


$banner_post = array();
//$default_banner_content = '<div style="display:table;text-align:center;height:100%;width:100%;"><span style="display: table-cell;vertical-align:middle;">Advertise Here</span></div>';
$default_banner_content = '';

/**
 * POST
*/
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['save_banner']))
	{
        /*
		 * Filter: 'ADNI_save_banner' - Allow other plugins to adjust $_POST variable.
        */
        $post = apply_filters('ADNI_save_banner', $_POST);
        

        //echo '<pre>'.print_r($post,true).'</pre>';
        $id = ADNI_CPT::add_update_post($post);
        $notice[] = __('Banner saved successfully.','adn');
	}
}

$set_arr = ADNI_Main::settings();
$settings = $set_arr['settings'];


/*
 * Load Post data or default values
*/
$banner_post = ADNI_CPT::load_post($id, array('post_type' => ADNI_CPT::$banner_cpt, 'filter' => 0));

if( empty( $banner_post['post'] ) || !current_user_can(ADNI_ALL_BANNERS_ROLE) && $user_id != $banner_post['post']->post_author)
{
    $h.= '<div style="margin-top:50px;text-align:center;">'.esc_attr__('Sorry, This banner does not exists.','adn').'</div>';
    return $h;
}

//$b = apply_filters('ADNI_single_banner_args', $banner_post['args'], $id, $_GET);
$b = $banner_post['args'];
//echo '<pre>'.print_r($banner_post,true).'</pre>';
//echo '<pre>'.print_r($b,true).'</pre>';
//print_r( get_post_meta($id, '_adning_size') );


/**
 * Check if user has access to this banner
*/
if( !empty( $banner_post['post'] ))
{
    ADNI_CPT::user_has_access(array(
        'id' => $id,
        'author' => $banner_post['post']->post_author,
        'post_type' => ADNI_CPT::$banner_cpt
    ));
}

$frontend_class = $is_frontend ? ' adning_frontend' : '';
$h.= '<div class="adning_cont adning_add_new_banner'.$frontend_class.'">';
    $h.= '<div class="wrap">';
    
    	//<!-- Wordpress Messages -->
        $h.= '<h2 class="messages-position"></h2>';
        
        $h.= ADNI_Templates::admin_header();

        if(!empty($notice))
        {
            foreach($notice as $note)
            {
                $h.= '<div class="notice">'.$note.'</div>';
            }
        }

        
        /*
         * Filter: 'adning_single_banner_notice' - Allow other plugins to add notices to the banner page.
        */
        $h.= apply_filters('adning_single_banner_notice', $banner_post, $is_frontend);
        $h.= apply_filters('adning_general_notice', $is_frontend);
        
        
        $h.= '<form action="" method="post" enctype="multipart/form-data">';
            $h.= '<input type="hidden" value='.$id.' name="post_id">';
            $h.= '<input type="hidden" value="'.ADNI_CPT::$banner_cpt.'" name="post_type">';
            
            $h.= '<div class="spr_row">';
        	    $h.= '<div class="spr_column spr_col-4">';
                    $h.= '<div class="spr_column-inner left_column">';
                        $h.= '<div class="spr_wrapper">';
                            $h.= '<div class="option_box">';
                                $h.= '<div class="info_header">';
                                    $h.= '<span class="nr"><svg viewBox="0 0 512 512"><path fill="currentColor" d="M507.73 109.1c-2.24-9.03-13.54-12.09-20.12-5.51l-74.36 74.36-67.88-11.31-11.31-67.88 74.36-74.36c6.62-6.62 3.43-17.9-5.66-20.16-47.38-11.74-99.55.91-136.58 37.93-39.64 39.64-50.55 97.1-34.05 147.2L18.74 402.76c-24.99 24.99-24.99 65.51 0 90.5 24.99 24.99 65.51 24.99 90.5 0l213.21-213.21c50.12 16.71 107.47 5.68 147.37-34.22 37.07-37.07 49.7-89.32 37.91-136.73zM64 472c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"></path></svg></span>';
                            	    $h.= '<span class="text">'.esc_attr__('Banner Settings','adn').'</span>';
                                $h.= '</div>';
                                $h.= '<div class="input_container">';
                                    $h.= '<h3 class="title">'.esc_attr__('Title','adn').'</h3>';

                                    $val = !empty($banner_post['post']) ? $banner_post['post']->post_title : '';
                                    $h.= '<div class="input_container_inner">
                                            <input 
                                            type="text" 
                                            class="" 
                                            name="title" 
                                            value="'.$val.'" 
                                            placeholder="'.esc_attr__('Banner Title','adn').'">
                                        <i class="input_icon fa fa-pencil" aria-hidden="true"></i>
                                    </div>';
                                    $h.= '<span class="description bottom">'.esc_attr__('Add a banner title.','adn').'</span>';
                                $h.= '</div>';
                                //<!-- end .input_container -->

                            
                                if( current_user_can(ADNI_ALL_BANNERS_ROLE) )
                                {
                                    global $current_user;
                                    $advertiser_id = !empty($banner_post['post']) ? $banner_post['post']->post_author : $current_user->ID;
                                    
                                    // (optional) add user role option to limit the amount of users getting loaded.
                                    // http://tunasite.com/?p=14314#reply-14392
                                    $filter_role = !empty($settings['filters']['advertiser_role']) ? 'role='.$settings['filters']['advertiser_role'] : '';
                                    $all_users = get_users($filter_role); 
                                    
                                    $h.= '<div class="input_container">';
                                        $h.= '<h3 class="title">'.esc_attr__('Advertiser','adn').'</h3>';
                                        $h.= '<select name="advertiser" data-placeholder="'.__('Select Advertiser','adn').'" class="chosen-select">';
                                            
                                            foreach($all_users as $user)
                                            {
                                                $selected = $user->ID == $advertiser_id ? ' selected' : '';
                                                $h.= '<option value="'.$user->ID.'"'.$selected.'>'.$user->display_name.' (#'.$user->ID.')</option>';
                                            }
                                           
                                        $h.= '</select>';
                                            
                                        $h.= '<span class="description bottom">'.esc_attr__('Assign this banner to a specific advertiser.','adn').'</span>';
                                    $h.= '</div>';
                                    //<!-- end .input_container -->
                                }
                                

                                $h.= '<div class="input_container">';
                                    $h.= '<h3 class="title">'.esc_attr__('Status','adn').'</h3>';
                                        $h.= '<div class="input_container_inner">';
                                           
                                            if( current_user_can(ADNI_BANNERS_ROLE) )
                                            {
                                                $h.= '<select name="status" class="">';
                                                    $h.= '<option value="active" '.selected( $b['status'], 'active', false).'>'.esc_attr__('Active','adn').'</option>';
                                                    $h.= '<option value="expired" '.selected( $b['status'], 'expired', false).'>'.esc_attr__('Expired','adn').'</option>';
                                                    $h.= '<option value="draft" '.selected( $b['status'], 'draft', false).'>'.esc_attr__('Draft','adn').'</option>';
                                                    $h.= '<option value="review" '.selected( $b['status'], 'review', false).'>'.esc_attr__('Pending Review','adn').'</option>';
                                                    $h.= '<option value="on-hold" '.selected( $b['status'], 'on-hold', false).'>'.esc_attr__('On Hold','adn').'</option>';
                                                $h.= '</select>';
                                            }
                                            else
                                            {
                                                $h.= '<div style="font-size: 18px;">'.$b['status'].'</div>';
                                            }
                                           
                                        $h.= '</div>';
                                    $h.= '<span class="description bottom">'.esc_attr__('Banner status.','adn').'</span>';
                                $h.= '</div>';
                                //<!-- end .input_container -->
                             
                                $h.= '<div class="input_container">';
                                    $h.= '<h3 class="title">'.esc_attr__('URL','adn').'</h3>';
                                        $h.= '<div class="input_container_inner">';
                                        $h.= '<input 
                                            type="text" 
                                            class="" 
                                            name="banner_url" 
                                            value="'.esc_url($b['banner_url']).'" 
                                            placeholder="'.esc_attr__('http://','adn').'">
                                        <i class="input_icon fa fa-link" aria-hidden="true"></i>';
                                    $h.= '</div>';
                                    $h.= '<span class="description bottom">'.esc_attr__('Add a banner link (URL).','adn').'</span>';
                                $h.= '</div>';
                                //<!-- end .input_container -->
                             
                                $h.= '<div class="input_container">';
                                    $h.= '<h3 class="title">'.esc_attr__('Target','adn').'</h3>';
                                        $h.= '<div class="input_container_inner">';
                                            $h.= '<select name="banner_target" class="">';
                                                $h.= '<option value="_blank" '.selected( $b['banner_target'], '_blank', false).'>'.esc_attr__('_blank, Load in a new window.','adn').'</option>';
                                                $h.= '<option value="_self" '.selected( $b['banner_target'], '_self', false).'>'.esc_attr__('_self, Load in the same frame as it was clicked.','adn').'</option>';
                                                $h.= '<option value="_parent" '.selected( $b['banner_target'], '_parent', false).'>'.esc_attr__('_parent, Load in the parent frameset.','adn').'</option>';
                                                $h.= '<option value="_top" '.selected( $b['banner_target'], '_top', false).'>'.esc_attr__('_top, Load in the full body of the window.','adn').'</option>';
                                            $h.= '</select>';
                                        $h.= '</div>';
                                    $h.= '<span class="description bottom">'.esc_attr__('Banner link target.','adn').'</span>';
                                $h.= '</div>';
                                //<!-- end .input_container -->
                             
                            
                                $h.= '<div class="spr_row">';
                                    if( current_user_can(ADNI_BANNERS_ROLE) )
                                    {
                                        $h.= ADNI_Templates::spr_column(array(
                                            'col' => 'spr_col-6',
                                            'title' => esc_attr__('Link Masking','adn'),
                                            'desc' => '',
                                            'content' => ADNI_Templates::switch_btn(array(
                                                'name' => 'banner_link_masking',
                                                'tooltip' => esc_attr__('Turn Off link masking to link directly to the raw banner url, When turned off its not possible to save statistics for this banner.','adn'),
                                                'checked' => $b['banner_link_masking'],
                                                'value' => 1,
                                                'hidden_input' => 1,
                                                'chk-on' => esc_attr__('On','adn'),
                                                'chk-off' => esc_attr__('Off','adn'),
                                                'chk-high' => 0
                                            ))
                                        ));
                                    }
                                        
                                    $h.= ADNI_Templates::spr_column(array(
                                        'col' => 'spr_col-6',
                                        'title' => esc_attr__('No Follow','adn'),
                                        'desc' => '',
                                        'content' => ADNI_Templates::switch_btn(array(
                                            'name' => 'banner_no_follow',
                                            'tooltip' => esc_attr__('Add no Follow to banner link.','adn'),
                                            'checked' => $b['banner_no_follow'],
                                            'value' => 1,
                                            'hidden_input' => 1,
                                            'chk-on' => esc_attr__('On','adn'),
                                            'chk-off' => esc_attr__('Off','adn'),
                                            'chk-high' => 0
                                        ))
                                    ));
                                    
                                $h.= '</div>';
                                //<!-- end .spr_row -->
                             
                             
                             
                            $h.= '<div class="input_container">';
                                $h.= '<div class="input_container_inner">';
                                    $h.= '<div class="sep_line" style="margin:10px 0 20px 0;"><span><strong>'.esc_attr__('Save','adn').'</strong></span></div>';
                                    $h.= '<input type="submit" value="'.esc_attr__('Save Banner','adn').'" class="button-primary" name="save_banner" style="width: auto;">';
                                $h.= '</div>';
                                $h.= '<span class="description bottom"></span>';
                            $h.= '</div>';
                            //<!-- end .input_container -->
                                         
                        $h.= '</div>';
                        //<!-- end .option_box -->


                        
                        /**
                         * BANNER STATS
                        */
                        $h.= ADNI_Templates::stats_settings_tpl(array('id' => $id, 'frontend' => $is_frontend), $b);
                        
                        
                        /**
                         * CAMPAIGNS
                        */
                        $h.= current_user_can(ADNI_CAMPAIGNS_ROLE) ? ADNI_Templates::link_campaign_tpl($b) : '';
                        


                        /**
                         * ADZONES
                        */
                        $h.= current_user_can(ADNI_ADZONES_ROLE) ? ADNI_Templates::link_adzone_tpl($b) : '';


                        /**
                         * ALIGNMENT SETTINGS
                        */
                        $h.= current_user_can(ADNI_BANNERS_ROLE) ? ADNI_Templates::alignment_settings_tpl($b) : '';
                        

                        /**
                         * BORDER SETTINGS
                        */
                        $h.= current_user_can(ADNI_BANNERS_ROLE) ? ADNI_Templates::border_settings_tpl($b) : '';
                        

                        /**
                         * EXPORT BANNER
                        */
                        $h.= ADNI_Templates::export_tpl($banner_post);
                        
                    $h.= '</div>
                </div>
            </div>';
            //<!-- end .spr_column -->
            
            
            $h.= '<div class="spr_column spr_col-8">';
                $h.= '<div class="spr_column-inner">';
                    $h.= '<div class="spr_wrapper">';
                        $h.= '<div class="option_box">';
                            $h.= '<div class="info_header">';
                                $h.= '<span class="nr">';
                                    $h.= '<svg viewBox="0 0 576 512"><path fill="currentColor" d="M512 320s-64 92.65-64 128c0 35.35 28.66 64 64 64s64-28.65 64-64-64-128-64-128zm-9.37-102.94L294.94 9.37C288.69 3.12 280.5 0 272.31 0s-16.38 3.12-22.62 9.37l-81.58 81.58L81.93 4.76c-6.25-6.25-16.38-6.25-22.62 0L36.69 27.38c-6.24 6.25-6.24 16.38 0 22.62l86.19 86.18-94.76 94.76c-37.49 37.48-37.49 98.26 0 135.75l117.19 117.19c18.74 18.74 43.31 28.12 67.87 28.12 24.57 0 49.13-9.37 67.87-28.12l221.57-221.57c12.5-12.5 12.5-32.75.01-45.25zm-116.22 70.97H65.93c1.36-3.84 3.57-7.98 7.43-11.83l13.15-13.15 81.61-81.61 58.6 58.6c12.49 12.49 32.75 12.49 45.24 0s12.49-32.75 0-45.24l-58.6-58.6 58.95-58.95 162.44 162.44-48.34 48.34z"></path></svg>';
                                $h.= '</span>';
                                $h.= '<span class="text">'.esc_attr__('Banner','adn').'</span>';
                                $h.= '<span class="fa tog ttip" title="'.esc_attr__('Toggle box','adn').'"></span>';
                                $h.= '<input type="submit" value="'.esc_attr__('Save Banner','adn').'" class="button-primary" name="save_banner" style="width:auto;float:right;margin:8px;">';
                            $h.= '</div>';
                            
                            $h.= '<div class="settings_box_content">';

                                if( current_user_can(ADNI_BANNERS_ROLE) )
                                {
                                    $h.= '<div class="sep_line" style="margin:0 0 15px 0;"><span><strong>'.esc_attr__('Sizing','adn').'</strong></span></div>';
                                    $h.= '<div class="spr_column spr_col-4">';
                                        $h.= '<div class="spr_column-inner left_column">';
                                            $h.= '<div class="spr_wrapper">';
                                                $h.= '<div class="input_container">';
                                                    $h.= '<h3 class="title"></h3>';
                                                    $h.= '<div class="input_container_inner">';
                                                        $h.= '<select id="ADNI_size" name="size" class="">';
                                                            $banner_sizes = ADNI_Main::banner_sizes();
                                                            if( !empty($banner_sizes) )
                                                            {
                                                                foreach($banner_sizes as $size)
                                                                {
                                                                    $h.= '<option value="'.$size['size'].'" '.selected( $b['size'], $size['size'], false).'>'.$size['name'].' ('.$size['size'].')</option>';
                                                                }
                                                            }
                                                            $h.= '<option value="custom" '.selected( $b['size'], 'custom', false).'>'.esc_attr__('Custom','adn').'</option>';
                                                        $h.= '</select>';
                                                    $h.= '</div>';
                                                    $h.= '<span class="description bottom">'.esc_attr__('Select one of the common banner sizes.','adn').'</span>';
                                                $h.= '</div>';
                                                //<!-- end .input_container -->
                                            $h.= '</div>';
                                        $h.= '</div>';
                                    $h.= '</div>';
                                    //<!-- end .spr_column -->
                                    
                                    $h.= ADNI_Templates::spr_column(array(
                                        'col' => 'spr_col-2',
                                        'title' => '',
                                        'desc' => esc_attr__('Responsive','adn'),
                                        'content' => ADNI_Templates::switch_btn(array(
                                            'name' => 'responsive',
                                            'id' => 'ADNI_responsive',
                                            'tooltip' => esc_attr__('Responsive banner.','adn'),
                                            'checked' => $b['responsive'],
                                            'value' => 1,
                                            'hidden_input' => 1,
                                            'chk-on' => esc_attr__('On','adn'),
                                            'chk-off' => esc_attr__('Off','adn'),
                                            'chk-high' => 1
                                        ))
                                    ));
                                
                                    
                                    $h.= '<div class="spr_column spr_col-6">';
                                        $h.= '<div class="spr_column-inner">';
                                            $h.= '<div class="spr_wrapper">';

                                                $h.= ADNI_Templates::spr_column(array(
                                                    'col' => 'spr_col-6',
                                                    'title' => '',
                                                    'desc' => esc_attr__('width.','adn'),
                                                    'content' => ADNI_Templates::inpt_cont(array(
                                                            'type' => 'text',
                                                            'width' => '100%',
                                                            'class' => '_ning_custom_size',
                                                            'name' => 'size_w',
                                                            'id' => 'ADNI_size_w',
                                                            'value' => $b['size_w'],
                                                            'placeholder' => '',
                                                            'icon' => 'arrows-h',
                                                            'show_icon' => 1
                                                        ))
                                                ));
                                                $h.= ADNI_Templates::spr_column(array(
                                                    'col' => 'spr_col-6',
                                                    'title' => '',
                                                    'desc' => esc_attr__('height.','adn'),
                                                    'content' => ADNI_Templates::inpt_cont(array(
                                                        'type' => 'text',
                                                        'width' => '100%',
                                                        'class' => '_ning_custom_size',
                                                        'name' => 'size_h',
                                                        'id' => 'ADNI_size_h',
                                                        'value' => $b['size_h'],
                                                        'placeholder' => '',
                                                        'icon' => 'arrows-v',
                                                        'show_icon' => 1
                                                    ))
                                                ));
                                                
                                            $h.= '</div>
                                        </div>
                                    </div>';
                                    //<!-- end .spr_column -->
                                }



                                // <!-- BANNER CODE -->
                                $h.= '<div class="spr_column">';
                                    $h.= '<div class="spr_column-inner">';
                                        $h.= '<div class="spr_wrapper">';
                                        
                                            $h.= '<div class="sep_line" style="margin:0 0 5px 0;"><span><strong>'.esc_attr__('Preview','adn').'</strong></span></div>';
                                            $h.= '<div class="banner_holder clear" style="padding:20px;">';
                                                $h.= '<div class="banner_notice"></div>';
                                            
                                                $h.= ADNI_Templates::banner_tpl($id, array('add_url' => 0, 'filter' => 0, 'stats' => 0));
                                            $h.= '</div>';
                                            // <!-- end .banner_holder -->
                                            
                                            $h.= '<div class="sep_line" style="margin:0 0 25px 0;"><span><strong>'.esc_attr__('Content','adn').'</strong></span></div>';
                                            
                                            
                                            $h.= ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col',
                                                'title' => '',
                                                'desc' => esc_attr__('Upload banner content.','adn'),
                                                'content' => ADNI_Templates::file_upload(array(
                                                    'class' => 'HTML5Uploader',
                                                    //'data' => array('wpnonce' => wp_create_nonce( '_ning_upload_'.$id ))
                                                ))
                                            ));
                                            
                                            

                                            // IMGMCE Button
                                            if( class_exists('ImgMCE'))
                                            {
                                                $imgmce_logo = IMC_ASSETS_URL.'/images/logo_20.png';
                                                $h.= '<div class="spr_column spr_col-3 left_column">';
                                                    $h.= '<div class="spr_column-inner">';
                                                        $h.= '<div class="spr_wrapper">';
                                                            $h.= '<div class="input_container">';
                                                                $h.= '<h3 class="title"></h3>';
                                                                $h.= '<div class="input_container_inner">';
                                                                    $h.= '<a class="open_imgmce_button _imgMCE_btn">
                                                                        <div class="logo_holder"><svg x="0px" y="0px" width="19px" height="15px" viewBox="0 0 310 426">
                                                                        <g><g><g><g><path fill="#FFFF00" d="M237,225c-0.33,0-0.67,0-1,0c-53.73,59.93-108.85,118.49-163,178c-0.85,0.18-0.94-0.39-1-1
                                                                        c26.08-58.92,51.7-118.3,77-178C178,224.67,209.67,222.67,237,225z"></path></g><g></g></g></g><g><g><g><path fill="#FFFF00" d="M289,165c0,1.33,0,2.67,0,4c-17.86,18.48-34.64,38.03-52,57c-56,0-112,0-168,0c0-1,0-2,0-3
                                                                        c21.5-64.83,42.62-130.05,63-196c46.33,0,92.67,0,139,0c-27.25,45.75-54.78,91.22-81,138C223,165,256,165,289,165z"></path></g><g></g></g></g></g><g><g id="bottom_xA0_Image_1_"><g><g><path fill="#D7CB05" d="M149,225c0,1.33,0,2.67,0,4c-26.73,57.27-50.59,117.41-77,175c-16.02-1.31-37.3,2.63-50-2
                                                                        c25.93-59.4,52.98-117.69,79-177C117,225,133,225,149,225z"></path></g><g></g></g></g><g><g><g><path fill="#D7CB05" d="M133,27c0,1,0,2,0,3c-21.17,64.5-41.92,129.41-62,195c-16.33,0-32.67,0-49,0c0-1,0-2,0-3
                                                                        C43.17,157.5,63.92,92.59,84,27C100.33,27,116.67,27,133,27z"></path></g><g></g></g></g></g></svg></div>
                                                                        <div class="text_holder">imgMCE Editor</div>
                                                                    </a>';
                                                                    
                                                                $h.= '</div>';
                                                                $h.= '<span class="description bottom">'.esc_attr__('Open imgMCE editor.','adn').'</span>';
                                                            $h.= '</div>';
                                                            //<!-- end .input_container -->
                                                        $h.= '</div>';
                                                    $h.= '</div>';
                                                $h.= '</div>';
                                                //<!-- end .spr_column -->
                                            }
                                            
                                            if( !$is_frontend )
                                            {
                                                $h.= '<div class="spr_column spr_col-3 left_column">';
                                                    $h.= '<div class="spr_column-inner">';
                                                        $h.= '<div class="spr_wrapper">';
                                                            $h.= '<div class="input_container">';
                                                                $h.= '<h3 class="title"></h3>';
                                                                $h.= '<div class="input_container_inner">';
                                                                    $h.= '<button class="upload_image_button button" type="button" style="height: 30px;">'.esc_attr__('Wordpress Media', 'adn').'</button>';
                                                                $h.= '</div>';
                                                                $h.= '<span class="description bottom">'.esc_attr__('Upload banner image.','adn').'</span>';
                                                            $h.= '</div>';
                                                            //<!-- end .input_container -->
                                                        $h.= '</div>';
                                                    $h.= '</div>';
                                                $h.= '</div>';
                                                //<!-- end .spr_column -->
                                            }


                                            if( current_user_can(ADNI_BANNERS_ROLE) )
                                            {
                                                $h.= '<div class="spr_column spr_col-3 left_column">';
                                                    $h.= '<div class="spr_column-inner">';
                                                        $h.= '<div class="spr_wrapper">';
                                                            $h.= '<div class="input_container">';
                                                                $h.= '<h3 class="title"></h3>';
                                                                $h.= '<div class="input_container_inner">';
                                                                    $h.= '<button class="adsense_btn button" type="button" style="height: 30px;">';
                                                                        $h.= '<svg style="height: 13px;" viewBox="0 0 256 252" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid"><defs><linearGradient x1=".041%" y1="50.091%" x2="100.078%" y2="50.091%" id="a"><stop stop-color="#4284F0" offset="0%"/><stop stop-color="#4487F4" offset="100%"/></linearGradient><linearGradient x1="28.448%" y1="81.561%" x2="55.474%" y2="42.237%" id="b"><stop stop-color="#F5B406" offset="0%"/><stop stop-color="#F4B913" offset="100%"/></linearGradient></defs><path d="M254.2 154.4L209.4 252H102.3l58.6-126h75c14.6 0 24.3 15.1 18.3 28.4z" fill="#437DE6"/><path d="M235.9 127.8c6.3 0 12 3.1 15.4 8.4 3.4 5.3 3.8 11.8 1.2 17.5l-44.2 96.6H107.2l56.9-122.5h71.8zm0-1.8H163l-58.6 126h105l44.7-97.6c6.1-13.3-3.6-28.4-18.2-28.4z" fill="#196CEA"/><path d="M62.1 1.8s56.2 61 69.4 67.3L149.8 29c6.4-12.7-1.8-27.5-17.1-27.3l-70.6.1z" fill="url(#a)" transform="translate(102 126)"/><g><path d="M112.9 10.9L0 252h104.4L165 121.7 221.6 0H130c-7.3 0-14 4.2-17.1 10.9z" fill="url(#b)"/><path d="M218.8 1.8L163.4 121l-60.1 129.3H2.8L114.5 11.6c2.8-6 8.9-9.9 15.5-9.9h88.8v.1zm2.8-1.8H130c-7.3 0-14 4.2-17.1 10.9L0 252h104.4L165 121.7 221.6 0z" fill="#F3AA00"/></g></svg>';
                                                                        $h.= esc_attr__('Google AdSense', 'adn');
                                                                    $h.= '</button>';
                                                                $h.= '</div>';
                                                                $h.= '<span class="description bottom">'.esc_attr__('Adsense banner settings.','adn').'</span>';
                                                            $h.= '</div>';
                                                            //<!-- end .input_container -->
                                                        $h.= '</div>
                                                    </div>
                                                </div>';
                                                //<!-- end .spr_column -->


                                                // Link to WP POST EDITOR
                                                if( !$is_frontend )
                                                {
                                                    $h.= ADNI_Templates::spr_column(array(
                                                        'col' => 'spr_col-3',
                                                        'title' => '',
                                                        'desc' => esc_attr__('Wordpress post editor','adn'),
                                                        'content' => '<a href="post.php?post='.$id.'&action=edit" class="button-secondary" target="_blank">'.esc_attr__('Wordpress Editor','adn').'</a>'
                                                    ));
                                                }
                                            }
                                            
                                            
                                            
                                            $adsense_pub_id = is_array($b['adsense_settings']) && array_key_exists('pub_id', $b['adsense_settings']) && !empty($b['adsense_settings']['pub_id']) ? $b['adsense_settings']['pub_id'] : $settings['adsense_pubid'];
                                            $adsense_slot_id = is_array($b['adsense_settings']) && array_key_exists('slot_id', $b['adsense_settings']) ? $b['adsense_settings']['slot_id'] : '';
                                            $show_adsense_settings = !empty($adsense_slot_id) ? '' : ' style="display:none;"';
                                            
                                            $h.= '<div class="spr_column google_adsense_container"'.$show_adsense_settings.'>';
                                                $h.= '<div class="spr_column-inner">';
                                                    $h.= '<div class="spr_wrapper">';
                                                        $h.= '<div class="input_container clear">';
                                                            $h.= '<h3 class="title">'.esc_attr__('Google AdSense','adn').'</h3>';

                                                            $h.= ADNI_Templates::spr_column(array(
                                                                'col' => 'spr_col-4',
                                                                'title' => '',
                                                                'desc' => esc_attr__('AdSense pub ID.','adn'),
                                                                'content' => ADNI_Templates::inpt_cont(array(
                                                                    'type' => 'text',
                                                                    'width' => '100%',
                                                                    'class' => 'adsense_set adsense_pub_id',
                                                                    'name' => 'adsense_pubid',
                                                                    'value' => $adsense_pub_id,
                                                                    'placeholder' => 'pub-xxxxxxx',
                                                                    'icon' => 'pencil',
                                                                    'show_icon' => 1
                                                                ))
                                                            ));
                                                            $h.= ADNI_Templates::spr_column(array(
                                                                'col' => 'spr_col-4',
                                                                'title' => '',
                                                                'desc' => esc_attr__('AdSense ad slot ID.','adn'),
                                                                'content' => ADNI_Templates::inpt_cont(array(
                                                                    'type' => 'text',
                                                                    'width' => '100%',
                                                                    'class' => 'adsense_set adsense_slot_id',
                                                                    'name' => 'adsense_slotid',
                                                                    'value' => $adsense_slot_id,
                                                                    'placeholder' => 'xxxxxxx',
                                                                    'icon' => 'pencil',
                                                                    'show_icon' => 1
                                                                ))
                                                            ));
                                                            
                                                            $adsense_type = is_array($b['adsense_settings']) && array_key_exists('type', $b['adsense_settings']) ? $b['adsense_settings']['type'] : '';
                                                            
                                                            $h.= '<div class="spr_column spr_col-4"><div class="spr_column-inner"><div class="input_container_inner">';
                                                                $h.= '<select name="adsense_type" class="adsense_set adsense_type">';
                                                                    $h.= '<option value="" '.selected($adsense_type, '', false).'>'.esc_attr__('-- Select --','adn').'</option>';
                                                                    $h.= '<option value="normal" '.selected($adsense_type, 'normal', false).'>'.esc_attr__('Normal','adn').'</option>';
                                                                    $h.= '<option value="responsive" '.selected($adsense_type, 'responsive',false).'>'.esc_attr__('Responsive','adn').'</option>';
                                                                    $h.= '<option value="matched-content" '.selected($adsense_type, 'matched-content', false).'>'.esc_attr__('Responsive (Matched Content)','adn').'</option>';
                                                                    $h.= '<option value="link" '.selected($adsense_type, 'link', false).'>'.esc_attr__('Link ads','adn').'</option>';
                                                                    $h.= '<option value="link-responsive" '.selected($adsense_type, 'link-responsive', false).'>'.esc_attr__('Link ads (Responsive)','adn').'</option>';
                                                                    $h.= '<option value="in-article" '.selected($adsense_type, 'in-article', false).'>'.esc_attr__('InArticle','adn').'</option>';
                                                                    $h.= '<option value="in-feed" '.selected($adsense_type, 'in-feed', false).'>'.esc_attr__('InFeed','adn').'</option>';
                                                                $h.= '</select>';
                                                                $h.= '<span class="description bottom">'.esc_attr__('AdSense banner type','adn').'</span>';
                                                            $h.= '</div></div></div>';
                                                            //<!-- end .spr_column -->

                                                        
                                                            $h.= '<span class="description bottom"></span>';
                                                        $h.= '</div>';
                                                        //<!-- end .input_container -->
                                                    $h.= '</div>';
                                                $h.= '</div>';
                                            $h.= '</div>';
                                            //<!-- end .spr_column -->
                                            
                                            // SHould this be visible for user?
                                            $readonly = $is_frontend && !$settings['upload']['html_ads'] ? ' readonly' : '';
                                            $display = $is_frontend && !$settings['upload']['html_ads'] ? ' display:none;' : '';
                                            $cm_class = $is_frontend && !$settings['upload']['html_ads'] ? '' : 'code_editor';

                                            $h.= '<div class="spr_column">';
                                                $h.= '<div class="spr_column-inner">';
                                                    $h.= '<div class="spr_wrapper">';
                                                        $h.= '<div class="input_container">';
                                                            $h.= '<h3 class="title"></h3>';
                                                            $h.= '<div class="input_container_inner">';
                                                                $h.= '<textarea id="banner_content" class="'.$cm_class.'" name="banner_content" data-lang="htmlmixed" style="min-height:200px;font-size: 13px;'.$display.'"'.$readonly.'>'.$b['banner_content'].'</textarea>';
                                                            $h.= '</div>';
                                                            $h.= '<span class="description bottom">'.esc_attr__('Banner HTML content.','adn').'</span>';
                                                        $h.= '</div>';
                                                        // <!-- end .input_container -->
                                                    $h.= '</div>';
                                                $h.= '</div>';
                                            $h.= '</div>';
                                            //<!-- end .spr_column -->

                                            $h.= ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col-3',
                                                'title' => esc_attr__('Live Preview','adn'),
                                                'desc' => '',
                                                'content' => ADNI_Templates::checkbox(array(
                                                    'id' => 'dont_render_preview_code',
                                                    'tooltip' => esc_attr__('When using javascript banners you may need to turn of live preview code rendering.','adn'),
                                                    'checked' => 1,
                                                    'chk-on' => esc_attr__('Yes','adn'),
                                                    'chk-off' => esc_attr__('No','adn'),
                                                    'chk-high' => 0
                                                ))
                                            ));

                                            $h.= ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col-3',
                                                'title' => esc_attr__('Bg Color','adn'),
                                                'class' => 'small_coloringPick',
                                                'desc' => esc_attr__('Banner background color.','adn'),
                                                'content' => ADNI_Templates::inpt_cont(array(
                                                    'type' => 'text',
                                                    'class' => 'banner_bg_color',
                                                    'name' => 'bg_color',
                                                    'value' => $b['bg_color']
                                                )).
                                                "<script>jQuery(document).ready(function($){ $('.banner_bg_color').coloringPick( {'on_select': function(color){ $('.banner_holder').find('._ning_inner').css({'background': color}); } } ); });</script>"
                                            ));

                                            $h.= ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col-3',
                                                'title' => esc_attr__('Scale','adn'),
                                                'desc' => esc_attr__('Scale banner content.','adn'),
                                                'content' => ADNI_Templates::switch_btn(array(
                                                    'name' => 'banner_scale',
                                                    'id' => 'ADNI_scale',
                                                    'tooltip' => esc_attr__('Scale banner content to match resized banner container.','adn'),
                                                    'checked' => $b['banner_scale'],
                                                    'value' => 1,
                                                    'hidden_input' => 1,
                                                    'chk-on' => esc_attr__('On','adn'),
                                                    'chk-off' => esc_attr__('Off','adn'),
                                                    'chk-high' => 0
                                                ))
                                            ));

                                            $h.= ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col-3',
                                                'title' => esc_attr__('Container','adn'),
                                                'desc' => esc_attr__('Use the ultra responsive container template.','adn'),
                                                'content' => ADNI_Templates::switch_btn(array(
                                                    'name' => 'banner_container',
                                                    'id' => 'ADNI_container',
                                                    'tooltip' => esc_attr__('Use the ultra responsive container template.','adn'),
                                                    'checked' => $b['banner_container'],
                                                    'value' => 1,
                                                    'hidden_input' => 1,
                                                    'chk-on' => esc_attr__('Yes','adn'),
                                                    'chk-off' => esc_attr__('No','adn'),
                                                    'chk-high' => 0
                                                ))
                                            ));
                                        
                                        $h.= '</div>
                                    </div>
                                </div>';
                                //<!-- end .spr_column -->
                                
                                $h.= ADNI_Templates::spr_column(array(
                                    'col' => 'spr_col',
                                    'title' => '',
                                    'desc' => '',
                                    'content' => '<input type="submit" value="'.esc_attr__('Save Banner','adn').'" class="button-primary" name="save_banner">'
                                ));

                            $h.= '</div>';
                            // end .settings_box_content
                            
                            
                        $h.= '</div>';
                        //<!-- end .option_box -->
                    $h.= '</div>
                </div>';

                

                
                
                            
                            


                $h.= '<div class="spr_column">';
                    $h.= current_user_can(ADNI_BANNERS_ROLE) ? ADNI_Templates::auto_positioning_template($id, $banner_post) : '';
                    $h.= current_user_can(ADNI_BANNERS_ROLE) ? ADNI_templates::display_filters_tpl($banner_post, $settings) : '';
                $h.= '</div>';
                //<!-- end .spr_column -->

                $h.= current_user_can(ADNI_BANNERS_ROLE) ? ADNI_Templates::parallax_tpl($banner_post, $settings) : '';
                

            $h.= '</div>';
            //<!-- end .spr_column -->

 
        $h.= '</div>';
        //<!-- end .spr_row -->
        $h.= '</form>';
        
    
    $h.= '</div>';
    // <!-- end .wrap -->
$h.= '</div>';
// <!-- end .adning_add_new_banner -->


$h.= '<script>';
$h.= 'jQuery(document).ready(function($) {';

    //$h.= 'window.Adning_global.activate_tooltips($(\'.adning_dashboard\'));';
	
	// Initiate banner
	$h.= '$("._ning_cont").ningResponsive();';
	
    $h.= '$("#ADNI_size").on("change", function(){';
		$h.= 'var size = $(this).val(),';
            $h.= 'sizes = size.split("x");';
		
        $h.= 'console.log("common banner size change");';
		
		$h.= 'if( size !== "custom"){';
			$h.= "$('#ADNI_size_w').val(sizes[0]);";
			$h.= "$('#ADNI_size_h').val(sizes[1]);";

			// Change preview banner size
			$h.= '$("._ning_cont").ningResponsive({width:sizes[0], height:sizes[1]});';
			
			$h.= 'banner_resized_notice();';
        $h.= '}';
    $h.= '});';
	
	$h.= "$('._ning_custom_size').on('change', function(){";
		$h.= "var w = $('#ADNI_size_w').val(),";
            $h.= "h = $('#ADNI_size_h').val();";
		
        $h.= "console.log('custom size change');";
		
		// Select banner size option	
		$h.= 'if($("#ADNI_size option[value=\'"+w+"x"+h+"\']").length > 0){';
			$h.= "$('#ADNI_size option[value=\"'+w+'x'+h+'\"]').attr('selected', 'selected').change();";
        $h.= '}else{';
			$h.= '$(\'#ADNI_size option[value="custom"]\').attr(\'selected\', \'selected\').change();';
        $h.= '}';
			
		// Change preview banner size
		$h.= '$("._ning_cont").ningResponsive({width:w, height:h});';
		$h.= 'banner_resized_notice();';
    $h.= '});';
    //$('._ning_custom_size').trigger("change");
    

    
	
	$h.= "$('#ADNI_responsive').on('change', function(){";
        $h.= '$("._ning_cont").toggleClass("responsive");';
        $h.= "var w = $('#ADNI_size_w').val(),";
            $h.= "h = $('#ADNI_size_h').val();";
                
        $h.= 'if( $("._ning_cont").hasClass("responsive") ){';
            $h.= '$("._ning_cont").css({width: "100%", height: h});';
        $h.= '}else{';
            $h.= '$("._ning_cont").css({width: w, height: h});';
        $h.= '}';
		//$("._ning_cont").ningResponsive();
    $h.= '});';
	$h.= "$('#ADNI_scale').on('change', function(){";
		$h.= '$("._ning_cont").toggleClass("scale");';
		//$("._ning_cont").ningResponsive();
    $h.= '});';
	
	
	$h.= '$(window).on("resize", banner_resized_notice);';
	
	
	
	// Banner resied notice
	$h.= 'function banner_resized_notice(){';
		$h.= "var width = $('#ADNI_size').val().split('x')[0],";
            $h.= 'banner_width = $("._ning_cont")[0].getBoundingClientRect().width,';
			$h.= 'banner_height = $("._ning_cont")[0].getBoundingClientRect().height;';
			
        $h.= 'if(banner_width < width){';
			$h.= "$('.banner_notice').html('Resized banner version: '+Math.round(banner_width)+'x'+Math.round(banner_height)+'px');";
        $h.= '}else{';
			$h.= '$(".banner_notice").html("");';
        $h.= '}';
    $h.= '}';



    $h.= "$('.adsense_btn').on('click', function(){";
        $h.= "$('.google_adsense_container').show();";
    $h.= "});";

    $h.= "$('.adsense_set').on('change', function(){";
		$h.= "var w = $('#ADNI_size_w').val(),";
            $h.= "h = $('#ADNI_size_h').val();";
        
        $h.= "var code = Adning_global.adsense_tpl({";
            $h.= "'pub_id': $('.adsense_pub_id').val(),";
            $h.= "'slot_id': $('.adsense_slot_id').val(),";
            $h.= "'type': $('.adsense_type').val()";
        $h.= "});";
        
        $h.= "$('#banner_content').val( code ).trigger( 'change' );";
    $h.= "});";
    
    
    
    $h.= '$(".HTML5Uploader")._ning_file_upload({';
        $h.= "'banner_id': '".$id."',";
        $h.= "'user_id': '".get_current_user_id()."',";
        $h.= "'max_upload_size': 1000,";
        $h.= "'wpnonce': '".wp_create_nonce( '_ning_upload_'.$id )."',";
        $h.= "'upload': {";
            $h.= "'folder': 'items/".$id."/',";
            $h.= "'dir': '".ADNI_UPLOAD_DIR."',";
            $h.= "'src': '".ADNI_UPLOAD_SRC."'";
        $h.= "},";
        $h.= "'allowed_file_types': ['zip','jpg','png','gif','svg','mp4'],";
        $h.= "'callback': function(obj){";
            //console.log('after upload callback');
            //console.log(obj);
            $h.= "var w = $('#ADNI_size_w').val(),";
                $h.= "h = $('#ADNI_size_h').val(),";
                $h.= "src = '',";
                $h.= "cont = '';";

            $h.= "if($.isEmptyObject(obj.unzip)){";
                //console.log('NO ZIP FILE');
                $h.= "var uploaded_file = JSON.parse(obj.files);";
                //console.log(uploaded_file);
                $h.= "src = uploaded_file[0].src;";
                $h.= "cont = Adning_global.fileContent({'url':src,'type':''});";
                //cont = '<img src="'+src+'" />';
            $h.= "}else{";
                $h.= "src = obj.unzip.url;";
                // https://stackoverflow.com/a/5796744/3481803
                $h.= "cont = $('<textarea />').html('".htmlentities ("<div style=\"max-width:'+w+'px; width:100%; height:'+h+'px;\"><iframe src=\"'+src+'\" border=\"0\" scrolling=\"no\" allowtransparency=\"true\" style=\"width:1px;min-width:100%;*width:100%;height:100%;border:0;\"></iframe></div>")."').text();";
                //cont = '<iframe src="'+src+'" style="border:none;width:'+w+'px;height:'+h+'px;"></iframe>';
            $h.= "}";

            $h.= "$('#banner_content').val( cont ).trigger( 'change' );";
        $h.= "}";
    $h.= '});';
	

    
    $h.= "$('.BGADUploader')._ning_file_upload({";
        $h.= "'banner_id': '".$id."',";
        $h.= "'user_id': '".get_current_user_id()."',";
        $h.= "'max_upload_size': 1000,";
        $h.= "'wpnonce': '".wp_create_nonce( '_ning_upload_'.$id )."',";
        $h.= "'upload': {";
            $h.= "'folder': 'items/".$id."/',";
            $h.= "'dir': '".ADNI_UPLOAD_DIR."',";
            $h.= "'src': '".ADNI_UPLOAD_SRC."'";
        $h.= "},";
        $h.= "'allowed_file_types': ['jpg','png','gif','svg'],";
        $h.= "'callback': function(obj){";
            $h.= "var src = '';";

            $h.= "var uploaded_file = JSON.parse(obj.files);";
            $h.= "src = uploaded_file[0].src;";

            $h.= "$('#bg_takeover_src').val(src).trigger('change');";
            /*$('.bgad_preview_container').show();

            $('.bgad_preview').bgTakeover({
                bg_image: src,
                bg_color: $('#bg_takeover_bg_color').val(),
                bg_pos: 'absolute',
                top_skin: $('#bg_takeover_top_skin').val(),
                container: '.bgad_prev_content'
            });*/

            $h.= "console.log(src);";
            //$('#banner_content').val( cont ).trigger( "change" );
        $h.= "}";
    $h.= "});";

    $h.= "$('.bg_takeover_prev_obj').on('change', function(){";
        $h.= "$('.bgad_preview_container').show();";
        $h.= "$('.bgad_preview').bgTakeover({";
            $h.= "bg_image: $('#bg_takeover_src').val(),";
            $h.= "bg_color: $('#bg_takeover_bg_color').val(),";
            $h.= "bg_pos: 'absolute',";
            $h.= "top_skin: $('#bg_takeover_top_skin').val(),";
            $h.= "container: '.bgad_prev_content',";
            $h.= "click_url: {";
                $h.= "'top': $('#bg_takeover_top_skin_url').val(),";
                $h.= "'left': $('#bg_takeover_left_skin_url').val(),";
                $h.= "'right': $('#bg_takeover_right_skin_url').val()";
            $h.= "}";
        $h.= "});";
    $h.= "});";

    if( !empty($b['bg_takeover_src']) || !empty($b['bg_takeover_bg_color']) )
    {
        $h.= "$('.bgad_preview_container').show();";
        $h.= "$('.bgad_preview').bgTakeover({";
            $h.= "bg_image: $('#bg_takeover_src').val(),";
            $h.= "bg_color: $('#bg_takeover_bg_color').val(),";
            $h.= "bg_pos: 'absolute',";
            $h.= "top_skin: $('#bg_takeover_top_skin').val(),";
            $h.= "container: '.bgad_prev_content',";
            $h.= "click_url: {";
                $h.= "'top': $('#bg_takeover_top_skin_url').val(),";
                $h.= "'left': $('#bg_takeover_left_skin_url').val(),";
                $h.= "'right': $('#bg_takeover_right_skin_url').val()";
            $h.= "}";
        $h.= "});";
    }



    /**
     * IMGMCE EDITOR
    */
    if( class_exists('ImgMCE'))
    {
        $banner_obj = '';

        // Check if content is imgMCE and if so get ID.
        $pattern = '~imgmce_element\s*id="\K[^"]*~';
        if ( preg_match_all($pattern, $b['banner_content'], $matches) )
        {
            //print_r($matches);
            $imgmce_id = $matches[0][0];

            // user_id
            $pattern_uid = '~user_id="\K[^"]*~';
            if ( preg_match_all($pattern_uid, $b['banner_content'], $matches) ) 
            {
                //print_r($matches);
                $imgmce_user_id = $matches[0][0];
            }

            $banner_obj = ADN_Main::load_element(array('id' => $imgmce_id,'user_id' => $imgmce_user_id));
        }

        $h.= "$('.open_imgmce_button').on('click', function(){";

            $h.= "var w = $('#ADNI_size_w').val(),";
                $h.= "h = $('#ADNI_size_h').val();";
            
            $h.= 'var fw = w === "full" ? true : false;';
            $h.= 'w === "full" ? "" : w;';

            $h.= "var editor = {imgMCE:{}};";
            $h.= "var defaults = {";
                $h.= "'active_editor': '',";
                $h.= "'user_id': '".$user_id."',";
                $h.= "'in_popup':1,";
                $h.= "'save_to_folder': 1,";
                $h.= "'callback': '_imc_save_to_adning' ";
            $h.= "};";
            $h.= "editor.imgMCE = $.extend(defaults, editor.imgMCE);";

            if( empty($banner_obj) )
            {
                $h.= "ImgMCE_global.load_editor({ 'banner_obj': { 'size':{'full': w+'x'+h, 'w':w, 'h':h, 'fw': fw}}, 'editor':editor.imgMCE });";
            }
            else
            {
                $h.= "ImgMCE_global.load_editor({ 'banner_obj': ".json_encode($banner_obj['banner_obj']).", 'editor':editor.imgMCE });";
            }
        $h.= "});";
    }

$h.= "});";


$h.= "function _imc_save_to_adning(_obj, arr, settings){";

    $h.= "var $ = jQuery;";

    // Insert content in tinymce editor
    $h.= "var editing = $('body').find('#TMCimgModal').data('editing'),";
        $h.= "load_code = settings.editor.return_all_code;";
        $h.= "active_editor = settings.editor.active_editor,";
        $h.= "msg = JSON.stringify(arr),";
        $h.= "debug = settings.editor.debug;";

    $h.= "console.log('SAVE imgMCE from ADNING');";
    $h.= "console.log(arr);";
    
    $h.= "$('#banner_content').val( '[imgmce_element id=\"'+arr.banner_obj.id+'\" user_id=\"'+arr.banner_obj.user_id+'\" iframe_id=\"_dn".$id."\"]' ).trigger( 'change' );";
    $h.= "$('._ning_inner').html( arr.banner_template );";
$h.= "}";
$h.= "</script>";