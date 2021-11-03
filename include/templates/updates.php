<?php
/**
 * IF POST DATA
*/
$notice = array();
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['submit_btn']))
    {
        if(!empty($_POST['license_key']))
        {
            $resp = ADNI_Activate::register(array('license-key' => $_POST['license_key']));
		    $notice[] = $resp->msg;
        }
    }
}

if( isset($_GET['deregister_plugin']))
{
    $activation = ADNI_Multi::get_option('adning_activation', array());
    if( !empty($activation))
    {
        $resp = ADNI_Activate::deregister(array('license-key' => $activation['license-key']));
        $notice[] = $resp['msg'];
    }
}

//$activation = get_option('adning_activation', array());
$activation = ADNI_Multi::get_option('adning_activation', array());
$active_support = ADNI_Activate::check_support();
?>


<!-- Wordpress Messages -->
<h2 class="messages-position"></h2>


<div class="adning_dashboard">
	<div class="wrap">
       
        <?php echo ADNI_Templates::main_admin_header(array(
            'page' => 'updates',
            'title' => 'Adning Product License',
            'desc' => '⚡ ' . __('In order to receive all benefits of Adning you need to activate your copy. By activating your Adning license you will unlock premium options like automatic plugin updates, install add-ons and official support.','adn')
        )); ?>

		<div class="container">

            <?php
            if( !empty($notice))
            {
                $h = '';
                $h.= '<div class="notifications" style="background: #ffffdd;padding: 10px;">';
                    foreach( $notice as $note)
                    {
                        $h.= '<div>'.$note.'</div>';
                    }
                $h.= '</div>';
                echo $h;
            }
            ?>
			
			
            <div class="spr_row">  
                <div class="spr_column">
                    <div class="spr_column-inner left_column">
                        <div class="spr_wrapper">
                            <div> <!-- class="option_box" -->
                                <div class="input_container">
                                    <div class="input_container_inner _imc_editor">
                                        <div style="margin:20px 0 30px 0;font-size: 14px;max-width: 960px;">

                                            <?php
                                            if( empty($activation))
                                            {
                                                ?>
                                                <form action="" method="post" id="imc-role-updates">
                                                    <div class="clear">
                                                        <div style="float:left;">
                                                            <input type="text" name="license_key" value="" placeholder="<?php _e('Add your license key here','adn'); ?>" style="padding: 13px;width: 500px;margin-right: -5px;background: #FFF;line-height: 1;" />
                                                        </div>
                                                        <div style="float:left;">
                                                            <input type="submit" name="submit_btn" class="button button-primary button-updater" value="<?php echo sprintf(__('Activate %s','adn'), 'Adning'); ?>" style="font-size: 14px;height: 46px;line-height: 44px;padding: 0 36px;margin-bottom:10px;">   
                                                        </div>
                                                    </div> 
                                                    <p class="description">
                                                        <a href="http://support.adning.com/docs/where-do-i-get-the-license-key/" target="_blank"><?php _e("Where do I find my license key?","adn"); ?></a>.				
                                                    </p>
                                                    <p class="description">
                                                        <?php _e("Don't have a license yet?","adn");?> <a href="https://codecanyon.net/item/wp-pro-advertising-system-all-in-one-ad-manager/269693" target="_blank"><?php echo sprintf(__('Purchase %s license','adn'), 'Adning'); ?></a>.				
                                                    </p>
                                                    
                                                </form>
                                                <?php
                                            }
                                            else
                                            {
                                                $h = '';
                                                $h.= '<h2>'.__('Plugin Activated','adn').'</h2>';
                                                $h.= '<ul>';
                                                    $h.= '<li>'.__('Support','adn').': '.$active_support['code'].'</li>';
                                                $h.= '</ul>';
                                                echo $h;

                                                $deactivate_url = esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning-updates&deregister_plugin=1')));
                                                ?>
                                                <a href="<?php echo $deactivate_url; ?>" class="button button-primary button-updater" style="font-size: 14px;height: 46px;line-height: 44px;padding: 0 36px;margin-bottom:10px;">  
                                                    <?php echo sprintf(__('Deactivate %s','adn'), 'Adning'); ?>
                                                </a>
                                                <?php
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end .spr_wrapper -->
                    </div>
                </div>
                <!-- end .spr_column -->
            </div>
            <!-- end .spr_row -->
			
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

