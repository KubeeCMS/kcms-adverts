<?php
$error = array();

//delete_option( '_adning_settings' );
if( isset($_GET['run_update']) && !empty($_GET['run_update']))
{
    ADNI_Updates::run_update();
}

if( isset($_GET['clearAdningErrorLog']) && !empty($_GET['clearAdningErrorLog']))
{
    ADNI_Init::clear_error_log();
}

$set_arr = ADNI_Main::settings();
$settings = $set_arr['settings'];

//echo '<pre>'.print_r(wp_roles(),true).'</pre>';

//echo ADNI_Filters::get_country(ADNI_Main::get_visitor_ip());

/**
 * IF POST DATA
*/
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['submit_btn']))
    {
        //echo '<pre>'.print_r($_POST, true).'</pre>';
        // Check for empty positioning post
        if(!isset($_POST['positioning']['post_types']))
        {
            $_POST['positioning']['post_types'] = array();
        }
        
        foreach($_POST as $key => $post){
            $settings[$key] = $_POST[$key];
        }

        // create debug file if necessary
        if($settings['debug'] && !file_exists(ADNI_Init::$log_file)) 
        {
            ADNI_Init::error_log(__('Adning - created debug.log file','adn'));
            if( !is_writable(ADNI_Init::$log_file) )
            {
                $error[] = array('type' => 'warning', 'msg' => __('We are unable to create the Adning <strong><em>debug.log</em></strong> file on your server. This is most likely a file permission issue. You can manually create the file under <em><strong>plugins/angwp/</strong>debug.log</em> and make sure it\'s writable.','adn'));
            }
        }
    
        // UPDATE SETTINGS
        ADNI_Multi::update_option('_adning_settings', $settings);
    }
}

//echo '<pre>'.print_r($settings, true).'</pre>';
?>

<!-- Wordpress Messages -->
<h2 class="messages-position"></h2>

<div class="adning_dashboard adning_cont">
	<div class="wrap">
       
        <?php 
        $smartrackErr = apply_filters('adning_general_notice', 0);
        if( $smartrackErr != '' )
        {
            $error[] = array('type' => 'warning', 'msg' => $smartrackErr);
        }
        
        echo ADNI_Templates::main_admin_header(array(
            'page' => 'settings',
            'title' => 'Adning General Settings',
            'desc' => '⚡ ' . __('Adning is designed in a very modular fashion so that lots of functions are customizable. Should you wish to, you can find most general settings below.','adn'),
            'errors' => $error
        )); 
        ?>

        <div class="container">

            <form action="" method="post" id="imc-role-settings">

                <div class="spr_row">  
                    <!-- /**
                    * 6 x 6
                    */ -->
                    <div class="spr_column spr_col-6">

                        <div class="spr_column spr_col">
                            <div class="spr_column-inner left_column">
                                <div class="spr_wrapper">
                                    <div class="option_box">
                                        <div class="info_header">
                                            <span class="nr">
                                            <svg viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119.034 8 8 119.033 8 256s111.034 248 248 248 248-111.034 248-248S392.967 8 256 8zm130.108 117.892c65.448 65.448 70 165.481 20.677 235.637L150.47 105.216c70.204-49.356 170.226-44.735 235.638 20.676zM125.892 386.108c-65.448-65.448-70-165.481-20.677-235.637L361.53 406.784c-70.203 49.356-170.226 44.736-235.638-20.676z"></path></svg>
                                            </span>
                                            <span class="text"><?php _e('Disable AD Settings','adn'); ?></span>
                                            <span class="fa tog"></span>
                                            <!--<input type="submit" value="<?php _e('Save Changes','adn'); ?>" class="button-primary" name="submit_btn" style="width:auto;float:right;margin:8px;">-->
                                        </div>
                                        <div class="settings_box_content">
                                            <div class="input_container">
                                                <div class="input_container_inner">
                                                    <?php
                                                    $html = '';
                                                    $html.= '<div class="adn_settings_cont">';
                                                        $html.= '<h4>'.__('Disable Ads','adn').' <span class="fa togg"></span></h4>';
                                                        $html.= '<div class="set_box_content">';
                                                            $html.= '<div class="adn_settings_cont_inner clear">';
                                                                $html.= '<p>'.__('','adn').'</p>';
                                                                
                                                                $html.= ADNI_Templates::switch_btn(array(
                                                                    'title' => __('Disable All Ads','adn'),
                                                                    'id' => 'disable_all_ads',
                                                                    'name' => 'disable[all_ads]',
                                                                    'checked' => $settings['disable']['all_ads'],
                                                                    'value' => 1,
                                                                    'hidden_input' => 1,
                                                                    'chk-on' => __('Yes','adn'),
                                                                    'chk-off' => __('No','adn'),
                                                                    'column' => array(
                                                                        'size' => 'col-6',
                                                                        'desc' => __('This will disable all ads on the website.','adn'),
                                                                    )
                                                                ));
                                                                
                                                                
                                                                $html.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-6',
                                                                    'title' => __('Hide ads for logged-in users.','adn'),
                                                                    'desc' => __('Select the lowest role a user must have in order not to see any ads.','adn'),
                                                                    'content' => '<select name="disable[user_role_ads]" class=""><option value="" '.selected( $settings['disable']['user_role_ads'], '', false ).'>'.__('Show ads to everyone','adn').'</option>'.ADNI_Main::dropdown_roles($settings['disable']['user_role_ads']).'</select>'
                                                                ));
                                                                
                                                                
                                                            $html.= '</div>';
                                                        $html.= '</div>';
                                                        // end .set_box_content
                                                    $html.= '</div>';

                                                    $html.= '<div class="adn_settings_cont closed">';
                                                        $html.= '<h4>'.__('GDPR - Disable Ads till content is approved','adn').'<span class="fa togg"></span></h4>';
                                                        
                                                        $html.= '<div class="set_box_content hidden">';
                                                            $html.= '<div class="adn_settings_cont_inner clear">';
                                                                $html.= '<p>'.__('Disable all Ads until the "content cookie" is approved. You can use the build in cookie message or use a third party option, in that case you can provide the cookie name in value here.','adn').'</p>';
                                                                
                                                                $html.= ADNI_Templates::switch_btn(array(
                                                                    'title' => __('Disable all Ads until the "content cookie" is approved.','adn'),
                                                                    'id' => 'disable_till_approved',
                                                                    'name' => 'gdpr[disable_till_approved]',
                                                                    'checked' => $settings['gdpr']['disable_till_approved'],
                                                                    'value' => 1,
                                                                    'hidden_input' => 1,
                                                                    'chk-on' => __('Yes','adn'),
                                                                    'chk-off' => __('No','adn'),
                                                                    'column' => array(
                                                                        'size' => 'col',
                                                                        'desc' => __('','adn'),
                                                                    )
                                                                ));


                                                                $html.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-6',
                                                                    'title' => __('Cookie name','adn'),
                                                                    'desc' => __('The name of the cookie that needs to be available.','adn'),
                                                                    'content' => ADNI_Templates::inpt_cont(array(
                                                                            'type' => 'text',
                                                                            'width' => '100%',
                                                                            'name' => 'gdpr[cookie_name]',
                                                                            'value' => $settings['gdpr']['cookie_name'],
                                                                            'placeholder' => '',
                                                                            'icon' => 'pencil',
                                                                            'show_icon' => 1
                                                                        ))
                                                                ));

                                                                $html.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-6',
                                                                    'title' => __('Cookie value','adn'),
                                                                    'desc' => __('The required value of the cookie.','adn'),
                                                                    'content' => ADNI_Templates::inpt_cont(array(
                                                                            'type' => 'text',
                                                                            'width' => '100%',
                                                                            'name' => 'gdpr[cookie_value]',
                                                                            'value' => $settings['gdpr']['cookie_value'],
                                                                            'placeholder' => '',
                                                                            'icon' => 'pencil',
                                                                            'show_icon' => 1
                                                                        ))
                                                                ));
                                                                
                                                            $html.= '</div>';

                                                            $html.= '<div class="adn_settings_cont_inner clear">';
                                                                
                                                                $html.= ADNI_Templates::switch_btn(array(
                                                                    'title' => __('Show build-in gdpr cookie message.','adn'),
                                                                    'id' => 'show_cookie_message',
                                                                    'name' => 'gdpr[show_cookie_message]',
                                                                    'checked' => $settings['gdpr']['show_cookie_message'],
                                                                    'value' => 1,
                                                                    'hidden_input' => 1,
                                                                    'chk-on' => __('Yes','adn'),
                                                                    'chk-off' => __('No','adn'),
                                                                    'column' => array(
                                                                        'size' => 'col-6',
                                                                        'desc' => __('This will show a gdpr cookie message modal on your website.','adn'),
                                                                    )
                                                                ));

                                                                $html.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-6',
                                                                    'title' => __('Button Text','adn'),
                                                                    'desc' => __('The "Approve" button text for the GDPR cookie message.','adn'),
                                                                    'content' => ADNI_Templates::inpt_cont(array(
                                                                            'type' => 'text',
                                                                            'width' => '100%',
                                                                            'name' => 'gdpr[cookie_message_approve_btn]',
                                                                            'value' => $settings['gdpr']['cookie_message_approve_btn'],
                                                                            'show_icon' => 1,
                                                                            'icon' => 'pencil',
                                                                            'placeholder' => __('I Accept Cookies','adn')
                                                                        ))
                                                                ));

                                                                $html.= ADNI_Templates::textarea_cont(array(
                                                                    'title' => __('Message Text','adn'),
                                                                    'name' => 'gdpr[cookie_message_text]',
                                                                    'value' => stripslashes($settings['gdpr']['cookie_message_text']),
                                                                    'placeholder' => __('We use cookies to offer you a better browsing experience. If you continue to use this site, you consent to our use of cookies.','adn'),
                                                                    'desc_pos' => 'bottom',
                                                                    'desc' => __('The text to show in the GDPR cookie message.','adn')
                                                                ));

                                                                $html.= ADNI_Templates::inpt_cont(array(
                                                                    'title' => __('Page button','adn'),
                                                                    'desc' => __('(Optional) The URL to a page you want to add in the GDPR cookie message.','adn'),
                                                                    'type' => 'text',
                                                                    'width' => '100%',
                                                                    'name' => 'gdpr[cookie_message_page_url]',
                                                                    'value' => $settings['gdpr']['cookie_message_page_url'],
                                                                    'show_icon' => 1,
                                                                    'icon' => 'link',
                                                                    'placeholder' => ''
                                                                ));
                                                            
                                                            $html.= '</div>';
                                                        $html.= '</div>';
                                                        // end .set_box_content
                                                    $html.= '</div>';

                                                    echo $html;
                                                    ?>
                                                </div>
                                                <span class="description bottom"><?php _e('','adn'); ?></span>
                                            </div>
                                            <!-- end .input_container -->

                                        <?php
                                        echo ADNI_Templates::spr_column(array(
                                            'col' => 'spr_col',
                                            'title' => '',
                                            'desc' => '',
                                            'content' => '<input type="submit" value="'.esc_attr__('Save Changes','adn').'" class="button-primary" name="submit_btn">'
                                        ));
                                        ?>
                                        </div>
                                        <!-- end .settings_box_content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .spr_column -->


                        <div class="spr_column spr_col">
                        <div class="spr_column-inner left_column">
                            <div class="spr_wrapper">
                                <div class="option_box closed">
                                    <div class="info_header">
                                        <span class="nr">
                                            <svg viewBox="0 0 384 512"><path fill="currentColor" d="M35.5 279.9l148-148.4c4.7-4.7 12.3-4.7 17 0l148 148.4c4.7 4.7 4.7 12.3 0 17l-19.6 19.6c-4.8 4.8-12.5 4.7-17.1-.2L218 219.2V468c0 6.6-5.4 12-12 12h-28c-6.6 0-12-5.4-12-12V219.2l-93.7 97.1c-4.7 4.8-12.4 4.9-17.1.2l-19.6-19.6c-4.8-4.7-4.8-12.3-.1-17zM12 84h360c6.6 0 12-5.4 12-12V44c0-6.6-5.4-12-12-12H12C5.4 32 0 37.4 0 44v28c0 6.6 5.4 12 12 12z" class=""></path></svg>
                                        </span>
                                        <span class="text"><?php _e('Upload Settings','adn'); ?></span>
                                        <span class="fa tog"></span>
                                    </div>
                                    <div class="settings_box_content hidden">
                                        <div class="input_container">
                                            <div class="input_container_inner">
                                                <?php
                                                $html = '';
                                                $html.= '<div class="adn_settings_cont">';
                                                    $html.= '<h4 id="posttypes_for_ads">'.__('Allowed file types','adn').' <span class="fa togg"></span></h4>';
                                                    $html.= '<div class="set_box_content">';
                                                        $html.= '<div class="adn_settings_cont_inner clear">';
                                                            $html.= '<p>'.__('Select the file types allowed for banner uploads.','adn').'</p>';
                                                            
                                                            $file_types = apply_filters('angwp_available_file_types', array('jpg','jpeg','png','gif','svg','mp4','zip'));
                                                            $valid_formats = $settings['upload']['file_types'];
                                                            
                                                            if( !empty($file_types ))
                                                            {
                                                                foreach( $file_types as $file_type )
                                                                {
                                                                    $html.= ADNI_Templates::checkbox(array(
                                                                        'title' => $file_type,
                                                                        //'tooltip' => __('Run plugin in debug mode.','adn'),
                                                                        'checked' => in_array($file_type, $valid_formats) ? 1 : 0,
                                                                        'value' => $file_type,
                                                                        'hidden_input' => 0,
                                                                        'name' => 'upload[file_types][]',
                                                                        'class' => 'option_checkbox'
                                                                    )); 
                                                                }
                                                            }
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                $html.= '</div>';

                                                $html.= '<div class="adn_settings_cont closed">';
                                                    $html.= '<h4>'.__('More','adn').' <span class="fa togg"></span></h4>';
                                                    $html.= '<div class="set_box_content hidden">';
                                                        $html.= '<div class="adn_settings_cont_inner clear">';
                                                            $html.= '<p>'.__('More upload settings.','adn').'</p>';
                                                        
                                                            $html.= ADNI_Templates::switch_btn(array(
                                                                'title' => __('Allow HTML ads','adn'),
                                                                'id' => 'allow_html_ads',
                                                                'name' => 'upload[html_ads]',
                                                                'checked' => $settings['upload']['html_ads'],
                                                                'value' => 1,
                                                                'hidden_input' => 1,
                                                                'chk-on' => __('Yes','adn'),
                                                                'chk-off' => __('No','adn'),
                                                                'column' => array(
                                                                    'size' => 'col-6',
                                                                    'desc' => __('Allow users to upload HTML ads','adn'),
                                                                )
                                                            ));

                                                            
                                                            $html.= ADNI_Templates::switch_btn(array(
                                                                'title' => __('Sanitize AD content','adn'),
                                                                'id' => 'sanitize_ads',
                                                                'name' => 'upload[sanitize]',
                                                                'checked' => $settings['upload']['sanitize'],
                                                                'value' => 1,
                                                                'hidden_input' => 1,
                                                                'chk-on' => __('Yes','adn'),
                                                                'chk-off' => __('No','adn'),
                                                                'column' => array(
                                                                    'size' => 'col-6',
                                                                    'desc' => sprintf(__('Sanitize ad content for allowed HTML tags %s. <strong>NOTE:</strong> when enabled javascript ADS will not work.','adn'), '<small><a href="https://developer.wordpress.org/reference/functions/wp_kses_post/" target="_blank">wp_kses_post()</a></small>'),
                                                                )
                                                            ));
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                $html.= '</div>';
                                                echo $html;
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- end .spr_column -->


                        
                        <div class="spr_column spr_col">
                        <div class="spr_column-inner left_column">
                            <div class="spr_wrapper">
                                <div class="option_box closed">
                                    <div class="info_header">
                                        <span class="nr">
                                        <svg viewBox="0 0 512 512"><path fill="currentColor" d="M500 224h-30.364C455.724 130.325 381.675 56.276 288 42.364V12c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v30.364C130.325 56.276 56.276 130.325 42.364 224H12c-6.627 0-12 5.373-12 12v40c0 6.627 5.373 12 12 12h30.364C56.276 381.675 130.325 455.724 224 469.636V500c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-30.364C381.675 455.724 455.724 381.675 469.636 288H500c6.627 0 12-5.373 12-12v-40c0-6.627-5.373-12-12-12zM288 404.634V364c0-6.627-5.373-12-12-12h-40c-6.627 0-12 5.373-12 12v40.634C165.826 392.232 119.783 346.243 107.366 288H148c6.627 0 12-5.373 12-12v-40c0-6.627-5.373-12-12-12h-40.634C119.768 165.826 165.757 119.783 224 107.366V148c0 6.627 5.373 12 12 12h40c6.627 0 12-5.373 12-12v-40.634C346.174 119.768 392.217 165.757 404.634 224H364c-6.627 0-12 5.373-12 12v40c0 6.627 5.373 12 12 12h40.634C392.232 346.174 346.243 392.217 288 404.634zM288 256c0 17.673-14.327 32-32 32s-32-14.327-32-32c0-17.673 14.327-32 32-32s32 14.327 32 32z"></path></svg>
                                        </span>
                                        <span class="text"><?php _e('Placement Settings','adn'); ?></span>
                                        <span class="fa tog"></span>
                                    </div>
                                    <div class="settings_box_content hidden">
                                        <div class="input_container">
                                            <div class="input_container_inner">
                                                <?php
                                                $html = '';
                                                $html.= '<div class="adn_settings_cont closed">';
                                                    $html.= '<h4>'.__('Content Ads','adn').' <span class="fa togg"></span></h4>';
                                                    $html.= '<div class="set_box_content hidden">';
                                                        $html.= '<div class="adn_settings_cont_inner clear">';
                                                            $html.= '<p>'.__('Content Ads are ads that get added into the post content (Above content, Inside content, Below content).','adn').'</p>';
                                                        
                                                            $html.= ADNI_Templates::switch_btn(array(
                                                                'title' => __('Disable Non-singular Ads','adn'),
                                                                'id' => 'disable_non_singular_ads',
                                                                'name' => 'disable[non_singular_ads]',
                                                                'checked' => $settings['disable']['non_singular_ads'],
                                                                'value' => 1,
                                                                'hidden_input' => 1,
                                                                'chk-on' => __('Yes','adn'),
                                                                'chk-off' => __('No','adn'),
                                                                'column' => array(
                                                                    'size' => 'col-6',
                                                                    'desc' => __('This will disable all "content" ads on non-singular pages (categories, tags, authors,...)','adn'),
                                                                )
                                                            ));
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                    // end .set_box_content
                                                $html.= '</div>';
                                                        
                                                
                                                $html.= '<div class="adn_settings_cont">';
                                                    $html.= '<h4 id="posttypes_for_ads">'.__('Post Types for ADS','adn').' <span class="fa togg"></span></h4>';
                                                    $html.= '<div class="set_box_content">';
                                                        $html.= '<div class="adn_settings_cont_inner clear">';
                                                            $html.= '<p>'.__('Select the post types where "Auto Positioning" for Ads should be available.','adn').'</p>';
                                                        
                                                            // Post types
                                                            $post_types = get_post_types();
                                                            if( !empty($post_types ))
                                                            {
                                                                foreach( $post_types as $post_type )
                                                                {
                                                                    $exclude = array('attachment', 'revision', 'nav_menu_item', 'adni_banners', 'adni_adzones', 'adni_campaigns');
                                                                    if( !in_array( $post_type, $exclude))
                                                                    {
                                                                        $html.= ADNI_Templates::checkbox(array(
                                                                            'title' => $post_type,
                                                                            //'tooltip' => __('Run plugin in debug mode.','adn'),
                                                                            'checked' => in_array($post_type, $settings['positioning']['post_types']) ? 1 : 0,
                                                                            'value' => $post_type,
                                                                            'hidden_input' => 0,
                                                                            'name' => 'positioning[post_types][]',
                                                                            'class' => 'option_checkbox'
                                                                        ));
                                                                        
                                                                    }
                                                                }
                                                            }
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                $html.= '</div>';


                                                $html.= '<div class="adn_settings_cont closed">';
                                                    $html.= '<h4 id="posttypes_for_ads">'.__('Filters','adn').' <span class="fa togg"></span></h4>';
                                                    $html.= '<div class="set_box_content hidden">';
                                                        $html.= '<div class="adn_settings_cont_inner clear">';
                                                            //$html.= '<p>'.__('Select the post types where "Auto Positioning" for Ads should be available.','adn').'</p>';
                                                        
                                                            // Author Filter
                                                            $html.= ADNI_Templates::switch_btn(array(
                                                                'title' => __('Hide Ads when no Author Filter is selected','adn'),
                                                                'id' => 'hide_noauthorfilter_ads',
                                                                'name' => 'filters[hide_ads_when_no_author_filter]',
                                                                'checked' => $settings['filters']['hide_ads_when_no_author_filter'],
                                                                'value' => 1,
                                                                'hidden_input' => 1,
                                                                'chk-on' => __('Yes','adn'),
                                                                'chk-off' => __('No','adn'),
                                                                'column' => array(
                                                                    'size' => 'col-6',
                                                                    'desc' => __('This will hide all ads when no author filter is selected.','adn'),
                                                                )
                                                            ));


                                                            //$roles = ADNI_Main::default_role_options();
                                                            global $wp_roles;
                                                            $role_arr = array();
                                                            $role_arr['all'] = array('value' => '', 'text' => '');
                                                            //echo '<pre>'.print_r($wp_roles,true).'</pre>';
                                                            if(!empty($wp_roles->role_names))
                                                            {
                                                                
                                                                foreach($wp_roles->role_names as $role_name => $r)
                                                                {
                                                                    $role_arr[$role_name] = array(
                                                                        'value' => $role_name,
                                                                        'text' => ucfirst($role_name)
                                                                    );
                                                                }
                                                            }
                                                            
                                                            $html.= ADNI_Templates::spr_column(array(
                                                                'col' => 'spr_col-6',
                                                                'title' => __('Advertiser Role','frm'),
                                                                'desc' => __('(optional) Select a user role for advertisers.','frm'),
                                                                'content' => ADNI_Templates::select_cont(array(
                                                                    'name' => 'filters[advertiser_role]',
                                                                    'value' => $settings['filters']['advertiser_role'],
                                                                    'select_opts' => $role_arr
                                                                ))
                                                            ));
                                                            

                                                            
                                                            
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                $html.= '</div>';

                                                echo $html;
                                                ?>
                                            </div>
                                            <span class="description bottom"><?php _e('','adn'); ?></span>

                                        </div>
                                        <!-- end .input_container -->
                                        
                                        <?php
                                        echo ADNI_Templates::spr_column(array(
                                            'col' => 'spr_col',
                                            'title' => '',
                                            'desc' => '',
                                            'content' => '<input type="submit" value="'.esc_attr__('Save Changes','adn').'" class="button-primary" name="submit_btn">'
                                        ));
                                        ?>
                                    </div>
                                    <!-- end .settings_box_content -->
                                </div>
                            </div>
                        </div>
                        </div>


                        <div class="spr_column spr_col">
                            <div class="spr_column-inner left_column">
                                <div class="spr_wrapper">
                                    <div class="option_box closed">
                                        <div id="google-settings" class="info_header">
                                            <span class="nr">
                                                <svg viewBox="0 0 488 512" style="width:20px;"><path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"></path></svg>
                                            </span>
                                            <span class="text"><?php _e('Google Settings','adn'); ?></span>
                                            <span class="fa tog"></span>
                                        </div>
                                        <div class="settings_box_content hidden">
                                            <div class="input_container">
                                                <div class="input_container_inner">
                                                    <?php
                                                    $html = '';
                                                    // ANALYTICS
                                                    $html.= '<div class="adn_settings_cont closed">';
                                                        $html.= '<h4>'.__('Google Analytics','adn').' <span class="fa togg"></span></h4>';
                                                        
                                                        $html.= '<div class="set_box_content hidden">';
                                                            $html.= '<div class="adn_settings_cont_inner">';
                                                                
                                                                $html.= ADNI_Templates::inpt_cont(array(
                                                                    'type' => 'text',
                                                                    'width' => '100%',
                                                                    'title' => 'Tracking ID',
                                                                    'desc' => 'Add your Google Analytics Tracking ID to start tracking impressions and clicks using Google Analytics.',
                                                                    'name' => 'ga_tracking_id',
                                                                    'value' => $settings['ga_tracking_id'],
                                                                    'placeholder' => 'UA-xxxxxxxx-xx',
                                                                    'desc_pos' => 'bottom'
                                                                ));
                                                            $html.= '</div>';
                                                        $html.= '</div>';
                                                    $html.= '</div>';

                                                    // ADSENSE
                                                    $html.= '<div class="adn_settings_cont closed">';
                                                        $html.= '<h4>'.__('Google AdSense','adn').' <span class="fa togg"></span></h4>';
                                                        
                                                        $html.= '<div class="set_box_content hidden">';
                                                            $html.= '<div class="adn_settings_cont_inner">';
                                                                
                                                                $html.= ADNI_Templates::inpt_cont(array(
                                                                    'type' => 'text',
                                                                    'width' => '100%',
                                                                    'title' => 'Pub ID',
                                                                    'desc' => __('Add your Google AdSense publisher ID','adn'),
                                                                    'name' => 'adsense_pubid',
                                                                    'value' => $settings['adsense_pubid'],
                                                                    'placeholder' => 'pub-xxxxxxxxxxxxxx',
                                                                    'desc_pos' => 'bottom'
                                                                ));

                                                                $html.= ADNI_Templates::switch_btn(array(
                                                                    'title' => __('Enable Auto Ads.','adn'),
                                                                    'name' => 'adsense_auto_ads',
                                                                    'checked' => $settings['adsense_auto_ads'],
                                                                    'value' => 1,
                                                                    'hidden_input' => 1,
                                                                    'chk-on' => __('Yes','adn'),
                                                                    'chk-off' => __('No','adn'),
                                                                    'column' => array(
                                                                        'size' => 'col-6',
                                                                        'desc' => __('Enable Google AdSense to add advertisements to your website automatically.','adn'),
                                                                    )
                                                                ));

                                                                $html.= '<div class="clearFix"></div>';
                                                            $html.= '</div>';
                                                        $html.= '</div>';
                                                    $html.= '</div>';

                                                    echo $html;
                                                    ?>
                                                </div>
                                                <span class="description bottom"><?php _e('','adn'); ?></span>

                                                <div class="clearFix"></div>
                                            </div>
                                            <!-- end .input_container -->

                                            <?php
                                            echo ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col',
                                                'title' => '',
                                                'desc' => '',
                                                'content' => '<input type="submit" value="'.esc_attr__('Save Changes','adn').'" class="button-primary" name="submit_btn">'
                                            ));
                                            ?>
                                        </div>
                                        <!-- end .settings_box_content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .spr_column -->


                        <div class="spr_column spr_col">
                            <div class="spr_column-inner left_column">
                                <div class="spr_wrapper">
                                    <div class="option_box closed">
                                        <div class="info_header">
                                            <span class="nr">
                                            <svg viewBox="0 0 512 512"><path fill="currentColor" d="M466.5 83.7l-192-80a48.15 48.15 0 0 0-36.9 0l-192 80C27.7 91.1 16 108.6 16 128c0 198.5 114.5 335.7 221.5 380.3 11.8 4.9 25.1 4.9 36.9 0C360.1 472.6 496 349.3 496 128c0-19.4-11.7-36.9-29.5-44.3zM256.1 446.3l-.1-381 175.9 73.3c-3.3 151.4-82.1 261.1-175.8 307.7z"></path></svg>
                                            </span>
                                            <span class="text"><?php _e('AD Blocker Settings','adn'); ?></span>
                                            <span class="fa tog"></span>
                                        </div>
                                        <div class="settings_box_content hidden">
                                            <div class="input_container">
                                                <div class="input_container_inner">
                                                    <?php
                                                    $html = '';
                                                    // ADSENSE
                                                    $html.= '<div class="adn_settings_cont">';
                                                        $html.= '<h4>'.__('Enable AD Blocker detection','adn').' <span class="fa togg"></span></h4>';
                                                        
                                                        $html.= '<div class="set_box_content">';
                                                            $html.= '<div class="adn_settings_cont_inner">';
                                                                
                                                                $html.= ADNI_Templates::switch_btn(array(
                                                                    'title' => __('Check for active ad blockers.','adn'),
                                                                    'name' => 'adblock_detect',
                                                                    'checked' => $settings['adblock_detect'],
                                                                    'value' => 1,
                                                                    'hidden_input' => 1,
                                                                    'chk-on' => __('Yes','adn'),
                                                                    'chk-off' => __('No','adn')
                                                                ));

                                                                $html.= '<span class="description bottom">'.__('Detect when a visitor has an ab blocker enabled.','adn').'</span>';
                                                                
                                                            $html.= '</div>';
                                                        $html.= '</div>';
                                                    $html.= '</div>';

                                                    
                                                    $html.= '<div class="adn_settings_cont">';
                                                        $html.= '<h4>'.__('AD Blocker detection message','adn').' <span class="fa togg"></span></h4>';
                                                        
                                                        $html.= '<div class="set_box_content">';
                                                            $html.= '<div class="adn_settings_cont_inner">';
                                                                
                                                                $html.= ADNI_Templates::textarea_cont(array(
                                                                    'title' => __('Message Text','adn'),
                                                                    'name' => 'adblock_message',
                                                                    'value' => stripslashes($settings['adblock_message']),
                                                                    'placeholder' => __('You are using AD Blocker!.','adn'),
                                                                    'desc_pos' => 'bottom',
                                                                    'desc' => __('Message to show when an ad blocker is detected.','adn')
                                                                ));
                                                            $html.= '</div>';
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                    echo $html;
                                                    ?>
                                                </div>
                                                <span class="description bottom"><?php _e('','adn'); ?></span>
                                            </div>
                                            <!-- end .input_container -->

                                            <?php
                                            echo ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col',
                                                'title' => '',
                                                'desc' => '',
                                                'content' => '<input type="submit" value="'.esc_attr__('Save Changes','adn').'" class="button-primary" name="submit_btn">'
                                            ));
                                            ?>
                                        </div>
                                        <!-- end .settings_box_content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .spr_column -->

                    </div>
                    <!-- end LEFT COLUMN .spr_column -->






                    <!-- RIGHT COLUMN -->
                    <div class="spr_column spr_col-6 ">
                        <div class="spr_column-inner">
                            <div class="spr_wrapper">
                                <div class="option_box">
                                    <div class="info_header">
                                        <span class="nr">⚡</span>
                                        <span class="text"><?php _e('Server Status','adn'); ?></span>
                                    </div>
                                    <div class="input_container">
                                        
                                        <div class="system_status">
                                            <p style="border-bottom: solid 1px #e2e2e2;padding:0 0 10px 0;margin-bottom: 20px;">
                                                <?php _e("If any of the options below are not available please install the missing functions or contact your hosting provider.", 'adn'); ?>
                                            </p>
                                            <div class="option_box">
                                                <?php 
                                                _e('Uploads Folder Writable:', 'adn');
                                                $wp_uploads = wp_get_upload_dir();
                                                if ( wp_is_writable( $wp_uploads['basedir'] ) ) {
                                                    _e( '<code class="status-good">Yes</code>', 'adn' ); 
                                                } else {
                                                    echo sprintf( __( '<code class="status-bad">No</code> Uploads folder must be writable to allow WordPress function properly.<br><span>See <a href="%1$s" target="_blank">changing file permissions</a> or contact your hosting provider.</span>', 'adn' ), 'https://codex.wordpress.org/Changing_File_Permissions' );
                                                }
                                                ?>
                                            </div>
                                            <div class="option_box">
                                                <?php
                                                _e('WP Memory Limit:', 'adn');
                                                $wp_memory_limit = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
                                                if ( function_exists( 'memory_get_usage' ) ) {
                                                    $wp_memory_limit = max( $wp_memory_limit, wp_convert_hr_to_bytes( @ini_get( 'memory_limit' ) ) );
                                                }
                                                $memory = $wp_memory_limit;
                                                
                                                if ( $memory < 67108864 ) {
                                                    echo sprintf( __( '<code class="status-bad">%1$s</code> Minimum value is <strong>64 MB</strong>. <strong>128 MB</strong> is recommended.', 'adn' ), size_format( $memory ) );
                                                    //echo $tip;
                                                }
                                                else if ( $memory < 134217728 ) {
                                                    echo sprintf( __( '<code class="status-okay">%1$s</code> Current memory limit is sufficient for most tasks. However, recommended value is <strong>128 MB</strong>.', 'adn' ), size_format( $memory ) );
                                                    //echo $tip;
                                                }
                                                else if ( $memory < 268435456 ) {
                                                    echo sprintf( __( '<code class="status-good">%1$s</code>', 'adn' ), size_format( $memory ) );
                                                    //echo $tip;
                                                }
                                                else {
                                                    echo sprintf( __( '<code class="status-good">%1$s</code>', 'adn' ), size_format( $memory ) );
                                                }
                                                ?>
                                            </div>
                                            <div class="option_box">
                                                <?php 
                                                _e('ZipArchive Support:', 'adn');
                                                if ( class_exists( 'ZipArchive' ) ) {
                                                    _e( '<code class="status-good">Yes</code>', 'adn' ); 
                                                } else {
                                                    echo sprintf( __( '<code class="status-bad">No</code> ZipArchive is required for importing HTML5 banners.<br><span>Please contact your hosting provider.</span>', 'adn' ));
                                                }
                                                ?>
                                            </div>
                                            <div class="option_box">
                                                <?php 
                                                _e('cURL Support:', 'adn');
                                                if( function_exists('curl_version')) {
                                                    _e( '<code class="status-good">Yes</code>', 'adn' ); 
                                                } else {
                                                    echo sprintf( __( '<code class="status-bad">No</code> cURL is required to activate the plugin and receive automatic updates.<br><span>Please contact your hosting provider.</span>', 'adn' ));
                                                }
                                                ?>
                                            </div>
                                            <div class="option_box">
                                                <?php 
                                                _e('PHP Version:', 'adn');
                                                if(phpversion() >= 5.3){
                                                    echo '<code class="status-good">'.phpversion().'</code>';
                                                }else{
                                                    echo sprintf(__('<code class="status-okay">%s</code> Adning has not been tested with PHP versions under 5.3.','adn'), phpversion());
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        

                                        <div class="adn_settings_cont closed">
                                            <h4><?php _e('Debug','adn'); ?> <span class="fa togg"></span></h4>
                                            <div class="set_box_content hidden">
                                                <div class="adn_settings_cont_inner clear">
                                                <p><?php _e('Only turn this on for actual debugging, don\'t leave it turned on on a live site.','adn'); ?></p>
                                                    <div class="system_status">
                                                        <?php 
                                                        echo ADNI_Templates::checkbox(array(
                                                            'title' => __('Debug','adn'),
                                                            'tooltip' => __('Run plugin in debug mode.','adn'),
                                                            'checked' => array_key_exists('debug',$settings) ? $settings['debug'] : 0,
                                                            'value' => 1,
                                                            'hidden_input' => 1,
                                                            'name' => 'debug',
                                                            'class' => 'option_checkbox _debug'
                                                        ));

                                                        if( $settings['debug'] )
                                                        {
                                                            $h = '';
                                                            $h.= '<div class="option_box">';
                                                            
                                                                $h.= __('Error log:', 'adn');
                                                                if(is_writable(ADNI_Init::$log_file))
                                                                {
                                                                    $h.= '<code class="status-good">'.__('Yes', 'adn').'</code>';
                                                                }
                                                                else
                                                                {
                                                                    $h.= sprintf(__('<code class="status-okay">%s</code> The Adning debug.log file could not be created or is not writable.','adn'), __('No','adn'));
                                                                }
                                                            $h.= '</div>';
                                                            
                                                            // Read error log
                                                            if(file_exists(ADNI_Init::$log_file))
                                                            {
                                                                $content = file_get_contents(ADNI_Init::$log_file);
                                                                $h.= '<div class="option_box error_log_container">';
                                                                    $h.= '<textarea class="error_log" readonly>'.$content.'</textarea>';
                                                                $h.= '</div>';
                                                                $h.= '<div><a href="'.ADNI_URL.'debug.log" target="_blank">'.__('Full Log').'</a></div>';
                                                                $h.= '<div><a href="admin.php?page=adning-settings&clearAdningErrorLog=1" onclick="return confirm(\''.__('Are you sure to clear all error_log items?','adn').'\');">'.__('Clear error log').'</a></div>';
                                                            }
                                                            echo $h;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end .adn_settings_cont -->


                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="spr_column spr_col">
                            <div class="spr_column-inner left_column">
                                <div class="spr_wrapper">
                                    <div class="option_box closed">
                                        <div class="info_header">
                                            <span class="nr">
                                            <svg viewBox="0 0 512 512" style="width: 20px;"><path fill="currentColor" d="M480 32l-64 368-223.3 80L0 400l19.6-94.8h82l-8 40.6L210 390.2l134.1-44.4 18.8-97.1H29.5l16-82h333.7l10.5-52.7H56.3l16.3-82H480z"></path></svg>
                                            </span>
                                            <span class="text"><?php _e('Website Code Injection','adn'); ?></span>
                                            <span class="fa tog"></span>
                                        </div>
                                        <div class="settings_box_content hidden">
                                            <div class="input_container">
                                                <div class="input_container_inner">
                                                    <?php
                                                    $html = '';
                                                    $html.= '<div class="adn_settings_cont">';
                                                        $html.= '<h4>'.__('Code Placement Areas','adn').'</h4>';
                                                        $html.= '<div class="adn_settings_cont_inner">';
                                                            $html.= '<p>'.__('Code placement can inject scripts and styles into the header/footer area of your website. Common use cases are header/footer tags from ad networks like <em>Google DoubleClick for Publishers</em> or custom styles and scripts.','adn').'</p>';

                                                            // Header area
                                                            $html.= ADNI_Templates::textarea_cont(array(
                                                                'title' => __('Header Area','adn'),
                                                                'id' => 'placement_area_head',
                                                                'class' => 'code_editor',
                                                                'data' => 'data-lang="htmlmixed"',
                                                                'name' => 'placement_area_head',
                                                                'value' => stripslashes($settings['placement_area_head']),
                                                                'desc_pos' => 'bottom',
                                                                'desc' => sprintf(__('Add code before the closing %s tag.','adn'), htmlentities('</head>'))
                                                            ));
                                                            // Body area
                                                            $html.= ADNI_Templates::textarea_cont(array(
                                                                'title' => __('Footer Area','adn'),
                                                                'id' => 'placement_area_body',
                                                                'class' => 'code_editor',
                                                                'data' => 'data-lang="htmlmixed"',
                                                                'name' => 'placement_area_body',
                                                                'value' => stripslashes($settings['placement_area_body']),
                                                                'desc_pos' => 'bottom',
                                                                'desc' => sprintf(__('Add code before the closing %s tag.','adn'), htmlentities('</body>'))
                                                            ));
                                                        $html.= '</div>';
                                                    $html.= '</div>';
                                                    
                                                    $html.= '<div class="adn_settings_cont">';
                                                        $html.= '<h4>'.__('Custom CSS','adn').'</h4>';
                                                        $html.= '<div class="adn_settings_cont_inner">';
                                                            $html.= ADNI_Templates::textarea_cont(array(
                                                                'title' => '',
                                                                'name' => 'custom_css',
                                                                'class' => 'code_editor',
                                                                'data' => 'data-lang="css"',
                                                                'value' => stripslashes($settings['custom_css']),
                                                                'placeholder' => '',
                                                                'desc_pos' => 'bottom',
                                                                'desc' => __('In case you need to add some custom CSS to your website.','adn')
                                                            ));
                                                        $html.= '</div>';
                                                    $html.= '</div>';

                                                    echo $html;
                                                    ?>
                                                </div>
                                                <span class="description bottom"><?php _e('','adn'); ?></span>
                                            </div>
                                            <!-- end .input_container -->

                                            <?php
                                            echo ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col',
                                                'title' => '',
                                                'desc' => '',
                                                'content' => '<input type="submit" value="'.esc_attr__('Save Changes','adn').'" class="button-primary" name="submit_btn">'
                                            ));
                                            ?>
                                        </div>
                                        <!-- end .settings_box_content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .spr_column -->


                        <div class="spr_column spr_col">
                            <div class="spr_column-inner left_column">
                                <div class="spr_wrapper">
                                    <div class="option_box closed">
                                        <div class="info_header">
                                            <span class="nr">
                                            <svg viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128H32zm272-256a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zm-96 0a16 16 0 0 1 32 0v224a16 16 0 0 1-32 0zM432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z"></path></svg>
                                            </span>
                                            <span class="text"><?php _e('Uninstall Settings','adn'); ?></span>
                                            <span class="fa tog"></span>
                                        </div>
                                        <div class="settings_box_content hidden">
                                            <div class="input_container">
                                                <div class="input_container_inner">
                                                    <?php
                                                    $html = '';
                                                    $html.= ADNI_Templates::switch_btn(array(
                                                        'title' => __('Remove all Adning data when uninstalling','adn'),
                                                        'name' => 'uninstall_remove_data',
                                                        'checked' => $settings['uninstall_remove_data'],
                                                        'value' => 1,
                                                        'hidden_input' => 1,
                                                        'chk-on' => __('Yes','adn'),
                                                        'chk-off' => __('No','adn'),
                                                        'column' => array(
                                                            'size' => 'col-6',
                                                            'desc' => __('This will remove all Adning content + settings when you remove the plugin.','adn'),
                                                        )
                                                    ));
                                                    echo $html;
                                                    ?>
                                                </div>
                                                <span class="description bottom"><?php _e('','adn'); ?></span>
                                            </div>
                                            <!-- end .input_container -->

                                            <?php
                                            echo ADNI_Templates::spr_column(array(
                                                'col' => 'spr_col',
                                                'title' => '',
                                                'desc' => '',
                                                'content' => '<input type="submit" value="'.esc_attr__('Save Changes','adn').'" class="button-primary" name="submit_btn">'
                                            ));
                                            ?>
                                        </div>
                                        <!-- end .settings_box_content -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .spr_column -->


                        


                    </div>
                    <!-- end RIGHT COLUMN .spr_column -->


                </div>
                <!-- end .spr_row -->

                <!--<input type="submit" name="submit_btn" id="submit_btn" class="button button-primary" value="<?php _e('Save Changes','adn'); ?>" style="display: inline-block;width: auto;">-->
            </form>
         
        </div>
        <!-- end .container -->

    </div>
    <!-- end .wrap -->
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

    Adning_global.activate_tooltips($('.adning_dashboard'));

	$(".spr_column").inViewport(function(px){
		var animation = $(this).data('animation');
		if( typeof animation !== 'undefined' && animation != ''){
			if(px) $(this).addClass(animation+" spr_visible animated");
		}
	}, {padding:0});
});
</script>