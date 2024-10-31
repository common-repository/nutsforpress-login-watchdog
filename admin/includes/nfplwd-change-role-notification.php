<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_change_role_notification')){

	function nfplwd_change_role_notification($nfplwd_involved_user_id, $nfplwd_new_role, $nfplwd_old_roles) {

		//get options 
		global $nfproot_current_language_settings;

		//if notifications and change role notification are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_notifications'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_notifications'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_change_role_notification'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_change_role_notification'] === '1'
								
		) {
			
			//set this condition for only triggering role change and not user creation
			if(!empty($nfplwd_old_roles)) {
			
				if(
				
					!empty($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']) 
					&& is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
					
				) {
					
					$nfplwd_change_role_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
					
					$nfplwd_involved_user_data = get_userdata($nfplwd_involved_user_id);
					$nfplwd_user_login = $nfplwd_involved_user_data->user_login;
					
					$nfplwd_new_role_translated = translate_user_role($nfplwd_new_role);

					//send mail to inform about role change 
					$nfplwd_change_role_notification_subject = __('Role changed in','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_change_role_notification_body = '<html><body><p>'.__('A user, with user login','nutsforpress-login-watchdog').' <strong>'.$nfplwd_user_login.'</strong>, '.__('has now','nutsforpress-login-watchdog').' "<strong>'.$nfplwd_new_role_translated.'</strong>" '.__('role','nutsforpress-login-watchdog').' '.__('in','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p></body></html>';
					$nfplwd_change_role_notification_headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail(
					
						$nfplwd_change_role_notification_address, 
						$nfplwd_change_role_notification_subject, 
						$nfplwd_change_role_notification_body, 
						$nfplwd_change_role_notification_headers
						
					);		

				} 				
			
			}		

		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_change_role_notification" already exists');
	
}