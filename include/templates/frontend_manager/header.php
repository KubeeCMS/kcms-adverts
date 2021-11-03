<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<?php if(function_exists('wp_head')) { wp_head(); } ?>

	<link href="<?php echo ADNI_ASSETS_URL; ?>/dist/angwp_admin.bundle.js.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo ADNI_ASSETS_URL; ?>/dist/angwp_frontend_manager.bundle.js.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="https://fonts.googleapis.com/css?family=Hind:300,700" rel="stylesheet">
</head>
<body <?php body_class('ning-frontend'); ?>>