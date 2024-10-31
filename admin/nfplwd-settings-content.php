<?php
//if this file is called directly, die.
if(!defined('ABSPATH')) die('please, do not call this page directly');
	
//with this function we will define the NutsForPress menu page content
if(!function_exists('nfplwd_settings_content')) {
	
	function nfplwd_settings_content() {
		
		//create steps for login attempts
		$nfplwd_allowed_login_attempts_values = array();
				
		$nfplwd_allowed_login_attempts_value = 2;
		$nfplwd_allowed_login_attempts_step = 1;
		
		while($nfplwd_allowed_login_attempts_value < 19) {

			$nfplwd_allowed_login_attempts_selected = '';
			
			if($nfplwd_allowed_login_attempts_value === 4) {
					
				$nfplwd_allowed_login_attempts_selected = 'selected';
					
			}

			$nfplwd_allowed_login_attempts_values[$nfplwd_allowed_login_attempts_step]['option-value'] = $nfplwd_allowed_login_attempts_value;
			$nfplwd_allowed_login_attempts_values[$nfplwd_allowed_login_attempts_step]['option-text'] = ($nfplwd_allowed_login_attempts_value + 1).' '.__('attempts','nutsforpress-login-watchdog');
			$nfplwd_allowed_login_attempts_values[$nfplwd_allowed_login_attempts_step]['option-selected'] = $nfplwd_allowed_login_attempts_selected;
					
			$nfplwd_allowed_login_attempts_step++;
			$nfplwd_allowed_login_attempts_value++;
			
		}
		
		while($nfplwd_allowed_login_attempts_value < 50) {

			$nfplwd_allowed_login_attempts_values[$nfplwd_allowed_login_attempts_step]['option-value'] = $nfplwd_allowed_login_attempts_value;
			$nfplwd_allowed_login_attempts_values[$nfplwd_allowed_login_attempts_step]['option-text'] = ($nfplwd_allowed_login_attempts_value + 1).' '.__('attempts','nutsforpress-login-watchdog');
			$nfplwd_allowed_login_attempts_values[$nfplwd_allowed_login_attempts_step]['option-selected'] = $nfplwd_allowed_login_attempts_selected;
					
			$nfplwd_allowed_login_attempts_step++;
			$nfplwd_allowed_login_attempts_value = $nfplwd_allowed_login_attempts_value + 5;
			
		}
		
		//create steps for tracking_locking and suspension duration
		$nfplwd_tracking_locking_duration_values = array();
				
		$nfplwd_tracking_locking_duration_value = 5;
		$nfplwd_tracking_locking_duration_step = 1;
		
		//minutes
		while($nfplwd_tracking_locking_duration_value <= 60) {

			$nfplwd_tracking_locking_duration_selected = '';
			
			if($nfplwd_tracking_locking_duration_value === 30) {
					
				$nfplwd_tracking_locking_duration_selected = 'selected';
					
			}

			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-value'] = $nfplwd_tracking_locking_duration_value;
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-text'] = $nfplwd_tracking_locking_duration_value.' '.__('minutes','nutsforpress-login-watchdog');
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-selected'] = $nfplwd_tracking_locking_duration_selected;
					
			$nfplwd_tracking_locking_duration_step++;
			$nfplwd_tracking_locking_duration_value = $nfplwd_tracking_locking_duration_value + 5;
			
		}
		
		$nfplwd_tracking_locking_duration_value = 120;

		while($nfplwd_tracking_locking_duration_value <= 1440) {

			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-value'] = $nfplwd_tracking_locking_duration_value;
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-text'] = ($nfplwd_tracking_locking_duration_value / 60).' '.__('hours','nutsforpress-login-watchdog');
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-selected'] = $nfplwd_tracking_locking_duration_selected;
					
			$nfplwd_tracking_locking_duration_step++;
			$nfplwd_tracking_locking_duration_value = $nfplwd_tracking_locking_duration_value + 120;
			
		}

		$nfplwd_tracking_locking_duration_value = 2880;
		
		while($nfplwd_tracking_locking_duration_value <= 14400) {

			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-value'] = $nfplwd_tracking_locking_duration_value;
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-text'] = ($nfplwd_tracking_locking_duration_value / 1440).' '.__('days','nutsforpress-login-watchdog');
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-selected'] = $nfplwd_tracking_locking_duration_selected;
					
			$nfplwd_tracking_locking_duration_step++;
			$nfplwd_tracking_locking_duration_value = $nfplwd_tracking_locking_duration_value + 1440;
			
		}
		
		$nfplwd_tracking_locking_duration_value = 21600;

		while($nfplwd_tracking_locking_duration_value <= 86400) {

			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-value'] = $nfplwd_tracking_locking_duration_value;
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-text'] = ($nfplwd_tracking_locking_duration_value / 1440).' '.__('days','nutsforpress-login-watchdog');
			$nfplwd_tracking_locking_duration_values[$nfplwd_tracking_locking_duration_step]['option-selected'] = $nfplwd_tracking_locking_duration_selected;
					
			$nfplwd_tracking_locking_duration_step++;
			$nfplwd_tracking_locking_duration_value = $nfplwd_tracking_locking_duration_value + 7200;
			
		}

		//create steps for permanent lock
		$nfplwd_permanently_lock_values = array();

		$nfplwd_permanently_lock_values[1]['option-value'] = 0;
		$nfplwd_permanently_lock_values[1]['option-text'] = __('unlimited (never locked)','nutsforpress-login-watchdog');
		$nfplwd_permanently_lock_values[1]['option-selected'] = '';
				
		$nfplwd_permanently_lock_value = 3;
		$nfplwd_permanently_lock_step = 2;
		
		while($nfplwd_permanently_lock_value < 10) {

			$nfplwd_permanently_lock_selected = '';
			
			if($nfplwd_permanently_lock_value === 3) {
					
				$nfplwd_permanently_lock_selected = 'selected';
					
			}

			$nfplwd_permanently_lock_values[$nfplwd_permanently_lock_step]['option-value'] = $nfplwd_permanently_lock_value;
			$nfplwd_permanently_lock_values[$nfplwd_permanently_lock_step]['option-text'] = __('after','nutsforpress-login-watchdog').' '.($nfplwd_permanently_lock_value).' '.__('lock downs','nutsforpress-login-watchdog');
			$nfplwd_permanently_lock_values[$nfplwd_permanently_lock_step]['option-selected'] = $nfplwd_permanently_lock_selected;
					
			$nfplwd_permanently_lock_step++;
			$nfplwd_permanently_lock_value++;
			
		}
		
		while($nfplwd_permanently_lock_value < 50) {

			$nfplwd_permanently_lock_values[$nfplwd_permanently_lock_step]['option-value'] = $nfplwd_permanently_lock_value;
			$nfplwd_permanently_lock_values[$nfplwd_permanently_lock_step]['option-text'] = __('after','nutsforpress-login-watchdog').' '.($nfplwd_permanently_lock_value).' '.__('lock downs','nutsforpress-login-watchdog');
			$nfplwd_permanently_lock_values[$nfplwd_permanently_lock_step]['option-selected'] = $nfplwd_permanently_lock_selected;
					
			$nfplwd_permanently_lock_step++;
			$nfplwd_permanently_lock_value = $nfplwd_permanently_lock_value + 5;
			
		}
		
		//get currently locked user number
		global $nfproot_current_language_settings;
		if(!empty($nfproot_current_language_settings['nfplwd']['nfproot_permanently_lock'])) {
			
			$nfplwd_permanently_lock = $nfproot_current_language_settings['nfplwd']['nfproot_permanently_lock'];

			global $wpdb;

			//get history entries
			$nfplwd_get_history_entries = $wpdb->get_results(
			
				
				$wpdb->prepare("
					SELECT id 
					FROM ".$wpdb->prefix."nfplwd_failed_login_history 
					GROUP BY ip HAVING COUNT(ip) >= %s",
					$nfplwd_permanently_lock
				)
				
			);
			
			//count total hisotry entries
			$nfplwd_history_entries = $wpdb->num_rows;
			
			if(!empty($nfplwd_history_entries) && $nfplwd_history_entries > 0) {
				
				$nfplwd_permanently_lock_after_input = $nfplwd_history_entries.' '.__('users permanently locked','nutsforpress-login-watchdog');
				
			} else {
				
				$nfplwd_permanently_lock_after_input = false;
			}
			
		} else {
			
			$nfplwd_permanently_lock_after_input = false;
		}
		
		//get las core check
		$nfplwd_last_core_check = get_option('_nfplwd_last_core_check');
		
		if(!empty($nfplwd_last_core_check)){
			
			$nfplwd_last_core_check_time = date_i18n(get_option('date_format').' '.get_option('time_format'), $nfplwd_last_core_check['time']);
			$nfplwd_last_core_check_result = $nfplwd_last_core_check['result'];
			
		} else {
			
			$nfplwd_last_core_check_time = __('browse one of your frontend page and get back to see the results','nutsforpress-login-watchdog');
			$nfplwd_last_core_check_result = __('no checks so far','nutsforpress-login-watchdog');
			
		}
	
		$nfplwd_settings_content = array(

			array(
			
				'container-title'	=> __('Two factors authentication for Administrators','nutsforpress-login-watchdog'),
				
				'container-id'		=> 'nfplwd_two_factors_authentication_container',
				'container-class' 	=> 'nfplwd-two-factors-authentication-container',
				'input-name'		=> 'nfproot_two_factors_authentication',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfplwd',
				'input-id'			=> 'nfplwd_two_factors_authentication',
				'input-class'		=> 'nfplwd-two-factors-authentication',
				'input-description'	=> __('If switched on, Administrators are required to confirm their authentication by following the instructions contained into an email message sent each time they login successfully','nutsforpress-login-watchdog'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
				
				'childs'			=> array(
				
					array(
					
						'container-title'	=> __('Skip two factors authentication until IP does not change','nutsforpress-login-watchdog'),
						
						'container-id'		=> 'nfplwd_skip_two_factors_authentication_container',
						'container-class' 	=> 'nfplwd-skip-two-factors-authentication-container',
						'input-name'		=> 'nfproot_skip_two_factors_authentication',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id'			=> 'nfplwd_skip_two_factors_authentication',
						'input-class'		=> 'nfplwd-skip-two-factors-authentication',
						'input-description'	=> __('If switched on, two factors authentication is skipped when IP has not changed since the last successful login','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
				
				),
				
			),
		
			array(
			
				'container-title'	=> __('Login attempts tracking','nutsforpress-login-watchdog'),
				
				'container-id'		=> 'nfplwd_login_tracking_container',
				'container-class' 	=> 'nfplwd-login-tracking-container',
				'input-name'		=> 'nfproot_login_tracking',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfplwd',
				'input-id'			=> 'nfplwd_login_tracking',
				'input-class'		=> 'nfplwd-login-tracking',
				'input-description'	=> __('If switched on, login attempts will be tracked and user exceeding the allowed login attempts will be suspended or excluded from login','nutsforpress-login-watchdog'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
							
				'childs'			=> array(
				
					array(
					
						'container-title'	=> __('Allowed failed login attempts','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_allowed_login_attempts_container',
						'container-class' 	=> 'nfplwd-allowed-login-attempts-container',					
						'input-name' 		=> 'nfproot_allowed_login_attempts',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_allowed_login_attempts',
						'input-class'		=> 'nfplwd-allowed-login-attempts',
						'input-description' => __('Define how many times a user can be fail on login before being temporarily prevented from trying again','nutsforpress-login-watchdog'),
						'arrow-before'		=> true,
						'after-input'		=> '',
						'input-type' 		=> 'dropdown',
						'input-value'		=> $nfplwd_allowed_login_attempts_values,
						
					),	
					
					/*array(
					
						'container-title'	=> __('Tracking duration','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_tracking_duration_container',
						'container-class' 	=> 'nfplwd-tracking-duration-container',					
						'input-name' 		=> 'nfproot_tracking_duration',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_tracking_duration',
						'input-class'		=> 'nfplwd-tracking-duration',
						'input-description' => __('Define how long have to be tracked the failed login attempts','nutsforpress-login-watchdog'),
						'arrow-before'		=> true,
						'after-input'		=> '',
						'input-type' 		=> 'dropdown',
						'input-value'		=> $nfplwd_tracking_locking_duration_values,
						
					),*/
					
					array(
					
						'container-title'	=> __('Inform user about attempts left','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_notify_user_container',
						'container-class' 	=> 'nfplwd-notify-user-container',					
						'input-name' 		=> 'nfproot_notify_user',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_notify_user',
						'input-class'		=> 'nfplwd-notify-user',
						'input-description' => __('After a failed login attempt, display a message for notifying the user about login attempts left','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
					array(
					
						'container-title'	=> __('Temporary lock duration','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_temporary_lock_duration_container',
						'container-class' 	=> 'nfplwd-temporary-lock-duration-container',					
						'input-name' 		=> 'nfproot_temporary_lock_duration',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_temporary_lock_duration',
						'input-class'		=> 'nfplwd-temporary-lock-duration',
						'input-description' => __('Define how long a user that exceeded conceded login attempts has to wait before retrying','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'dropdown',
						'input-value'		=> $nfplwd_tracking_locking_duration_values,
						
					),
					
					array(
					
						'container-title'	=> __('Temporary lock down message','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_temporary_lock_down_message_container',
						'container-class' 	=> 'nfplwd-temporary-lock-down-message-container',					
						'input-name' 		=> 'nfproot_temporary_lock_down_message',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_temporary_lock_down_message',
						'input-class'		=> 'nfplwd-temporary-lock-down-message nfproot-long-text',
						'input-description' => __('Message to be displayed when someone exceeds the maximum number of login attempts conceded','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('You have exceeded the maximum number of login attempts conceded, please try again later','nutsforpress-login-watchdog'),
						
					),
					
					array(
					
						'container-title'	=> __('Permanently lock','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_permanently_lock_container',
						'container-class' 	=> 'nfplwd-permanently-lock-container',					
						'input-name' 		=> 'nfproot_permanently_lock',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_permanently_lock',
						'input-class'		=> 'nfplwd-permanently-lock',
						'input-description' => __('Define how many times a user can be temporarily locked down before being permanently prevented from login','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> array(

							array(
							
								'type' 		=> 'paragraph',
								'id' 		=> 'nfplwd_permanently_lock_history',
								'class' 	=> 'nfproot-after-input nfplwd-permanently-lock-history',
								'hidden' 	=> false,
								'content' 	=> $nfplwd_permanently_lock_after_input,
								'value'		=> ''
							
							),			

						),							
						
						'input-type' 		=> 'dropdown',
						'input-value'		=> $nfplwd_permanently_lock_values,
						
					),
					
					array(
					
						'container-title'	=> __('Permanent lock down message','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_permanent_lock_down_message_container',
						'container-class' 	=> 'nfplwd-permanent-lock-down-message-container',					
						'input-name' 		=> 'nfproot_permanent_lock_down_message',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_permanent_lock_down_message',
						'input-class'		=> 'nfplwd-permanent-lock-down-message nfproot-long-text',
						'input-description' => __('Message to be displayed when someone gets permanently locked down','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('Sorry, you can not try to login anymore since you are permanently locked down','nutsforpress-login-watchdog'),
						
					),
					
				),
				
			),
			
			array(
			
				'container-title'	=> __('Deactivate XML-RPC','nutsforpress-login-watchdog'),
				
				'container-id'		=> 'nfplwd_disable_xmlrpc_container',
				'container-class' 	=> 'nfplwd-disable-xmlrpc-container',
				'input-name'		=> 'nfproot_disable_xmlrpc',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfplwd',
				'input-id'			=> 'nfplwd_disable_xmlrpc',
				'input-class'		=> 'nfplwd-disable-xmlrpc',
				'input-description'	=> __('If switched on, the WordPress support for XML-RPC protocol, often used for brute force attacks, will be deactivated','nutsforpress-login-watchdog'),
				'arrow-before'		=> false,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
				
			),
			
			array(
			
				'container-title'	=> __('Prevent author usernames from being discovered','nutsforpress-login-watchdog'),
				
				'container-id'		=> 'nfplwd_hide_usernames_container',
				'container-class' 	=> 'nfplwd-hide-usernames-container',
				'input-name'		=> 'nfproot_hide_usernames',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfplwd',
				'input-id'			=> 'nfplwd_hide_usernames',
				'input-class'		=> 'nfplwd-hide-usernames',
				'input-description'	=> __('If switched on, some WordPress native functions that help hackers to have access to authors usernames, including the ones with administrative privileges, will be deactivated','nutsforpress-login-watchdog'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
			
				'childs'			=> array(
				
					array(
					
						'container-title'	=> __('Prevent automatic redirection to author page','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_hide_authors_archive_container',
						'container-class' 	=> 'nfplwd-hide-authors-archive-container',					
						'input-name' 		=> 'nfproot_hide_authors_archive',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_hide_authors_archive',
						'input-class'		=> 'nfplwd-hide-authors-archive',
						'input-description' => sprintf(
						
								__('If switched on, an author archive page called by an ID parameter, for example %s, will be redirected to home URL through a WordPress safe redirection','nutsforpress-login-watchdog'),
								'<a href="'.site_url().'/?author=1" target="_blank" title="'.site_url().'/?author=1" alt="'.site_url().'/?author=1">'.__('yours','nutsforpress-login-watchdog').'</a>'
								
							),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),	
					
					array(
					
						'container-title'	=> __('Hide username access through API REST','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_hide_usernames_api_container',
						'container-class' 	=> 'nfplwd-hide-usernames-api-container',					
						'input-name' 		=> 'nfproot_hide_usernames_api',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_hide_usernames_api',
						'input-class'		=> 'nfplwd-hide-usernames-api',
						'input-description' => sprintf(
						
								__('If switched on, you will prevent usernames to be discovered through %s','nutsforpress-login-watchdog'),
								'<a href="'.site_url().'/wp-json/wp/v2/users" target="_blank" title="REST API" alt="REST API">REST API</a>'
								
							),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),	
					
				),
				
			),
			
			array(
			
				'container-title'	=> __('Custom login errors','nutsforpress-login-watchdog'),
				
				'container-id'		=> 'nfplwd_custom_login_errors_container',
				'container-class' 	=> 'nfplwd-custom-login-errors-container',
				'input-name'		=> 'nfproot_custom_login_errors',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfplwd',
				'input-id'			=> 'nfplwd_custom_login_errors',
				'input-class'		=> 'nfplwd-custom-login-errors',
				'input-description'	=> __('If switched on, the defined custom login errors will be displayed instead of the ones from WordPress','nutsforpress-login-watchdog'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
							
				'childs'			=> array(
				
					array(
					
						'container-title'	=> __('Message to display when username is not valid','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_wrong_username_error_container',
						'container-class' 	=> 'nfplwd-wrong-username-error-container',					
						'input-name' 		=> 'nfproot_wrong_username_error',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_wrong_username_error',
						'input-class'		=> 'nfplwd-wrong-username-error nfproot-long-text',
						'input-description' => __('Enter the message that you want to be displayed on wrong username','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('User not found, please retry','nutsforpress-login-watchdog'),
						
					),	
					
					array(
					
						'container-title'	=> __('Message to display when email address is not valid','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_wrong_email_error_container',
						'container-class' 	=> 'nfplwd-wrong-email-error-container',					
						'input-name' 		=> 'nfproot_wrong_email_error',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_wrong_email_error',
						'input-class'		=> 'nfplwd-wrong-email-error nfproot-long-text',
						'input-description' => __('Enter the message that you want to be displayed on wrong email address','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('User not found, please retry','nutsforpress-login-watchdog'),
						
					),	
					
					array(
					
						'container-title'	=> __('Message to display when password is not valid','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_wrong_password_error_container',
						'container-class' 	=> 'nfplwd-wrong-password-error-container',					
						'input-name' 		=> 'nfproot_wrong_password_error',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_wrong_password_error',
						'input-class'		=> 'nfplwd-wrong-password-error nfproot-long-text',
						'input-description' => __('Enter the message that you want to be displayed on wrong password','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('User not found, please retry','nutsforpress-login-watchdog'),
						
					),	
					
					array(
					
						'container-title'	=> __('Message to display when username is empty','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_empty_username_error_container',
						'container-class' 	=> 'nfplwd-empty-username-error-container',					
						'input-name' 		=> 'nfproot_empty_username_error',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_empty_username_error',
						'input-class'		=> 'nfplwd-empty-username-error nfproot-long-text',
						'input-description' => __('Enter the message that you want to be displayed on empty username','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('Please, fill-in all the required data','nutsforpress-login-watchdog'),
						
					),	
					
					array(
					
						'container-title'	=> __('Message to display when password is empty','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_empty_password_error_container',
						'container-class' 	=> 'nfplwd-empty-password-error-container',					
						'input-name' 		=> 'nfproot_empty_password_error',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_empty_password_error',
						'input-class'		=> 'nfplwd-empty-password-error nfproot-long-text',
						'input-description' => __('Enter the message that you want to be displayed on empty password','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'text',
						'input-value'		=> __('Please, fill-in all the required data','nutsforpress-login-watchdog'),
						
					),	
					
				),
				
			),
				
			array(
			
				'container-title'	=> __('Notifications','nutsforpress-login-watchdog'),
				
				'container-id'		=> 'nfplwd_notifications_container',
				'container-class' 	=> 'nfplwd-notifications-container',
				'input-name'		=> 'nfproot_notifications',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfplwd',
				'input-id'			=> 'nfplwd_notifications',
				'input-class'		=> 'nfplwd-notifications',
				'input-description'	=> __('If switched on, the notifications defined here will be sent to the entered address','nutsforpress-login-watchdog'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
							
				'childs'			=> array(
				
					array(
					
						'container-title'	=> __('On administrator successful login','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_admin_login_notification_container',
						'container-class' 	=> 'nfplwd-admin-login-notification-container',					
						'input-name' 		=> 'nfproot_admin_login_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_admin_login_notification',
						'input-class'		=> 'nfplwd-admin-login-notification',
						'input-description' => __('If switched on, a notification will be sent when a user with administrative role logs in successfully','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),	

					array(
					
						'container-title'	=> __('On role change','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_change_role_notification_container',
						'container-class' 	=> 'nfplwd-change-role-notification-container',					
						'input-name' 		=> 'nfproot_change_role_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_change_role_notification',
						'input-class'		=> 'nfplwd-change-role-notification',
						'input-description' => __('If switched on, a notification will be sent when a user role is changed','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),	

					array(
					
						'container-title'	=> __('On administrator delete','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_admin_delete_notification_container',
						'container-class' 	=> 'nfplwd-admin-delete-notification-container',					
						'input-name' 		=> 'nfproot_admin_delete_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_admin_delete_notification',
						'input-class'		=> 'nfplwd-admin-delete-notification',
						'input-description' => __('If switched on, a notification will be sent when an administrator is deleted','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),		

					array(
					
						'container-title'	=> __('On temporary lock','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_temporary_lock_notification_container',
						'container-class' 	=> 'nfplwd-temporary-lock-notification-container',					
						'input-name' 		=> 'nfproot_temporary_lock_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_temporary_lock_notification',
						'input-class'		=> 'nfplwd-temporary-lock-notification',
						'input-description' => __('If switched on and if login attempts tracking is enabled, a notification will be sent when a user is temporary locked down','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),		

					array(
					
						'container-title'	=> __('On permanent lock','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_premanent_lock_notification_container',
						'container-class' 	=> 'nfplwd-premanent-lock-notification-container',					
						'input-name' 		=> 'nfproot_premanent_lock_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_premanent_lock_notification',
						'input-class'		=> 'nfplwd-premanent-lock-notification',
						'input-description' => __('If switched on and if login attempts tracking is enabled, a notification will be sent when a user is permanently locked down','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),	
					
					array(
					
						'container-title'	=> __('On a plugin activation','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_plugin_activate_notification_container',
						'container-class' 	=> 'nfplwd-plugin-activate-notification-container',					
						'input-name' 		=> 'nfproot_plugin_activate_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_plugin_activate_notification',
						'input-class'		=> 'nfplwd-plugin-activate-notification',
						'input-description' => __('If switched on, a notification will be sent when a plugin is activated','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),	

					array(
					
						'container-title'	=> __('When suspect files are found','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_core_difference_notification_container',
						'container-class' 	=> 'nfplwd-core-difference-notification-container',					
						'input-name' 		=> 'nfproot_core_difference_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_core_difference_notification',
						'input-class'		=> 'nfplwd-core-difference-notification',
						'input-description' => __('If switched on, a notification will be sent when the daily security check will find suspect files','nutsforpress-login-watchdog').'<br><strong><small>'.__('Last security check','nutsforpress-login-watchdog').': '.$nfplwd_last_core_check_result.' ('.$nfplwd_last_core_check_time.')</small></strong><br><br>'.sprintf(
						
								__('If you want to manually run the security check now and you wish to be notified by email about results, even if no suspect files are found, %s','nutsforpress-login-watchdog'),
								'<a href="'.site_url().'?nfplwd-core-difference-check=true" target="_blank" title="'.__('run the security check now','nutsforpress-login-watchdog').'" alt="'.__('run the security check now','nutsforpress-login-watchdog').'">'.__('click here','nutsforpress-login-watchdog').'</a>'
								
							),
						'arrow-before'		=> false,
						'after-input'		=> false,
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),						

					array(
					
						'container-title'	=> __('Notification address','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_notification_address_container',
						'container-class' 	=> 'nfplwd-notification-address-container',					
						'input-name' 		=> 'nfproot_notification_address',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_notification_address',
						'input-class'		=> 'nfplwd-notification-address',
						'input-description' => __('Enter the email address to be used for all the above notifications','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'email',
						'input-value'		=> get_option('admin_email')
						
					),	

					array(
					
						'container-title'	=> __('Notify administrator on successful login','nutsforpress-login-watchdog'),
					
						'container-id'		=> 'nfplwd_admin_on_login_notification_container',
						'container-class' 	=> 'nfplwd-admin-on-login-notification-container',					
						'input-name' 		=> 'nfproot_admin_on_login_notification',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfplwd',
						'input-id' 			=> 'nfplwd_admin_on_login_notification',
						'input-class'		=> 'nfplwd-admin-on-login-notification',
						'input-description' => __('If switched on, a notification will be sent to the administrator who logs in successfully','nutsforpress-login-watchdog'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'checkbox',
						'input-value'		=> 1,
						
					),					
					
				),
				
			),
				
		);
						
		return $nfplwd_settings_content;
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_settings_content" already exists');
	
}