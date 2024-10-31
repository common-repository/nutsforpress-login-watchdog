<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_custom_login_errors')){

	function nfplwd_custom_login_errors($nfplwd_login_form_errors) {

		//get options 
		global $nfproot_current_language_settings;

		//if custom login error is enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_custom_login_errors'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_custom_login_errors'] === '1'
								
		) {
			
			if(
			
				isset($nfplwd_login_form_errors->errors['empty_username']) 
				&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_empty_username_error'])
				
			){
				
				$nfplwd_login_form_errors->errors['empty_username'][0] = esc_html($nfproot_current_language_settings['nfplwd']['nfproot_empty_username_error']);
			
			}
			
			if(
			
				isset($nfplwd_login_form_errors->errors['empty_password']) 
				&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_empty_password_error'])
				
			){
				
				$nfplwd_login_form_errors->errors['empty_password'][0] = esc_html($nfproot_current_language_settings['nfplwd']['nfproot_empty_password_error']);
			
			}
		
			if(
			
				isset($nfplwd_login_form_errors->errors['invalid_username']) 
				&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_wrong_username_error'])
				
			){
				
				$nfplwd_login_form_errors->errors['invalid_username'][0] = esc_html($nfproot_current_language_settings['nfplwd']['nfproot_wrong_username_error']);
			
			}
			
			if(
			
				isset($nfplwd_login_form_errors->errors['invalid_email']) 
				&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_wrong_email_error'])
				
			){
				
				$nfplwd_login_form_errors->errors['invalid_email'][0] = esc_attr($nfproot_current_language_settings['nfplwd']['nfproot_wrong_email_error']);
			
			}
			
			if(
			
				isset($nfplwd_login_form_errors->errors['incorrect_password']) 
				&& !empty($nfproot_current_language_settings['nfplwd']['nfproot_wrong_password_error'])
				
			){
				
				$nfplwd_login_form_errors->errors['incorrect_password'][0] = esc_attr($nfproot_current_language_settings['nfplwd']['nfproot_wrong_password_error']);
			
			}			

		}			
		
		return $nfplwd_login_form_errors;

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_custom_login_errors" already exists');
	
}