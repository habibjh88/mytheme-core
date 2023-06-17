<?php
/**
 * @author  MyTheme
 * @since   1.0
 * @version 1.0
 */

namespace MyTheme\MyTheme_Core;

use Elementor\Plugin;
use MyTheme\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

require_once MYTHEME_CORE_BASE_DIR . '/elementor/controls/traits-icons.php';

// Elementor default widget control
require_once __DIR__ . '/el-extend.php';

class Custom_Widget_Init {

	public function __construct() {
		add_action( 'elementor/widgets/register', [ $this, 'init' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'widget_category' ] );
		add_action( 'elementor/icons_manager/additional_tabs', [ $this, 'mytheme_flaticon_tab' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_style' ] );
	}

	public function editor_style() {
		$img = plugins_url( 'icon.png', __FILE__ );
		wp_enqueue_style( 'flaticon' );
		wp_add_inline_style( 'elementor-editor', '.elementor-element .icon .rdtheme-el-custom{content: url(' . $img . ');width: 28px;}' );
		wp_add_inline_style( 'elementor-editor', '.elementor-panel .select2-container {min-width: 100px !important; min-height: 30px !important;}' );
		wp_add_inline_style( 'elementor-editor', '.elementor-panel .elementor-control-type-heading .elementor-control-title {color: #93013d !important}' );
	}


	public function init() {
		require_once __DIR__ . '/base.php';

		// dirname => classname /@dev
		$widgets = [
			'title'             => 'Title',
			'post'              => 'Post',
			'text-button'       => 'Button',
			'info-box'          => 'Info_Box',
			'parallax'          => 'RT_Parallax',
			'image-placeholder' => 'Image_Placeholder',
		];

		foreach ( $widgets as $dirname => $class ) {
			$template_name = '/elementor-custom/' . $dirname . '/class.php';
			if ( file_exists( STYLESHEETPATH . $template_name ) ) {
				$file = STYLESHEETPATH . $template_name;
			} elseif ( file_exists( TEMPLATEPATH . $template_name ) ) {
				$file = TEMPLATEPATH . $template_name;
			} else {
				$file = __DIR__ . '/' . $dirname . '/class.php';
			}

			require_once $file;

			$classname = __NAMESPACE__ . '\\' . $class;
			Plugin::instance()->widgets_manager->register( new $classname );
		}
	}

	/**
	 * Adding custom icon to icon control in Elementor
	 */
	public function mytheme_flaticon_tab( $tabs = [] ) {
		// Append new icons
		$flat_icons = ElementorIconTrait::flaticon_icons();

		$tabs['mytheme-flaticon-icons'] = [
			'name'          => 'mytheme-flaticon-icons',
			'label'         => esc_html__( 'Flat Icons', 'mytheme-core' ),
			'labelIcon'     => 'fab fa-elementor',
			'prefix'        => '',
			'displayPrefix' => '',
			'url'           => Helper::get_css( 'flaticon' ),
			'icons'         => $flat_icons,
			'ver'           => '1.0',
		];

		return $tabs;
	}

	public function widget_category( $class ) {
		$id         = MYTHEME_CORE_THEME_PREFIX . '-widgets'; // Category /@dev
		$properties = [
			'title' => __( 'MyTheme Elements', 'mytheme-core' ),
		];

		Plugin::$instance->elements_manager->add_category( $id, $properties );
	}

}

new Custom_Widget_Init();