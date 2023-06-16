<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\MyTheme_Core;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Rtcl\Helpers\Functions;
use \WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RT_Properties extends Custom_Widget_Base {

	public function __construct( $data = [], $args = null ) {
		$this->rt_name = esc_html__( 'RT Properties', 'mytheme-core' );
		$this->rt_base = 'rt-properties-type-tab';

		$this->rt_translate = [
			'cols'        => [
				'12' => __( '1 Columns', 'mytheme-core' ),
				'6'  => __( '2 Columns', 'mytheme-core' ),
				'4'  => __( '3 Columns', 'mytheme-core' ),
				'3'  => __( '4 Columns', 'mytheme-core' ),
			],
			'slider_cols' => [
				'1' => __( '1 Columns', 'mytheme-core' ),
				'2' => __( '2 Columns', 'mytheme-core' ),
				'3' => __( '3 Columns', 'mytheme-core' ),
				'4' => __( '4 Columns', 'mytheme-core' ),
				'5' => __( '5 Columns', 'mytheme-core' ),
				'6' => __( '6 Columns', 'mytheme-core' ),
				'7' => __( '7 Columns', 'mytheme-core' ),
				'8' => __( '8 Columns', 'mytheme-core' ),
			],
		];

		parent::__construct( $data, $args );
	}

	protected function register_controls() {
		$terms             = get_terms( [ 'taxonomy' => 'rtcl_category', 'fields' => 'id=>name' ] );
		$category_dropdown = [];

		$type_dropdown = Functions::get_listing_types();

		foreach ( $terms as $id => $name ) {
			$category_dropdown[ $id ] = $name;
		}

		$this->start_controls_section(
			'sec_general',
			[
				'label' => esc_html__( 'General', 'mytheme-core' ),
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
					'style1' => __( 'Style 1 - Grid', 'mytheme-core' ),
					'style2' => __( 'Style 2 - Thumb', 'mytheme-core' ),
					'style3' => __( 'Style 3 - Tab', 'mytheme-core' ),
					'style4' => __( 'Style 4 - Extra padding', 'mytheme-core' ),
					'style5' => __( 'Style 5 - List', 'mytheme-core' ),
					'style6' => __( 'Style 6 - Without Thumb', 'mytheme-core' ),
					'style7' => __( 'Style 7 - Slider', 'mytheme-core' ),
					'style8' => __( 'Style 8 - Slider-2', 'mytheme-core' ),
					'style9' => __( 'Style 9 - Info', 'mytheme-core' ),
				],
			]
		);

		$this->add_responsive_control(
			'gird_column_desktop',
			[
				'label'     => esc_html__( 'Grid Column', 'mytheme-core' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->rt_translate['cols'],
				'default'   => '4',
				'condition' => [
					'layout!' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->add_responsive_control(
			'slider_column_lg_desktop',
			[
				'label'          => esc_html__( 'Slider Column', 'mytheme-core' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => $this->rt_translate['slider_cols'],
				'condition'      => [
					'layout' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->add_control(
			'type',
			[
				'type'     => Controls_Manager::SELECT2,
				'label'    => esc_html__( 'Type', 'mytheme-core' ),
				'options'  => $type_dropdown,
				'multiple' => true,
			]
		);

		$this->add_control(
			'cat',
			[
				'type'       => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Categories', 'mytheme-core' ),
				'options'    => $category_dropdown,
				'multiple'   => true,
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'promotions_product',
			[
				'label'     => __( 'Filter Promotions Product', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => __( '--Select--', 'mytheme-core' ),
					'_top'     => __( 'Top Product', 'mytheme-core' ),
					'featured' => __( 'Featured Product', 'mytheme-core' ),
					'_bump_up' => __( 'Bump Up Product', 'mytheme-core' ),
				],
				'condition' => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label'       => esc_html__( 'Posts Per Page', 'mytheme-core' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '8',
				'description' => esc_html__( 'Write -1 to show all', 'mytheme-core' ),
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'ids',
			[
				'label'       => esc_html__( "Posts ID's, seperated by commas", 'mytheme-core' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => [
					'type' => [ 'custom' ],
				],
				'description' => __( "Put the comma seperated ID's here eg. 23,26,89", 'mytheme-core' ),
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => __( 'Post offset', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Post offset', 'mytheme-core' ),
				'description' => __( 'Number of post to displace or pass over. The offset parameter is ignored when post limit => -1 (show all posts) is used.', 'mytheme-core' ),
				'condition'   => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => __( 'Exclude posts', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'label_block' => 'true',
				'description' => __( 'Enter the post IDs separated by comma for exclude', 'mytheme-core' ),
				'condition'   => [
					'layout!' => 'style3',
				],
			]
		);

		$this->add_control(
			'random',
			[
				'label'        => esc_html__( 'Change items on every page load', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'default'      => false,
				'return_value' => 'yes',
				'conditions'   => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
					],
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'type'       => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Order By', 'mytheme-core' ),
				'options'    => [
					'date'  => __( 'Date (Recents comes first)', 'mytheme-core' ),
					'title' => __( 'Title', 'mytheme-core' ),
				],
				'default'    => 'date',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
						[
							'name'     => 'random',
							'operator' => '!==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'order',
			[
				'type'       => Controls_Manager::SELECT2,
				'label'      => esc_html__( 'Sort By', 'mytheme-core' ),
				'options'    => [
					'asc'  => esc_html__( 'Ascending', 'mytheme-core' ),
					'desc' => esc_html__( 'Descending', 'mytheme-core' ),
				],
				'default'    => 'asc',
				'conditions' => [
					'terms' => [
						[
							'name'     => 'type',
							'operator' => '!==',
							'value'    => 'custom',
						],
						[
							'name'     => 'random',
							'operator' => '!==',
							'value'    => 'yes',
						],
					],
				],
			]
		);


		$this->add_control(
			'more_options',
			[
				'label'     => __( 'Other Options', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'cat_display',
			[
				'label'        => esc_html__( 'Category Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'category_position',
			[
				'label'     => __( 'Category Position', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default'          => __( 'Default', 'mytheme-core' ),
					'cat-before-title' => __( 'Above Title', 'mytheme-core' ),
				],
				'condition' => [
					'cat_display' => 'yes',
					'layout'      => [ 'style1', 'style7', 'style8' ],
				],
			]
		);

		$this->add_control(
			'content_visibility',
			[
				'label'        => esc_html__( 'Content Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'layout!' => [ 'style2' ],
				],
			]
		);

		$this->add_control(
			'content_limit',
			[
				'label'     => esc_html__( 'Content Limit', 'mytheme-core' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 30,
				'condition' => [
					'content_visibility' => 'yes',
					'layout!'            => [ 'style2' ],
				],
			]
		);

		$this->add_control(
			'field_display',
			[
				'label'        => esc_html__( 'Properties Info Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hide_price_range',
			[
				'label'        => esc_html__( 'Hide Price Range', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'hide',
				'default'      => 'hide',
				'prefix_class' => 'is-price-range-',
			]
		);
		$this->add_control(
			'hide_price_meta',
			[
				'label'        => esc_html__( 'Hide Price Meta', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'hide',
				'default'      => false,
				'prefix_class' => 'is-price-meta-',
			]
		);

		$this->add_control(
			'info_style',
			[
				'label'   => __( 'Info Style', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'space-between',
				'options' => [
					'space-between'       => __( 'Space Between (with BG)', 'mytheme-core' ),
					'list-view'           => __( 'List View (with BG)', 'mytheme-core' ),
					'space-between-no-bg' => __( 'Space Between (without BG)', 'mytheme-core' ),
					'list-view-no-bg'     => __( 'List View (without BG)', 'mytheme-core' ),
				],
			]
		);

		$this->add_control(
			'listing_thumb_visibility',
			[
				'label'        => esc_html__( 'Thumbnail Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				//				'condition'    => [
				//					'layout!' => 'style2',
				//				],
			]
		);

		$this->add_control(
			'listing_action_visibility',
			[
				'label'        => esc_html__( 'Action Button Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'location_visibility',
			[
				'label'        => esc_html__( 'Location Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'date_visibility',
			[
				'label'        => esc_html__( 'Date Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'views_display',
			[
				'label'        => esc_html__( 'Post View Count Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'label_display',
			[
				'label'        => esc_html__( 'Label Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'label_position',
			[
				'label'        => __( 'Label Position', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'thumb',
				'options'      => [
					'thumb'       => __( 'In Thumb', 'mytheme-core' ),
					'below_title' => __( 'Below Title', 'mytheme-core' ),
				],
				'condition'    => [
					'label_display' => 'yes',
					'layout!'       => [ 'style2', 'style5', 'style6', 'style9' ],
				],
				'render_type'  => 'template',
				'prefix_class' => 'label_position_',
			]
		);

		$this->add_control(
			'type_visibility',
			[
				'label'        => esc_html__( 'Product Type Visibility', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hanging_visibility',
			[
				'label'     => __( 'Type Hanger Visibility', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::SELECT,
				'default'   => 'visible',
				'options'   => [
					'visible'       => __( 'Visible', 'mytheme-core' ),
					'hanger-hidden' => __( 'Hidden', 'mytheme-core' ),
				],
				'condition' => [
					'type_visibility' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_display',
			[
				'label'        => esc_html__( 'Display Author Name', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'author_position',
			[
				'label'        => __( 'Author Position', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default'  => __( 'Default', 'mytheme-core' ),
					'in_thumb' => __( 'In Thumb', 'mytheme-core' ),
				],
				'condition'    => [
					'author_display' => 'yes',
					'layout'         => 'style1',
				],
				'prefix_class' => 'rt_author_position_',
				'render_type'  => 'template',
			]
		);

		$this->add_control(
			'author_prefix',
			[
				'label'       => __( 'Author Prefix', 'mytheme-core' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'By', 'mytheme-core' ),
				'placeholder' => __( 'Type author prefix here', 'mytheme-core' ),
				'condition'   => [
					'author_display' => 'yes',
					'layout!'        => 'style2',
				],
			]
		);

		$this->add_control(
			'show_listing_footer',
			[
				'label'        => esc_html__( 'Show/Hide Footer', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => true,
			]
		);

		$this->add_control(
			'isotope_enable',
			[
				'label'        => esc_html__( 'Enable Isotope', 'mytheme-core' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'mytheme-core' ),
				'label_off'    => esc_html__( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => true,
				'condition'    => [
					'layout' => [ 'style1', 'style4', 'style5' ],
				],
			]
		);

		$this->end_controls_section();

		/*
		 * Additional Settings
		 * ===========================================
		 */
		$this->start_controls_section(
			'additional_settings',
			[
				'label' => esc_html__( 'Additional Settings', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

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
			'thumbnail_source',
			[
				'label'   => __( 'Thumbnail Source', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slider',
				'options' => [
					'slider'    => __( 'From Gallery - Slider', 'mytheme-core' ),
					'thumbnail' => __( 'From Thumbnail - Image', 'mytheme-core' ),
				],
			]
		);

		$this->add_control(
			'thumbnail_size',
			[
				'label'   => __( 'Thumbnail Size', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => $this->rt_get_all_image_sizes(),
			]
		);


		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => __( 'Thumbnail Height', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 150,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 10,
						'max' => 300,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 60,
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-thumb .thumbnail-bg' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'thumb_overlay_height',
			[
				'label'      => __( 'Overlay Height', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'max'  => 500,
						'step' => 5,
					],
					'%'  => [
						'min' => 10,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-box .product-thumb:before' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->add_control(
			'box_border_radius',
			[
				'label'        => __( 'Border Radius', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'mytheme-core' ),
					'disbale' => __( 'Disbale', 'mytheme-core' ),
				],
				'prefix_class' => 'listing-border-radius-',
			]
		);

		$this->add_control(
			'listing_title_wrap',
			[
				'label'        => __( 'Title Wrap', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'mytheme-core' ),
					'disbale' => __( 'Disbale', 'mytheme-core' ),
				],
				'prefix_class' => 'listing-title-wrap-',
			]
		);

		$this->add_control(
			'listing_border',
			[
				'label'        => __( 'Listing Border', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'enable',
				'options'      => [
					'enable'  => __( 'Enable', 'mytheme-core' ),
					'disbale' => __( 'Disbale', 'mytheme-core' ),
				],
				'prefix_class' => 'listing-wrap-border-',
				'condition'    => [
					'layout' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->start_controls_tabs(
			'additional_tabs'
		);

		$this->start_controls_tab(
			'additional_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box',
			]
		);


		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'background_overlay',
				'label'          => __( 'Thumbnail Overlay Background', 'mytheme-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Image Overlay Type', 'mytheme-core' ),
					],
				],
				'types'          => [ 'gradient' ],
				'selector'       => '{{WRAPPER}} .rt-el-listing-wrapper .product-box .product-thumb:before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'box_border',
				'label'    => __( 'Box Border', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'additional_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'box_shadow_hover',
				'label'    => __( 'Box Shadow Hover', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box:hover',
			]
		);


		$this->add_control(
			'background_overlay_title_hover',
			[
				'label' => __( 'Background Overlay Hover:', 'mytheme-core' ),
				'type'  => \Elementor\Controls_Manager::RAW_HTML,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'           => 'hover_background_overlay',
				'label'          => __( 'Thumbnail Overlay Background Hover', 'mytheme-core' ),
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Image Overlay Type - Hover', 'mytheme-core' ),
					],
				],
				'types'          => [ 'gradient' ],
				'selector'       => '{{WRAPPER}} .rt-el-listing-wrapper .product-box:hover .product-thumb:before',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'box_border_hover',
				'label'    => __( 'Box Border - Hover', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->end_controls_section();


		//Carousel Settings
		//=======================================
		$this->start_controls_section(
			'carousel_settings',
			[
				'label'     => esc_html__( 'Carousel Settings', 'mytheme-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => [ 'style7', 'style8' ],
				],
			]
		);

		$this->add_control(
			'slider_animation',
			[
				'label'   => __( 'Slider Animation', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => __( 'Slide', 'mytheme-core' ),
					'fade'  => __( 'Fade', 'mytheme-core' ),
				],
			]
		);

		$this->add_control(
			'item_overflow',
			[
				'label'        => __( 'Slider Overflow', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'hidden',
				'options'      => [
					'none'   => __( 'None', 'mytheme-core' ),
					'hidden' => __( 'Hidden', 'mytheme-core' ),
				],
				'prefix_class' => 'list-carousel-overflow-',
			]
		);


		$this->add_control(
			'arrows',
			[
				'label'        => __( 'Arrow', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'dots',
			[
				'label'        => __( 'Dots', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'draggable',
			[
				'label'        => __( 'Draggable', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'On', 'mytheme-core' ),
				'label_off'    => __( 'Off', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'        => __( 'Infinite', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'mytheme-core' ),
				'label_off'    => __( 'No', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => __( 'Autoplay', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'mytheme-core' ),
				'label_off'    => __( 'No', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->add_control(
			'autoplaySpeed',
			[
				'label'     => __( 'Autoplay Speed', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'min'       => 1000,
				'max'       => 5000,
				'step'      => 500,
				'default'   => 3000,
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'centeredSlides',
			[
				'label'        => __( 'Centered Slides', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'mytheme-core' ),
				'label_off'    => __( 'No', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
				'description'  => __( 'If you use centered slider options then default column will not working.', 'mytheme-core' ),
			]
		);


		$this->add_responsive_control(
			'slider_min_width',
			[
				'label'      => __( 'Min Item Width', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 300,
						'max'  => 450,
						'step' => 5,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 360,
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-slide' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
				'condition'  => [
					'centeredSlides' => 'yes',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => __( 'Speed', 'mytheme-core' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'min'     => 100,
				'max'     => 3000,
				'step'    => 100,
				'default' => 300,
			]
		);

		$this->add_control(
			'arrow_style_heading',
			[
				'label'     => __( 'Arrow Style', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_visibility',
			[
				'label'        => __( 'Arrow Visibility', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default'  => __( 'Always Show', 'mytheme-core' ),
					'on-hover' => __( 'Show on Hover', 'mytheme-core' ),
				],
				'condition'    => [
					'arrows' => 'yes',
				],
				'prefix_class' => 'listing-arrow-visibility-'
			]
		);

		$this->add_responsive_control(
			'arrow_border_radius',
			[
				'label'      => __( 'Arrow Radius', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'arrow_width_height',
			[
				'label'      => __( 'Arrow Width/Height', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height:{{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'new_arrow_position',
			[
				'label'      => __( 'Arrow X Position', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 300,
						'max'  => 300,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-prev'      => 'left: {{SIZE}}{{UNIT}};right:auto;',
					'{{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-next'      => 'right: {{SIZE}}{{UNIT}};left:auto;',
					'.rtl {{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-prev' => 'right: {{SIZE}}{{UNIT}};left:auto;',
					'.rtl {{WRAPPER}} .rt-el-listing-wrapper .elementor-swiper-button-next' => 'left: {{SIZE}}{{UNIT}};right:auto;',
				],
				'condition'  => [
					'arrows' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'arrow_style_tabs',
			[
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->start_controls_tab(
			'arrow_style_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Icon Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_arrow_bg_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Background', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button i' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'arrow_style_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Icon Color Hover', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button:hover i'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);

		$this->add_control(
			'arrow_bg_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Arrow Background Hover', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .list-slick-carousel .elementor-swiper-button:hover i'     => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active i' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'arrows' => 'yes',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_control(
			'dot_style_heading',
			[
				'label'     => __( 'Dots Style', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_text_align',
			[
				'label'        => __( 'Dots Alignment', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
				'options'      => [
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
				'default'      => 'center',
				'toggle'       => true,
				'condition'    => [
					'dots' => 'yes',
				],
				'prefix_class' => 'dots-align-',
			]
		);

		$this->add_control(
			'dots_button_style',
			[
				'label'        => __( 'Dot Style', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SELECT,
				'default'      => 'default',
				'options'      => [
					'default'  => __( 'Default', 'mytheme-core' ),
					'creative' => __( 'Creative', 'mytheme-core' ),
				],
				'condition'    => [
					'dots' => 'yes',
				],
				'prefix_class' => 'carousel-dots-',
			]
		);

		$this->add_responsive_control(
			'dots_border_radius',
			[
				'label'      => __( 'Dots Radius', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_width_height',
			[
				'label'      => __( 'Dots Width/Height', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'dots_position',
			[
				'label'      => __( 'Dots Y Position', 'mytheme-core' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => - 100,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'dots' => 'yes',
				],
			]
		);

		$this->start_controls_tabs(
			'dots_style_tabs'
		);

		$this->start_controls_tab(
			'dots_style_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'dots_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dots Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'dots' => 'yes',
				],
			]
		);

		$this->add_control(
			'dots_border_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dots Border Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'dots'              => 'yes',
					'dots_button_style' => 'creative',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'dots_style_hover_tab',
			[
				'label' => __( 'Hover', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'dots_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Hover/Active Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span:hover'                           => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'dots'              => 'yes',
					'dots_button_style' => 'creative',
				],
			]
		);

		$this->add_control(
			'dots_border_color_hover',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Dots Border Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span.swiper-pagination-bullet-active' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .swiper-pagination span:hover'                           => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'dots'              => 'yes',
					'dots_button_style' => 'creative',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'is_shadow',
			[
				'label'        => __( 'Enable Slider Overlay', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'mytheme-core' ),
				'label_off'    => __( 'No', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'prefix_class' => 'is-shadow-',
			]
		);

		$this->add_control(
			'shadow_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Shadow Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper.style8::before' => 'background: {{VALUE}}; background: linear-gradient(90deg, {{VALUE}} 0%, transparent 100%);',
					'{{WRAPPER}} .rt-el-listing-wrapper.style8::after'  => 'background: {{VALUE}}; background: linear-gradient(-90deg, {{VALUE}} 0%, transparent 100%);',
				],
				'condition' => [
					'is_shadow' => 'yes',
				],
			]
		);

		$this->end_controls_section();


		/*
		 * Filter Settings
		 * ===========================================
		 */
		$this->start_controls_section(
			'filter_settings',
			[
				'label'     => esc_html__( 'Filter Settings', 'mytheme-core' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout' => 'style3',
				],
			]
		);

		$this->add_responsive_control(
			'filter_text_align',
			[
				'label'     => __( 'Alignment', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
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
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .filter-wrapper' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'show_all_btn',
			[
				'label'        => __( 'Show All Button', 'mytheme-core' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'mytheme-core' ),
				'label_off'    => __( 'Hide', 'mytheme-core' ),
				'return_value' => 'yes',
				'default'      => false,
			]
		);

		$this->start_controls_tabs(
			'filter_style_tabs'
		);

		$this->start_controls_tab(
			'filter_style_normal_tab',
			[
				'label' => __( 'Normal', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'filter_color',
			[
				'label'     => __( 'Color', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filter_background',
			[
				'label'     => __( 'Background', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item'         => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .isotope-classes-tab .nav-item::before' => 'border-top-color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab(
			'filter_style_hover_tab',
			[
				'label' => __( 'Hover/Active', 'mytheme-core' ),
			]
		);

		$this->add_control(
			'filter_color_hover',
			[
				'label'     => __( 'Color on Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item:hover'   => 'color: {{VALUE}}',
					'{{WRAPPER}} .isotope-classes-tab .nav-item.current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'filter_background_hover',
			[
				'label'     => __( 'Background on Hover', 'mytheme-core' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item:hover, {{WRAPPER}} .isotope-classes-tab .nav-item.current'                 => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .isotope-classes-tab .nav-item:hover::before, {{WRAPPER}} .isotope-classes-tab .nav-item.current::before' => 'border-top-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();


		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => 'filter_typography',
				'label'    => __( 'Filter Typography', 'mytheme-core' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .isotope-classes-tab .nav-item',
			]
		);


		$this->add_responsive_control(
			'filter_padding',
			[
				'label'      => __( 'Button Padding', 'mytheme-core' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'default'    => [
					'top'      => '6',
					'right'    => '40',
					'bottom'   => '6',
					'left'     => '40',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .isotope-classes-tab .nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_spacing',
			[
				'label'              => __( 'Spacing', 'mytheme-core' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => [ 'px' ],
				'allowed_dimensions' => 'vertical',
				'default'            => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '30',
					'left'     => '',
					'isLinked' => false,
				],
				'selectors'          => [
					'{{WRAPPER}} .filter-wrapper .isotope-classes-tab' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
		//End Filter Settings

		$this->start_controls_section(
			'sec_color',
			[
				'label' => esc_html__( 'Color', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .rt-main-title a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'title_hover_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Title Hover', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .rt-main-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Content Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-content .listing-content' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Price', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-price'                    => 'color: {{VALUE}}',
					'{{WRAPPER}} .rt-el-listing-wrapper .product-price .rtcl-price-amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'cat_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Category', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-category a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Meta', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .entry-meta' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Meta Icon', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .entry-meta i' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Icon', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-bottom-content .action-btn a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'custom_field_icon_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Custom Field Icon', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-features li i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'custom_field_text_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Custom Field Text', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-features li .listable-value' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'author_name_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Author Name', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-content .product-bottom-content .media .item-title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sec_label_color',
			[
				'label' => esc_html__( 'Label Color', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'type_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Type Background', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-type > span' => 'background-image: linear-gradient(to right, {{VALUE}}, {{VALUE}})',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'type_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Type Text Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .rt-el-listing-wrapper .product-type > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'featured_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Featured Background', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .feature-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'featured_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Featured Text Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .feature-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'new_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'New Background', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .new-badge' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'new_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'New Text Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .new-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'top_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Top Background', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .top-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'top_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Top Text Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .top-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'popular_label_bg',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Popular Background', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .popular-badge' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->add_control(
			'popular_label_color',
			[
				'type'      => Controls_Manager::COLOR,
				'label'     => esc_html__( 'Popular Text Color', 'mytheme-core' ),
				'selectors' => [
					'{{WRAPPER}} .product-grid .product-box .product-thumb .product-type .popular-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'layout!' => 'style2',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'sec_typo',
			[
				'label' => esc_html__( 'Typography', 'mytheme-core' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'label'    => esc_html__( 'Title', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .rt-el-listing-wrapper.product-grid .product-box .item-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_typo',
				'label'    => esc_html__( 'Content Typography', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .product-grid .product-box .product-content .listing-content',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'price_type',
				'label'    => esc_html__( 'Price Typography', 'mytheme-core' ),
				'selector' => '{{WRAPPER}} .rt-el-listing-wrapper .product-box .product-price .rtcl-price-amount, {{WRAPPER}} .rt-el-listing-wrapper .product-box .product-price .rtcl-price-amount',
			]
		);


		$this->end_controls_section();
	}

	private function rt_isotope_query( $data ) {
		$result = [];

		// Post type
		$args = [
			'post_type'           => 'rtcl_listing',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => $data['number'],
		];

		// Ordering
		if ( $data['random'] ) {
			$args['orderby'] = 'rand';
		} else {
			$args['orderby'] = $data['orderby'];
			if ( $data['orderby'] == 'title' ) {
				$args['order'] = 'ASC';
			}
		}

		// Date and Meta Query results

		$args2 = [];

		if ( ! empty( $data['cat'] ) ) {
			$args2['tax_query'] = [
				[
					'taxonomy' => 'rtcl_category',
					'field'    => 'term_id',
					'terms'    => $data['cat'],
				],
			];
		}

		foreach ( $data['type'] as $key => $value ) {
			$args2['meta_query'] = [
				[
					'key'     => 'ad_type',
					'value'   => $value,
					'compare' => '=',
				],
			];

			$result[ $value ] = new WP_Query( $args + $args2 );
			$args2            = [];
		}

		return $result;
	}

	private function rt_isotope_navigation( $data ) {
		$type_list = Functions::get_listing_types();

		$navs = [];

		if ( ! empty( $type_list ) ) {
			$navs = $type_list;
		}

		$navs = apply_filters( 'classipost_isotope_navigations', $navs );

		foreach ( $navs as $key => $value ) {
			if ( ! in_array( $key, $data['type'] ) ) {
				unset( $navs[ $key ] );
			}
		}

		return $navs;
	}

	protected function render() {
		$data = $this->get_settings();

		if ( 'style7' == $data['layout'] || 'style8' == $data['layout'] ) {
			$slider_colum         = intval( ( isset( $data['slider_column_lg_desktop'] ) && $data['slider_column_lg_desktop'] ) ? $data['slider_column_lg_desktop'] : 3 );
			$slider_column_tablet = intval( ( isset( $data['slider_column_lg_desktop_tablet'] ) && $data['slider_column_lg_desktop_tablet'] )
				? $data['slider_column_lg_desktop_tablet']
				: 2 );
			$slider_column_mobile = intval( ( isset( $data['slider_column_lg_desktop_mobile'] ) && $data['slider_column_lg_desktop_mobile'] )
				? $data['slider_column_lg_desktop_mobile']
				: 1 );


			$data['slider_data'] = [
				'effect'         => 'slide',
				'loop'           => $data['infinite'] ? true : false,
				'speed'          => $data['speed'],
				'slidesPerView'  => $slider_colum,
				'spaceBetween'   => 30,
				'centeredSlides' => $data['centeredSlides'] ? true : false,
//				'navigation'     => [
//					'nextEl' => '.elementor-swiper-button-prev',
//					'prevEl' => '.elementor-swiper-button-next',
//				],
				'pagination'     => [
					'el'        => '.swiper-pagination',
					'clickable' => true,
					'type'      => 'bullets',
				],
				'breakpoints'    => [
					'50'   => [
						'slidesPerView' => $slider_column_mobile,
						'spaceBetween'  => 30,
					],
					'640'  => [
						'slidesPerView' => $slider_column_mobile,
						'spaceBetween'  => 30,
					],
					'768'  => [
						'slidesPerView' => $slider_column_tablet,
						'spaceBetween'  => 30,
					],
					'1024' => [
						'slidesPerView' => $slider_colum,
						'spaceBetween'  => 30,
					],
				],
			];


			if ( $data['autoplay'] ) {
				$data['slider_data']['autoplay'] = [
					'delay'                => $data['autoplaySpeed'],
					'disableOnInteraction' => true,
					'pauseOnMouseEnter'    => true
				];
			}
		}


		$template = 'view-1';

		if ( 'style2' == $data['layout'] ) {
			$template = 'view-2';
		} elseif ( 'style3' == $data['layout'] ) {
			$data['queries'] = $this->rt_isotope_query( $data );
			$data['navs']    = $this->rt_isotope_navigation( $data );
			if ( empty( $data['queries'] ) ) {
				echo '<div class="alert alert-info" role="alert"><i class="fa fa-info-circle"></i>';
				echo __( " Please choose 2 or more ( Ad Type ) first for showing posts", "mytheme-core" );
				echo '</div>';
			}
			$template = 'view-3';
		} elseif ( 'style4' == $data['layout'] ) {
			$template = 'view-4';
		} elseif ( 'style5' == $data['layout'] ) {
			$template = 'view-5';
		} elseif ( 'style6' == $data['layout'] ) {
			$template = 'view-6';
		} elseif ( 'style7' == $data['layout'] ) {
			$template = 'view-7';
		} elseif ( 'style8' == $data['layout'] ) {
			$template = 'view-8';
		} elseif ( 'style9' == $data['layout'] ) {
			$template = 'view-9';
		}

		$this->rt_template( $template, $data );
	}

}