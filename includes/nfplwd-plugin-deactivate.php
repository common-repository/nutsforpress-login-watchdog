<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//DEACTIVATE

//plugin deactivate function
if(!function_exists('nfplwd_plugin_deactivate')){

	function nfplwd_plugin_deactivate() {
				
		require_once NFPLWD_BASE_PATH.'/root/nfproot-saved-settings.php';
		nfproot_saved_settings();
		
		global $nfproot_plugins_settings;
		global $nfproot_plugins_settings_option_name;	

		if(!empty($nfproot_plugins_settings['nfplwd'])) {		
					
			//unset plugin root settings
			unset($nfproot_plugins_settings['nfplwd']);
						
		}

		//if, after cleaning nfplwd settings, root settings is empty, delete it (no more NutsForPress plugin is activated)
		if(empty($nfproot_plugins_settings)) {

			//delete root settings
			delete_option($nfproot_plugins_settings_option_name);			
			
		} else {
			
			//update root settings
			update_option($nfproot_plugins_settings_option_name, $nfproot_plugins_settings, false);
			
		}	
	
	}
		
}  else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_plugin_deactivate" already exists');
	
}