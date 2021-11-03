<?php
$h = '';
if(isset($_GET['remove_order']) && !empty($_GET['remove_order']))
{
    ADNI_Sell::remove_order($_GET['remove_order']);
}


$h.= '<div class="adning_cont adning_my_adzones">
	<div class="wrap">';

        $h.= ADNI_Sell::user_dashboard();
        $h.= ADNI_Sell::faq_info_footer();

    $h.= '</div>
</div>';
?>