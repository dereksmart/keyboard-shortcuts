<?php
/*
 * Registers the Settings page
 *
*/

class Keyboard_Shortcuts_Admin {
	
	function __construct() {
		// Register scripts
		add_action( 'admin_init', array( $this, 'keyboard_shortcuts_register_assets' ) );

		// Set up the admin page in menu
		add_action( 'admin_menu', array( $this, 'keyboard_shortcuts_register_admin_page' ), 1 );
		
		// Save changes
		add_action( 'admin_init', array( $this, 'keyboard_shortcuts_save_settings' )  );
	}
	
	function keyboard_shortcuts_register_assets() {
		wp_register_style( 'keyboard-shortcuts-css', plugins_url( 'css/style.css' , __FILE__ ) );
	}
	

	/*
	 * Register Settings Page
	 */
	function keyboard_shortcuts_register_admin_page() {
		$parent_slug                   = 'options-general.php';
		$keyboard_shortcuts_page_title = 'Keyboard Shortcuts Settings';
		$keyboard_shortcuts_menu_title = 'Keyboard Shortcuts';
		$keyboard_shortcuts_capability = 'manage_options';
		$keyboard_shortcuts_menu_slug  = 'keyboard-shortcuts';
		$keyboard_shortcuts_function   = array( $this, 'render_keyboard_shortcuts_main_page' );
		
		add_submenu_page(
			$parent_slug,
			$keyboard_shortcuts_page_title,
			$keyboard_shortcuts_menu_title,
			$keyboard_shortcuts_capability,
			$keyboard_shortcuts_menu_slug,
			$keyboard_shortcuts_function
		);


		add_action( 'admin_enqueue_scripts', array( $this, 'keyboard_shortcuts_enqueue_assets' ) );
	}

	// Enqueue Style
	function keyboard_shortcuts_enqueue_assets( $hook ) {
		if ( 'settings_page_keyboard-shortcuts' != $hook )
			return;

		wp_enqueue_style( 'keyboard-shortcuts-css' );
	}
	
	// Include view file for settings
	function render_keyboard_shortcuts_main_page() {
		include 'views/admin-settings.php';
	}
	
	/**
	 * Save our settings
	 */
	function keyboard_shortcuts_save_settings() {
		if ( isset( $_POST['keyboard_shortcuts_save_nonce'] ) && wp_verify_nonce( $_POST['keyboard_shortcuts_save_nonce'], 'keyboard_shortcuts_save' ) ) {
			update_option( 'keys_to_save', $_POST['saved_keys'] );
			add_action( 'admin_notices', array( $this, 'keyboard_shortcuts_updated_success_message' ) );
		}
	}

	function keyboard_shortcuts_updated_success_message() {
		echo '<div id="message" class="updated below-h2"><p>Settings Updated!</p></div>';
	}
}