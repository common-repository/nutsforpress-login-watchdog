<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_user_delete_notification')){

	function nfplwd_user_delete_notification($nfplwd_involved_user_id) {

		//get options 
		global $nfproot_current_language_settings;

		//if notifications and delete user notification are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_notifications'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_notifications'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_admin_delete_notification'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_admin_delete_notification'] === '1'
								
		) {
			
			//go on only if user has an administrative role  
			if(user_can($nfplwd_involved_user_id, 'manage_options')){
				
				if(
				
					!empty($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']) 
					&& is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
					
				) {
					
					$nfplwd_user_delete_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
					
					$nfplwd_involved_user_data = get_userdata($nfplwd_involved_user_id);
					$nfplwd_user_login = $nfplwd_involved_user_data->user_login;

					//send mail to inform about role change 
					$nfplwd_user_delete_notification_subject = __('User deleted in','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_user_delete_notification_body = '<html><body><p>'.__('An administrator, with user login','nutsforpress-login-watchdog').' <strong>'.$nfplwd_user_login.'</strong>, '.__('was just deleted from','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p></body></html>';
					$nfplwd_user_delete_notification_headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail(
					
						$nfplwd_user_delete_notification_address, 
						$nfplwd_user_delete_notification_subject, 
						$nfplwd_user_delete_notification_body, 
						$nfplwd_user_delete_notification_headers
						
					);		

				} 		

			}				
		
		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_user_delete_notification" already exists');
	
}