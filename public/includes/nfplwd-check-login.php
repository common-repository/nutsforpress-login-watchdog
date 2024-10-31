<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//print the lock down message
if(!function_exists('nfplwd_temporay_lock_down_message')) {
	
	function nfplwd_temporay_lock_down_message($nfplwd_wp_login_error) {	
						
		global $nfproot_current_language_settings;			
		$nfplwd_wp_login_error = esc_attr($nfproot_current_language_settings['nfplwd']['nfproot_temporary_lock_down_message']);
		
		return $nfplwd_wp_login_error;
	}

} else {
	
	error_log('function: "nfplwd_temporay_lock_down_message" already exists');
	
}

//print the ban message
if(!function_exists('nfplwd_permanent_lock_down_message')) {
	
	function nfplwd_permanent_lock_down_message($nfplwd_wp_login_error) {	
						
		global $nfproot_current_language_settings;			
		$nfplwd_wp_login_error = esc_attr($nfproot_current_language_settings['nfplwd']['nfproot_permanent_lock_down_message']);
		
		return $nfplwd_wp_login_error;
	}

} else {
	
	error_log('function: "nfplwd_permanent_lock_down_message" already exists');
	
}

//append attempts left message to login error (this function is invoked only if nfproot_notify_user is set and only if at least one attempt is available)
if(!function_exists('nfplwd_attempts_left_message')) {
	
	function nfplwd_attempts_left_message($nfplwd_login_form_errors) {	
			
		if(!empty($nfplwd_login_form_errors)) {	

			//get needed data
			global $nfplwd_get_attempts_left;		
					
			if(isset($nfplwd_login_form_errors->errors['invalid_username'])){
				
				$nfplwd_login_form_errors->errors['invalid_username'][0] = $nfplwd_login_form_errors->errors['invalid_username'][0].'<br>'.__('Be careful','nutsforpress-login-watchdog').': '.__('you have only','nutsforpress-login-watchdog').' <b>'.$nfplwd_get_attempts_left.' '.__('attempts left','nutsforpress-login-watchdog').'</b>';
			
			}
			
			elseif(isset($nfplwd_login_form_errors->errors['invalid_email'])){
				
				$nfplwd_login_form_errors->errors['invalid_email'][0] = $nfplwd_login_form_errors->errors['invalid_email'][0].'<br>'.__('Be careful','nutsforpress-login-watchdog').': '.__('you have only','nutsforpress-login-watchdog').' <b>'.$nfplwd_get_attempts_left.' '.__('attempts left','nutsforpress-login-watchdog').'</b>';
				
			}
			
			elseif(isset($nfplwd_login_form_errors->errors['incorrect_password'])){
				
				$nfplwd_login_form_errors->errors['incorrect_password'][0] = $nfplwd_login_form_errors->errors['incorrect_password'][0].'<br>'.__('Be careful','nutsforpress-login-watchdog').': '.__('you have only','nutsforpress-login-watchdog').' <b>'.$nfplwd_get_attempts_left.' '.__('attempts left','nutsforpress-login-watchdog').'</b>';
				
			} 	
			
			return $nfplwd_login_form_errors;		
		
		}
			
	}

} else {
	
	error_log('function: "nfplwd_attempts_left_message" already exists');
	
}

//deal with login errors
if(!function_exists('nfplwd_do_things_on_fail')){
	
	function nfplwd_do_things_on_fail($nfplwd_check_login_username) {
					
		//check if authenticate filter is enamble		
		if(has_filter('authenticate','wp_authenticate_username_password')) {
							
			global $wpdb;

			//get needed data
			global $nfproot_current_language_settings;	
						
			//this global variable comes from the next function
			global $nfplwd_do_things_on_fail;
			
			if(NFPLWD_DEBUG === true) {error_log('NUTSFORPRESS: things to do on fail is: '.$nfplwd_do_things_on_fail);}
			
			//encode ip
			$nfplwd_check_login_username_ip = base64_encode(nfplwd_get_user_ip());				
			
			//work on anonymize IP only if is ban or lock down
			if($nfplwd_do_things_on_fail !== 'warn') {			
				
				//anonymize user IP
				$nfplwd_check_login_anonymized_ip = null;
				$nfplwd_get_user_ip = nfplwd_get_user_ip();
				
				if(!empty($nfplwd_get_user_ip) && strpos($nfplwd_get_user_ip, '.') !== false) {
					
					$nfplwd_get_user_ip_exploded = explode('.',$nfplwd_get_user_ip);
					
				}
				
				if(!empty($nfplwd_get_user_ip) && strpos($nfplwd_get_user_ip, ':') !== false) {
					
					$nfplwd_get_user_ip_exploded = explode(':',$nfplwd_get_user_ip);
					
				}
				
				if(!empty($nfplwd_get_user_ip_exploded)) {
				
					$nfplwd_get_user_ip_exploded_end = end($nfplwd_get_user_ip_exploded);
					$nfplwd_get_user_ip_exploded_end_anonymized = str_repeat('X', strlen($nfplwd_get_user_ip_exploded_end));
					
					$nfplwd_check_login_anonymized_ip = str_replace(
					
						$nfplwd_get_user_ip_exploded_end,
						$nfplwd_get_user_ip_exploded_end_anonymized,
						$nfplwd_get_user_ip
						
					);
				
				}
				
			}
			
			if($nfplwd_do_things_on_fail === 'permanent-lock') {
						
				//register ban
				$wpdb->insert( 
					$wpdb->prefix.'nfplwd_failed_login_history', 
					array( 
						'time' => current_time('mysql'), 
						'ip' => $nfplwd_check_login_username_ip,
						'username' => $nfplwd_check_login_username
					), 
					array( 
						'%s', 
						'%s',
						'%s'
					) 
				);

				//send ban mail, if notification message is set
				if(
					is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
					&&!empty($nfproot_current_language_settings['nfplwd']['nfproot_premanent_lock_notification']) 
					&& $nfproot_current_language_settings['nfplwd']['nfproot_premanent_lock_notification'] === '1'
					
				) {
					
					$nfplwd_permenent_lock_down_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
						
					//send mail to inform about successful login
					$nfplwd_permenent_lock_down_notification_subject = __('User permanently locked down from','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_permenent_lock_down_notification_body = '<html><body>';
					$nfplwd_permenent_lock_down_notification_body .= '<p>'.__('A user was permanently locked for having exceeded temporary locks conceded','nutsforpress-login-watchdog').'.</p><p>'.__('Last username used was','nutsforpress-login-watchdog').': <strong>'.$nfplwd_check_login_username.'</strong></p>';
					if(!empty($nfplwd_check_login_anonymized_ip)) {
						
						$nfplwd_permenent_lock_down_notification_body .= '<p>'.__('Anonymized user IP is','nutsforpress-login-watchdog').': <b>'.$nfplwd_check_login_anonymized_ip.'</b></p>';
						
					}
					$nfplwd_permenent_lock_down_notification_body .= '</body></html>';
					$nfplwd_permenent_lock_down_notification_headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail($nfplwd_permenent_lock_down_notification_address, $nfplwd_permenent_lock_down_notification_subject, $nfplwd_permenent_lock_down_notification_body, $nfplwd_permenent_lock_down_notification_headers);		
							
				}
				
				if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_permanent_lock_down_message'])) {
				
					//display ban message
					add_filter('login_errors', 'nfplwd_permanent_lock_down_message');
					
				}				
			
			}
			
			elseif($nfplwd_do_things_on_fail === 'temporary-lock') {

				if(
				
					is_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address'])
					&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_temporary_lock_notification'])
					&& $nfproot_current_language_settings['nfplwd']['nfproot_temporary_lock_notification'] === '1' 
					
				){
					
					$nfplwd_temporary_lock_notification_address = sanitize_email($nfproot_current_language_settings['nfplwd']['nfproot_notification_address']);
						
					//send email if lock_user_notification is true and PRO features are available
					$nfplwd_temporary_lock_notification_subject = __('User locked down from','nutsforpress-login-watchdog').' '.get_bloginfo('name');
					$nfplwd_temporary_lock_notification_body = '<html><body>';
					$nfplwd_temporary_lock_notification_body .= '<p>'.__('A user was temporarily locked for having exceeded login attempts conceded','nutsforpress-login-watchdog').'.</p><p>'.__('Last username used was','nutsforpress-login-watchdog').': <strong>'.$nfplwd_check_login_username.'</strong></p>';
					if(!empty($nfplwd_check_login_anonymized_ip)) {
						
						$nfplwd_temporary_lock_notification_body .= '<p>'.__('Anonymized user IP is','nutsforpress-login-watchdog').': <b>'.$nfplwd_check_login_anonymized_ip.'</b></p>';
						
					}
					$nfplwd_temporary_lock_notification_body .= '</body></html>';
					$nfplwd_temporary_lock_notification_headers = array('Content-Type: text/html; charset=UTF-8');
					wp_mail($nfplwd_temporary_lock_notification_address, $nfplwd_temporary_lock_notification_subject, $nfplwd_temporary_lock_notification_body, $nfplwd_temporary_lock_notification_headers);		

					//show lock down message only if is set
					if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_temporary_lock_down_message'])) {					
					
						add_filter('login_errors', 'nfplwd_temporay_lock_down_message');

					}

				}

				//register history entry
				$wpdb->insert( 
					$wpdb->prefix.'nfplwd_failed_login_history', 
					array( 
						'time' => current_time('mysql'), 
						'ip' => $nfplwd_check_login_username_ip,
						'username' => $nfplwd_check_login_username
					), 
					array( 
						'%s', 
						'%s',
						'%s'
					) 
				);				
				
			}
			
			elseif($nfplwd_do_things_on_fail === 'warn') {
						
				//show attempts left, indipendently of previous attempts, only if nfproot_notify_user is true and only if there are one or more attempts left
				if(
				
					!empty($nfproot_current_language_settings['nfplwd']['nfproot_notify_user']) 
					&& $nfproot_current_language_settings['nfplwd']['nfproot_notify_user'] === '1'
					
				) {	

						add_filter('wp_login_errors', 'nfplwd_attempts_left_message',10,1);				
					
				} 
			
			}
			
			//register failed login attempt into database
			$wpdb->insert( 
				$wpdb->prefix.'nfplwd_failed_login_attempts', 
				array( 
					'time' => current_time('mysql'), 
					'ip' => $nfplwd_check_login_username_ip,
					'username' => $nfplwd_check_login_username
				), 
				array( 
					'%s', 
					'%s',
					'%s'
				) 
			);					

		}

	}
	
} else {
	
	error_log('function: "nfplwd_do_things_on_fail" already exists');
	
}

//remove failed login attempts from database when user login successfully
if(!function_exists('nfplwd_remove_failed_login')){
	
	function nfplwd_remove_failed_login() {
		
		if(!empty(nfplwd_get_user_ip())) {
			
			global $wpdb;
			
			//encode ip
			$nfplwd_check_login_username_ip = base64_encode(nfplwd_get_user_ip());				
			
			//remove failed login attempts from database when user login successfully
			$wpdb->query(
			
				$wpdb->prepare("
				
					DELETE 
					FROM ".$wpdb->prefix."nfplwd_failed_login_attempts 
					WHERE ip = %s",
					
					$nfplwd_check_login_username_ip
				)
				
			);

			$wpdb->query(
			
				$wpdb->prepare("
				
					DELETE 
					FROM ".$wpdb->prefix."nfplwd_failed_login_history 
					WHERE ip = %s",
					
					$nfplwd_check_login_username_ip
				)
				
			);
			
				
		}
			
	}
		
	add_action('wp_login', 'nfplwd_remove_failed_login'); 	
	
} else {
	
	error_log('function: "nfplwd_remove_failed_login" already exists');
	
}


//deal with authentication procedure
if(!function_exists('nfplwd_check_login')){
	
	function nfplwd_check_login($nfplwd_check_login_username, $nfplwd_check_login_password) {

		//get needed data
		global $nfproot_current_language_settings;
		
		if(
		
			!empty($nfproot_current_language_settings['nfplwd']['nfproot_login_tracking']) 
			&& $nfproot_current_language_settings['nfplwd']['nfproot_login_tracking'] === '1'		
		
		) {
	
			//act only if username and password are entered and user ip is detected
			if(
		
			!empty($nfplwd_check_login_username) && 
			!empty($nfplwd_check_login_password) &&
			!empty(nfplwd_get_user_ip())
			
			){
				
				if(NFPLWD_DEBUG === true) {error_log('NUTSFORPRESS: check login is involved');}
				
				global $wpdb;
				
				//encode ip
				$nfplwd_check_login_username_ip = base64_encode(nfplwd_get_user_ip());	
				
				//define 
				global $nfplwd_get_attempts_left;
										
				if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_permanently_lock'])) {
					
					$nfplwd_permanetly_lock_after = $nfproot_current_language_settings['nfplwd']['nfproot_permanently_lock'];
					
				} else {
					
					$nfplwd_permanetly_lock_after = '0';
				}
				
				$nfplwd_allowed_login_attempts = $nfproot_current_language_settings['nfplwd']['nfproot_allowed_login_attempts'];
				$nfplwd_temporary_lock_duration = $nfproot_current_language_settings['nfplwd']['nfproot_temporary_lock_duration'];
				
				//initially set attempts left to attempts conceded
				$nfplwd_get_attempts_left = $nfplwd_allowed_login_attempts;				
				
				//get investigation period
				//$nfplwd_tracking_duration = $nfproot_current_language_settings['nfplwd']['nfproot_tracking_duration'];
				
				//define current time
				$nfplwd_current_time_mysql = current_time('mysql');
				$nfplwd_current_time_timestamp = current_time('timestamp');
				
				//delete all failed login attempts out of investigation period
				$wpdb->query(
				
					$wpdb->prepare("
					
						DELETE 
						FROM ".$wpdb->prefix."nfplwd_failed_login_attempts 
						WHERE TIMESTAMPDIFF(MINUTE, time, %s) >= %d",
						
						$nfplwd_current_time_mysql,
						$nfplwd_temporary_lock_duration
					)
					
				);		
				
				global $nfplwd_do_things_on_fail;
				global $nfplwd_do_things_on_signon_fail;
				$nfplwd_do_things_on_fail = 'warn';
				$nfplwd_do_things_on_signon_fail = null;
				
				//if ban is enabled
				if($nfplwd_permanetly_lock_after !== '0') {
					
					//get history entries
					$nfplwd_get_history_entries = $wpdb->get_results(

						$wpdb->prepare("
							SELECT id 
							FROM ".$wpdb->prefix."nfplwd_failed_login_history 
							WHERE ip = %s",
							$nfplwd_check_login_username_ip
						)

					);				
					
					$nfplwd_history_entries = 0;
					
					//count total hisotry entries
					$nfplwd_history_entries = $wpdb->num_rows;
					
					//display ban message and end authentication process if user is banned 
					if((int)$nfplwd_history_entries >= (int)$nfplwd_permanetly_lock_after) {
						
						if(NFPLWD_DEBUG === true) {error_log('NUTSFORPRESS: user is permanently locked');}
						
						$nfplwd_get_attempts_left = 0;
						
						if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_permanent_lock_down_message'])) {
						
							//display ban message
							add_filter('login_errors', 'nfplwd_permanent_lock_down_message');
							$nfplwd_do_things_on_signon_fail = 'permanent-lock';
						
						}
						
						//prevent from further authentication
						remove_filter('authenticate','wp_authenticate_username_password',20,3);
						return null;			
					
					}						
					
				}
				
				//get previous attempts (first the newest)
				$nfplwd_get_previous_failed_logins = $wpdb->get_results(

					$wpdb->prepare("
						SELECT time 
						FROM ".$wpdb->prefix."nfplwd_failed_login_attempts 
						WHERE ip = %s
						ORDER BY time DESC",
						$nfplwd_check_login_username_ip
					)

				);	
				
				//count total attempts
				$nfplwd_previous_failed_logins = $wpdb->num_rows;
				
				if(!empty($nfplwd_previous_failed_logins) && $nfplwd_previous_failed_logins > 0) {
				
					//redefine attempts left
					$nfplwd_get_attempts_left = (int)$nfplwd_allowed_login_attempts - $nfplwd_previous_failed_logins;
					
					//get last attempt time
					$nfplwd_get_last_failed_login_time = $nfplwd_get_previous_failed_logins[0]->time;
					
					//count minutes passed from the last fail
					$nfplwd_minutes_passed_from_last_fail = ($nfplwd_current_time_timestamp - strtotime($nfplwd_get_last_failed_login_time))/60;
				
					//lock down only if time passed from the last fail excedes locked down time
					if($nfplwd_minutes_passed_from_last_fail <= (int)$nfplwd_temporary_lock_duration) {
										
						//display lock down message and end authentication process if user is locked down 
						if((int)$nfplwd_previous_failed_logins > (int)$nfplwd_allowed_login_attempts) {
							
							if(NFPLWD_DEBUG === true) {error_log('NUTSFORPRESS: user is temporarily locked');}
												
							//keep showing temporary lock down message only if is set
							if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_temporary_lock_down_message'])) {
							
								add_filter('login_errors', 'nfplwd_temporay_lock_down_message');
								$nfplwd_do_things_on_signon_fail = 'temporary-lock';
								
							}
						
							//prevent from further authentication
							remove_filter('authenticate','wp_authenticate_username_password',20,3);
							return null;					

						}	
						
						//define what to do on wp_login_failed, if user has to be locked down
						elseif((int)$nfplwd_previous_failed_logins === (int)$nfplwd_allowed_login_attempts) {

							//define what to do on wp_login_failed, if user has to be banned
							if((int)$nfplwd_history_entries + 1 === (int)$nfplwd_permanetly_lock_after && $nfplwd_permanetly_lock_after !== '0') {
								
								if(NFPLWD_DEBUG === true) {error_log('NUTSFORPRESS: user has to be permanently locked');}
								
								if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_permanent_lock_down_message'])) {
								
									$nfplwd_do_things_on_fail = 'permanent-lock';
									$nfplwd_do_things_on_signon_fail = 'permanent-lock';
								
								}
										
							
							} else {				
							
								$nfplwd_do_things_on_fail = 'temporary-lock';
								$nfplwd_do_things_on_signon_fail = 'temporary-lock';
								
							}
							
						}
						
					}
					
				}
								
			}
		
			add_action('wp_login_failed', 'nfplwd_do_things_on_fail'); 
		
		}
	
	}
	
} else {
	
	error_log('function: "nfplwd_check_login" already exists');
	
}