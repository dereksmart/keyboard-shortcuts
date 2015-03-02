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
		add_action( 'init', array( $this, 'smartkeys_jetpack_commands' ) );
		add_action( 'init', array( $this, 'smartkeys_wordpress_commands' ) );

		if ( is_user_logged_in() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'smartkeys_enqueue_admin_keys' ) );
			add_action( 'wp_enqueue_scripts',    array( $this, 'smartkeys_enqueue_admin_keys' ) );
		} else {
			add_action( 'wp_enqueue_scripts',    array( $this, 'smartkeys_enqueue_visitor_keys' ) );
		}
	}

	/*
	 * Handle the Jetpack commands
	 * @return array
	 */
	function smartkeys_jetpack_commands() {
		$default_jp_commands = array(
			'jp_settings' => admin_url( 'admin.php?page=jetpack_modules' ),
			'jp_stats'    => admin_url( 'admin.php?page=stats' ),
		);

		return $default_jp_commands;
	}

	/*
	 * Handle WordPress commands
	 * @return array
	 */
	function smartkeys_wordpress_commands() {
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
				$menu_name[] = $v[0];
				$menu_url[]  = $v[2];
			}
		}

		$default_wp_commands = array(
			'command' => $menu_name,
			'action'  => $menu_url
		);

		return $default_wp_commands;
	}

	function smartkeys_enqueue_admin_keys() {
		wp_enqueue_script( 'smartkeys-master', plugin_dir_url( __FILE__ ) . 'js/smartkeys-master.js', array( 'jquery', 'underscore' ), false );
		wp_localize_script( 'smartkeys-master', 'smartkeys_master_vars',
			array(
				'home_url'        => home_url(),
				'option_keycodes' => get_option( 'keys_to_save' ),
				'jp_commands'     => $this->smartkeys_jetpack_commands(),
				'wp_commands'     => $this->smartkeys_wordpress_commands(),
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