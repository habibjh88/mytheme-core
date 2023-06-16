<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\MyTheme_Core;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit;

class Title_Animated extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'Animated Title', 'mytheme-core' );
		$this->rt_base = 'rt-title-animated';
		parent::__construct( $data, $args );
	}

    protected function register_controls() {

        $this->start_controls_section(
            'sec_general',
            [
                'label' => esc_html__( 'General', 'mytheme-core' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'mytheme-core' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Find your dream home' , 'mytheme-core' ),
            ]
        );

        $this->add_control(
            'items',
            [
                'label'     => esc_html__( 'Titles', 'mytheme-core' ),
                'type'      => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_title' => esc_html__( 'Title #1', 'mytheme-core' ),
                    ],
                ],
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => esc_html__( 'Subtitle', 'mytheme-core' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Lorem Ipsum has been standard daand scrambled. Rimply dummy text of the printing and typesetting industry',
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => __( 'Alignment', 'mytheme-core' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'mytheme-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __( 'Center', 'mytheme-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'mytheme-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .section-heading' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'sec_animation',
            [
                'label' => esc_html__( 'Animation Options', 'mytheme-core' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'typejs_cursor',
            [
                'label' => esc_html__( 'Show Cursor', 'mytheme-core' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on'    => esc_html__( 'On', 'mytheme-core' ),
                'label_off'   => esc_html__( 'Off', 'mytheme-core' ),
                'default'     => '',
            ]
        );

        $this->add_control(
            'typejs_speed',
            [
                'label'     => esc_html__( 'Speed', 'mytheme-core' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 80,
                'description' => esc_html__( 'Speed in milliseconds', 'mytheme-core' ),
            ]
        );

        $this->add_control(
            'typejs_loop',
            [
                'label' => esc_html__( 'Loop', 'mytheme-core' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on'    => esc_html__( 'On', 'mytheme-core' ),
                'label_off'   => esc_html__( 'Off', 'mytheme-core' ),
                'default'     => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'sec_color',
            [
                'label' => esc_html__( 'Color', 'mytheme-core' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'type'  => Controls_Manager::COLOR,
                'label' => esc_html__('Title', 'mytheme-core'),
                'selectors' => [
                    '{{WRAPPER}} .section-heading .heading-title' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'type'  => Controls_Manager::COLOR,
                'label' => esc_html__('Subtitle', 'mytheme-core'),
                'selectors' => [
                    '{{WRAPPER}} .section-heading .heading-subtitle' => 'color: {{VALUE}}'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
          'sec_typo',
          [
              'label'   => esc_html__('Typography', 'mytheme-core'),
              'tab'     => Controls_Manager::TAB_STYLE
          ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__('Title', 'mytheme-core'),
                'selector' => '{{WRAPPER}} .section-heading .heading-title',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'subtitle_typo',
                'label' => esc_html__('Subtitle', 'mytheme-core'),
                'selector' => '{{WRAPPER}} .section-heading .heading-subtitle',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'sec_spacing',
            [
                'label'   => esc_html__('Spacing', 'mytheme-core'),
                'tab'     => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label'      => __( 'Margin', 'mytheme-core' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function rt_get_titles( $data ) {
        $result = array();
        foreach ( $data['items'] as $item ) {
            $title = trim( $item['title'] );
            if ( $title ) {
                $result[] = $title;
            }
        }

        return $result;
    }

	protected function render() {
		$data = $this->get_settings();

        $options = array(
            'strings'     => $this->rt_get_titles( $data ),
            'typeSpeed'   => $data['typejs_speed'] ? $data['typejs_speed'] : 30,
            'loop'        => $data['typejs_loop'] == 'yes' ? true : false,
            'showCursor'  => $data['typejs_cursor'] == 'yes' ? true : false,
            'contentType' => null,
        );

        $data['options'] = json_encode( $options );

        wp_enqueue_script( 'typed' );

        $template = 'view-1';

		return $this->rt_template( $template, $data );
	}
}