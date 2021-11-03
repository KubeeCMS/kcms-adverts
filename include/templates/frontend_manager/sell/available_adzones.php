<?php
$defaults = array();
$args = isset($args) && !empty($args) ? ADNI_Main::parse_args($args, $defaults) : array();

$h = '';


$h.= '<div class="adning_cont adning_available_adzones">
	<div class="wrap">';
       
        if( isset($_GET['adzone']) && !empty($_GET['adzone']) )
        {
            $h.= ADNI_Sell::order_form(array('id' => $_GET['adzone']));
        }
        else
        {
            $h.= ADNI_Sell::all_available_adzones($args); 
        }

        $h.= ADNI_Sell::faq_info_footer();
       
        

    $h.= '</div>
</div>';
?>