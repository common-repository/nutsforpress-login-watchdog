<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

if(!function_exists('nfplwd_check_two_factor_authentication')){

	function nfplwd_check_two_factor_authentication($nfplwd_check_login_username, $nfplwd_check_login_password) {
		
		//get options 
		global $nfproot_current_language_settings;

		//if two factors authentication is enabled
		if(

			!empty($nfproot_current_language_settings['nfplwd']['nfproot_two_factors_authentication'])
			&& $nfproot_current_language_settings['nfplwd']['nfproot_two_factors_authentication'] === '1'
								
		){
			
			//if submit is clicked
			if(
			
				isset($_POST['wp-submit'])
				&& is_login()
				&& wp_doing_ajax() === false
				
			){
				
				$nfplwd_two_factors_meta = false;

				//if meta id is passed
				if(
				
					isset($_POST['nfplwd-mid'])
					&& !empty(absint($_POST['nfplwd-mid']))
					
				){
					
					$nfplwd_user_meta_id = absint($_POST['nfplwd-mid']);
				
					//get two factors meta
					$nfplwd_two_factors_meta = nfplwd_two_factors_get_meta($nfplwd_user_meta_id);					
					
				}
				
				//ten minutes ago
				$nfplwd_ten_minutes_ago = time() - 600;
				
				//check two factors meta
				if(
		
					isset($_POST['nfplwd-token'])
					&& isset($_POST['nfplwd-code'])
					&& $nfplwd_two_factors_meta !== false
					&& !empty($nfplwd_two_factors_meta)
					&& $nfplwd_two_factors_meta['token'] === sanitize_text_field($_POST['nfplwd-token'])
					&& $nfplwd_two_factors_meta['request_time'] >= $nfplwd_ten_minutes_ago
					
				){

					//check the provided code is the right one
					if(absint($_POST['nfplwd-code']) === absint($nfplwd_two_factors_meta['code'])){
						
						$nfplwd_user_id = $nfplwd_two_factors_meta['user_id'];
						
						//get current user IP
						$nfplwd_current_user_ip = nfplwd_get_user_ip();

						//save a user meta with the current IP
						if(!empty($nfplwd_current_user_ip)){
							
							update_user_meta($nfplwd_user_id, '_nfplwd_last_user_ip', sanitize_text_field($nfplwd_current_user_ip));
							
						}

						//delete two factors meta
						delete_user_meta($nfplwd_two_factors_meta['user_id'], '_nfplwd_two_factors_info');
						
						//authenticate user
						wp_clear_auth_cookie();
						wp_set_current_user($nfplwd_user_id);
						wp_set_auth_cookie($nfplwd_user_id, true);
						
						$nfplwd_get_user = get_user_by('id', $nfplwd_user_id);
						$nfplwd_check_login_username = $nfplwd_get_user -> user_login;
						
						//send notification stopped by two factors autentication
						nfplwd_user_login_notification($nfplwd_check_login_username, $nfplwd_get_user);
						nfplwd_login_notification_to_admin($nfplwd_check_login_username, $nfplwd_get_user);
						
						//redirect user to dashboard
						wp_safe_redirect(admin_url());
						exit;
						
					}
					
					add_filter('login_errors', 'nfplwd_wrong_code_message');
				
				}	
				
			}				
		
		} 	

	}

} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_check_two_factor_authentication" already exists');
	
}

if(!function_exists('nfplwd_two_factors_get_meta')){

	function nfplwd_two_factors_get_meta($nfplwd_user_meta_id){
		
		//check if arguments is not empty
		if(!empty($nfplwd_user_meta_id)){
			
			//get two factors meta by meta id
			$nfplwd_two_factors_info = get_metadata_by_mid('user', $nfplwd_user_meta_id);
			
			//if value is not empty, return it
			if(!empty($nfplwd_two_factors_info -> meta_value)){

				$nfplwd_meta_to_return = $nfplwd_two_factors_info -> meta_value;					
				return $nfplwd_meta_to_return;
				
			} 	
			
		} 

		return false;

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_two_factors_get_meta" already exists');
	
}

if(!function_exists('nfplwd_two_factors_authentication_form')){

	function nfplwd_two_factors_authentication_form(){
		
		//firstly check if meta id is set
		if(
		
			isset($_REQUEST['mid'])
			&& !empty(absint($_REQUEST['mid']))
			
		){
			
			$nfplwd_user_meta_id = absint($_REQUEST['mid']);
			
			//get two factors meta
			$nfplwd_two_factors_meta = nfplwd_two_factors_get_meta($nfplwd_user_meta_id);
			
			//check two factors meta
			if(
			
				$nfplwd_two_factors_meta !== false
				&& !empty($nfplwd_two_factors_meta)
				&& $nfplwd_two_factors_meta['token'] === sanitize_text_field($_REQUEST['token'])
				
			){

				//add this style if action is nfplwd-two-factors-authentication
				?>
				
				<style>
					#loginform > p:first-child{
						display:none;
					}
					.user-pass-wrap, .forgetmenot{
						display:none;
					}
				</style>

				<?php
				
				//add custom fields, then return
				add_action('login_form', 'nfplwd_two_factors_token_input');
				return;
							
			}
			
		}
		
		//if something went wrong, redirect to login url
		wp_safe_redirect(wp_login_url());

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_two_factors_authentication_form" already exists');
	
}

if(!function_exists('nfplwd_two_factors_token_input')){

	function nfplwd_two_factors_token_input(){

		//add custom fields input and remove the required attribute, added after WP6.3
		?>

		<script>
		
			document.getElementById('user_login').removeAttribute('required');
			document.getElementById('user_pass').removeAttribute('required');
		
		</script>
		
		<div class="nfplwd-user-pass-wrap">
			<label for="nfplwd-user_pass"><?php echo __('Enter the code received by email','nutsforpress-login-watchdog');?></label>
			<div class="wp-pwd">
				<input type="text" name="nfplwd-code" id="nfplwd-user_pass" class="input password-input" value="" size="20">
				<input type="hidden" name="nfplwd-token" value="<?php echo sanitize_text_field($_REQUEST['token']); ?>">
				<input type="hidden" name="nfplwd-mid" value="<?php echo sanitize_text_field($_REQUEST['mid']); ?>">
			</div>
		</div>
		
		<?php

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_two_factors_token_input" already exists');
	
}

//print the lock down message
if(!function_exists('nfplwd_wrong_code_message')) {
	
	function nfplwd_wrong_code_message($nfplwd_wp_login_error) {	
						
		$nfplwd_wp_login_error = __('The provided code is not valid','nutsforpress-login-watchdog');
		
		return $nfplwd_wp_login_error;
	}

} else {
	
	error_log('function: "nfplwd_wrong_code_message" already exists');
	
}