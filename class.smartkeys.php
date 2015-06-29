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
	 * Convert menu_slug to menu title
	 *
	 * @param string $path
	 *
	 * @return string - the Title of the page.  Or WP_Error if something went wrong.
	 */
	function smartkeys_convert_to_title( $path = '' ) {
		global $menu;

		if ( ! is_string( $path ) ) {
			return new WP_Error( 'not-a-string', 'gotta use a string' );
		}

		foreach ( $menu as $page => $values ) {
			if ( $path == $values[2] ) {
				return $values[0];
			}
		}

		return 'Other';
	}

	/*
	 * All of the admin pages in organized arrays
	 *
	 * @return array
	 */
	function smartkeys_organized_pages() {
		global $submenu;

		$total = array();

		foreach ( $submenu as $index => $pages ) {
			$first = true;
			$title = $this->smartkeys_convert_to_title( $index );

			$group = array();
			foreach ( $pages as $page ) {
				if ( $first ) {
					echo $title . ' - ';
					$first = false;
				}
				$group[ $page[0] ] = $page;
				$total[ $title ] = $group;
			}
		}

		return $total;
	}

	function smartkeys_enqueue_admin_keys() {
		if ( ! is_admin() ) {
			return;
		}

		include_once 'larry-bird.php';
		wp_enqueue_script( 'smartkeys-master', plugin_dir_url( __FILE__ ) . 'js/smartkeys-master.js', array( 'jquery', 'underscore' ), false );
		wp_localize_script( 'smartkeys-master', 'smartkeys_master_vars',
			array(
				'home_url'         => home_url(),
				'option_keycodes'  => get_option( 'keys_to_save' ),
				'prompt_commands'  => $this->smartkeys_organized_pages(),
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