<?php
//if this file is called directly, die.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//with this function we will create the NutsForPress menu page
if(!function_exists('nfplwd_settings')) {
	
	function nfplwd_settings() {	
		
		global $nfproot_root_settings;
		$nfplwd_pro = null;
		
		if(
		
			!empty($nfproot_root_settings) 
			&& !empty($nfproot_root_settings['installed_plugins']['nfplwd']['edition'])
			&& $nfproot_root_settings['installed_plugins']['nfplwd']['edition'] === 'registered'
			
		) {
			
			$nfplwd_pro = ' <span class="dashicons dashicons-saved"></span>';
			
		}
		
		add_submenu_page(
	
			'nfproot-settings',
			'Login Watchdog',
			'Login Watchdog'.$nfplwd_pro,
			'manage_options',
			'nfplwd-settings',
			'nfplwd_settings_callback'
		
		);
		
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_base_options" already exists');
	
}
	
//with this function we will define the NutsForPress menu page content
if(!function_exists('nfplwd_settings_callback')) {
	
	function nfplwd_settings_callback() {
		
		?>
		
		<div class="wrap nfproot-settings-wrap">
			
			<h1>Login Watchdog settings</h1>
			
			<div class="nfproot-settings-main-container">
		
				<?php
				
				//include option content page
				require_once NFPLWD_BASE_PATH.'/admin/nfplwd-settings-content.php';
				
				//define contents as result of the function nfplwd_settings_content
				$nfplwd_settings_content = nfplwd_settings_content();
				
				//invoke nfproot_options_structure functions included into /root/options/nfproot-options-structure.php
				nfproot_settings_structure($nfplwd_settings_content);
				
				?>
			
			</div>
		
		</div>
		
		<?php
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfplwd_settings" already exists');
	
}