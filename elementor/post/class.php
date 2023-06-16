<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.2
 */

namespace radiustheme\MyTheme_Core;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name      = esc_html__( 'RT Post', 'mytheme-core' );
		$this->rt_base      = 'rt-post';
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
			'rt_post_grid',
			[
				'label' => esc_html__( 'Post Grid', 'mytheme-core' ),
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

		$this->add_control(
			'gridcolumn-popover-toggle',
			[
				'label'        => __( 'Grid Column', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off'    => __( 'Default', 'mytheme-core' ),
				'label_on'     => __( 'Custom', 'mytheme-core' ),
				'return_value' => 'yes',
			]
		);

		$this->start_popover();

		$this->add_control(
			'gird_column_desktop',
			[
				'label'   => esc_html__( 'Grid Column for Desktop', 'mytheme-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '4',
				'options' => $this->rt_translate['cols'],

			]
		);

		$this->add_control(
			'gird_column_tab',
			[
				'label'   => esc_html__( 'Grid Column for Tab', 'mytheme-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '6',
				'options' => $this->rt_translate['cols'],

			]
		);

		$this->add_control(
			'gird_column_mobile',
			[
				'label'   => esc_html__( 'Grid Column for Mobile', 'mytheme-core' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '12',
				'options' => $this->rt_translate['cols'],

			]
		);

		$this->end_popover();

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title Tag', 'the-post-grid' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => [
					'h1' => esc_html__( 'H1', 'the-post-grid' ),
					'h2' => esc_html__( 'H2', 'the-post-grid' ),
					'h3' => esc_html__( 'H3', 'the-post-grid' ),
					'h4' => esc_html__( 'H4', 'the-post-grid' ),
					'h5' => esc_html__( 'H5', 'the-post-grid' ),
					'h6' => esc_html__( 'H6', 'the-post-grid' ),
				],
			]
		);

		$this->add_control(
			'post_limit',
			[
				'label'       => __( 'Post Limit', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post Limit', 'mytheme-core' ),
				'description' => __( 'Enter number of post to show.', 'mytheme-core' ),
				'default'     => '12',
			]
		);

		$this->add_control(
			'post_source',
			[
				'label'       => __( 'Post Source', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					'most_recent' => __( 'From all recent post', 'mytheme-core' ),
					'by_category' => __( 'By Category', 'mytheme-core' ),
					'by_tags'     => __( 'By Tags', 'mytheme-core' ),
					'by_id'       => __( 'By Post ID', 'mytheme-core' ),
				],
				'default'     => [ 'most_recent' ],
				'description' => __( 'Select posts source that you like to show.', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'categories',
			[
				'label'       => __( 'Choose Categories', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->rt_category_list(),
				'label_block' => true,
				'condition'   => [
					'post_source' => 'by_category',
				],
				'description' => __( 'Select post category\'s.', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'tags',
			[
				'label'       => __( 'Choose Tags', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'multiple'    => true,
				'options'     => $this->rt_tag_list(),
				'label_block' => true,
				'condition'   => [
					'post_source' => 'by_tags',
				],
				'description' => __( 'Select post tag\'s.', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'post_id',
			[
				'label'       => __( 'Enter post IDs', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'description' => __( 'Enter the post IDs separated by comma', 'mytheme-core' ),
				'label_block' => 'true',
				'condition'   => [
					'post_source' => 'by_id',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Post offset', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post offset', 'mytheme-core' ),
				'description' => __( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => __( 'Exclude posts', 'mytheme-core' ),
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
					'date'           => __( 'Date', 'mytheme-core' ),
					'ID'             => __( 'Order by post ID', 'mytheme-core' ),
					'author'         => __( 'Author', 'mytheme-core' ),
					'title'          => __( 'Title', 'mytheme-core' ),
					'modified'       => __( 'Last modified date', 'mytheme-core' ),
					'parent'         => __( 'Post parent ID', 'mytheme-core' ),
					'comment_count'  => __( 'Number of comments', 'mytheme-core' ),
					'menu_order'     => __( 'Menu order', 'mytheme-core' ),
					'meta_value'     => __( 'Meta value', 'mytheme-core' ),
					'meta_value_num' => __( 'Meta value number', 'mytheme-core' ),
					'rand'           => __( 'Random order', 'mytheme-core' ),
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


		// Thumbnail style
		//========================================================
		$this->start_controls_section(
			'thumbnail_style',
			[
				'label' => __( 'Thumbnail Style', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'thumbnail_visibility',
			[
				'label'   => __( 'Thumbnail Visibility', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'visible' => [
						'title' => __( 'Visible', 'mytheme-core' ),
						'icon'  => 'eicon-check',
					],
					'hidden'  => [
						'title' => __( 'Hidden', 'mytheme-core' ),
						'icon'  => 'eicon-editor-close',
					],
				],
				'toggle'  => false,
				'default' => 'visible',
			]
		);

		$this->add_control(
			'project_thumbnail_size',
			[
				'label'     => esc_html__( 'Image Size', 'mytheme-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->rt_get_all_image_sizes(),
				'condition' => [
					'thumbnail_visibility' => 'visible',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => __( 'Image Height Ratio', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 30,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .blog-grid .blog-box .post-img .thumb-bg' => 'padding-bottom: {{SIZE}}%;',
				],
				'condition'  => [
					'thumbnail_visibility' => 'visible',
				],
			]
		);

		$this->add_control(
			'thumb_overlay_visibility',
			[
				'label'        => __( 'Show Thumbnail Overlay', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'thumbnail_visibility' => 'visible',
				],
			]
		);

		$this->add_control(
			'overlay_type',
			[
				'label'     => __( 'Overlay Type', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'always',
				'options'   => [
					'always'        => __( 'Show Always', 'mytheme-core' ),
					'hide-on-hover' => __( 'Hide on hover', 'mytheme-core' ),
					'show-on-hover' => __( 'Show on hover', 'mytheme-core' ),
				],
				'condition' => [
					'thumbnail_visibility'     => 'visible',
					'thumb_overlay_visibility' => 'yes',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'background',
				'label'          => __( 'Overlay BG', 'mytheme-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overlay Background', 'mytheme-core' ),
					],
				],
				'types'          => [ 'classic', 'gradient' ],
				'selector'       => '{{WRAPPER}} .blog-grid .blog-box .post-img .thumb-bg .overlay',
				'condition'      => [
					'thumbnail_visibility'     => 'visible',
					'thumb_overlay_visibility' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumb_box_radius',
			[
				'label'      => __( 'Thumbnail Radius', 'mytheme-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-post-wrapper.blog-grid .blog-box.grid-style .post-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();


		// Title Settings
		//=====================================================================
		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .blog-grid .blog-box .post-content .post-title',
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label'              => __( 'Title Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '14',
					'left'     => '',
					'isLinked' => false,
				],
			]
		);

		$this->start_controls_tabs(
			'title_style_tabs'
		);

		$this->start_controls_tab(
			'title_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .post-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'title_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => __( 'Title Hover Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .post-title a:hover' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();


		// Content Settings
		//=====================================================================

		$this->start_controls_section(
			'content_style',
			[
				'label' => __( 'Excerpt Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_visibility',
			[
				'label'   => __( 'Excerpt Visibility', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'visible' => [
						'title' => __( 'Visible', 'mytheme-core' ),
						'icon'  => 'eicon-check',
					],
					'hidden'  => [
						'title' => __( 'Hidden', 'mytheme-core' ),
						'icon'  => 'eicon-editor-close',
					],
				],
				'toggle'  => false,
				'default' => 'visible',
			]
		);


		$this->add_control(
			'content_limit',
			[
				'label'     => __( 'Excerpt Limit', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => '15',
				'condition' => [
					'content_visibility' => 'visible',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'content_typography',
				'selector'  => '{{WRAPPER}} .blog-grid .blog-box .post-excerpt',
				'condition' => [
					'content_visibility' => 'visible',
				],
			]
		);

		$this->add_control(
			'content_spacing',
			[
				'label'              => __( 'Excerpt Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'selectors'          => [
					'{{WRAPPER}} .blog-grid .blog-box .post-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
				'condition'          => [
					'content_visibility' => 'visible',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'     => __( 'Content Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-excerpt' => 'color: {{VALUE}}',
				],
				'condition' => [
					'content_visibility' => 'visible',
				],
			]
		);

		$this->end_controls_section();

		// Meta Information Settings
		//=====================================================================

		$this->start_controls_section(
			'meta_info_style',
			[
				'label' => __( 'Meta Info Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'is_dots',
			[
				'label'   => __( 'Left Dots', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'is_dots',
				'options' => [
					'is_dots' => __( 'Enable', 'mytheme-core' ),
					'no_dots' => __( 'Disable', 'mytheme-core' ),
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'post_meta_typography',
				'selector' => '{{WRAPPER}} .blog-grid .blog-box .post-content .post-meta a',
			]
		);

		$this->start_controls_tabs(
			'post_meta_style_tabs'
		);

		$this->start_controls_tab(
			'post_meta_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);


		$this->add_control(
			'post_meta_color',
			[
				'label'     => __( 'Meta Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .post-meta ul > li a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'post_meta_hover_tab',
			[
				'label' => __( 'Box Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'post_meta_color_hover',
			[
				'label'     => __( 'Meta Color Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .post-meta ul > li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'author_visibility',
			[
				'label'        => __( 'Author Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'author_avatar',
			[
				'label'     => __( 'Author Avatar Style', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'image',
				'options'   => [
					'icon'      => __( 'Author Icon', 'mytheme-core' ),
					'image'     => __( 'Author Image', 'mytheme-core' ),
					'no-avatar' => __( 'No Avatar', 'mytheme-core' ),
				],
				'condition' => [
					'author_visibility' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_bottom_visibility',
			[
				'label'        => __( 'Author Bottom Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'author_bottom_avatar',
			[
				'label'     => __( 'Author Avatar Style', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'image',
				'options'   => [
					'icon'      => __( 'Author Icon', 'mytheme-core' ),
					'image'     => __( 'Author Image', 'mytheme-core' ),
					'no-avatar' => __( 'No Avatar', 'mytheme-core' ),
				],
				'condition' => [
					'author_bottom_visibility' => 'yes',
				],
			]
		);

		$this->add_control(
			'cat_visibility',
			[
				'label'        => __( 'Category Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'date_visibility',
			[
				'label'        => __( 'Date Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'p_date_format',
			[
				'label'     => __( 'Date Format', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'time_ago',
				'options'   => [
					'default'  => __( 'Default From Settings', 'mytheme-core' ),
					'time_ago' => __( 'Time Ago Format', 'mytheme-core' ),
				],
				'condition' => [
					'date_visibility' => 'yes',
					'layout!'         => 'style2',
				],
			]
		);

		$this->add_control(
			'date_bg_color',
			[
				'label'     => __( 'Date Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .thumbnail-date .popup-date' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'date_visibility' => 'yes',
					'layout'          => 'style2',
				],
			]
		);


		$this->add_control(
			'reading_time_visibility',
			[
				'label'        => __( 'Reading Time Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'reading_suffix',
			[
				'label'       => esc_html__( 'Reading Suffix', 'mytheme-core' ),
				'type'        => Controls_Manager::TEXT,
				'description' => 'If you need, you can use reading time suffix. NB: to Read',
				'condition'   => [
					'reading_time_visibility' => 'yes'
				]
			]
		);

		$this->add_control(
			'comment_visibility',
			[
				'label'        => __( 'Comment Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);


		$this->end_controls_section();


		//Read More Style
		//=============================================================================

		$this->start_controls_section(
			'readmore_style',
			[
				'label' => __( 'Read More Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'readmore_visibility',
			[
				'label'   => __( 'Read More Visibility', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'visible' => [
						'title' => __( 'Visible', 'mytheme-core' ),
						'icon'  => 'eicon-check',
					],
					'hidden'  => [
						'title' => __( 'Hidden', 'mytheme-core' ),
						'icon'  => 'eicon-editor-close',
					],
				],
				'toggle'  => false,
				'default' => 'hidden',
			]
		);

		$this->add_control(
			'readmore_text',
			[
				'label'       => __( 'Button Text', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Read More', 'mytheme-core' ),
				'placeholder' => __( 'Type your title here', 'mytheme-core' ),
				'condition'   => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'readmore_typography',
				'selector'  => '{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn',
				'condition' => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->add_control(
			'readmore_spacing',
			[
				'label'              => __( 'Button Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'          => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->add_control(
			'readmore_padding',
			[
				'label'      => __( 'Button Padding', 'mytheme-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label'      => __( 'Radius', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn a' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->add_control(
			'show_btn_icon',
			[
				'label'        => __( 'Show Button Icon', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label'     => __( 'Choose Icon', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-angle-right',
					'library' => 'solid',
				],
				'condition' => [
					'readmore_visibility' => [ 'visible' ],
					'show_btn_icon'       => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 10,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'readmore_visibility' => [ 'visible' ],
					'show_btn_icon'       => 'yes',
				],
			]
		);

		$this->add_control(
			'icon_y_position',
			[
				'label'      => __( 'Icon Position-Y', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 20,
						'max'  => 20,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn i' => 'transform: translateY( {{SIZE}}{{UNIT}} );',
				],
				'condition'  => [
					'readmore_visibility' => [ 'visible' ],
					'show_btn_icon'       => 'yes',
				],
			]
		);

		//Button style Tabs
		$this->start_controls_tabs(
			'readmore_style_tabs', [
				'condition' => [
					'readmore_visibility' => [ 'visible' ],
				],
			]
		);

		$this->start_controls_tab(
			'readmore_style_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'readmore_color',
			[
				'label'     => __( 'Font Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_bg',
			[
				'label'     => __( 'Background Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_icon_margin',
			[
				'label'              => __( 'Icon Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => 'horizontal',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'          => [
					'readmore_visibility' => [ 'visible' ],
					'show_btn_icon'       => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'readmore_style_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'readmore_color_hover',
			[
				'label'     => __( 'Font Color hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'     => __( 'Icon Color Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_bg_hover',
			[
				'label'     => __( 'Background Color hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .item-btn:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'readmore_icon_margin_hover',
			[
				'label'              => __( 'Icon Spacing Hover', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => 'horizontal',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .blog-grid .blog-box .post-content .read-more-btn a:hover i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'          => [
					'readmore_visibility' => [ 'visible' ],
					'show_btn_icon'       => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Post Card Styles
		 */
		$this->start_controls_section(
			'post_card_style',
			[
				'label' => __( 'Post Card Style', 'mytheme-core' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .rt-el-post-wrapper.blog-grid .blog-box.grid-style',
			]
		);

		$this->add_control(
			'main_box_radius',
			[
				'label'      => __( 'Main Box Radius', 'mytheme-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-post-wrapper.blog-grid .blog-box.grid-style' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$data = $this->get_settings();

		$template = 'view-1';
		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		} elseif ( 'style3' == $data['layout'] ) {
			$template = 'view-3';
		}
		$this->rt_template( $template, $data );
	}

}