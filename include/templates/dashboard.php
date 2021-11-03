<!-- Wordpress Messages -->
<h2 class="messages-position"></h2>


<div class="adning_dashboard adning_cont">
	<div class="wrap">
       
		<?php
		$error = array();
		$activation = ADNI_Multi::get_option('adning_activation', array());
		$tip = empty($activation) && current_user_can(ADNI_ADMIN_ROLE) ? ' <span style="background-color:#d4ff00;">'.__('Tip: start by activting your Product License.','adn').'</span>' : '';

		$smartrackErr = apply_filters('adning_general_notice', 0);
        if( $smartrackErr != '' )
        {
            $error[] = array('type' => 'warning', 'msg' => $smartrackErr);
        }

		echo ADNI_Templates::main_admin_header(array(
			'title' => sprintf('Welcome to Adning - <em>v%s', ADNI_VERSION).'</em>',
			'desc' => '⚡ ' . __('Great! Thanks for choosing ADning. ADning is always improving and we would love to hear your feedback and suggestions. Let\'s get started!','adn').$tip,
			'tabs' => 0,
			'errors' => $error
		));
		
		if( current_user_can(ADNI_ADMIN_ROLE) )
		{
			?>
			<div>
				<a href="?page=adning-settings" class="button button-primary"><?php _e('Settings','adn'); ?></a>
			</div>
			<?php
		} 
		$tab = isset($_GET['tab']) ? $_GET['tab'] : 'faq';
		echo ADNI_Templates::about_tabs(array('tab' => $tab)); 
		?>

		<div class="container">
			
			<?php 
			if( $tab ==  'faq')
			{
				?>
				<div class="spr_row">  
					<div class="spr_column spr_hidden" data-animation="fadeInLeft">
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div> <!-- class="option_box" -->
									<div class="input_container">
										<div class="input_container_inner _imc_editor">
											<div style="margin: 20px 0 30px 0; font-size: 14px;max-width:960px;">
												<!--⚡-->
												<p style="font-size:15px;">
													<?php echo sprintf(__('Adning has complete online documentation available at our website: %s which covers everything related to Adning Advertisning starting from Installation and up to more advanced features like our Inner API.', 'adn'), '<a href="http://support.adning.com/docs" target="_blank">support.adning.com</a>'); ?>
												</p>
											</div>
											<?php
											if( current_user_can(ADNI_ADMIN_ROLE) )
											{
												?>
												<div>
													<a href="http://support.adning.com/docs/where-do-i-get-the-license-key/" target="_blank"><?php _e('Where do I get my license key?','adn'); ?></a>
												</div>
												<?php
											}
											?>

										</div>
									</div>
								</div>
							</div>
							<!-- end .spr_wrapper -->
						</div>
					</div>
					<!-- end .spr_column -->
				<?php
			}
			if( $tab ==  'addons')
			{
				// spr_hidden data-animation="fadeInLeft"
				?>
				<div class="spr_row">  
					<div class="spr_column spr_col"> 
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div> <!-- class="option_box" -->
									<div class="input_container">
										<div class="input_container_inner">
											<p>
												<?php _e('Add-ons extend the functionalities from the main Adning plugin. They are default Wordpress plugins and need to be installed just like any other Wordpress plugin. If your WordPress file permissions allow it we will install the add-ons automatically if you click on them.','adn'); ?>
											</p>
											<div class="ajax_errors"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="spr_column spr_col-6"> 
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div> <!-- class="option_box" -->
									<div class="input_container">
										<div class="input_container_inner">
											
											<div style="margin: 20px 0 30px 0; font-size: 14px;max-width:960px;">
												
												<h3><?php _e('Included Add-Ons','adn'); ?></h3>
												<?php
												$h = '';
												$activation = ADNI_Multi::get_option('adning_activation', array());
												$addOns = array(
													'smartrack' => array(
														'name' => 'smarTrack',
														'desc' => __('Statistics add-on to keep track of your banner/adzone stats.','adn'),
														'package' => ADNI_INC_URL.'/extensions/plugins/smartrack.zip'
													),
													'angwp_woo' => array(
														'name' => 'Adning WooCommerce',
														'desc' => __('Sell adzones on your website and handle payments using WooCommerce.','adn'),
														'package' => ADNI_INC_URL.'/extensions/plugins/angwp_woo.zip'
 													)
												);

												if( empty($activation))
												{
													$h.= '<p class="_ning_notices">'.sprintf(__('Please %s in order to install the Add-Ons.','adn'), '<a href="'.esc_url( wp_nonce_url( self_admin_url('admin.php?page=adning-updates'))).'">'.__('activate your license','adn').'</a>').'</p>';
												}
								
												$h.= '<ul>';
													foreach($addOns as $key => $addon)
													{
														$h.= '<li>';
															if( !empty($activation) && !is_plugin_active( $key.'/'.$key.'.php' ) ) 
															{
																$h.= '<a href="#" class="install_addon" data-plugin="'.$key.'" data-package="'.$addon['package'].'">'.$addon['name'].'</a>';
															}
															else
															{
																$h.= '<strong>'.$addon['name'].'</strong>';
															}
															$h.= '<p>';
																$h.= $addon['desc'];
															$h.= '</p>';
														$h.= '</li>';
													}
												$h.= '</ul>';
												echo $h;
												?>

                                               
                                                <?php/*
                                                <h3 style="margin-top:80px;"><?php _e('Premium Add-Ons','adn'); ?></h3>
												<ul>
													<li>
														
													</li>
                                                </ul>
												*/
												?>
                                                
                                                
											</div>

										</div>
									</div>
								</div>
							</div>
							<!-- end .spr_wrapper -->
						</div>
					</div>
					<!-- end .spr_column -->
				<?php
			}
			if( $tab ==  'resources')
			{
				?>
				<div class="spr_row">  
					<div class="spr_column spr_col-6 spr_hidden" data-animation="fadeInLeft">
						<div class="spr_column-inner left_column">
							<div class="spr_wrapper">
								<div> <!-- class="option_box" -->
									<div class="input_container">
										<div class="input_container_inner _imc_editor">
											<div style="margin: 20px 0 30px 0; font-size: 14px;max-width:960px;">
												<!--<h3><?php _e('Resources','adn'); ?></h3>-->
												<ul>
													<li><a href="http://adning.com" target="_blank"><?php _e('Official Website','adn'); ?></a></li>
													<li><a href="http://support.adning.com/docs" target="_blank"><?php _e('Official Documentation','adn'); ?></a></li>
												</ul>

                                               
                                                
                                                <h4 style="margin-top:80px;"><?php _e('Scripts','adn'); ?></h4>
												<ul>
													<li><a href="https://codecanyon.net/item/modaljs-most-complete-jquery-popupmodal-plugin/22918522" target="_blank">ModalJS</a></li>
													<li><a href="https://codecanyon.net/item/coloringpick-jquery-gradient-color-picker/21130553" target="_blank">ColoringPick</a></li>
                                                </ul>
                                                
                                                
											</div>

										</div>
									</div>
								</div>
							</div>
							<!-- end .spr_wrapper -->
						</div>
					</div>
					<!-- end .spr_column -->
				<?php
			}
			?>

			
		</div>
		<!-- end .container -->
        
        
        
    </div>
    <!-- end .wrap -->
</div>
<!-- end .adning_dashboard -->




<script type="text/javascript">
jQuery(document).ready(function($) {
	$('.install_addon').on('click', function(){
		var plugin = $(this).data('plugin'),
			package = $(this).data('package');

		$.ajax({
			type: "POST",
			url: _adn_.ajaxurl,
			dataType: "json",
			data: "action=ajax_install_plugin&package="+package+"&plugin="+plugin
		}).done(function( obj ) {
			if( obj.success ){
				console.log( 'Installed', obj );
				$('.ajax_errors').addClass('_ning_notices').html( 'Plugin installed successfully' );
			}else{
				console.log( 'Error: ', obj.data.message );
				$('.ajax_errors').addClass('_ning_notices').html( 'Error: '+ obj.data.message );
			}
		});
	});

	$(".spr_column").inViewport(function(px){
		var animation = $(this).data('animation');
		if( typeof animation !== 'undefined' && animation != ''){
			if(px) $(this).addClass(animation+" spr_visible animated");
		}
	}, {padding:0});
});
</script>

