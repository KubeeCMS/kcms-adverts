<?php
/**
 * POST
*/
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['save_role_settings']))
	{
        //echo '<pre>'.print_r($_POST, true).'</pre>';
        // Find lowest role with Manage ALL previleges
        //$roles = ADNI_Main::default_role_options();
        $settings = ADNI_Main::settings();
        $edit_roles = $settings['roles'];
        
        foreach($_POST['roles'] as $role_name => $role_info)
        {
            //echo '<pre>'.print_r($role_info, true).'</pre>';
            if($role_info['manage_banners'])
            {
                $edit_roles['create_banner_role'] = $role_name;
            }
            if($role_info['manage_all_banners'])
            {
                $edit_roles['manage_all_banners_role'] = $role_name;
            }
            // Adzones
            if($role_info['manage_adzones'])
            {
                $edit_roles['create_adzone_role'] = $role_name;
            }
            if($role_info['manage_all_adzones'])
            {
                $edit_roles['manage_all_adzones_role'] = $role_name;
            }
            // Campaigns
            if($role_info['manage_campaigns'])
            {
                $edit_roles['create_campaign_role'] = $role_name;
            }
            if($role_info['manage_all_campaigns'])
            {
                $edit_roles['manage_all_campaigns_role'] = $role_name;
            }
        }

        //echo '<pre>'.print_r($edit_roles, true).'</pre>';
        ADNI_CPT::add_custom_caps(array('role' => $edit_roles['create_banner_role'], 'cpt' => ADNI_CPT::$banner_cpt));
        ADNI_CPT::add_custom_caps(array('role' => $edit_roles['create_adzone_role'], 'cpt' => ADNI_CPT::$adzone_cpt));
        ADNI_CPT::add_custom_caps(array('role' => $edit_roles['create_campaign_role'], 'cpt' => ADNI_CPT::$campaign_cpt));
        ADNI_Multi::update_option('_adning_roles', $edit_roles);
        ADNI_Multi::update_option('_adning_admin_roles', $_POST['roles']);
        
		/*ADNI_CPT::add_custom_caps(array('role' => $_POST['create_banner_role'], 'cpt' => ADNI_CPT::$banner_cpt));
        ADNI_CPT::add_custom_caps(array('role' => $_POST['create_adzone_role'], 'cpt' => ADNI_CPT::$adzone_cpt));
        ADNI_CPT::add_custom_caps(array('role' => $_POST['create_campaign_role'], 'cpt' => ADNI_CPT::$campaign_cpt));
        ADNI_Multi::update_option('_adning_roles', $_POST);*/
	}
}

//echo '<pre>'.print_r($role->capabilities,true).'</pre>';//ADNI_Main::capabilities(array('role' => 'administrator'));
$settings = ADNI_Main::settings();
$h = '';
//echo '<pre>'.print_r($settings,true).'</pre>';
?>


<!-- Wordpress Messages -->
<h2 class="messages-position"></h2>

<div class="adning_dashboard adning_cont">
	<div class="wrap">
       
        <?php echo ADNI_Templates::main_admin_header(array(
            'page' => 'role-manager',
            'title' => 'Adning Role Manager',
            'desc' => '⚡ ' . __('Adning is designed in a very modular fashion so that lots of functions are customizable. Should you wish to, you can find most general settings below.','adn')
        )); ?>

        <div class="container">
            <form action="" method="post" enctype="multipart/form-data">  
                <?php
                /**
                 * ROLE SETTINGS
                */
                $h = '';
                if( current_user_can(ADNI_ADMIN_ROLE))
                {
                    //$roles = get_editable_roles();
                    //unset($roles['subscriber']);
                    $roles = ADNI_Main::default_role_options();
                    $i = 0;
                    foreach( $roles as $role_name => $role_info )
                    {
                        $closed = $i ? ' closed' : '';
                        $hidden = $i ? ' hidden' : '';
                        $h.= '<div class="spr_row role_manager">';
                            $h.= '<div class="spr_column">';
                                $h.= '<div class="spr_column-inner">';
                                    $h.= '<div class="spr_wrapper">';
                                        $h.= '<div class="option_box'.$closed.'">';

                                            $h.= '<div class="info_header">';
                                                $h.= '<span class="nr"><i class="input_icon fa fa-user" aria-hidden="true"></i></span>';
                                                $h.= '<span class="text">'.ucfirst($role_name).'</span>';
                                                $h.= '<span class="fa tog ttip" title="'.__('Toggle box','adn').'"></span>';
                                            $h.= '</div>';
                                            
                                            $h.= '<div class="settings_box_content'.$hidden.'">';

                                                $types = array('banners','adzones','campaigns');
                                                
                                                $c = 0;
                                                foreach($types as $type)
                                                {
                                                    $h.= '<div class="spr_row">';
                                                        $h.= '<div class="spr_column">';
                                                            $h.= '<div class="spr_column-inner">';
                                                                $h.= '<div class="spr_wrapper">';
                                                                    $h.= '<div class="input_container">';

                                                                        $close = $c ? ' closed' : '';
                                                                        $hide = $c ? ' hidden' : '';
                                                                        $h.= '<div class="adn_settings_cont'.$close.'">';
                                                                            $h.= '<h4>'.ucfirst($type).' <span class="fa togg"></span></h4>'; // $role_info['name']
                                                                            $h.= '<div class="set_box_content'.$hide.'">';
                                                                                $h.= ADNI_Templates::spr_column(array(
                                                                                    'col' => 'spr_col-2',
                                                                                    'title' => '',
                                                                                    'desc' => sprintf(esc_attr__('Manage %s','adn'), ucfirst($type)),
                                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                                        'name' => 'roles['.$role_name.'][manage_'.$type.']',
                                                                                        'tooltip' => sprintf(esc_attr__('Give admin privileges to manage %s.','adn'), $type),
                                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_'.$type],
                                                                                        'value' => 1,
                                                                                        'hidden_input' => 1,
                                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                                        'chk-high' => 0
                                                                                    ))
                                                                                ));
                                                                                $h.= ADNI_Templates::spr_column(array(
                                                                                    'col' => 'spr_col-2',
                                                                                    'title' => '',
                                                                                    'desc' => sprintf(esc_attr__('Manage ALL %s','adn'), ucfirst($type)),
                                                                                    'class' => 'all_check_cont',
                                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                                        'name' => 'roles['.$role_name.'][manage_all_'.$type.']',
                                                                                        'tooltip' => sprintf(esc_attr__('Give admin privileges to manage ALL %s.','adn'), $type),
                                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_all_'.$type],
                                                                                        'value' => 1,
                                                                                        'hidden_input' => 1,
                                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                                        'chk-high' => 0
                                                                                    ))
                                                                                ));

                                                                                $h.= '<div class="clearFix"></div>';
                                                                            $h.= '</div>';
                                                                        $h.= '</div>';

                                                                    $h.= '</div>';
                                                                $h.= '</div>';
                                                            $h.= '</div>';
                                                        $h.= '</div>';
                                                    $h.= '</div>';
                                                    $c++;
                                                }

                                                $h.= ADNI_Templates::spr_column(array(
                                                    'col' => 'spr_col',
                                                    'title' => '',
                                                    'desc' => '',
                                                    'content' => '<input type="submit" value="'.__('Save Role Settings','adn').'" class="button-primary" name="save_role_settings">'
                                                ));

                                            $h.= '</div>';
                                            // end .settings_box_content

                                        $h.= '</div>';
                                        // end .option_box
                                    $h.= '</div>';
                                $h.= '</div>';
                            $h.= '</div>';
                        $h.= '</div>';
                        $i++;
                    }
                }
                echo $h;
                ?>
            </form>

            <?php
            /*
            $h = '';
            if( current_user_can(ADNI_ADMIN_ROLE))
            {
                ?>
                <div class="spr_row role_manager"> 
                    <form action="" method="post" enctype="multipart/form-data">  
                    <div class="spr_column">
                    <div class="spr_column-inner">
                            <div class="spr_wrapper">
                            
                            <div class="option_box">
                                <div class="info_header">
                                    <span class="nr"><i class="input_icon fa fa-cog" aria-hidden="true"></i></span>
                                    <span class="text"><?php _e('Admin Role Settings','adn'); ?></span>
                                    <input type="submit" value="<?php _e('Save Role Settings','adn'); ?>" class="button-primary" name="save_role_settings" style="width:auto;float:right;margin:8px;">
                                </div>
                                <div class="spr_row"> 
                                    
                                    <div class="spr_column"> 
                                        <div class="spr_column-inner">
                                            <div class="spr_wrapper">
                                                <div class="input_container">
                                                    <?php
                                                    //$roles = get_editable_roles();
                                                    //unset($roles['subscriber']);
                                                    $roles = ADNI_Main::default_role_options();
                                                    $i = 0;
                                                    foreach( $roles as $role_name => $role_info )
                                                    {
                                                        if( !array_key_exists($role_name, $settings['roles']))
                                                        {
                                                            $settings['roles'][$role_name] = ADNI_Main::default_role_options($role_name);
                                                        }

                                                        $closed = $i ? ' closed' : '';
                                                        $hidden = $i ? ' hidden' : '';
                                                        $h.= '<div class="adn_settings_cont'.$closed.'">';
                                                            $h.= '<h4>'.ucfirst($role_name).' <span class="fa togg"></span></h4>'; // $role_info['name']
                                                            $h.= '<div class="set_box_content'.$hidden.'">';
                                                                
                                                                // BANNERS 
                                                                $h.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-2',
                                                                    'title' => __('Banners','adn'),
                                                                    'desc' => esc_attr__('Manage Banners','adn'),
                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                        'name' => 'roles['.$role_name.'][manage_banners]',
                                                                        'tooltip' => esc_attr__('Give admin privileges to manage banners.','adn'),
                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_banners'],
                                                                        'value' => 1,
                                                                        'hidden_input' => 1,
                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                        'chk-high' => 0
                                                                    ))
                                                                ));
                                                                $h.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-2',
                                                                    'title' => '&nbsp;',
                                                                    'desc' => esc_attr__('Manage All Banners','adn'),
                                                                    'class' => 'all_check_cont',
                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                        'name' => 'roles['.$role_name.'][manage_all_banners]',
                                                                        'tooltip' => esc_attr__('Give admin privileges to manage ALL banners.','adn'),
                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_all_banners'],
                                                                        'value' => 1,
                                                                        'hidden_input' => 1,
                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                        'chk-high' => 0
                                                                    ))
                                                                ));

                                                                // ADZONES
                                                                $h.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-2',
                                                                    'title' => __('Adzones','adn'),
                                                                    'desc' => esc_attr__('Manage Adzones','adn'),
                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                        'name' => 'roles['.$role_name.'][manage_adzones]',
                                                                        'tooltip' => esc_attr__('Give admin privileges to manage adzones.','adn'),
                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_adzones'],
                                                                        'value' => 1,
                                                                        'hidden_input' => 1,
                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                        'chk-high' => 0
                                                                    ))
                                                                ));
                                                                $h.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-2',
                                                                    'title' => '&nbsp;',
                                                                    'desc' => esc_attr__('Manage All Adzones','adn'),
                                                                    'class' => 'all_check_cont',
                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                        'name' => 'roles['.$role_name.'][manage_all_adzones]',
                                                                        'tooltip' => esc_attr__('Give admin privileges to manage ALL adzones.','adn'),
                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_all_adzones'],
                                                                        'value' => 1,
                                                                        'hidden_input' => 1,
                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                        'chk-high' => 0
                                                                    ))
                                                                ));
                                                                

                                                                // CAMPAIGNS
                                                                $h.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-2',
                                                                    'title' => __('Campaigns','adn'),
                                                                    'desc' => esc_attr__('Manage Campaigns','adn'),
                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                        'name' => 'roles['.$role_name.'][manage_campaigns]',
                                                                        'tooltip' => esc_attr__('Give admin privileges to manage campaigns.','adn'),
                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_campaigns'],
                                                                        'value' => 1,
                                                                        'hidden_input' => 1,
                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                        'chk-high' => 0
                                                                    ))
                                                                ));
                                                                $h.= ADNI_Templates::spr_column(array(
                                                                    'col' => 'spr_col-2',
                                                                    'title' => '&nbsp;',
                                                                    'desc' => esc_attr__('Manage All Campaigns','adn'),
                                                                    'content' => ADNI_Templates::switch_btn(array(
                                                                        'name' => 'roles['.$role_name.'][manage_all_campaigns]',
                                                                        'tooltip' => esc_attr__('Give admin privileges to manage ALL campaigns.','adn'),
                                                                        'checked' => $settings['admin_roles'][$role_name]['manage_all_campaigns'],
                                                                        'value' => 1,
                                                                        'hidden_input' => 1,
                                                                        'chk-on' => esc_attr__('Yes','adn'),
                                                                        'chk-off' => esc_attr__('No','adn'),
                                                                        'chk-high' => 0
                                                                    ))
                                                                ));
                                                                
                                                                $h.= '<div class="clearFix"></div>';
                                    
                                                            $h.= '</div>';
                                                        $h.= '</div>';
                                                        //print_r($role_info);

                                                        $i++;
                                                    }
                                                    echo $h;
                                                    ?>
                                                    <div class="clearFix"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    ?>
                                    
                                </div>
                                <!-- end .spr_row -->
                            </div>
                            <!-- end .option_box -->
                            
                        </div>
                        </div>
                </div>
                </form>
                </div>
                <!-- end .spr_row -->
                <?php
                }
                */
            ?>
        
        </div>
        <!-- end .container -->
        
    </div>
    <!-- end .wrap -->
</div>
<!-- end .adning_dashboard -->



<script type="text/javascript">
jQuery(document).ready(function($) {
	$(".spr_column").inViewport(function(px){
		var animation = $(this).data('animation');
		if( typeof animation !== 'undefined' && animation != ''){
			if(px) $(this).addClass(animation+" spr_visible animated");
		}
	}, {padding:0});
});
</script>