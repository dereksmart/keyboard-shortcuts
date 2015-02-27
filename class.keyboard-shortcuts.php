<?php

class Keyboard_Shortcuts {
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() {
		self::$initiated = true;

		add_action( 'wp_enqueue_scripts',    array( 'Keyboard_Shortcuts', 'keyboard_shortcuts_enqueue' ) );
		add_action( 'admin_enqueue_scripts', array( 'Keyboard_Shortcuts', 'keyboard_shortcuts_enqueue' ) );
	}

	public static function keyboard_shortcuts_enqueue() {
		wp_enqueue_script( 'keyboard-shortcut', plugin_dir_url( __FILE__ ) . 'js/keyboard-shortcuts.js', array( 'jquery' ), false );
		wp_localize_script( 'keyboard-shortcut', 'keyboard_shortcut_vars',
			array(
				'home_url' => home_url()
			)
		);
	}

}