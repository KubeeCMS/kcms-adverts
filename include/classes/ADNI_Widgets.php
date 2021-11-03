<?php
class ADNI_Widgets extends WP_Widget 
{
	public function __construct(){
		global $pro_ads_codex;
		$widget_ops = array('classname' => '', 'description' => __( 'Display Adning banners or adzones into widget areas.','adn') );
		parent::__construct('ADNI_Widgets', '⚡ '.__('Adning ADS.','adn'), $widget_ops);
	}

    function widget($args, $instance) 
	{
		extract($args);
		/***
		 * Multisite ___________________________________________________________________ */
		ADNI_Multi::wpmu_load_from_main_start();
			
		if( !empty( $instance['banner_id'] ))
		{
			echo $before_widget;
				$sc = '[adning id="'.$instance['banner_id'].'"]';
				echo ADNI_Multi::do_shortcode($sc);
			echo $after_widget;
		}
		elseif( !empty( $instance['adzone_id'] ))
		{
            echo $before_widget;
                $sc = '[adning id="'.$instance['adzone_id'].'"]';
				//$posttype = get_post_type($instance['adzone_id']);
				//$sc = $posttype == 'adzones' ? '[adning id="'.$instance['adzone_id'].'"]' : '[adning id="'.$instance['adzone_id'].'"]';
				echo ADNI_Multi::do_shortcode($sc);
				//echo do_shortcode("[pro_ad_display_adzone id=".$instance['adzone_id']."]");
			echo $after_widget;
		}
		elseif( !empty( $instance['pas_shortcode'] ))
		{
			echo $before_widget;
				echo ADNI_Multi::do_shortcode($instance['pas_shortcode']);
			echo $after_widget;
		}
		
		ADNI_Multi::wpmu_load_from_main_stop();
	}
	
	
	function update($new_instance,$old_instance) {
		$instance = $old_instance;
		//$instance['title'] = strip_tags($new_instance['title']);
		$instance['banner_id'] = $new_instance['banner_id'];
		$instance['adzone_id'] = $new_instance['adzone_id'];
		$instance['pas_shortcode'] = $new_instance['pas_shortcode'];

		return $instance;
	}

	function form($instance) 
	{	
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'banner_id' => '', 'adzone_id' => '', 'pas_shortcode' => ''));
		
		/***
		 * Multisite ___________________________________________________________________ */
		ADNI_Multi::wpmu_load_from_main_start();
		    $banners = ADNI_CPT::get_posts(array(
                'post_type'  => ADNI_CPT::$banner_cpt,
                /*'meta_query' => array(
                    array(
                        'key'     => '_adning_args[status]',
                        'value'   => 'active',
                        'compare' => '=',
                    ),
                )*/
            ));
		ADNI_Multi::wpmu_load_from_main_stop();
		// BANNERS
		?>
        <!-- BANNERS -->
        <p>
        	<label for="<?php echo $this->get_field_id('banner_id'); ?>"><?php _e('Select a Banner:', 'adn'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'banner_id' ); ?>" name="<?php echo $this->get_field_name( 'banner_id' ); ?>" style="width:100%;">
           	<option value=""><?php _e('-- Select a Banner --', 'adn'); ?></option>
            	<?php
				foreach( $banners as $i => $banner )
				{
					?>
                    <option value="<?php echo $banner->ID; ?>" <?php if ( $banner->ID == $instance['banner_id'] ) echo 'selected="selected"'; ?>>
						<?php echo $banner->post_title; ?>
                    </option>
                    <?php
				}
				?>
           </select>
        </p>
        
        <!-- ADZONES -->
        <p>
        	<label for="<?php echo $this->get_field_id('adzone_id'); ?>"><?php _e('Select an Adzone:', 'adn'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'adzone_id' ); ?>" name="<?php echo $this->get_field_name( 'adzone_id' ); ?>" style="width:100%;">
            	
                <option value=""><?php _e('-- Select an Adzone --', 'adn'); ?></option>
                
            	<?php
                ADNI_Multi::wpmu_load_from_main_start();
                $adzones = ADNI_CPT::get_posts(array(
                    'post_type'  => ADNI_CPT::$adzone_cpt,
                ));
		
				foreach( $adzones as $i => $adzone )
				{
					?>
					<option value="<?php echo $adzone->ID; ?>" <?php if ( $adzone->ID == $instance['adzone_id'] ) echo 'selected="selected"'; ?>>
						<?php echo get_the_title($adzone->ID); ?>
                    </option>
                    <?php
				}
				
				/***
				 * Multisite ___________________________________________________________________ */
				ADNI_Multi::wpmu_load_from_main_stop();
				?>
				
			</select>
		</p>
        <hr />
        <p>
        	<label for="<?php echo $this->get_field_id('pas_shortcode'); ?>"><?php _e('Or add the adzone shortcode:', 'adn'); ?></label><br />
            <textarea id="<?php echo $this->get_field_id( 'pas_shortcode' ); ?>" name="<?php echo $this->get_field_name( 'pas_shortcode' ); ?>" style="font-size:10px; width:100%;"><?php echo $instance['pas_shortcode']; ?></textarea>
		</p>
		<?php
	}
}  
?>