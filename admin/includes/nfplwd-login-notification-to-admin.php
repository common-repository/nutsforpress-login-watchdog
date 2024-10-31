<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_login_notification_to_admin')){

	function nfplwd_login_notification_to_admin($nfplwd_user_login, $nfplwd_user_object) {
		
		//get options 
		global $nfproot_current_language_settings;

		//if notifications and admin login notification are enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_notifications'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_notifications'] === '1'
			&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_admin_on_login_notification'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_admin_on_login_notification'] === '1'
								
		) {
			
			//get user id
			$nfplwd_user_id = $nfplwd_user_object->ID;

			//go on only if user has an administrative role  
			if(user_can($nfplwd_user_id, 'manage_options')){

				//get user email
				$nfplwd_user_email = $nfplwd_user_object->user_email;
					
				if(
				
					!empty($nfplwd_user_email) 
					&& is_email($nfplwd_user_email)
					
				){
					
					$nfplwd_login_notification_to_admin_address = sanitize_email($nfplwd_user_email);
					
					//send mail to inform about user login 
					$nfplwd_login_notification_to_admin_subject = __('Successful login to','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_login_notification_to_admin_body = '<html><body><p>'.__('You\'ve just logged in to','nutsforpress-login-watchdog').' '.get_bloginfo('name').' ('.get_bloginfo('url').')</p><p>'.__('If you do not recognize this action as your own, go to your profile page','nutsforpress-login-watchdog').' ('.get_admin_url().'profile.php) '.__('and immediately change your password using the "Generate Password" button, then click on the "Log Out Everywhere Else" button','nutsforpress-login-watchdog').'</p></body></html>';
					$nfplwd_login_notification_to_admin_headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail(
					
						$nfplwd_login_notification_to_admin_address, 
						$nfplwd_login_notification_to_admin_subject, 
						$nfplwd_login_notification_to_admin_body, 
						$nfplwd_login_notification_to_admin_headers
						
					);		

				} 
			
			}
		
		}			

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_login_notification_to_admin" already exists');
	
}