<?php
/*
Plugin Name: SmartKeys
Description: Create your own keyboard shortcuts
Author: Derek Smart
Version: 0.1
License: GPLv2 or later
*/

define( 'SMARTKEYS_VERSION', '0.1' );
define( 'SMARTKEYS__MINIMUM_WP_VERSION', '3.1' );
define( 'SMARTKEYS__PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'SMARTKEYS__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( is_admin() ) {
	require_once( SMARTKEYS__PLUGIN_DIR . 'class.admin-setup.php' );
	new Smartkeys_Admin();
}

require_once( SMARTKEYS__PLUGIN_DIR . 'class.smartkeys.php' );

add_action( 'init', array( 'Smartkeys', 'init' ) );