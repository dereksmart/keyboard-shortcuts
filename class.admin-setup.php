<?php
/*
 * Registers the Settings page
 *
*/

class Smartkeys_Admin {
	
	function __construct() {
		// Register scripts
		add_action( 'admin_init', array( $this, 'smartkeys_register_assets' ) );

		// Set up the admin page in menu
		add_action( 'admin_menu', array( $this, 'smartkeys_register_admin_page' ), 1 );
		
		// Save changes
		add_action( 'admin_init', array( $this, 'smartkeys_save_settings' )  );
	}
	
	function smartkeys_register_assets() {
		wp_register_style( 'smartkeys-css', plugins_url( 'css/style.css' , __FILE__ ) );
	}
	

	/*
	 * Register Settings Page
	 */
	function smartkeys_register_admin_page() {
		$parent_slug                   = 'options-general.php';
		$smartkeys_page_title = 'SmartKeys Settings';
		$smartkeys_menu_title = 'SmartKeys';
		$smartkeys_capability = 'manage_options';
		$smartkeys_menu_slug  = 'smartkeys';
		$smartkeys_function   = array( $this, 'render_smartkeys_main_page' );
		
		add_submenu_page(
			$parent_slug,
			$smartkeys_page_title,
			$smartkeys_menu_title,
			$smartkeys_capability,
			$smartkeys_menu_slug,
			$smartkeys_function
		);


		add_action( 'admin_enqueue_scripts', array( $this, 'smartkeys_enqueue_assets' ) );
	}

	// Enqueue Style
	function smartkeys_enqueue_assets( $hook ) {
		if ( 'settings_page_smartkeys' != $hook )
			return;

		wp_enqueue_style( 'smartkeys-css' );
	}
	
	// Include view file for settings
	function render_smartkeys_main_page() {
		include 'views/admin-settings.php';
	}
	
	// Save the settings
	function smartkeys_save_settings() {
		if ( isset( $_POST['smartkeys_save_nonce'] ) && wp_verify_nonce( $_POST['smartkeys_save_nonce'], 'smartkeys_save' ) ) {
			update_option( 'keys_to_save', $_POST['saved_keys'] );
			add_action( 'admin_notices', array( $this, 'smartkeys_updated_success_message' ) );
		}
	}

	function smartkeys_updated_success_message() {
		echo '<div id="message" class="updated below-h2"><p>Settings Updated!</p></div>';
	}
}