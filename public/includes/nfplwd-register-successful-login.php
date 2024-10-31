<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_register_successful_login')){

	function nfplwd_register_successful_login($nfplwd_user_login, $nfplwd_user_object) {
		
		//get options 
		global $nfproot_current_language_settings;

		//if two factors authentication is enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_two_factors_authentication'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_two_factors_authentication'] === '1'
								
		){
			
			if(
			
				!isset($_REQUEST['action'])
				|| sanitize_text_field($_REQUEST['action']) !== 'nfplwd-two-factors-authentication'
				
			){
				
				//get current user id
				$nfplwd_user_id = $nfplwd_user_object->ID;
				
				//go on only if current user owns administrative capabilities and log in is asked from the default login page
				if(
				
					user_can($nfplwd_user_id, 'manage_options') === false
					|| is_login() === false
					|| wp_doing_ajax() === true
					
				){
					
					return;
					
				}

				//if two factors authentication should be skipped when IP has not changed since the last successful login
				if(

					!empty($nfproot_current_language_settings['nfplwd']['nfproot_skip_two_factors_authentication'])
					&& $nfproot_current_language_settings['nfplwd']['nfproot_skip_two_factors_authentication'] === '1'
										
				){
				
					//get current user IP
					$nfplwd_current_user_ip = nfplwd_get_user_ip();
					
					//get last user IP
					$nfplwd_last_user_ip = get_user_meta($nfplwd_user_id, '_nfplwd_last_user_ip', true);	

					//go on only if current user IP is different from the one related to the the last login
					if(
					
						!empty($nfplwd_last_user_ip)
						&& !empty($nfplwd_current_user_ip)
						&& $nfplwd_current_user_ip === $nfplwd_last_user_ip
						
					){
						
						//send login notification
						nfplwd_user_login_notification($nfplwd_user_login, $nfplwd_user_object);
						nfplwd_login_notification_to_admin($nfplwd_user_login, $nfplwd_user_object);
						
						return;
						
					}
					
				}
				
				//get other user info
				$nfplwd_user_email = $nfplwd_user_object->user_email;
				$nfplwd_user_display_name = $nfplwd_user_object->display_name;
				$nfplwd_user_first_name = get_user_meta($nfplwd_user_id, 'first_name', true);
				
				if(empty($nfplwd_user_first_name)){
					
					$nfplwd_user_name = $nfplwd_user_display_name;
					
				} else {
					
					$nfplwd_user_name = $nfplwd_user_first_name;
					
				}

				//generate a token
				$nfplwd_two_factos_token = substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(32))), 0, 32);

				//generate a six numbers code
				$nfplwd_two_factos_code = random_int(100000, 999999);
				
				//get current time
				$nfplwd_request_time = time();
				
				$nfplwd_user_meta_value = array(
				
					'request_time' => $nfplwd_request_time,
					'user_id' => $nfplwd_user_id,
					'token' => $nfplwd_two_factos_token,
					'code' => $nfplwd_two_factos_code
				
				);
				
				//update two factors meta
				delete_user_meta($nfplwd_user_id, '_nfplwd_two_factors_info');
				$nfplwd_user_meta_id = update_user_meta($nfplwd_user_id, '_nfplwd_two_factors_info', $nfplwd_user_meta_value);
				
				$nfplwd_confirmation_link = wp_login_url().'?action=nfplwd-two-factors-authentication&mid='.$nfplwd_user_meta_id.'&token='.$nfplwd_two_factos_token;
								
				//send mail to inform about user login 
				$nfplwd_two_factors_email_subject = __('Your login code is','nutsforpress-login-watchdog').' '.$nfplwd_two_factos_code;
				$nfplwd_two_factors_email_body = '<html>';
				$nfplwd_two_factors_email_body .= '<body>';
				$nfplwd_two_factors_email_body .= '<p>';
				$nfplwd_two_factors_email_body .= __('Howdy','nutsforpress-login-watchdog').' '.$nfplwd_user_name.',<br>';
				$nfplwd_two_factors_email_body .= __('in order to confirm your authentication, please enter the below code within the next ten minutes','nutsforpress-login-watchdog').'.';
				$nfplwd_two_factors_email_body .= '</p>';
				$nfplwd_two_factors_email_body .= '<p>';
				$nfplwd_two_factors_email_body .= __('Login code','nutsforpress-login-watchdog').': <strong>'.$nfplwd_two_factos_code.'</strong>';
				$nfplwd_two_factors_email_body .= '</p>';
				$nfplwd_two_factors_email_body .= '<p>';
				$nfplwd_two_factors_email_body .= __('If you are not trying to login to','nutsforpress-login-watchdog').' '.home_url().', '.__('it is strongly recommended that you change your password, since someone knows it','nutsforpress-login-watchdog').'.<br>';
				$nfplwd_two_factors_email_body .= __('Thank you','nutsforpress-login-watchdog').'!';
				$nfplwd_two_factors_email_body .= '</p>';

				$nfplwd_two_factors_email_body .= '</body>';
				$nfplwd_two_factors_email_body .= '</html>';
				
				$nfplwd_two_factors_email_headers = array('Content-Type: text/html; charset=UTF-8');
				
				//send email
				wp_mail(
				
					$nfplwd_user_email, 
					$nfplwd_two_factors_email_subject, 
					$nfplwd_two_factors_email_body, 
					$nfplwd_two_factors_email_headers
					
				);		

				//force current user logout
				wp_logout();
				
				//redirect to confirmation link
				wp_safe_redirect($nfplwd_confirmation_link);
				exit;
				
			}
		
		} else {
			
			//send login notification
			nfplwd_user_login_notification($nfplwd_user_login, $nfplwd_user_object);
			nfplwd_login_notification_to_admin($nfplwd_user_login, $nfplwd_user_object);
			
		}		

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_register_successful_login" already exists');
	
}