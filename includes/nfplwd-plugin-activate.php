<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//ACTIVATE

//plugin activate function
if(!function_exists('nfplwd_plugin_activate')){

	function nfplwd_plugin_activate() {
				
		//get NutsForPress setting
		global $nfproot_plugins_settings;
		
		//define plugin installaton type
		$nfproot_plugins_settings['nfplwd']['prefix'] = 'nfplwd';
		$nfproot_plugins_settings['nfplwd']['slug'] = 'nfplwd-settings';
		$nfproot_plugins_settings['nfplwd']['edition'] = 'repository';
		$nfproot_plugins_settings['nfplwd']['name'] = 'Login Watchdog';
		
		//update NutsForPress setting
		update_option('_nfproot_plugins_settings', $nfproot_plugins_settings, false);
		
		//create tables for login monitor functions
		global $wpdb;
		
		$wpdb->query("
		
			CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."nfplwd_failed_login_attempts (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime,
				ip varchar(50),
				username varchar(150),
				PRIMARY KEY  (id)
			) ".$wpdb->get_charset_collate()
		
		);

		$wpdb->query("
		
			CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."nfplwd_failed_login_history (
				id mediumint(9) NOT NULL AUTO_INCREMENT,
				time datetime,
				ip varchar(50),
				username varchar(150),
				PRIMARY KEY  (id)
			) ".$wpdb->get_charset_collate()
		
		);		
			
	}
		
}  else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_plugin_activate" already exists');
	
}