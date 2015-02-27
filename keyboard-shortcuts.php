<?php
/*
Plugin Name: Keyboard Shortcuts
Description: Create your own keyboard shortcuts
Author: Derek Smart
Version: 0.1
License: GPLv2 or later
*/

define( 'KEYBOARD_SHORTCUT_VERSION', '0.1' );
define( 'KEYBOARD_SHORTCUT__MINIMUM_WP_VERSION', '3.1' );
define( 'KEYBOARD_SHORTCUT__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'KEYBOARD_SHORTCUT__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( is_admin() ) {
	require_once( KEYBOARD_SHORTCUT__PLUGIN_DIR . 'class.admin-setup.php' );
	new Keyboard_Shortcuts_Admin();
}

require_once( KEYBOARD_SHORTCUT__PLUGIN_DIR . 'class.keyboard-shortcuts.php' );

add_action( 'init', array( 'Keyboard_Shortcuts', 'init' ) );