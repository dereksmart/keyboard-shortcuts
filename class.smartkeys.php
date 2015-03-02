<?php

add_action( 'init', array( 'Smartkeys', 'init' ) );

class Smartkeys {

	public static function init() {
		static $instance = NULL;

		if ( !$instance ) {
			$instance = new Smartkeys;
		}

		return $instance;
	}

	function __construct() {

		if ( is_user_logged_in() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'smartkeys_enqueue_admin_keys' ) );
			add_action( 'wp_enqueue_scripts',    array( $this, 'smartkeys_enqueue_admin_keys' ) );
		} else {
			add_action( 'wp_enqueue_scripts',    array( $this, 'smartkeys_enqueue_visitor_keys' ) );
		}

	}

	/*
	 * Handle WordPress commands
	 * @return array
	 */
	function smartkeys_prompt_commands() {
		global $menu, $submenu;

		/*
		$submenu_name = array();
		$submenu_url  = array();
		if ( is_array( $submenu ) ) {
			foreach ( $submenu as $k => $submenu_group ) {
				foreach( $submenu_group as $submenu_item ) {
					$submenu_name[] = $submenu_item[0];
					$submenu_url[]  = $submenu_item[2];
				}
			}
		}
		*/

		$menu_name = array();
		$menu_url  = array();
		if ( is_array( $menu ) ) {
			foreach ( $menu as $k => $v ) {
				// If a plugin adds a top-level menu, make sure we can get to it.
				if ( ! strstr( $v[2], '.php' ) ) {
					$v[2] = 'admin.php?page=' . $v[2];
				}

				// Some menu items have span tags in them, let's remove the number
				if ( preg_match( '/(>\d{1,3}<)/', $v[0], $match ) ) {
					$v[0] = wp_strip_all_tags( str_replace( $match, '', $v[0] ) );
				}

				// We don't want to add separators here.
				if ( $v[4] !== 'wp-menu-separator' ) {
					$menu_name[] = strtolower( $v[0] );
					$menu_url[]  = $v[2];
				}
			}
		}

		$defaults = array(
			'command' => $menu_name,
			'action'  => $menu_url
		);

		// Only update in admin because global $menu is not available outside of admin
		if ( is_admin() ) {
			update_option( 'smartkey_commands', $defaults );
		}

		$default_commands = get_option('smartkey_commands');

		return $default_commands;
	}

	function smartkeys_jetpack_prompt_commands() {
		if ( Jetpack::is_active() ) {
			$modules = Jetpack::get_available_modules();

			$module_name = array();
			$settings_url = array();
			foreach ( $modules as $module ) {
				if ( Jetpack::module_configuration_url( $module ) ) {
					$settings_url[] = Jetpack::module_configuration_url( $module );
				}

				$module_name[] = $module;
			}
		}

		$jp_defaults = array(
			'command' => $module_name,
			'action'  => $settings_url
		);

		return $jp_defaults;
	}

	function smartkeys_enqueue_admin_keys() {
		wp_enqueue_script( 'smartkeys-master', plugin_dir_url( __FILE__ ) . 'js/smartkeys-master.js', array( 'jquery', 'underscore' ), false );
		wp_localize_script( 'smartkeys-master', 'smartkeys_master_vars',
			array(
				'home_url'         => home_url(),
				'option_keycodes'  => get_option( 'keys_to_save' ),
				'prompt_commands'  => $this->smartkeys_prompt_commands(),
				'jetpack_commands' => $this->smartkeys_jetpack_prompt_commands(),
				'currentCombo'   => '',
			)
		);
	}

	function smartkeys_enqueue_visitor_keys() {
		wp_localize_script( 'smartkeys', 'smartkeys_vars',
			array(
				'home_url' => home_url()
			)
		);
	}

}