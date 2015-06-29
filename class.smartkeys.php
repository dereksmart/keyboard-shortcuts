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
		// Register scripts
		add_action( 'init', array( $this, 'smartkeys_register_assets' ) );

		if ( is_user_logged_in() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'smartkeys_enqueue_admin_keys' ) );
			add_action( 'wp_enqueue_scripts',    array( $this, 'smartkeys_enqueue_admin_keys' ) );
		} else {
			add_action( 'wp_enqueue_scripts',    array( $this, 'smartkeys_enqueue_visitor_keys' ) );
		}

	}

	function smartkeys_register_assets() {
		wp_register_style( 'smartkeys-css', plugins_url( 'css/style.css' , __FILE__ ) );
	}

	/*
	 * Get the top level admin pages
	 *
	 * @return array
	 */
	function smartkeys_get_top_level_menu() {
		global $menu;

		$menu_slugs = array();
		// Get the top level menu as an array
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
				$menu_slugs[$v[2] . ' - ' . $v[0]]  = array(
					'slug'    => $v[1],
					'parent'  => $v[2],
					'name'    => $v[0],
					'command' => strtolower( $v[0] ),
					'action'  => $v[2],
				);
			}
		}

		return $menu_slugs;
	}


	/*
	 * Get the sub pages in the admin
	 *
	 * @return array
	 */
	function smartkeys_get_sub_menu_pages() {
		global $submenu;

		// Submenu slugs
		$submenu_slugs = array();
		if ( is_array( $submenu ) ) {
			foreach ( $submenu as $k => $group ) {
				foreach( $group as $g ) {
					$submenu_slugs[$k. ' - ' . $g[0]] = array(
						'slug'    => $g[1],
						'parent'  => $k,
						'name'    => $g[0],
						'command' => strtolower( $g[0] ),
						'action'  => $g[2],
					);
				}
			}
		}

		return $submenu_slugs;
	}

	/*
	 * Handle WordPress commands
	 * @return array
	 */
	function smartkeys_prompt_commands() {
		global $submenu;

		echo '<pre>';
		print_r( $this->smartkeys_get_sub_menu_pages() );
		echo '</pre>';
		exit;
		/*
		// Merge them all and sort
		$all = array_merge_recursive( $menu_slugs, $submenu_slugs );
		foreach ( $all as $row ) {
			foreach ( $row as $key => $value ) {
				${$key}[] = $value;
			}
		}

//		array_multisort( $parent, SORT_ASC, $parenty, SORT_DESC, $all );
		
		// Only update in admin because global $menu is not available outside of admin
		if ( is_admin() ) {
			update_option( 'smartkey_commands', $all );
		}

		$default_commands = get_option( 'smartkey_commands' );

		return $default_commands;
		*/
	}

	/*
	 * Jetpack specific pages
	 *
	 * @return array
	 */
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
		} else {
			return false;
		}

		$jp_defaults = array(
			'command' => $module_name,
			'action'  => $settings_url
		);

		return $jp_defaults;
	}

	function smartkeys_enqueue_admin_keys() {
		include_once 'larry-bird.php';
		wp_enqueue_script( 'smartkeys-master', plugin_dir_url( __FILE__ ) . 'js/smartkeys-master.js', array( 'jquery', 'underscore' ), false );
		wp_localize_script( 'smartkeys-master', 'smartkeys_master_vars',
			array(
				'home_url'         => home_url(),
				'option_keycodes'  => get_option( 'keys_to_save' ),
//				'prompt_commands'  => $this->smartkeys_prompt_commands(),
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