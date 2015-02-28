<?php

class Smartkeys {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$initiated = true;

		if ( is_user_logged_in() ) {
			add_action( 'admin_enqueue_scripts', array( 'Smartkeys', 'smartkeys_enqueue_admin_keys' ) );
			add_action( 'wp_enqueue_scripts',    array( 'Smartkeys', 'smartkeys_enqueue_admin_keys' ) );
		}
		else {
			add_action( 'wp_enqueue_scripts',    array( 'Smartkeys', 'smartkeys_enqueue_visitor_keys' ) );
		}
	}

	public static function smartkeys_enqueue_admin_keys() {
		wp_enqueue_script( 'smartkeys-master', plugin_dir_url( __FILE__ ) . 'js/smartkeys-master.js', array( 'jquery' ), false );
		wp_localize_script( 'smartkeys-master', 'smartkeys_master_vars',
			array(
				'home_url' => home_url()
			)
		);
	}

	public static function smartkeys_enqueue_visitor_keys() {
		wp_localize_script( 'smartkeys', 'smartkeys_vars',
			array(
				'home_url' => home_url()
			)
		);
	}

}