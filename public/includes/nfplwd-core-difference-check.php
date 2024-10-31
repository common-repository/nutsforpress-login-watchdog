<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_core_difference_check')){

	function nfplwd_core_difference_check() {
		
		//delete option if administrator asks to manually run the core check
		if(
		
			is_user_logged_in()
			&& current_user_can('manage_options')
			&& isset($_REQUEST['nfplwd-core-difference-check'])
			&& (bool)$_REQUEST['nfplwd-core-difference-check'] === true
		
		){
			
			delete_option('_nfplwd_last_core_check');
			update_option('_nfplwd_manually_core_check', '1');
			
		}
				
		//get options 
		global $nfproot_current_language_settings;

		//if notifications and core difference notification are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_notifications'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_notifications'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_core_difference_notification'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_core_difference_notification'] === '1'
								
		){
					
			if(
			
				!empty($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']) 
				&& is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
				
			){
																			
				//core difference check
				wp_enqueue_script('nfplwd-core-difference-check', plugins_url().'/'.plugin_basename( __DIR__ ).'/js/nfplwd-core-difference-check.js', array('jquery'), '', true);		
				wp_localize_script('nfplwd-core-difference-check', 'nfplwd_core_difference_check_object', array( 
				
					'nfplwd_core_difference_check_url' => admin_url('admin-ajax.php'),
					'nfplwd_core_difference_check_nonce' => wp_create_nonce('nfplwd-core-difference-check-nonce')
					
				));	
				
			} 
		
		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_core_difference_check" already exists');
	
}