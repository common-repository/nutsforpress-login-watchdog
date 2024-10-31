<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_user_login_notification')){

	function nfplwd_user_login_notification($nfplwd_user_login, $nfplwd_user_object) {
		
		//get options 
		global $nfproot_current_language_settings;

		//if notifications and admin login notification are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_notifications'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_notifications'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_admin_login_notification'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_admin_login_notification'] === '1'
								
		) {
			
			//get user id
			$nfplwd_user_id = $nfplwd_user_object->ID;

			//go on only if user has an administrative role  
			if(user_can($nfplwd_user_id, 'manage_options')){
					
				if(
				
					!empty($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']) 
					&& is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
					
				){
					
					$nfplwd_user_login_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
					
					//send mail to inform about user login 
					$nfplwd_user_login_notification_subject = __('Successful login to','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_user_login_notification_body = '<html><body><p>'.__('The user with administrative role','nutsforpress-login-watchdog').' <strong>'.$nfplwd_user_login.'</strong> '.__('logged in successfully to','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p></body></html>';
					$nfplwd_user_login_notification_headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail(
					
						$nfplwd_user_login_notification_address, 
						$nfplwd_user_login_notification_subject, 
						$nfplwd_user_login_notification_body, 
						$nfplwd_user_login_notification_headers
						
					);		

				} 
			
			}
		
		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_user_login_notification" already exists');
	
}