<?php
/**
 * Adning Uninstall
 *
 * Uninstalling Adning
 *
 * @author 		Tunafish
 * @category 	Core
 * @package 	adning/Uninstaller
 */
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

global $wpdb, $wp_roles, $wp_version;

// NOTE: Calling plugin functions / classes here does not work!
// https://wordpress.stackexchange.com/a/115471/30732
$settings = get_option('_adning_settings', array());

if( !empty($settings['uninstall_remove_data']) )
{
	// Delete options
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'adning_%';");
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_adning_%';");
	
	// Delete posts + data
	$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'adni_banners', 'adni_adzones', 'adni_campaigns' );" );
	$wpdb->query( "DELETE FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE wp.ID IS NULL;" );
}