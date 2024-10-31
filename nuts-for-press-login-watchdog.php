<?php
/*
Plugin Name: 	NutsForPress Login Watchdog
Plugin URI:		https://www.nutsforpress.com/
Description: 	NutsForPress Login Watchdog is an essential tool for protectiong and securing WordPress login, by checking login attempts, role changes, xmlrpc and much more. 
Version:     	2.2
Author:			Christian Gatti
Author URI:		https://profiles.wordpress.org/christian-gatti/
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:	nutsforpress-login-watchdog
Domain Path:	/languages/
*/

//if this file is called directly, die.
if(!defined('ABSPATH')) die('please, do not call this page directly');


//DEFINITIONS

if(!defined('NFPROOT_BASE_RELATIVE')) {define('NFPROOT_BASE_RELATIVE', dirname(plugin_basename( __FILE__ )).'/root');}
define('NFPLWD_BASE_PATH', plugin_dir_path( __FILE__ ));
define('NFPLWD_BASE_URL', plugins_url().'/'.plugin_basename( __DIR__ ).'/');
define('NFPLWD_BASE_RELATIVE', dirname( plugin_basename( __FILE__ )));
define('NFPLWD_DEBUG', false);


//NUTSFORPRESS ROOT CONTENT
	
//add NutsForPress parent menu page
require_once NFPLWD_BASE_PATH.'root/nfproot-settings.php';
add_action('admin_menu', 'nfproot_settings');

//add NutsForPress save settings function and make it available through ajax
require_once NFPLWD_BASE_PATH.'root/nfproot-save-settings.php';
add_action('wp_ajax_nfproot_save_settings', 'nfproot_save_settings');

//add NutsForPress saved settings and make them available through the global varibales $nfproot_current_language_settings and $nfproot_options_name
require_once NFPLWD_BASE_PATH.'root/nfproot-saved-settings.php';
add_action('plugins_loaded', 'nfproot_saved_settings');

//register NutsForPress styles and scripts
require_once NFPLWD_BASE_PATH.'root/nfproot-styles-and-scripts.php';
add_action('admin_enqueue_scripts', 'nfproot_styles_and_scripts');
	
//add NutsForPress settings structure that contains nfproot_options_structure function invoked by plugin settings
require_once NFPLWD_BASE_PATH.'root/nfproot-settings-structure.php';


//PLUGIN INCLUDES

//add activate actions
require_once NFPLWD_BASE_PATH.'includes/nfplwd-plugin-activate.php';
register_activation_hook(__FILE__, 'nfplwd_plugin_activate');

//add deactivate actions
require_once NFPLWD_BASE_PATH.'includes/nfplwd-plugin-deactivate.php';
register_deactivation_hook(__FILE__, 'nfplwd_plugin_deactivate');

//add uninstall actions
require_once NFPLWD_BASE_PATH.'includes/nfplwd-plugin-uninstall.php';
register_uninstall_hook(__FILE__, 'nfplwd_plugin_uninstall');

//load languages
require_once NFPLWD_BASE_PATH.'includes/nfplwd-load-languages.php';
add_filter('load_textdomain_mofile', 'nfplwd_load_languages', 10, 2);

//styles and scripts
require_once NFPLWD_BASE_PATH.'includes/nfplwd-styles-and-scripts.php';
add_action('admin_enqueue_scripts', 'nfplwd_styles_and_scripts');


//PLUGIN SETTINGS

//add plugin settings
require_once NFPLWD_BASE_PATH.'admin/nfplwd-settings.php';
add_action('admin_menu', 'nfplwd_settings');


//INCLUDES CONDITIONALLY

require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-get-user-ip.php';

require_once NFPLWD_BASE_PATH.'admin/includes/nfplwd-disable-xmlrpc.php';
add_action('init', 'nfplwd_disable_xmlrpc');

require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-core-difference-check.php';
add_action('wp_enqueue_scripts', 'nfplwd_core_difference_check');

//no action is needed: notification is sent by nfplwd-register-successful-login and/or nfplwd-check-two-factors-authentication functions
require_once NFPLWD_BASE_PATH.'admin/includes/nfplwd-user-login-notification.php';
require_once NFPLWD_BASE_PATH.'admin/includes/nfplwd-login-notification-to-admin.php';

require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-register-successful-login.php';
add_action('wp_login', 'nfplwd_register_successful_login', 10, 2);
	
require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-check-login.php';
add_filter('wp_authenticate', 'nfplwd_check_login', 50, 2);

require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-check-two-factors-authentication.php';
add_filter('wp_authenticate', 'nfplwd_check_two_factor_authentication', 50, 2);
add_action('login_form_nfplwd-two-factors-authentication','nfplwd_two_factors_authentication_form');

require_once NFPLWD_BASE_PATH.'public/includes/ajax/nfplwd-core-difference-notification.php';
add_action('wp_ajax_nfplwd_core_difference_notification', 'nfplwd_core_difference_notification');
add_action('wp_ajax_nopriv_nfplwd_core_difference_notification', 'nfplwd_core_difference_notification');


//ADMIN INCLUDES CONDITIONALLY

if(is_admin()){

	require_once NFPLWD_BASE_PATH.'admin/includes/nfplwd-change-role-notification.php';
	add_action('set_user_role', 'nfplwd_change_role_notification', 10, 3);	
	
	require_once NFPLWD_BASE_PATH.'admin/includes/nfplwd-user-delete-notification.php';
	add_action('delete_user', 'nfplwd_user_delete_notification',10 ,2);
	
	require_once NFPLWD_BASE_PATH.'admin/includes/nfplwd-plugin-activate-notification.php';
	add_action('activate_plugin', 'nfplwd_plugin_activate_notification', 10, 1);
		
}


//PUBLIC INCLUDES CONDITIONALLY

if(!is_admin()){
	
	require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-custom-login-errors.php';
	add_filter('wp_login_errors', 'nfplwd_custom_login_errors', 10, 1);
	//add_action('init', 'nfplwd_custom_login_errors');

	require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-hide-authors-archive.php';
	add_action('init', 'nfplwd_hide_authors_archive');
	
	require_once NFPLWD_BASE_PATH.'public/includes/nfplwd-hide-usernames-api.php';
	add_filter('rest_endpoints', 'nfplwd_hide_usernames_api');

}