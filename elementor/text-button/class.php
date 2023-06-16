<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\MyTheme_Core;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Button extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = __( 'RT Button', 'mytheme-core' );
		$this->rt_base = 'rt-btn';
		parent::__construct( $data, $args );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'sec_general',
			[
				'label' => esc_html__( 'General', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'btntext',
			[
				'type'    => Controls_Manager::TEXT,
				'label'   => esc_html__( 'Button Text', 'mytheme-core' ),
				'default' => 'LOREM IPSUM',
			]
		);

		$this->add_control(
			'btnurl',
			[
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Button URL', 'mytheme-core' ),
				'placeholder' => 'https://your-link.com',
			]
		);

		$this->add_responsive_control(
			'alignment',
			[
				'label'     => __( 'Alignment', 'mytheme-core' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'mytheme-core' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'mytheme-core' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'mytheme-core' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-btn' => 'text-align: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_box',
			[
				'label' => __( 'General', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'box_border_radius',
			[
				'label'     => __( 'Border Radius', 'mytheme-core' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 0,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .banner-btn .item-btn' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_typo',
				'label'    => esc_html__( 'Typography', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .banner-btn .item-btn',
			]
		);

		$this->start_controls_tabs( 'cat_box_style' );

		// Normal tab.
		$this->start_controls_tab(
			'box_style_normal',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		// Normal background color.
		$this->add_control(
			'box_style_normal_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'mytheme-core' ),
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .banner-btn .item-btn' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Normal Text color.
		$this->add_control(
			'box_style_normal_text_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Text Color', 'mytheme-core' ),
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .banner-btn .item-btn' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		// Hover tab.
		$this->start_controls_tab(
			'box_style_hover',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		// Hover background color.
		$this->add_control(
			'box_style_hover_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Background Color', 'mytheme-core' ),
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .banner-btn .item-btn:after' => 'background-color: {{VALUE}};',
				],
			]
		);

		// Hover Text color.
		$this->add_control(
			'box_style_hover_title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => __( 'Text Color', 'mytheme-core' ),
				'separator' => '',
				'selectors' => [
					'{{WRAPPER}} .banner-btn .item-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'sec_spacing',
			[
				'label' => esc_html__( 'Spacing', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'btn_margin',
			[
				'label'      => __( 'Margin', 'mytheme-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .banner-btn .item-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => __( 'Padding', 'mytheme-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .banner-btn .item-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view';

		return $this->rt_template( $template, $data );
	}

}