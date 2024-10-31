=== NutsForPress Login Watchdog ===

Contributors: Christian Gatti
Tags: two factor,two factors,authentication,login,attempt,monitor,secure,protect,lock,down,custom,errors,xml,rpc,notification
Donate link: https://www.paypal.com/paypalme/ChristianGatti
Requires at least: 5.3
Tested up to: 6.5
Requires PHP: 7.0.0
Stable tag: 2.2
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

NutsForPress Login Watchdog a simple and lightweight plugin that protects your site from unauthorized login attempts.


== Description ==

*Login Watchdog* is one of the several NutsForPress plugins providing some essential features that WordPress does not offer itself or offers only partially.  

*Login Watchdog* include these very useful functions for your every-day secure and protection of your WordPress website:

*   Two Factors Authentication for administrators: after a successful login every administrator is asked to enter a validation code sent by email; you can also set an option to skip two factors authentication until IP does not change, which is extremely useful for administrators they need to login frequently from the same IP
*   Login Attempts Monitor: checks login attempts and blocks (temporarily or permanently) failed login attempts exceeding the limits defined
*   Daily Check Notification: sends a notification when a difference is found between your site WordPress core files and the original ones of a clean setup, or when other suspect files are found
*   Security Notification: sends a notification on administrator successful login, on change of role, on plugin activation, on administrator delete and on user temporary or permanent lock down
*   Disable XML-RPC: disables XML-RPC protocol to prevent brute force attacks
*   Disable Author Page: hides author archive page and hides user info via API REST, so that none can know the author name by his ID
*   Custom Login Errors: displays custom login errors instead of WordPress default

Login Watchdog is full compliant with WPML (you don't need to translate any option value)

Take a look at the others [NutsForPress Plugins](https://wordpress.org/plugins/search/nutsforpress/)

**Whatever is worth doing at all is worth doing well**


== Installation ==

= Installation From Plugin Repository =

* Into your WordPress plugin section, press "Add New"
* Use "NutsForPress" as search term
* Click on *Install Now* on *NutsForPress Login Watchdog* into result page, then click on *Activate*
* Setup "NutsForPress Login Watchdog" options by clicking on the link you find under the "NutsForPress" menu
* Enjoy!

= Manual Installation =

* Download *NutsForPress Login Watchdog* from https://wordpress.org/plugins/nutsforpress
* Into your WordPress plugin section, press "Add New" then press "Load Plugin"
* Choose nutsforpress-smtp-mail.zip file from your local download folder
* Press "Install Now"
* Activate *NutsForPress Login Watchdog*
* Setup "NutsForPress Login Watchdog" options by clicking on the link you find under the "NutsForPress" menu
* Enjoy!


== Changelog ==

= 2.2 =
* now you can choose to send a notification to the administrator who logs in successfully

= 2.1 =
* during the WP core difference check a curl instance is now used if the function file_get_contents fails

= 2.0 =
* fixed a bug that prevented the two factor authentication process to be completed after update to WordPress 6.3

= 1.9 =
* Now it is possible to manually run the security check for suspect files

= 1.8 =
* Fixed a bug that caused false positives on plugins and themes coming from the core and updated after the core setup (Akismet, for instance)

= 1.7.9 =
* Fixed a bug that caused the reset of the options of this plugin when WPML was installed and activated after the configuration of this plugin

= 1.7.8 =
* Now a more precise and verbose notification is sent in case of suspect files
* Some folders are now excluded from the daily security check, in order to reduce the risk of being notified for false positives

= 1.7.7 =
* Now a deeper investigation is done on suspect files: files not involved in the WordPress core files check now are also subject to investigation; even if the check is a quite shallow, it reveals surprisingly effective in many cases

= 1.7.6 =
* Now the user delete notification is sent only on delete of a user with administrative roles (otherwise too much notifications are sent)
* Few bugs fixed
* Tested up to WordPress 6.2

= 1.7.5 =
* Fixed a bug that caused the core integrity check to be performed more than once a day on websites with WP Rocket or other caching plugins

= 1.7.4 =
* Now the login notification is sent only on successful login of a user with administrative roles (otherwise too much notifications are sent)

= 1.7.3 =
* Fixed a bug that caused the error "The provided code is not valid" to be displayed when a non existent user tried to login

= 1.7.2 =
* Fixed a bug that caused the themes folder and the plugin folder to be included into daily check, returning false positives

= 1.7.1 =
* Fixed a bug that caused the daily check notification to not be launched on some conditions
* Addeed a filter to exclude from the daily check the themes and the plugins folder files (plugins and themes can be updated regardless of the WordPress version)

= 1.7 =
* Now you can get notified when a difference is found between your site WordPress core php or js files and the original ones of a clean setup
* Fixed a bug that caused the login notification to be sent before the two-factor authentication was completed
* Raised to ten minutes the maximum time to complete the two-factor authentication

= 1.6 =
* Now you can get a notification on plugin activation

= 1.5 =
* Now you can set an option to skip two factors authentication until IP does not change
* Local italian translations added (the ones from https://translate.wordpress.org/ are still pending)

= 1.4 =
* A very useful two factor authentication for administrators has been implemented

= 1.3 =
* Now translations are provided by translate.wordpress.org, instead of being locally provided: please contribute!

= 1.2.2 =
* Fixed a bug that displayed some option messages that should have been kept hidden by a css rule miswritten by an escape rule

= 1.2.1 =
* Fixed a bug that caused to some urls contained into some descriptions in the plugin options were showed as html code, instead of clickable urls 

= 1.2 =
* New root version, in order to welcome a new NutsForPress plugin
* Security improved by escaping echoed variables

= 1.1.1 =
* Fixed a bug that produced a warning into logs
* Now the function that hides author archive pages redirects to the homepage only if pages are called through URL parameter (for example: ?author=1)

= 1.1 =
* Fixed a bug that prevented from saving local options when WPML is not active

= 1.0 =
* First full working release


== Translations ==

* English: default language
* Italian: entirely translated


== Credits ==

* Very many thanks to [DkR](https://dkr.srl/) and [SviluppoEuropa](https://sviluppoeuropa.it/)!