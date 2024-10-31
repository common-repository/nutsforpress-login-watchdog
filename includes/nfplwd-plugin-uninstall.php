<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//UNINSTALL

//plugin uninstall function
if(!function_exists('nfplwd_plugin_uninstall')){

	function nfplwd_plugin_uninstall() {
		
		require_once NFPLWD_BASE_PATH.'/root/nfproot-saved-settings.php';
		nfproot_saved_settings();
				
		global $nfproot_root_settings;
		global $nfproot_root_settings_name;
		
		if(!empty($nfproot_root_settings['nfplwd'])) {
			
			//unset plugin installaton
			unset($nfproot_root_settings['nfplwd']);
			
		}

		//if, after cleaning nfplwd settings, base settings is empty, delete it (no more NutsForPress plugins are installed)
		if(empty($nfproot_root_settings)) {

			//delete base settings
			delete_option($nfproot_root_settings_name);			
			
		} else {
			
			//update base settings
			update_option($nfproot_root_settings_name, $nfproot_root_settings, false);
			
		}

		//get alla WPML active languages
		$nfplwd_get_wpml_active_languages = apply_filters('wpml_active_languages', false);

		//if WPML has active languages
		if(!empty($nfplwd_get_wpml_active_languages)) {
		  
			//loop into languages
			foreach($nfplwd_get_wpml_active_languages as $nfplwd_wpml_language) {

				$nfplwd_wpml_language_code = $nfplwd_wpml_language['language_code'];

				$nfproot_current_language_settings_name = '_nfproot_settings_'.$nfplwd_wpml_language_code;
				$nfproot_current_language_settings = get_option($nfproot_current_language_settings_name, false);
				
				if(!empty($nfproot_current_language_settings['nfplwd'])) {
					
					//unset plugin installaton
					unset($nfproot_current_language_settings['nfplwd']);
					
				}	
				
				//if, after cleaning nfplwd settings, language settings is empty, delete it (no more NutsForPress plugins are installed)
				if(empty($nfproot_current_language_settings)) {

					//delete language settings
					delete_option($nfproot_current_language_settings_name);			
					
				} else {
					
					//update language settings
					update_option($nfproot_current_language_settings_name, $nfproot_current_language_settings, false);
					
				}
								
			}
			
		}	
		
		//delete tables
		global $wpdb;
		
		$wpdb->query("
		
			DROP TABLE IF EXISTS ".$wpdb->prefix."nfplwd_failed_login_attempts"
		
		);		
		
		$wpdb->query("
		
			DROP TABLE IF EXISTS ".$wpdb->prefix."nfplwd_failed_login_history"
		
		);			
	
		//delete settings from the old plugin structure
		delete_option('_nfp_root_settings');
		delete_option('_nfp_settings');
		
		//delete options from this plugin
		delete_option('_nfplwd_last_core_check');
		
		//delete usermeta set by this plugin
		delete_metadata(
		
			'user',
			0,
			'_nfplwd_last_user_ip',
			'',
			true           
			
		);

	}
		
}  else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_plugin_uninstall" already exists');
	
}