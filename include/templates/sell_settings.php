<?php
if(isset($_GET['remove_order']) && !empty($_GET['remove_order']))
{
    ADNI_Sell::remove_order($_GET['remove_order']);
}
if(isset($_GET['activate_order']) && !empty($_GET['activate_order']))
{
    ADNI_Sell::activate_order($_GET['activate_order']);
}

do_action( 'ADNI_sell_settings_get', $_GET );


$set_arr = ADNI_Main::settings();
$settings = $set_arr['settings'];



/**
 * IF POST DATA
*/
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['submit_btn']))
    {
        //echo '<pre>'.print_r($_POST,true).'</pre>';
        unset($_POST['submit_btn']);
        foreach($_POST as $key => $post){
            $settings['sell'][$key] = $_POST[$key];
        }
        
        //$settings['sell'] = ADNI_Main::handle_form_fields($_POST, $settings['sell']);
        $settings['sell'] = apply_filters( 'ADNI_save_sell_settings', $settings['sell'] );

        //echo '<pre>'.print_r($settings,true).'</pre>';
        // UPDATE SETTINGS
        ADNI_Multi::update_option('_adning_settings', $settings);
    }
}

//unset($settings['sell']['payment']);
//ADNI_Multi::update_option('_adning_settings', $settings);
//echo '<pre>'.print_r($settings,true).'</pre>';
?>

<!-- Wordpress Messages -->
<h2 class="messages-position"></h2>

<div class="adning_dashboard adning_cont">
	<div class="wrap">
       
        <?php echo ADNI_Templates::main_admin_header(array(
            'page' => 'sell',
            'title' => 'Adning Sell Settings',
            'desc' => '⚡ ' . __('Allow users to buy advertisement spots on your website and manage their own banners.','adn')
        )); ?>

        <div class="container">

            <form action="" method="post">
                <div class="spr_row">  
                    <div class="spr_column spr_col-8">
                        <div class="spr_column-inner left_column">
                            <div class="spr_wrapper">
                                <div class="option_box">
                                    <div class="info_header">
                                        <span class="nr">
                                        <svg viewBox="0 0 576 512"><path fill="currentColor" d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z"></path></svg>
                                        </span>
                                        <span class="text"><?php _e('Payment Settings','adn'); ?></span>
                                        <span class="fa tog"></span>
                                    </div>
                                    <!-- end .info_header -->
                                    <div class="settings_box_content">
                                        <div class="input_container">
                                            <div class="input_container_inner">
                                                <?php
                                                $h = '';
                                                if( !empty($settings['sell']['payment']))
                                                {
                                                    foreach( $settings['sell']['payment'] as $key => $payment)
                                                    {
                                                        $form_item = ADNI_Sell::sell_payment_option_forms($settings, $key);

                                                        //$h.= '<input type="hidden" name="payment['.$key.'][title]" value="'.$payment['title'].'">';
                                                        $h.= '<div class="adn_settings_cont payment_settings_cont closed">';
                                                            $h.= '<h4>'.$form_item['logo'].sprintf(__('%s Settings','adn'), $form_item['title']).' <span class="fa togg"></span></h4>';
                                                            $h.= '<div class="set_box_content hidden">';
                                                                $h.= '<div class="adn_settings_cont_inner clear">';
                                                                    $h.= '<p>'.__('','adn').'</p>';

                                                                    //echo '<pre>'.print_r(ADNI_Sell::sell_payment_option_forms($settings, $key),true).'</pre>';

                                                                    $h.= ADNI_Templates::spr_column(array(
                                                                        'col' => 'spr_col-2',
                                                                        'title' => __('Activate','adn'),
                                                                        'desc' => sprintf(__('Allow %s payments.'), $form_item['title']),
                                                                        'content' => ADNI_Templates::switch_btn(array(
                                                                            'name' => 'payment['.$key.'][active]',
                                                                            'checked' => $payment['active'],
                                                                            'value' => 1,
                                                                            'hidden_input' => 1,
                                                                            'chk-on' => __('Yes','adn'),
                                                                            'chk-off' => __('No','adn')
                                                                        ))
                                                                    ));

                                                                    
                                                                    if( array_key_exists('form', $form_item))
                                                                    {
                                                                        if(!empty($form_item['form']))
                                                                        {
                                                                            //echo '<pre>'.print_r($payment['form'],true).'</pre>'; //['type'];
                                                                            foreach($form_item['form'] as $i => $item )
                                                                            {
                                                                                $h.= $item['html'];
                                                                            }
                                                                        }
                                                                    }


                                                                    if( array_key_exists('info', $form_item))
                                                                    {
                                                                        $h.= '<div class="clearFix"></div>';
                                                                        $h.= ADNI_Templates::spr_column(array(
                                                                            'col' => 'spr_col',
                                                                            'title' => '<strong>'.__('Info','adn').'</strong>',
                                                                            'content' => $form_item['info']
                                                                        ));
                                                                    }

                                                                $h.= '</div>';
                                                            $h.= '</div>';
                                                        $h.= '</div>';
                                                    }
                                                }
                                                
                                                echo $h;
                                                ?>
                                            </div>
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
                                <!-- end .option_box -->
                                
                                <div class="option_box">
                                    <div class="info_header">
                                        <span class="nr">
                                        <svg viewBox="0 0 512 512"><path fill="currentColor" d="M507.73 109.1c-2.24-9.03-13.54-12.09-20.12-5.51l-74.36 74.36-67.88-11.31-11.31-67.88 74.36-74.36c6.62-6.62 3.43-17.9-5.66-20.16-47.38-11.74-99.55.91-136.58 37.93-39.64 39.64-50.55 97.1-34.05 147.2L18.74 402.76c-24.99 24.99-24.99 65.51 0 90.5 24.99 24.99 65.51 24.99 90.5 0l213.21-213.21c50.12 16.71 107.47 5.68 147.37-34.22 37.07-37.07 49.7-89.32 37.91-136.73zM64 472c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"></path></svg>
                                        </span>
                                        <span class="text"><?php _e('Frontend AD Manager Settings','adn'); ?></span>
                                        <span class="fa tog"></span>
                                    </div>
                                    <!-- end .info_header -->
                                    <div class="settings_box_content">
                                    <div class="input_container">
                                        <div class="input_container_inner">

                                            <p>
                                                <?php _e('All purchases are handled on the frontend.','adn'); ?> <a href="<?php echo get_bloginfo('url'); ?>/?_ning_front=1" target="_blank"><?php _e('Frontend AD Manager','adn'); ?></a>
                                            </p>
                                            <p><?php _e('By default we use the custom Adning frontend pages. If you want to keep users inside your website you can create your own pages using the shortcodes below. In that case make sure to update the page URLs.'); ?></p>

                                            <?php
                                            $h = '';
                                            $h.= '<div class="adn_settings_cont closed">';
                                                $h.= '<h4>'.__('Page URLs','adn').' <span class="fa togg"></span></h4>';
                                                $h.= '<div class="set_box_content hidden">';
                                                    $h.= '<div class="adn_settings_cont_inner clear">';
                                                        $h.= '<p>'.__('','adn').'</p>';

                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col',
                                                            'title' => __('Order Form / Available Adzones','adn'),
                                                            'desc' => __('Link to the page with the available adzones/ order form. Shortcode: <strong>[adning_available_adzones]</strong>','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'urls[available_adzones]',
                                                                'value' => $settings['sell']['urls']['available_adzones'],
                                                                'placeholder' => '',
                                                                'icon' => 'link',
                                                                'show_icon' => 1
                                                            ))
                                                        ));
                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col',
                                                            'title' => __('User Dashboard','adn'),
                                                            'desc' => __('Link to the user dashboard page. Shortcode: <strong>[adning_user_dashboard]</strong>','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'urls[user_dashboard]',
                                                                'value' => $settings['sell']['urls']['user_dashboard'],
                                                                'placeholder' => '',
                                                                'icon' => 'link',
                                                                'show_icon' => 1
                                                            ))
                                                        ));
                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col',
                                                            'title' => __('Edit Banner','adn'),
                                                            'desc' => __('Link to the edit banner page. Shortcode: <strong>[adning_edit_banner]</strong>','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'urls[edit_banner]',
                                                                'value' => $settings['sell']['urls']['edit_banner'],
                                                                'placeholder' => '',
                                                                'icon' => 'link',
                                                                'show_icon' => 1
                                                            ))
                                                        ));
                                                    $h.= '</div>';
                                                $h.= '</div>';
                                            $h.= '</div>';


                                            $h.= '<div class="adn_settings_cont closed">';
                                                $h.= '<h4>'.__('Template Settings','adn').' <span class="fa togg"></span></h4>';
                                                $h.= '<div class="set_box_content hidden">';
                                                    $h.= '<div class="adn_settings_cont_inner clear">';
                                                        $h.= '<p>'.__('','adn').'</p>';

                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col-6',
                                                            'title' => __('AD Manager Title','adn'),
                                                            'desc' => __('Your website AD manager title.','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'template[logo_title]',
                                                                'value' => $settings['sell']['template']['logo_title'],
                                                                'placeholder' => 'Adning',
                                                                'icon' => 'pencil',
                                                                'show_icon' => 1
                                                            ))
                                                        ));

                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col-6',
                                                            'title' => __('Side Title','adn'),
                                                            'desc' => __('AD Manager side title.','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'template[side_title]',
                                                                'value' => $settings['sell']['template']['side_title'],
                                                                'placeholder' => 'Frontend AD Manager',
                                                                'icon' => 'pencil',
                                                                'show_icon' => 1
                                                            ))
                                                        ));

                                                        $h.= '<div class="clearFix"></div>';
                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col-4',
                                                            'title' => __('Footer Info Line','adn'),
                                                            'desc' => __('Description in footer.','adn'),
                                                            'content' => ADNI_Templates::textarea_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'template[footer_info]',
                                                                'value' => stripslashes($settings['sell']['template']['footer_info']),
                                                                'placeholder' => '',
                                                                'icon' => 'pencil',
                                                                'show_icon' => 1
                                                            ))
                                                        ));

                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col-4',
                                                            'title' => __('Footer Copyright','adn'),
                                                            'desc' => __('Name of the copyright.','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'template[footer_copy]',
                                                                'value' => $settings['sell']['template']['footer_copy'],
                                                                'placeholder' => '',
                                                                'icon' => 'pencil',
                                                                'show_icon' => 1
                                                            ))
                                                        ));

                                                        $h.= ADNI_Templates::spr_column(array(
                                                            'col' => 'spr_col-4',
                                                            'title' => __('Footer Copyright URL','adn'),
                                                            'desc' => __('URL of the copyright.','adn'),
                                                            'content' => ADNI_Templates::inpt_cont(array(
                                                                'type' => 'text',
                                                                'width' => '100%',
                                                                'name' => 'template[footer_copy_url]',
                                                                'value' => $settings['sell']['template']['footer_copy_url'],
                                                                'placeholder' => '',
                                                                'icon' => 'link',
                                                                'show_icon' => 1
                                                            ))
                                                        ));

                                                    $h.= '</div>';
                                                $h.= '</div>';
                                            $h.= '</div>';
                                            echo $h;
                                            ?>
                                        </div>
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
                                <!-- end .option_box -->

                            </div>
                        </div>
                        <!-- end .spr_column-inner -->
                    </div>
                    <!-- end .spr_column -->

                    <div class="spr_column spr_col-4">
                        <div class="spr_column-inner left_column">
                            <div class="spr_wrapper">
                                <div class="option_box">
                                    <div class="info_header">
                                        <span class="nr">
                                        <svg viewBox="0 0 288 512"><path fill="currentColor" d="M209.2 233.4l-108-31.6C88.7 198.2 80 186.5 80 173.5c0-16.3 13.2-29.5 29.5-29.5h66.3c12.2 0 24.2 3.7 34.2 10.5 6.1 4.1 14.3 3.1 19.5-2l34.8-34c7.1-6.9 6.1-18.4-1.8-24.5C238 74.8 207.4 64.1 176 64V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48h-2.5C45.8 64-5.4 118.7.5 183.6c4.2 46.1 39.4 83.6 83.8 96.6l102.5 30c12.5 3.7 21.2 15.3 21.2 28.3 0 16.3-13.2 29.5-29.5 29.5h-66.3C100 368 88 364.3 78 357.5c-6.1-4.1-14.3-3.1-19.5 2l-34.8 34c-7.1 6.9-6.1 18.4 1.8 24.5 24.5 19.2 55.1 29.9 86.5 30v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-48.2c46.6-.9 90.3-28.6 105.7-72.7 21.5-61.6-14.6-124.8-72.5-141.7z"></path></svg>
                                        </span>
                                        <span class="text"><?php _e('Sell Settings','adn'); ?></span>
                                        <span class="fa tog"></span>
                                    </div>
                                    <!-- end .info_header -->
                                    <div class="settings_box_content">
                                        <div class="input_container">
                                            <div class="input_container_inner">
                                                <?php
                                                $h = '';
                                                $h.= ADNI_Templates::spr_column(array(
                                                    'col' => 'spr_col',
                                                    'title' => __('Currency','adn'),
                                                    'desc' => __('Select the currency for the payments.'),
                                                    'content' => ADNI_Templates::select_cont(array(
                                                        'name' => 'cur',
                                                        'value' => $settings['sell']['cur'],
                                                        'select_opts' => ADNI_Sell::currencies()
                                                    ))
                                                ));
                                                
                                                echo $h;
                                                ?>
                                            </div>
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
                        <!-- end .spr_column-inner -->
                    </div>
                    <!-- end .spr_column -->

                </div>
                <!-- end .spr_row -->
            </form>

            

            
            <div class="spr_row">  
                <div class="spr_column spr_col">
                    <div class="spr_column-inner left_column">
                        <div class="spr_wrapper">
                            
                            <div id="orders" class="option_box">
                                <div class="info_header">
                                    <span class="nr">
                                    <svg viewBox="0 0 576 512"><path fill="currentColor" d="M528.12 301.319l47.273-208C578.806 78.301 567.391 64 551.99 64H159.208l-9.166-44.81C147.758 8.021 137.93 0 126.529 0H24C10.745 0 0 10.745 0 24v16c0 13.255 10.745 24 24 24h69.883l70.248 343.435C147.325 417.1 136 435.222 136 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-15.674-6.447-29.835-16.824-40h209.647C430.447 426.165 424 440.326 424 456c0 30.928 25.072 56 56 56s56-25.072 56-56c0-22.172-12.888-41.332-31.579-50.405l5.517-24.276c3.413-15.018-8.002-29.319-23.403-29.319H218.117l-6.545-32h293.145c11.206 0 20.92-7.754 23.403-18.681z"></path></svg>
                                    </span>
                                    <span class="text"><?php _e('Orders','adn'); ?></span>
                                    <span class="fa tog"></span>
                                </div>
                                <!-- end .info_header -->
                                <div class="settings_box_content">
                                    <div class="input_container">
                                        <div class="input_container_inner">
                                            <?php echo ADNI_Sell::admin_dashboard(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end .option_box -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- end .spr_row -->


        </div>
        <!-- end .container -->
    </div>
    <!-- end .wrap -->
</div>
<!-- end .adning_dashboard -->