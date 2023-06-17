<?php
/**
 * @author  MyTheme
 * @since   1.0
 * @version 1.2
 */

namespace MyTheme\MyTheme_Core;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RT_Agent extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name      = esc_html__( 'RT Agents', 'mytheme-core' );
		$this->rt_base      = 'rtcl-agent';
		$this->rt_translate = [
			'cols' => [
				'3'  => __( '4 Columns', 'mytheme-core' ),
				'4'  => __( '3 Columns', 'mytheme-core' ),
				'6'  => __( '2 Columns', 'mytheme-core' ),
				'12' => __( '1 Columns', 'mytheme-core' ),
			],
		];
		parent::__construct( $data, $args );
	}

	protected function register_controls() {
		// widget title
		$this->start_controls_section(
			'rt_agent_grid',
			[
				'label' => esc_html__( 'Agent', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'layout',
			[
				'label'   => esc_html__( 'Style', 'mytheme-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style1',
				'options' => [
					'style1' => __( 'Style 1', 'mytheme-core' ),
					'style2' => __( 'Style 2', 'mytheme-core' ),
					'style3' => __( 'Style 3', 'mytheme-core' ),
				],

			]
		);

		$this->add_responsive_control(
			'gird_column',
			[
				'label'   => esc_html__( 'Grid Column', 'mytheme-core' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $this->rt_translate['cols'],
				'default' => '3',
			]
		);

		$this->add_control(
			'post_limit',
			[
				'label'       => __( 'Post Limit', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post Limit', 'mytheme-core' ),
				'description' => __( 'Enter number of post to show.', 'mytheme-core' ),
				'default'     => '4',
			]
		);

		$this->add_control(
			'post_source',
			[
				'label'       => __( 'Agent Source', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					'most_recent' => __( 'From all recent agent', 'mytheme-core' ),
					'by_agency'   => __( 'By Agency', 'mytheme-core' ),
					'by_id'       => __( 'By Agent ID', 'mytheme-core' ),
				],
				'default'     => [ 'most_recent' ],
				'description' => __( 'Select posts source that you like to show.', 'mytheme-core' ),
			]
		);


		$this->add_control(
			'agencies',
			[
				'label'       => __( 'Choose Agency', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->rt_get_agency_list(),
				'condition'   => [
					'post_source' => 'by_agency',
				],
				'label_block' => true,
			]
		);

		$this->add_control(
			'post_id',
			[
				'label'       => __( 'Enter Agent IDs', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the agent IDs separated by comma', 'mytheme-core' ),
				'label_block' => 'true',
				'condition'   => [
					'post_source' => 'by_id',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Agent offset', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post offset', 'mytheme-core' ),
				'description' => __( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => __( 'Exclude agent', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => 'true',
				'description' => __( 'Enter the post IDs separated by comma for exclude', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => __( 'Order by', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'date'          => __( 'Date', 'mytheme-core' ),
					'ID'            => __( 'Order by post ID', 'mytheme-core' ),
					'author'        => __( 'Author', 'mytheme-core' ),
					'title'         => __( 'Title', 'mytheme-core' ),
					'modified'      => __( 'Last modified date', 'mytheme-core' ),
					'parent'        => __( 'Post parent ID', 'mytheme-core' ),
					'comment_count' => __( 'Number of comments', 'mytheme-core' ),
					'menu_order'    => __( 'Menu order', 'mytheme-core' ),
					'rand'          => __( 'Random order', 'mytheme-core' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'   => __( 'Sort order', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'ASC'  => __( 'ASC', 'mytheme-core' ),
					'DESC' => __( 'DESC', 'mytheme-core' ),
				],
			]
		);

		$this->end_controls_section();


		// Title Settings
		//=====================================================================
		$this->start_controls_section(
			'thumbnail_style',
			[
				'label' => __( 'Thumbnail Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => __( 'Thumb Height', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 600,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .item-img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Title Settings
		//=====================================================================
		$this->start_controls_section(
			'name_style',
			[
				'label' => __( 'Name Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'name_typography',
				'selector' => '{{WRAPPER}} .rt-agents-wrapper .agent-name',
			]
		);

		$this->add_control(
			'name_spacing',
			[
				'label'              => __( 'Name Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$this->start_controls_tabs(
			'name_style_tabs'
		);

		$this->start_controls_tab(
			'name_style_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'name_color',
			[
				'label'     => __( 'Name Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-name a' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'name_style_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'name_color_hover',
			[
				'label'     => __( 'Name Color - Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-name a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		// Designation / Agency Settings
		//=====================================================================
		$this->start_controls_section(
			'agency_style',
			[
				'label' => __( 'Agency / Designation Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'agency_typography',
				'selector' => '{{WRAPPER}} .rt-agents-wrapper .item-subtitle',
			]
		);

		$this->add_control(
			'agency_spacing',
			[
				'label'              => __( 'Agency Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .rt-agents-wrapper .item-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$this->start_controls_tabs(
			'agency_style_tabs'
		);

		$this->start_controls_tab(
			'agency_style_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'agency_color',
			[
				'label'     => __( 'Agency Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .item-subtitle a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'agency_style_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'agency_color_hover',
			[
				'label'     => __( 'Agency Color - Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .item-subtitle a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		// Contact Style
		//=====================================================================
		$this->start_controls_section(
			'contact_style',
			[
				'label' => __( 'Contact Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_contact',
			[
				'label'        => __( 'Show Contact', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'contact_typography',
				'selector'  => '{{WRAPPER}} .rt-agents-wrapper .item-contact .item-phn-no',
				'condition' => [
					'show_contact' => 'yes',
				],
			]
		);

		$this->add_control(
			'contact_color',
			[
				'label'     => __( 'Contact Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .item-contact .item-phn-no' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_contact' => 'yes',
				],
			]
		);

		$this->add_control(
			'contact_icon_color',
			[
				'label'     => __( 'Contact Icon Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .item-content .item-contact .item-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_contact' => 'yes',
				],
			]
		);

		$this->end_controls_section();


		// Thumb Meta Style
		//=====================================================================
		$this->start_controls_section(
			'thumb_meta_style',
			[
				'label'     => __( 'Thumb Meta Style', 'mytheme-core' ),
				'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'show_thumb_meta',
			[
				'label'        => __( 'Show Thumb Meta', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'thumb_meta_typography',
				'selector'  => '{{WRAPPER}} .rt-agents-wrapper .item-category',
				'condition' => [
					'show_thumb_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumb_meta_color',
			[
				'label'     => __( 'Thumb Meta Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .item-category' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_thumb_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumb_meta_bg',
			[
				'label'     => __( 'Thumb Meta Background', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .item-category' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'show_thumb_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumb_meta_dot_color',
			[
				'label'     => __( 'Thumb Meta Dot color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .category-box .item-category:after' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'show_thumb_meta' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		// Contact Style
		//=====================================================================
		$this->start_controls_section(
			'social_icon_style',
			[
				'label' => __( 'Social Icon Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_social_cion',
			[
				'label'        => __( 'Show Social Icon', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'social_icon_size',
			[
				'label'      => __( 'Icon Size', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 60,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'{{WRAPPER}} .agent-block .social-icon a' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'show_social_cion' => 'yes',
				],
			]
		);


		$this->start_controls_tabs(
			'social_icon_style_tabs',
			[
				'condition' => [
					'show_social_cion' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'social_icon_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'top_icon_color',
			[
				'label'     => __( 'Top Icon Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'top_icon_bg',
			[
				'label'     => __( 'Top Icon Background', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_color',
			[
				'label'     => __( 'Social Icon Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon ~ a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color',
			[
				'label'     => __( 'Social Icon Background', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon ~ a' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style3',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'social_icon_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'top_icon_color_hover',
			[
				'label'     => __( 'Top Icon Color - Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'top_icon_bg_hover',
			[
				'label'     => __( 'Top Icon Background - Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon:hover' => 'background-color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'social_icon_color_hover',
			[
				'label'     => __( 'Social Icon Color Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon ~ a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'social_icon_bg_color_hoer',
			[
				'label'     => __( 'Social Icon Background Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .rt-agents-wrapper .agent-block .social-icon a.social-hover-icon ~ a:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style3',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render content
	 */
	protected function render() {
		$data               = $this->get_settings();
		$data['query_args'] = $this->query_args( $data );
		$template           = 'view-1';
		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		} elseif ( 'style3' == $data['layout'] ) {
			$template = 'view-3';
		}
		if ( class_exists( 'RtclStore\Models\Store' ) ) {
			$this->rt_template( $template, $data );
		}
	}

	/**
	 * Agent Query Args
	 *
	 * @param $data
	 *
	 * @return array
	 */
	protected function query_args( $data ) {
		$args = [
			'post_type'      => 'rtcl_agent',
			'posts_per_page' => $data['post_limit'],
			'post_status'    => 'publish',
		];
		if ( $data['orderby'] ) {
			$args['orderby'] = $data['orderby'];
		}
		if ( $data['order'] ) {
			$args['order'] = $data['order'];
		}

		if ( $data['post_source'] == 'by_id' && $data['post_id'] ) :
			$post_ids         = explode( ',', $data['post_id'] );
			$args['post__in'] = $post_ids;
		endif;

		if ( $data['post_source'] == 'by_agency' && ! empty( $data['agencies'] ) ) :
			$users     = get_users( [
				'meta_key'   => '_rtcl_store_id',
				'meta_value' => $data['agencies'],
				'fields'     => 'id',
			] );
			$agent_ids = [];
			foreach ( $users as $user_id ) {
				$agent_ids[] = get_user_meta( $user_id, '_rtcl_agent_id', true );
			}
			if ( $data['exclude'] ) {
				$excluded_ids = explode( ',', $data['exclude'] );
				$agent_ids    = array_diff( $agent_ids, $excluded_ids );
			}
			$args['post__in'] = $agent_ids;
		elseif ( $data['exclude'] ) :
			$excluded_ids         = explode( ',', $data['exclude'] );
			$args['post__not_in'] = $excluded_ids;
		endif;


		if ( $data['offset'] ) {
			$args['offset'] = $data['offset'];
		}

		return $args;
	}

}