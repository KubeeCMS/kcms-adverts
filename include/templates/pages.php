<?php
$h = '';
if(isset($_GET['view']) && $_GET['view'] == 'banner')
{
	require_once( ADNI_TPL_DIR.'/single_banner.php');
	echo $h;
}
elseif(isset($_GET['view']) && $_GET['view'] == 'adzone')
{
	require_once( ADNI_TPL_DIR.'/single_adzone.php');
}
elseif(isset($_GET['view']) && $_GET['view'] == 'campaign')
{
	require_once( ADNI_TPL_DIR.'/single_campaign.php');
}
else
{
	require_once( ADNI_TPL_DIR.'/dashboard.php');
}
?>