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

		add_action( 'wp_enqueue_scripts',    array( 'Smartkeys', 'smartkeys_enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( 'Smartkeys', 'smartkeys_enqueue' ) );
	}

	public static function smartkeys_enqueue() {
		wp_enqueue_script( 'smartkeys', plugin_dir_url( __FILE__ ) . 'js/smartkeys.js', array( 'jquery' ), false );
		wp_localize_script( 'smartkeys', 'smartkeys_vars',
			array(
				'home_url' => home_url()
			)
		);
	}

}