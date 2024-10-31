<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_plugin_activate_notification')){

	function nfplwd_plugin_activate_notification($nfplwd_plugin_activated) {
		
		//get options 
		global $nfproot_current_language_settings;

		//if notifications and user login notification are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_notifications'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_notifications'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_plugin_activate_notification'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_plugin_activate_notification'] === '1'
								
		) {
			
			if(
			
				!empty($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']) 
				&& is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
				
			) {
				
				$nfplwd_plugin_activate_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
				
				//send mail to inform about user login 
				$nfplwd_plugin_activate_notification_subject = __('Plugin activation on','nutsforpress-login-watchdog').' '.get_bloginfo('name');
				$nfplwd_plugin_activate_notification_body = '<html><body><p>'.__('The plugin','nutsforpress-login-watchdog').' <strong>'.$nfplwd_plugin_activated.'</strong>, '.__('has just been activated on','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p></body></html>';
				$nfplwd_plugin_activate_notification_headers = array('Content-Type: text/html; charset=UTF-8');
				wp_mail(
				
					$nfplwd_plugin_activate_notification_address, 
					$nfplwd_plugin_activate_notification_subject, 
					$nfplwd_plugin_activate_notification_body, 
					$nfplwd_plugin_activate_notification_headers
					
				);		

			} 				
		
		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_plugin_activate_notification" already exists');
	
}