<?php
/*
Plugin Name: MyTheme Core
Plugin URI: https://www.habibportfolio.com
Description: MyTheme Theme Core Plugin
Version: 1.6.15
Author: RadiusTheme
Author URI: https://www.habibportfolio.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MYTHEME_CORE' ) ) {
	define( 'MYTHEME_CORE', '1.6.15' );
	define( 'MYTHEME_CORE_THEME_PREFIX', 'mytheme' );
	define( 'MYTHEME_CORE_BASE_URL', plugin_dir_url( __FILE__ ) );
	define( 'MYTHEME_CORE_BASE_DIR', plugin_dir_path( __FILE__ ) );
}

class MyTheme_Core {

	public $plugin = 'mytheme-core';
	public $action = 'mytheme_theme_init';
	protected static $instance;

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'load_textdomain' ], 20 );
		add_action( $this->action, [ $this, 'after_theme_loaded' ] );
//		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function after_theme_loaded() {
		require_once MYTHEME_CORE_BASE_DIR . 'lib/sidebar-generator/init.php'; // Sidebar generator
		require_once MYTHEME_CORE_BASE_DIR . 'lib/wp-svg/init.php'; // SVG support

		if ( did_action( 'elementor/loaded' ) ) {
			require_once MYTHEME_CORE_BASE_DIR . 'elementor/init.php'; // Elementor
		}
	}


	public function load_textdomain() {
		load_plugin_textdomain( $this->plugin, false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

}

MyTheme_Core::instance();